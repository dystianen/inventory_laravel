<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Yajra\Datatables\Datatables;

class SupplierController extends Controller
{
    public function fetch()
    {
        $data = Supplier::get();
        return json_encode($data);
    }
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Supplier::latest()->get();
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
        return view('supplier.index');
    }

    public function detail($id){
        $data = Supplier::findOrFail($id);
        return json_encode($data);
    }

    public function create(Request $request){
        $item = Supplier::create([
            'suppliers_code' => $request->suppliers_code,
            'suppliers_name' => $request->suppliers_name,
        ]);
        return response()->json([
            'status'    =>  'Success',
        ]);
    }

    public function update(Request $request,$id){
        $item = Supplier::findOrFail($id) ;
        $item->suppliers_name = $request->suppliers_name;
        $item->suppliers_code = $request->suppliers_code;
        $item->save();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }

    public function delete($id)
    {
        Supplier::findOrFail($id)->delete();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }
}
