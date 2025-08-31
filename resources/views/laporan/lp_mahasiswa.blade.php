@extends('shared._layout')
@section('pageTitle', 'Lp_Mahasiswa')
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
        <div class="pull-right tombol-gandeng dua">
          <?php
          if (count($q_data) > 0) {
           ?>
          <a href="javascript:" class="btn btn-success btn-sm" onclick="fnExcelReport()">Cetak &nbsp;<i class="fa fa-print"></i></a>
          <?php
          }
           ?>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Laporan Pembayaran Program Studi</b>
        </div>
      </div>
          <br>
          <form class="form-inline" action="" method="GET">
            <div class="form-group mb-3">
              <label for="Term_Year_Id">Th/Smt </label>
              <select name="Term_Year_Id" class="form-control form-control-sm col-sm-12" id="Term_Year_Id" onchange = "this.form.submit()">
                <option value="">--- Pilih Th/Stm ---</option>
                <?php
                  foreach ($q_Term_Year_Id as $Term_Year) {
                    ?>
                      <option value="<?php echo $Term_Year->Term_Year_Id ?>" <?php if($Term_Year->Term_Year_Id == $Term_Year_Id){ echo "selected";} ?>><?php echo $Term_Year->Year_Id ?></option>
                    <?php
                  }
                ?>
              </select>
            </div>
            <div class="form-group mb-3">
              <label for="Department_Id">Prodi </label>
              <select name="Department_Id" class="form-control form-control-sm col-sm-12" id="Term_Year_Id" onchange = "this.form.submit()">
                <option value="">--- Pilih Prodi ---</option>
                <?php
                  foreach ($q_Department_Id as $Department) {
                    ?>
                      <option value="<?php echo $Department->Department_Id ?>" <?php if($Department->Department_Id == $Department_Id){ echo "selected";} ?>><?php echo $Department->Department_Name ?></option>
                    <?php
                  }
                ?>
              </select>
            </div>
          </form>
        </div>
        <hr>
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
          <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="tbl">
            <thead class="thead-default thead-green">
              <tr>
                <th width="w-1">No.</th>
                <th width="w-4">Nim</th>
                <th width="w-4">Nama</th>
                <th width="w-4">Kelas</th>
                <th width="w-4">Jumlah Pembayaran</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i=1;
              foreach ($q_data as $data) {
                ?>
                <tr>
                  <td class="td-nom"><b><?php echo $i; ?>.</b></td>
                  <td>{{ $data->Nim }}</td>
                  <td><a href="{{asset('laporan/Rp_Mahasiswa?param='.$data->Nim)}}">{{ $data->Full_Name }}</a></td>
                  <td>{{ $data->Class_Program_Name }}</td>
                  <td style="text-align:right;"><?php echo number_format($data->Payment_Amount,0,',','.') ?></td>
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
  <iframe id="txtArea1" style="display:none"></iframe>
<script type="text/javascript">
    function fnExcelReport() {
        var tab_text = "<table border='2px'><tr><td colspan='5' align='center'>Laporan Pembayaran Mahasiswa</td></tr><tr bgcolor='#87AFC6'>";
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
