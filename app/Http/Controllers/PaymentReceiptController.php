<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Redirect;
use Auth;

class PaymentReceiptController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Index(Request $request){

        $status = $request->status == "true" ? true : false;
        $q = $request->q;
        $page = $request->page;

        if($status == null || $status == ""){
            $status = false;
        }

        $res = [];
        $res['status'] = $status;
        $res['q'] = $q;
        $res['page'] = $page;
        $res['receiptHost'] = env("PAYMENT_RECEIPT_HOST", "https://mahasiswa.umpri.ac.id");

        $paymentReceipts = DB::table('fnc_payment_receipt')
            ->select(
                'fnc_payment_receipt.*',
                'acd_student.Student_Id',
                'acd_student.Nim',
                'acd_student.Full_Name',
                'mstr_department.Department_Id',
                'mstr_department.Department_Name',
                'mstr_department.Department_Code',
                'mstr_education_program_type.Acronym'
            )
            ->join('acd_student', 'fnc_payment_receipt.Register_Number', '=', 'acd_student.Register_Number')
            ->join('mstr_department', 'acd_student.Department_Id', '=', 'mstr_department.Department_Id')
            ->join('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=', 'mstr_education_program_type.Education_Prog_Type_Id')
            ->where([['Is_Done', $status]])
            ->where(function ($query) use ($q) {
                if($q != null && $q != ""){
                    $query->whereRaw('LOWER(`Nim`) LIKE ? ', ['%' . trim(strtolower($q)) . '%'])
                    ->orWhereRaw('LOWER(`Full_Name`) LIKE ? ', ['%' . trim(strtolower($q)) . '%']);
                }
            })
            ->orderBy('fnc_payment_receipt.Created_Date', 'desc')
            ->paginate(10);

        $res['paymentReceipts'] = $paymentReceipts->appends(request()->input());

        return view('payment_receipt.index', $res);
    }
}
