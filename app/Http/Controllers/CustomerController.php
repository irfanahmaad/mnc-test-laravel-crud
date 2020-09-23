<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Helpers\SendResponse;
use DataTables;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function getData(){
        
        try {
            
            $cust = Customer::all();
            $data = DataTables::of($cust)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $button = '<a href="javascript:;"><button class="btn btn-warning btn-sm edit" style="float:left;margin-left:30px" id="'.$row['id'].'"><i class="fa fa-pencil"></i> Edit</button></a>';
                        $button .= '<a href="javascript:;"><button class="btn btn-danger btn-sm delete" id="'.$row['id'].'"><i class="fa fa-trash"></i> Delete</button></a>';
                        return $button;
                    })
                    ->make(true);

            return SendResponse::default($data,'Berhasil memuat data',200,1);

        } catch(\Exception $e) {
            
            return SendResponse::default(null,$e->getMessage(),500,0);
        
        }

    }

    public function store(Request $request)
    {
        try {
            
            $exist = Customer::where('email',$request->email)->first();
            if($exist){
                return SendResponse::default(null,'Email telah digunakan',400,0);
            }
            $req = $request->except(['password','action','hidden_id']);
            $req['password'] = Hash::make($request->password);
            $data = Customer::create($req);

            return SendResponse::default($data,'Data Added',201,1);
        
        } catch(\Exception $e) {
        
            return SendResponse::default(null,$e->getMessage(),500,0);
        
        }
    }

    public function show($id)
    {
        try {

            $data = Customer::find($id);
            
            if(!$data){
                return SendResponse::default(null,'Data tidak ditemukan',404,0);
            }

            return SendResponse::default($data,'Berhasil memuat data',200,1);
        
        } catch(\Exception $e) {
            return SendResponse::default(null,$e->getMessage(),500,0);
        }
    }

    public function update(Request $request, $id)
    {
        try {
        
            $exist = Customer::where('email',$request->email)->first();
            if($exist){
                return SendResponse::default(null,'Email telah digunakan',400,0);
            }
            
            $req = $request->except(['password','action','hidden_id']);
            if($request->password){
                $req['password'] = Hash::make($request->password);
            }
            
            $data = Customer::find($id)->update($req);
            return SendResponse::default($data,'Data Updated',200,1);

        } catch(\Exception $e) {
            
            return SendResponse::default(null,$e->getMessage(),500,0);
        
        }
    }

    public function destroy($id)
    {
        try{
        
            $data = Customer::find($id);
            $data->delete();
        
            return SendResponse::default($data,'message',204,1);
        
        }catch(\Exception $e){
        
            return SendResponse::default(null,$e->getMessage(),500,0);
        
        }
    }
}
