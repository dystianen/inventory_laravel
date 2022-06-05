<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BussinesPartnerType;
use Yajra\Datatables\Datatables;

class BussinesPartnerTypeController extends Controller
{
    public function fetch()
    {
        $data = BussinesPartnerType::get();
        return json_encode($data);
    }
    public function index(Request $request){
        if ($request->ajax()) {
            $data = BussinesPartnerType::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn='';
                        if($row['id']!==0){
                            $btn = '
                            <button onclick="showModal('.$row['id'].')" class="edit btn btn-info btn-sm">Edit</button >
                            <button onclick="deleteData('.$row['id'].')" class="edit btn btn-danger btn-sm">Delete</button >
                            ';
                        }
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('bussinesPartnerType.index');
    }

    public function detail($id){
        $data = BussinesPartnerType::findOrFail($id);
        return json_encode($data);
    }

    public function create(Request $request){
        $item = BussinesPartnerType::create([
            'code' => $request->code,
            'type' => $request->type,
        ]);
        return response()->json([
            'status'    =>  'Success',
        ]);
    }

    public function update(Request $request,$id){
        $item = BussinesPartnerType::findOrFail($id) ;
        $item->type = $request->type;
        $item->code = $request->code;
        $item->save();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }

    public function delete($id)
    {
        BussinesPartnerType::findOrFail($id)->delete();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }
}
