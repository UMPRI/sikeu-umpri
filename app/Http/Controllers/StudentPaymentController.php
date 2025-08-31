<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Input;
use DB;
use Redirect;
use Validator;
use PDO;
use Notifiable;
use Alert;
use PDF;


class StudentPaymentController extends Controller
{
    function index(Request $request)
    {
      $tgl_awal = $request->from_date;
      $tgl_akhir = $request->until_date;

      $code = 2;
      if ($tgl_awal != null && $tgl_akhir != null) {
        $q_Fnc_Student_Payment = DB::table('fnc_student_payment')
                                ->leftjoin('fnc_bank', 'fnc_student_payment.Bank_Id','fnc_bank.Bank_Id')
                                ->leftjoin('fnc_bill_type','fnc_student_payment.Bill_Type_Id','fnc_bill_type.Bill_Type_Id')
                                ->leftjoin('fnc_cost_item','fnc_student_payment.Cost_Item_Id','fnc_cost_item.Cost_Item_Id')
                                ->leftJoin('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','fnc_reff_payment.Reff_Payment_Id')
                                ->leftJoin('mstr_term_year','fnc_student_payment.Term_Year_Id','mstr_term_year.Term_Year_Id')
                                ->leftJoin('acd_student','fnc_student_payment.Register_Number','acd_student.Register_Number')
                                ->leftJoin('mstr_department','acd_student.Department_Id','mstr_department.Department_Id')
                                ->leftJoin('fnc_trans_type','fnc_reff_payment.Trans_Type_Id','fnc_trans_type.Trans_Type_Id')
                                ->where('fnc_reff_payment.Payment_Date', '>=', date("Y-m-d", strtotime($tgl_awal)))
                                ->where('fnc_reff_payment.Payment_Date', '<=', date("Y-m-d", strtotime($tgl_akhir)))
                                // ->where('fnc_trans_type.Trans_Type_Code', $code)
                                ->get();
                                // dd($q_Fnc_Student_Payment);
      }else{
        $q_Fnc_Student_Payment = null;
      }

       // dd($q_Fnc_Student_Payment);

      return view('studentpayment.index')
      ->with('Fnc_Student_Payment', $q_Fnc_Student_Payment)
      ->with('tgl_awal', $tgl_awal)
      ->with('tgl_akhir', $tgl_akhir);
    }

    // create ----------------------
    function create(Request $request)
    {
      $Student_Id = $request->Student_Id;
        $q_Student = "";
      if ($Student_Id != null) {
        // Studen e
        $q_Student = DB::table('acd_student')
        ->where('Student_Id', $Student_Id)->first();
      }


      $q_Cost_Item = DB::table('fnc_cost_item')->OrderBy('Cost_Item_Id','desc')->get();

      return view('studentpayment.create')
            ->with('Cost_Item', $q_Cost_Item)
            ->with('Student', $q_Student);
    }
    // akhir create-----------------

    // create_post------------------
    function create_post(Request $request)
    {
      $nim = $request->nim;
      $nama = $request->name;
      $Cost_Item_Id = $request->cost_item_id;
      $nominal = $request->nominal;

      $Payment_Status = "R";
      // cari Register number_____________________________________
      $q_Register_Number = DB::table('acd_student')
                          ->select('Register_Number')
                          ->where('Nim','=',$nim)
                          ->first();


      // cari count_reff_payment__________________________________
      $q_Count_Reff_Payment = DB::table('fnc_reff_payment')
                               ->where('Register_Number','=',$q_Register_Number->Register_Number)
                               ->count();

      $q_Fnc_Reff_Payment = DB::table('fnc_reff_payment')
                            ->where('Register_Number','=',$q_Register_Number->Register_Number)
                            ->first();

      // cari acd_student_________________________________________
      $q_Student = DB::table('acd_student')
                   ->where('Register_Number','=',$q_Register_Number->Register_Number)
                   ->first();

      // proses e ________________________________________________
      $penjumlahan = $q_Count_Reff_Payment + 1;

      $Reff_Payment_Code = $q_Register_Number->Register_Number + $penjumlahan;
      $Total_Amount = $nominal * (0 - 1);
      $Payment_Date = Date('Y-m-d');

      $Term_Year_Id = DB::table('mstr_term_year')
                        ->where('Start_Date','<=',$Payment_Date)
                        ->where('End_Date','>=',$Payment_Date)
                        ->first();

      $Reff_Payment_Id = DB::table('fnc_reff_payment')
                ->insertGetId([
                  'Reff_Payment_Code' => $Reff_Payment_Code,
                  'Total_Amount' => $Total_Amount,
                  'Payment_Date' => $Payment_Date,
                  'Register_Number' => $q_Register_Number->Register_Number,
                  'Trans_Type_Id' => 1
                ]);

      if($Reff_Payment_Id != null){

      $Student_Payment_Id = DB::table('fnc_student_payment')
                            ->insertGetId([
                              'Reff_Payment_Id' => $Reff_Payment_Id,
                              'Cost_Item_Id' => $Cost_Item_Id,
                              'Payment_Amount' => $Total_Amount,
                              'Register_Number' => $q_Register_Number->Register_Number,
                              'Term_Year_Id' => $Term_Year_Id->Term_Year_Id,
                              'Payment_Status' => $Payment_Status
                            ]);

      return redirect('Set_Aturan_Pengembalian/cetak/'.$Student_Payment_Id."?tgl_awal=".$Term_Year_Id->Start_Date."&tgl_akhir=".$Term_Year_Id->End_Date);
      }
    }

