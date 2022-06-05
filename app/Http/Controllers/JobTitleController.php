<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobTitle;
use Yajra\Datatables\Datatables;

class JobTitleController extends Controller
{
    public function fetch()
    {
        $data = JobTitle::get();
        return json_encode($data);
    }
    public function index(Request $request){
        if ($request->ajax()) {
            $data = JobTitle::latest()->get();
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
        return view('job-title.index');
    }

    public function detail($id){
        $data = JobTitle::findOrFail($id);
        return json_encode($data);
    }

    public function create(Request $request){
        $item = JobTitle::create([
            'code' => $request->code,
            'job_title_name' => $request->job_title_name,
        ]);
        return response()->json([
            'status'    =>  'Success',
        ]);
    }

    public function update(Request $request,$id){
        $item = JobTitle::findOrFail($id) ;
        $item->job_title_name = $request->job_title_name;
        $item->code = $request->code;
        $item->save();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }

    public function delete($id)
    {
        JobTitle::findOrFail($id)->delete();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }
}
