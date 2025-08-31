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

class CostSchedPersonalPlusController extends Controller
{
  public function __construct()
  {
        $this->middleware('auth');
  }

  // index -----------------------------------------------------------------------------------------\\
  public function index()
  {
    $Term_Year_Id = Input::get('Term_Year_Id');
    $Term_Year = DB::table('mstr_term_year')->OrderBy('Term_Year_Id','desc')->get();
    $Cost_Sched_Personal_Plus = DB::table('fnc_cost_sched_personal_plus')->where('Term_Year_Id','=',$Term_Year_Id)->join('fnc_cost_item','fnc_cost_sched_personal_plus.Cost_Item_id','=','fnc_cost_item.Cost_Item_Id')->OrderBy('Explanation','asc')->get();
    return view('set_biaya_tambahan/index')
      ->with('Term_Year_Id',$Term_Year_Id)
      ->with('Term_Year',$Term_Year)
      ->with('Cost_Sched_Personal_Plus',$Cost_Sched_Personal_Plus)
    ;
  }
  // akhir Index ------------------------------------------------------------------------------------


  // detail -----------------------------------------------------------------------------------------------
  public function Detail($Cost_Sched_Personal_Plus_Id)
  {
    $Cost_Sched_Personal_Plus = DB::table('fnc_cost_sched_personal_plus')->where('Cost_Sched_Personal_Plus_Id','=',$Cost_Sched_Personal_Plus_Id)->first();
    return view('set_biaya_tambahan/index')->with('Cost_Sched_Personal_Plus',$Cost_Sched_Personal_Plus);
  }
  // akhir detail ---------------------------------------------------------------------------------------------//


  // create --------------------------------------------------------------------------------------------------
  public function Create()
  {
    $Term_Year_Id = Input::get('Term_Year_Id');
    $Department_Id = Input::get('Department_Id');
    $Department = DB::table('mstr_department')->where('Faculty_Id', '=' ,'1')->OrderBy('Department_Code','asc')->get();
    $Student = DB::table('acd_student')->where('Department_Id','=',$Department_Id)->OrderBy('Entry_Year_Id','desc')->OrderBy('Full_Name','asc')->get();
    $Cost_Item = DB::table('fnc_cost_item')->OrderBy('Cost_Item_Name','asc')->get();
    $Term_Year = DB::table('mstr_term_year')->where('Term_Year_Id','=',$Term_Year_Id)->first();
    return view('set_biaya_tambahan/create')
      ->with('Term_Year_Id',$Term_Year_Id)
      ->with('Department_Id',$Department_Id)
      ->with('Department',$Department)
      ->with('Student',$Student)
      ->with('Cost_Item',$Cost_Item)
      ->with('Term_Year',$Term_Year)
    ;
  }
  // akhir create ---------------------------------------------------------------------------------------//

  public function Department_Id()
  {
    $Department = DB::table('mstr_department')->join('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=', 'mstr_education_program_type.Education_Prog_Type_Id')->where('Faculty_Id', '=' ,'1')->OrderBy('Department_Name','asc')->get();

    echo json_encode($Department);
  }

  public function Register_Number(Request $request)
  {
    $Department_Id = $request->Department_Id;
    $Students = DB::table('acd_student')
    ->Where('Department_Id',$Department_Id)
    ->OrderBy('Register_Number','asc')
    ->Select(DB::raw('Register_Number as Register_Number, CONCAT(Nim," | ",Full_Name) as Full_Name'))
    ->get();

    echo json_encode($Students);
  }

