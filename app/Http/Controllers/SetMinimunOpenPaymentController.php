<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
use Auth;

class SetMinimunOpenPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Index(Request $request){
        $termYearId = $request->termYearId;
        $entryYearId = $request->entryYearId;

        $res = [];
        $res['termYearId'] = $termYearId;
        $res['entryYearId'] = $entryYearId;
        $res['termYears'] = DB::table('mstr_term_year')->orderBy('Term_Year_Id', 'desc')->get();
        $res['entryYears'] = DB::table('mstr_entry_year')->orderBy('Entry_Year_Id', 'desc')->get();
        $res['departments'] = DB::table('mstr_department')->join('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=', 'mstr_education_program_type.Education_Prog_Type_Id')->orderBy('Department_Name', 'asc')->get();

        $res['setMinimums'] = DB::table('fnc_minimum_installment')
            ->join('mstr_department', 'fnc_minimum_installment.Department_Id', '=', 'mstr_department.Department_Id')
            ->join('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=', 'mstr_education_program_type.Education_Prog_Type_Id')
            ->where([
                ['Term_Year_Id', $termYearId],
                ['Entry_Year_Id', $entryYearId]
            ])
            ->orderBy('mstr_department.Department_Name', 'asc')
            ->get();
        
        return view('set_minimum_open_payment.index', $res);
    }

    public function Create(Request $request){
        $termYearId = $request->termYearId;
        $entryYearId = $request->entryYearId;
        $departmentId = $request->departmentId;
        $amount = $request->amount;

        // dd($departmentId);

        $Created_By = Auth::user()->name;
        $Date_Now = Date("Y-m-d")." 00:00:00";
        $Created_Date = Date('Y-m-d H:i:s',strtotime($Date_Now));

        try{
            $insert = DB::table('fnc_minimum_installment')
                ->insert([
                    'Department_Id' => $departmentId,
                    'Term_year_Id' => $termYearId,
                    'Entry_Year_Id' => $entryYearId,
                    'Amount' => $amount,
                    'Created_By' => $Created_By,
                    'Created_Date' => $Created_Date,
                ]);

            return redirect()->route('set-minimum.index', ['termYearId' => $termYearId, 'entryYearId' => $entryYearId]);
        }catch(\Exception $e){
            
            return redirect()->route('set-minimum.index', ['termYearId' => $termYearId, 'entryYearId' => $entryYearId, 'errorMessage' => $e->getMessage()]);
        }
    }

    public function Update(Request $request){
        $id = $request->id;
        $departmentId = $request->departmentId;
        $amount = $request->amount;
        $termYearId = $request->termYearId;
        $entryYearId = $request->entryYearId;

        $Modified_By = Auth::user()->name;
        $Date_Now = Date("Y-m-d")." 00:00:00";
        $Modified_Date = Date('Y-m-d H:i:s',strtotime($Date_Now));

        try{
            $update = DB::table('fnc_minimum_installment')
                ->where('Minimum_Installment_Id', $id)
                ->update([
                    'Department_Id' => $departmentId,
                    'Amount' => $amount,
                    'Modified_By' => $Modified_By,
                    'Modified_Date' => $Modified_Date,
                ]);

            return redirect()->route('set-minimum.index', ['termYearId' => $termYearId, 'entryYearId' => $entryYearId]);
        }catch(\Exception $e){
            return redirect()->route('set-minimum.index', ['termYearId' => $termYearId, 'entryYearId' => $entryYearId, 'errorMessage' => $e->getMessage()]);
        }
    }

    public function Delete(Request $request){
        $id = $request->id;
        $termYearId = $request->termYearId;
        $entryYearId = $request->entryYearId;

        try{
            $delete = DB::table('fnc_minimum_installment')
                ->where('Minimum_Installment_Id', $id)
                ->delete();

            return redirect()->route('set-minimum.index', ['termYearId' => $termYearId, 'entryYearId' => $entryYearId]);
        }catch(\Exception $e){
            return redirect()->route('set-minimum.index', ['termYearId' => $termYearId, 'entryYearId' => $entryYearId, 'errorMessage' => $e->getMessage()]);
        }
    }
}
