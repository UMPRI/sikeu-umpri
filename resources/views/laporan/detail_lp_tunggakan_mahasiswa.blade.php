@extends('shared._layout')
@section('pageTitle', 'Lp_Tunggakan_Mahasiswa')
@section('content')

<!-- Main content -->
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Laporan</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua" style="text-align:right;">
            <a href="{{asset('laporan/Lp_Tunggakan_Mahasiswa?Department_Id='.$Student->Department_Id.'&Entry_Year_Id='.$Student->Entry_Year_Id.'&param='.$param.'&Year_Id='.$Year_Id)}}" class="btn btn-sm btn-danger"><i class="fa fa-arrow-left"></i> Kembali</a>
            <br><br><br>
            <?php
            if (count($tunggakan) > 0) {
            ?>
            <a href="javascript:" class="btn btn-success" style="font-size:medium" onclick="fnExcelReport()">Cetak &nbsp;<i class="fa fa-print"></i></a>
          <?php } ?>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Detail Laporan Tunggakan Mahasiswa</b>
        </div>
      </div>
        <br>
        <p class="badge badge-warning" style="color:black">Nama : {{$Student->Full_Name}}</p><br />
        <p class="badge badge-warning" style="color:black">Prodi : {{$Student->Department_Name}}</p>
        </div>
        <hr>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="tbl">
            <thead class="thead-default thead-green">
              <tr>
                <th width="w-4">No.</th>
                <th width="w-4">Biaya</th>
                <th width="w-4">Tahap Ke</th>
                <th width="w-4">Th/Smt</th>
                <th width="w-4">Tagihan</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (isset($tunggakan)) {
                $sum = 0;
                $i=1;
                foreach ($tunggakan as $data) {
                  ?>
                  <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $data['Cost_Item_Name'] ?></td>
                    <td><?php echo $data['Payment_Order'] ?></td>
                    <td><?php echo /*"Ini error th" */$data['Term_Year_Bill_Name'] ?></td>
                    <td style="text-align:right;"><?php echo number_format($data['Amount'],0,',','.') ?></td>
                  </tr>
                  <?php
                  $i++;
                  $sum += $data['Amount'];
                }
                ?>
                <tr>
                  <td colspan="4" style="text-align:center;">Total</td>
                  <td style="text-align:right;"><?php echo number_format($sum,0,',','.') ?></td>
                </tr>
                <?php
              }else{
                ?>
                <tr>
                  <td colspan="6">Tidak Ada Data</td>
                </tr>
                <?php
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
    function fnExcelReport() {
        var tab_text = "<table border='2px'><tr><td colspan='6' align='center'>Detail Laporan Tunggakan Mahasiswa</td></tr><tr><td colspan='6'>Nama : {{$Student->Full_Name}}</td></tr><td colspan='6'>Prodi : {{$Student->Department_Name}}</td></tr><tr bgcolor='#87AFC6'>";
        var textRange; var j = 0;
        tab = document.getElementById('tbl'); // id of table

        for (j = 0 ; j < tab.rows.length ; j++) {
            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            //tab_text=tab_text+"</tr>";
        }

        tab_text = tab_text + "</table>";


        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
        {
            txtArea1.document.open("txt/html", "replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus();
            sa = txtArea1.document.execCommand("SaveAs", true, "Global View Task.xls");
        }
        else //other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
        return (sa);
    }
</script>
</section>
@endsection
