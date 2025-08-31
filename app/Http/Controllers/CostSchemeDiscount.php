<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Alert;
use Auth;

class CostSchemeDiscount extends Controller
{
    public function index(Request $request)
    {   
        // $rowpage = $request->rowpage;
        // $search = $request->search;

        // if ($rowpage == null) {
        //     $rowpage = 10;
        // }

        // if ($search == null) {
        //     $q_cost_scheme_discount = DB::table('fnc_cost_scheme_discount')
        //             // ->join('fnc_cost_scheme_discount_detail','fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','=','fnc_cost_scheme_discount_detail.Cost_Scheme_Discount_Id')
        //             // ->join('fnc_cost_scheme_discount_member','fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','=','fnc_cost_scheme_discount_member.Cost_Scheme_Discount_Id')
        //             ->paginate($rowpage);
        // }else {
        //     $q_cost_scheme_discount = DB::table('fnc_cost_scheme_discount')
        //             ->where('Discount_Code','LIKE','%'.$search.'%')
        //             // ->join('fnc_cost_scheme_discount_detail','fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','=','fnc_cost_scheme_discount_detail.Cost_Scheme_Discount_Id')
        //             // ->join('fnc_cost_scheme_discount_member','fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','=','fnc_cost_scheme_discount_member.Cost_Scheme_Discount_Member_Id')
        //             ->paginate($rowpage);
        // }
        
        // // dd($q_cost_scheme_discount);
        return view('diskon_biaya/index');
                // ->with('rowpage', $rowpage)
                // ->with('search', $search)
                // ->with('cost_scheme_discount', $q_cost_scheme_discount);
        // return view('diskon_biaya/index');
    }
    

    public function getDiscount(Request $request)
    {
        // $search = $request->search;
        $q_cost_scheme_discount = DB::table('fnc_cost_scheme_discount')
                    // ->join('fnc_cost_scheme_discount_detail','fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','=','fnc_cost_scheme_discount_detail.Cost_Scheme_Discount_Id')
                    // ->join('fnc_cost_scheme_discount_member','fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','=','fnc_cost_scheme_discount_member.Cost_Scheme_Discount_Id')
                    ->get();

        return response()->json($q_cost_scheme_discount);
    }

    public function memberDiscount(Request $request)
    {
        $q_cost_scheme_discount_member = DB::table('fnc_cost_scheme_discount')
                    // ->join('fnc_cost_scheme_discount_detail','fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','=','fnc_cost_scheme_discount_detail.Cost_Scheme_Discount_Id')
                    ->where('fnc_cost_scheme_discount.Cost_Scheme_Discount_Id', $request->Cost_Scheme_Discount_Id)
                    ->join('fnc_cost_scheme_discount_member','fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','=','fnc_cost_scheme_discount_member.Cost_Scheme_Discount_Id')
                    ->join('acd_student','fnc_cost_scheme_discount_member.Register_Number','acd_student.Register_Number')
                    ->get();
        return response()->json($q_cost_scheme_discount_member);            
    }

    public function detailDiscount(Request $request)
    {
        $q_cost_scheme_discount_detail = DB::table('fnc_cost_scheme_discount')
                    ->where('fnc_cost_scheme_discount.Cost_Scheme_Discount_Id', $request->Cost_Scheme_Discount_Id)
                    ->join('fnc_cost_scheme_discount_detail','fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','=','fnc_cost_scheme_discount_detail.Cost_Scheme_Discount_Id')
                    ->join('fnc_cost_item','fnc_cost_scheme_discount_detail.Cost_Item_Id','fnc_cost_item.Cost_Item_Id')
                    ->get();
        return response()->json($q_cost_scheme_discount_detail);   
    }

    public function addDiscount(Request $request)
    {
        $Discount_Code = $request->kodediskon;
        $Cost_Scheme_Discount_Name = $request->namadiskon;
        $Created_By = Auth::user()->name;
        $Date_Now = Date("Y-m-d")." 00:00:00";
        $Created_Date = Date('Y-m-d H:i:s',strtotime($Date_Now));

        $Insert_Cost_Scheme_Discount = DB::table('fnc_cost_scheme_discount')
                                    ->insert([
                                        'Discount_Code' => $Discount_Code,
                                        'Cost_Scheme_Discount_Name' => $Cost_Scheme_Discount_Name,
                                        'Created_By' => $Created_By,
                                        'Created_Date' => $Created_Date 
                                    ]); 

        return json_encode($Insert_Cost_Scheme_Discount);

        // $Term_Total = $request->Term_Total;
        // $Item_Biaya = json_decode($request->Item_Biaya, true);
        // $Mahasiswa = json_decode($request->Mahasiswa, true);
        

    }

    public function getStudent(Request $request)
    {
        $q_acd_student = DB::table('acd_student')
                   ->get();
        // return response()->json($q_acd_student);
    }

    public function get (Type $var = null)
    {
        # code...
    }

    public function create()
    {
        $q_costItem = DB::table('fnc_cost_item')
                    ->orderBy('fnc_cost_item.Cost_Item_Code', 'asc')
                    ->get();
        

        return view('diskon_biaya/create')
                ->with('d_costItem', $q_costItem)
                ;

    }


