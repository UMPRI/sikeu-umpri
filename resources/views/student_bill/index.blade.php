@extends('shared._layout')
@section('pageTitle', 'Student_Bill')
@section('content')

<?php
$access = auth()->user()->akses();
$acc = $access;
?>

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
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Index</b>
        </div>
      </div>
        <br>
        <div class="row">
          <div class="col-md-8">
            <label for="Term_Year_Id">Nim / No.Reg </label>
            <form class="form-inline" action="" method="GET">
              <div class="form-group mb-4">
                <div class="input-group">
                  <?php
                  if ($param != null) {
                    ?>
                    <input type="text" name="param" class="form-control form-control-sm col-sm-12" id="param" value="<?php echo $param ?>" >
                    <?php
                  }else{
                    ?>
                    <input type="text" name="param" class="form-control form-control-sm col-sm-12" id="param" >
                    <?php
                  }
                  ?>
                  <div class="input-group-btn">
                    <button type="submit" name="" class="btn btn-success"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="col-md-4">
            <?php
            if ($Student != null) {
              ?>
              <table class="table table-sm table-striped table-bordered">
                <tbody>
                  <tr><td>Nama</td><td>{{$Student->Full_Name}}</td></tr>
                  <tr><td>Nim</td><td>{{$Student->Nim}}</td></tr>
                  <tr><td>Prodi</td><td>{{$Student->Department_Name}}</td></tr>
                  @if($deposit > 0)
                  <tr><td>Deposit</td><td>Rp.{{number_format($deposit, 0, ',', '.')}}</td></tr>
                  @endif
                </tbody>
              </table>

            <?php }else{
              if (isset($Student)) {
                ?>
                <div class="alert alert-danger" style="font-size:11px;">
                  <b>Nim/Reg.Number Salah !</b><br>
                  Pastikan NIM/Reg.Number yang anda Inputkan benar
                </div>
                <?php
              }

            }?>
          </div>
        </div>
      </div>
      <hr>
      <div style="padding-left:2em; padding-top:1em;padding-right:2em">
          <div class="pull-right">
              <?php if($Student != null){ ?>
                  <div style="margin-top: -20px">
                    @if(in_array('Entry Pembayaran Mahasiswa-CanEditTagihan',$acc))<a class="btn btn-info" href="{{asset('Entry_Pembayaran_Mahasiswa/Edit/'.$param)}}" method="post" id="form"><i class="fa fa-edit"></i> Edit Tagihan</a>@endif
                  </div>
              <?php } ?>
          </div>
          <?php
            if (!isset($ListTagihan)) {
              $ListTagihan = null;
            }
            if ($ListTagihan != null || $ListTagihanKRS != null) {
                $sumAmount = 0;
                ?>
                  <!-- <form class="" action="{{asset('Entry_Pembayaran_Mahasiswa/Bayar')}}" method="post" id="Bayar"> -->
                      <?php if ($ListTagihan != null && count($ListTagihan) > 0){?>
                        Tagihan non-KRS
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="dataTagihan">
                          <thead class="thead-default thead-green">
                            <tr>
                              <th>
                                Tahun
                              </th>
                              <th>
                                Semester
                              </th>
                              <th>
                                Tanggal Tagih
                              </th>
                              <th>
                                Tahapan
                              </th>
                              <th>
                                Item Biaya
                              </th>
                              <th>
                                Jumlah
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            foreach ($ListTagihan as $tagihan) {
                              $ganjilgenap = substr($tagihan['Term_Year_Bill_Id'],-1);
                              ?>
                              <tr>

                                <td><?php echo substr($tagihan['Term_Year_Bill_Id'],0,4) ?></td>
                                <td><?php if( $ganjilgenap == 1){echo "Ganjil";}elseif( $ganjilgenap == 2){echo "Genap";} ?></td>
                                <td align="center"><?php echo date('d/M/Y', strtotime($tagihan['Start_Date']))." - ".date('d/M/Y', strtotime($tagihan['End_Date']))?></td>
                                <td><?php echo $tagihan['Payment_Order'] ?></td>
                                <td><?php echo $tagihan['Cost_Item_Name'] ?></td>
                                <td style="display:none">{{ $tagihan['Amount'] }}</td> {{-- tagihan--------- --}}
                                <td align="right"><?php echo number_format($tagihan['Amount'],'0',',','.') ?></td>
                              </tr>
                              <?php
                              $sumAmount += $tagihan['Amount'];
                            }
                            ?>
                            <tr>
                              <td colspan="5" align="right">
                                <b>Jumlah</b>
                              </td>
                              <td align="right" id="totalTagihan">

                                <?php echo $jumlah = number_format($sumAmount,'0',',','.') ?>
                              </td>
                              <td style="display:none" id="totalTagihanAsli">

                                <?php echo $jumlah = number_format($sumAmount,'0',',','.') ?>
                              </td>
                            </tr>
                            @foreach($cicilanNonKRS as $itemCicilanNonKRS)
                            <tr>
                              <?php 
                                $ganjilgenap = substr($itemCicilanNonKRS->Term_Year_Id,-1); 
                                $sumAmount = $sumAmount - $itemCicilanNonKRS->Amount;
                              ?>
                              <td>{{ substr($itemCicilanNonKRS->Term_Year_Id,0,4) }}</td>
                              <td><?php if( $ganjilgenap == 1){echo "Ganjil";}elseif( $ganjilgenap == 2){echo "Genap";} ?></td>
                              <td></td>
                              <td></td>
                              <td>Pembayaran Open</td>
                              <td align="right">- {{ number_format($itemCicilanNonKRS->Amount, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            @if(count($cicilanNonKRS) > 0)
                            <tr>
                              <td colspan="5" align="right">
                                <b>Jumlah Setelah Dikurangi Cicilan</b>
                              </td>
                              <td align="right" id="totalTagihan">

                                <?php echo $jumlah = number_format($sumAmount,'0',',','.') ?>
                              </td>
                              <td style="display:none" id="totalTagihanAsli">

                                <?php echo $jumlah = number_format($sumAmount,'0',',','.') ?>
                              </td>
                            </tr>
                            @endif
                          </tbody>
                        </table>
                      </div>
                      <br />
                      <br>
                      <div class="card" style="padding-top:0px !important;display:block;" id="cardOpenPaymentNonKRS">
                        <div class="card-header"><b>Form Pembayaran Open Payment</b></div>
                        <div class="card-body" style="padding: 5px;">
                          <form action="{{ route('open-payment.nonkrs') }}" method="POST" id="formOpenPaymentNonKRS">
                            {{ csrf_field() }}
                            <input type="hidden" name="param" value="{{ $param }}">
                            <input type="hidden" name="paymentReceiptId" value="{{ $receiptId }}">
                            <div class="form-horizontal">
                              <div class="row">
                                <div class="col-md-3">
                                  <i>Bank</i>
                                  <select class="form-control form-control-sm" name="bankId" id="bankId-openNonKRS" required>
                                    <option value="">-- Pilih Bank --</option>
                                    <?php
                                      foreach ($Bank as $bank) {
                                        ?>
                                        <option value="<?php echo $bank->Bank_Id ?>"><?php echo $bank->Bank_Name ?></option>
                                        <?php
                                      }
                                      ?>
                                  </select>
                                  <span style="color:red;display:none" id="warning_bank-openNonKRS">Bank Belum Dipilih</span>
                                </div>
                                <div class="col-md-2">
                                  <i>Th Akademik</i>
                                  <select class="form-control form-control-sm" name="termYearId" id="termYearId-openNonKRS" >
                                    <option value="">-- Pilih Tahun Akademik --</option>
                                    <?php
                                    if($th_aktif != null){
                                        foreach ($Term_Year as $d_Term_Year) {
                                          ?>
                                          <option value="<?php echo $d_Term_Year->Term_Year_Id ?>" <?php if($th_aktif->Term_Year_Id ==  $d_Term_Year->Term_Year_Id){ echo "selected"; }?>><?php echo $d_Term_Year->Term_Year_Name ?></option>
                                          <?php
                                        }
                                    }else{
                                        foreach ($Term_Year as $d_Term_Year) {
                                          ?>
                                          <option value="<?php echo $d_Term_Year->Term_Year_Id ?>"><?php echo $d_Term_Year->Term_Year_Name ?></option>
                                          <?php
                                        }
                                    }
                                      ?>
                                  </select>
                                  <span style="color:red;display:none" id="warning_thn2">Tahun Akademik Belum Dipilih</span>
                                </div>
                                <div class="col-md-2">
                                  <?php
                                  $Date_Now = Date('Y/m/d');
                                  ?>
                                  <i>Tgl Bayar</i>
                                  <input type="text" name="paymentDate" id='datetimepicker2' class="form-control form-control-sm" value="<?php echo $Date_Now ?>">
                                  <script type="text/javascript">
                                    $('#datetimepicker2').kendoDatePicker({
                                      uiLibrary: 'bootstrap4',
                                      format: "yyyy/MM/dd"
                                    });
                                  </script>
                                </div>
                                <div class="col-md-2">
                                  <i>Nominal</i>
                                  <input type="number" name="amount" id='amount-openNonKRS' class="form-control form-control-sm">
                                </div>
                                @if($deposit > 0)
                                <div class="col-md-2">
                                  <input type="checkbox" name="useDeposit" id="useDeposit-Trigger" value="1"><i>Gunakan Deposit ?</i>
                                  <input type="number" name="amountDeposit" id='amountDeposit-openNonKRS' value="0" class="form-control form-control-sm" readonly>
                                </div>
                                @endif
                              </div>
                              <div class="row">
                                <div class="col-md-12 text-center">
                                    <br />
                                    <a href="javascript:" title="Bayar" id="btnSubmitOpenPaymentNonKRS" class="btn btn-sm btn-success">
                                      Bayar Open Payment<i class="glyphicon glyphicon-ok" style="color:white"></i>
                                    </a>
                                </div>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                      @if($paymentReceipt != null)
                      <br>
                      <div class="card" style="padding-top:0px !important;display:block;" id="cardOpenPaymentNonKRS">
                        <div class="card-header"><b>Slip Bukti Pembayaran</b></div>
                        <div class="card-body" style="padding: 5px;">
                          @if($paymentReceipt->Is_Done)
                          <div class="alert alert-info">Sudah dilakukan pembayaran menggunakan bukti slip pembayaran ini</div>
                          @endif
                          <center>
                            <a href="{{ $receiptHost }}/storage{{ $paymentReceipt->File_Name }}" target="_blank">
                              <img src="{{ $receiptHost }}/storage{{ $paymentReceipt->File_Name }}" class="preview-payment-receipt img-thumbnail">
                            </a>
                          </center>
                        </div>
                      </div>
                      @endif
                      <?php } ?>

                      <?php if ($ListTagihanKRS != null){?>
                      <form class="" action="{{asset('Entry_Pembayaran_Mahasiswa/Bayar')}}" method="post" id="Bayar">
                        Tagihan KRS
                        <div class="table-responsive">
                          <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="dataTagihanKRS">
                            <thead class="thead-default thead-green">
                              <tr>
                                <th>
                                  Tahun
                                </th>
                                <th>
                                  Semester
                                </th>
                                <th>
                                  Tanggal Tagih
                                </th>
                                <th>
                                  Item Biaya
                                </th>
                                <th>
                                  Mata Kuliah
                                </th>
                                <th>
                                  SKS x Harga Per SKS
                                </th>
                                <th>
                                  Jumlah Nominal
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $sumAmountKRS =0;
                              foreach ($ListTagihanKRS as $tagihanKRS) {
                                $Deskripsi = "";
                                if ($tagihanKRS['Cost_Item_Id'] == 2) {
                                  $Deskripsi = $tagihanKRS['SKS']." x Rp.".number_format($tagihanKRS['perSKS'],'0',',','.');
                                  // $Deskripsi = ($tagihanKRS['Amount'] / $tagihanKRS['perSKS'])." x Rp.".number_format($tagihanKRS['perSKS'],'0',',','.');
                                }
                                $ganjilgenap = substr($tagihanKRS['Term_Year_Bill_Id'],-1);
                                ?>
                                <tr>

                                  <td><?php echo substr($tagihanKRS['Term_Year_Bill_Id'],0,4) ?></td>
                                  <td><?php if( $ganjilgenap == 1){echo "Ganjil";}elseif( $ganjilgenap == 2){echo "Genap";} ?></td>
                                  <td align="center"><?php echo date('d/M/Y', strtotime($tagihanKRS['Start_Date']))." - ".date('d/M/Y', strtotime($tagihanKRS['End_Date']))?></td>
                                  <td><?php echo $tagihanKRS['Cost_Item_Name'] ?></td>
                                  <td><?php echo $tagihanKRS['Course_Name'] ?></td>
                                  <td style="display:none">{{ $tagihanKRS['Amount'] }}</td> {{-- tagihan--------- --}}
                                  <?php if ($tagihanKRS['Cost_Item_Id'] != 105 && $tagihanKRS['Course_Id'] == null){ ?>
                                    <td><?php echo ($tagihanKRS['Amount'] / $tagihanKRS['perSKS']) ?> x <?php echo number_format($tagihanKRS['perSKS'],'0',',','.') ?></td>
                                  <?php } else{
                                    ?>
                                    <td><?php echo $tagihanKRS['SKS'] ?> SKS, Rp. <?php echo number_format($tagihanKRS['Amount'],'0',',','.') ?> (Paket)</td>
                                    <?php
                                  } ?>
                                  <td align="right"><?php echo number_format($tagihanKRS['Amount'],'0',',','.') ?></td>
                                </tr>
                                <?php
                                $sumAmount += $tagihanKRS['Amount'];
                                $sumAmountKRS += $tagihanKRS['Amount'];
                              }
                              ?>
                            </tbody>
                            <tfoot>
                              <tr>
                                <td colspan="6" align="right">
                                  <b>Jumlah</b>
                                </td>
                                <td align="right" id="totalTagihanKRS">

                                  <?php echo $jumlahKRS = number_format($sumAmountKRS,'0',',','.') ?>
                                </td>
                                <td style="display:none" id="totalTagihanKRSAsli">

                                  <?php echo $jumlahKRS = number_format($sumAmountKRS,'0',',','.') ?>
                                </td>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </form>
                    <br />
                  <?php }?>
                    <!-- </form> -->
              <?php 
          }
          else
          {
              if ($param != null)
              { ?>
                  <div style="text-align:center"><p>* Tidak ada Tagihan</p></div>
            <?php  }
          } ?>
        </div>
        <hr>
        <b>Riwayat Pembayaran Open</b>
        <div class="alert alert-info" style="font-size:11px;">
          Di bawah ini adalah daftar pembayaran - pembayaran open payment
        </div>
        <div class="table-responsive" >
          <table class="table table-striped table-bordered table-hover table-sm table-font-sm">
            <thead class="thead-default thead-green">
              <tr>
                <th width="w-1">No.</th>
                <th width="w-4">Semester</th>
                <th width="w-4">Tgl Bayar</th>
                <th width="w-4">Jumlah</th>
                <th width="w-4">Bank</th>
                <th width="w-4"><i class="glyphicon glyphicon-cog"></i></th>
              </tr>
            </thead>
            <tbody id="tbody-OpenPayment">
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3" style="text-align:center;">Total</td>
                <td style="text-align:right;" id="total-OpenPayment"></td>
                <td colspan="2"></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <hr>
        <b>Riwayat Pembayaran Lunas</b>
        <div class="alert alert-info" style="font-size:11px;">
          Di bawah ini adalah daftar pembayaran - pembayaran yang sudah lunas
        </div>
        <div class="table-responsive" >
          <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="listTagihan">
            <thead class="thead-default thead-green">
              <tr>
                <th width="w-1">No.</th>
                <th width="w-1">No Kwitansi</th>
                <th width="w-4">Tahun</th>
                <th width="w-4">Semester</th>
                <th width="w-4">Tgl Bayar</th>
                <th width="w-4">Jumlah</th>
                <th width="w-4">Bank</th>
                <th width="w-4"><i class="glyphicon glyphicon-cog"></i></th>
              </tr>
            </thead>
            <?php if ($Student_Payment != null) { ?>
            <tbody>
              <?php
              $no = 1 ;
              $sumpayment = 0;
                foreach ($Student_Payment as $payment) {
                  $ganjilgenapPayment = substr($payment->Term_Year_Bill_Id,-1);
                  $tahun = substr($payment->Term_Year_Bill_Id,0,4);
                  ?>
                  <tr>
                    <td><?php echo $no++ ?></td>
                    <td><?php echo $payment->No_Kwitansi ?></td>
                    <td><?php echo $tahun ?></td>
                    <td><?php if( $ganjilgenapPayment == 1){echo "Ganjil";}elseif( $ganjilgenapPayment == 2){echo "Genap";} ?></td>
                    <td>
                      <?php
                      if ($payment->Payment_Date != null) {
                        echo date("d F Y",strtotime($payment->Payment_Date));
                      }else{
                        echo "-";
                      }
                      ?>
                    </td>
                    <td style="text-align:right;"><?php echo number_format($payment->Payment_Amount,'0',',','.') ?></td>
                    <td><?php echo $payment->Bank_Name ?></td>
                    <td style="text-align:center;">
                      <a title="Lihat Details" class="btn btn-sm btn-info details" href="javascript:;" onclick="modal_show(<?php  echo $payment->Reff_Payment_Id ?>)"><i class="glyphicon glyphicon-search"></i></a>&nbsp;
                      <a title="Cetak" class="btn btn-sm btn-primary" href="{{ asset('riwayat_pembayaran_details/pdf/'.$payment->Reff_Payment_Id)}}" target="_blank">Bukti Terima</a>&nbsp;
                      <a title="Cetak" class="btn btn-sm btn-success" href="{{ asset('riwayat_pembayaran_details/kwitansi/'.$payment->Reff_Payment_Id)}}" target="_blank">Kuitansi</a>&nbsp;
                      <a title="Batal" class="btn btn-sm btn-warning" href="{{ asset('Entry_Pembayaran_Mahasiswa/cancel/'.$payment->Reff_Payment_Id.'?param='.$param)}}" ><i class="fa fa-warning"></i></a>
                    </td>
                  </tr>
                  <?php
                  $sumpayment += $payment->Payment_Amount;
                }
               ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3" style="text-align:center;">Total</td>
                <td style="text-align:right;"><?php echo number_format($sumpayment,'0',',','.') ?></td>
                <td colspan="2"></td>
              </tr>
            </tfoot>
          <?php }else{
            ?>
            <td colspan="5" style="text-align:center;">Tidak ada data</td>
            <?php
          } ?>
          </table>
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
      function thousandFormat(n) {
        var rx = /(\d+)(\d{3})/;
        return String(n).replace(/^\d+/, function (w) {
            while (rx.test(w)) {
                w = w.replace(rx, '$1.$2');
            }
            return w;
        });
      }
 
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

      function sebSweetConfirm(originLink) {

          var tagihan = $("#dataTagihan #totalTagihan").text();
          var urlKwitansi = $("#urlKwitansi").val();
          // alert(urlKwitansi);
          swal({
              title: 'Anda yakin?',
              text: "Melakukan Pembayaran sebesar Rp "+tagihan,
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ya, Bayar Tagihan!',
              cancelButtonText: 'Batal',
              //closeOnConfirm: true,
              //closeOnCancel: true
          },function (isConfirm) {
              if (isConfirm) {
                  $.ajax({
                      url: $('#Bayar').attr('action'),
                      type: 'POST',
                      data: $('#Bayar').serialize(),
                      success: function (d) {
                        //check is successfully save to database
                        if (d.status == 1) {
                          //will send status from server side
                          //alert('Successfully done.');
                          swal('Berhasil','Pembayaran Berhasil','success');
                          if(d.Reff_Payment_Id != null){
                            window.open('{{ asset('riwayat_pembayaran_details/kwitansi') }}/'+d.Reff_Payment_Id , '_blank');
                          }
                          location.reload();
                        } else {
                          swal('Gagal!','Kesalahan pada system.'+d.exp,'error');
                          // location.reload();
                        }
                      }
                  });
              }
          });
      }

        $('.confirmation').click(function (event) {
            var bank = $('#Bank_Id').val();
            var thn = $("#Term_Year_Id").val();
            var status = 0;
            var status_bnk = 0;
            var status_thn = 0;
            if (bank != "") {
              status = 1;
              $("#warning_bank").css("display","none");
            } else {
              status = 0;
              $("#warning_bank").css("display","inline");
            }
            if (thn != "") {
              status = 1;
              $("#warning_thn").css("display","none");
            } else {
              status = 0;
              $("#warning_thn").css("display","inline");
            }
            if(status == 1){
              event.preventDefault();
              var originLink = $(this).attr("href");
              sebSweetConfirm(originLink);
            }
        });

        // $('.goToDelay').change(function(){
        //   alert('cange');
        // });

        @if($accessToken != null && $accessToken != '')

          $(document).ready(function(){
            $.ajax({
              url: '{{route('open-payment.report.by-student', $Student->Register_Number)}}',
              type: 'GET',
              beforeSend: function (xhr) {
                  xhr.setRequestHeader('Authorization', 'Bearer {{$accessToken}}');
              },
              data: {},
              success: function (res) {
                
                if (res.status == true) {
                  console.log('res',res);
                  var tbody = "";
                  var i = 1;
                  $.each( res.data, function( key, value ) {
                    if(value.Amount > 0){
                      tbody = tbody + '<tr>'+
                      '<td width="w-1">'+i+'.</td>'+ // No.
                      '<td width="w-4">'+value.Term_Year_Name+'</td>'+ // Semester
                      '<td width="w-4">'+value.Payment_Date+'</td>'+ // Tgl Bayar
                      '<td width="w-4">'+value.Amount+'</td>'+ // Jumlah
                      '<td width="w-4">'+value.Bank_Name+'</td>'+ // Bank
                      '<td><button class="btn btn-sm btn-danger" onclick="cancelOpenPayment('+value.Student_Installment_Id+')">Batalkan</button></td>'+
                      '</tr>';
                    }else{
                      tbody = tbody + '<tr>'+
                      '<td width="w-1">'+i+'.</td>'+ // No.
                      '<td width="w-4">'+value.Term_Year_Name+'</td>'+ // Semester
                      '<td width="w-4">'+value.Payment_Date+'</td>'+ // Tgl Bayar
                      '<td width="w-4">'+value.Amount+'</td>'+ // Jumlah
                      '<td width="w-4">'+value.Bank_Name+'</td>'+ // Bank
                      '<td></td>'+
                      '</tr>';
                    }
                    i++;
                  });

                  console.log('tbody',tbody);

                  $('#tbody-OpenPayment').append(tbody);
                } else {
                  $('#tbody-OpenPayment').append("");
                }
              }
            });
          });

        @endif
    
    function cancelOpenPayment(id){
      swal({
        title: 'Anda yakin?',
        text: "Melakukan Pembatalan Pembayaran",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Tidak',
      },function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: "{{route('open-payment.nonkrs.delete')}}",
                type: "DELETE",
                cache: false,
                data: {
                  "id": id,
                  "_token": '{{ csrf_token() }}',
                  "_method": "DELETE"
                },
                success:function(response){ 

                    //show success message
                    swal({
                        type: 'success',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 3000
                    });

                    location.reload();
                }
            });
        }
      });
    }
  </script>
  <script type="text/javascript">
    var arr = []
    function add(accumulator, a) {
        return accumulator + a;
    }

    function thousandFormat(n) {
        var rx = /(\d+)(\d{3})/;
        return String(n).replace(/^\d+/, function (w) {
            while (rx.test(w)) {
                w = w.replace(rx, '$1.$2');
            }
            return w;
        });
    }

    var totalTagihanAsli = $("#totalTagihanAsli").text();

    $('.goToDelay').change(function(){
      if ($(this).is(':checked')) {
        var tagihancek = parseInt($(this).closest("tr").find("td").eq(5).html());

        arr.push(tagihancek); // isi array nya

        const total = arr.reduce(add,0); // tambah datanya
        $('#totalTagihan').html(thousandFormat(total));
        // alert(total);
      }else{
        // alert("not checked");
        var tagihancek = parseInt($(this).closest("tr").find("td").eq(5).html());
        arr.splice($.inArray(tagihancek, arr),1);

        const total = arr.reduce(add,0);

        $('#totalTagihan').html(thousandFormat(total));
        // alert("checked");
      }

      // fungsi simpan data ke dB
    });

    $(document).ready(function(){

      @if($deposit > 0)
      $('#useDeposit-Trigger').click(function(){
        if ($('#amountDeposit-openNonKRS').val() == 0){
          $('#amountDeposit-openNonKRS').val({{ $deposit }});
        }else{
          $('#amountDeposit-openNonKRS').val(0);
        }
      })
      @endif

      $('#btnOpenPaymentNonKRS').click(function(){
        if($('#cardOpenPaymentNonKRS').css('display') == "none"){
          $('#cardOpenPaymentNonKRS').css('display', 'block');
        }else{
          $('#cardOpenPaymentNonKRS').css('display', 'none');
        }
      });

      $('#btnSubmitOpenPaymentNonKRS').click(function(e){
        e.preventDefault();

        var nominalOpenNonKRS = $('#amount-openNonKRS').val();
        var nominalDeposit = 0;

        if($('#amountDeposit-openNonKRS').length){
          nominalDeposit = $('#amountDeposit-openNonKRS').val();
        }

        totalNominal = (nominalOpenNonKRS - 0) + (nominalDeposit - 0);

        swal({
            title: 'Anda yakin?',
            text: "Melakukan pembayaran open sebesar Rp "+thousandFormat(totalNominal) ,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Bayar',
            cancelButtonText: 'Batal',
            //closeOnConfirm: true,
            //closeOnCancel: true
        },function (isConfirm) {

            if (isConfirm) {

                $.ajax({
                    url: $('#formOpenPaymentNonKRS').attr('action'),
                    type: 'POST',
                    data: $('#formOpenPaymentNonKRS').serialize(),
                    success: function (res) {
                      if (res.status == true) {
                        swal({
                          title: "Berhasil!",
                          text: res.message,
                          icon: "success",
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'OK',
                        },function (isConfirm) {
                          location.reload();
                        }); 
                      } else {
                        console.log("open payment non-krs res",res);
                        swal({
                          title: "Gagal",
                          text: res.message,
                          icon: "error",
                          timer: 3000,
                        }); 
                      }
                    }
                });
            }
        });
      });
    });

  </script>
</section>
@endsection
