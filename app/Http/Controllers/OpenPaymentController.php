<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UseCases\Student\StudentUseCase;

use DB;
use Redirect;
use Auth;

class OpenPaymentController extends Controller
{
    // Function untuk web service tagihan mahasiswa baik kode 3 (non-KRS) ataupun 4 (KRS)
    // id = register number
    // Parameters
    // - bill_type (3, 4)
    // - nim ()
    public function GetBills(Request $request, $id){
        $res = [];
        $res['status'] = true;
        $res['message'] = "Success";

        if(!$request->hasHeader('Authorization')){
            $res['status'] = false;
            $res['message'] = "Unauthorized. Missing Token.";
            return response()->json($res);
        }

        $stdUseCase = new StudentUseCase();
        $validation = $stdUseCase->TokenValidation($request->header('Authorization'), $id);
        if(!$validation['status']){
            $res = $validation;
            return response()->json($res);
        }

        switch($request->bill_type){

            case 3:// Tagihan non KRS beserta cicilannya
                $res = $stdUseCase->GetBills($id, $request->bill_type);
                break;

            case 4:// Tagihan KRS beserta cicilannya
                $res = $stdUseCase->GetBills($id, $request->bill_type);
                break;

            default:
                
                $res['status'] = false;
                $res['message'] = "Bill type is not valid";

            break;
        }

        return response()->json($res);
    }
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function PaymentNonKRS(Request $request){
        $this->middleware('auth');

        $res = new \stdClass();

        $param = $request->param;
        $termYearId = $request->termYearId;
        $bankId = $request->bankId;
        $paymentDate = $request->paymentDate;
        $amount = $request->amount;
        $paymentReceiptId = $request->paymentReceiptId;
        $usedDeposit = $request->usedDeposit;
        $amountDeposit = $request->amountDeposit;

        $Created_By = Auth::user()->name;
        $Created_Date = Date('Y-m-d H:i:s');

        $status = true;
        $message = "Berhasil melakukan pembayaran";

        try{
            $student = DB::table('acd_student')->where('Nim', $param)->first();
            if($student == null){
                $student = DB::table('acd_student')->where('Register_Number', $param)->first();
            }
            
            // TODO Preparing minimum payment
            $minimumInstallment = DB::table('fnc_minimum_installment')->where([
                ['Department_Id', $student->Department_Id],
                ['Term_Year_Id', $termYearId],
                ['Entry_Year_Id', $student->Entry_Year_Id]
            ])->first();
            if($minimumInstallment == null){
                $res->status = false;
                $res->message = "minimal pembayaran open payment belum di setting";

                return response()->json($res);
            }

            // TODO Insert installment
            $insertInstallment = DB::table('fnc_student_installment')
                ->insert([
                    'Term_Year_Id' => $termYearId,
                    'Register_Number' => $student->Register_Number,
                    'Payment_Date' => $paymentDate,
                    'Amount' => $amount,
                    'Created_By' => $Created_By,
                    'Created_Date' => $Created_Date
                ]);
            
            // TODO Checking exceeded minimum to allow krs
            $installmentsInTermYearsAmount = DB::table('fnc_student_installment')->where([
                ['Term_Year_Id', $termYearId],
                ['Register_Number', $student->Register_Number]
            ])->sum('Amount');

            if($installmentsInTermYearsAmount >= $minimumInstallment->Amount){ // if exceeded , allow student to krs

                // check if exist , no need to insert
                $existAllowed = DB::table('acd_student_allowed_krs')->where([
                    ['Register_Number', $student->Register_Number],
                    ['Term_Year_Id', $termYearId]
                ])->first();
                if($existAllowed == null){
                    $insertAllowedKRS = DB::table('acd_student_allowed_krs')->insert([
                        'Register_Number' => $student->Register_Number,
                        'Term_Year_Id' => $termYearId,
                        'Is_Auto' => true
                    ]);
                }
            }

            // TODO Check if paid off
            $xml = "";
            $tagihans = DB::select('CALL usp_GetStudentBill(?,?,?)',[$student->Register_Number,"",""]);
            $totalTagihanInTermYear = 0; 
            foreach($tagihans as $tagihan){
                if($tagihan->Term_Year_Bill_id == $termYearId){
                    $totalTagihanInTermYear = $totalTagihanInTermYear + $tagihan->Amount;

                    // Preparing xml data for payment
                    $Deskripsi = "";
                    if($tagihan->Payment_Order != null){
                        $Payment_Order = $tagihan->Payment_Order;
                    }else{
                        $Payment_Order = 1;
                    }
                    $Course_Id = "";
                    $xml = $xml."<Data><Cost_Item_Id>".$tagihan->Cost_Item_Id."</Cost_Item_Id><Payment_Order>".$Payment_Order."</Payment_Order><Amount>".$tagihan->Amount."</Amount><Term_Year_Bill_Id>".$tagihan->Term_Year_Bill_id."</Term_Year_Bill_Id><Course_Id>".$Course_Id."</Course_Id><Deskripsi>".$Deskripsi."</Deskripsi></Data>";
                }
            }

            $installmentsInTermYears = DB::table('fnc_student_installment')
            ->select(
                DB::raw('
                    fnc_student_installment.Student_Installment_Id,
                    fnc_student_installment.Term_Year_Id,
                    fnc_student_installment.Register_Number,
                    fnc_student_installment.Payment_Date,
                    fnc_student_installment.Amount as Amount,
                    SUM(IFNULL(fnc_used_student_installment.Amount, 0)) as Used_Amount'
                )
            )
            ->leftJoin('fnc_used_student_installment','fnc_student_installment.Student_Installment_Id','=','fnc_used_student_installment.Student_Installment_Id')
            ->where([
                ['fnc_student_installment.Register_Number', $student->Register_Number],
                ['fnc_student_installment.Amount', '>', 0],
                ['fnc_student_installment.Term_Year_Id', $termYearId],
                ['fnc_used_student_installment.Used_Student_Installment_Id', null]
            ])
            ->orWhere([
                ['fnc_student_installment.Register_Number', $student->Register_Number],
                ['fnc_student_installment.Amount', '>', 0],
                ['fnc_used_student_installment.Amount', '>', 0],
                ['fnc_used_student_installment.Amount', '<', 'fnc_student_installment.Amount']
            ])
            ->groupBy(['fnc_student_installment.Student_Installment_Id', 'fnc_student_installment.Term_Year_Id', 'fnc_student_installment.Register_Number', 'fnc_student_installment.Payment_Date', 'fnc_student_installment.Amount'])
            ->get();

            // $installmentsInTermYears = DB::select(DB::raw('
            //     SELECT
            //     fnc_student_installment.Student_Installment_Id,
            //     fnc_student_installment.Term_Year_Id,
            //     fnc_student_installment.Register_Number,
            //     fnc_student_installment.Payment_Date,
            //     fnc_student_installment.Amount as Amount,
            //     SUM(IFNULL(fnc_used_student_installment.Amount, 0)) as Used_Amount
            //     FROM fnc_student_installment
            //     LEFT JOIN fnc_used_student_installment ON fnc_student_installment.Student_Installment_Id = fnc_used_student_installment.Student_Installment_Id
            //     WHERE 
            //     fnc_student_installment.Register_Number = '.$student->Register_Number.' AND
            //     fnc_student_installment.Amount > 0 AND
            //     (
            //         fnc_student_installment.Term_Year_Id = '.$termYearId.' AND
            //         fnc_used_student_installment.Used_Student_Installment_Id IS NULL
            //         OR
            //             fnc_used_student_installment.Amount > 0 AND
            //         fnc_used_student_installment.Amount < fnc_student_installment.Amount
            //     )
            //     GROUP BY 
            //     fnc_student_installment.Student_Installment_Id,
            //     fnc_student_installment.Term_Year_Id,
            //     fnc_student_installment.Register_Number,
            //     fnc_student_installment.Payment_Date,
            //     fnc_student_installment.Amount'));
            
            $unusedInstallmentsInTermYearsAmount = 0;
            foreach( $installmentsInTermYears as $installmentsInTermYear ){
                $unusedInstallmentsInTermYearsAmount += ($installmentsInTermYear->Amount - $installmentsInTermYear->Used_Amount);
            }
            // dd($installmentsInTermYears);

            if($unusedInstallmentsInTermYearsAmount >= $totalTagihanInTermYear){ // if paid off, call usp_SetStudentPayment for payment
                $reffPaymentCode = "BPP".Date("ymdHis").substr($termYearId,0,4)."-".$student->Nim;
                try {
                    $payment = DB::select('CALL usp_SetStudentPayment("' . $reffPaymentCode . '","' . $student->Register_Number . '","' . $termYearId . '","' . $totalTagihanInTermYear . '","' . $paymentDate . '","' . $xml . '","' . Auth::user()->name . '","' . $bankId . '")');
                }catch(\Exception $e){
                    
                }

                $reffPayment = DB::table('fnc_reff_payment')->where('Reff_Payment_Code','=', $reffPaymentCode)->first();
                if ($reffPayment != null){

                    $usedAmount = $totalTagihanInTermYear;

                    if($usedDeposit == 1 || $amountDeposit > 0){
                        // insert into used_student_installment to mark this installment is used in reff_payment
                        // $installmentsInTermYears = DB::table('fnc_student_installment')
                        // ->select(DB::raw('
                        //     fnc_student_installment.Student_Installment_Id,
                        //     fnc_student_installment.Term_Year_Id,
                        //     fnc_student_installment.Register_Number,
                        //     fnc_student_installment.Payment_Date,
                        //     fnc_student_installment.Amount as Amount,
                        //     SUM(fnc_used_student_installment.Amount) as Used_Amount
                        // '))
                        // ->leftJoin('fnc_used_student_installment', 'fnc_used_student_installment.Student_Installment_Id', '=', 'fnc_student_installment.Student_Installment_Id')
                        // ->where([
                        //     ['fnc_student_installment.Term_Year_Id', '=', $termYearId],
                        //     ['fnc_student_installment.Register_Number', $student->Register_Number],
                        //     ['fnc_used_student_installment.Used_Student_Installment_Id', null]
                        // ])
                        // ->orWhere([
                        //     // ['fnc_student_installment.Term_Year_Id', '<=', $termYearId],
                        //     ['fnc_student_installment.Register_Number', $student->Register_Number],
                        //     ['fnc_student_installment.Amount', '>', 0],
                        //     ['fnc_used_student_installment.Amount', '>', 0],
                        //     ['fnc_used_student_installment.Amount', '<', 'fnc_student_installment.Amount'] // get not fully used
                        // ])
                        // ->groupBy('fnc_student_installment.Student_Installment_Id',
                        // 'fnc_student_installment.Term_Year_Id',
                        // 'fnc_student_installment.Register_Number',
                        // 'fnc_student_installment.Payment_Date',
                        // 'fnc_student_installment.Amount')
                        // ->get();

                        // dd($installmentsInTermYears);

                        foreach( $installmentsInTermYears as $installmentsInTermYear ){
                            $usedAmount = $usedAmount - ($installmentsInTermYear->Amount - $installmentsInTermYear->Used_Amount);

                            if ( $usedAmount >= 0){
                                DB::table('fnc_used_student_installment')
                                ->insert([
                                    'Student_Installment_Id'=> $installmentsInTermYear->Student_Installment_Id,
                                    'Reff_Payment_Id'=> $reffPayment->Reff_Payment_Id,
                                    'Amount' => ($installmentsInTermYear->Amount - $installmentsInTermYear->Used_Amount),
                                ]);
                            } else if ( $usedAmount < 0 ) {
                                DB::table('fnc_used_student_installment')
                                ->insert([
                                    'Student_Installment_Id'=> $installmentsInTermYear->Student_Installment_Id,
                                    'Reff_Payment_Id'=> $reffPayment->Reff_Payment_Id,
                                    'Amount' => (($installmentsInTermYear->Amount - $installmentsInTermYear->Used_Amount) - ($usedAmount * ( 0 - 1) )),
                                ]);
                            }
                        }
                    } else {
                        // insert into used_student_installment to mark this installment is used in reff_payment
                        $installmentsInTermYears = DB::table('fnc_student_installment')
                        ->select(DB::raw('
                            fnc_student_installment.Student_Installment_Id,
                            fnc_student_installment.Amount as Amount
                        '))
                        ->leftJoin('fnc_used_student_installment', 'fnc_used_student_installment.Student_Installment_Id', '=', 'fnc_student_installment.Student_Installment_Id')
                        ->where([
                            ['fnc_student_installment.Term_Year_Id', $termYearId],
                            ['fnc_student_installment.Register_Number', $student->Register_Number],
                            ['fnc_used_student_installment.Used_Student_Installment_Id', null]
                        ])->get();

                        $usedAmount = $totalTagihanInTermYear;
                        foreach( $installmentsInTermYears as $installmentsInTermYear ){
                            $usedAmount = $usedAmount - $installmentsInTermYear->Amount;

                            if ( $usedAmount >= 0){
                                DB::table('fnc_used_student_installment')
                                ->insert([
                                    'Student_Installment_Id'=> $installmentsInTermYear->Student_Installment_Id,
                                    'Reff_Payment_Id'=> $reffPayment->Reff_Payment_Id,
                                    'Amount' => $installmentsInTermYear->Amount,
                                ]);
                            } else if ( $usedAmount < 0 ) {
                                DB::table('fnc_used_student_installment')
                                ->insert([
                                    'Student_Installment_Id'=> $installmentsInTermYear->Student_Installment_Id,
                                    'Reff_Payment_Id'=> $reffPayment->Reff_Payment_Id,
                                    'Amount' => ($installmentsInTermYear->Amount - ($usedAmount * ( 0 - 1) )),
                                ]);
                            }
                        }
                    }

                }

            }

            // TODO check if it is from payment receipt , then set Is_Done true the payment receipt
            if($paymentReceiptId != null && $paymentReceiptId != ""){
                try {
                    $updatePaymentReceipt = DB::table('fnc_payment_receipt')->where('Payment_Receipt_Id', $paymentReceiptId)->update(['Is_Done' => true]);
                }catch(\Exception $e){
                    
                }
            }

        }catch(\Exception $e){
            $status = false;
            $message = "Gagal melakukan pembayaran open, Keterangan error : " . $e->getMessage();
        }

        $res->status = $status;
        $res->message = $message;

        return response()->json($res);
    }

    public function DeletePaymentNonKRS(Request $request){
        $this->middleware('auth');

        $res = new \stdClass();

        $id = $request->id;

        $Modified_By = Auth::user()->name;
        $Modified_Date = Date('Y-m-d H:i:s');

        $status = true;
        $message = "Berhasil melakukan pembatalan pembayaran";

        try{
            $existedInstallment = DB::table('fnc_student_installment')->where('Student_Installment_Id', $id)->first();
            if($existedInstallment == null){
                $status = false;
                $message = "Gagal melakukan pembatalan pembayaran. Data tidak ditemukan";
            } else {
                $usedInstallments = DB::table('fnc_used_student_installment')->where('Student_Installment_Id', $existedInstallment->Student_Installment_Id)->get();
                if(count($usedInstallments) > 0){
                    foreach($usedInstallments as $usedInstallment){
                        DB::table('fnc_used_student_installment')
                        ->insert([
                            'Student_Installment_Id'=> $usedInstallment->Student_Installment_Id,
                            'Reff_Payment_Id'=> $usedInstallment->Reff_Payment_Id,
                            // 'Amount' => ($usedInstallment->Amount * ( 0 - 1) ),
                            'Amount' => 0,
                        ]);
                    }
                }

                $insertInstallment = DB::table('fnc_student_installment')
                ->where('Student_Installment_Id',$existedInstallment->Student_Installment_Id)
                ->update([
                    'Amount' => 0,
                    'Modified_By' => $Modified_By,
                    'Modifeid_Date' => $Modified_Date
                ]);
            }

        }catch(\Exception $e){
            $status = false;
            $message = "Gagal melakukan pembatalan pembayaran, Keterangan error : " . $e->getMessage();
        }

        $res->status = $status;
        $res->message = $message;

        return response()->json($res);
    }
    
    public function getReportByDate(Request $request){
        $res = [];
        $res['status'] = true;
        $res['message'] = "Success";

        $startDate = $request->startDate;
        $endDate = $request->endDate;

        if($startDate == null){
            $startDate = Date("Y-m-d H:i:s");
        }

        if($endDate == null){
            $endDate = Date("Y-m-d H:i:s");
        }

        $installments = DB::table('fnc_student_installment')
        ->select(DB::raw('
            MIN(fnc_student_installment.Student_Installment_Id) as Student_Installment_Id,
            fnc_student_installment.Term_Year_Id,
            fnc_student_installment.Register_Number,
            acd_student.Nim,
            acd_student.Full_Name,
            fnc_student_installment.Payment_Date,
            SUM(fnc_student_installment.Amount) as Amount,
            fnc_student_installment.Bank_Id,
            fnc_bank.Bank_Name
        '))
        ->leftJoin('acd_student', 'fnc_student_installment.Register_Number', '=', 'acd_student.Register_Number')
        ->leftJoin('fnc_bank', 'fnc_student_installment.Bank_Id', '=', 'fnc_bank.Bank_Id')
        ->where([
            ['fnc_student_installment.Payment_Date', '>=', $startDate],
            ['fnc_student_installment.Payment_Date', '<=', Date('Y-m-d H:i:d', strtotime(' + 1 days', strtotime($endDate)))]
        ])
        ->orderBy('fnc_student_installment.Payment_Date', 'desc')
        ->groupBy(
            'fnc_student_installment.Term_Year_Id', 
            'fnc_student_installment.Register_Number', 
            'fnc_student_installment.Payment_Date', 
            'fnc_student_installment.Bank_Id',
            'acd_student.Nim',
            'acd_student.Full_Name',
            'fnc_bank.Bank_Name'
        )
        ->get();

        $res['data'] = $installments;

        return response()->json($res);
    }

    public function getReportByMhs(Request $request, $id){
        $res = [];
        $res['status'] = true;
        $res['message'] = "Success";

        if(!$request->hasHeader('Authorization')){
            $res['status'] = false;
            $res['message'] = "Unauthorized. Missing Token.";
            return response()->json($res);
        }

        $registerNumber = $request->id;
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $stdUseCase = new StudentUseCase();
        $validation = $stdUseCase->TokenValidation($request->header('Authorization'), $registerNumber);
        if(!$validation['status']){
            $res = $validation;
            return response()->json($res);
        }

        $installments = DB::table('fnc_student_installment')->where('fnc_student_installment.Register_Number', $registerNumber);

        if($startDate != null){
            $installments = $installments->where('Payment_Date', '>=', $startDate);
        }
        
        if($endDate != null){
            $installments = $installments->where('Payment_Date', '<=', Date('Y-m-d H:i:d', strtotime(' + 1 days', strtotime($endDate))));
        }

        $installments = $installments
            ->select(DB::raw('
                fnc_student_installment.Student_Installment_Id,
                fnc_student_installment.Term_Year_Id,
                fnc_student_installment.Register_Number,
                acd_student.Nim,
                acd_student.Full_Name,
                fnc_student_installment.Payment_Date,
                fnc_student_installment.Amount,
                fnc_student_installment.Bank_Id,
                fnc_bank.Bank_Name,
                mstr_term_year.Term_Year_Name
            '))
            ->leftJoin('acd_student', 'fnc_student_installment.Register_Number', '=', 'acd_student.Register_Number')
            ->leftJoin('fnc_bank', 'fnc_student_installment.Bank_Id', '=', 'fnc_bank.Bank_Id')
            ->leftJoin('mstr_term_year', 'fnc_student_installment.Term_Year_Id', '=', 'mstr_term_year.Term_Year_Id')
            ->orderBy('fnc_student_installment.Payment_Date', 'desc')
            // ->groupBy(
            //     'fnc_student_installment.Term_Year_Id', 
            //     'fnc_student_installment.Register_Number', 
            //     'fnc_student_installment.Payment_Date', 
            //     'fnc_student_installment.Bank_Id',
            //     'acd_student.Nim',
            //     'acd_student.Full_Name',
            //     'fnc_bank.Bank_Name',
            //     'mstr_term_year.Term_Year_Name'
            // )
            ->get();

        $res['data'] = $installments;

        return response()->json($res);
    }
}