    function cetak(Request $request,$Student_Payment_Id)
    {
      $tgl_awal = $request->tgl_awal;
      $tgl_akhir = $request->tgl_akhir;

      $q_Student_Payment = DB::table('fnc_student_payment')
                            ->where('Student_Payment_Id', $Student_Payment_Id)
                            ->first();

      if ($q_Student_Payment != null) {
        $Student = DB::table('acd_student')
                    ->where('Register_Number','=', $q_Student_Payment->Register_Number)
                    ->first();

        $Payment_Date = Date('Y-m-d');

        $Cost_Item = DB::table('fnc_cost_item')
                      ->where('Cost_Item_Id',$q_Student_Payment->Cost_Item_Id)
                      ->first();

        $tgl_awal2 = date("Y-m-d", strtotime($Payment_Date));
        $tgl_akhir2 = date("Y-m-d", strtotime($tgl_awal2. ' + 2 days'));


        return view('studentpayment.cetak')
              ->with('Student_Payment_Id', $Student_Payment_Id)
              ->with('Student', $Student)
              ->with('Payment_Date', $Payment_Date)
              ->with('Cost_Item', $Cost_Item->Cost_Item_Name)
              ->with('Total_Amount', $q_Student_Payment->Payment_Amount * (0-1))
              ->with('tgl_awal', $tgl_awal2)
              ->with('tgl_akhir', $tgl_akhir2);
      }

    }
    // Akhir create_post------------


