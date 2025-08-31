@extends('shared._layout')
@section('pageTitle', 'Set_Biaya_Registrasi')
@section('content')


<?php
  $access = auth()->user()->akses();
  $acc = $access;
?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Setting Biaya Registrasi</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <?php
          if (isset($Term_Year_Id) && isset($Entry_Year_Id) && $Term_Year_Id != 0 && $Entry_Year_Id != 0) {
            if (substr($Term_Year_Id,0,4) != $Entry_Year_Id) {
              $Entry_Period_Type_Id = 1;
              ?>
              {{-- @if(in_array('Set Biaya Registrasi-CanCopyData',$acc))
                <a href="{{ asset('/set_biaya_registrasi/copy_data?Term_Year_Id='.$Term_Year_Id.'&Entry_Year_Id='.$Entry_Year_Id.'&Entry_Period_Type_Id='.$Entry_Period_Type_Id.'&Url='.url()->full())}}" class="btn btn-warning btn-sm">Copy data &nbsp;<i class="fa fa-copy"></i></a>
              @endif --}}

              @if(in_array('Set Biaya Registrasi-CanAdd',$acc))
                <a href="{{ asset('/set_biaya_registrasi/create?Term_Year_Id='.$Term_Year_Id.'&Entry_Year_Id='.$Entry_Year_Id.'&Entry_Period_Type_Id='.$Entry_Period_Type_Id)}}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>
              @endif

              <?php
            }elseif($Entry_Period_Type_Id != null){
              ?>
              {{-- @if(in_array('Set Biaya Registrasi-CanCopyData',$acc))
                <a href="{{ asset('/set_biaya_registrasi/copy_data?Term_Year_Id='.$Term_Year_Id.'&Entry_Year_Id='.$Entry_Year_Id.'&Entry_Period_Type_Id='.$Entry_Period_Type_Id.'&Url='.url()->full())}}" class="btn btn-warning btn-sm">Copy data &nbsp;<i class="fa fa-copy"></i></a>
              @endif --}}

              @if(in_array('Set Biaya Registrasi-CanAdd',$acc))
                <a href="{{ asset('/set_biaya_registrasi/create?Term_Year_Id='.$Term_Year_Id.'&Entry_Year_Id='.$Entry_Year_Id.'&Entry_Period_Type_Id='.$Entry_Period_Type_Id)}}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>
              @endif

              <?php
            }
          }
           ?>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Setting Biaya Registrasi</b>
        </div>
      </div>
        <br>
        <form class="form-inline" action="" method="GET">
          <div class="form-group mb-3">
            <label for="Term_Year_Id">Tahun Akademik </label>
            <select name="Term_Year_Id" class="form-control form-control-sm col-sm-12" id="Term_Year_Id" onchange = "this.form.submit()">
              <option value="">--- Pilih Th/Stm ---</option>
              {{-- Data tahun --}}
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
            <label for="Entry_Year_Id"> Tahun Angkatan </label>
            <select name="Entry_Year_Id" class="form-control form-control-sm col-sm-12" id="Entry_Year_Id" onchange = "this.form.submit()">
              <option value="">--- Pilih Tahun Angkatan ---</option>
              <?php
                foreach ($q_Entry_Year as $Entry_Year) {
                  ?>
                    <option value="<?php echo $Entry_Year->Entry_Year_Id ?>" <?php if($Entry_Year->Entry_Year_Id == $Entry_Year_Id){ echo "selected";} ?>><?php echo $Entry_Year->Entry_Year_Name ?></option>
                  <?php
                }
              ?>
            </select>
          </div>
          <?php
          if ($q_Entry_Period_Type != null) {
            ?>
            <div class="form-group mb-3">
            <label for="Entry_Period_Type_Id"> Aturan </label>
            <select name="Entry_Period_Type_Id" class="form-control form-control-sm col-sm-12" id="Entry_Period_Type_Id" onchange = "this.form.submit()">
            <option value="">--- Pilih Aturan ---</option>
            <?php
            foreach ($q_Entry_Period_Type as $Entry_Period_Type) {
              ?>
              <option value="<?php echo $Entry_Period_Type->Entry_Period_Type_Id ?>" <?php if($Entry_Period_Type->Entry_Period_Type_Id == $Entry_Period_Type_Id){ echo "selected";} ?>><?php echo $Entry_Period_Type->Entry_Period_Type_Name ?></option>
              <?php
            }
            ?>
            </select>
            </div>
            <div class="col-mb-3">
              @if(in_array('Set Biaya Registrasi-CanCopyData',$acc))
                <a  data-toggle="tooltip" data-placement="top" title="Jika ingin mengcopy data gelombang sebelumnya !" style="font-size: 15px;" href="{{ asset('/set_biaya_registrasi/copy_data?Term_Year_Id='.$Term_Year_Id.'&Entry_Year_Id='.$Entry_Year_Id.'&Entry_Period_Type_Id='.$Entry_Period_Type_Id.'&Url='.url()->full())}}" class="btn btn-warning btn-sm">Copy data &nbsp;<i class="fa fa-copy"></i></a>
              @endif
            </div>
            <?php
          }
           ?>
        </form>
      </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
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
           <?php
            if ($q_Data->count() > 0) {
            ?>
           <div style="padding-bottom:14px">
              <form class="" action="{{asset('set_biaya_registrasi/Set_Student_Bill')}}" method="post" id="SetStudentBill">
                {{ csrf_field() }}
                <input type="hidden" name="current_url" value="<?php echo url()->full() ?>">
                <input type="hidden" name="Term_Year_Id" value="<?php echo $Term_Year_Id ?>">
                <input type="hidden" name="Entry_Year_Id" value="<?php echo $Entry_Year_Id ?>">
                <input type="hidden" name="Entry_Period_Type_Id" value="<?php echo $Entry_Period_Type_Id ?>">
                @if(in_array('Set Biaya Registrasi-CanHitungTagihan',$acc))<button type="submit" class="btn btn-warning">Hitung Tagihan <i class="fa fa-list-alt"></i></button> @endif
              </form>
          </div>
          <?php } ?>
          <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="dataTables-example">
            <thead class="thead-default thead-green">
              <tr>
                <th width="w-4">Prodi</th>
                <th width="w-4">Program Kelas</th>
                <th width="w-4">Jadwal Pembayaran</th>
                <th width="w-4">Total Bayar</th>
                <th width="w-4"><i class="glyphicon glyphicon-cog"></i></th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i=1;
              foreach ($q_Data as $data) {
                ?>
                <tr>
                  <td>{{ $data->Department_Name }} - {{ $data->Acronym }}</td>
                  <td>{{ $data->Class_Program_Name }}</td>
                  <td style="text-align:center;"><?php echo Date('d-m-Y',strtotime($data->Start_Date)) ?> - <?php echo Date('d-m-Y',strtotime($data->End_Date)) ?></td>
                  <td style="text-align:right;"><?php echo number_format($data->Total_Amount,0,',','.') ?></td>
                  <td align="center" width="20%">
                    <table style="border:0px; background-color:none;">
                      <tr style="border:0px; background-color:none;">
                        <td style="border:0px; background-color:none;">
                          @if(in_array('Set Biaya Registrasi-CanEdit',$acc))<a href="{{ asset('/set_biaya_registrasi/edit/'.$data->Cost_Sched_Id.'?Term_Year_Id='.$Term_Year_Id)}}" class="btn btn-info btn-sm">EDIT</a>@endif
                        </td>
                        <td style="border:0px; background-color:none;">
                          <input type="hidden" name="urldelete" id="urldelete<?php echo $i ?>" value="{{ asset('/set_biaya_registrasi/delete/'.$data->Cost_Sched_Id)}}">
                          @if(in_array('Set Biaya Registrasi-CanDelete',$acc)) <a href="javascript:" class="btn btn-danger btn-sm" onclick="confirmdelete(<?php echo $i ?>)">Hapus</a> @endif
                        </td>
                      </tr>
                    </table>
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
    var href = $("#urldelete"+param).val();
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
      window.location.href = href;
    });
  }
  </script>
</section>
@endsection
