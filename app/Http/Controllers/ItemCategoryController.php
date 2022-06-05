<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemCategory;
use Yajra\Datatables\Datatables;

class ItemCategoryController extends Controller
{
    public function fetch()
    {
        $data = ItemCategory::get();
        return json_encode($data);
    }
    public function index(Request $request){
        if ($request->ajax()) {
            $data = ItemCategory::latest()->get();
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
        return view('category.index');
    }

    public function detail($id){
        $data = ItemCategory::findOrFail($id);
        return json_encode($data);
    }

    public function create(Request $request){
        $item = ItemCategory::create([
            'category_code' => $request->category_code,
            'category_name' => $request->category_name,
        ]);
        return response()->json([
            'status'    =>  'Success',
        ]);
    }

    public function update(Request $request,$id){
        $item = ItemCategory::findOrFail($id) ;
        $item->category_name = $request->category_name;
        $item->category_code = $request->category_code;
        $item->save();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }

    public function delete($id)
    {
        ItemCategory::findOrFail($id)->delete();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }
}
