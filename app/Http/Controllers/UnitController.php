<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Yajra\Datatables\Datatables;

class UnitController extends Controller
{
    public function fetch()
    {
        $data = Unit::get();
        return json_encode($data);
    }
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Unit::latest()->get();
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
        return view('unit.index');
    }

    public function detail($id){
        $data = Unit::findOrFail($id);
        return json_encode($data);
    }

    public function create(Request $request){
        $item = Unit::create([
            'unit_code' => $request->unit_code,
            'unit_name' => $request->unit_name,
        ]);
        return response()->json([
            'status'    =>  'Success',
        ]);
    }

    public function update(Request $request,$id){
        $item = Unit::findOrFail($id) ;
        $item->unit_name = $request->unit_name;
        $item->unit_code = $request->unit_code;
        $item->save();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }

    public function delete($id)
    {
        Unit::findOrFail($id)->delete();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }
}
