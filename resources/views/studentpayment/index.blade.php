@extends('shared._layout');
@section('content')

<?php
  $access = auth()->user()->akses();
  $acc = $access;
?>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid-title">
      <div class="title-laporan">
        <h3 class="text-white">Set Aturan Pengembalian</h3>
      </div>
    </div>
    <div class="container">
      <div class="panel panel-default bootstrap-admin-no-table-panel">
        <div class="panel-heading-green">
          <div class="pull-right tombol-gandeng dua">
            @if ($tgl_awal != null && $tgl_akhir != null)
              <div class="margin-badge">
                <p class="badge badge-warning" style="color:black">Dari Tanggal : {{ date('d F Y', strtotime($tgl_awal)) }}</p></br>
                <p class="badge badge-warning" style="color:black">Sampai Tanggal : {{ date('d F Y', strtotime($tgl_akhir)) }}</p>
              </div>
            @endif
          </div>
          <div class="bootstrap-admin-box-title right text-white">
            <b>Set aturan Pengembalian/Retur</b>
          </div>
        </div>
        <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <div class="row text-green" style="padding-top:30px;padding-left:15px; ">
            {{-- <form class="form-inline" action="" method="GET">
              <div class="form-group mb-3">
                <label for="Dari Tanggal"> Dari Tanggal </label>
                <input type="text" name="from_date" class="form-control" placeholder="Dari Tanggal">
              </div>
              <div class="form-group mb-3">
                <label for="Dari Tanggal"> Sampai Tanggal </label>
                <input type="text" name="until_date" class="form-control" placeholder="Dari Tanggal">
              </div>
            </form> --}}
            <script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/js/gijgo.min.js" type="text/javascript"></script>
            <link href="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/css/gijgo.min.css" rel="stylesheet"/>
            <form class="form-inline" action="" method="GET">
              <div class="form-group mb-3">
                <label for="param">Dari Tanggal &nbsp;</label>
                <input id="from_date" name="from_date" class="form-control" value="{{ $tgl_awal }}"/>
                <script>
                    $('#from_date').kendoDatePicker({
                        uiLibrary: 'bootstrap4',
                    });
                </script>
              </div>
              <div class="form-group mb-3">
                <label for="Term_Year_Id">Sampai Tanggal &nbsp;</label>
                <input id="until_date" name="until_date" value="{{ $tgl_akhir }}"/>
                <script>
                    $('#until_date').kendoDatePicker({
                        uiLibrary: 'bootstrap4',
                    });
                </script>


              </div>
              <div class="form-group mb-3" style="padding-top:20px;">
                &nbsp;
                <button type="submit" class="btn btn-lg btn-success"><i class="fa fa-search"></i></button>
              </div>
            </form>
          </div>
          <div class="tombol-gandeng dua">
            @if(in_array('Set Aturan Pengembalian-CanAdd',$acc))
              <a href="{{ asset('Set_Aturan_Pengembalian/Create') }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>
            @endif
          </div>
          {{-- <form class="row text-green" action="" method="GET" style="padding-top:30px;padding-left:15px; ">
            Cari : &nbsp;<input type="text" name="search"  class="form-control form-control-sm col-md-4" value="" placeholder="Cari">&nbsp;
            Baris/Halaman : &nbsp;<input type="number" name="rowpage" min="1" class="form-control form-control-sm col-md-1" value="" placeholder="Baris Halaman">&nbsp;
            <input type="submit" name="" class="btn btn-primary btn-sm" value="kirim">
          </form> --}}

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
                  <th width="w-1">Tanggal Pembayaran</th>
                  <th width="w-4">NIM</th>
                  <th width="w-4">Nama Lengkap</th>
                  <th width= "w-4">Department_Name</th>
                  <th width= "w-4">Item Biaya</th>
                  <th width= "w-4">Total_Amount</th>
                  <th width="w-4"><i class="glyphicon glyphicon-cog"></i></th>
                </tr>
              </thead>
              <tbody>
                @if ($Fnc_Student_Payment != null)
                  @foreach ($Fnc_Student_Payment as $res)
                    <tr>
                      <td class="center">{{ date('d F Y' , strtotime($res->Payment_Date)) }}</td>
                      <td>{{ $res->Nim }}</td>
                      <td>{{ $res->Full_Name }}</td>
                      <td>{{ $res->Department_Name }}</td>
                      <td>{{ $res->Cost_Item_Name }}</td>
                      <td style="text-align: right">{{ number_format($res->Payment_Amount * (0 - 1),0,",",".") }}</td>
                      <td align="center" width="20%">
                        <table style="border:0px; background-color:none; margin:0;padding:0;">
                          <tr style="border:0px; background-color:none;margin:0;padding:0;">
                            <td style="border:0px; background-color:none;margin:0;">
                              <a href="{{ asset('Set_Aturan_Pengembalian/edit/'.$res->Student_Payment_Id."?from_date=".$_GET['from_date']."&until_date=".$_GET['until_date']) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                            </td>
                            <td style="border:0px; background-color:none;margin:0;">
                              {{-- 46636?tgl_awal=2018-07-11%2000:00:00&tgl_akhir=2018-12-07%2000:00:00 --}}
                              <a href="{{ asset('Set_Aturan_Pengembalian/pdf/'.$res->Student_Payment_Id) }}" class="btn btn-success btn-sm"><i class="fa fa-print"></i></a>
                            </td>
                            <td style="border:0px; background-color:none;margin:0;">
                              <form class="" action="{{ asset('Set_Aturan_Pengembalian/delete') }}" method="post" id="delete<?php echo $res->Student_Payment_Id;?>">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $res->Student_Payment_Id }}">
                                <button type="button" onclick="confirmdelete(<?php echo $res->Student_Payment_Id ?>)" id="confirm<?php echo $res->Student_Payment_Id ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                              </form>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  @endforeach

                @endif
                  <tfoot class="thead-green">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tfoot>
              </tbody>
            </table>
          <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
        function confirmdelete(param) {
          var form = $('#cconfirm'+param).parent('form');
          swal({
            title: 'Apa anda yakin ?',
            text: 'Data akan terhapus , anda tidak akan bisa mengembalikan data yang sudah di hapus',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor:'#DD6B55',
            confirmButtonText:"Ya, Hapus!",
            cancelButtonText:"Batal Hapus",
            closeOnConfirm: false
          },function() {
            $("#delete"+param).submit()
          });
        }
    </script>
  </section>
@endsection
