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
use Auth;

class CostSchedPersonalController extends Controller
{
  public function __construct()
  {
        $this->middleware('auth');
  }

  // index -----------------------------------------------------------------------------------------\\
  public function index()
  {
    $Term_Year_Id = Input::get('Term_Year_Id');
    $param = Input::get('param');

    $q_Term_Year = DB::table('mstr_term_year')->OrderBy('Term_Year_Id','desc')->get();

    $q_Student = DB::table('acd_student')->where('Nim','=',$param)
                ->leftJoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
                ->first();
    if ($q_Student == null) {
      $q_Student = DB::table('acd_student')
                  ->where('Register_Number','=',$param)
                  ->leftJoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id'
                  )->first();
    }
    if ($q_Student != null) {
        $Register_Number = $q_Student->Register_Number;
    }else{
      $Register_Number = null;
    }

    if (isset($Register_Number)) {
      if ($Register_Number != null)
      {
        $fnc_Cost_Sched_Personal = DB::table('fnc_cost_sched_personal')
                                  ->OrderBy('Payment_Order','asc')
                                      ->leftjoin('mstr_term_year','fnc_cost_sched_personal.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
                                      ->leftjoin('fnc_cost_sched_personal_detail','fnc_cost_sched_personal.Cost_Sched_Personal_Id','=','fnc_cost_sched_personal_detail.Cost_Sched_Personal_Id')
                                      ->Where([
                                        ['Register_Number','=',$Register_Number],
                                        ['fnc_cost_sched_personal.Term_Year_Id','=',$Term_Year_Id]
                                      ])
                                      ->groupBy('fnc_cost_sched_personal.Cost_Sched_Personal_Id')
                                      ->select(DB::raw('
                                          fnc_cost_sched_personal.Cost_Sched_Personal_Id as Cost_Sched_Personal_Id,
                                          fnc_cost_sched_personal.Payment_Order as Payment_Order,
                                          mstr_term_year.Year_Id as Year_Id,
                                          fnc_cost_sched_personal.Start_Date as Start_Date,
                                          fnc_cost_sched_personal.End_Date as End_Date,
                                          fnc_cost_sched_personal.Explanation as Explanation,
                                          SUM(fnc_cost_sched_personal_detail.Amount) as Amount
                                      '))->get();

        $Entry_Period_Type_Id = $q_Student->Entry_Period_Type_Id;

        $Cost_Sched = DB::table('fnc_cost_sched_detail')
                      ->join('fnc_cost_sched','fnc_cost_sched_detail.Cost_Sched_Id','=','fnc_cost_sched.Cost_Sched_Id')
                      ->join('mstr_department','fnc_cost_sched.Department_Id','=','mstr_department.Department_Id')
                      ->join('mstr_class_program','fnc_cost_sched.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
                      ->join('mstr_term_year','fnc_cost_sched.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
                      ->Where([
                        ['fnc_cost_sched.Department_Id','=', $q_Student->Department_Id],
                        ['fnc_cost_sched.Class_Prog_Id','=', $q_Student->Class_Prog_Id],
                        ['fnc_cost_sched.Entry_Year_Id','=', $q_Student->Entry_Year_Id],
                        ['fnc_cost_sched.Entry_Period_Type_Id','=', $Entry_Period_Type_Id],
                        ['fnc_cost_sched.Term_Year_Id','=', $Term_Year_Id],
                        ['fnc_cost_sched.Payment_Order','!=',0]
                       ])
                      ->GroupBy('Cost_Sched_Id')
                      ->select(DB::raw('
                          mstr_department.Department_Name as Department_Name,
                          mstr_class_program.Class_Program_Name as Class_Program_Name,
                          mstr_term_year.Term_Year_Name as Term_Year_Name,
                          fnc_cost_sched.Start_Date as Start_Date,
                          fnc_cost_sched.End_Date as End_Date,
                          fnc_cost_sched.Cost_Sched_Id as Cost_Sched_Id,
                          fnc_cost_sched.Payment_Order as Payment_Order,
                          SUM(fnc_cost_sched_detail.Amount) as Total_Amount
                      '))->get();

        // dd($Cost_Sched);
       $Count_Cost_Sched = DB::table('fnc_cost_sched')->OrderBy('Payment_Order','asc')
                                                 ->Where([
                                                   ['Department_Id','=', $q_Student->Department_Id],
                                                   ['Class_Prog_Id','=', $q_Student->Class_Prog_Id],
                                                   ['Entry_Year_Id','=', $q_Student->Entry_Year_Id],
                                                   ['Entry_Period_Type_Id','=', $Entry_Period_Type_Id],
                                                   ['Term_Year_Id','=', $Term_Year_Id],
                                                   ['Payment_Order','!=',0]
                                                  ])
                                                ->count();

        $Student_Payment = DB::table('fnc_student_payment')
                              ->join('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
                              ->where([
                                ['fnc_student_payment.Register_Number','=',$Register_Number],
                                ['fnc_student_payment.Term_Year_Bill_Id','=',$Term_Year_Id],
                                ['fnc_student_payment.Cost_Item_Id','!=', 1]
                              ])
                              ->groupBy('fnc_student_payment.Reff_Payment_Id','fnc_student_payment.Term_Year_Bill_Id','fnc_reff_payment.Payment_Date')
                              ->select(DB::raw('
                                  fnc_student_payment.Reff_Payment_Id as Reff_Payment_Id,
                                  fnc_student_payment.Term_Year_Bill_Id as Term_Year_Bill_Id,
                                  fnc_student_payment.Installment_Order as Installment_Order,
                                  fnc_reff_payment.Payment_Date as Payment_Date,
                                  SUM(fnc_student_payment.Payment_Amount) as Total_Amount
                              '))->get();

        return view('set_biaya_registrasi_personal/index')
        ->with('q_Term_Year',$q_Term_Year)
        ->with('param',$param)
        ->with('Term_Year_Id', $Term_Year_Id)
        ->with('q_Student', $q_Student)
        ->with('fnc_Cost_Sched_Personal', $fnc_Cost_Sched_Personal)
        ->with('Entry_Period_Type_Id',$Entry_Period_Type_Id)
        ->with('Cost_Sched', $Cost_Sched)
        ->with('Count_Cost_Sched',$Count_Cost_Sched)
        ->with('Student_Payment', $Student_Payment)
        ;
      }
    }
    return view('set_biaya_registrasi_personal/index')
    ->with('q_Term_Year',$q_Term_Year)
    ->with('param',$param)
    ->with('Term_Year_Id', $Term_Year_Id)
    ;
  }
  // -- akhir index =====================================================================================================================================//

  // create ---------------------------------------------------------------------------------------------------------------------------------------------
  public function create()
  {
    $Register_Number = Input::get('Register_Number');
    $Term_Year_Id = input::get('Term_Year_Id');
    $Cost_Item_Id = input::get('Cost_Item_Id');
    // dd($Cost_Item_Id);
    $PaymentOrder = 1;
    $Amount = 0;

    $fnc_cost_item = DB::table('fnc_cost_item')
                    // ->Where('Is_Active','=',1)
                    ->OrderBy('Cost_Item_Name','asc')->get();


    $q_mstr_term_year = DB::table('mstr_term_year')->Where('Term_Year_Id','=',$Term_Year_Id)->get();
    foreach ($q_mstr_term_year as $d_mstr_term_year) {
      $Year_Id = $d_mstr_term_year->Year_Id;
    }
    // dd($q_mstr_term_year);
    $q_acd_student = DB::table('acd_student')->Where('Register_Number','=',$Register_Number)->get();
    foreach ($q_acd_student as $d_acd_student) {
      # code...
    }

    $Entry_Period_Type_Id = 1;
    if ($d_acd_student->Entry_Year_Id == $Year_Id)
    {
      $mstr_entry_period_type = DB::table('acd_student')
      ->join('mstr_entry_period','acd_student.Entry_Period_Id','=','mstr_entry_period.Entry_Period_Id')
      ->join('mstr_entry_period_type','mstr_entry_period.Entry_Period_Type_Id','=','mstr_entry_period_type.Entry_Period_Type_Id')
      ->where('acd_student.Student_Id','=', $d_acd_student->Student_Id)
      ->get();
      foreach ($mstr_entry_period_type as $d_mstr_entry_period_type) {
        $Entry_Period_Type_Id = $d_mstr_entry_period_type->Entry_Period_Type_Id;
      }
      // dd($mstr_entry_period_type);
    }
    else
    {
      $mstr_entry_period_type = DB::table('mstr_entry_period_type')
      ->where('Entry_Period_Type_Code','=',1)
      ->get();

      foreach ($mstr_entry_period_type as $d_mstr_entry_period_type) {
        $Entry_Period_Type_Id = $d_mstr_entry_period_type->Entry_Period_Type_Id;
      }
    }

    $fnc_cost_sched = DB::table('fnc_cost_sched')
    ->Where([
      ['Department_Id','=', $d_acd_student->Department_Id],
      ['Class_Prog_Id','=', $d_acd_student->Class_Prog_Id],
      ['Entry_Year_Id','=', $d_acd_student->Entry_Year_Id],
      ['Entry_Period_Type_Id','=', $Entry_Period_Type_Id],
      ['Payment_Order','=', 1],
      ['Term_Year_Id','=', $Year_Id]
    ])
    ->get();

    if ($fnc_cost_sched->Count() > 0) {
      foreach ($fnc_cost_sched as $d_fnc_cost_sched) {
        $Cost_Sched_Id = $d_fnc_cost_sched->Cost_Sched_Id;
      }
      $fnc_cost_sched_detail = DB::table('fnc_cost_sched_detail')
                ->Where('Cost_Sched_Id','=',$Cost_Sched_Id)
                ->Where('Cost_Item_id','=',$Cost_Item_Id)
                ->get();
      if ($fnc_cost_sched_detail->Count() > 0) {
        # code...
        foreach ($fnc_cost_sched_detail as $d_fnc_cost_sched_detail) {
          $Amount = $d_fnc_cost_sched_detail->Amount;
        }
      }
    }

    // dd($d_mstr_term_year);
    return View('set_biaya_registrasi_personal/create')
    ->with('Student',$d_acd_student)
    ->with('PaymentOrder',$PaymentOrder)
    ->with('Term_Year',$d_mstr_term_year)
    ->with('fnc_cost_item', $fnc_cost_item)
    ->with('Register_Number', $Register_Number)
    ->with('Term_Year_Id', $Term_Year_Id)
    ;
  }
  // -- akhir create =====================================================================================================================================//


  public function create_amount()
  {
    $Register_Number = Input::get('Register_Number');
    $Term_Year_Id = input::get('Term_Year_Id');
    $Cost_Item_Id = input::get('Cost_Item_Id');
    // dd($Cost_Item_Id);
    $PaymentOrder = 1;
    $Amount = 0;

    if ($Cost_Item_Id == null) {
      $Amount = 0;
    }else {
        $q_mstr_term_year = DB::table('mstr_term_year')->Where('Term_Year_Id','=',$Term_Year_Id)->get();
        foreach ($q_mstr_term_year as $d_mstr_term_year) {
          $Year_Id = $d_mstr_term_year->Year_Id;
        }


        $q_acd_student = DB::table('acd_student')->Where('Register_Number','=',$Register_Number)->get();
        foreach ($q_acd_student as $d_acd_student) {
          # code...
        }

        $Entry_Period_Type_Id = 1;
        // dd($d_acd_student->Entry_Year_Id);
        if ($d_acd_student->Entry_Year_Id == $Year_Id)
        {
          $mstr_entry_period_type = DB::table('acd_student')
          ->join('mstr_entry_period','acd_student.Entry_Period_Id','=','mstr_entry_period.Entry_Period_Id')
          ->join('mstr_entry_period_type','mstr_entry_period.Entry_Period_Type_Id','=','mstr_entry_period_type.Entry_Period_Type_Id')
          ->where('acd_student.Student_Id','=', $d_acd_student->Student_Id)
          ->first();
          if($mstr_entry_period_type != null){
            $Entry_Period_Type_Id = $mstr_entry_period_type->Entry_Period_Type_Id;
          }
        }
        else
        {
          $mstr_entry_period_type = DB::table('mstr_entry_period_type')
          ->where('Entry_Period_Type_Code','=',1)
          ->get();

          foreach ($mstr_entry_period_type as $d_mstr_entry_period_type) {
            $Entry_Period_Type_Id = $d_mstr_entry_period_type->Entry_Period_Type_Id;
          }
        }




        $fnc_cost_sched = DB::table('fnc_cost_sched')
        ->Where([
          ['Department_Id','=', $d_acd_student->Department_Id],
          ['Class_Prog_Id','=', $d_acd_student->Class_Prog_Id],
          ['Entry_Year_Id','=', $d_acd_student->Entry_Year_Id],
          ['Entry_Period_Type_Id','=', $Entry_Period_Type_Id],
          ['Payment_Order','=', 1],
          ['Term_Year_Id','=', $Term_Year_Id]
        ])
        ->get();

        if ($fnc_cost_sched->Count() > 0) {
          foreach ($fnc_cost_sched as $d_fnc_cost_sched) {
            $Cost_Sched_Id = $d_fnc_cost_sched->Cost_Sched_Id;
          }
          $fnc_cost_sched_detail = DB::table('fnc_cost_sched_detail')
                    ->Where('Cost_Sched_Id','=',$Cost_Sched_Id)
                    ->Where('Cost_Item_id','=',$Cost_Item_Id)
                    ->get();
          if ($fnc_cost_sched_detail->Count() > 0) {
            # code...
            foreach ($fnc_cost_sched_detail as $d_fnc_cost_sched_detail) {
              $Amount = $d_fnc_cost_sched_detail->Amount;
            }
          }
        }


    }

    return json_encode($Amount);
  }

  //create post ----------------------------------------------------------------------------------------------------------------------------------------------
  public function create_post(){
    $Register_Number = Input::get('Register_Number');
    $Term_Year_Id = Input::get('Term_Year_Id');
    $Payment_Order = 1;
    $Start_Date = Date('Y-m-d H:i:s',strtotime(Input::get('Start_Date')));
    $End_Date = Date('Y-m-d H:i:s',strtotime(Input::get('End_Date')));
    $Explanation = Input::get('Explanation');
    try {
      try {
        $insert = DB::table('fnc_cost_sched_personal')->insertGetId([
          'Register_Number' => $Register_Number,
          'Term_Year_Id' => $Term_Year_Id,
          'Payment_Order' => $Payment_Order,
          'Start_Date' => $Start_Date,
          'End_Date' => $End_Date,
          'Explanation' => $Explanation
        ]);
      } catch (\Exception $e) {
        return redirect('set_biaya_registrasi_personal/create?Register_Number='.$Register_Number.'&Term_Year_Id='.$Term_Year_Id.'$Payment_Order='.$Payment_Order);
      }

      $id = $insert;
      Alert::success('Berhasil menambahkan data', 'Data ditambahkan !');
      return redirect('set_biaya_registrasi_personal/edit/'.$id);
    } catch (\Exception $e) {
      return redirect('set_biaya_registrasi_personal/create?Register_Number='.$Register_Number.'&Term_Year_Id='.$Term_Year_Id.'$Payment_Order='.$Payment_Order);
    }
  }
  // -- akhir create post ====================================================================================================================================

  //create post ajax
  public function create_post_ajax(){
    $exp = "";
    $status = 0;

    // data fnc_cost_sched_poersonal---------------------------------
    $Register_Number = Input::get('Register_Number');
    $Term_Year_Id = Input::get('Term_Year_Id');
    $Payment_Order = 1;
    $Start_Date = Date('Y-m-d H:i:s',strtotime(Input::get('Start_Date')));
    $End_Date = Date('Y-m-d H:i:s',strtotime(Input::get('End_Date')));
    $Keterangan = Input::get('Keterangan');

    // data fnc_cost_sched_personal_detail----------------------------------
    $BiayaRegistrasiDetails = json_decode(Input::get('BiayaRegistrasiDetails'), true);
    $Created_By = Auth::user()->name;

    try {
      // simpan data fnc_cost_sched_personal------------------------
      $Cost_Sched_Personal_Id = DB::table('fnc_cost_sched_personal')
      ->insertGetId([
        'Register_Number' => $Register_Number,
        'Term_Year_Id' => $Term_Year_Id,
        'Payment_Order' => $Payment_Order,
        'Start_Date' => $Start_Date,
        'End_Date' => $End_Date,
        'Explanation' => $Keterangan
      ]);

      //simpan  fnc_cost_sched_personal_detail-----------------
      foreach ($BiayaRegistrasiDetails as $item) {
        $Cost_Item_id = $item['Cost_Item_id'];
        $Amount = $item['Amount'];
        $Percentage = $item['Percentage'];

        $insert_fnc_cost_sched_personal_detail = DB::table('fnc_cost_sched_personal_detail')
                                                ->insert([
                                                  'Cost_Sched_Personal_Id' => $Cost_Sched_Personal_Id,
                                                  'Cost_Item_id' => $Cost_Item_id,
                                                  'Amount' => $Amount,
                                                  'Percentage' => $Percentage,
                                                  'Created_By' => $Created_By
                                                ]);

      }

      $simpan = DB::select('CALL usp_SetStudentBillPersonal_ByTermYearId(?,?,?)',[$Register_Number, $Term_Year_Id, $Created_By]);

      $status = 1;
    } catch (\Exception $e) {
      $status = 0;
      $exp = $e->getMessage();
      Alert::error($exp, 'gagal !');
    }

    return response()->json(['status' => $status, 'exp' => $exp]);

  }


  // create detail ------------------------------------------------------------------------------------------------------------------------------------------
  public function create_detail(){
    $Cost_Sched_Personal_Id = Input::get('Cost_Sched_Personal_Id');
    $Cost_Item_Id = Input::get('Cost_Item_Id');


    $Amount = 0;
    if ($Cost_Item_Id != null && $Cost_Sched_Personal_Id != null)
    {
      $fnc_cost_sched_personal = DB::table('fnc_cost_sched_personal')->Where('Cost_Sched_Personal_Id',$Cost_Sched_Personal_Id)->get();
      foreach ($fnc_cost_sched_personal as $d_fnc_cost_sched_personal) {
        # code...
      }

      $mstr_term_year = DB::table('mstr_term_year')->Where('Term_Year_Id','=',$d_fnc_cost_sched_personal->Term_Year_Id)->get();
      foreach ($mstr_term_year as $d_Mstr_Term_year) {
        $Year_Id = $d_Mstr_Term_year->Year_Id;
      }
      if ($fnc_cost_sched_personal == null)
      {
        return HttpNotFound();
      }
      $acd_student = DB::table('acd_student')->Where('Register_Number','=',$d_fnc_cost_sched_personal->Register_Number)->get();
      foreach ($acd_student as $d_acd_student) {
        # code...
      }
      $Entry_Period_Type_Id = 1;

      if ($d_acd_student->Entry_Year_Id == $Year_Id)
      {
        $mstr_entry_period_type = DB::table('acd_student')
        ->join('mstr_entry_period','acd_student.Entry_Period_Id','=','mstr_entry_period.Entry_Period_Id')
        ->join('mstr_entry_period_type','mstr_entry_period.Entry_Period_Type_Id','=','mstr_entry_period_type.Entry_Period_Type_Id')
        ->where('acd_student.Student_Id','=', $d_acd_student->Student_Id)
        ->get();
        foreach ($mstr_entry_period_type as $d_mstr_entry_period_type) {
          $Entry_Period_Type_Id = $d_mstr_entry_period_type->Entry_Period_Type_Id;
        }
      }
      else
      {
        $mstr_entry_period_type = DB::table('mstr_entry_period_type')
        ->where('Entry_Period_Type_Code','=',1)
        ->get();
        foreach ($mstr_entry_period_type as $d_mstr_entry_period_type) {
          $Entry_Period_Type_Id = $d_mstr_entry_period_type->Entry_Period_Type_Id;
        }
      }

      $fnc_cost_sched = DB::table('fnc_cost_sched')
      ->Where([
        ['Department_Id','=', $d_acd_student->Department_Id],
        ['Class_Prog_Id','=', $d_acd_student->Class_Prog_Id],
        ['Entry_Year_Id','=', $d_acd_student->Entry_Year_Id],
        ['Entry_Period_Type_Id','=', $Entry_Period_Type_Id],
        ['Payment_Order','=', $d_fnc_cost_sched_personal->Payment_Order],
        ['Term_Year_Id','=', $d_fnc_cost_sched_personal->Term_Year_Id]
      ])
      ->get();


      if ($fnc_cost_sched->Count() > 0) {
        foreach ($fnc_cost_sched as $d_fnc_cost_sched) {
          $Cost_Sched_Id = $d_fnc_cost_sched->Cost_Sched_Id;
        }
        $fnc_cost_sched_detail = DB::table('fnc_cost_sched_detail')->Where('Cost_Sched_Id','=',$Cost_Sched_Id)->Where('Cost_Item_id','=',$Cost_Item_Id)->get();
        if ($fnc_cost_sched_detail->Count() > 0) {
          # code...
          foreach ($fnc_cost_sched_detail as $d_fnc_cost_sched_detail) {
            $Amount = $d_fnc_cost_sched_detail->Amount;
          }
        }
      }
    }

    $array_cost_item = array();
    // $q_fnc_cost_sched_personal_detail = DB::table('fnc_cost_sched_personal_detail')->get();
    // if ($q_fnc_cost_sched_personal_detail != null) {
    //   foreach ($q_fnc_cost_sched_personal_detail as $d_fnc_cost_sched_personal_detail) {
    //     $array_cost_item[] = $d_fnc_cost_sched_personal_detail->Cost_Item_id;
    //   }
    // }
    $fnc_cost_item = DB::table('fnc_cost_item')
                    // ->Where('Is_Active','=',1)
                    ->OrderBy('Cost_Item_Name','asc')->get();

    return View('set_biaya_registrasi_personal/create_detail')
    ->with('fnc_cost_item',$fnc_cost_item)
    ->with('Cost_Sched_Personal_Id',$Cost_Sched_Personal_Id)
    ->with('Cost_Item_Id',$Cost_Item_Id)
    ->with('Amount',$Amount)
    ;
  }
  // -- akhir create detail ==================================================================================================================================


  // create_detail_post --------------------------------------------------------------------------------------------------------------------------------------
  public function create_detail_post(){
    $Cost_Sched_Personal_Id = Input::get('Cost_Sched_Personal_Id');
    $Cost_Item_id = Input::get('Cost_Item_Id');
    $Amount = Input::get('Amount');
    $Percentage = Input::get('Percentage');

    $fnc_cost_sched_personal = DB::table('fnc_cost_sched_personal')
                              ->Where('Cost_Sched_Personal_Id',$Cost_Sched_Personal_Id)
                              ->get();
    foreach ($fnc_cost_sched_personal as $d_fnc_cost_sched_personal) {
        $insert = DB::table('fnc_cost_sched_personal_detail')->insertGetId([
          'Cost_Sched_Personal_Id' => $Cost_Sched_Personal_Id,
          'Cost_Item_id' => $Cost_Item_id,
          'Amount' => $Amount,
          'Percentage' => $Percentage
        ]);
    }
    Alert::success('Berhasil menambahkan data', 'Data ditambahkan !');
    return redirect('set_biaya_registrasi_personal/edit/'.$Cost_Sched_Personal_Id);
  }
  // -- Akhir Create Detail Post =============================================================================================================================

  // detail ----------------------------------------------------------------------------------------------------------------------------------------------------
  public function detail($Cost_Sched_Personal_Id)
  {
    $fnc_cost_sched_personal = DB::table('fnc_cost_sched_personal')->Where('Cost_Sched_Personal_Id','=',$Cost_Sched_Personal_Id)->get();
    foreach ($fnc_cost_sched_personal as $d_fnc_cost_sched_personal) {
    }
    $Register_Number = $d_fnc_cost_sched_personal->Register_Number;
    $Term_Year_Id = $d_fnc_cost_sched_personal->Term_Year_Id;
    $fnc_cost_sched_personal_detail = DB::table('fnc_cost_sched_personal_detail')
                                            ->join('fnc_cost_item','fnc_cost_sched_personal_detail.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
                                            ->Where('Cost_Sched_Personal_Id','=',$Cost_Sched_Personal_Id)
                                            ->get();
    $acd_student = DB::table('acd_student')->Where('Register_Number','=',$Register_Number)->get();
    foreach ($acd_student as $d_acd_student) {
      # code...
    }
    $mstr_term_year = DB::table('mstr_term_year')->Where('Term_Year_Id','=', $Term_Year_Id)->get();
    foreach ($mstr_term_year as $d_mstr_term_year) {
      # code...
    }
    return View('set_biaya_registrasi_personal/detail')
      ->with('fnc_cost_sched_personal',$d_fnc_cost_sched_personal)
      ->with('acd_student',$d_acd_student)
      ->with('mstr_term_year',$d_mstr_term_year)
      ->with('fnc_cost_sched_personal_detail',$fnc_cost_sched_personal_detail)
    ;
  }
  // -- akhir detail ===========================================================================================================================================
  // -- simpan
  public function simpan(Request $request)
  {
    $Register_Number = $request->Register_Number;
    $Term_Year_Id = $request->Term_Year_Id;

    // $Data="";
    //
    // foreach ($tagihan as $item) {
    //   $Data = $Data."<Data><id>".$item->Cost_Item_Id."</id><is_personal>1</is_personal></Data>";
    // }

    $Created_By = Auth::user()->name;

    try {

      $simpan = DB::select('CALL usp_SetStudentBillPersonal_ByTermYearId(?,?,?)',[$Register_Number, $Term_Year_Id, $Created_By]);
      Alert::success('Berhasil Menghitung Tagihan, Silakan Cek di menu Teller', 'Selesai !');
      return redirect('set_biaya_registrasi_personal/?param='.$Register_Number.'&Term_Year_Id='.$Term_Year_Id);

    } catch (\Exception $e) {

      Alert::error($e->getMessage(), 'Gagal !');
      return redirect('set_biaya_registrasi_personal/?param='.$Register_Number.'&Term_Year_Id='.$Term_Year_Id);

    }

  }




  // edit ----------------------------------------------------------------------------------------------------------------------------------------------------
  public function edit($Cost_Sched_Personal_Id)
  {
    $fnc_cost_sched_personal = DB::table('fnc_cost_sched_personal')
                              ->Where('Cost_Sched_Personal_Id','=',$Cost_Sched_Personal_Id)
                              ->get();
    foreach ($fnc_cost_sched_personal as $d_fnc_cost_sched_personal) {
    }

    $Register_Number = $d_fnc_cost_sched_personal->Register_Number;
    $Term_Year_Id = $d_fnc_cost_sched_personal->Term_Year_Id;
    $fnc_cost_sched_personal_detail = DB::table('fnc_cost_sched_personal_detail')
                                            ->join('fnc_cost_item','fnc_cost_sched_personal_detail.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
                                            ->Where('Cost_Sched_Personal_Id','=',$Cost_Sched_Personal_Id)
                                            ->get();

    $acd_student = DB::table('acd_student')
                  ->Where('Register_Number','=',$Register_Number)
                  ->get();
    foreach ($acd_student as $d_acd_student) {
      # code...
    }
    $mstr_term_year = DB::table('mstr_term_year')->Where('Term_Year_Id','=', $Term_Year_Id)->get();
    foreach ($mstr_term_year as $d_mstr_term_year) {
      # code...
    }

    $fnc_cost_item = DB::table('fnc_cost_item')
                    ->get();

    return View('set_biaya_registrasi_personal/edit')
      ->with('fnc_cost_sched_personal',$d_fnc_cost_sched_personal)
      ->with('acd_student',$d_acd_student)
      ->with('mstr_term_year',$d_mstr_term_year)
      ->with('fnc_cost_sched_personal_detail',$fnc_cost_sched_personal_detail)
      ->with('fnc_cost_item', $fnc_cost_item )
    ;
  }
  // -- akhir edit ===========================================================================================================================================


  // insert_detail_post_ajax------------------------------------------------------------------
  public function insert_detail_post_ajax(Request $request)
  {
    $exp = "";
    $status = 0;

    $Cost_Sched_Personal_Id = $request->Cost_Sched_Personal_Id;
    $Cost_Item_id = $request->Cost_Item_Id;
    $Amount = $request->Amount;
    $Percentage = $request->Percentage;
    $Created_By = Auth::user()->name;

    // return $Percentage;Cost_Sched_Personal_Detail_Id
    try {
      $insert = DB::table('fnc_cost_sched_personal_detail')
      ->insert([
        'Cost_Sched_Personal_Id' => $Cost_Sched_Personal_Id,
        'Cost_Item_id' => $Cost_Item_id,
        'Amount' => $Amount,
        'Percentage' => $Percentage,
        'Created_By' => $Created_By
      ]);

      $status = 1;
    } catch (\Exception $e) {
      $status = 0;
      $exp = $e->getMessage();
      Alert::error($exp, 'gagal !');
    }

    return response()->json(['status' => $status, 'exp' => $exp]);

  }
  // insert_detail_post_ajax------------------------------------------------------------------

  // edit_detail_post_ajax --------------------------------------------------------------------------------------------------------------------------------------
  public function edit_detail_post_ajax($Cost_Sched_Personal_Detail_Id, Request $request){
    $exp = "";
    $status = 0;

    $Cost_Sched_Personal_Id = $request->Cost_Sched_Personal_Id;
    $Cost_Item_id = $request->Cost_Item_Id;
    $Amount = $request->Amount;
    $Percentage = $request->Percentage;

    try {
      $update = DB::table('fnc_cost_sched_personal_detail')
      ->where('Cost_Sched_Personal_Detail_Id','=',$Cost_Sched_Personal_Detail_Id)
      ->update([
        'Cost_Item_id' => $Cost_Item_id,
        'Amount' => $Amount,
        'Percentage' => $Percentage
      ]);

      $status = 1;
    } catch (\Exception $e) {
      $status = 0;
      $exp = $e->getMessage();
      Alert::error($exp, 'gagal !');
    }


    return response()->json(['status' => $status, 'exp' => $exp]);

    // Alert::success('Berhasil mengubah data', 'Data terupdate !');
    // return redirect('set_biaya_registrasi_personal/edit/'.$Cost_Sched_Personal_Id);
  }
  // -- Akhir Edit Detail Post =============================================================================================================================


  // delete_detail --------------------------------------------------------------------------------------------------------------------------------------
  public function delete_detail_ajax($Cost_Sched_Personal_Detail_Id){
    $exp = "";
    $status = 0;

    $q_fnc_cost_sched_personal_detail = DB::table('fnc_cost_sched_personal_detail')
      ->Where('Cost_Sched_Personal_Detail_Id','=',$Cost_Sched_Personal_Detail_Id)
      ->get();


    foreach ($q_fnc_cost_sched_personal_detail as $fnc_cost_sched_personal_detail) {
      $Cost_Sched_Personal_Id = $fnc_cost_sched_personal_detail->Cost_Sched_Personal_Id;
    }

    try {
      $delete = DB::table('fnc_cost_sched_personal_detail')
      ->where('Cost_Sched_Personal_Detail_Id','=',$Cost_Sched_Personal_Detail_Id)
      ->delete();

      $status = 1;
    } catch (\Exception $e) {
      $status = 0;
      $exp = $e->getMessage();
      Alert::error($exp, 'gagal !');
    }

    return response()->json(['status' => $status, 'exp' => $exp]);


  }
  // -- Akhir Delete Detail =============================================================================================================================


  // edit post ----------------------------------------------------------------------------------------------------------------------------------------------------
  public function edit_post($Cost_Sched_Personal_Id)
  {
    $fnc_cost_sched_personal = DB::table('fnc_cost_sched_personal')->Where('Cost_Sched_Personal_Id','=',$Cost_Sched_Personal_Id)->get();
    foreach ($fnc_cost_sched_personal as $d_fnc_cost_sched_personal) {
    }
    $Register_Number = $d_fnc_cost_sched_personal->Register_Number;
    $Term_Year_Id = $d_fnc_cost_sched_personal->Term_Year_Id;
    $Explanation= Input::get('Explanation');
    $update = DB::table('fnc_cost_sched_personal')
                ->where('Cost_Sched_Personal_Id',$Cost_Sched_Personal_Id)
                ->update([
                  'Explanation' => $Explanation
                ]);
    if ($update) {
        Alert::success('Berhasil mengubah data', 'Data terupdate !');
        return redirect('set_biaya_registrasi_personal?param='.$Register_Number.'&Term_Year_Id='.$Term_Year_Id);
    }else{
      return redirect('set_biaya_registrasi_personal/edit/'.$Cost_Sched_Personal_Id);
    }
  }
  // -- akhir edit post===========================================================================================================================================

  // edit detail ------------------------------------------------------------------------------------------------------------------------------------------
  public function edit_detail($Cost_Sched_Personal_Detail_Id){
    $Cost_Item_id = Input::get('Cost_Item_Id');
    $Normal_Amount=0;
    $q_fnc_cost_sched_personal_detail = DB::table('fnc_cost_sched_personal_detail')->Where('Cost_Sched_Personal_Detail_Id','=',$Cost_Sched_Personal_Detail_Id)->get();
    foreach ($q_fnc_cost_sched_personal_detail as $fnc_cost_sched_personal_detail) {}
    if ($Cost_Item_id == null) {
      $Cost_Item_id = $fnc_cost_sched_personal_detail->Cost_Item_id;
    }
    $q_fnc_cost_sched_personal = DB::table('fnc_cost_sched_personal')->Where('Cost_Sched_Personal_Id','=',$fnc_cost_sched_personal_detail->Cost_Sched_Personal_Id)->get();
    foreach ($q_fnc_cost_sched_personal as $fnc_cost_sched_personal) {}
    $q_mstr_term_year = DB::table('mstr_term_year')->Where('Term_Year_Id','=',$fnc_cost_sched_personal->Term_Year_Id)->get();
    foreach ($q_mstr_term_year as $mstr_term_year) {
        $Year_Id = $mstr_term_year->Year_Id;
    }
    if ($q_fnc_cost_sched_personal_detail == null)
    {
        return HttpNotFound();
    }
    if ($q_fnc_cost_sched_personal == null)
    {
        return HttpNotFound();
    }
    $q_acd_student = DB::table('acd_student')->Where('Register_Number','=',$fnc_cost_sched_personal->Register_Number)->get();
    foreach ($q_acd_student as $acd_student) {}
    $Entry_Period_Type_Id = 1;

    if ($acd_student->Entry_Year_Id == $Year_Id)
    {
      $mstr_entry_period_type = DB::table('acd_student')
      ->join('mstr_entry_period','acd_student.Entry_Period_Id','=','mstr_entry_period.Entry_Period_Id')
      ->join('mstr_entry_period_type','mstr_entry_period.Entry_Period_Type_Id','=','mstr_entry_period_type.Entry_Period_Type_Id')
      ->where('acd_student.Student_Id','=', $acd_student->Student_Id)
      ->get();
      foreach ($mstr_entry_period_type as $d_mstr_entry_period_type) {
        $Entry_Period_Type_Id = $d_mstr_entry_period_type->Entry_Period_Type_Id;
      }
    }
    else
    {
      $mstr_entry_period_type = DB::table('mstr_entry_period_type')
      ->where('Entry_Period_Type_Code','=',1)
      ->get();
      foreach ($mstr_entry_period_type as $d_mstr_entry_period_type) {
        $Entry_Period_Type_Id = $d_mstr_entry_period_type->Entry_Period_Type_Id;
      }
    }

    $fnc_cost_sched = DB::table('fnc_cost_sched')
    ->Where('Department_Id','=', $acd_student->Department_Id)
    ->Where('Class_Prog_Id','=', $acd_student->Class_Prog_Id)
    ->Where('Entry_Year_Id','=', $acd_student->Entry_Year_Id)
    ->Where('Entry_Period_Type_Id','=', $Entry_Period_Type_Id)
    ->Where('Payment_Order','=', $fnc_cost_sched_personal->Payment_Order)
    ->Where('Term_Year_Id','=', $fnc_cost_sched_personal->Term_Year_Id)
    ->get();
    if ($fnc_cost_sched->Count() > 0) {
      foreach ($fnc_cost_sched as $d_fnc_cost_sched) {
        $Cost_Sched_Id = $d_fnc_cost_sched->Cost_Sched_Id;
      }
      $fnc_cost_sched_detail = DB::table('fnc_cost_sched_detail')->Where('Cost_Sched_Id','=',$Cost_Sched_Id)->Where('Cost_Item_id','=',$Cost_Item_id)->get();
      if ($fnc_cost_sched_detail->Count() > 0) {
        # code...
        foreach ($fnc_cost_sched_detail as $d_fnc_cost_sched_detail) {
          $Normal_Amount = $d_fnc_cost_sched_detail->Amount;
        }
      }
    }
    $array_cost_item = array();
    $q_fnc_cost_sched_personal_detail = DB::table('fnc_cost_sched_personal_detail')->get();
    if ($q_fnc_cost_sched_personal_detail != null) {
      foreach ($q_fnc_cost_sched_personal_detail as $d_fnc_cost_sched_personal_detail) {
        $array_cost_item[] = $d_fnc_cost_sched_personal_detail->Cost_Item_id;
      }
    }
    $fnc_cost_item = DB::table('fnc_cost_item')
                    ->Where('Is_Active','=',1)
                    ->WhereNotIn('Cost_Item_Id',$array_cost_item)
                    ->orWhere('Cost_Item_id',$fnc_cost_sched_personal_detail->Cost_Item_id)
                    ->OrderBy('Cost_Item_Name','asc')->get();

    $Amount = $fnc_cost_sched_personal_detail->Amount;
    return View('set_biaya_registrasi_personal/edit_detail')
    ->with('fnc_cost_item',$fnc_cost_item)
    ->with('Cost_Sched_Personal_Id',$fnc_cost_sched_personal->Cost_Sched_Personal_Id)
    ->with('Cost_Sched_Personal_Detail_Id',$fnc_cost_sched_personal_detail->Cost_Sched_Personal_Detail_Id)
    ->with('fnc_cost_sched_personal_detail',$fnc_cost_sched_personal_detail)
    ->with('Cost_Item_Id',$Cost_Item_id)
    ->with('Amount',$Amount)
    ->with('Normal_Amount',$Normal_Amount)
    ;
  }
  // -- akhir edit detail ==================================================================================================================================


  // edit_detail_post --------------------------------------------------------------------------------------------------------------------------------------
  public function edit_detail_post($Cost_Sched_Personal_Detail_Id){
    $Cost_Sched_Personal_Id = Input::get('Cost_Sched_Personal_Id');
    $Cost_Item_id = Input::get('Cost_Item_Id');
    $Amount = Input::get('Amount');
    $Percentage = Input::get('Percentage');
    $insert = DB::table('fnc_cost_sched_personal_detail')
    ->where('Cost_Sched_Personal_Detail_Id','=',$Cost_Sched_Personal_Detail_Id)
    ->update([
      'Cost_Item_id' => $Cost_Item_id,
      'Amount' => $Amount,
      'Percentage' => $Percentage
    ]);
    Alert::success('Berhasil mengubah data', 'Data terupdate !');
    return redirect('set_biaya_registrasi_personal/edit/'.$Cost_Sched_Personal_Id);
  }
  // -- Akhir Edit Detail Post =============================================================================================================================

  // delete --------------------------------------------------------------------------------------------------------------------------------------
  public function delete($Cost_Sched_Personal_Id){
    $fnc_cost_sched_personal = DB::table('fnc_cost_sched_personal')
                              ->Where('Cost_Sched_Personal_Id','=',$Cost_Sched_Personal_Id)
                              ->get();

    foreach ($fnc_cost_sched_personal as $d_fnc_cost_sched_personal) {
    }
    $Register_Number = $d_fnc_cost_sched_personal->Register_Number;
    $Term_Year_Id = $d_fnc_cost_sched_personal->Term_Year_Id;
    $Created_By = Auth::user()->name;

    $deleteDetail = DB::table('fnc_cost_sched_personal_detail')
              ->where('Cost_Sched_Personal_Id','=',$Cost_Sched_Personal_Id)
              ->delete();

    $delete = DB::table('fnc_cost_sched_personal')
              ->where('Cost_Sched_Personal_Id','=',$Cost_Sched_Personal_Id)
              ->delete();

    // $simpan = DB::select('CALL usp_SetStudentBillPersonal_ByTermYearId(?,?,?)',[$Register_Number, $Term_Year_Id, "".$Created_By.""]);


    Alert::success('Berhasil menghapus data', 'Data terhapus !');
    return redirect('set_biaya_registrasi_personal?param='.$Register_Number.'&Term_Year_Id='.$Term_Year_Id);
  }
  // -- Akhir Delete  =============================================================================================================================

  // delete_detail --------------------------------------------------------------------------------------------------------------------------------------
  public function delete_detail($Cost_Sched_Personal_Detail_Id){
    $q_fnc_cost_sched_personal_detail = DB::table('fnc_cost_sched_personal_detail')->Where('Cost_Sched_Personal_Detail_Id','=',$Cost_Sched_Personal_Detail_Id)->get();
    foreach ($q_fnc_cost_sched_personal_detail as $fnc_cost_sched_personal_detail) {
      $Cost_Sched_Personal_Id = $fnc_cost_sched_personal_detail->Cost_Sched_Personal_Id;
    }
    $delete = DB::table('fnc_cost_sched_personal_detail')
    ->where('Cost_Sched_Personal_Detail_Id','=',$Cost_Sched_Personal_Detail_Id)
    ->delete();
    Alert::success('Berhasil menghapus data', 'Data terhapus !');
    return redirect('set_biaya_registrasi_personal/edit/'.$Cost_Sched_Personal_Id);
  }
  // -- Akhir Delete Detail =============================================================================================================================
}
