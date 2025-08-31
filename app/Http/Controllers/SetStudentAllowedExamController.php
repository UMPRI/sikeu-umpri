<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
use Auth;

class SetStudentAllowedExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function Update(Request $request){
        $termYearId = $request->termYearId;
        $entryYearId = $request->entryYearId;
        $departmentId = $request->departmentId;
        $q = $request->q;
        $page = $request->page;
        $registerNumber = $request->id;

        $res = [];
        $res['q'] = $q;
        $res['page'] = $page;
        $res['termYearId'] = $termYearId;
        $res['entryYearId'] = $entryYearId;
        $res['departmentId'] = $departmentId;

        $student = DB::table('acd_student')->where('Register_Number', $registerNumber)->first();
        if($student == null){
            $res['errorMessage'] = "Mahasiswa tidak ditemukan !";
            return redirect()->route('perizinan-krs.index', $res);
        }

        // Check if allowed exam exist
        $existAllowed = DB::table('acd_student_allowed_exam')
            ->where([
                ['Register_Number', $registerNumber],
                ['Term_Year_Id', $termYearId]
            ])
            ->first();
        if($existAllowed != null){ // if exist update
            $updateAllowedExam = DB::table('acd_student_allowed_exam')
                ->where('Student_Allowed_Exam_Id', $existAllowed->Student_Allowed_Exam_Id)
                ->update([
                    'Modified_By' => Auth::user()->name,
                    'Modified_Date' => Date('Y-m-d H:i:s')
                ]);
        } else { // if don't exist insert
            // dd($registerNumber);
            $insertAllowedExam = DB::table('acd_student_allowed_exam')
                ->insert([
                    'Register_Number' => $registerNumber,
                    'Term_Year_Id' => $termYearId,
                    'Created_By' => Auth::user()->name,
                    'Created_Date' => Date('Y-m-d H:i:s')
                ]);
        }

        return redirect()->route('perizinan-krs.index', $res);
    }

    public function Delete(Request $request){
        $termYearId = $request->termYearId;
        $entryYearId = $request->entryYearId;
        $departmentId = $request->departmentId;
        $q = $request->q;
        $page = $request->page;
        $id = $request->id;

        $res = [];
        $res['q'] = $q;
        $res['page'] = $page;
        $res['termYearId'] = $termYearId;
        $res['entryYearId'] = $entryYearId;
        $res['departmentId'] = $departmentId;

        // Check if allowed krs exist
        $existAllowed = DB::table('acd_student_allowed_exam')
            ->where('Student_Allowed_Exam_Id', $id)
            ->first();
        if($existAllowed != null){ // if exist delete
            $deleteAllowedExam = DB::table('acd_student_allowed_exam')
                ->where('Student_Allowed_Exam_Id', $existAllowed->Student_Allowed_Exam_Id)
                ->delete();
        }

        return redirect()->route('perizinan-krs.index', $res);
    }
}