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

class CostItemController extends Controller
{
    public function __construct()
    {
          $this->middleware('auth');
    }

    // index -----------------------------------------------------------------------------------------\\
    public function index()
    {
      // $access = auth()->user()->Access();
      // foreach ($access as $data) {$cost_item = explode("|",$data->cost_item);}
      // if (in_array(1,$cost_item)) {
         $search = Input::get('search');
         $rowpage = Input::get('rowpage');
         if ($rowpage == null) {
           $rowpage = 10;
         }

         if ($search == null) {
           $q_costItem = DB::table('fnc_cost_item')
           ->orderBy('fnc_cost_item.Cost_Item_Code', 'asc')
           ->paginate($rowpage);
         }else {
           $q_costItem = DB::table('fnc_cost_item')
           ->where('Cost_Item_Name', 'LIKE', '%'.$search.'%')
           ->orwhere('Cost_Item_Code', 'LIKE', '%'.$search.'%')
           ->orderBy('fnc_cost_item.Cost_Item_Code', 'asc')
           ->paginate($rowpage);
         }
         $q_costItem->appends(['search'=> $search, 'rowpage'=> $rowpage]);
          
         return view('item_biaya/index')
            ->with('q_costItem',$q_costItem)
            ->with('search',$search)
            ->with('rowpage',$rowpage);
      
    }
    // akhir index -------------------------------------------------------------------------------------//

    // create ------------------------------------------------------------------------------------------\\
    public function create()
    {
      // $access = auth()->user()->Access();
      // foreach ($access as $data) {$cost_item = explode("|",$data->cost_item);}
      // if (in_array(1,$cost_item)) {
      return view('item_biaya/create');
      // }else{
      //   return view('error/403');
      // }
    }
    // akhir create -------------------------------------------------------------------------------------//

    // create post --------------------------------------------------------------------------------------\\
    public function createpost(Request $request)
    {
      $Cost_Item_Code= Input::get('item_code');
      $Cost_Item_Name= Input::get('item_name');
      $Acronym= Input::get('acronym');
      $insert = DB::table('fnc_cost_item')->insert([
                    ['Cost_Item_Code' => $Cost_Item_Code, 'Cost_Item_Name' => $Cost_Item_Name, 'Acronym' => $Acronym]
                  ]);
      if ($insert) {
        Alert::success('Sukses menambahkan data', 'Berhasil !');
        return redirect('item_biaya');
      }else{
        Alert::error('Terjadi kesalahan, gagal menambahkan data', 'Gagal !');
        return redirect('item_biaya');
      }
    }
    // akhir create post --------------------------------------------------------------------------------//

    // edit ---------------------------------------------------------------------------------------------\\
    public function edit($Cost_Item_Id){
      $q_cost_item = DB::table('fnc_cost_item')->where('Cost_Item_Id','=',$Cost_Item_Id)->first();
      return view('item_biaya/edit')->with('cost_item',$q_cost_item);
    }
    // akhir edit ---------------------------------------------------------------------------------------//

    // edit post ----------------------------------------------------------------------------------------\\
    public function editpost($Cost_Item_Id)
    {
      $Cost_Item_Code= Input::get('item_code');
      $Cost_Item_Name= Input::get('item_name');
      $Acronym= Input::get('acronym');
      $update = DB::table('fnc_cost_item')
                  ->where('Cost_Item_Id',$Cost_Item_Id)
                  ->update([
                    'Cost_Item_Code' => $Cost_Item_Code,
                    'Cost_Item_Name' => $Cost_Item_Name,
                    'Acronym' => $Acronym
                  ]);
      if ($update) {
        Alert::success('Sukses mengubah data', 'Berhasil !');
          return redirect('item_biaya');
      }else{
        $message = "Tidak dapat menyimpan data";
        return redirect('item_biaya')->with('message',$message);
      }
    }
    // akhir edit post ---------------------------------------------------------------------------------//

    // delete ------------------------------------------------------------------------------------------\\
    public function delete($Cost_Item_Id){
      try {
          
        $delete = DB::table('fnc_cost_item')
        ->where('Cost_Item_Id',$Cost_Item_Id)
        ->delete();

        Alert::success('Sukses menghapus data', 'Berhasil !');
      } catch (\Throwable $e) {
        Alert::error("Item Biaya ini telah digunakan", 'Error !');
      }

      return redirect('item_biaya');
    }
    // akhir delete ------------------------------------------------------------------------------------//
}
