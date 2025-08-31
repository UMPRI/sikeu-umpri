@extends('shared._layout')
@section('pageTitle', 'set_biaya_tambahan')
@section('content')

<?php
  $access = auth()->user()->akses();
  $acc = $access;
?>
<!-- Main content -->
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Biaya Tambahan</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <?php
            if ($Term_Year_Id != null) {
              ?>
              @if(in_array('Set Biaya Tambahan-CanAdd',$acc))
                <a href="{{asset('set_biaya_tambahan/create?Term_Year_Id='.$Term_Year_Id)}}" class="btn btn-success btn-sm">Tambah &nbsp;<i class="fa fa-plus"></i></a>
              @endif
              <?php
            }
           ?>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Set Biaya Tambahan</b>
        </div>
      </div>
          <br>
          <form class="form-inline" action="" method="GET">
            <div class="form-group mb-3">
              <label for="Term_Year_Id">Th/Smt </label>
              <select name="Term_Year_Id" class="form-control form-control-sm col-sm-12" id="Term_Year_Id" onchange = "this.form.submit()">
                <option value="">--- Pilih Th/Smt ---</option>
                <?php
                  foreach ($Term_Year as $d_Term_Year) {
                    ?>
                      <option value="<?php echo $d_Term_Year->Term_Year_Id ?>" <?php if($d_Term_Year->Term_Year_Id == $Term_Year_Id){ echo "selected";} ?>><?php echo $d_Term_Year->Term_Year_Name ?></option>
                    <?php
                  }
                ?>
              </select>
            </div>
          </form>
        <hr>
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <div class="table-responsive">
          <?php
          if ($Cost_Sched_Personal_Plus != null) {
           ?>
          <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="tbl">
            <thead class="thead-default thead-green">
              <tr>
                <th width="w-1">No.</th>
                <th width="w-4">Keterangan</th>
                <th width="w-4">Jadwal</th>
                <th width="w-4">Nama Biaya</th>
                <th width="w-4">Biaya</th>
                <th width="w-4">Jumlah Mahasiswa</th>
                <th width="w-4"><i class="fa fa-cog"></i></th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i=1;
              foreach ($Cost_Sched_Personal_Plus as $data) {
                ?>
                <tr>
                  <td class="td-nom"><b><?php echo $i; ?>.</b></td>
                  <td>{{ $data->Explanation }}</td>
                  <td style="text-align:center;"><?php echo Date('d/m/Y',strtotime($data->Start_Date))." - ".Date('d/m/Y',strtotime($data->End_Date)) ?></td>
                  <td>{{ $data->Cost_Item_Name }}</td>
                  <td style="text-align:right;"><?php echo number_format($data->Amount,0,',','.') ?></td>
                  <td style="text-align:center;">
                    <?php
                      echo $jumlah_mhs = DB::table('fnc_cost_sched_personal_plus_detail')->where('Cost_Sched_Personal_Plus_Id','=',$data->Cost_Sched_Personal_Plus_Id)->count();
                     ?>
                  </td>
                  <td style="text-align:center;">
                    <center>
                      <table style="border:0px; background-color:none;">
                        <tr style="border:0px; background-color:none;">
                          <td style="border:0px; background-color:none;">
                            @if(in_array('Set Biaya Tambahan-CanEdit',$acc))
                              <a href="{{ asset('set_biaya_tambahan/edit/'.$data->Cost_Sched_Personal_Plus_Id.'?Term_Year_Id='.$data->Term_Year_Id)}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                            @endif
                          </td>
                          <td style="border:0px; background-color:none;">
                            <form class="" action="{{ asset('set_biaya_tambahan/delete/'.$data->Cost_Sched_Personal_Plus_Id)}}" method="post" id="delete<?php echo $i ?>">
                              {{ csrf_field() }}
                              @if(in_array('Set Biaya Tambahan-CanDelete',$acc))
                                <button type="button" onclick="confirmdelete(<?php echo $i ?>)" id="confirm<?php echo $i ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button>
                              @endif
                            </form>
                          </td>
                        </tr>
                      </table>
                    </center>
                  </td>
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