    // ceteak pdfnya------------------------------------------
    function pdf(Request $request, $Student_Payment_Id)
    {
      $Fnc_Student_Payment = DB::table('fnc_student_payment')
                  ->where('Student_Payment_Id','=', $Student_Payment_Id)
                  ->leftjoin('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=', 'fnc_reff_payment.Reff_Payment_Id')
                  ->leftJoin('fnc_cost_item','fnc_student_payment.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
                  ->leftjoin('mstr_term_year','fnc_student_payment.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
                  ->leftjoin('mstr_term','mstr_term_year.Term_Id','=','mstr_term.Term_Id')
                  ->leftjoin('acd_student','fnc_student_payment.Register_Number','=','acd_student.Register_Number')
                  ->leftjoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
                  ->leftjoin('mstr_faculty','mstr_department.Faculty_Id','=','mstr_faculty.Faculty_Id')
                  ->leftjoin('fnc_bank','fnc_reff_payment.Bank_Id','=','fnc_bank.Bank_Id')
                  ->first();
                  // dd($Fnc_Student_Payment);
      $tglCetak = Date('Y-m-d');
      $tglBayar = date('d-F-Y', strtotime($Fnc_Student_Payment->Payment_Date));

      // dd($Fnc_Student_Payment->Reff_Payment_Code);
      View()->share('Fnc_Student_Payment', $Fnc_Student_Payment);
      $pdf =  PDF::loadView('studentpayment.pdf');
      return $pdf->stream('KuitansiPengganti_'.$Fnc_Student_Payment->Reff_Payment_Code.'.pdf', array("Attachment" => false));
      exit(0);
      return view('studentpayment/pdf');
    }
    // ceteak pdfnya------------------------------------------

    //student ----------------------
    function student(Request $request)
    {
      $Entry_Year_Id = $request->Entry_Year_Id;
      $Department_Id = $request->Department_Id;

      $q_Entry_Year = DB::table('mstr_entry_year')->OrderBy('Entry_Year_Id','desc')->get();
      $q_Department = DB::table('mstr_department')->where('Faculty_Id', '=' ,'1')->OrderBy('Department_Code','asc')->get();


      $Row_Page = $request->Row_Page;
      if ($Row_Page == null) {
        $Row_Page = 10;
      }
      if ($request->search == null) {
        // code...
        $q_Student = DB::table('acd_student')
        ->leftjoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
        ->leftjoin('mstr_entry_year','acd_student.Entry_Year_Id','=','mstr_entry_year.Entry_Year_Id')
        ->leftjoin('mstr_gender', 'acd_student.Gender_Id','=','mstr_gender.Gender_Id')
        ->where('acd_student.Department_Id', $Department_Id)
        ->where('acd_student.Entry_Year_Id', $Entry_Year_Id)
        ->paginate($Row_Page);
      }else {
        $q_Student = DB::table('acd_student')
        ->leftjoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
        ->leftjoin('mstr_entry_year','acd_student.Entry_Year_Id','=','mstr_entry_year.Entry_Year_Id')
        ->leftjoin('mstr_gender', 'acd_student.Gender_Id','=','mstr_gender.Gender_Id')
        ->where('acd_student.Department_Id', $Department_Id)
        ->where('acd_student.Entry_Year_Id', $Entry_Year_Id)
        ->where('acd_student.Nim','Like' ,'%'.$request->search.'%')
        ->orwhere('acd_student.Full_Name','Like' ,'%'.$request->search.'%')
        ->where('acd_student.Department_Id', $Department_Id)
        ->where('acd_student.Entry_Year_Id', $Entry_Year_Id)
        ->paginate($Row_Page);
      }

      $q_Student->appends([
            'Entry_Year_Id'=> $Entry_Year_Id,
            'Department_Id'=> $Department_Id,
            'Row_Page'=> $Row_Page ,
            'search'=> $request->search
          ]);


      // $q_Student = DB::table('acd_student')

      return view('studentpayment.student')
            ->with('Entry_Year',  $q_Entry_Year)
            ->with('Entry_Year_Id', $Entry_Year_Id)
            ->with('Department_Id', $Department_Id)
            ->with('Department', $q_Department)
            ->with('Row_Page', $Row_Page)
            ->with('Student', $q_Student);
    }
    //akhir student =---------------

    // Edit----------------------------------------------------------------
    function edit(Request $request)
    {

      $Student_Payment_Id = $request->id;

      $q_Fnc_Student_Payment = DB::table('fnc_student_payment')
                          ->where('Student_Payment_Id', $Student_Payment_Id)
                          ->first();

      $q_Acd_Student = DB::table('acd_student')
                          ->where('Register_Number', $q_Fnc_Student_Payment->Register_Number)
                          ->first();

      $q_Fnc_Reff_Payment = DB::table('fnc_reff_payment')
                              ->where('Reff_Payment_Id', $q_Fnc_Student_Payment->Reff_Payment_Id)
                              ->first();

      $nim = $q_Acd_Student->Nim;
      $Cost_Item_Id = $q_Fnc_Student_Payment->Cost_Item_Id;
      $Total_Amount = $q_Fnc_Reff_Payment->Total_Amount * (0-1);
      $q_Cost_Item = DB::table('fnc_cost_item')->get();

      return view('studentpayment.edit')
              ->with('cost_item', $q_Cost_Item)
              ->with('nim', $nim)
              ->with('cost_item_id', $Cost_Item_Id)
              ->with('total_amount', $Total_Amount)
              ->with('student_payment_id', $q_Fnc_Student_Payment->Student_Payment_Id);
    }
    // Edit----------------------------------------------------------------


    // Edit Post------------------------------------------------------------
    function edit_post(Request $request)
    {
      $Nim = $request->nim;
      $Total_Amount = $request->total_amount * (0-1);
      $Cost_Item_Id = $request->cost_item_id;
      $Student_Payment_Id = $request->Student_Payment_Id;

      // dd($Student_Payment_Id);
      $Register_Number = DB::table('acd_student')
                        ->select('Register_Number')
                        ->where('Nim', '=' , $Nim)
                        ->first();

      $Reff_Payment_Id = DB::table('fnc_student_payment')
                            ->select('Reff_Payment_Id')
                            ->where('Student_Payment_Id', "=" ,$Student_Payment_Id)
                            ->first();

      $update = DB::table('fnc_student_payment')
                  ->where('Student_Payment_Id', '=' ,$Student_Payment_Id)
                  ->update([
                    'Cost_Item_Id' => $Cost_Item_Id,
                    'Register_Number' => $Register_Number->Register_Number,
                    'Payment_Amount' => $Total_Amount
                  ]);

      if ($update) {
        $update2 = DB::table('fnc_reff_payment')
                      ->where('Reff_Payment_Id', '=' , $Reff_Payment_Id->Reff_Payment_Id)
                      ->update([
                        'Total_Amount' => $Total_Amount,
                        'Register_Number' => $Register_Number->Register_Number
                      ]);

        if ($update2) {
          $q_Acd_Student = DB::table('acd_student')
                              ->where('Nim', $Nim)
                              ->first();
          $Payment_Date = Date('Y-m-d');

          $q_Student_Payment = DB::table('fnc_student_payment')
                                  ->where('Student_Payment_Id', $Student_Payment_Id)
                                  ->first();

          $Cost_Item = DB::table('fnc_cost_item')
                        ->where('Cost_Item_Id',$q_Student_Payment->Cost_Item_Id)
                        ->first();

          return view('studentpayment.cetak')
                ->with('Student_Payment_Id', $Student_Payment_Id)
                ->with('Student', $q_Acd_Student)
                ->with('Payment_Date', $Payment_Date)
                ->with('Cost_Item', $Cost_Item->Cost_Item_Name)
                ->with('Total_Amount', $q_Student_Payment->Payment_Amount * (0-1));
        }
      }
    }
    // Edit Post------------------------------------------------------------

    function delete(Request $request)
    {
      $Student_Payment_Id = $request->id;
      if ($Student_Payment_Id != null) {
        $delete = DB::table('fnc_student_payment')
                    ->where('Student_Payment_Id', '=' ,$Student_Payment_Id)
                    ->delete();
        if ($delete) {
          Alert::success('Sukses menghapus data', 'Berhasil !');
          return redirect('Set_Aturan_Pengembalian');
        }
      }

    }
}
