@extends('shared._layout')
@section('pageTitle', 'Student_Bill')
@section('content')

<!-- Main content -->
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Daftar Tagihan</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ asset('/Entry_Pembayaran_Mahasiswa?param='.$param)}}" class="btn btn-sm btn-danger"><i class="fa fa-arrow-left"></i> Batal</a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Cancel</b>
        </div>
      </div>
      <br>
        Apakah anda yakin untuk membatalkan pembayaran ini ?
      </div>
      <div style="padding-left:2em; padding-top:1em;padding-right:2em">
          <div class="pull-right">
          </div>
          <?php
            if (!isset($q_fnc_student_payment)) {
              $q_fnc_student_payment = null;
            }
            if ($q_fnc_student_payment != null) {
           ?>
              <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="dataTables-example">
                <thead class="thead-default thead-green">
                  <tr>
                      <th>
                          No.
                      </th>
                      <th>
                          Tahun Akademik
                      </th>
                      <th>
                          Semester
                      </th>
                      <th>
                          Nama Biaya
                      </th>
                      <th>
                          Biaya
                      </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  $sumAmount =0;
                    foreach ($q_fnc_student_payment as $pembayaran) {
                      $ganjilgenap = substr($pembayaran->Term_Year_Bill_Id,-1);
                      ?>
                      <tr>
                        <td><?php echo $no ?></td>
                        <td><?php echo substr($pembayaran->Term_Year_Bill_Id,0,4) ?></td>
                        <td><?php if( $ganjilgenap == 1){echo "Ganjil";}elseif( $ganjilgenap == 2){echo "Genap";} ?></td>
                        <td><?php echo $pembayaran->Cost_Item_Name ?></td>
                        <td><?php echo number_format($pembayaran->Payment_Amount,'0',',','.') ?></td>
                      </tr>
                      <?php
                      $sumAmount += $pembayaran->Payment_Amount;
                      $no++;
                    }
                   ?>
                </tbody>
                <tfoot>
                  <tr>
                      <td colspan="4" align="right">
                          <b>Jumlah</b>
                      </td>
                      <td align="right">

                        <?php echo number_format($sumAmount,'0',',','.') ?>
                      </td>
                  </tr>
                </tfoot>
              </table><br />

              <form class="form-horizontal" action="{{ asset('/Entry_Pembayaran_Mahasiswa/cancel_post/'.$ReffPaymentId)}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="param" value="<?php echo $param ?>" required readonly>
                <div class="form-group">
                    <label class = "control-label col-md-4">Alasan</label>
                    <div class="col-md-12">
                        <textarea class="form-control form-control-sm" name="alasan" id="alasan"></textarea>
                    </div>
                </div>
                <button type="submit" id="submit" class="btn btn-success">Ya</button>
                <br>
                <br>
              </form>
            <?php } ?>
      </div>
    </div>
  <script type="text/javascript">
  </script>
</section>
@endsection
