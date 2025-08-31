<?php

namespace App\Http\Controllers;

use App\UseCases\Student\StudentUseCase;
use Illuminate\Http\Request;
use App\UseCases\Student;

use DB;

class TokenController extends Controller
{
    //
    public function GetToken(Request $request){
        $res = [];
        $res['status'] = true;
        $res['message'] = "Success";

        try{
            $registerNumber = $request->register_number;
            $password = $request->password;

            if($registerNumber == null || $password == null){
                $res['status'] = false;
                $res['message'] = "Invalid parameters";
            }else{
                $stdUseCase = new StudentUseCase();
                $getToken = $stdUseCase->GetToken($registerNumber, $password);
                
                $res['status'] = $getToken['status'];
                $res['message'] = $getToken['message'];
                $res['data'] = $getToken['data'];
            }
        }catch(\Exception $e){
            $res['status'] = false;
            $res['message'] = $e->getMessage();
        }

        return response()->json($res); 
    }
}
