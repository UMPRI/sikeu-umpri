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

class CourseCostTypeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  // index -----------------------------------------------------------------------------------------\\
  public function index()
  {
    $Class_Prog_Id = Input::get('Class_Prog_Id');
    $Term_Year_Id = Input::get('Term_Year_Id');
    $Department_Id = Input::get('Department_Id');

    // $Class_Prog_Id = 0;
    // $Term_Year_Id = 0;
    // $Department_Id = 0;
    // if (isset(Input::get('Class_Prog_Id'))) {
    //   $Class_Prog_Id = Input::get('Class_Prog_Id');
    // }
    // if (isset(Input::get('Term_Year_Id'))) {
    //   $Term_Year_Id = Input::get('Term_Year_Id');
    // }
    // if (isset(Input::get('Department_Id'))) {
    //   $Department_Id = Input::get('Department_Id');
    // }

    $q_Class_Prog = DB::table('mstr_department_class_program')
                          ->join('mstr_class_program', 'mstr_department_class_program.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                          ->where('Department_Id','=',$Department_Id)
                          ->get() ;
    $q_Term_Year = DB::table('mstr_term_year')->OrderBy('Term_Year_Id','desc')->get() ;
    $q_Department = DB::table('mstr_department')
                    ->join('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=', 'mstr_education_program_type.Education_Prog_Type_Id')
                    ->where('Faculty_Id','!=' ,null)
                    ->OrderBy('Department_Code','asc')->get() ;

    $exceptionList = DB::table('fnc_course_cost_type')
                          ->Where('Term_Year_Id','=',$Term_Year_Id)
                          ->where('Department_Id','=',$Department_Id)
                          ->where('Class_Prog_Id','=',$Class_Prog_Id)
                          ->get();

    $array_Course_Id = array();
    foreach ($exceptionList as $data_array) {
      $array_Course_Id[] = $data_array->Course_Id;
    }

    $Course_Cost_Type_Sks = DB::table('fnc_course_cost_type')
                              ->join('acd_course', 'fnc_course_cost_type.Course_Id', '=', 'acd_course.Course_Id')
                              ->Where('fnc_course_cost_type.Term_Year_Id','=',$Term_Year_Id)
                              ->where('fnc_course_cost_type.Department_Id','=',$Department_Id)
                              ->where('fnc_course_cost_type.Class_Prog_Id','=',$Class_Prog_Id)
                              ->where('Is_Sks','=','1')
                              ->get();
    $Course_Cost_Type_Paket = DB::table('fnc_course_cost_type')
                              ->join('acd_course', 'fnc_course_cost_type.Course_Id', '=', 'acd_course.Course_Id')
                              ->Where('fnc_course_cost_type.Term_Year_Id','=',$Term_Year_Id)
                              ->where('fnc_course_cost_type.Department_Id','=',$Department_Id)
                              ->where('fnc_course_cost_type.Class_Prog_Id','=',$Class_Prog_Id)
                              ->where('Is_Sks','=','0')
                              ->get();
    $acd_offered_course = DB::table('acd_offered_course')
                              ->join('acd_course', 'acd_offered_course.Course_Id', '=', 'acd_course.Course_Id')
                              ->select('acd_course.Course_Name','acd_course.Course_Id','acd_course.Course_Code')
                              ->whereNotIn('acd_offered_course.Course_Id', $array_Course_Id)
                              ->where('acd_offered_course.Term_Year_Id','=',$Term_Year_Id)
                              ->where('acd_offered_course.Department_Id','=',$Department_Id)
                              ->where('acd_offered_course.Class_Prog_Id','=',$Class_Prog_Id)
                              ->groupBy('acd_offered_course.Course_Id')
                              ->OrderBy('acd_course.Course_Name')
                              ->get();

    // data per paket ---------------------------
    $q_Course_Package_Count = DB::table('fnc_course_cost_package')
                                  ->join('fnc_course_cost_type','fnc_course_cost_package.Course_Cost_Type_Id','=','fnc_course_cost_type.Course_Cost_Type_Id')
                                  ->join('acd_course','fnc_course_cost_type.Course_Id','=','acd_course.Course_Id')
                                  ->where('fnc_course_cost_type.Term_Year_Id','=',$Term_Year_Id)
                                  ->where('fnc_course_cost_type.Class_Prog_Id','=',$Class_Prog_Id)
                                  ->where('acd_course.Department_Id','=',$Department_Id)
                                  ->GroupBy('fnc_course_cost_type.Course_Id')
                                  ->OrderBy('acd_course.Course_Name','asc')
                                  ->count();

    // data per sks ---------------------------
    $q_Course_Sks_Count = DB::table('fnc_course_cost_sks')
                        ->where('Term_Year_Id','=',$Term_Year_Id)
                        ->where('Class_Prog_Id','=',$Class_Prog_Id)
                        ->OrderBy('Entry_Year_Id','desc')
                        ->count();



    return view('set_jenis_mata_kuliah/index')
          ->with('acd_offered_course',$acd_offered_course)
          ->with('Course_Cost_Type_Paket',$Course_Cost_Type_Paket)
          ->with('Course_Cost_Type_Sks',$Course_Cost_Type_Sks)
          ->with('q_Department',$q_Department)
          ->with('q_Term_Year',$q_Term_Year)
          ->with('q_Class_Prog',$q_Class_Prog)
          ->with('Class_Prog_Id',$Class_Prog_Id)
          ->with('Term_Year_Id',$Term_Year_Id)
          ->with('Department_Id',$Department_Id)
          ->with('Package_Count', $q_Course_Package_Count)
          ->with('Sks_Count', $q_Course_Sks_Count);
  }

  public function createpost(){
    // $Course_Cost_Type_Id = Input::get('Course_Cost_Type_Id');
    $Term_Year_Id = Input::get('Term_Year_Id');
    $Department_Id = Input::get('Department_Id');
    $Class_Prog_Id = Input::get('Class_Prog_Id');
    // $CourseId = Input::get('CourseId');
    $Is_Sks = Input::get('Is_Sks');
    // $Created_By = Input::get('Created_By');
    // $Created_Date = Input::get('Created_Date');
    // $Modified_By = Input::get('Modified_By');
    // $Modified_Date = Input::get('Modified_Date');
    $CourseId = Input::get('CourseId');
    $delSKS = Input::get('delSKS');
    $delPaket = Input::get('delPaket');
    $Is_Delete = Input::get('Is_Delete');
    if (isset($_POST['btnSKS'])) {
      # code...
    }elseif (isset($_POST['btnDelSKS'])) {
      $Is_Delete = 1;
    }elseif (isset($_POST['btnDelPaket'])) {
      $Is_Delete = 1;
      $Is_Sks = 0;
    }elseif (isset($_POST['btnPaket'])) {
      $Is_Sks = 0;
    }

    if ($Is_Delete == "1")
    {
      if ($Is_Sks == "1")
      {
        try
        {
          if (is_array($delSKS) || is_object($delSKS)) {
            // db.fnc_course_cost_type.Where(cct => delSKS.Contains(cct.Course_Cost_Type_Id)).Delete();
            DB::table('fnc_course_cost_type')->WhereIn('Course_Cost_Type_Id', $delSKS)->delete();
          }else{
            DB::table('fnc_course_cost_type')->Where('Course_Cost_Type_Id','=', $delSKS)->delete();
          }
        }
        catch (Exception $e)
        {
            // TempData["shortMessage"] = "Data tidak bisa dihapus, dikarenakan Harganya telah disetting..";
        }
      }
      else
      {
        try
        {
            if (is_array($delPaket) || is_object($delPaket)) {
              // db.fnc_course_cost_type.Where(cct => delPaket.Contains(cct.Course_Cost_Type_Id)).Delete();
              DB::table('fnc_course_cost_type')->WhereIn('Course_Cost_Type_Id', $delPaket)->delete();
            }else{
              DB::table('fnc_course_cost_type')->Where('Course_Cost_Type_Id','=', $delPaket)->delete();
            }
        }
        catch (Exception $e)
        {
            // TempData["shortMessage"] = "Data tidak bisa dihapus, dikarenakan Harganya telah disetting..";
        }
      }
    }
    else
    {
      if ($CourseId != null)
      {
        if (is_array($CourseId) || is_object($CourseId)) {
          foreach ($CourseId as $Course_Id)
          {
            // fnc_Course_Cost_Type.Course_Id = $course;
            // db.fnc_course_cost_type.Add(fnc_Course_Cost_Type);
            // db.SaveChanges();

            DB::table('fnc_course_cost_type')->insert([
              'Term_Year_Id' => $Term_Year_Id ,
              'Department_Id' => $Department_Id ,
              'Class_Prog_Id' => $Class_Prog_Id ,
              'Course_Id' => $Course_Id ,
              'Is_Sks' => $Is_Sks
            ]);
          }
        }else{
          DB::table('fnc_course_cost_type')->insert([
            'Term_Year_Id' => $Term_Year_Id ,
            'Department_Id' => $Department_Id ,
            'Class_Prog_Id' => $Class_Prog_Id ,
            'Course_Id' => $CourseId ,
            'Is_Sks' => $Is_Sks
          ]);
        }
      }
    }
    return redirect('set_jenis_mata_kuliah?Term_Year_Id='.$Term_Year_Id.'&Department_Id='.$Department_Id.'&Class_Prog_Id='.$Class_Prog_Id);
  }
}
