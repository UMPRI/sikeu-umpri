<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
use Auth;

class SetStudentAllowedKRSController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Index(Request $request){
        $termYearId = $request->termYearId;
        $entryYearId = $request->entryYearId;
        $departmentId = $request->departmentId;
        $q = $request->q;
        $page = $request->page;

        $res = [];
        $res['q'] = $q;
        $res['page'] = $page;
        $res['termYearId'] = $termYearId;
        $res['entryYearId'] = $entryYearId;
        $res['departmentId'] = $departmentId;
        $res['termYears'] = DB::table('mstr_term_year')->orderBy('Term_Year_Id', 'desc')->get();
        $res['entryYears'] = DB::table('mstr_entry_year')->orderBy('Entry_Year_Id', 'desc')->get();
        $res['departments'] = DB::table('mstr_department')->join('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=', 'mstr_education_program_type.Education_Prog_Type_Id')->orderBy('Department_Name', 'asc')->get();

        $res['students'] = DB::table('acd_student')
            ->select(
                'acd_student.*', 
                'acd_student_allowed_krs.Student_Allowed_Krs_Id', 
                'acd_student_allowed_krs.Term_Year_Id', 
                'acd_student_allowed_krs.Is_Auto',
                'acd_student_allowed_krs.Created_By',
                'acd_student_allowed_krs.Created_Date',
                'acd_student_allowed_krs.Modified_By',
                'acd_student_allowed_krs.Modified_Date',
                'acd_student_allowed_exam.Student_Allowed_Exam_Id'
            )
            ->leftJoin('acd_student_allowed_krs', function ($join) use ($termYearId) {
                $join->on('acd_student_allowed_krs.Register_Number', '=', 'acd_student.Register_Number');
                $join->where('acd_student_allowed_krs.Term_Year_Id', $termYearId);
            })
            ->leftJoin('acd_student_allowed_exam', function ($join) use ($termYearId) {
                $join->on('acd_student_allowed_exam.Register_Number', '=', 'acd_student.Register_Number');
                $join->where('acd_student_allowed_exam.Term_Year_Id', $termYearId);
            })
            ->where([
                ['acd_student.Department_Id', $departmentId],
                ['acd_student.Entry_Year_Id', $entryYearId]
            ])
            ->where(function ($query) use ($q) {
                if($q != null && $q != ""){
                    $query->whereRaw('LOWER(`Nim`) LIKE ? ', [trim(strtolower($q)) . '%'])
                    ->orWhereRaw('LOWER(`Full_Name`) LIKE ? ', [trim(strtolower($q)) . '%']);
                }
            })
            ->orderBy('acd_student.Nim', 'asc')
            ->paginate(10);

        $res['students']->appends(request()->input());

        // $res['studentAllowedAutos'] = DB::table('acd_student_allowed_krs')
        //     ->join('acd_student', 'acd_student_allowed_krs.Register_Number', '=', 'acd_student.Register_Number')
        //     ->where([
        //         ['Term_Year_Id', $termYearId],
        //         ['acd_student.Department_Id', $departmentId],
        //         ['acd_student.Entry_Year_Id', $entryYearId],
        //         ['Is_Auto', true]
        //     ])
        //     ->orderBy('acd_student.Nim', 'asc')
        //     ->get();
        
        // $res['studentAllowedManuals'] = DB::table('acd_student_allowed_krs')
        //     ->join('acd_student', 'acd_student_allowed_krs.Register_Number', '=', 'acd_student.Register_Number')
        //     ->where([
        //         ['Term_Year_Id', $termYearId],
        //         ['acd_student.Department_Id', $departmentId],
        //         ['acd_student.Entry_Year_Id', $entryYearId],
        //         ['Is_Auto', '!=', true]
        //     ])
        //     ->orderBy('acd_student.Nim', 'asc')
        //     ->get();
        
        return view('set_student_allowed_krs.index', $res);
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

        // Check if allowed krs exist
        $existAllowed = DB::table('acd_student_allowed_krs')
            ->where([
                ['Register_Number', $registerNumber],
                ['Term_Year_Id', $termYearId]
            ])
            ->first();
        if($existAllowed != null){ // if exist update
            $updateAllowedKRS = DB::table('acd_student_allowed_krs')
                ->where('Student_Allowed_Krs_Id', $existAllowed->Student_Allowed_Krs_Id)
                ->update([
                    'Is_Auto' => false,
                    'Modified_By' => Auth::user()->name,
                    'Modified_Date' => Date('Y-m-d H:i:s')
                ]);
        } else { // if don't exist insert
            $insertAllowedKRS = DB::table('acd_student_allowed_krs')
                ->insert([
                    'Register_Number' => $registerNumber,
                    'Term_Year_Id' => $termYearId,
                    'Is_Auto' => false,
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
        $existAllowed = DB::table('acd_student_allowed_krs')
            ->where('Student_Allowed_Krs_Id', $id)
            ->first();
        if($existAllowed != null){ // if exist delete
            $deleteAllowedKRS = DB::table('acd_student_allowed_krs')
                ->where('Student_Allowed_Krs_Id', $existAllowed->Student_Allowed_Krs_Id)
                ->delete();
        }

        return redirect()->route('perizinan-krs.index', $res);
    }
}
