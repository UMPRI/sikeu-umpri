<?php

namespace App\Http\Controllers;

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
use PDF;

class LaporanController extends Controller
{
  public function __construct()
  {
        $this->middleware('auth');
  }

  // laporan prodi -----------------------------------------------------------------------------------------\\
  public function Lp_Prodi()
  {
    // $access = auth()->user()->Access();
    // foreach ($access as $data) {$akses = explode("|",$data->cost_item);}
    // if (in_array(1,$menu)) {
      $Term_Year_Id = Input::get('Term_Year_Id');

      $q_Term_Year_Id = DB::table('mstr_term_year')->Where('Term_Id','=',1)->OrderBy('Term_Year_Id','desc')->get();

      $lap = DB::table('fnc_student_payment')
                 ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
                 ->join('acd_student','fnc_student_payment.Register_Number','=','acd_student.Register_Number')
                 ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
                 ->where([['fnc_student_payment.Term_Year_Id','=', $Term_Year_Id],['Cost_Item_Id','!=',1]])
                 ->OrderBy('acd_student.Nim','asc')
                 ->GroupBy('mstr_department.Department_Id')
                 ->select(DB::raw('
                    mstr_department.Department_Code as Department_Code,
                    mstr_department.Department_Name as Department_Name,
                    mstr_department.Department_Id as Department_Id,
                    SUM(fnc_student_payment.Payment_Amount) as Payment_Amount
                 '))->get();

     return view('laporan/lp_prodi')
     ->with('q_data',$lap)
     ->with('Term_Year_Id',$Term_Year_Id)
     ->with('q_Term_Year_Id',$q_Term_Year_Id);
     // }else{
     //   return view('error/403');
     // }
  }

  // laporan mahasiswa -----------------------------------------------------------------------------------------\\
  public function Lp_Mahasiswa()
  {
    $Term_Year_Id = Input::get('Term_Year_Id');
    $Department_Id = Input::get('Department_Id');

    $q_Department_Id = DB::table('mstr_department')->OrderBy('Department_Code','asc')->get();
    $q_Term_Year_Id = DB::table('mstr_term_year')->Where('Term_Id','=',1)->OrderBy('Term_Year_Id','desc')->get();

    $lap = DB::table('fnc_student_payment')
               ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
               ->join('acd_student','fnc_student_payment.Register_Number','=','acd_student.Register_Number')
               ->join('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
               ->where([['fnc_student_payment.Term_Year_Id','like', $Term_Year_Id.'%'],['acd_student.Department_Id','=',$Department_Id],['Cost_Item_Id','!=',27]])
               ->OrderBy('acd_student.Nim','asc')
               ->GroupBy('acd_student.Nim')
               ->select(DB::raw('
                    acd_student.Nim as Nim,
                    acd_student.Full_Name as Full_Name,
                    mstr_class_program.Class_Program_Name as Class_Program_Name,
                    SUM(fnc_student_payment.Payment_Amount) as Payment_Amount
               '))->get();

     return view('laporan/lp_mahasiswa')
     ->with('q_data',$lap)
     ->with('Department_Id',$Department_Id)
     ->with('q_Department_Id',$q_Department_Id)
     ->with('Term_Year_Id',$Term_Year_Id)
     ->with('q_Term_Year_Id',$q_Term_Year_Id);
  }

  //riwayat pembayaran mahasiswa  -----------------------------------------------------------------------------------------\\
  public function RP_Mahasiswa()
  {
    $param = Input::get('param');
    $Term_Year_Id = Input::get('Term_Year_Id');

    $q_Term_Year_Id = DB::table('mstr_term_year')->Where('Term_Id','=',1)->OrderBy('Term_Year_Id','desc')->get();

    $Student = DB::table('acd_student')->where('Nim','=',$param)->leftJoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')->first();
    if ($Student == null) {
      $Student = DB::table('acd_student')->where('Register_Number','=',$param)->leftJoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')->first();
    }
    if ($Student != null) {
      $q_Student = DB::table('acd_student')->where('Nim','=',$param)->leftJoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')->first();
      if ($q_Student == null) {
        $q_Student = DB::table('acd_student')->where('Register_Number','=',$param)->leftJoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')->first();
      }
      if ($Term_Year_Id != null)
      {
        $Student_Payment = DB::table('fnc_student_payment')
        ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
        ->where([['fnc_student_payment.Register_Number','=',$Student->Register_Number],['fnc_student_payment.Cost_Item_Id','!=', 27],['Term_Year_Bill_Id','like', $Term_Year_Id.'%']])
        ->GroupBy('fnc_student_payment.Reff_Payment_Id','fnc_student_payment.Term_Year_Id','fnc_reff_payment.Payment_Date')
        ->select(DB::raw('
        fnc_student_payment.Reff_Payment_Id as Reff_Payment_Id,
        fnc_student_payment.Term_Year_Bill_Id as Term_Year_Bill_Id,
        fnc_student_payment.Installment_Order as Installment_Order,
        fnc_reff_payment.Payment_Date as Payment_Date,
        SUM(fnc_student_payment.Payment_Amount) as Total_Amount'))->get();
      }else{
        $Student_Payment = DB::table('fnc_student_payment')
        ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
        ->where([['fnc_student_payment.Register_Number','=',$Student->Register_Number],['fnc_student_payment.Cost_Item_Id','!=', 27]])
        ->GroupBy('fnc_student_payment.Reff_Payment_Id','fnc_student_payment.Term_Year_Id','fnc_reff_payment.Payment_Date')
        ->select(DB::raw('
           fnc_student_payment.Reff_Payment_Id as Reff_Payment_Id,
           fnc_student_payment.Term_Year_Bill_Id as Term_Year_Bill_Id,
           fnc_student_payment.Installment_Order as Installment_Order,
           fnc_reff_payment.Payment_Date as Payment_Date,
           SUM(fnc_student_payment.Payment_Amount) as Total_Amount
        '))->get();
      }
      return View('laporan/rp_mahasiswa')
      ->with('data',$Student_Payment)
      ->with('param',$param)
      ->with('q_student',$q_Student)
      ->with('Term_Year_Id',$Term_Year_Id)
      ->with('q_Term_Year_Id',$q_Term_Year_Id);
    }
    Alert::error('TIdak ada data mahasiswa yang ditemukan, pastikan NiM / No. Reg nya benar', 'TIdak ada data !');
    return View('laporan/rp_mahasiswa')
    ->with('param',$param)
    ->with('Term_Year_Id',$Term_Year_Id)
    ->with('q_Term_Year_Id',$q_Term_Year_Id);
  }

  //laporan pembayaran Bank --------------------------------------------------------------------------------------------------------------------\\
  public function Lp_Bank()
  {
    $Start = Input::get('StartDate');
    $StartDate = date("m/d/Y",strtotime($Start)) ;
    if ($StartDate == null || !isset($_GET['StartDate'])) {
      $StartDate = Date("m/d/Y");
    }
    $Start_Date = Date("Y-m-d",strtotime($StartDate));
    $End = Input::get('EndDate');
    $EndDate = date("m/d/Y",strtotime($End)) ;
    if ($EndDate == null || !isset($_GET['EndDate'])) {
      $EndDate = Date("m/d/Y");
    }
    $End_Date = Date("Y-m-d",strtotime($EndDate));
    $Bank_Id = Input::get('Bank_Id');
    if ($Bank_Id != "")
    {
      $LapPerBank = DB::table('fnc_student_payment')
      ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
      ->join('fnc_bank','fnc_reff_payment.Bank_Id','=','fnc_bank.Bank_Id')
      ->where([['fnc_reff_payment.Payment_Date','>=',$Start_Date],['fnc_reff_payment.Payment_Date','<=',$End_Date],['fnc_bank.Bank_Id','=',$Bank_Id]])
      ->GroupBy('fnc_reff_payment.Payment_Date','fnc_bank.Bank_Name')
      ->select(DB::raw('
      fnc_reff_payment.Bank_Id as Bank_Id,
      fnc_reff_payment.Payment_Date as Payment_Date,
      fnc_bank.Bank_Name as Bank_Name,
      SUM(fnc_student_payment.Payment_Amount) as Payment_Amount'))
      ->OrderBy('Payment_Date')
      ->get();
    }else{
      $LapPerBank = DB::table('fnc_student_payment')
      ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
      ->join('fnc_bank','fnc_reff_payment.Bank_Id','=','fnc_bank.Bank_Id')
      ->where([['fnc_reff_payment.Payment_Date','>=',$Start_Date],['fnc_reff_payment.Payment_Date','<=',$End_Date]])
      ->GroupBy('fnc_reff_payment.Payment_Date','fnc_bank.Bank_Name')
      ->select(DB::raw('
          fnc_reff_payment.Bank_Id as Bank_Id,
          fnc_reff_payment.Payment_Date as Payment_Date,
          fnc_bank.Bank_Name as Bank_Name,
          SUM(fnc_student_payment.Payment_Amount) as Payment_Amount
      '))
      ->OrderBy('Payment_Date')
      ->get();
    }

    $q_Bank_Id = DB::table('fnc_bank')->get();

    return View('laporan/lp_bank')
    ->with('LapPerBank',$LapPerBank)
    ->with('q_Bank_Id',$q_Bank_Id)
    ->with('StartDate',$StartDate)
    ->with('EndDate',$EndDate)
    ->with('Bank_Id',$Bank_Id)
    ;
  }

  // laporan per hari bank --------------------------------------------------------------------------------------------------------------------------\\
  public function Lp_Hari_Bank($value='')
  {
    if (isset($_GET['Tgl_Bayar'])) {
      $Payment_date = Input::get('Tgl_Bayar');
      $PaymentDate = date("m/d/Y",strtotime($Payment_date));
    }else{
      $Pdate = Input::get('TglBayar');
      $PaymentDate = date("m/d/Y",strtotime($Pdate)) ;
      if ($PaymentDate == null) {
        $PaymentDate = Date("m/d/Y");
      }
      $Payment_date = Date("Y-m-d",strtotime($PaymentDate));
    }
    $Bank_Id = Input::get('Bank');
    if ($Bank_Id != "")
    {
      $LapPerHariBank = DB::table('fnc_student_payment')
      ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
      ->join('acd_student','fnc_reff_payment.Register_Number','=','acd_student.Register_Number')
      ->leftjoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
      ->leftjoin('mstr_term_year','fnc_student_payment.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
      ->leftjoin('fnc_cost_item','fnc_student_payment.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
      ->where('fnc_reff_payment.Payment_Date','=',$Payment_date)
      ->GroupBy('fnc_reff_payment.Reff_Payment_Id','fnc_reff_payment.Bank_Id','acd_student.Nim','fnc_reff_payment.Payment_Date')
      ->select(DB::raw('
          fnc_reff_payment.Reff_Payment_Id as Reff_Payment_Id,
          fnc_reff_payment.Bank_Id as Bank_Id,
          fnc_reff_payment.Payment_Date as TglBayar,
          acd_student.Nim as Nim,
          acd_student.Full_Name as NamaMhs,
          mstr_department.Department_Name as Department_Name,
          SUM(fnc_student_payment.Payment_Amount) as Biaya,
          fnc_student_payment.Installment_Order as Installment_Order,
          mstr_term_year.Year_Id as Year_Id,
          fnc_cost_item.Acronym as Acronym
      '))->OrderBy('TglBayar','asc')->get();
    }else
    {
      $LapPerHariBank = DB::table('fnc_student_payment')
      ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
      ->join('acd_student','fnc_reff_payment.Register_Number','=','acd_student.Register_Number')
      ->leftjoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
      ->leftjoin('mstr_term_year','fnc_student_payment.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
      ->leftjoin('fnc_cost_item','fnc_student_payment.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
      ->where([['fnc_reff_payment.Payment_Date','=',$Payment_date],['fnc_reff_payment.Bank_Id','=',$Bank_Id]])
      ->GroupBy('fnc_reff_payment.Reff_Payment_Id','fnc_reff_payment.Bank_Id','acd_student.Nim','fnc_reff_payment.Payment_Date')
      ->select(DB::raw('
          fnc_reff_payment.Reff_Payment_Id as Reff_Payment_Id,
          fnc_reff_payment.Bank_Id as Bank_Id,
          fnc_reff_payment.Payment_Date as TglBayar,
          acd_student.Nim as Nim,
          acd_student.Full_Name as NamaMhs,
          mstr_department.Department_Name as Department_Name,
          SUM(fnc_student_payment.Payment_Amount) as Biaya,
          fnc_student_payment.Installment_Order as Installment_Order,
          mstr_term_year.Year_Id as Year_Id,
          fnc_cost_item.Acronym as Acronym
      '))->OrderBy('TglBayar','asc')->get();
    }

    $q_Bank_Id = DB::table('fnc_bank')->get();

    return View('laporan/lp_bank_detail')
    ->with('LapPerHariBank',$LapPerHariBank)
    ->with('q_Bank_Id',$q_Bank_Id)
    ->with('PaymentDate',$PaymentDate)
    ->with('Bank_Id',$Bank_Id)
    ;
  }


  // pembayaran mahasiswa per item ----------------------------------------------------------------------------------------------------------------\\
  public function P_Mahasiswa_Item()
  {

    $Cost_Item_Id = Input::get('Cost_Item_Id');
    $Term_Year_Id = Input::get('Term_Year_Id');
    $Payment_Order = Input::get('Payment_Order');
    $Start = Input::get('StartDate');
    $StartDate = date("m/d/Y",strtotime($Start)) ;
    if ($StartDate == null || !isset($_GET['StartDate'])) {
      $StartDate = Date("m/d/Y");
    }
    $Start_Date = Date("Y-m-d",strtotime($StartDate));
    $End = Input::get('EndDate');
    $EndDate = date("m/d/Y",strtotime($End)) ;
    if ($EndDate == null || !isset($_GET['EndDate'])) {
      $EndDate = Date("m/d/Y");
    }
    $End_Date = Date("Y-m-d",strtotime($EndDate));
    $JmlTahapCostSchedPersonal = 5;
    $Payment_Orders = array();
    for ($i = 0; $i <= $JmlTahapCostSchedPersonal; $i++)
    {
      if($i == 0){
        $Payment_Orders[] = "Lunas";
      }else{
        $Payment_Orders[] = $i;
      }
    }
    $q_Term_Year_Id = DB::table('mstr_term_year')->Where('Term_Id','=',1)->OrderBy('Term_Year_Id','desc')->get();
    $q_Cost_Item_Id = DB::table('fnc_cost_item')->OrderBy('Cost_Item_Name','asc')->get();
    // dd($q_Cost_Item_Id);

    if ($Term_Year_Id != null && $Cost_Item_Id != null && $Payment_Order != null) {
      $item = DB::table('fnc_student_payment')
      ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
      ->join('acd_student','fnc_student_payment.Register_Number','=','acd_student.Register_Number')
      ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
      ->join('fnc_cost_item','fnc_student_payment.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
      ->where([
        ['fnc_reff_payment.Payment_Date','>=',$Start_Date],
        ['fnc_reff_payment.Payment_Date','<=',$End_Date],
        ['fnc_student_payment.Term_Year_Bill_Id','=',$Term_Year_Id],
        ['fnc_student_payment.Cost_Item_Id','=',$Cost_Item_Id],
        ['fnc_student_payment.Installment_Order','=',$Payment_Order]
      ])
      ->GroupBy('fnc_student_payment.Term_Year_Bill_Id','mstr_department.Department_Id','fnc_student_payment.Installment_Order','fnc_student_payment.Cost_Item_Id')
      ->OrderBy('mstr_department.Department_Code','fnc_student_payment.Installment_Order')
      ->select(DB::raw('
          mstr_department.Department_Code as Department_Code,
          mstr_department.Department_Id as Department_Id,
          mstr_department.Department_Name as Department_Name,
          fnc_cost_item.Cost_Item_Id as Cost_Item_Id,
          fnc_student_payment.Term_Year_Bill_Id as Term_Year_Bill_Id,
          fnc_cost_item.Cost_Item_Name as Cost_Item_Name,
          fnc_student_payment.Installment_Order as Installment_Order,
          SUM(fnc_student_payment.Payment_Amount) as Payment_Amount
      '))->get();
    }elseif ($Term_Year_Id != null && $Cost_Item_Id != null && $Payment_Order == null) {
      $item = DB::table('fnc_student_payment')
      ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
      ->join('acd_student','fnc_student_payment.Register_Number','=','acd_student.Register_Number')
      ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
      ->join('fnc_cost_item','fnc_student_payment.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
      ->where([
        ['fnc_reff_payment.Payment_Date','>=',$Start_Date],
        ['fnc_reff_payment.Payment_Date','<=',$End_Date],
        ['fnc_student_payment.Term_Year_Bill_Id','=',$Term_Year_Id],
        ['fnc_student_payment.Cost_Item_Id','=',$Cost_Item_Id]
      ])
      ->GroupBy('fnc_student_payment.Term_Year_Bill_Id','mstr_department.Department_Id','fnc_student_payment.Installment_Order','fnc_student_payment.Cost_Item_Id')
      ->OrderBy('mstr_department.Department_Code','fnc_student_payment.Installment_Order')
      ->select(DB::raw('
          mstr_department.Department_Code as Department_Code,
          mstr_department.Department_Id as Department_Id,
          mstr_department.Department_Name as Department_Name,
          fnc_cost_item.Cost_Item_Id as Cost_Item_Id,
          fnc_student_payment.Term_Year_Bill_Id as Term_Year_Bill_Id,
          fnc_cost_item.Cost_Item_Name as Cost_Item_Name,
          fnc_student_payment.Installment_Order as Installment_Order,
          SUM(fnc_student_payment.Payment_Amount) as Payment_Amount
      '))->get();
    }elseif ($Term_Year_Id != null && $Cost_Item_Id == null && $Payment_Order != null) {
      $item = DB::table('fnc_student_payment')
      ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
      ->join('acd_student','fnc_student_payment.Register_Number','=','acd_student.Register_Number')
      ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
      ->join('fnc_cost_item','fnc_student_payment.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
      ->where([
        ['fnc_reff_payment.Payment_Date','>=',$Start_Date],
        ['fnc_reff_payment.Payment_Date','<=',$End_Date],
        ['fnc_student_payment.Term_Year_Bill_Id','=',$Term_Year_Id],
        ['fnc_student_payment.Installment_Order','=',$Payment_Order]
      ])
      ->GroupBy('fnc_student_payment.Term_Year_Bill_Id','mstr_department.Department_Id','fnc_student_payment.Installment_Order','fnc_student_payment.Cost_Item_Id')
      ->OrderBy('mstr_department.Department_Code','fnc_student_payment.Installment_Order')
      ->select(DB::raw('
          mstr_department.Department_Code as Department_Code,
          mstr_department.Department_Id as Department_Id,
          mstr_department.Department_Name as Department_Name,
          fnc_cost_item.Cost_Item_Id as Cost_Item_Id,
          fnc_student_payment.Term_Year_Bill_Id as Term_Year_Bill_Id,
          fnc_cost_item.Cost_Item_Name as Cost_Item_Name,
          fnc_student_payment.Installment_Order as Installment_Order,
          SUM(fnc_student_payment.Payment_Amount) as Payment_Amount
      '))->get();
    }elseif ($Term_Year_Id == null && $Cost_Item_Id != null && $Payment_Order != null) {
      $item = DB::table('fnc_student_payment')
      ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
      ->join('acd_student','fnc_student_payment.Register_Number','=','acd_student.Register_Number')
      ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
      ->join('fnc_cost_item','fnc_student_payment.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
      ->where([
        ['fnc_reff_payment.Payment_Date','>=',$Start_Date],
        ['fnc_reff_payment.Payment_Date','<=',$End_Date],
        ['fnc_student_payment.Cost_Item_Id','=',$Cost_Item_Id],
        ['fnc_student_payment.Installment_Order','=',$Payment_Order]
      ])
      ->GroupBy('fnc_student_payment.Term_Year_Bill_Id','mstr_department.Department_Id','fnc_student_payment.Installment_Order','fnc_student_payment.Cost_Item_Id')
      ->OrderBy('mstr_department.Department_Code','fnc_student_payment.Installment_Order')
      ->select(DB::raw('
          mstr_department.Department_Code as Department_Code,
          mstr_department.Department_Id as Department_Id,
          mstr_department.Department_Name as Department_Name,
          fnc_cost_item.Cost_Item_Id as Cost_Item_Id,
          fnc_student_payment.Term_Year_Bill_Id as Term_Year_Bill_Id,
          fnc_cost_item.Cost_Item_Name as Cost_Item_Name,
          fnc_student_payment.Installment_Order as Installment_Order,
          SUM(fnc_student_payment.Payment_Amount) as Payment_Amount
      '))->get();
    }elseif ($Term_Year_Id != null && $Cost_Item_Id == null && $Payment_Order == null) {
      $item = DB::table('fnc_student_payment')
      ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
      ->join('acd_student','fnc_student_payment.Register_Number','=','acd_student.Register_Number')
      ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
      ->join('fnc_cost_item','fnc_student_payment.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
      ->where([
        ['fnc_reff_payment.Payment_Date','>=',$Start_Date],
        ['fnc_reff_payment.Payment_Date','<=',$End_Date],
        ['fnc_student_payment.Term_Year_Bill_Id','=',$Term_Year_Id]
      ])
      ->GroupBy('fnc_student_payment.Term_Year_Bill_Id','mstr_department.Department_Id','fnc_student_payment.Installment_Order','fnc_student_payment.Cost_Item_Id')
      ->OrderBy('mstr_department.Department_Code','fnc_student_payment.Installment_Order')
      ->select(DB::raw('
          mstr_department.Department_Code as Department_Code,
          mstr_department.Department_Id as Department_Id,
          mstr_department.Department_Name as Department_Name,
          fnc_cost_item.Cost_Item_Id as Cost_Item_Id,
          fnc_student_payment.Term_Year_Bill_Id as Term_Year_Bill_Id,
          fnc_cost_item.Cost_Item_Name as Cost_Item_Name,
          fnc_student_payment.Installment_Order as Installment_Order,
          SUM(fnc_student_payment.Payment_Amount) as Payment_Amount
      '))->get();
    }elseif ($Term_Year_Id == null && $Cost_Item_Id != null && $Payment_Order == null) {
      $item = DB::table('fnc_student_payment')
      ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
      ->join('acd_student','fnc_student_payment.Register_Number','=','acd_student.Register_Number')
      ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
      ->join('fnc_cost_item','fnc_student_payment.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
      ->where([
        ['fnc_reff_payment.Payment_Date','>=',$Start_Date],
        ['fnc_reff_payment.Payment_Date','<=',$End_Date],
        ['fnc_student_payment.Cost_Item_Id','=',$Cost_Item_Id]
      ])
      ->GroupBy('fnc_student_payment.Term_Year_Bill_Id','mstr_department.Department_Id','fnc_student_payment.Installment_Order','fnc_student_payment.Cost_Item_Id')
      ->OrderBy('mstr_department.Department_Code','fnc_student_payment.Installment_Order')
      ->select(DB::raw('
          mstr_department.Department_Code as Department_Code,
          mstr_department.Department_Id as Department_Id,
          mstr_department.Department_Name as Department_Name,
          fnc_cost_item.Cost_Item_Id as Cost_Item_Id,
          fnc_student_payment.Term_Year_Bill_Id as Term_Year_Bill_Id,
          fnc_cost_item.Cost_Item_Name as Cost_Item_Name,
          fnc_student_payment.Installment_Order as Installment_Order,
          SUM(fnc_student_payment.Payment_Amount) as Payment_Amount
      '))->get();
    }elseif ($Term_Year_Id == null && $Cost_Item_Id == null && $Payment_Order != null) {
      $item = DB::table('fnc_student_payment')
                  ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
                  ->join('acd_student','fnc_student_payment.Register_Number','=','acd_student.Register_Number')
                  ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
                  ->join('fnc_cost_item','fnc_student_payment.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
                  ->where([
                    ['fnc_reff_payment.Payment_Date','>=',$Start_Date],
                    ['fnc_reff_payment.Payment_Date','<=',$End_Date],
                    ['fnc_student_payment.Installment_Order','=',$Payment_Order]
                  ])
                  ->GroupBy('fnc_student_payment.Term_Year_Bill_Id','mstr_department.Department_Id','fnc_student_payment.Installment_Order','fnc_student_payment.Cost_Item_Id')
                  ->OrderBy('mstr_department.Department_Code','fnc_student_payment.Installment_Order')
                  ->select(DB::raw('
                      mstr_department.Department_Code as Department_Code,
                      mstr_department.Department_Id as Department_Id,
                      mstr_department.Department_Name as Department_Name,
                      fnc_cost_item.Cost_Item_Id as Cost_Item_Id,
                      fnc_student_payment.Term_Year_Bill_Id as Term_Year_Bill_Id,
                      fnc_cost_item.Cost_Item_Name as Cost_Item_Name,
                      fnc_student_payment.Installment_Order as Installment_Order,
                      SUM(fnc_student_payment.Payment_Amount) as Payment_Amount
                  '))->get();
    }else{
      $item = DB::table('fnc_student_payment')
                  ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
                  ->join('acd_student','fnc_student_payment.Register_Number','=','acd_student.Register_Number')
                  ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
                  ->join('fnc_cost_item','fnc_student_payment.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
                  ->where([['fnc_reff_payment.Payment_Date','>=',$Start_Date],['fnc_reff_payment.Payment_Date','<=',$End_Date]])
                  ->GroupBy('fnc_student_payment.Term_Year_Bill_Id','mstr_department.Department_Id','fnc_student_payment.Installment_Order','fnc_student_payment.Cost_Item_Id')
                  ->OrderBy('mstr_department.Department_Code','fnc_student_payment.Installment_Order')
                  ->select(DB::raw('
                      mstr_department.Department_Code as Department_Code,
                      mstr_department.Department_Id as Department_Id,
                      mstr_department.Department_Name as Department_Name,
                      fnc_cost_item.Cost_Item_Id as Cost_Item_Id,
                      fnc_student_payment.Term_Year_Bill_Id as Term_Year_Bill_Id,
                      fnc_cost_item.Cost_Item_Name as Cost_Item_Name,
                      fnc_student_payment.Installment_Order as Installment_Order,
                      SUM(fnc_student_payment.Payment_Amount) as Payment_Amount
                  '))->get();
    }

    return View('laporan/p_mahasiswa_item')
    ->with('item',$item)
    ->with('Cost_Item_Id',$Cost_Item_Id)
    ->with('Term_Year_Id',$Term_Year_Id)
    ->with('Payment_Order',$Payment_Order)
    ->with('StartDate',$StartDate)
    ->with('EndDate',$EndDate)
    ->with('Payment_Orders',$Payment_Orders)
    ->with('q_Term_Year_Id',$q_Term_Year_Id)
    ->with('q_Cost_Item_Id',$q_Cost_Item_Id)
    ;
  }

  //Detail Pembayaran Mahasiswa Per Itemm -------------------------------------------------------------------------------------------------------------\\
  public function D_Mahasiswa_Item()
  {
    $Cost_Item_Id = Input::get('Cost_Item_Id');
    $fnc_cost_item = DB::table('fnc_cost_item')->where('Cost_Item_Id','=',$Cost_Item_Id)->first();
    $Term_Year_Id = Input::get('Term_Year_Id');
    $mstr_term_year = DB::table('mstr_term_year')->where('Term_Year_Id','=',$Term_Year_Id)->first();
    $Payment_Order = Input::get('PaymentOrders');
    $Department_Id = Input::get('Department_Id');
    $Start = Input::get('StartDate');
    $End = Input::get('EndDate');
    $StartDate = date("m/d/Y",strtotime($Start));
    $EndDate = date("m/d/Y",strtotime($End));
    $Start_Date = Date("Y-m-d",strtotime($StartDate));
    $End_Date = Date("Y-m-d",strtotime($EndDate));
    $lap = DB::table('fnc_student_payment')
                       ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
                       ->join('acd_student','fnc_student_payment.Register_Number','=','acd_student.Register_Number')
                       ->join('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
                       ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
                       ->join('fnc_cost_item','fnc_student_payment.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
                       ->where([
                         ['fnc_student_payment.Term_Year_Id','=',$Term_Year_Id],
                         ['acd_student.Department_Id','=',$Department_Id],
                         ['fnc_student_payment.Cost_Item_id','=',$Cost_Item_Id],
                         ['fnc_student_payment.Installment_Order','=',$Payment_Order],
                         ['fnc_reff_payment.Payment_Date','>=',$Start_Date],
                         ['fnc_reff_payment.Payment_Date','<=',$End_Date]
                         ])
                       ->OrderBy('acd_student.Nim')
                       ->GroupBy('acd_student.Nim','fnc_student_payment.Installment_Order')
                       ->select(DB::raw('
                          acd_student.Nim as Nim,
                          acd_student.Full_Name as Full_Name,
                          fnc_cost_item.Cost_Item_Name as Cost_Item_Name,
                          mstr_department.Department_Name as Department_Name,
                          fnc_student_payment.Installment_Order as Installment_Order,
                          SUM(fnc_student_payment.Payment_Amount) as Payment_Amount
                       '))->get();
     return View('laporan/d_mahasiswa_item')
     ->with('lap',$lap)
     ->with('Payment_Order',$Payment_Order)
     ->with('Start_Date',$Start_Date)
     ->with('End_Date',$End_Date)
     ->with('mstr_term_year',$mstr_term_year)
     ->with('fnc_cost_item',$fnc_cost_item)
     ;
  }


  // Laporan Tunggakan Mahasiswa ----------------------------------------------------------------------
  public function Lp_Tunggakan_Mahasiswa()
  {
    $Department_Id = Input::get('Department_Id');
    $Department = DB::table('mstr_department')->where('Department_Id','=',$Department_Id)->get();
    $Entry_Year_Id = Input::get('Entry_Year_Id');
    $param = Input::get('param');
    $Year_Id = Input::get('Year_Id');

    $q_Department_Id = DB::table('mstr_department')->OrderBy('Department_Name','asc')->get();
    $q_Entry_Year_Id = DB::table('mstr_entry_year')->OrderBy('Entry_Year_Id','desc')->get();
    $q_Year_Id = DB::table('mstr_term_year')->Where('Term_Id','=',1)->OrderBy('Year_Id','desc')->get();

    $i = 0;
    $laporan = [];

    if ($Department_Id != null && $Entry_Year_Id != null && $param != null) {
      $q_Student = DB::table('acd_student')
                        ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
                        ->join('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
                        ->where([['acd_student.Department_Id','=',$Department_Id],['acd_student.Entry_Year_Id','=',$Entry_Year_Id]])
                        ->get();
      foreach ($q_Student as $Student) {
        $tagihan = 0;
        if ($param == 1) {
          $usp = DB::select('CALL usp_getstudentbill(?,?,?)',[$Student->Register_Number,"",""]);
          if ($usp != null) {
            foreach ($usp as $item) {
              $tagihan += $item->Amount;
            }
          }
        }else{
          $usp = DB::select('CALL usp_GetStudentBillAllPaymentOrder(?,?)',[$Student->Register_Number, $Year_Id]);
          if ($usp != null) {
            foreach ($usp as $item) {
              $tagihan += $item->Amount;
            }
          }
        }
        if ($tagihan != 0) {
          $laporan[$i]['Nim'] = $Student->Nim;
          $laporan[$i]['Class_Program_Name'] = $Student->Class_Program_Name;
          $laporan[$i]['Register_Number'] = $Student->Register_Number;
          $laporan[$i]['Full_Name'] = $Student->Full_Name;
          $laporan[$i]['Department_Name'] = $Student->Department_Name;
          $laporan[$i]['Entry_Year_Id'] = $Student->Entry_Year_Id;
          $laporan[$i]['Tunggakan'] = $tagihan;
          $i++;
        }
      }
    }
    return View('laporan/lp_tunggakan_mahasiswa')
    ->with('lap',$laporan)
    ->with('Department_Id',$Department_Id)
    ->with('Department',$Department)
    ->with('Entry_Year_Id',$Entry_Year_Id)
    ->with('param',$param)
    ->with('Year_Id',$Year_Id)
    ->with('q_Department_Id',$q_Department_Id)
    ->with('q_Entry_Year_Id',$q_Entry_Year_Id)
    ->with('q_Year_Id',$q_Year_Id)
    ;
  }


  public function Detail_Lp_Tunggakan_Mahasiswa()
  {
    $Register_Number = Input::get('Register_Number');
    $param = Input::get('param');
    $Year_Id = Input::get('Year_Id');

    $Student = DB::table('acd_student')->where('Register_Number','=',$Register_Number)->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')->first();

    $i = 0;
    $tunggakan = [];
    if ($param == 1) {
      $usp = DB::select('CALL usp_getstudentbill(?,?,?)',[$Student->Register_Number,"",""]);
      if ($usp != null) {
        foreach ($usp as $item) {
          $Term_Year = DB::table('mstr_term_year')->Where('Term_Year_Id','=',$item->Term_Year_Bill_id)->first();
          $tunggakan[$i]['Cost_Item_Id'] = $item->Cost_Item_Id;
          $tunggakan[$i]['Cost_Item_Name'] = $item->Cost_Item_Name;
          $tunggakan[$i]['Payment_Order'] = $item->Payment_Order;
          $tunggakan[$i]['Amount'] = $item->Amount;
          $tunggakan[$i]['Term_Year_Bill_Id'] = $item->Term_Year_Bill_id;
          $tunggakan[$i]['Term_Year_Bill_Name'] = $Term_Year->Term_Year_Name;
          $i++;
        }
      }
    }else{
      $usp = DB::select('CALL usp_GetStudentBillAllPaymentOrder(?,?)',[$Student->Register_Number, $Year_Id]);
      if ($usp != null) {
        foreach ($usp as $item) {
          if ($param == 1) {
            $Term_Year = DB::table('mstr_term_year')->Where('Term_Year_Id','=',$item->Term_Year_Bill_Id)->get();
            foreach ($Term_Year as $key) {
              $tunggakan[$i]['Cost_Item_Id'] = $item->Cost_Item_Id;
              $tunggakan[$i]['Cost_Item_Name'] = $item->Cost_Item_Name;
              $tunggakan[$i]['Payment_Order'] = $item->Payment_Order;
              $tunggakan[$i]['Amount'] = $item->Amount;
              $tunggakan[$i]['Term_Year_Bill_Id'] = $item->Term_Year_Bill_id;
              $tunggakan[$i]['Term_Year_Bill_Name'] = $key->Term_Year_Name;
            }
          }else{
            $Term_Year_Bill_Id = $item->Term_Year_Bill_id;
            $tunggakan[$i]['Cost_Item_Id'] = $item->Cost_Item_Id;
            $tunggakan[$i]['Cost_Item_Name'] = $item->Cost_Item_Name;
            $tunggakan[$i]['Payment_Order'] = $item->Payment_Order;
            $tunggakan[$i]['Amount'] = $item->Amount;
            $tunggakan[$i]['Term_Year_Bill_Id'] = $Term_Year_Bill_Id;
            $tunggakan[$i]['Term_Year_Bill_Name'] = "-";
            $Term_Year = DB::table('mstr_term_year')->Where('Term_Year_Id','=',$Term_Year_Bill_Id)->get();
            if ($Term_Year != null) {
              foreach ($Term_Year as $key) {
                if($key->Term_Year_Name != null){
                    $tunggakan[$i]['Term_Year_Bill_Name'] = $key->Term_Year_Name;
                }else{
                }
              }
            }else{
            }
          }
          $i++;
        }
      }
    }

    return View('laporan/detail_lp_tunggakan_mahasiswa')
    ->with('tunggakan',$tunggakan)
    ->with('Student',$Student)
    ->with('param',$param)
    ->with('Year_Id',$Year_Id)
    ;
  }

}
