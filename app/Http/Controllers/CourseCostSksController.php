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

class CourseCostSksController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }


  // index ---------------------------------------------------------------------------------------------------------------------------------------
  public function index(){
    $Class_Prog_Id = Input::get('Class_Prog_Id');
    $Term_Year_Id = Input::get('Term_Year_Id');
    $q_Term_Year = DB::table('mstr_term_year')->OrderBy('Term_Year_Id','desc')->get() ;
    $q_Class_Program = DB::table('mstr_class_program')->OrderBy('Class_Prog_Id','asc')->get() ;

    $q_Entry_Year_FCCS = DB::table('fnc_course_cost_sks')
                                  ->select('Entry_Year_Id')
                                  ->where('Term_Year_Id','=',$Term_Year_Id)
                                  ->where('Class_Prog_Id','=',$Class_Prog_Id)
                                  ->GroupBy('fnc_course_cost_sks.Entry_Year_Id')
                                  ->OrderBy('Entry_Year_Id','desc')
                                  ->get();
    $q_Department_FCCS = DB::table('fnc_course_cost_sks')
                                  ->join('mstr_department','fnc_course_cost_sks.Department_Id','=','mstr_department.Department_Id')
                                  ->join('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=', 'mstr_education_program_type.Education_Prog_Type_Id')
                                  ->where('Term_Year_Id','=',$Term_Year_Id)
                                  ->where('Class_Prog_Id','=',$Class_Prog_Id)
                                  ->GroupBy('fnc_course_cost_sks.Department_Id')
                                  ->OrderBy('mstr_department.Department_Name','asc')
                                  ->get();
    return view('biaya_per_sks/index')
    ->with('q_Entry_Year_FCCS',$q_Entry_Year_FCCS)
    ->with('q_Department_FCCS',$q_Department_FCCS)
    ->with('Class_Prog_Id', $Class_Prog_Id)
    ->with('Term_Year_Id', $Term_Year_Id)
    ->with('q_Term_Year', $q_Term_Year)
    ->with('q_Class_Prog', $q_Class_Program)
    ;
  }
    // ----------------------------------------------------------- akhir index ------------------------------------------------------------------------

    // create -----------------------------------------------------------------------------------------------------------------------------------------
    public function create(){
      $Class_Prog_Id = Input::get('Class_Prog_Id');
      $Term_Year_Id = Input::get('Term_Year_Id');
      $Department_Id = Input::get('Department_Id');
      $Entry_Year_Id = Input::get('Entry_Year_Id');

      if ($Department_Id != null) {
        $q_Department = DB::table('mstr_department')->join('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=', 'mstr_education_program_type.Education_Prog_Type_Id')->where('Department_Id',$Department_Id)->get();
      }else{
        $q_Department = DB::table('mstr_department_class_program')->join('mstr_department','mstr_department_class_program.Department_Id','=','mstr_department.Department_Id')->join('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=', 'mstr_education_program_type.Education_Prog_Type_Id')->where('mstr_department_class_program.Class_Prog_Id',$Class_Prog_Id)->OrderBy('mstr_department.Department_Name')->get();
      }

      if ($Entry_Year_Id != null) {
        $q_Entry_Year = DB::table('mstr_entry_year')->where('Entry_Year_Id','=',$Entry_Year_Id)->get();
      }else{
        $q_Entry_Year = DB::table('mstr_entry_year')->OrderBy('Entry_Year_Id','desc')->get();
      }

      $q_Class_Program = DB::table('mstr_class_program')->where('Class_Prog_Id','=',$Class_Prog_Id)->get();
      $q_Term_Year = DB::table('mstr_term_year')->where('Term_Year_Id','=',$Term_Year_Id)->get();

      return view('biaya_per_sks/create')
      ->with('q_Department', $q_Department)
      ->with('q_Entry_Year', $q_Entry_Year)
      ->with('q_Class_Prog', $q_Class_Program)
      ->with('q_Term_Year', $q_Term_Year)
      ->with('Class_Prog_Id',$Class_Prog_Id)
      ->with('Term_Year_Id',$Term_Year_Id)
      ->with('Department_Id',$Department_Id)
      ->with('Entry_Year_Id',$Entry_Year_Id);
    }
    // ----------------------------------------------------------- akhir create -----------------------------------------------------------------------

    // create_post ------------------------------------------------------------------------------------------------------------------------------------
    public function create_post(){
      $Term_Year_Id = Input::get('Term_Year_Id');
      $Department_Id = Input::get('Department_Id');
      $Class_Prog_Id = Input::get('Class_Prog_Id');
      $Entry_Year_Id = Input::get('Entry_Year_Id');
      $Amount_Per_Sks = Input::get('Amount_Per_Sks');

      $insert = DB::table('fnc_course_cost_sks')->insert([
        'Term_Year_Id' => $Term_Year_Id,
        'Department_Id' => $Department_Id,
        'Class_Prog_Id' => $Class_Prog_Id,
        'Entry_Year_Id' => $Entry_Year_Id,
        'Amount_Per_Sks' => $Amount_Per_Sks,
      ]);
      if ($insert) {
        Alert::success('Sukses menambahkan data', 'Berhasil !');
        return redirect('biaya_per_sks?Term_Year_Id='.$Term_Year_Id.'&Class_Prog_Id='.$Class_Prog_Id);
      }else{
        Alert::error('Gagal menambahkan data', 'Gagal !');
        return redirect('biaya_per_sks/create?Term_Year_Id='.$Term_Year_Id.'&Class_Prog_Id='.$Class_Prog_Id.'&Department_Id='.$Department_Id.'&Entry_Year_Id='.$Entry_Year_Id);
      }
    }
    // --------------------------------------------------------- akhir create post --------------------------------------------------------------------

    // edit -------------------------------------------------------------------------------------------------------------------------------------------
    public function edit($Course_Cost_Sks_Id){
      $q_Course_Cost_Sks = DB::table('fnc_course_cost_sks')
      ->join('mstr_term_year','fnc_course_cost_sks.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
      ->join('mstr_department','fnc_course_cost_sks.Department_Id','=','mstr_department.Department_Id')
      ->join('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=', 'mstr_education_program_type.Education_Prog_Type_Id')
      ->join('mstr_class_program','fnc_course_cost_sks.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
      ->join('mstr_entry_year','fnc_course_cost_sks.Entry_year_Id','=','mstr_entry_year.Entry_year_Id')
      ->where('Course_Cost_Sks_Id','=',$Course_Cost_Sks_Id)->get();
      return view('biaya_per_sks/edit')->with('q_Course_Cost_Sks',$q_Course_Cost_Sks);
    }
    // -----------------------------------------------------------akhir edit --------------------------------------------------------------------------

    // edit post --------------------------------------------------------------------------------------------------------------------------------------
    public function edit_post($Course_Cost_Sks_Id){
      $q_Course_Cost_Sks = DB::table('fnc_course_cost_sks')->where('Course_Cost_Sks_Id','=',$Course_Cost_Sks_Id)->get();
      foreach ($q_Course_Cost_Sks as $data) {
        $Amount_Per_Sks = Input::get('Amount_Per_Sks');
        $update = DB::table('fnc_course_cost_sks')->where('Course_Cost_Sks_Id','=',$Course_Cost_Sks_Id)->update(['Amount_Per_Sks' => $Amount_Per_Sks]);
        if ($update) {
          Alert::success('Sukses mengubah data', 'Berhasil !');
          return redirect('biaya_per_sks?Term_Year_Id='.$data->Term_Year_Id.'&Class_Prog_Id='.$data->Class_Prog_Id);
        }else{
          Alert::error('Gagal mengubah data', 'Gagal !');
          return redirect('biaya_per_sks/edit?Term_Year_Id='.$data->Term_Year_Id.'&Class_Prog_Id='.$data->Class_Prog_Id.'&Department_Id='.$data->Department_Id.'&Entry_Year_Id='.$data->Entry_Year_Id);
        }
      }
    }
    // ------------------------------------------------------------ akhir edit post -------------------------------------------------------------------

    //-------------------cetak excel -----------------------------------------------------------------------------------------------------------------------
    public function cetakSks(Request $request)
    {
      $Class_Prog_Id = $request->Class_Prog_Id;
      $Term_Year_Id =  $request->Term_Year_Id;
      // $q_Term_Year = DB::table('mstr_term_year')->OrderBy('Term_Year_Id','desc')->get() ;
      // $q_Class_Program = DB::table('mstr_class_program')->OrderBy('Class_Prog_Id','asc')->get() ;

      $q_Entry_Year_FCCS = DB::table('fnc_course_cost_sks')
                                    ->select('Entry_Year_Id')
                                    ->where('Term_Year_Id','=',$Term_Year_Id)
                                    ->where('Class_Prog_Id','=',$Class_Prog_Id)
                                    ->GroupBy('fnc_course_cost_sks.Entry_Year_Id')
                                    ->OrderBy('Entry_Year_Id','desc')
                                    ->get();
      $q_Department_FCCS = DB::table('fnc_course_cost_sks')
                                    ->join('mstr_department','fnc_course_cost_sks.Department_Id','=','mstr_department.Department_Id')
                                    ->where('Term_Year_Id','=',$Term_Year_Id)
                                    ->where('Class_Prog_Id','=',$Class_Prog_Id)
                                    ->GroupBy('fnc_course_cost_sks.Department_Id')
                                    ->OrderBy('mstr_department.Department_Name','asc')
                                    ->get();

      return view('biaya_per_sks/cetakSks')
      ->with('q_Entry_Year_FCCS',$q_Entry_Year_FCCS)
      ->with('q_Department_FCCS',$q_Department_FCCS)
      ->with('Class_Prog_Id', $Class_Prog_Id)
      ->with('Term_Year_Id', $Term_Year_Id);
      // ->with('q_Term_Year', $q_Term_Year)
      // ->with('q_Class_Prog', $q_Class_Program);
    }
    //-------------------cetak excel -----------------------------------------------------------------------------------------------------------------------


    // delete -----------------------------------------------------------------------------------------------------------------------------------------
    public function delete($Course_Cost_Sks_Id){
      $Term_Year_Id = Input::get('Term_Year_Id');
      $Class_Prog_Id = Input::get('Class_Prog_Id');
      $delete = DB::table('fnc_course_cost_sks')->where('Course_Cost_Sks_Id','=',$Course_Cost_Sks_Id)->delete();
      if ($delete) {
        Alert::success('Sukses menghapus data', 'Berhasil !');
        return redirect('biaya_per_sks?Term_Year_Id='.$Term_Year_Id.'&Class_Prog_Id='.$Class_Prog_Id);
      }else{
        Alert::error('Gagal menghapus data', 'Gagal !');
        return redirect('biaya_per_sks?Term_Year_Id='.$Term_Year_Id.'&Class_Prog_Id='.$Class_Prog_Id);
      }
    }
    // ------------------------------------------------------------ akhir delete -----------------------------------------------------------------------
}
