@extends('shared._layout')
@section('pageTitle', 'Biaya_Per_Sks')
@section('content')

<?php
$access = auth()->user()->akses();
$acc = $access;
?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Biaya Per SKS</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <?php
          if ($Class_Prog_Id != null && $Term_Year_Id != null) {
            ?>
             @if(in_array('Biaya Per Sks-CanAdd',$acc)) <a href="{{ asset('/biaya_per_sks/create?Term_Year_Id='.$Term_Year_Id.'&Class_Prog_Id='.$Class_Prog_Id)}}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a> @endif
             @if(in_array('Biaya Per Sks-CanCetak',$acc)) <a href="{{ asset('/biaya_per_sks/cetakSks?Term_Year_Id='.$Term_Year_Id.'&Class_Prog_Id='.$Class_Prog_Id)}}" class="btn btn-warning btn-sm">Cetak &nbsp;<i class="fa fa-print"></i></a> @endif
            <?php
          }
           ?>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Biaya Per SKS</b>
        </div>
      </div>
        <br>
        <form class="form-inline" action="" method="GET">
          <div class="form-group mb-3">
            <label for="Term_Year_Id">Th/Smt </label>
            <select name="Term_Year_Id" class="form-control form-control-sm col-sm-12" id="Term_Year_Id" onchange = "this.form.submit()">
              <option value="">--- Pilih Th/Stm ---</option>
              <?php
                foreach ($q_Term_Year as $Term_Year) {
                  ?>
                    <option value="<?php echo $Term_Year->Term_Year_Id ?>" <?php if($Term_Year->Term_Year_Id == $Term_Year_Id){ echo "selected";} ?>><?php echo $Term_Year->Term_Year_Name ?></option>
                  <?php
                }
              ?>
            </select>
          </div>
          <div class="form-group mb-3">
            <label for="Class_Prog_Id"> Program Kelas </label>
            <select name="Class_Prog_Id" class="form-control form-control-sm col-sm-12" id="Class_Prog_Id" onchange = "this.form.submit()">
              <option value="">--- Pilih Program Kelas ---</option>
              <?php
                foreach ($q_Class_Prog as $Class_Prog) {
                  ?>
                    <option value="<?php echo $Class_Prog->Class_Prog_Id ?>" <?php if($Class_Prog->Class_Prog_Id == $Class_Prog_Id){ echo "selected";} ?>><?php echo $Class_Prog->Class_Program_Name ?></option>
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
          if (isset($pesan)) {
              echo $pesan;
            }
          if (isset($message)) {
            if ($message != null) {
              ?>
              <div class="alert alert-danger">
                <h1>
                  Tidak dapat terhubung ke database !
                </h1>
              </div>
              <?php
            }
          }else{
           ?>
          <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="dataTables-example">
            <thead class="thead-default thead-green">
              <tr>
                <th width="w-4">Prodi</th>
                <?php
                  $jumlah_entry_year = 0;
                  $array_entry_year = array();
                  foreach ($q_Entry_Year_FCCS as $Entry_Year) {
                    ?>
                    <th width="w-4"><?php echo $Entry_Year->Entry_Year_Id ?></th>
                    <?php
                    $jumlah_entry_year++;
                    $array_entry_year[] = $Entry_Year->Entry_Year_Id;
                  }
                 ?>
              </tr>
            </thead>
            <tbody>
              <?php
              $i=1;
              foreach ($q_Department_FCCS as $Department) {
                ?>
                <tr>
                  <td><?php echo $Department->Department_Name." - ".$Department->Acronym; ?></td>
                  <!-- di sini query dalam department -->
                  <?php
                  for ($i=0; $i < $jumlah_entry_year; $i++) {
                    // $angka_array = $i - 1;
                    $entry_year_id_kolom_ini = $array_entry_year[$i];
                    ?>
                    <td>
                      <?php
                      $q_biaya_per_sks = DB::table('fnc_course_cost_sks')
                      ->where('Term_Year_Id','=',$Term_Year_Id)
                      ->where('Class_Prog_Id','=',$Class_Prog_Id)
                      ->where('Department_Id','=',$Department->Department_Id)
                      ->where('Entry_Year_Id','=',$entry_year_id_kolom_ini)
                      ->OrderBy('Entry_Year_Id','desc')
                      ->get();
                      $c_biaya_per_sks = DB::table('fnc_course_cost_sks')
                      ->where('Term_Year_Id','=',$Term_Year_Id)
                      ->where('Class_Prog_Id','=',$Class_Prog_Id)
                      ->where('Department_Id','=',$Department->Department_Id)
                      ->where('Entry_Year_Id','=',$entry_year_id_kolom_ini)
                      ->OrderBy('Entry_Year_Id','desc')
                      ->count();
                      if ($c_biaya_per_sks > 0) {
                        foreach ($q_biaya_per_sks as $data_sks) {
                          ?>
                          <span style="white-space:nowrap">
                            <a href="@if(in_array('Biaya Per Sks-CanEdit',$acc)) {{asset('biaya_per_sks/edit/'.$data_sks->Course_Cost_Sks_Id)}} @endif" title="edit"><?php echo number_format($data_sks->Amount_Per_Sks,0,'.','.') ?></a>
                            <input type="hidden" id="urldelete<?php echo $data_sks->Course_Cost_Sks_Id ?>" value="{{asset('biaya_per_sks/delete/'.$data_sks->Course_Cost_Sks_Id.'?Term_Year_Id='.$Term_Year_Id.'&Class_Prog_Id='.$Class_Prog_Id)}}">
                             @if(in_array('Biaya Per Sks-CanDelete',$acc)) <a href="javascript:" onclick="confirmdelete(<?php echo $data_sks->Course_Cost_Sks_Id ?>)" title="Hapus" class="glyphicon glyphicon-remove" style="color:red"></a> @endif
                          </span>
                          <?php
                        }
                      }else{
                        ?>
                        <a href="{{asset('biaya_per_sks/create?Term_Year_Id='.$Term_Year_Id.'&Class_Prog_Id='.$Class_Prog_Id.'&Department_Id='.$Department->Department_Id.'&Entry_Year_Id='.$entry_year_id_kolom_ini)}}" title="tambah">...</a>
                        <?php
                      }
                      ?>
                    </td>
                    <?php
                  }
                   ?>
                </tr>
                <?php
                $i++;
              }
               ?>
            </tbody>
          </table>
        <?php } ?>
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
