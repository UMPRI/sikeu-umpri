@extends('shared._layout')
@section('pageTitle', 'Detail_Pembayaran_Mahasiswa_Per_Item')
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
          <a href="javascript:history.back()" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Detail Pembayaran Mahasiswa Per Item</b>
        </div>
      </div>
      <br>
          <div class="row">
              <div class="col-md-6">
                <table>
                  <tr>
                    <td>Th/Smt</td>
                    <td>: <?php echo $mstr_term_year->Term_Year_Name ?></td>
                  </tr>
                  <tr>
                    <td>Tahap</td>
                    <td>: <?php echo $Payment_Order ?></td>
                  </tr>
                  <tr>
                    <td>Item Pembayaran</td>
                    <td>: <?php echo $fnc_cost_item->Cost_Item_Name ?></td>
                  </tr>
                </table>
              </div>
              <div class="col-md-6" >
                <table style="float:right;">
                  <tr>
                    <td>Dari</td>
                    <td>: <?php echo Date("d-m-Y",strtotime($Start_Date)); ?></td>
                  </tr>
                  <tr>
                    <td>Sampai</td>
                    <td>: <?php echo Date("d-m-Y",strtotime($End_Date)); ?></td>
                  </tr>
                  <tr>
                    <td colspan="2" style="text-align:right;"><button id="btnExport" onclick="fnExcelReport();" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export to Excel</button></td>
                  </tr>
                </table>
              </div>
          </div>
        </div>
        <hr>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="tbl">
            <thead class="thead-default thead-green">
              <tr>
                <th>No</th>
                <th>Nim</th>
                <th>Nama</th>
                <th>Prodi</th>
                <th>Item Pembayaran</th>
                <th>Tahap Ke</th>
                <th>Jumlah Pembayaran</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sum = 0;
              if (isset($lap)) {
                $i=1;
                foreach ($lap as $data_payment) {
                  ?>
                  <tr>
                    <td class="td-nom"><b><?php echo $i; ?>.</b></td>
                    <td>{{ $data_payment->Nim }}</td>
                    <td>{{ $data_payment->Full_Name }}</td>
                    <td>{{ $data_payment->Department_Name }}</td>
                    <td>{{ $data_payment->Cost_Item_Name }}</td>
                    <td>
                      <?php
                      if ($data_payment->Installment_Order == "0") {
                        echo "Lunas";
                      }else {
                        echo $data_payment->Installment_Order;

                      }
                      ?>
                    </td>
                    <td style="text-align:right;"><?php echo number_format($data_payment->Payment_Amount,0,',','.') ?></td>
                  </tr>
                  <?php
                  $i++;
                  $sum += $data_payment->Payment_Amount;
                }
              } ?>
              <tr>
                <td colspan="6" style="text-align:center;">Total</td>
                <td style="text-align:right;"><?php echo number_format($sum,0,',','.') ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
    function fnExcelReport() {
        var tab_text = "<table border='2px'><tr><td colspan='7' align='center'>Detail Laporan Pembayaran Mahasiswa Per Item</td></tr><tr bgcolor='#87AFC6'>";
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
