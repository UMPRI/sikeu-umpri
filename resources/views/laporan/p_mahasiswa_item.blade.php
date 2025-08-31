@extends('shared._layout')
@section('pageTitle', 'Pembayaran_Mahasiswa_Per_Item')
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
            ?>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Pembayaran Mahasiswa Per Item</b>
        </div>
      </div>
        <br>
        <form class="form-inline" action="" method="GET">
          <div class="form-group col-sm-2">
            <label for="Term_Year_Id"> Tahun Akademik </label>
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
          <div class="form-group col-sm-2">
            <label for="Payment_Order"> Tahap Ke </label>
            <select name="Payment_Order" class="form-control form-control-sm col-sm-12" id="Term_Year_Id">
              <option value="">Semua</option>
              <?php
              $no_array = 0;
              $length_array = count($Payment_Orders);
              if (isset($Payment_Order)) {
                for($i = 0; $i < $length_array; $i++) {
                  ?>
                  <option value="<?php echo $no_array ?>" <?php if($no_array == $Payment_Order){echo "selected";} ?>><?php echo $Payment_Orders[$no_array] ?></option>
                  <?php
                  $no_array++;
                }
              }else{
                for($i = 0; $i < $length_array; $i++) {
                  ?>
                  <option value="<?php echo $no_array ?>"><?php echo $Payment_Orders[$no_array] ?></option>
                  <?php
                  $no_array++;
                }
              }
               ?>
            </select>
          </div>
          <div class="form-group col-sm-2">
            <label for="Cost_Item_Id"> Item Pembayaran </label>
            <select name="Cost_Item_Id" class="form-control form-control-sm col-sm-12" id="Term_Year_Id">
              <option value="">Semua</option>
              <?php
                foreach ($q_Cost_Item_Id as $Cost_Item) {
                  ?>
                    <option value="<?php echo $Cost_Item->Cost_Item_Id ?>" <?php if($Cost_Item->Cost_Item_Id == $Cost_Item_Id){ echo "selected";} ?>><?php echo $Cost_Item->Cost_Item_Name ?></option>
                  <?php
                }
              ?>
            </select>
          </div>
          <div class="form-group panggil_date col-sm-2">
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
          <div class="form-group panggil_date col-sm-2">
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
                <th width="w-1">No.</th>
                <th width="w-4">Nama Prodi</th>
                <th width="w-4">Tahun Akademik</th>
                <th width="w-4">Tahap Ke</th>
                <th width="w-4">Item Pembayaran</th>
                <td width="w-4">Jumlah</td>
              </tr>
            </thead>
            <tbody>
              <?php
              if (isset($item)) {
                $i=1;
                foreach ($item as $data_payment) {
                  ?>
                  <tr>
                    <td class="td-nom"><b><?php echo $i; ?>.</b></td>
                    <td>{{ $data_payment->Department_Name }}</td>
                    <td><?php echo substr($data_payment->Term_Year_Bill_Id, 0, -1) ?></td>
                    <td>
                      <?php
                      if ($data_payment->Installment_Order == "0") {
                        echo "Lunas";
                      }else {
                        echo $data_payment->Installment_Order;

                      }
                      ?>
                    </td>
                    <td>{{ $data_payment->Cost_Item_Name }}</td>
                    <td style="text-align:right;"><a href="{{asset('laporan/D_Mahasiswa_Item?Cost_Item_Id='.$data_payment->Cost_Item_Id.'&Term_Year_Id='.$data_payment->Term_Year_Bill_Id.'&PaymentOrders='.$data_payment->Installment_Order.'&Department_Id='.$data_payment->Department_Id.'&StartDate='.$StartDate.'&EndDate='.$EndDate)}}"><?php echo number_format($data_payment->Payment_Amount,0,',','.') ?></a></td>
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