  // create post -------------------------------------\----------------------------------------------\\
  public function Create_Post()
  {
    $exp = "";
    $status = 0;
    $Cost_Item_id = Input::get('Cost_Item_id');
    $Amount = Input::get('Amount');
    $Explanation = Input::get('Explanation');
    $Term_Year_Id = Input::get('Term_Year_Id');
    $Start = Input::get('Start_Date');
    $Start_Date = Date("Y-m-d",strtotime($Start));
    $End = Input::get('End_Date');
    $End_Date = Date("Y-m-d",strtotime($End));
    // $Start_Date = Input::get('Start_Date');
    // $End_Date = Input::get('End_Date');
    $Mahasiswa = json_decode(Input::get('Mahasiswa'), true);
    $Created_By = Auth::user()->name;
    $Date_Now = Date("Y-m-d")." 00:00:00";
    $Created_Date = Date('Y-m-d H:i:s',strtotime($Date_Now));
    try
    {
        $Cost_Sched_Personal_Plus_Id = DB::table('fnc_cost_sched_personal_plus')->insertGetId([
          'Term_Year_Id' => $Term_Year_Id,
          'Cost_Item_id' => $Cost_Item_id,
          'Amount' => $Amount,
          'Start_Date' => $Start_Date,
          'End_Date' => $End_Date,
          'Explanation' => $Explanation,
          'Created_By' => $Created_By,
          'Created_Date' => $Created_Date
        ]);
        foreach($Mahasiswa as $item) {
          $Register_Number = $item['Register_Number'];
          $Insert_Fnc_Cost_Sched_Detail = DB::table('fnc_cost_sched_personal_plus_detail')->insert([
            'Cost_Sched_Personal_Plus_Id'=>$Cost_Sched_Personal_Plus_Id,
            'Register_Number'=> $Register_Number,
            'Created_By'=> $Created_By,
            'Created_Date'=> $Created_Date
          ]);
        }
        $status = 1;
    }
    catch (\Exception $e)
    {
        $status = 0;
        $exp = $e->getMessage();
        Alert::error($exp, 'gagal !');
    }
    return response()->json(['status' => $status, 'exp' => $exp]);
  }
  // akhir create post -------------------------------------------------------------------------------//

  // create --------------------------------------------------------------------------------------------------
  public function Edit($Cost_Sched_Personal_Plus_Id)
  {
    $Term_Year_Id = Input::get('Term_Year_Id');
    $Department_Id = Input::get('Department_Id');
    $fnc_Cost_Sched_Personal_Plus = DB::table('fnc_cost_sched_personal_plus')->Where('Cost_Sched_Personal_Plus_Id','=',$Cost_Sched_Personal_Plus_Id)->first();
    $q_Cost_Sched_Personal_Plus_Detail = DB::table('fnc_cost_sched_personal_plus_detail')
                                        ->where('Cost_Sched_Personal_Plus_Id','=',$Cost_Sched_Personal_Plus_Id)
                                        ->join('acd_student','fnc_cost_sched_personal_plus_detail.Register_Number','=','acd_student.Register_Number')
                                        ->get();
    $Department = DB::table('mstr_department')->OrderBy('Department_Name','asc')->get();
    $Student = DB::table('acd_student')->where('Department_Id','=',$Department_Id)->OrderBy('Entry_Year_Id','desc')->OrderBy('Full_Name','asc')->get();
    $Cost_Item = DB::table('fnc_cost_item')->OrderBy('Cost_Item_Name','asc')->get();
    $Term_Year = DB::table('mstr_term_year')->where('Term_Year_Id','=',$fnc_Cost_Sched_Personal_Plus->Term_Year_Id)->first();

    return view('set_biaya_tambahan/edit')
      ->with('Term_Year_Id',$Term_Year_Id)
      ->with('fnc_Cost_Sched_Personal_Plus',$fnc_Cost_Sched_Personal_Plus)
      ->with('q_Cost_Sched_Personal_Plus_Detail',$q_Cost_Sched_Personal_Plus_Detail)
      ->with('Department_Id',$Department_Id)
      ->with('Department',$Department)
      ->with('Student',$Student)
      ->with('Cost_Item',$Cost_Item)
      ->with('Term_Year',$Term_Year)
    ;
  }
  // akhir create ---------------------------------------------------------------------------------------//


