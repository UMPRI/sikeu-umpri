<?php
namespace App\Http\Controllers;
use App\UseCases\Deposit\DepositUseCase;
use App\UseCases\Student\StudentUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// use aipt\Http\Requests;
// use aipt\Http\Controllers\Controller;
use Input;
use DB;
use Redirect;
use Validator;
use PDO;
use Notifiable;
use Alert;
use Auth;
class StudentBillController extends Controller
{
    public function __construct()
    {
          $this->middleware('auth');
    }
    // index -----------------------------------------------------------------------------------------\\
    public function index()
    {
      $receiptHost = env("PAYMENT_RECEIPT_HOST", "https://mahasiswa.umpri.ac.id");

      $receiptId = Input::get('receipt');
      $param = Input::get('param');

      $i = 0;
      $iKRS = 0;
      $Datetimenow = Date('Y-m-d');
      $thactive = DB::Table('mstr_term_year')
              ->where('Start_Date','<=',$Datetimenow)
              ->where('End_Date','>=',$Datetimenow)
              ->select('Term_Year_Id')
              ->first();

      $ListTagihan = [];
      $ListTagihanKRS = [];
      $cicilanNonKRS = [];
      $deposit = 0;

      $paymentReceipt = null;
      
      $Student = DB::table('acd_student')->where('Nim','=',$param)->leftJoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')->first();
      if ($Student == null) {
        $Student = DB::table('acd_student')->where('Register_Number','=',$param)->leftJoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')->first();
      }
      if ($param != null && $Student != null) {
        $nonKRSTermYearBillIds = [];
        $tagihan = DB::select('CALL usp_GetStudentBill(?,?,?)',[$Student->Register_Number,"",""]);
        $iy= 0;
        // dd($tagihan);
        foreach ($tagihan as $key) {
          $ListTagihan[$i]['Cost_Item_Id'] = $key->Cost_Item_Id;
          $ListTagihan[$i]['Cost_Item_Name'] = $key->Cost_Item_Name;
          $ListTagihan[$i]['Payment_Order'] = $key->Payment_Order;
          $ListTagihan[$i]['Amount'] = $key->Amount;
          $ListTagihan[$i]['Start_Date'] = $key->Start_Date;
          $ListTagihan[$i]['End_Date'] = $key->End_Date;
          $ListTagihan[$i]['Term_Year_Bill_Id'] = $key->Term_Year_Bill_id;
          $i++;

          if(!in_array($key->Term_Year_Bill_id, $nonKRSTermYearBillIds)){
            $nonKRSTermYearBillIds[$iy] = $key->Term_Year_Bill_id;
            $iy++;
          }
          
        }

        $tagihanKRS = DB::select('CALL usp_GetStudentBill_For_KRS(?,?,?)',[$Student->Register_Number,"",""]);
        // dd($tagihanKRS);
        foreach ($tagihanKRS as $key) {
          // if ($key->bill_amount > 0) {
            $ListTagihanKRS[$iKRS]['Cost_Item_Id'] = $key->Cost_Item_Id;
            $ListTagihanKRS[$iKRS]['Cost_Item_Name'] = $key->Cost_Item_Name;
            $ListTagihanKRS[$iKRS]['Payment_Order'] = 1;
            $ListTagihanKRS[$iKRS]['Course_Id'] = $key->Course_Id;
            $ListTagihanKRS[$iKRS]['Course_Name'] = $key->Course_Name;
            $ListTagihanKRS[$iKRS]['Amount'] = $key->Amount;
            // $ListTagihanKRS[$iKRS]['Bill_Amount'] = $key->bill_amount;
            // $ListTagihanKRS[$iKRS]['Payment_Amount'] = $key->payment_amount;
            $ListTagihanKRS[$iKRS]['Start_Date'] = $key->Start_Date;
            $ListTagihanKRS[$iKRS]['End_Date'] = $key->End_Date;
            $ListTagihanKRS[$iKRS]['Term_Year_Bill_Id'] = $key->Term_Year_Bill_id;
            $ListTagihanKRS[$iKRS]['SKS'] = $key->SKS;
            $ListTagihanKRS[$iKRS]['perSKS'] = $key->perSKS;
            $iKRS++;
          // }
        }

        $cicilanNonKRSraws = DB::table('fnc_student_installment')
          ->where([['Register_Number', $Student->Register_Number],['fnc_used_student_installment.Used_Student_Installment_Id', null]])
          ->whereIn('fnc_student_installment.Term_Year_Id', $nonKRSTermYearBillIds)
          ->join('mstr_term_year', 'fnc_student_installment.Term_Year_Id', '=', 'mstr_term_year.Term_Year_Id')
          ->leftJoin('fnc_used_student_installment', 'fnc_student_installment.Student_Installment_Id', '=', 'fnc_used_student_installment.Student_Installment_Id')
          ->groupBy('Register_Number', 'fnc_student_installment.Term_Year_Id', 'mstr_term_year.Term_Year_Name')
          ->select(DB::raw('fnc_student_installment.Term_Year_Id, Term_Year_Name, SUM(fnc_student_installment.Amount) as Amount'))
          ->get();
        
        // dd($cicilanNonKRSraws);
        
        $iy = 0;
        foreach($cicilanNonKRSraws as $cicilanNonKRSraw){
          $paymentInTermYear = DB::table('fnc_student_payment')->where([
            ['Register_Number', $Student->Register_Number],
            ['Term_Year_Bill_Id', $cicilanNonKRSraw->Term_Year_Id]
          ])->sum('Payment_Amount');
            
          // if(($cicilanNonKRSraw->Amount - $paymentInTermYear) > 0){
            // $itemCicilanNonKRS = new \stdClass();
            // $itemCicilanNonKRS->Term_Year_Id = $cicilanNonKRSraw->Term_Year_Id;
            // $itemCicilanNonKRS->Term_Year_Name = $cicilanNonKRSraw->Term_Year_Name;
            // $itemCicilanNonKRS->Amount = $cicilanNonKRSraw->Amount - $paymentInTermYear;

            // $cicilanNonKRS[$iy] = $itemCicilanNonKRS;
            // $iy++;
          // }

          if ($cicilanNonKRSraw->Amount > 0) {
            $itemCicilanNonKRS = new \stdClass();
            $itemCicilanNonKRS->Term_Year_Id = $cicilanNonKRSraw->Term_Year_Id;
            $itemCicilanNonKRS->Term_Year_Name = $cicilanNonKRSraw->Term_Year_Name;
            $itemCicilanNonKRS->Amount = $cicilanNonKRSraw->Amount;

            $cicilanNonKRS[$iy] = $itemCicilanNonKRS;
            $iy++;
          }
          
        }

        if($receiptId != null && $receiptId != ""){
          $paymentReceipt = DB::table('fnc_payment_receipt')->where([['Payment_Receipt_Id', $receiptId]])->first();
        }
      }

      // dd($ListTagihan);
      $user_id = Auth::user()->id;
      $role_user = DB::table('_role_user')->where('user_id')->get();
      $roles = [];
      $ii = 0;
      foreach ($role_user as $data_role_user) {
        $roles[$ii] = DB::table('_roles')->where('roles.id','=',$data_role_user->role_id)->select('name')->first();
      }

      if (in_array("teller",$roles)) {
        $Bank = DB::table('fnc_bank')->Where('Bank_Id','=',999)->get();
      }else{
        $Bank = DB::table('fnc_bank')->get();
      }

      $accessToken = '';

      $Term_Year  = DB::table('mstr_term_year')->OrderBy('Term_Year_Id','desc')->get();
      if ($Student != null) {
        $Student_Payment = DB::table('fnc_student_payment')
                            ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
                            ->join('fnc_bank','fnc_reff_payment.Bank_Id','=','fnc_bank.Bank_Id')
                            ->where([
                                      ['fnc_student_payment.Register_Number','=',$Student->Register_Number],
                                      // ['fnc_student_payment.Cost_Item_Id','!=', 1]
                                    ])
                            ->GroupBy('fnc_student_payment.Reff_Payment_Id','fnc_student_payment.Term_Year_Bill_Id','fnc_reff_payment.Payment_Date')
                            ->select(DB::raw('
                                fnc_reff_payment.Reff_Payment_Id as Reff_Payment_Id,
                                fnc_reff_payment.Term_Year_Id as Term_Year_Bill_Id,
                                fnc_reff_payment.Reff_Payment_Code as No_Kwitansi,
                                fnc_bank.Bank_Name as Bank_Name,
                                fnc_reff_payment.Payment_Date as Payment_Date,
                                SUM(fnc_student_payment.Payment_Amount) as Payment_Amount
                            '))->get();
        
        $stdUseCase = new StudentUseCase();
        $getToken = $stdUseCase->GetToken($Student->Register_Number, hash('sha256', $Student->Student_Password));
        if($getToken['status']){
          $accessToken = $getToken['data']['token'];
        }
        
        $depositUseCase = new DepositUseCase();
        $deposit = $depositUseCase->GetDeposit($Student->Register_Number);
      }else{
        $Student_Payment = null;
      }
      $days = array(1, 2, 3, 4, 5);
      $day = date("d");
      $isAktif = 1; // sementara ini dibuka terus
      //bool isAktif = ((days.Contains(day) && Now.TimeOfDay > limit.TimeOfDay) || day == 6) ? true : false;
      //if (User.IsInRole("ADMINUNIVSIKEU"))
      //{
      //    isAktif = true;
      //}
      if ($Student == null && $param != null) {
        Alert::error('NIM atau Register Number tidak dapat ditemukan, pastikan Nim/Reg.Number yang anda inputkan benar', 'NIM/Reg.Number Salah !');
      }

      return View('student_bill/index')
      ->with('receiptHost', $receiptHost)
      ->with('Student_Payment',$Student_Payment)
      ->with('Term_Year',$Term_Year)
      ->with('Bank',$Bank)
      ->with('ListTagihan',$ListTagihan)
      ->with('ListTagihanKRS', $ListTagihanKRS)
      ->with('param',$param)
      ->with('th_aktif',$thactive)
      ->with('Student',$Student)
      ->with('isAktif',$isAktif)
      ->with('cicilanNonKRS', $cicilanNonKRS)
      ->with('receiptId', $receiptId)
      ->with('paymentReceipt', $paymentReceipt)
      ->with('accessToken', $accessToken)
      ->with('deposit', $deposit)
      ;
    }
    public function Bayar(Request $request)
    {
      $date = $request->TglBayar;
      $TglBayar =  Date("Y-m-d h:i:s",strtotime($date));
      $strDateNow = Date("ymdHis");
      $Register_Number = $request->Register_Number;
      $Term_Year_Id = $request->Term_Year_Id;
      $TotalAmount = 0;
      $Bank_Id = $request->Bank_Id;
      $name = Auth::user()->name;
      $acd_student = DB::table('acd_student')
                    ->where('Register_Number','=',$Register_Number
                    )->first();
      $Nim = $acd_student->Nim;
      $Reff_Payment_Code = "BPP".$strDateNow.substr($Term_Year_Id,0,4)."-".$Nim;

      // validasi checklist--------------
      $terpilih = $request->ToAdd;
      $terpilihKRS = $request->ToAddKRS;
      $xml = "";
      if ($terpilih != null || $terpilihKRS != null) {
        // jika ada data terpilih
        if ($terpilih != null) {
          $data = [];
          foreach ($terpilih as $string) {
            $data[] = explode(",", $string);
          }


          foreach ($data as $dataItem) {
            $Cost_Item_Id = $dataItem[0];
            if ($dataItem[1] != null) {
              $Payment_Order = $dataItem[1];
            }else {
              $Payment_Order = 1;
            }
            $Amount = $dataItem[2];
            $TotalAmount = $TotalAmount + $Amount;
            $Term_Year_Bill_Id = $dataItem[3];
            $Course_Id = "";
            $Deskripsi = "";

            $xml = $xml."<Data><Cost_Item_Id>".$Cost_Item_Id."</Cost_Item_Id><Payment_Order>".$Payment_Order."</Payment_Order><Amount>".$Amount."</Amount><Term_Year_Bill_Id>".$Term_Year_Bill_Id."</Term_Year_Bill_Id><Course_Id>".$Course_Id."</Course_Id><Deskripsi>".$Deskripsi."</Deskripsi></Data>";
          }
        }

        if ($terpilihKRS != null) {
          $dataKRS = [];
          foreach ($terpilihKRS as $stringKRS) {
            $dataKRS[] = explode(",", $stringKRS);
          }

          foreach ($dataKRS as $dataItemKRS) {
            $Cost_Item_Id = $dataItemKRS[0];
            if ($dataItemKRS[1] != null) {
              $Payment_Order = $dataItemKRS[1];
            }else {
              $Payment_Order = 1;
            }
            $Amount = $dataItemKRS[2];
            $TotalAmount = $TotalAmount + $Amount;
            $Term_Year_Bill_Id = $dataItemKRS[3];
            $Course_Id = $dataItemKRS[4];
            $Deskripsi = $dataItemKRS[5];
            if ($Cost_Item_Id == 2 || $Course_Id == null || $Course_Id == "") {
              $Course_Id = "";
            }

            $xml = $xml."<Data><Cost_Item_Id>".$Cost_Item_Id."</Cost_Item_Id><Payment_Order>".$Payment_Order."</Payment_Order><Amount>".$Amount."</Amount><Term_Year_Bill_Id>".$Term_Year_Bill_Id."</Term_Year_Bill_Id><Course_Id>".$Course_Id."</Course_Id><Deskripsi>".$Deskripsi."</Deskripsi></Data>";
          }
        }

      }else {
        // jika tidak ada data terpilihh
        $tagihan = DB::select('CALL usp_getstudentbill(?,?,?)',[$Register_Number,"",""]);
        foreach ($tagihan as $item) {
            $Deskripsi = "";
            if($item->Payment_Order != null){
              $Payment_Order = $item->Payment_Order;
            }else{
              $Payment_Order = 1;
            }
            $TotalAmount = $TotalAmount + $item->Amount;
            $Course_Id = "";
            $xml = $xml."<Data><Cost_Item_Id>".$item->Cost_Item_Id."</Cost_Item_Id><Payment_Order>".$Payment_Order."</Payment_Order><Amount>".$item->Amount."</Amount><Term_Year_Bill_Id>".$item->Term_Year_Bill_id."</Term_Year_Bill_Id><Course_Id>".$Course_Id."</Course_Id><Deskripsi>".$Deskripsi."</Deskripsi></Data>";
        }

        $tagihanKRS = DB::select('CALL usp_GetStudentBill_For_KRS(?,?,?)',[$Register_Number,"",""]);
        foreach ($tagihanKRS as $itemKRS) {
            $Deskripsi = "";
            if($itemKRS->Payment_Order != null){
              $Payment_Order = $itemKRS->Payment_Order;
            }else{
              $Payment_Order = 1;
            }
            $Course_Id = $itemKRS->Course_Id;
            if ($itemKRS->Cost_Item_Id == 2) {
              $Deskripsi = $itemKRS->SKS." x Rp.".number_format($itemKRS->perSKS,'0',',','.');
              $Course_Id = "";
            }
            $TotalAmount = $TotalAmount + $itemKRS->Amount;
            $xml = $xml."<Data><Cost_Item_Id>".$itemKRS->Cost_Item_Id."</Cost_Item_Id><Payment_Order>".$Payment_Order."</Payment_Order><Amount>".$itemKRS->Amount."</Amount><Term_Year_Bill_Id>".$itemKRS->Term_Year_Bill_id."</Term_Year_Bill_Id><Course_Id>".$Course_Id."</Course_Id><Deskripsi>".$Deskripsi."</Deskripsi></Data>";
        }
      }

      // proses usp bayar-------------
      try {
        $payment = DB::select('CALL usp_SetStudentPayment("'. $Reff_Payment_Code. '","'.$Register_Number.'","'.$Term_Year_Id.'","'.$TotalAmount.'","'.$TglBayar.'","'.$xml.'","'.$name.'","'.$Bank_Id.'")');
        $status = 1;
        $exp=null;
      }
      catch (\Exception $e)
      {
        $payment = null;
        // $status = 0;
        $status = 1; // sementara
        $exp = $e->getMessage();
      }
      // dd($exp);
      // if(strpos($exp,'Packets out of order') !== false){
      //   $status = 1;
      //   $exp = null;
      // }
      // if($exp != null){
      //   Alert::error($exp, 'gagal !');
      // }
      try{
        $q_fnc_reff_payment = DB::table('fnc_reff_payment')
                              ->where('Reff_Payment_Code',$Reff_Payment_Code)
                              ->first();
        $ReffPaymentId = $q_fnc_reff_payment->Reff_Payment_Id;

        // dd($ReffPaymentId);
        // dd($exp);

        return response()->json(['status' => $status, 'exp' => $exp, 'Reff_Payment_Id' => $ReffPaymentId]);
      }catch(\Exception $e){
        return response()->json(['status' => $status, 'exp' => $exp, 'Reff_Payment_Id' => null]);
      }

    }


    //edit
    public function Edit($param)
    {
      $acd_student = DB::table('acd_student')->where('Nim','=',$param)->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')->first();
      if ($acd_student == null) {
        $acd_student = DB::table('acd_student')->where('Register_Number','=',$param)->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')->first();
      }
      $Register_Number = $acd_student->Register_Number;
      $ListTagihan=[];
      $i=0;
      $tagihan = DB::select('CALL usp_GetStudentBillChoiche2(?)',[$Register_Number]);
      if ($tagihan != null) {
        foreach ($tagihan as $item) {
          $ListTagihan[$i]['Student_Bill_Id'] = $item->Student_Bill_Id;
          $ListTagihan[$i]['Term_Year_Id'] = $item->Term_Year_Id;
          $ListTagihan[$i]['Cost_Item_Name'] = $item->Cost_Item_Name;
          $ListTagihan[$i]['Payment_Order'] = $item->Payment_Order;
          $ListTagihan[$i]['Start_Date'] = $item->Start_Date;
          $ListTagihan[$i]['End_Date'] = $item->End_Date;
          $ListTagihan[$i]['Amount'] = $item->Amount;
          $ListTagihan[$i]['Is_Must_Paid'] = $item->Is_MustPaid;
          $ListTagihan[$i]['Is_Forced_To_Pay'] = $item->Is_Forced_To_Pay;
          $i++;
        }
      }

      return View('student_bill/edit')
      ->with('Register_Number',$Register_Number)
      ->with('acd_student',$acd_student)
      ->with('ListTagihan',$ListTagihan)
      ->with('param',$param)
      ;
    }
    //edit POST
    public function Edit_Post(Request $request)
    {
      // dd($request->all());
      $Student_Bill_Id = $request->Student_bill_id;
      $Is_Forced_To_Pay = $request->Is_forced_to_pay == true ? '1' : '0';
      try{
       $update = DB::table('fnc_student_bill')
       ->where([['Student_Bill_Id',$Student_Bill_Id]])
       ->update([
         'Is_Forced_To_Pay' => $Is_Forced_To_Pay
       ]);
       // $update = DB::select(DB::raw("UPDATE fnc_student_bill SET Is_Forced_To_Pay = '".chr($Is_Forced_To_Pay)."' WHERE Student_Bill_Id = ".$Student_Bill_Id));
       $status = 1;
       $exp="";
      }
      catch(\Exception $e)
      {
       $status = 0;
       $exp = $e->getMessage();
       // Alert::error($exp, 'gagal !');
      }
      return response()->json(['status' => $status, 'exp' => $exp]);
    }
    //cancel
    public function Cancel($ReffPaymentId)
    {
      $param = Input::get('param');
      $q_fnc_student_payment = DB::table('fnc_student_payment')
                              ->Where('Reff_Payment_Id','=',$ReffPaymentId)
                              ->join('fnc_cost_item','fnc_student_payment.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
                              ->get();

      return View('student_bill/cancel')
      ->with('q_fnc_student_payment',$q_fnc_student_payment)
      ->with('param',$param)
      ->with('ReffPaymentId',$ReffPaymentId)
      ;
    }
    //cancel_post
    public function Cancel_Post($ReffPaymentId)
    {
      $param = Input::get('param');
      $alasan = Input::get('alasan');
      $date_now_string = date("Y-m-d")." 00:00:00";
      $date_now = date("Y-m-d h:i:s",strtotime($date_now_string));
      $q_fnc_student_payment = DB::table('fnc_student_payment')->Where('Reff_Payment_Id','=',$ReffPaymentId)->get();
      $fnc_reff_payment = DB::table('fnc_reff_payment')->Where('Reff_Payment_Id','=',$ReffPaymentId)->first();
      if ($q_fnc_student_payment == null || $fnc_reff_payment == null) {
        Alert::error('Data pembayaran tidak dapat ditemukan', 'Gagal Membatalkan Pembayaran !');
        return redirect('Entry_Pembayaran_Mahasiswa/cancel/'.$ReffPaymentId.'?param='.$param);
      }else {
        foreach ($q_fnc_student_payment as $fnc_student_payment) {
          $Payment_Amount = (0-1) * $fnc_student_payment->Payment_Amount;
          $insert_fnc_student_payment = DB::table('fnc_student_payment')->insert([
            'Payment_Amount'=> $Payment_Amount,
            'Reff_Payment_Id'=> $fnc_student_payment->Reff_Payment_Id,
            'Trans_Order' => $fnc_student_payment->Trans_Order,
            'Term_Year_Id' => $fnc_student_payment->Term_Year_Id,
            'Register_Number' => $fnc_student_payment->Register_Number,
            'Cost_Item_Id' => $fnc_student_payment->Cost_Item_Id,
            'Installment_Order' => $fnc_student_payment->Installment_Order,
            'Payment_Status' => $fnc_student_payment->Payment_Status,
            'Cashier_Id' => $fnc_student_payment->Cashier_Id,
            'Bank_Id' => $fnc_student_payment->Bank_Id,
            'Print_Date' => $fnc_student_payment->Print_Date,
            'Term_Year_Bill_Id' => $fnc_student_payment->Term_Year_Bill_Id,
            'Bill_Type_Id' => $fnc_student_payment->Bill_Type_Id,
            // 'Descripton' => $fnc_student_payment->Descripton,
            'Created_By' => $fnc_student_payment->Created_By,
            'Created_Date' => $fnc_student_payment->Created_Date,
            'Modified_Date' => $fnc_student_payment->Modified_Date,
            'Modified_By' => $fnc_student_payment->Modified_By
            ]);
        }
        $update_fnc_reff_payment = DB::table('fnc_reff_payment')
                    ->where('Reff_Payment_Id',$ReffPaymentId)
                    ->update([
                      'Description' => $alasan,
                      'Total_Amount' => 0,
                      'Modified_By' => Auth::user()->name,
                      'Modified_Date' => $date_now
                    ]);
        
        // delete cicilan yang menginduk ke sini
        $usedInstallments = DB::table('fnc_used_student_installment')->where('Reff_Payment_Id', $ReffPaymentId )->delete();
                    
        if ($update_fnc_reff_payment) {
          Alert::success('Sukses mengubah data', 'Berhasil !');
            return redirect('Entry_Pembayaran_Mahasiswa?param='.$param);
        }else{
          $message = "Tidak dapat menyimpan data";
          return redirect('Entry_Pembayaran_Mahasiswa?param='.$param)->with('message',$message);
        }
      }
    }
}
