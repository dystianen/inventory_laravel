<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Yajra\Datatables\Datatables;

class CustomerController extends Controller
{
    public function fetch()
    {
        $data = Customer::get();
        return json_encode($data);
    }
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Customer::latest()->get();
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
        return view('customer.index');
    }

    public function detail($id){
        $data = Customer::findOrFail($id);
        return json_encode($data);
    }

    public function create(Request $request){
        $item = Customer::create([
            'customer_code' => $request->customer_code,
            'customer_name' => $request->customer_name,
        ]);
        return response()->json([
            'status'    =>  'Success',
        ]);
    }

    public function update(Request $request,$id){
        $item = Customer::findOrFail($id) ;
        $item->customer_name = $request->customer_name;
        $item->customer_code = $request->customer_code;
        $item->save();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }

    public function delete($id)
    {
        Customer::findOrFail($id)->delete();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }
}
