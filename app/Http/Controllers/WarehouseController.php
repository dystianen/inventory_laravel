<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class WarehouseController extends Controller
{
    public function fetch()
    {
        $data = Warehouse::get();
        return json_encode($data);
    }
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Warehouse::latest()->get();
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
        return view('warehouse.index');
    }

    public function detail($id){
        $data = Warehouse::findOrFail($id);
        return json_encode($data);
    }

    public function create(Request $request){
        $item = Warehouse::create([
            'warehouse_code' => $request->warehouse_code,
            'warehouse_name' => $request->warehouse_name,
        ]);
        return response()->json([
            'status'    =>  'Success',
        ]);
    }

    public function update(Request $request,$id){
        $item = Warehouse::findOrFail($id) ;
        $item->warehouse_name = $request->warehouse_name;
        $item->warehouse_code = $request->warehouse_code;
        $item->save();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }

    public function delete($id)
    {
        Warehouse::findOrFail($id)->delete();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }
}
