@extends('shared._layout')
@section('pageTitle', 'Set_Biaya_Registrasi_Resume')
@section('content')

<?php
  $access = auth()->user()->akses();
  $acc = $access;
?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Setting Biaya Registrasi (Resume)</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Resume</b>
        </div>
      </div>
        <br>
        <form class="form-inline" action="" method="GET">
          <div class="form-group mb-2 col-md-2">
            <label for="Entry_Year_Id">Th Angkatan </label>
            <select name="Entry_Year_Id" class="form-control form-control-sm col-sm-12" id="Entry_Year_Id" onchange = "this.form.submit()">
              <option value="">--- Pilih Th Angkatan ---</option>
              <?php
                foreach ($Entry_Year as $d_Entry_Year) {
                  ?>
                    <option value="<?php echo $d_Entry_Year->Entry_Year_Id ?>" <?php if($d_Entry_Year->Entry_Year_Id == $Entry_Year_Id){ echo "selected";} ?>><?php echo $d_Entry_Year->Entry_Year_Name ?></option>
                  <?php
                }
              ?>
            </select>
          </div>
          <div class="form-group mb-2 col-md-2">
            <label for="Department_Id"> Program Studi </label>
            <select name="Department_Id" class="form-control form-control-sm col-sm-12" id="Department_Id" onchange = "this.form.submit()">
              <option value="">--- Pilih Program Studi ---</option>
              <?php
                foreach ($Department as $d_Department) {
                  ?>
                    <option value="<?php echo $d_Department->Department_Id ?>" <?php if($d_Department->Department_Id == $Department_Id){ echo "selected";} ?>><?php echo $d_Department->Department_Name ?></option>
                  <?php
                }
              ?>
            </select>
          </div>
          <div class="form-group mb-2 col-md-2">
            <label for="Class_Prog_Id"> Program Kelas </label>
            <select name="Class_Prog_Id" class="form-control form-control-sm col-sm-12" id="Class_Prog_Id" onchange = "this.form.submit()">
              <option value="">--- Pilih Program Kelas ---</option>
              <?php
                foreach ($Class_Prog as $d_Class_Prog) {
                  ?>
                    <option value="<?php echo $d_Class_Prog->Class_Prog_Id ?>" <?php if($d_Class_Prog->Class_Prog_Id == $Class_Prog_Id){ echo "selected";} ?>><?php echo $d_Class_Prog->Class_Program_Name ?></option>
                  <?php
                }
              ?>
            </select>
          </div>
          <div class="form-group mb-2 col-md-2">
            <label for="Entry_Period_Type_Id">Aturan </label>
            <select name="Entry_Period_Type_Id" class="form-control form-control-sm col-sm-12" id="Entry_Period_Type_Id" onchange = "this.form.submit()">
              <option value="">--- Pilih Th Angkatan ---</option>
              <?php
                foreach ($Entry_Period_Type as $d_Entry_Period_Type) {
                  ?>
                    <option value="<?php echo $d_Entry_Period_Type->Entry_Period_Type_Id ?>" <?php if($d_Entry_Period_Type->Entry_Period_Type_Id == $Entry_Period_Type_Id){ echo "selected";} ?>><?php echo $d_Entry_Period_Type->Entry_Period_Type_Name ?></option>
                  <?php
                }
              ?>
            </select>
          </div>
        </form>
      </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <div class="table-responsive">
          <?php
          if (count($data) > 0) {
           ?>
          <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="dataTables-example">
            <thead class="thead-default thead-green">
              <tr>
                <th width="w-4">Nama Biaya</th>
                <?php
                foreach ($Payment_Orders as $Payment_Order) {
                  ?>
                  <th width="w-4">
                    <?php
                    if ($Payment_Order->Payment_Order == 0) {
                      echo "Lunas";
                    }else {
                      echo $Payment_Order->Payment_Order;
                    }
                     ?>
                  </th>
                  <?php
                }
                 ?>
              </tr>
            </thead>
            <tbody>
              <?php
              for ($i=0; $i < count($data); $i++) {
                ?>
                <tr>
                  <td><?php echo $data[$i]['Cost_Item_Name']; ?></td>
                  <?php
                    $ii = 0;
                    foreach ($data[$i]['Isi'] as $value) {
                      ?>
                      <td style="text-align:right;"><a href="@if(in_array('Set Biaya Registrasi Resume-CanEdit',$acc)) {{asset('set_biaya_registrasi_resume/edit/'.$value['Cost_Sched_Detail_Id'])}} @endif"><?php echo number_format($value['Amount'],0,',','.') ?></a></td>
                      <?php
                      $ii++;
                    }
                   ?>
                </tr>
                <?php
              }
               ?>
               <tr>
                 <th style="text-align:center;">Jumlah</th>
                 <?php
                 $iii = 0;
                 foreach ($Payment_Orders as $Payment_Order) {
                   $sumcolumn = 0;
                   ?>
                   <th style="text-align:right;">
                     <?php
                     for ($i=0; $i < count($data); $i++) {
                      $sumcolumn += $data[$i]['Isi'][$iii]['Amount'];
                     }
                     echo number_format($sumcolumn,0,',','.');
                      ?>
                   </th>
                   <?php
                   $iii++;
                 }
                  ?>
               </tr>
            </tbody>
          </table>
        <?php }else{
          ?>
          <center>
            <h4>Tidak ada data</h4>
          </center>
          <?php
        } ?>
        </div>
      </div>
    </div>
  <script type="text/javascript">
  function confirmdelete(param) {
    var href = $("#urldelete"+param).val();
    swal({
      title: "Apa anda yakin?",
      text: "File akan terhapus , anda tidak akan bisa mengembalikan file yang sudah di hapus",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Ya, Hapus!",
      cancelButtonText: "Batal Hapus!",
      closeOnConfirm: false
    },function(){
      window.location.href = href;
    });
  }
  </script>
</section>
@endsection
