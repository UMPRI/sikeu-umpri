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

class CourseCostPackageController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }


  // index ---------------------------------------------------------------------------------------------------------------------------------------
  public function index(){
    $Class_Prog_Id = Input::get('Class_Prog_Id');
    $Term_Year_Id = Input::get('Term_Year_Id');
    $Department_Id = Input::get('Department_Id');
    $q_Term_Year = DB::table('mstr_term_year')->OrderBy('Term_Year_Id','desc')->get() ;
    $q_Class_Program = DB::table('mstr_class_program')->OrderBy('Class_Prog_Id','asc')->get() ;
    $q_Department = DB::table('mstr_department')
                    ->join('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=', 'mstr_education_program_type.Education_Prog_Type_Id')
                    ->where('Faculty_Id', '!=' ,null)
                    ->OrderBy('Department_Code','asc')->get() ;

    $q_Entry_Year_FCCP = DB::table('fnc_course_cost_package')
                                  ->join('fnc_course_cost_type','fnc_course_cost_package.Course_Cost_Type_Id','=','fnc_course_cost_type.Course_Cost_Type_Id')
                                  ->select('fnc_course_cost_package.Entry_Year_Id')
                                  ->where('fnc_course_cost_type.Term_Year_Id','=',$Term_Year_Id)
                                  ->where('fnc_course_cost_type.Class_Prog_Id','=',$Class_Prog_Id)
                                  ->where('fnc_course_cost_type.Department_Id','=',$Department_Id)
                                  ->GroupBy('fnc_course_cost_package.Entry_Year_Id')
                                  ->OrderBy('fnc_course_cost_package.Entry_Year_Id','desc')
                                  ->get();
    $q_Course = DB::table('fnc_course_cost_package')
                                  ->join('fnc_course_cost_type','fnc_course_cost_package.Course_Cost_Type_Id','=','fnc_course_cost_type.Course_Cost_Type_Id')
                                  ->join('acd_course','fnc_course_cost_type.Course_Id','=','acd_course.Course_Id')
                                  ->where('fnc_course_cost_type.Term_Year_Id','=',$Term_Year_Id)
                                  ->where('fnc_course_cost_type.Class_Prog_Id','=',$Class_Prog_Id)
                                  ->where('acd_course.Department_Id','=',$Department_Id)
                                  ->GroupBy('fnc_course_cost_type.Course_Id')
                                  ->OrderBy('acd_course.Course_Name','asc')
                                  ->get();
    return view('biaya_per_paket/index')
    ->with('q_Entry_Year_FCCP',$q_Entry_Year_FCCP)
    ->with('q_Course',$q_Course)
    ->with('Class_Prog_Id', $Class_Prog_Id)
    ->with('Term_Year_Id', $Term_Year_Id)
    ->with('Department_Id', $Department_Id)
    ->with('q_Term_Year', $q_Term_Year)
    ->with('q_Class_Prog', $q_Class_Program)
    ->with('q_Department', $q_Department)
    ;
  }
    // ----------------------------------------------------------- akhir index ------------------------------------------------------------------------

    // create -----------------------------------------------------------------------------------------------------------------------------------------
    public function create(){
      $Class_Prog_Id = Input::get('Class_Prog_Id');
      $Term_Year_Id = Input::get('Term_Year_Id');
      $Department_Id = Input::get('Department_Id');
      $Entry_Year_Id = Input::get('Entry_Year_Id');
      $Course_Cost_Type_Id = Input::get('Course_Cost_Type_Id');

      if ($Entry_Year_Id != null) {
        $q_Entry_Year = DB::table('mstr_entry_year')->where('Entry_Year_Id','=',$Entry_Year_Id)->get();
      }else{
        $q_Entry_Year = DB::table('mstr_entry_year')->OrderBy('Entry_Year_Id','desc')->get();
      }
      if ($Course_Cost_Type_Id != null) {
        $q_FCCT = DB::table('fnc_course_cost_type')
        ->join('acd_course','fnc_course_cost_type.Course_Id','=','acd_course.Course_Id')
        ->where('fnc_course_cost_type.Course_Cost_Type_Id','=',$Course_Cost_Type_Id)
        ->get();
      }else{
        $q_FCCT = DB::table('fnc_course_cost_type')
        ->join('acd_course','fnc_course_cost_type.Course_Id','=','acd_course.Course_Id')
        ->where('fnc_course_cost_type.Term_Year_Id','=',$Term_Year_Id)
        ->where('fnc_course_cost_type.Class_Prog_Id','=',$Class_Prog_Id)
        ->where('fnc_course_cost_type.Department_Id','=',$Department_Id)
        ->where('fnc_course_cost_type.Is_Sks','=',0)
        ->get();
      }

      $q_Department = DB::table('mstr_department')->where('Department_Id',$Department_Id)->get();
      $q_Class_Program = DB::table('mstr_class_program')->where('Class_Prog_Id','=',$Class_Prog_Id)->get();
      $q_Term_Year = DB::table('mstr_term_year')->where('Term_Year_Id','=',$Term_Year_Id)->get();

      return view('biaya_per_paket/create')
      ->with('q_FCCT', $q_FCCT)
      ->with('q_Department', $q_Department)
      ->with('q_Entry_Year', $q_Entry_Year)
      ->with('q_Class_Prog', $q_Class_Program)
      ->with('q_Term_Year', $q_Term_Year)
      ->with('Class_Prog_Id',$Class_Prog_Id)
      ->with('Term_Year_Id',$Term_Year_Id)
      ->with('Department_Id',$Department_Id)
      ->with('Course_Cost_Type_Id',$Course_Cost_Type_Id)
      ->with('Entry_Year_Id',$Entry_Year_Id);
    }
    // ----------------------------------------------------------- akhir create -----------------------------------------------------------------------

    // create_post ------------------------------------------------------------------------------------------------------------------------------------
    public function create_post(){
      $Term_Year_Id = Input::get('Term_Year_Id');
      $Department_Id = Input::get('Department_Id');
      $Class_Prog_Id = Input::get('Class_Prog_Id');
      $Entry_Year_Id = Input::get('Entry_Year_Id');
      $Amount_Per_Mk = Input::get('Amount_Per_Mk');
      $Course_Cost_Type_Id = Input::get('Course_Cost_Type_Id');

      $insert = DB::table('fnc_course_cost_package')->insert([
        'Course_Cost_Type_Id' => $Course_Cost_Type_Id,
        'Entry_Year_Id' => $Entry_Year_Id,
        'Amount_Per_Mk' => $Amount_Per_Mk,
      ]);
      if ($insert) {
        return redirect('biaya_per_paket?Term_Year_Id='.$Term_Year_Id.'&Department_Id='.$Department_Id.'&Class_Prog_Id='.$Class_Prog_Id);
      }else{
        $pesan = "<div class='alert alert-success alert-dismissible fade show' role='alert'><h4 class='alert-heading'>Well done!</h4><p>Aww yeah, you successfully read this important alert message. This example text is going to run a bit longer so that you can see how spacing within an alert works with this kind of content.</p></div>";
        return redirect('biaya_per_paket/create?Term_Year_Id='.$Term_Year_Id.'&Department_Id='.$Department_Id.'&Class_Prog_Id='.$Class_Prog_Id.'&Entry_Year_Id='.$Entry_Year_Id)->with('pesan',$pesan);
      }
    }
    // --------------------------------------------------------- akhir create post --------------------------------------------------------------------

    // edit -------------------------------------------------------------------------------------------------------------------------------------------
    public function edit($Course_Cost_Package_Id){
      $q_Course_Cost_Package = DB::table('fnc_course_cost_package')
      ->join('fnc_course_cost_type','fnc_course_cost_package.Course_Cost_Type_Id','=','fnc_course_cost_type.Course_Cost_Type_Id')
      ->join('mstr_term_year','fnc_course_cost_type.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
      ->join('mstr_department','fnc_course_cost_type.Department_Id','=','mstr_department.Department_Id')
      ->join('mstr_class_program','fnc_course_cost_type.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
      ->join('mstr_entry_year','fnc_course_cost_package.Entry_year_Id','=','mstr_entry_year.Entry_year_Id')
      ->where('Course_Cost_Package_Id','=',$Course_Cost_Package_Id)->get();
      return view('biaya_per_paket/edit')->with('q_Course_Cost_Package',$q_Course_Cost_Package);
    }
    // -----------------------------------------------------------akhir edit --------------------------------------------------------------------------

    // edit post --------------------------------------------------------------------------------------------------------------------------------------
    public function edit_post($Course_Cost_Package_Id){
      $q_Course_Cost_Package = DB::table('fnc_course_cost_package')
                                  ->join('fnc_course_cost_type','fnc_course_cost_package.Course_Cost_Type_Id','=','fnc_course_cost_type.Course_Cost_Type_Id')
                                  ->where('Course_Cost_Package_Id','=',$Course_Cost_Package_Id)->get();
      foreach ($q_Course_Cost_Package as $data) {
        $Amount_Per_Mk = Input::get('Amount_Per_Mk');
        $update = DB::table('fnc_course_cost_package')->where('Course_Cost_Package_Id','=',$Course_Cost_Package_Id)->update(['Amount_Per_Mk' => $Amount_Per_Mk]);
        if ($update) {
          $pesan = "<div class='alert alert-success alert-dismissible fade show' role='alert'><h4 class='alert-heading'>Berhasil !</h4><p>Berhasil mengupdate data.</p></div>";
          return redirect('biaya_per_paket?Term_Year_Id='.$data->Term_Year_Id.'&Department_Id='.$data->Department_Id.'&Class_Prog_Id='.$data->Class_Prog_Id);
        }else{
          $pesan = "<div class='alert alert-danger alert-dismissible fade show' role='alert'><h4 class='alert-heading'>Gagal !</h4><p>Terjadi Kesalahan saat mengupdate data.</p></div>";
          return redirect('biaya_per_paket/edit/'.$Course_Cost_Package_Id.'?Term_Year_Id='.$data->Term_Year_Id.'&Class_Prog_Id='.$data->Class_Prog_Id.'&Department_Id='.$data->Department_Id.'&Entry_Year_Id='.$data->Entry_Year_Id)->with('pesan',$pesan);
        }
      }
    }
    // ------------------------------------------------------------ akhir edit post -------------------------------------------------------------------

    // delete -----------------------------------------------------------------------------------------------------------------------------------------
    public function delete($Course_Cost_Package_Id){
      $Term_Year_Id = Input::get('Term_Year_Id');
      $Class_Prog_Id = Input::get('Class_Prog_Id');
      $Department_Id = Input::get('Department_Id');
      $delete = DB::table('fnc_course_cost_package')->where('Course_Cost_Package_Id','=',$Course_Cost_Package_Id)->delete();
      if ($delete) {
        $pesan = "<div class='alert alert-success alert-dismissible fade show' role='alert'><h4 class='alert-heading'>Berhasil !</h4><p>Berhasil menghapus data.</p></div>";
        return redirect('biaya_per_paket?Term_Year_Id='.$Term_Year_Id.'&Department_Id='.$Department_Id.'&Class_Prog_Id='.$Class_Prog_Id);
      }else{
        $pesan = "<div class='alert alert-danger alert-dismissible fade show' role='alert'><h4 class='alert-heading'>Gagal !</h4><p>Terjadi Kesalahan saat menghapus data.</p></div>";
        return redirect('biaya_per_paket?Term_Year_Id='.$Term_Year_Id.'&Department_Id='.$Department_Id.'&Class_Prog_Id='.$Class_Prog_Id)->with('pesan',$pesan);
      }
    }
    // ------------------------------------------------------------ akhir delete -----------------------------------------------------------------------
}
