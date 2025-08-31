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
use Auth;

class CostSchedDispensasi extends Controller
{
    public function __construct()
    {
          $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
                    ->leftJoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
                    ->first();
      }

      $Student_Id = "";
      if ($q_Student != null) {
          $Register_Number = $q_Student->Register_Number;
          $Student_Id = $q_Student->Student_Id;
      }else{
          $Register_Number = null;
          $Student_Id = null;
      }


      $q_fnc_student_cost_krs_personal =  DB::table('fnc_student_cost_krs_personal')
                                              ->join('acd_course_type','fnc_student_cost_krs_personal.Course_Type_Id','=','acd_course_type.Course_Type_Id')
                                              ->join('mstr_term_year','fnc_student_cost_krs_personal.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
                                              ->where('Student_Id', $Student_Id)
                                              ->where('fnc_student_cost_krs_personal.Term_Year_Id', $Term_Year_Id)
                                              ->get();

      // dd($q_fnc_student_cost_krs_personal);
      return view('set_biaya_dispensasi/index')
              ->with('q_Term_Year',$q_Term_Year)
              ->with('param',$param)
              ->with('Term_Year_Id', $Term_Year_Id)
              ->with('q_Student', $q_Student)
              ->with('fnc_student_cost_krs_personal', $q_fnc_student_cost_krs_personal)
      ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Term_Year_Id = Input::get('Term_Year_Id');
        $param = Input::get('param');

        $q_Term_Year = DB::table('mstr_term_year')
                      ->where('Term_Year_Id', $Term_Year_Id)
                      ->OrderBy('Term_Year_Id','desc')->first();


        $q_Student = DB::table('acd_student')->where('Nim','=',$param)
                    ->leftJoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
                    ->first();
        if ($q_Student == null) {
          $q_Student = DB::table('acd_student')
                      ->where('Register_Number','=',$param)
                      ->leftJoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id'
                      )->first();

        }

        $q_Acd_Course_Type = DB::table("acd_course_type")->get();


        return view('set_biaya_dispensasi/create')
          ->with('q_Term_Year',$q_Term_Year)
          ->with('param',$param)
          ->with('Term_Year_Id', $Term_Year_Id)
          ->with('q_Student', $q_Student)
          ->with('q_Acd_Course_Type', $q_Acd_Course_Type)
        ;

    }


    public function create_post(Request $request)
    {
      $Student_Id = $request->Student_Id;
      $Term_Year_Id = $request->Term_Year_Id;
      $Course_Type_id = $request->Course_Type_id;
      $Percent = $request->Percent;
      $Description = $request->Description;
      $name = Auth::user()->name;

      $nim = $request->nim;
      // dd("Student_Id = ".$Student_Id." Term_Year_Id = ".$Term_Year_Id." Course_Type_id = ".$Course_Type_id." Percent = ".$Percent." Description = ".$Description." name = ".$name);

      try {
        $insert = DB::table('fnc_student_cost_krs_personal')->insert([
          'Student_Id' => $Student_Id,
          'Term_Year_Id' => $Term_Year_Id,
          'Course_Type_id' => $Course_Type_id,
          'Percent' => $Percent,
          'Description' => $Description,
          'Created_By' => $name
        ]);
        Alert::success('Sukses Menambahkan data', 'Berhasil !');
        return redirect('set_biaya_keyin?param='.$nim.'&Term_Year_Id='.$Term_Year_Id);
      } catch (\Exception $e) {
        $message = $e->getMessage();
        Alert::error($message, 'Gagal Menyimpan Data');
        echo $message;

        // return redirect('set_biaya_dispensasi_keyin?param='.$Student_Id.'&Term_Year_Id='.$Term_Year_Id);
      }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request)
    {
        $Student_Cost_Krs_Personal_Id = $request->Student_Cost_Krs_Personal_Id;
        $Term_Year_Id = Input::get('Term_Year_Id');
        $param = Input::get('param');

        $q_Term_Year = DB::table('mstr_term_year')
                      ->where('Term_Year_Id', $Term_Year_Id)
                      ->OrderBy('Term_Year_Id','desc')->first();


        $q_Student = DB::table('acd_student')->where('Nim','=',$param)
                    ->leftJoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
                    ->first();
        if ($q_Student == null) {
          $q_Student = DB::table('acd_student')
                      ->where('Register_Number','=',$param)
                      ->leftJoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id'
                      )->first();

        }

        $q_Acd_Course_Type = DB::table("acd_course_type")->get();
        $q_fnc_student_cost_krs_personal =  DB::table('fnc_student_cost_krs_personal')
                                                ->join('acd_course_type','fnc_student_cost_krs_personal.Course_Type_Id','=','acd_course_type.Course_Type_Id')
                                                ->where('Student_Cost_Krs_Personal_Id', $Student_Cost_Krs_Personal_Id)
                                                ->first();

        // dd($q_fnc_student_cost_krs_personal);
        return view('set_biaya_dispensasi/edit')
          ->with('q_Term_Year',$q_Term_Year)
          ->with('param',$param)
          ->with('Term_Year_Id', $Term_Year_Id)
          ->with('q_Student', $q_Student)
          ->with('q_Acd_Course_Type', $q_Acd_Course_Type)
          ->with('q_fnc_student_cost_krs_personal', $q_fnc_student_cost_krs_personal)
        ;
    }


    public function edit_post(Request $request)
    {
        $Student_Cost_Krs_Personal_Id = $request->Student_Cost_Krs_Personal_Id;
        $Term_Year_Id = $request->Term_Year_Id;
        $Course_Type_id = $request->Course_Type_id;
        $Percent = $request->Percent;
        $Description = $request->Description;
        $name = Auth::user()->name;

        $nim = $request->nim;

        try {
          $update = DB::table('fnc_student_cost_krs_personal')
          ->where('Student_Cost_Krs_Personal_Id', $Student_Cost_Krs_Personal_Id)
          ->update([
            'Course_Type_id' => $Course_Type_id,
            'Percent' => $Percent,
            'Description' => $Description,
            'Created_By' => $name
          ]);

          Alert::success('Sukses Update data', 'Berhasil !');
          return redirect('set_biaya_keyin?param='.$nim.'&Term_Year_Id='.$Term_Year_Id);

        } catch (\Exception $e) {
          $message = $e->getMessage();
          Alert::error($message, 'Gagal Menyimpan Data');
          echo $message;

          // return redirect('set_biaya_dispensasi_keyin?param='.$Student_Id.'&Term_Year_Id='.$Term_Year_Id);
        }

    }


    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $param = $request->param;
        $Term_Year_Id = $request->Term_Year_Id;
        $Student_Cost_Krs_Personal_Id = $request->Student_Cost_Krs_Personal_Id;
        $delete = DB::table('fnc_student_cost_krs_personal')
                  ->where('Student_Cost_Krs_Personal_Id', $Student_Cost_Krs_Personal_Id)
                  ->delete();
        Alert::success('Sukses menghapus data', 'Berhasil !');
        return redirect('/set_biaya_keyin?param='.$param.'&Term_Year_Id='.$Term_Year_Id);
    }
}
