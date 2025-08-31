@extends('shared._layout')
@section('pageTitle', 'Item_Biaya')
@section('content')

<?php
  $access = auth()->user()->akses();
  $acc = $access;
?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Setting Dispensasi Key-In Personal</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <?php
          if (isset($q_Student))
          {
              ?>
            @if(in_array('Item Biaya-CanAdd',$acc)) <a href="{{ asset('/set_biaya_keyin/create?param='.$param.'&Term_Year_Id='.$Term_Year_Id)}}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif

        </div>
        <div class="pull-right">

                <div class="margin-badge">
                  <p class="badge badge-warning">Nama : <?php echo $q_Student->Full_Name ?></p><br />
                  <p class="badge badge-warning">Prodi : <?php echo $q_Student->Department_Name ?></p>
                </div>
                <?php
            }else { ?>
              <div class="alert alert-success" style="font-size:11px;">
                <b>Nim/Reg.Number Salah !</b><br>
                Pastikan NIM/Reg.Number yang anda Inputkan benar
              </div>
            <?php }
            ?>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Set Dispensasi Key-In Personal</b>
        </div>
      </div>
      <br>

      <form class="form-inline" action="" method="GET">
        <div class="form-group mb-3">
          <label for="param">Nim / Register Number </label>
          <?php
            if (isset($param)) {
              ?>
              <input type="text" name="param" class="form-control form-control-sm col-sm-12" id="param" value="<?php echo $param ?>">
              <?php
            }else{
              ?>
              <input type="text" name="param" class="form-control form-control-sm col-sm-12" id="param">
              <?php
            }
           ?>
        </div>
        <div class="form-group mb-3">
          <label for="Term_Year_Id"> Tahun / Semester </label>
          <select name="Term_Year_Id" class="form-control form-control-sm col-sm-12" id="Term_Year_Id">
            <option value="">--- Pilih Tahun / Semester ---</option>
            <?php
              foreach ($q_Term_Year as $Term_Year) {
                ?>
                  <option value="<?php echo $Term_Year->Term_Year_Id ?>" <?php if($Term_Year->Term_Year_Id == $Term_Year_Id){ echo "selected";} ?>><?php echo $Term_Year->Term_Year_Name ?></option>
                <?php
              }
            ?>
          </select>
        </div>
        <div class="form-group mb-3" style="padding-top:20px;">
          &nbsp;
          <button type="submit" class="btn btn-lg btn-success"><i class="fa fa-search"></i></button>
        </div>
      </form>

      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <br>
        <div class="table-responsive">
          <?php
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
                <th width="w-4">Thn/Smtr</th>
                <th width="w-4">Jenis Dispensasi</th>
                <th width="w-4">Diskon</th>
                <th width="w-4">Jenis Matakuliah</th>
                <th width="w-4"><i class="glyphicon glyphicon-cog"></i></th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (count($fnc_student_cost_krs_personal) > 0) {
                foreach ($fnc_student_cost_krs_personal as $cost_krs_personal) { ?>
                  <tr>
                    <td align="center">{{ $cost_krs_personal->Term_Year_Name }}</td>
                    <td align="center">{{ $cost_krs_personal->Description }}</td>
                    <td align="center">{{ $cost_krs_personal->Percent }}</td>
                    <td align="center">{{ $cost_krs_personal->Course_Type_Name }}</td>
                    <td align="center" width="20%">
                      <table style="border:0px; background-color:none;">
                        <tr style="border:0px; background-color:none;">
                          <td style="border:0px; background-color:none;">
                            <a href="{{ asset('set_biaya_keyin/edit/'.$cost_krs_personal->Student_Cost_Krs_Personal_Id.'?param='.$param.'&Term_Year_Id='.$Term_Year_Id) }}" class="btn btn-info btn-sm">EDIT</a>
                          </td>
                          <td style="border:0px; background-color:none;">
                            <form class="" action="{{ asset('set_biaya_keyin/delete/'.$cost_krs_personal->Student_Cost_Krs_Personal_Id.'?param='.$param.'&Term_Year_Id='.$Term_Year_Id) }}" method="post" id="delete<?php echo $cost_krs_personal->Student_Cost_Krs_Personal_Id ?>">
                              {{ csrf_field() }}
                                <button type="button" onclick="confirmdelete(<?php echo $cost_krs_personal->Student_Cost_Krs_Personal_Id ?>)" id="confirm<?php echo $cost_krs_personal->Student_Cost_Krs_Personal_Id ?>" class="btn btn-danger btn-sm">HAPUS</button>
                            </form>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
              <?php
              }
            } ?>
            </tbody>
          </table>
        <?php } ?>
        </div>

      </div>
    </div>
  </div>
  <script type="text/javascript">
      function confirmdelete(param) {
        var form = $('#confirm'+param).parents('form');
        swal({
          title: "Apa anda yakin?",
          text: "Data akan terhapus , anda tidak akan bisa mengembalikan data yang sudah di hapus",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Ya, Hapus!",
          cancelButtonText: "Batal Hapus!",
          closeOnConfirm: false
        },function(){
          $("#delete"+param).submit();
        });
      }
  </script>
</section>
@endsection
