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

class CostSchedDetailController extends Controller
{
  public function __construct()
    {
          $this->middleware('auth');
    }

    // index -----------------------------------------------------------------------------------------\\
    public function index()
    {
    }
    // akhir index ------------------------------------------------------------------------------------


    // resume =---------------------------------------------------------------------------------------------------
    public function Resume()
    {
      $Entry_Year_Id= Input::get('Entry_Year_Id');
      $Department_Id= Input::get('Department_Id');
      $Class_Prog_Id= Input::get('Class_Prog_Id');
      $Entry_Period_Type_Id= Input::get('Entry_Period_Type_Id');

      $Entry_Year = DB::table('mstr_entry_year')->OrderBy('Entry_Year_Id','desc')->get();
      $Department = DB::table('mstr_department')->OrderBy('Department_Name','asc')
                    ->where('Faculty_Id', '!=' ,'null')
                    ->OrderBy('Department_Name','asc')
                    ->get();
      $Class_Prog = DB::table('mstr_class_program')->OrderBy('Class_Program_Name','asc')->get();
      $Entry_Period_Type = DB::table('mstr_entry_period_type')->OrderBy('Entry_Period_Type_Name','asc')->get();

      $data = [];
      $Payment_Orders = DB::table('fnc_cost_sched_detail')
                          ->join('fnc_cost_sched','fnc_cost_sched_detail.Cost_Sched_Id','=','fnc_cost_sched.Cost_Sched_Id')
                          ->where([
                            ['fnc_cost_sched.Entry_Year_Id','=',$Entry_Year_Id],
                            ['fnc_cost_sched.Entry_Period_Type_Id','=',$Entry_Period_Type_Id],
                            ['fnc_cost_sched.Department_Id','=',$Department_Id],
                            ['fnc_cost_sched.Class_Prog_Id','=',$Class_Prog_Id]
                          ])
                          ->OrderBy('fnc_cost_sched.Payment_Order','asc')
                          ->GroupBy('fnc_cost_sched.Payment_Order')
                          ->Select('fnc_cost_sched.Payment_Order as Payment_Order')
                          ->get();
      $Count_Payment_Orders = $Payment_Orders->count();

      $Cost_Items = DB::table('fnc_cost_sched_detail')
                        ->join('fnc_cost_sched','fnc_cost_sched_detail.Cost_Sched_Id','=','fnc_cost_sched.Cost_Sched_Id')
                        ->join('fnc_cost_item','fnc_cost_sched_detail.Cost_Item_id','=','fnc_cost_item.Cost_Item_Id')
                        ->where([
                          ['Entry_Year_Id','=',$Entry_Year_Id],
                          ['Entry_Period_Type_Id','=',$Entry_Period_Type_Id],
                          ['Department_Id','=',$Department_Id],
                          ['Class_Prog_Id','=',$Class_Prog_Id]
                        ])
                        ->OrderBy('fnc_cost_item.Cost_Item_Name','asc')
                        ->GroupBy('fnc_cost_item.Cost_Item_Id')
                        ->Select('fnc_cost_item.Cost_Item_Id as Cost_Item_Id','fnc_cost_item.Cost_Item_Name as Cost_Item_Name')
                        ->get();
      $Count_Cost_Items = $Cost_Items->count();
      if ($Count_Payment_Orders > 0 && $Count_Cost_Items > 0) {
        $i = 0;
        foreach ($Cost_Items as $Cost_Item) {
          // $data[$i] = [];
          $data[$i]['Cost_Item_Name'] = $Cost_Item->Cost_Item_Name;
          $data[$i]['Isi'] = [];
          $ii = 0;

          foreach ($Payment_Orders as $Payment_Order) {
            $fnc_cost_sched_detail = DB::table('fnc_cost_sched_detail')
            ->join('fnc_cost_sched','fnc_cost_sched_detail.Cost_Sched_Id','=','fnc_cost_sched.Cost_Sched_Id')
            ->where([
              ['fnc_cost_sched.Payment_Order','=',$Payment_Order->Payment_Order],
              ['fnc_cost_sched_detail.Cost_Item_id','=',$Cost_Item->Cost_Item_Id]
            ])
            ->first();
            $data[$i]['Isi'][$ii]['Amount'] = $fnc_cost_sched_detail->Amount;
            $data[$i]['Isi'][$ii]['Cost_Sched_Detail_Id'] = $fnc_cost_sched_detail->Cost_Sched_Detail_Id;

            $ii++;
          }

          $i++;
        }
      }

      return view('set_biaya_registrasi_resume/resume')
            ->with('Entry_Year_Id',$Entry_Year_Id)
            ->with('Department_Id',$Department_Id)
            ->with('Class_Prog_Id',$Class_Prog_Id)
            ->with('Entry_Period_Type_Id',$Entry_Period_Type_Id)
            ->with('Entry_Year',$Entry_Year)
            ->with('Department',$Department)
            ->with('Class_Prog',$Class_Prog)
            ->with('Entry_Period_Type',$Entry_Period_Type)
            ->with('data',$data)
            ->with('Payment_Orders',$Payment_Orders)
            ->with('Cost_Items',$Cost_Items)
      ;

    }

    // akhir reumse --------------------------------------------------------------------------------------------


    // edit ----------------------------------------------------------------------------------------------------
    public function Edit($Cost_Sched_Detail_Id)
    {
      $fnc_cost_item = DB::table('fnc_cost_item')->OrderBy('Cost_Item_Name','asc')->get();
      $fnc_cost_sched_detail = DB::table('fnc_cost_sched_detail')->where('Cost_Sched_Detail_Id','=',$Cost_Sched_Detail_Id)->first();
      return view('set_biaya_registrasi_resume/edit')
            ->with('Fnc_Cost_Item',$fnc_cost_item)
            ->with('Fnc_Cost_Sched_Detail',$fnc_cost_sched_detail)
            ;
    }
    //akhir edit -----------------------------------------------------------------------------------------------

    // edit post----------------------------------------------------------------------------------------------------
    public function Edit_Post($Cost_Sched_Detail_Id)
    {
      $Redirect = Input::get('Redirect');
      $Cost_Item_Id = Input::get('Cost_Item_Id');
      $Amount = Input::get('Amount');
      try {
        Alert::success('Berhasil mengubah data', 'Selesai !');
        $update = DB::table('fnc_cost_sched_detail')->where('Cost_Sched_Detail_Id','=',$Cost_Sched_Detail_Id)->update(['Cost_Item_id' => $Cost_Item_Id,'Amount' => $Amount]);
        return Redirect::to($Redirect);
      } catch (\Exception $e) {
        Alert::error('Gagal mengubah data', 'Gagal !');
        return Redirect::to($Redirect);
      }

    }
    //akhir edit post-----------------------------------------------------------------------------------------------
}
