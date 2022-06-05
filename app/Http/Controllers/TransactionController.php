<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Warehouse;
use App\Models\Transaction;
use stdClass;
use Yajra\Datatables\Datatables;

class TransactionController extends Controller
{
    public function Home()
    {
        $data['transaction'] = Transaction::count();
        $data['item'] = Item::count();
        $data['customer'] = Customer::count();
        $warehouse = Warehouse::with(array('transaction' => function ($query) {
            $query->where('status', 'accepted')
                ->orWhere('status', 'done');
        }))->get();
        $data['warehouse'] = count($warehouse);
        $data['wd'] = $warehouse;
        foreach ($warehouse as $element) {
            $element->stock = 0;
            foreach ($element->transaction as $key) {
                if ($key->type == 'in' || $key->type == 'order') {
                    $element->stock += $key->total;
                } else if ($key->type == 'out') {
                    $element->stock -= $key->total;
                }
            }
        }
        return view('dashboard', $data);
    }
    public function index(Request $request)
    {
        $res = [];
        $resData = [];
        $tes = [];
        $data = Transaction::with('item.unit', 'warehouse', 'user', 'checker')->orderBy('item')->orderBy('type', 'desc')->orderBy('date')->get();
        $lastItem = null;
        $lastDate = null;
        $lastType = null;
        $LeadTimeBefore = null;
        $totalBefore = 0;
        $maxSale = 0;
        $avgSale = 0;
        $sumSale = 0;
        $countIn = 0;
        $countOut = 0;
        $stock = 0;
        $avgLeadTime = 0;
        $sumLeadTime = 0;
        $indexOfRes = 0;
        $maxLeadTime = 0;
        $resObj = new stdClass();
        foreach ($data as $index => $value) {
            if ($value->item == $lastItem) {
                if ($value->date == $lastDate && $value->type == $lastType) {
                    if ($value->type == "out") {
                        $stock -= $value->total;
                        $sumSale += $value->total;
                        if ($maxSale < $value->total + $totalBefore) {
                            $maxSale = $value->total + $totalBefore;
                        }
                    }
                    if ($value->type == "in") {
                        $stock += $value->total;
                        if ($maxLeadTime < $value->lead_time + $LeadTimeBefore) {
                            $maxLeadTime = $value->lead_time + $LeadTimeBefore;
                        }
                    }
                } else {
                    $lastDate = $value->date;
                    $lastType = $value->type;
                    $totalBefore = 0;
                    if ($value->type == "out") {
                        $totalBefore = $value->total;
                        $stock -= $value->total;
                        $countOut += 1;
                        $sumSale += $value->total;
                        if ($maxSale > $value->total) {
                            $maxSale = $value->total;
                        }
                    }
                    if ($value->type == "in") {
                        $LeadTimeBefore = $value->lead_time;
                        $stock += $value->total;
                        $countIn += 1;
                        $sumLeadTime += $value->lead_time;
                        if ($maxLeadTime > $value->lead_time) {
                            $maxLeadTime = $value->lead_time;
                        }
                    }
                }
                $res[$value->item]->countIn = $countIn;
                $res[$value->item]->countOut = $countOut;
                $res[$value->item]->maxSale = $maxSale;
                $res[$value->item]->maxLeadTime = $maxLeadTime;
                $res[$value->item]->sumSale = $sumSale;
                $res[$value->item]->sumLeadTime = $sumLeadTime;
                $res[$value->item]->stock = $stock;
                if ($countOut > 0) {
                    $avgSale = $res[$value->item]->stock / $countOut;
                    $res[$value->item]->avgSale = $avgSale;
                }
                if ($countIn > 0) {
                    $avgLeadTime = $countOut / $countIn;
                    $res[$value->item]->avgLeadTime = $avgLeadTime;
                }
                $res[$value->item]->leadTimeDemand = $avgLeadTime * $avgSale;
                $res[$value->item]->safetyStock = ($maxSale * $maxLeadTime) - ($avgSale * $avgLeadTime);
            } else {
                if ($index > 0) {
                    $indexOfRes += 1;
                }
                $resObj = new stdClass();
                $stock = 0;
                $maxSale = 0;
                $sumSale = 0;
                $avgSale = 0;
                $countIn = 0;
                $countOut = 0;
                $sumLeadTime = 0;
                $avgLeadTime = 0;
                $totalBefore = 0;
                $LeadTimeBefore = 0;
                $maxLeadTime = 0;
                $lastDate = null;
                $lastType = null;
                $lastItem = $value->item;
                if ($value->type == "out") {
                    $totalBefore = $value->total;
                    $stock = -$value->total;
                    $maxSale = $value->total;
                    $sumSale = $value->total;
                }
                if ($value->type == "in") {
                    $stock = $value->total;
                    $LeadTimeBefore = $value->lead_time;
                    $maxLeadTime = $value->lead_time;
                    $sumLeadTime = $value->lead_time;
                }
                $resObj->countIn = $countIn;
                $resObj->countOut = $countOut;
                $resObj->maxSale = $maxSale;
                $resObj->sumSale = $sumSale;
                $resObj->maxLeadTime = $maxLeadTime;
                $resObj->sumLeadTime = $sumLeadTime;
                $resObj->stock = $stock;
                $resObj->avgSale = 0;
                $resObj->avgLeadTime = 0;
                $resObj->leadTimeDemand = 0;
                $resObj->safetyStock = 0;
                if ($countOut > 0) {
                    $avgSale = $sumSale / $countOut;
                    $resObj->avgSale = $avgSale;
                }
                if ($countIn > 0) {
                    $avgLeadTime = $sumLeadTime / $countIn;
                    $resObj->avgLeadTime = $avgLeadTime;
                }
                $resObj->leadTimeDemand = $avgLeadTime * $avgSale;
                $resObj->safetyStock = ($maxSale * $maxLeadTime) - ($avgSale * $avgLeadTime);
                $res[$value->item] = $resObj;
                $lastDate = $value->date;
                $lastType = $value->type;
            }
        };
        foreach ($res as $key => $value) {
            $resJson = null;
            foreach ($data as $valueData) {
                if ($key == $valueData->item) {
                    $resJson = $valueData;
                    $resJson->calculated = $value;
                    break;
                }
            }
            array_push($resData, $resJson);
        }
        if ($request->ajax()) {
            return Datatables::of($resData)
                ->addIndexColumn()
                ->make(true);
        }
        return view('transaction.index');
    }
    public function orderindex(Request $request)
    {
        $res = [];
        $resData = [];
        $tes = [];
        $data = Transaction::with('item.unit', 'warehouse', 'user', 'checker', 'supplier')->where('type', 'order')->orderBy('item')->orderBy('type', 'desc')->orderBy('date')->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '
                    <button onclick="accept(' . $row->id . ')" class="edit btn btn-success btn-sm">Accept</button >
                    <button onclick="reject(' . $row->id . ')" class="edit btn btn-danger btn-sm">Delete</button >
                        ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('transaction.order');
    }
    public function inindex(Request $request)
    {
        $res = [];
        $resData = [];
        $tes = [];
        $data = Transaction::with('item.unit', 'warehouse', 'user', 'checker')->where('type', 'in')->orderBy('item')->orderBy('type', 'desc')->orderBy('date')->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '
                    <button class="edit btn btn-info btn-sm">Detail</button >
                    <button class="edit btn btn-warning btn-sm">Edit</button >
                    <button class="edit btn btn-danger btn-sm">Delete</button >
                        ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('transaction.in');
    }
    public function outindex(Request $request)
    {
        $res = [];
        $resData = [];
        $tes = [];
        $data = Transaction::with('item.unit', 'warehouse', 'user', 'checker')->where('type', 'out')->orderBy('item')->orderBy('type', 'desc')->orderBy('date')->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '
                    <button class="edit btn btn-info btn-sm">Detail</button >
                    <button class="edit btn btn-warning btn-sm">Edit</button >
                    <button class="edit btn btn-danger btn-sm">Delete</button >
                        ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('transaction.out');
    }

