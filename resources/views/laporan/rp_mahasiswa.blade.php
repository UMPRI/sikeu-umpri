@extends('shared._layout')
@section('pageTitle', 'R_P_Mahasiswa')
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
        <div class="pull-right">
            <?php
            if (isset($q_student))
            {?>
              <div class="margin-badge">
                <p class="badge badge-warning">Nama : <?php echo $q_student->Full_Name ?></p><br/>
                <p class="badge badge-warning">Prodi : <?php echo $q_student->Department_Name ?></p>
              </div>
                <?php
            }
            ?>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Riwayat Pembayaran Mahasiswa</b>
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
              <option value="">Semua</option>
              <?php
                foreach ($q_Term_Year_Id as $Term_Year) {
                  ?>
                    <option value="<?php echo $Term_Year->Term_Year_Id ?>" <?php if($Term_Year->Term_Year_Id == $Term_Year_Id){ echo "selected";} ?>><?php echo $Term_Year->Year_Id ?></option>
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
                <th width="w-1">No.</th>
                <th width="w-4">ke</th>
                <th width="w-4">Tgl Bayar</th>
                <th width="w-4">Jumlah</th>
                <td width="w-4" style="text-align:center;"><i class="fa fa-cog"></i></td>
              </tr>
            </thead>
            <tbody>
              <?php
              if (isset($data)) {
                $i=1;
                foreach ($data as $data_payment) {
                  ?>
                  <tr>
                    <td class="td-nom"><b><?php echo $i; ?>.</b></td>
                    <td>
                      <?php
                      if ($data_payment->Installment_Order == "0") {
                        echo "Lunas";
                      }else {
                        echo $data_payment->Installment_Order;
                      }
                      ?>
                    </td>
                    <td>{{ $data_payment->Payment_Date }}</td>
                    <td style="text-align:right;"><?php echo number_format($data_payment->Total_Amount,0,',','.') ?></td>
                    <td style="text-align:center;">
                      <a title="Lihat Details" class="btn btn-sm btn-info details" href="javascript:;" onclick="modal_show(<?php  echo $data_payment->Reff_Payment_Id ?>)"><i class="glyphicon glyphicon-search"></i></a>&nbsp;
                      <a title="Cetak" class="btn btn-sm btn-success cetak" href="{{ asset('riwayat_pembayaran_details/pdf/'.$data_payment->Reff_Payment_Id)}}" target="_blank"><i class="glyphicon glyphicon-print"></i></a>
                    </td>
                  </tr>
                  <?php
                  $i++;
                }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <center>
      <div class="modal-content" style="width:50%; margin-top:10%;">
        <div class="modal-header">
            <br>
            <b>Detail Riwayat Pembayaran</b>
            <a href="javascript:" class="text-danger" id="close-modal">
                <i class="fa fa-close text-danger"></i>
            </a>
            <script type="text/javascript">
            $("#close-modal").click(function(){
               window.location.reload();
            });
            </script>
        </div>
        <div class="modal-body" id="modal-isi">
        </div>
      </div>
    </center>
  </div>
  <div class="dim" id="gifload">
    <!-- <img class="gifload" src="img/loading.gif" alt="" /> -->
    <i class="gifload fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
    <span class="sr-only">Loading...</span>
  </div>
  <iframe id="txtArea1" style="display:none"></iframe>
  <script type="text/javascript">
      function modal_hide(){
        $("#detail").modal('hide');
      }
      function modal_show(ReffPaymentId) {
          // var ReffPaymentId = $(this).closest("tr").find("td").eq(0).html();
          // var link = $('#url_riwayat').val();
          $.ajax({
            type: "GET",
            url: "{{ url('riwayat_pembayaran_details') }}",
            data: { Reff_Payment_Id: ReffPaymentId },
            // beforeSend: function () {
            //     document.getElementById('gifload').classList.add('show');
            // },
            success: function(data){
                // document.getElementById('gifload').classList.remove('show');
                $('#modal-isi').html(data);
                $("#detail").modal('show');
            },
            // error: function (data) {
            //     document.getElementById('gifload').classList.remove('show');
            //     alert('error'+data);
            // }
          });
      };
  </script>
<script type="text/javascript">
    function fnExcelReport() {
        var tab_text = "<table border='2px'><tr><td colspan='4' align='center'>Laporan Pembayaran Prodi</td></tr><tr bgcolor='#87AFC6'>";
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
