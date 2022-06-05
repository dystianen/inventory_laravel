<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Yajra\Datatables\Datatables;

class ItemController extends Controller
{
    public function fetch()
    {
        $data = Item::get();
        return json_encode($data);
    }
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Item::with('category','unit')->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '
                        <button onclick="showModal('.$row['id'].')" class="edit btn btn-info btn-sm">Edit</button >
                        <button onclick="deleteData('.$row['id'].')" class="edit btn btn-danger btn-sm">Delete</button >
                        ';
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('item.index');
    }

    public function detail($id){
        $data = Item::findOrFail($id);
        return json_encode($data);
    }

    public function create(Request $request){
        $item = Item::create([
            'item_name' => $request->item_name,
            'item_category_id' => $request->item_category_id,
            'unit_id' => $request->unit_id,
            'item_code' => $request->item_code,
            'sku_number' => $request->sku_number,
            'price' => $request->price
        ]);
        return response()->json([
            'status'    =>  'Success',
        ]);
    }
    public function update(Request $request,$id){
        $item = Item::findOrFail($id) ;
        $item->item_name = $request->item_name;
        $item->item_category_id = $request->item_category_id;
        $item->unit_id = $request->unit_id;
        $item->price = $request->price;
        if($request->item_code){
            $item->item_code = $request->item_code;
        }if($request->sku_number){
            $item->sku_number = $request->sku_number;
        }
        $item->save();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }
    public function delete($id)
    {
        Item::findOrFail($id)->delete();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }
}
