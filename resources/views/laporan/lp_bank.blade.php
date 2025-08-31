@extends('shared._layout')
@section('pageTitle', 'Lp_Bank')
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
            if (count($LapPerBank) > 0) {
            ?>
            <a href="javascript:" class="btn btn-success btn-sm" onclick="fnExcelReport()">Cetak &nbsp;<i class="fa fa-print"></i></a>
            <?php
            }
             ?>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Laporan Pembayaran Per Bank</b>
        </div>
      </div>
        <br>
        <form class="form-inline" action="" method="GET">
          <div class="form-group mb-3">
            <label for="Bank_Id"> Bank </label>
            <select name="Bank_Id" class="form-control form-control-sm col-sm-12" id="Term_Year_Id">
              <option value="">Semua Bank</option>
              <?php
                foreach ($q_Bank_Id as $Bank) {
                  ?>
                    <option value="<?php echo $Bank->Bank_Id ?>" <?php if($Bank->Bank_Id == $Bank_Id){ echo "selected";} ?>><?php echo $Bank->Bank_Name ?></option>
                  <?php
                }
              ?>
            </select>
          </div>
          <div class="form-group panggil_date mb-3">
              <label for="Nama" class = "control-label col-md-4">Mulai</Label>
              <div class="col-md-12 col-sm-12">
                  <div class="input-group date">
                    <?php
                    if(isset($StartDate)){
                      ?>
                      <input type="text" name="StartDate" id='datetimepicker1' class="form-control " value="<?php echo $StartDate ?>" >
                      <?php
                    }else{
                      ?>
                      <input type="text" name="StartDate" id='datetimepicker1' class="form-control " value="" >
                    <?php } ?>
                  </div>
              </div>
          </div>
          <script type="text/javascript">
            $('#datetimepicker1').kendoDatePicker({
                uiLibrary: 'bootstrap4'
            });
          </script>
          <div class="form-group panggil_date mb-3">
              <label for="Nama" class = "control-label col-md-4">Selesai</Label>
              <div class="col-md-12 col-sm-12">
                  <div class="input-group date">
                    <?php
                    if(isset($EndDate)){
                      ?>
                      <input type="text" name="EndDate" id='datetimepicker2' class="form-control " value="<?php echo $EndDate ?>" >
                      <?php
                    }else{
                      ?>
                      <input type="text" name="EndDate" id='datetimepicker2' class="form-control " value="" >
                    <?php } ?>
                  </div>
              </div>
          </div>
          <script type="text/javascript">
              $('#datetimepicker2').kendoDatePicker({
                  uiLibrary: 'bootstrap4'
              });
          </script>
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
                <th width="w-4">Tgl Bayar</th>
                <th width="w-4">Bank</th>
                <th width="w-4">Jumlah</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (isset($LapPerBank)) {
                $i=1;
                foreach ($LapPerBank as $data_payment) {
                  ?>
                  <tr>
                    <td><?php echo date("d-m-Y",strtotime($data_payment->Payment_Date)) ?></td>
                    <td><a href="{{asset('laporan/Lp_Bank_Detail?Tgl_Bayar='.$data_payment->Payment_Date.'&Bank='.$data_payment->Bank_Id)}}">{{ $data_payment->Bank_Name }}</a></td>
                    <td style="text-align:right;"><?php echo number_format($data_payment->Payment_Amount,0,',','.') ?></td>
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
            <?php $actual_link = url()->previous(); ?>
            <a href="{{$actual_link}}" class="text-danger">
                <i class="fa fa-close text-danger"></i>
            </a>
            <script type="text/javascript">
            $("#close-modal").click(function(){
               $("#detail").modal('hide');
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
        var tab_text = "<table border='2px'><tr><td colspan='3' align='center'>Laporan Pembayaran Per Bank</td></tr><tr bgcolor='#87AFC6'>";
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
