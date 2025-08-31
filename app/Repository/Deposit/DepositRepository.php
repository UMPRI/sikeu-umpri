<?php 

namespace App\Repository\Deposit;

use DB;

class DepositRepository{

    public function GetDeposit($registerNumber){
        
        $installment = DB::table('fnc_student_installment')->where('Register_Number',$registerNumber)->whereRaw('exists (
            SELECT * FROM fnc_used_student_installment as usi WHERE usi.Student_Installment_Id = fnc_student_installment.Student_Installment_Id LIMIT 1
        )')->select(DB::raw('SUM(Amount) as Amount'))->first();

        $usedInstallment = DB::table('fnc_used_student_installment')
            ->join('fnc_student_installment','fnc_used_student_installment.Student_Installment_Id','=','fnc_student_installment.Student_Installment_Id')
            ->where('Register_Number',$registerNumber)
            ->select(DB::raw('SUM(fnc_used_student_installment.Amount) as Amount'))
            ->first();

        return $installment->Amount - $usedInstallment->Amount;
    }
}