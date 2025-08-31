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
            <?php
            if (count($lap) > 0) {
            ?>
            <a href="javascript:" class="btn btn-success btn-sm" onclick="fnExcelReport()">Cetak &nbsp;<i class="fa fa-print"></i></a>
          <?php } ?>
            <br><br><br>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Laporan Tunggakan Mahasiswa</b>
        </div>
      </div>
        <br>
        <form class="form-inline" action="" method="GET">
          <div class="form-group mb-3">
            <label for="Department_Id"> Prodi </label>
            <select name="Department_Id" class="form-control form-control-sm col-sm-12" id="Department_Id">
              <option value="">--Pilih Prodi--</option>
              <?php
                foreach ($q_Department_Id as $d_Department) {
                  ?>
                    <option value="<?php echo $d_Department->Department_Id ?>" <?php if($d_Department->Department_Id == $Department_Id){ echo "selected";} ?>><?php echo $d_Department->Department_Name ?></option>
                  <?php
                }
              ?>
            </select>
          </div>
          <div class="form-group mb-3">
            <label for="Entry_Year_Id"> Angkatan </label>
            <select name="Entry_Year_Id" class="form-control form-control-sm col-sm-12" id="Entry_Year_Id">
              <option value="">--Pilih Angkatan--</option>
              <?php
                foreach ($q_Entry_Year_Id as $d_Entry_Year) {
                  ?>
                    <option value="<?php echo $d_Entry_Year->Entry_Year_Id ?>" <?php if($d_Entry_Year->Entry_Year_Id == $Entry_Year_Id){ echo "selected";} ?>><?php echo $d_Entry_Year->Entry_Year_Name ?></option>
                  <?php
                }
              ?>
            </select>
          </div>
          <div class="form-group mb-3">
            <label for="param"> Janis Kewajiban </label>
            <select name="param" class="form-control form-control-sm col-sm-12" id="param">
              <option value="">--Pilih Kewajiban--</option>
              <option value="1" <?php if($param == 1){ echo "selected";} ?>>Kewajiban Saat Ini</option>
              <option value="2" <?php if($param == 2){ echo "selected";} ?>>Kewajiban Seluruhnya</option>
            </select>
          </div>
          <div class="form-group mb-3" id="div_Year_Id">
            <label for="Year_Id"> Tahun Ajaran </label>
            <select name="Year_Id" class="form-control form-control-sm col-sm-12" id="Year_Id">
              <option value="">--Pilih Tahun Ajaran--</option>
              <?php
                foreach ($q_Year_Id as $d_Year) {
                  ?>
                    <option value="<?php echo $d_Year->Year_Id ?>" <?php if($d_Year->Year_Id == $Year_Id){ echo "selected";} ?>><?php echo $d_Year->Year_Id ?></option>
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
        </div>
        <hr>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="tbl">
            <thead class="thead-default thead-green">
              <tr>
                <th width="w-4">No.</th>
                <th width="w-4">Nim</th>
                <th width="w-4">Nama</th>
                <th width="w-4">Prodi</th>
                <th width="w-4">Program Kelas</th>
                <th width="w-4">Jumlah</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (isset($lap)) {
                $sum = 0;
                $i=1;
                foreach ($lap as $data_payment) {
                  ?>
                  <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $data_payment['Nim'] ?></td>
                    <td><?php echo $data_payment['Full_Name'] ?></td>
                    <td><?php echo $data_payment['Department_Name'] ?></td>
                    <td><?php echo $data_payment['Class_Program_Name'] ?></td>
                    <td style="text-align:right;"><a href="{{ url('laporan/Detail_Lp_Tunggakan_Mahasiswa?Register_Number='.$data_payment['Register_Number'].'&param='.$param.'&Year_Id='.$data_payment['Entry_Year_Id'])}}"><?php echo number_format($data_payment['Tunggakan'],0,',','.') ?></a></td>
                  </tr>
                  <?php
                  $i++;
                  $sum += $data_payment['Tunggakan'];
                }
                ?>
                <tr>
                  <td colspan="5" style="text-align:center;">Total</td>
                  <td style="text-align:right;"><?php echo number_format($sum,0,',','.') ?></td>
                </tr>
                <?php
              }else{
                ?>
                <tr>
                  <td colspan="7">Tidak Ada Data</td>
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
        var tab_text = "<table border='2px'><tr><td colspan='6' align='center'>Laporan Tunggakan Mahasiswa</td></tr><tr bgcolor='#87AFC6'>";
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
    $(document).ready(function () {
        var param = $('#param :selected').val();
        if (param == 2 ) {
            $("#Year_Id").css("display", "block");
            $("#div_Year_Id").css("display", "block");
        } else {
            $("#Year_Id").css("display", "none");
            $("#div_Year_Id").css("display", "none");
        }
        $('#param').change(function () {
            var param = $('#param :selected').val();
            if (param == 2) {
                $("#Year_Id").css("display", "block");
                $("#div_Year_Id").css("display", "block");
            } else {
                $("#Year_Id").css("display", "none");
                $("#div_Year_Id").css("display", "none");
            }
        })
    });
</script>
</section>
@endsection