    public function create_post(Request $request)
    {
        $exp = "";
        $status = 0;

        $Discount_Code = $request->Discount_Code;
        $Cost_Scheme_Discount_Name = $request->Cost_Scheme_Discount_Name;
        $Term_Total = $request->Term_Total;
        $Item_Biaya = json_decode($request->Item_Biaya, true);
        $Mahasiswa = json_decode($request->Mahasiswa, true);
        $Created_By = Auth::user()->name;
        $Date_Now = Date("Y-m-d")." 00:00:00";
        $Created_Date = Date('Y-m-d H:i:s',strtotime($Date_Now));

        dd($Mahasiswa);


        try {
            // step one insert data to fnc_cost_scheme_discount--------------------------
            $Cost_Scheme_Discount_Id = DB::table('fnc_cost_scheme_discount')
                                    ->insertGetId([
                                        'Discount_Code' => $Discount_Code,
                                        'Cost_Scheme_Discount_Name' => $Cost_Scheme_Discount_Name,
                                        'Created_By' => $Created_By,
                                        'Created_Date' => $Created_Date 
                                    ]); 
            
            // step two inset data to fnc_cost_scheme_discount_detail-------------------------
            foreach ($Item_Biaya as $d_Item_Biaya) {
                $Cost_Item_Id = $d_Item_Biaya['Cost_Item_id'];
                $Discount_Percent = $d_Item_Biaya['Discount'];
                $fnc_cost_scheme_discount_detail = DB::table('fnc_cost_scheme_discount_detail')
                                                 ->insert([
                                                    'Cost_Scheme_Discount_Id' => $Cost_Scheme_Discount_Id,
                                                    'Cost_Item_Id' => $Cost_Item_Id,
                                                    'Term_Total' => $Term_Total,
                                                    'Discount_Percent' => $Discount_Percent,
                                                    'Created_By' => $Created_By,
                                                    'Created_Date' => $Created_Date
                                                 ]);
            }                            

            // step tree insert to fnc_cost_scheme_discount_member-------------------------------
            foreach ($Mahasiswa as $d_Mahasiswa) {
                $Register_Number = $d_Mahasiswa['Register_Number'];
                $fnc_cost_scheme_discount_member = DB::table('fnc_cost_scheme_discount_member')
                                                ->insert([
                                                    'Register_Number' => $Register_Number,
                                                    'Cost_Scheme_Discount_Id' => $Cost_Scheme_Discount_Id,
                                                    'Created_By' => $Created_By,
                                                    'Created_Date' => $Created_Date
                                                ]);
            }

            $status = 1;

        } catch (\Exception $e) {
            $status = 0;
            $exp = $e->getMessage();
            Alert::error($exp, 'gagal !');
        }
        
        return response()->json(['status' => $status, 'exp' => $exp]);
    }


    public function edit(Request $request)
    {
        $q_costItem = DB::table('fnc_cost_item')
                    ->orderBy('fnc_cost_item.Cost_Item_Code', 'asc')
                    ->get();

        $Cost_Scheme_Discount_Id = $request->Cost_Scheme_Discount_Id;
        
        $Cost_Scheme_Discount = DB::table('fnc_cost_scheme_discount')
                                        ->where('fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','=',$Cost_Scheme_Discount_Id)
                                        ->join('fnc_cost_scheme_discount_detail','fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','fnc_cost_scheme_discount_detail.Cost_Scheme_Discount_Id')
                                        ->join('fnc_cost_scheme_discount_member','fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','fnc_cost_scheme_discount_member.Cost_Scheme_Discount_Id')
                                        ->join('fnc_cost_item','fnc_cost_scheme_discount_detail.Cost_Item_Id','fnc_cost_item.Cost_Item_Id')
                                        ->join('acd_student','fnc_cost_scheme_discount_member.Register_Number','acd_student.Register_Number')
                                        ->first();
        // dd($Cost_Scheme_Discount);
        $Cost_Scheme_Discount_Detail = DB::table('fnc_cost_scheme_discount')
                                        ->where('fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','=',$Cost_Scheme_Discount_Id)
                                        ->join('fnc_cost_scheme_discount_detail','fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','fnc_cost_scheme_discount_detail.Cost_Scheme_Discount_Id')
                                        ->join('fnc_cost_item','fnc_cost_scheme_discount_detail.Cost_Item_Id','fnc_cost_item.Cost_Item_Id')
                                        // ->join('fnc_cost_scheme_discount_member','fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','fnc_cost_scheme_discount_member.Cost_Scheme_Discount_Id')
                                        ->get();

        $Cost_Scheme_Discount_Member = DB::table('fnc_cost_scheme_discount')
                                        ->where('fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','=',$Cost_Scheme_Discount_Id)
                                        // ->join('fnc_cost_scheme_discount_detail','fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','fnc_cost_scheme_discount_detail.Cost_Scheme_Discount_Id')
                                        ->join('fnc_cost_scheme_discount_member','fnc_cost_scheme_discount.Cost_Scheme_Discount_Id','fnc_cost_scheme_discount_member.Cost_Scheme_Discount_Id')
                                        ->join('acd_student','fnc_cost_scheme_discount_member.Register_Number','acd_student.Register_Number')
                                        ->get();
        
        
        return view('diskon_biaya/edit')
                ->with('d_costItem', $q_costItem)
                ->with('Cost_Scheme_Discount_Detail',$Cost_Scheme_Discount_Detail)
                ->with('Cost_Scheme_Discount_Member',$Cost_Scheme_Discount_Member)
                ->with('Cost_Scheme_Discount',$Cost_Scheme_Discount);
    }
    