  // create post -------------------------------------\----------------------------------------------\\
  public function Edit_Post()
  {
    $Cost_Sched_Personal_Plus_Id = Input::get('Cost_Sched_Personal_Plus_Id');
    $Cost_Sched_Personal_Plus_Id2 = Input::get('Cost_Sched_Personal_Plus_Id');
    $Cost_Sched_Personal_Plus_Id3 = Input::get('Cost_Sched_Personal_Plus_Id');
    $exp = "";
    $status = 0;
    $Cost_Item_id = Input::get('Cost_Item_id');
    $Amount = Input::get('Amount');
    $Explanation = Input::get('Explanation');
    $Term_Year_Id = Input::get('Term_Year_Id');
    $Start = Input::get('Start_Date');
    $Start_Date = Date("Y-m-d",strtotime($Start));
    $End = Input::get('End_Date');
    $End_Date = Date("Y-m-d",strtotime($End));
    // $Start_Date = Input::get('Start_Date');
    // $End_Date = Input::get('End_Date');
    $Mahasiswa = json_decode(Input::get('Mahasiswa'), true);
    $Created_By = Auth::user()->name;
    $Date_Now = Date("Y-m-d")." 00:00:00";
    $Created_Date = Date('Y-m-d H:i:s',strtotime($Date_Now));
    try
    {
        $Cost_Sched_Personal_Plus_Id = DB::table('fnc_cost_sched_personal_plus')
        ->where('Cost_Sched_Personal_Plus_Id',$Cost_Sched_Personal_Plus_Id)
        ->update([
          'Term_Year_Id' => $Term_Year_Id,
          'Cost_Item_id' => $Cost_Item_id,
          'Amount' => $Amount,
          'Start_Date' => $Start_Date,
          'End_Date' => $End_Date,
          'Explanation' => $Explanation,
          'Created_By' => $Created_By,
          'Created_Date' => $Created_Date
        ]);
        $delete_detail = DB::table('fnc_cost_sched_personal_plus_detail')->where('Cost_Sched_Personal_Plus_Id','=',$Cost_Sched_Personal_Plus_Id2)->delete();
        foreach($Mahasiswa as $item) {
          $Register_Number = $item['Register_Number'];
          $Insert_Fnc_Cost_Sched_Detail = DB::table('fnc_cost_sched_personal_plus_detail')
          ->where('Cost_Sched_Personal_Plus_Id',$Cost_Sched_Personal_Plus_Id)
          ->insert([
            'Cost_Sched_Personal_Plus_Id'=>$Cost_Sched_Personal_Plus_Id3,
            'Register_Number'=> $Register_Number,
            'Modified_By'=> $Created_By,
            'Modified_Date'=> $Created_Date
          ]);
        }
        $status = 1;
    }
    catch (\Exception $e)
    {
        $status = 0;
        $exp = $e->getMessage();
        Alert::error($exp, 'gagal !');
    }
    return response()->json(['status' => $status, 'exp' => $exp]);
  }
  // akhir create post -------------------------------------------------------------------------------//

  //delete
  public function Delete($Cost_Sched_Personal_Plus_Id)
  {
    $q_Term_Year_Id = DB::table('fnc_cost_sched_personal_plus')->Where('Cost_Sched_Personal_Plus_Id','=',$Cost_Sched_Personal_Plus_Id)->first();
    $Term_Year_Id = $q_Term_Year_Id->Term_Year_Id;
    try {
      $delete = DB::table('fnc_cost_sched_personal_plus')->where('Cost_Sched_Personal_Plus_Id',$Cost_Sched_Personal_Plus_Id)->delete();
      Alert::success('Sukses menghapus data', 'Berhasil !');
      return redirect('set_biaya_tambahan?Term_Year_Id='.$Term_Year_Id);
    } catch (\Exception $e) {
      Alert::error('Data mungkin masih digunakan oleh data yang lain', 'Gagal !');
      return redirect('set_biaya_tambahan?Term_Year_Id='.$Term_Year_Id);
    }
  }
  // akhir delete
}
?>
