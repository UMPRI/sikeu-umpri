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

class CostSchedController extends Controller
{
  public function __construct()
    {
          $this->middleware('auth');
    }

    // index -----------------------------------------------------------------------------------------\\
    public function index()
    {
      $Term_Year_Id = Input::get('Term_Year_Id'); //ambil tahun
      $Entry_Year_Id = Input::get('Entry_Year_Id'); //ambil tahun angkatan

      $Entry_Period_Type_Id = Input::get('Entry_Period_Type_Id');

      if (substr($Term_Year_Id,0,4) != $Entry_Year_Id) {
        $Entry_Period_Type_Id = 1;
      }
      $q_Term_Year = DB::table('mstr_term_year')->OrderBy('Term_Year_Id','desc')->get();
      $q_Entry_Year = DB::table('mstr_entry_year')->OrderBy('Entry_year_Id','desc')->get();
      if ($Term_Year_Id != null && $Entry_Year_Id != null && substr($Term_Year_Id,0,4) == $Entry_Year_Id) {
        $q_Entry_Period_Type = DB::table('mstr_entry_period_type')->get();
      }else{
        $q_Entry_Period_Type = null;
      }
      $q_Data = DB::table('fnc_cost_sched_detail')
                    ->join('fnc_cost_sched','fnc_cost_sched_detail.Cost_Sched_Id','=','fnc_cost_sched.Cost_Sched_Id')
                    ->join('mstr_department','fnc_cost_sched.Department_Id','=','mstr_department.Department_Id')
                    ->join('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=', 'mstr_education_program_type.Education_Prog_Type_Id')
                    ->join('mstr_class_program','fnc_cost_sched.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
                    ->Where([
                              ['Term_Year_Id','=',$Term_Year_Id],
                              ['Entry_Year_Id','=',$Entry_Year_Id],
                              ['Entry_Period_Type_Id','=',$Entry_Period_Type_Id]
                              // ['Payment_Order','=',1]
                            ])
                    ->GroupBy('Cost_Sched_Id')
                    ->select(DB::raw('
                        mstr_department.Department_Name as Department_Name,
                        mstr_class_program.Class_Program_Name as Class_Program_Name,
                        fnc_cost_sched.Start_Date as Start_Date,
                        fnc_cost_sched.End_Date as End_Date,
                        fnc_cost_sched.Cost_Sched_Id as Cost_Sched_Id,
                        SUM(fnc_cost_sched_detail.Amount) as Total_Amount,
                        mstr_education_program_type.Acronym as Acronym
                    '))->get();
      return view('set_biaya_registrasi/index')
            ->with('Term_Year_Id',$Term_Year_Id)
            ->with('Entry_Year_Id',$Entry_Year_Id)
            ->with('Entry_Period_Type_Id',$Entry_Period_Type_Id)
            ->with('q_Term_Year',$q_Term_Year)
            ->with('q_Entry_Year',$q_Entry_Year)
            ->with('q_Entry_Period_Type',$q_Entry_Period_Type)
            ->with('q_Data',$q_Data)
      ;
    }
    // akhir index -------------------------------------------------------------------------------------//


    public function Create()
    {
       $Term_Year_Id = Input::get('Term_Year_Id');
       $Entry_Year_Id = Input::get('Entry_Year_Id');
       $Entry_Period_Type_Id = Input::get('Entry_Period_Type_Id');
       $Department_Id = Input::get('Department_Id');
       $Class_Prog_Id = Input::get('Class_Prog_Id');

       $d_Departments = null;
       $d_ClassProgs = null;
       $Departments = null;
       $ClassProgs = null;

        if ($Department_Id != null && $Class_Prog_Id != null)
        {
          $d_Departments = DB::table('mstr_department')
            ->join('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=', 'mstr_education_program_type.Education_Prog_Type_Id')
            ->Where('Department_Id','=',$Department_Id)
            ->first();

          $d_ClassProgs = DB::table('mstr_class_program')->Where('Class_Prog_Id','=',$Class_Prog_Id)->first();
        }
        else
        {
          $Departments = DB::table('mstr_department')
            ->join('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=', 'mstr_education_program_type.Education_Prog_Type_Id')
            ->where('Faculty_Id', '!=' ,'null')
            ->OrderBy('Department_Name','asc')
            ->get();

          $ClassProgs = DB::table('mstr_class_program')->OrderBy('Class_Program_Name','asc')->get();
        }

        // dd($Departments);
        $Biayas = DB::table('fnc_cost_item')->OrderBy('Cost_Item_Name','asc')->get();
        $Entry_Year = DB::table('mstr_entry_year')->Where('Entry_Year_Id','=',$Entry_Year_Id)->First();
        $Entry_Period_Type = DB::table('mstr_entry_period_type')->Where('Entry_Period_Type_Id','=',$Entry_Period_Type_Id)->First();
        $Term_Year = DB::table('mstr_term_year')->Where('Term_Year_Id','=',$Term_Year_Id)->First();

        return view('set_biaya_registrasi/create')
              ->with('Term_Year_Id',$Term_Year_Id)
              ->with('Entry_Year_Id',$Entry_Year_Id)
              ->with('Entry_Period_Type_Id',$Entry_Period_Type_Id)
              ->with('d_Departments',$d_Departments)
              ->with('d_ClassProgs',$d_ClassProgs)
              ->with('Departments',$Departments)
              ->with('ClassProgs',$ClassProgs)
              ->with('Biayas',$Biayas)
              ->with('Entry_Year',$Entry_Year)
              ->with('Entry_Period_Type',$Entry_Period_Type)
              ->with('Term_Year',$Term_Year)
        ;
    }

    // create post -------------------------------------\----------------------------------------------\\
    public function Create_Post()
    {
      $exp = "";
      $status = 0;
      // $tes = Input::get('tes');
      // $data = echo json_encode(Input::get('data'));;
      $Entry_Year_Id = Input::get('Entry_Year_Id');
      $Entry_Period_Type_Id = Input::get('Entry_Period_Type_Id');
      $Payment_Order = Input::get('Payment_Order');
      $Department_Id = Input::get('Department_Id');
      $Class_Prog_Id = Input::get('Class_Prog_Id');
      $Term_Year_Id = Input::get('Term_Year_Id');
      $Start = Input::get('Start_Date');
      $Start_Date = Date("Y-m-d",strtotime($Start));
      $End = Input::get('End_Date');
      $End_Date = Date("Y-m-d",strtotime($End));
      $BiayaRegistrasiDetails = json_decode(Input::get('BiayaRegistrasiDetails'), true);
      $Created_By = Auth::user()->name;
      $Date_Now = Date("Y-m-d")." 00:00:00";
      $Created_Date = Date('Y-m-d H:i:s',strtotime($Date_Now));
      try
      {
          $Cost_Sched_Id = DB::table('fnc_cost_sched')->insertGetId([
            'Entry_Year_Id'=> $Entry_Year_Id,
            'Department_Id'=> $Department_Id,
            'Class_Prog_Id'=> $Class_Prog_Id,
            'Entry_Period_Type_Id'=> $Entry_Period_Type_Id,
            'Payment_Order'=> $Payment_Order,
            'Term_Year_Id'=> $Term_Year_Id,
            'Start_Date'=> $Start_Date,
            'End_Date'=> $End_Date,
            'Created_By'=> $Created_By,
            'Created_Date'=> $Created_Date
          ]);
          foreach($BiayaRegistrasiDetails as $item) {
            $Cost_Item_id = $item['Cost_Item_id'];
            $Amount = $item['Amount'];
            $Insert_fnc_cost_sched_detail = DB::table('fnc_cost_sched_detail')->insert([
              'Cost_Sched_Id'=>$Cost_Sched_Id,
              'Cost_Item_id'=> $Cost_Item_id,
              'Amount'=> $Amount,
              'Created_By'=> $Created_By,
              'Created_Date'=> $Created_Date
            ]);
          }

          $Action = "Create";

          $insert_log = DB::table('log_cost_sched')->insert([
            'Department_Id' => $Department_Id,
            'Class_Prog_Id' => $Class_Prog_Id,
            'Entry_Year_Id' => $Entry_Year_Id,
            'Entry_Period_Type_Id' => $Entry_Period_Type_Id,
            'Payment_Order' => $Payment_Order,
            'Term_Year_Id' => $Term_Year_Id,
            'Created_By' => $Created_By,
            'Created_Date' => $Created_Date,
            'Action' => $Action
          ]);
          // DB::select('CALL usp_SetSB_ByTY_EY_EPT_D_CP_PO(?,?,?,?,?,?,?)',[$Term_Year_Id, $Entry_Year_Id, $Entry_Period_Type_Id, $Department_Id, $Class_Prog_Id, $Payment_Order, $Created_By]);
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

    // set student Bill -------------------------------------\----------------------------------------------\\
    public function Set_Student_Bill()
    {
      $Term_Year_Id = Input::get('Term_Year_Id');
      $Entry_Year_Id = Input::get('Entry_Year_Id');
      $Entry_Period_Type_Id = Input::get('Entry_Period_Type_Id');
      $redirect = Input::get('current_url');
      try
      {
        DB::select('CALL usp_SetSB_ByTY_EY_EPT(?,?,?)',[$Term_Year_Id, $Entry_Year_Id, $Entry_Period_Type_Id]);
        Alert::success('Berhasil Menghitung Tagihan', 'Selesai !');
        return Redirect::to($redirect);
      }
      catch (\Exception $e)
      {
        Alert::error($e->getMessage(), 'Gagal !');
        return Redirect::to($redirect);
      }

      //usp_SetSB_ByTY_EY_EPT
    }
    // akhir set student bill -------------------------------------------------------------------------------//



    // delete ------------------------------------------------------------------------------------------\\
    public function delete($Cost_Sched_Id){
      $data = DB::table('fnc_cost_sched')
                  ->where('Cost_Sched_Id',$Cost_Sched_Id)
                  ->first();

      if($data == null){
        return redirect('set_biaya_registrasi');
      }

      $Department_Id = $data->Department_Id;
      $Class_Prog_Id = $data->Class_Prog_Id;
      $Entry_Year_Id = $data->Entry_Year_Id;
      $Entry_Period_Type_Id = $data->Entry_Period_Type_Id;
      $Payment_Order = $data->Payment_Order;
      $Term_Year_Id = $data->Term_Year_Id;
      $Created_By = Auth::user()->name;
      $Created_Date = Date("Y-m-d H:i:s");
      $Action = "Delete";

      try{
        DB::table('fnc_cost_sched_detail')->where('Cost_Sched_Id',$Cost_Sched_Id)->delete();
        
        $delete = DB::table('fnc_cost_sched')
                  ->where('Cost_Sched_Id',$Cost_Sched_Id)
                  ->delete();

        // if($delete){
          $insert_log = DB::table('log_cost_sched')->insert([
            'Department_Id' => $Department_Id,
            'Class_Prog_Id' => $Class_Prog_Id,
            'Entry_Year_Id' => $Entry_Year_Id,
            'Entry_Period_Type_Id' => $Entry_Period_Type_Id,
            'Payment_Order' => $Payment_Order,
            'Term_Year_Id' => $Term_Year_Id,
            'Created_By' => $Created_By,
            'Created_Date' => $Created_Date,
            'Action' => $Action
          ]);
        // }
      }catch(Exception $e){

      }
      
      return redirect('set_biaya_registrasi?Term_Year_Id='.$Term_Year_Id.'&Entry_Year_Id='.$Entry_Year_Id.'&Entry_Period_Type_Id='.$Entry_Period_Type_Id);
    }
    // akhir delete ------------------------------------------------------------------------------------//



    // Copy Data ---------------------------------------------------------------------------------------\\
    public function Copy_Data()
    {
      $Term_Year_Id = Input::get('Term_Year_Id');
      $Entry_Year_Id = Input::get('Entry_Year_Id');
      $Entry_Period_Type_Id = Input::get('Entry_Period_Type_Id');
      $Term_Year = DB::table('mstr_term_year')->Where('Term_Year_Id','=', $Term_Year_Id)->first();
      $Entry_Year = DB::table('mstr_entry_year')->Where('Entry_Year_Id','=', $Entry_Year_Id)->first();
      $Entry_Period_Type = DB::table('mstr_entry_period_type')->Where('Entry_Period_Type_Id','=', $Entry_Period_Type_Id)->first();
      $Entry_Period_Type_Id_Source = DB::table('mstr_entry_period_type')->get();
      return view('set_biaya_registrasi/copy_data')
            ->with('Term_Year_Id',$Term_Year_Id)
            ->with('Entry_Year_Id',$Entry_Year_Id)
            ->with('Entry_Period_Type_Id',$Entry_Period_Type_Id)
            ->with('Entry_Period_Type_Id_Source',$Entry_Period_Type_Id_Source)
            ->with('Entry_Year',$Entry_Year)
            ->with('Entry_Period_Type',$Entry_Period_Type)
            ->with('Term_Year',$Term_Year)
      ;
    }
    //akhir Copy Data ==================================================================================//




    // copy data post --------------------------------------------------------------------------------------------
    public function Copy_Data_Post()
    {
        $Action = "Copy Data";

        $Term_Year_Id = Input::get('Term_Year_Id');
        $Entry_Year_Id = Input::get('Entry_Year_Id');
        $Entry_Period_Type_Id_Dest = Input::get('Entry_Period_Type_Id_Dest');
        $Entry_Period_Type_Id_Source = Input::get('Entry_Period_Type_Id_Source');
        $Created_By = Auth::user()->name;
        $dataSource = DB::table('fnc_cost_sched')->Where([['Term_Year_Id','=',$Term_Year_Id],['Entry_Year_Id','=',$Entry_Year_Id],['Entry_Period_Type_Id','=',$Entry_Period_Type_Id_Source]])->count();
        if ($dataSource > 0)
        {
          try {
            DB::select('CALL usp_CopyCostSched_ByTY_ByEY_ByEP(?,?,?,?,?)',[$Term_Year_Id, $Entry_Year_Id, $Entry_Period_Type_Id_Source, $Entry_Period_Type_Id_Dest, $Created_By]);
            Alert::success('Berhasil menyalin data', 'Selesai !');

            $insert_log = DB::table('log_cost_sched')->insert([
              'Department_Id' => null,
              'Class_Prog_Id' => null,
              'Entry_Year_Id' => $Entry_Year_Id,
              'Entry_Period_Type_Id' => $Entry_Period_Type_Id_Dest,
              'Payment_Order' =>  null,
              'Term_Year_Id' => $Term_Year_Id,
              'Created_By' => $Created_By,
              'Created_Date' => Date('Y-m-d H:i:s'),
              'Action' => $Action
            ]);
            
            return redirect('set_biaya_registrasi?Term_Year_Id='.$Term_Year_Id.'&Entry_Year_Id='.$Entry_Year_Id.'&Entry_Period_Type_Id='.$Entry_Period_Type_Id_Dest);
          } catch (\Exception $e) {
            $insert_log = DB::table('log_cost_sched')->insert([
              'Department_Id' => null,
              'Class_Prog_Id' => null,
              'Entry_Year_Id' => $Entry_Year_Id,
              'Entry_Period_Type_Id' => $Entry_Period_Type_Id_Dest,
              'Payment_Order' =>  null,
              'Term_Year_Id' => $Term_Year_Id,
              'Created_By' => $Created_By,
              'Created_Date' => Date('Y-m-d H:i:s'),
              'Action' => $Action." + Gagal"
            ]);

            Alert::error('Gagal menyalin data , pesan error : '.$e->getMessage(), 'Gagal !');
            return redirect('set_biaya_registrasi/copy_data?Term_Year_Id='.$Term_Year_Id.'&Entry_Year_Id='.$Entry_Year_Id.'&Entry_Period_Type_Id='.$Entry_Period_Type_Id_Dest);
          }
        }
        Alert::error('Sumber data tidak dapat ditemukan', 'Gagal !');
        return redirect('set_biaya_registrasi/copy_data?Term_Year_Id='.$Term_Year_Id.'&Entry_Year_Id='.$Entry_Year_Id.'&Entry_Period_Type_Id='.$Entry_Period_Type_Id_Dest);
    }
    // akhir copy data post ========================================================================================





    //edit
    public function Edit($Cost_Sched_Id)
    {
      $fnc_cost_sched = DB::table('fnc_cost_sched')
        ->leftjoin('mstr_department','fnc_cost_sched.Department_Id','=','mstr_department.Department_Id')
        ->join('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=', 'mstr_education_program_type.Education_Prog_Type_Id')
        ->leftjoin('mstr_class_program','fnc_cost_sched.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
        ->leftjoin('mstr_entry_year','fnc_cost_sched.Entry_Year_Id','=','mstr_entry_year.Entry_Year_Id')
        ->leftjoin('mstr_entry_period_type','fnc_cost_sched.Entry_Period_Type_Id','=','mstr_entry_period_type.Entry_Period_Type_Id')
        ->leftjoin('mstr_term_year','fnc_cost_sched.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
        ->where('Cost_Sched_Id','=',$Cost_Sched_Id)
        ->select(DB::raw('
            mstr_term_year.Term_Year_Name as Term_Year_Name,
            mstr_term_year.Term_Year_Id as Term_Year_Id,
            mstr_entry_year.Entry_Year_Id as Entry_Year_Id,
            mstr_entry_year.Entry_Year_Id as Entry_Year_Id,
            mstr_entry_period_type.Entry_Period_Type_Name as Entry_Period_Type_Name,
            mstr_entry_period_type.Entry_Period_Type_Id as Entry_Period_Type_Id,
            mstr_department.Department_Name as Department_Name,
            mstr_department.Department_Id as Department_Id,
            mstr_class_program.Class_Program_Name as Class_Program_Name,
            mstr_class_program.Class_Prog_Id as Class_Prog_Id,
            fnc_cost_sched.Start_Date as Start_Date,
            fnc_cost_sched.End_Date as End_Date,
            fnc_cost_sched.Cost_Sched_Id as Cost_Sched_Id,
            fnc_cost_sched.Payment_Order as Payment_Order,
            mstr_education_program_type.Acronym as Acronym
        '))
        ->first();

      $fnc_cost_sched_details = DB::table('fnc_cost_sched_detail')
        ->join('fnc_cost_item','fnc_cost_sched_detail.Cost_Item_id','=','fnc_cost_item.Cost_Item_Id')
        ->where('Cost_Sched_Id','=',$Cost_Sched_Id)
        ->select(DB::raw('
            fnc_cost_sched_detail.Cost_Sched_Id as Cost_Sched_Id,
            fnc_cost_sched_detail.Cost_Item_id as Cost_Item_id,
            fnc_cost_item.Cost_Item_Name as Cost_Item_Name,
            fnc_cost_sched_detail.Amount as Amount,
            fnc_cost_sched_detail.Amount as SAmount,
            fnc_cost_sched_detail.Created_By as Created_By,
            fnc_cost_sched_detail.Created_Date as Created_Date,
            fnc_cost_sched_detail.Modified_By as Modified_By,
            fnc_cost_sched_detail.Modified_Date as Modified_Date
        '))
        ->get();
      $Biayas = DB::table('fnc_cost_item')->OrderBy('Cost_Item_Name','asc')->get();
      // dd($fnc_cost_sched);
      return view('set_biaya_registrasi/edit')
              ->with('fnc_cost_sched',$fnc_cost_sched)
              ->with('fnc_cost_sched_details',$fnc_cost_sched_details)
              ->with('Biayas',$Biayas)
        ;
    }
    //akhir edit


    //edit post

    public function Edit_Post($Cost_Sched_Id)
    {
      $exp = "";
      $status = 0;
      // $tes = Input::get('tes');
      // $data = echo json_encode(Input::get('data'));;
      $Entry_Year_Id = Input::get('Entry_Year_Id');
      $Entry_Period_Type_Id = Input::get('Entry_Period_Type_Id');

      $Payment_Order = Input::get('Payment_Order');
      $Department_Id = Input::get('Department_Id');
      $Class_Prog_Id = Input::get('Class_Prog_Id');
      $Term_Year_Id = Input::get('Term_Year_Id');
      $Start = Input::get('Start_Date');
      $Start_Date = Date("Y-m-d",strtotime($Start));
      $End = Input::get('End_Date');
      $End_Date = Date("Y-m-d",strtotime($End));
      $BiayaRegistrasiDetails = json_decode(Input::get('BiayaRegistrasiDetails'), true);;
      $Created_By = Auth::user()->name;
      $Date_Now = Date("Y-m-d")." 00:00:00";
      $Created_Date = Date('Y-m-d H:i:s',strtotime($Date_Now));
      try
      {
          $delete_detail = DB::table('fnc_cost_sched_detail')->where('Cost_Sched_Id','=',$Cost_Sched_Id)->delete();
          foreach($BiayaRegistrasiDetails as $item) {
            $Cost_Item_id = $item['Cost_Item_id'];
            $Amount = $item['Amount'];
            $Insert_fnc_cost_sched_detail = DB::table('fnc_cost_sched_detail')->insert([
              'Cost_Sched_Id'=>$Cost_Sched_Id,
              'Cost_Item_id'=> $Cost_Item_id,
              'Amount'=> $Amount,
              'Created_By'=> $Created_By,
              'Created_Date'=> $Created_Date
            ]);
          }
          $Cost_Sched = DB::table('fnc_cost_sched')
            ->where('Cost_Sched_Id','=',$Cost_Sched_Id)
            ->update([
                'Start_Date'=> $Start_Date,
                'End_Date'=> $End_Date,
                'Modified_By'=> $Created_By,
                'Modified_Date'=> $Created_Date
              ]);

          $Action = "Update";

          $insert_log = DB::table('log_cost_sched')->insert([
            'Department_Id' => $Department_Id,
            'Class_Prog_Id' => $Class_Prog_Id,
            'Entry_Year_Id' => $Entry_Year_Id,
            'Entry_Period_Type_Id' => $Entry_Period_Type_Id,
            'Payment_Order' => $Payment_Order,
            'Term_Year_Id' => $Term_Year_Id,
            'Created_By' => $Created_By,
            'Created_Date' => $Created_Date,
            'Action' => $Action
          ]);
          // DB::select('CALL usp_SetSB_ByTY_EY_EPT_D_CP_PO(?,?,?,?,?,?,?)',[$Term_Year_Id, $Entry_Year_Id, $Entry_Period_Type_Id, $Department_Id, $Class_Prog_Id, $Payment_Order, $Created_By]);
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

    //akhir edit post


    // fungsi ini hanya digunakan untuk mengembalikan data setting biaya registrasi yang hilang, data diambil dari data tagihan mahasiswa yang masih ada di db
    public function ReInsert(){
      // $startEntryYearId = 2020;
      // $endEntryYearId = 2023;
      $entryYearIds = array(2020,2021,2023);
      $termYearId = 20241;

      $studentSamples = DB::table('acd_student')->select(DB::raw('max(Register_Number) as Register_Number'))
        // ->where([
        //   ['Entry_Year_Id', '>=', $startEntryYearId],
        //   ['Entry_Year_Id', '<=', $endEntryYearId]
        // ])
        ->whereIn('Entry_Year_Id', $entryYearIds)
        ->whereRaw('Student_Id NOT IN (SELECT Student_Id FROM simak_umpri.acd_student_krs WHERE Term_Year_Id = '.$termYearId.')')
        ->groupBy('Department_Id')
        ->groupBy('Entry_Year_Id')
        ->groupBy('Class_Prog_Id')
        ->groupBy('Entry_Period_Type_Id')
        ->get();
      
      // convert stdClass to arr
      $arrayStudentSamples = [];
      $i=0;
      foreach($studentSamples as $studentSample){
        $arrayStudentSamples[$i] = $studentSample->Register_Number;
        $i++;
      }
      
      // get bills for samples
      $studentBillSamples = DB::table('fnc_student_bill')
          ->where([
            ['Term_Year_Id', $termYearId],
            ['Created_By','usp_SetStudentBill_BTYI_EY_EPT']
          ])
          ->whereIn('Register_Number', $arrayStudentSamples)
          ->get();
      try{
        foreach($studentBillSamples as $studentBillSample){
          $student = DB::table('acd_student')->where('Register_Number', $studentBillSample->Register_Number)->first();
  
          $costSchedId = DB::table('fnc_cost_sched')->insertGetId([
            'Department_Id' => $student->Department_Id, 
            'Class_Prog_Id' => $student->Class_Prog_Id,
            'Entry_Year_Id' => $student->Entry_Year_Id,
            'Entry_Period_Type_Id' => $student->Entry_Period_Type_Id,
            'Payment_Order' => $studentBillSample->Payment_Order,
            'Term_Year_Id' => $studentBillSample->Term_Year_Id,
            'Start_Date' => $studentBillSample->Start_Date,
            'End_Date' => $studentBillSample->End_Date,
            'Created_By' => 're-insert',
            'Created_Date' => Date('Y-m-d H:i:s')
          ]);
  
          $billDetails = DB::table('fnc_student_bill_detail')->where('Student_Bill_Id', $studentBillSample->Student_Bill_Id)->get();
          foreach($billDetails as $billDetail){
            DB::table('fnc_cost_sched_detail')->insert([
              'Cost_Sched_Id' => $costSchedId,
              'Cost_Item_id' => $billDetail->Cost_Item_id,
              'Amount' => $billDetail->Amount,
              'Created_By' => 're-insert',
              'Created_Date' => Date('Y-m-d H:i:s')
            ]);
          }
        }

        $res = "OK";
      }catch(\Exception $e){
        $res = $e->getMessage();
      }
      

      return $res;
    }

    // fungsi ini hanya digunakan untuk mengembalikan data student bill yang hilang, data diambil dari data pembayaran mahasiswa yang masih ada di db
    public function ReInsertBill(){
      $entryYearIds = array(2020);
      $termYearId = 20241;

      // insert into fnc_student_bill(Register_NUmber,Term_Year_Id,Payment_Order,Start_Date,End_Date,Created_By,Created_Date)
			// values (v_RegisterNumber,p_TermYearId,v_PaymentOrder,v_StartDate,v_EndDate,'usp_SetStudentBill_BTYI_EY_EPT',now());

      // $End_Date = date('Y-m-d H:i:s');
      $Created_By = 're-insert';
      $Created_Date = date('Y-m-d H:i:s');

      $paymentSamples = DB::table('acd_student')
      ->select('acd_student.Register_Number', 'fnc_student_payment.Installment_Order', 'fnc_reff_payment.Payment_Date')
      ->join('fnc_reff_payment', 'acd_student.Register_Number', '=', 'fnc_reff_payment.Register_Number')
      ->join('fnc_student_payment', 'fnc_reff_payment.Reff_Payment_Id', '=', 'fnc_reff_payment.Reff_Payment_Id')
      ->whereIn('acd_student.Entry_Year_Id', $entryYearIds)
      ->where('fnc_student_payment.Term_Year_Bill_Id', $termYearId)
      ->groupBy('acd_student.Register_Number', 'fnc_student_payment.Installment_Order', 'fnc_reff_payment.Payment_Date', 'fnc_student_payment.Term_Year_Bill_Id')
      ->get();
      dd($paymentSamples);

      try{
        foreach($paymentSamples as $paymentSample){
          
          $checkBill = DB::table('fnc_student_bill')->where([
            ['Register_Number', $paymentSample->Register_Number],
            ['Payment_Order', $paymentSample->Register_Number],
            ['Term_Year_Id', $paymentSample->Term_Year_Bill_Id]
          ])->count();

          if(!($checkBill > 0)){
  
            $studentBillId = DB::table('fnc_student_bill')->insertGetId([
              'Register_Number' => $paymentSample->Register_Number,
              'Term_Year_Id' => $paymentSample->Term_Year_Bill_Id,
              'Payment_Order' => $paymentSample->Installment_Order,
              'Start_Date' => date('Y-m-d 00:00:00', strtotime('-1 day',strtotime($paymentSample->Payment_Date))),
              'End_Date' => $Created_Date,
              'Created_By' => $Created_By,
              'Created_Date' => $Created_Date
            ]);

            $paymentDetailSamples = DB::table('acd_student')
            ->select('fnc_student_payment.Cost_Item_Id', 'Payment_Amount')
            ->join('fnc_reff_payment', 'acd_student.Register_Number', '=', 'fnc_reff_payment.Register_Number')
            ->join('fnc_student_payment', 'fnc_reff_payment.Reff_Payment_Id', '=', 'fnc_reff_payment.Reff_Payment_Id')
            ->where([
              ['acd_student.Register_Number', $paymentSample->Register_Number ],
              ['fnc_student_payment.Installment_Order', $paymentSample->Installment_Order ],
              ['fnc_reff_payment.Payment_Date', $paymentSample->Payment_Date ],
              ['fnc_student_payment.Term_Year_Bill_Id', $paymentSample->Term_Year_Bill_Id ],
            ])
            ->get();

            foreach($paymentDetailSamples as $paymentDetailSample){

              // insert into fnc_student_bill_detail (Student_Bill_id,Cost_Item_Id,Amount,Created_By,Created_Date)
              DB::table('fnc_student_bill_detail')->insert([
                'Student_Bill_Id' => $studentBillId,
                'Cost_Item_Id' => $paymentDetailSample->Cost_Item_Id,
                'Amount' => $paymentDetailSample->Amount,
                'Created_By' => $Created_By,
                'Created_Date' => $Created_Date
              ]);

            }

          }
        }

        $res = "OK";
      }catch(\Exception $e){
        $res = $e->getMessage();
      }
      

      return $res;
    }
}