    public function edit_post(Request $request)
    {
        $exp = "";
        $status = 0;

        $Cost_Scheme_Discount_Id = $request->Cost_Scheme_Discount_Id;
        
        $Discount_Code = $request->Discount_Code;
        $Cost_Scheme_Discount_Name = $request->Cost_Scheme_Discount_Name;
        $Term_Total = $request->Term_Total;
        $Item_Biaya = json_decode($request->Item_Biaya, true);
        $Mahasiswa = json_decode($request->Mahasiswa, true);
        $Created_By = Auth::user()->name;
        $Date_Now = Date("Y-m-d")." 00:00:00";
        $Created_Date = Date('Y-m-d H:i:s',strtotime($Date_Now));

        try {
            //update fnc_cost_scheme_diskon
            $Cost_Scheme_Discount = DB::table('fnc_cost_scheme_discount')
                                    ->where('Cost_Scheme_Discount_Id','=',$Cost_Scheme_Discount_Id)
                                    ->update([
                                        'Discount_Code' => $Discount_Code,
                                        'Cost_Scheme_Discount_Name' => $Cost_Scheme_Discount_Name,
                                        'Modified_By' => $Created_By,
                                        'Modified_Date' => $Created_Date 
                                    ]);
            
            //delete detail 
            $delete_detail = DB::table('fnc_cost_scheme_discount_detail')
                             ->where('Cost_Scheme_Discount_Id','=',$Cost_Scheme_Discount_Id)
                             ->delete();

            // step two inset data to fnc_cost_scheme_discount_detail-------------------------
            foreach ($Item_Biaya as $d_Item_Biaya) {
                $Cost_Item_Id = $d_Item_Biaya['Cost_Item_id'];
                $Discount_Percent = $d_Item_Biaya['Discount'];
                $fnc_cost_scheme_discount_detail = DB::table('fnc_cost_scheme_discount_detail')
                                                 ->insert([
                                                    'Cost_Scheme_Discount_Id' => $Cost_Scheme_Discount_Id,
                                                    'Cost_Item_Id' => $Cost_Item_Id,
                                                    'Term_Total' => $Term_Total,
                                                    'Discount_Percent' => $Discount_Percent,
                                                    'Created_By' => $Created_By,
                                                    'Created_Date' => $Created_Date
                                                 ]);
            }
            
            //delete data mahasiswa
            $delete_mahasiswa = DB::table('fnc_cost_scheme_discount_member')
                                ->where('Cost_Scheme_Discount_Id','=',$Cost_Scheme_Discount_Id)
                                ->delete();
            
            // step tree insert to fnc_cost_scheme_discount_member-------------------------------
            foreach ($Mahasiswa as $d_Mahasiswa) {
                $Register_Number = $d_Mahasiswa['Register_Number'];
                $fnc_cost_scheme_discount_member = DB::table('fnc_cost_scheme_discount_member')
                                                ->insert([
                                                    'Register_Number' => $Register_Number,
                                                    'Cost_Scheme_Discount_Id' => $Cost_Scheme_Discount_Id,
                                                    'Created_By' => $Created_By,
                                                    'Created_Date' => $Created_Date
                                                ]);
            }
            
            $status = 1;
        } catch (\Exception $e) {
            $status = 0;
            $exp = $e->getMessage();
            Alert::error($exp, 'gagal !');
        }

        return response()->json(['status' => $status, 'exp' => $exp]);
        
        
    }

    public function delete(Request $request)
    {
        $Cost_Scheme_Discount_Id = $request->Cost_Scheme_Discount_Id;
        $delete = DB::table('fnc_cost_scheme_discount')
                    ->where('Cost_Scheme_Discount_Id', $Cost_Scheme_Discount_Id)
                    ->delete();
        Alert::success('Sukses menghapus data', 'Berhasil !');
        return redirect('diskon_biaya'); 
    }

    public function Department_Id()
    {
        $Department = DB::table('mstr_department')
                    ->where('Faculty_Id', '=' ,'1')
                    ->OrderBy('Department_Name','asc')->get();

        echo json_encode($Department);
    }

    public function Register_Number(Request $request)
    {
        $Department_Id = $request->Department_Id;
        $Students = DB::table('acd_student')
        ->Where('Department_Id',$Department_Id)
        ->OrderBy('Register_Number','asc')
        ->Select(DB::raw('Register_Number as Register_Number, CONCAT(Register_Number," | ",Full_Name) as Full_Name'))
        ->get();

        echo json_encode($Students);
    }
}
