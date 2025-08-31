<?php

namespace App\Http\Controllers;
use App\UseCases\Deposit\DepositUseCase;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function GetDeposit(Request $request, $id){

        $result = [];

        $result["status"] = true;
        $result["message"] = "OK";
        $result["data"] = null;

        if(!$request->hasHeader('Authorization')){
            $res['status'] = false;
            $res['message'] = "Unauthorized. Missing Token.";
            return response()->json($res);
        }

        $useCase = new DepositUseCase();
        $result["data"] = $useCase->GetDeposit($id);

        return response()->json($result);
    } 
}