    public function detail($id)
    {
        $data = Transaction::findOrFail($id);
        return json_encode($data);
    }

    public function create(Request $request)
    {
        $invoice = 'PO-' . date("Ymd") . Transaction::orderBy('created_at', 'desc')->first()->id + 1;
        $params = array(
            'invoice' => $invoice,
            'date' => $request->date,
            'item' => $request->item,
            'warehouse' => $request->warehouse,
            'total' => $request->total,
            'total_price' => $request->total * Item::find($request->item)->price,
            'type' => $request->type,
            'status' => 'pending',
            'user' => auth()->user()->id,
        );
        if ($request->input('supplier_id')) {
            $params['supplier_id'] = $request->input('supplier_id');
        }
        Transaction::create($params);
        return response()->json([
            'status'    =>  'Success',
        ]);
    }
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        if ($transaction->date) {
            $transaction->date = $request->date;
        }
        if ($transaction->item) {
            $transaction->item = $request->item;
        }
        if ($transaction->warehouse) {
            $transaction->warehouse = $request->warehouse;
        }
        if ($transaction->type) {
            $transaction->type = $request->type;
        }
        if ($transaction->total) {
            $transaction->total = $request->total;
        }
        if ($request->status) {
            $transaction->status = $request->status;
        }
        $transaction->save();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }
    public function accept(Request $request)
    {
        $transaction = Transaction::findOrFail($request->input('accept_id'));
        if ($request->input('total_accept') == $transaction->total) {
            $transaction->type = 'in';
            $transaction->status = 'done';
            $transaction->warehouse = $request->input('warehouse');
            $transaction->save();
        } else if ($request->input('total_accept') < $transaction->total) {
            $invoice = 'PO-' . date("Ymd") . Transaction::orderBy('created_at', 'desc')->first()->id + 1;
            $params = array(
                'invoice' => $invoice,
                'date' => $transaction->date,
                'item' => $transaction->item,
                'warehouse' => $transaction->warehouse,
                'total' => $request->input('total_accept'),
                'total_price' => $request->input('total_accept') * Item::find($transaction->item)->price,
                'type' => 'in',
                'status' => 'done',
                'supplier_id' => $request->input('supplier_id'),
                'user' => auth()->user()->id,
            );
            Transaction::create($params);
            $transaction->total = $transaction->total - $request->input('total_accept');
            $transaction->save();
        } else {
            return response()->json([
                'status'    =>  'Failed',
            ], 400);
        }
        return response()->json([
            'status'    =>  'Success',
        ]);
    }
    public function delete($id)
    {
        Transaction::findOrFail($id)->delete();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }
}
