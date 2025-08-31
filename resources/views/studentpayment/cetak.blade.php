@extends('shared._layout')
@section('content')


<?php
  $access = auth()->user()->akses();
  $acc = $access;
?>

  <section class="content">
    <div class="container-fluid-title">
      <div class="title-laporan">
        <h3 class="text-white">Set Aturan Pengembalian / Retur</h3>
      </div>
    </div>
    <div class="container">
      <div class="panel panel-default bootstrap-admin-no-table-panel">
        <div class="panel-heading-green">

          <div class="pull-right tombol-gandeng dua">
            @if(in_array('Set Aturan Pengembalian-CanCetak',$acc))
             <a href="{{ asset('Set_Aturan_Pengembalian/pdf/'.$Student_Payment_Id) }}" class="btn btn-success btn-sm" onclick="fnExcelReport()">Cetak &nbsp;<i class="fa fa-print"></i></a>
           @endif
           <a href="{{ asset('Set_Aturan_Pengembalian?from_date='.$tgl_awal.'&until_date='.$tgl_akhir) }}" class="btn btn-warning btn-sm"> Kembali &nbsp;<i class="fa fa-arrow-left"></i></a>

          </div>
          <div class="bootstrap-admin-box-title right text-white">
            <b>Cetak Tanda Bukti Retur</b>
          </div>
        </div>
        <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <div class="" style="padding:30px;">
                <div class="form-group">
                  <div class="row">
                    <label class="control-label col-md-3 col-sm-3 col-xs-3">Tanggal</label>
                    <div class="col-md-7 col-sm-7 col-xs-7">
                      : {{ $Payment_Date }}
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <label class="control-label col-md-3 col-sm-3 col-xs-3">NIM</label>
                    <div class="col-md-7 col-sm-7 col-xs-7">
                      : {{ $Student->Nim }}
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <label class="control-label col-md-3 col-sm-3 col-xs-3">Nama</label>
                    <div class="col-md-7 col-sm-7 col-xs-7">
                      : {{ $Student->Full_Name }}
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <label class="control-label col-md-3 col-sm-3 col-xs-3">Item Biaya</label>
                    <div class="col-md-7 col-sm-7 col-xs-7">
                      : {{ $Cost_Item }}
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <label class="control-label col-md-3 col-sm-3 col-xs-3">Nominal</label>
                    <div class="col-md-7 col-sm-7 col-xs-7">
                      : <?php echo number_format($Total_Amount,0,",",".")?>
                    </div>
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9">
                    &nbsp;
                </div>
                <div class="col-lg-3">
                    <center>
                        Bangka Belitung, {{ date('d-F-Y', strtotime($Payment_Date)) }}
                    </center>
                </div>
            </div>
            <div class="row" style="padding-bottom:20px;">
                <div class="col-lg-3">
                    <center>
                        <p style="text-decoration:underline">Petugas</p>
                        <br /><br /><br /><br />
                        ___________________
                    </center>
                </div>
                <div class="col-lg-6">
                    &nbsp;
                </div>
                <div class="col-lg-3">
                    <center>
                        <p style="text-decoration:underline">{{ $Student->Full_Name }}</p>
                        <br /><br /><br /><br />
                        ___________________
                    </center>
                </div>
            </div>
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
