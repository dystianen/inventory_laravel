<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;

class UserController extends Controller
{
    public function fetch()
    {
        $data = User::with('job_title')->get();
        return json_encode($data);
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('job_title')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    $btn = $row->job_title->job_title_name;
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '
                        <button onclick="showModal(' . $row['id'] . ')" class="edit btn btn-info btn-sm">Edit</button >
                        <button onclick="deleteData(' . $row['id'] . ')" class="edit btn btn-danger btn-sm">Delete</button >
                        ';
                    return $btn;
                })
                ->rawColumns(['role', 'action'])
                ->make(true);
        }
        return view('user.index');
    }

    public function detail($id)
    {
        $data = User::findOrFail($id);
        return json_encode($data);
    }

    public function create(Request $request)
    {
        $warehouse = 0;
        if($request->input('warehouse_id')){
            $warehouse = $request->warehouse_id;
        }
        $item = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'nik' => $request->nik,
            'address' => $request->address,
            'username' => $request->username,
            'gender' => $request->gender,
            'job_title_id' => $request->job_title_id,
            'warehouse_id' => $warehouse,
            'phone_number' => $request->phoneNumber,
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'status'    =>  'Success',
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = User::findOrFail($id);
        $item->email = $request->email;
        $item->name = $request->name;
        $item->nik = $request->nik;
        $item->address = $request->address;
        $item->username = $request->username;
        $item->gender = $request->gender;
        $item->phone_number = $request->phoneNumber;
        if($request->password){
            $item->password = Hash::make($request->password);
        }
        $item->save();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return response()->json([
            'status'    =>  'Success',
        ]);
    }
}
