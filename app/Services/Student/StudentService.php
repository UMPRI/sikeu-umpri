<?php 

namespace App\Services\Student;

use DB;

class StudentService{
    public function Authentication($registerNumber, $password){

        $student = DB::table('acd_student')->where([
            ['Register_Number', $registerNumber],
            // ['Student_Password', \md5($password)]
            // ['Student_Password', $password]
        ])->first();
        //  dd(hash('sha256', $student->Student_Password));

        if($student == null){
            return false;
        }

        return $password == hash('sha256', $student->Student_Password) ? true : false;
    }

    public function GetById($id){

        $student = DB::table('acd_student')->where([
            ['Student_Id', $id],
            // ['Student_Password', \md5($password)]
            // ['Student_Password', $password]
        ])->first();

        return $student;
    }

    public function GetByRegNum($registerNumber){

        $student = DB::table('acd_student')->where([
            ['Register_Number', $registerNumber]
        ])->first();

        return $student;
    }

    public function GetNonKRSBills($registerNumber){
        $res = [];

        $nonKRSTermYearBillIds = [];
        $tagihans = DB::select('CALL usp_GetStudentBill(?,?,?)',[$registerNumber,"",""]);
        
        $i = 0;
        $iy= 0;
        
        foreach ($tagihans as $tagihan) {
            $res[$i]['Cost_Item_Id'] = $tagihan->Cost_Item_Id;
            $res[$i]['Cost_Item_Name'] = $tagihan->Cost_Item_Name;
            $res[$i]['Payment_Order'] = $tagihan->Payment_Order;
            $res[$i]['Amount'] = $tagihan->Amount;
            $res[$i]['Start_Date'] = $tagihan->Start_Date;
            $res[$i]['End_Date'] = $tagihan->End_Date;
            $res[$i]['Term_Year_Id'] = $tagihan->Term_Year_Bill_id;

            $termYear = DB::table('mstr_term_year')->where('Term_Year_Id', $tagihan->Term_Year_Bill_id)->first();
            $res[$i]['Term_Year_Name'] = $termYear != null ? $termYear->Term_Year_Name : null;
            $i++;

            if(!in_array($tagihan->Term_Year_Bill_id, $nonKRSTermYearBillIds)){
                $nonKRSTermYearBillIds[$iy] = $tagihan->Term_Year_Bill_id;
                $iy++;
            }
          
        }

        $cicilans = DB::table('fnc_student_installment')
        ->where('Register_Number', $registerNumber)
        ->whereIn('fnc_student_installment.Term_Year_Id', $nonKRSTermYearBillIds)
        ->join('mstr_term_year', 'fnc_student_installment.Term_Year_Id', '=', 'mstr_term_year.Term_Year_Id')
        ->groupBy('Register_Number', 'fnc_student_installment.Term_Year_Id', 'mstr_term_year.Term_Year_Name')
        ->select(DB::raw('
            NULL as Cost_Item_Id, 
            "Cicilan Pembayaran Open" as Cost_Item_Name, 
            NULL as Payment_Order,
            SUM(Amount) as Amount,
            NULL as Start_Date,
            NULL as End_Date,
            fnc_student_installment.Term_Year_Id,
            mstr_term_year.Term_Year_Name
        '))
        ->get();

        foreach ($cicilans as $cicilan) {
            $res[$i]['Cost_Item_Id'] = $cicilan->Cost_Item_Id;
            $res[$i]['Cost_Item_Name'] = $cicilan->Cost_Item_Name;
            $res[$i]['Payment_Order'] = $cicilan->Payment_Order;
            $res[$i]['Amount'] = $cicilan->Amount * (0-1);
            $res[$i]['Start_Date'] = $cicilan->Start_Date;
            $res[$i]['End_Date'] = $cicilan->End_Date;
            $res[$i]['Term_Year_Id'] = $cicilan->Term_Year_Id;
            $res[$i]['Term_Year_Name'] = $cicilan->Term_Year_Name;
            $i++;
        }
        // dd($tagihans);

        return $res;
    }

    public function GetKRSBills($registerNumber){
        $res = [];

        $i = 0;

        $tagihanKRS = DB::select('CALL usp_GetStudentBill_For_KRS(?,?,?)',[$registerNumber,"",""]);
        
        foreach ($tagihanKRS as $key) {
            $res[$i]['Cost_Item_Id'] = $key->Cost_Item_Id;
            $res[$i]['Cost_Item_Name'] = $key->Cost_Item_Name;
            $res[$i]['Payment_Order'] = 1;
            $res[$i]['Course_Id'] = $key->Course_Id;
            $res[$i]['Course_Name'] = $key->Course_Name;
            $res[$i]['Amount'] = $key->Amount;
            $res[$i]['Start_Date'] = $key->Start_Date;
            $res[$i]['End_Date'] = $key->End_Date;
            $res[$i]['Term_Year_Bill_Id'] = $key->Term_Year_Bill_id;
            $res[$i]['SKS'] = $key->SKS;
            $res[$i]['perSKS'] = $key->perSKS;
            $i++;
        }

        return $res;
    }
}

?>