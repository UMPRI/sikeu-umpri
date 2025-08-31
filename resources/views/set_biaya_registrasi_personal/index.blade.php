@extends('shared._layout')
@section('pageTitle', 'Set_Biaya_Registrasi_Personal')
@section('content')

<?php
  $access = auth()->user()->akses();
  $acc = $access;
?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Setting Biaya Registrasi Personal</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right">
            <?php
            if (isset($q_Student))
            {
                ?>
                <div class="margin-badge">
                  <p class="badge badge-warning">Nama : <?php echo $q_Student->Full_Name ?></p><br />
                  <p class="badge badge-warning">Prodi : <?php echo $q_Student->Department_Name ?></p>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Set Biaya Registrasi Personal</b>
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
              <option value="">--- Pilih Tahun / Semester ---</option>
              <?php
                foreach ($q_Term_Year as $Term_Year) {
                  ?>
                    <option value="<?php echo $Term_Year->Term_Year_Id ?>" <?php if($Term_Year->Term_Year_Id == $Term_Year_Id){ echo "selected";} ?>><?php echo $Term_Year->Term_Year_Name ?></option>
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
        <?php
        if (isset($q_Student)) {
         ?>

        <div class="panel panel-default bootstrap-admin-no-table-panel">
                <div class="panel-heading">
                    <div class="pull-right">
                        <div class="tombol-gandeng">
                          @if(in_array('Set Biaya Registrasi Personal-CanAdd',$acc))
                            <a href="{{ asset('set_biaya_registrasi_personal/create?Register_Number='.$q_Student->Register_Number.'&Term_Year_Id='.$Term_Year_Id)}}" class="btn-sm btn-success" style="font-style:italic;font-size:small;text-decoration:none;">Tambah data <i class="fa fa-plus"></i> &nbsp;</a>
                          @endif
                        </div>
                    </div>

                    {{-- Hitung tagihan --}}
                    <div class="">
                       <a href="{{ asset('set_biaya_registrasi_personal/simpan?Register_Number='.$q_Student->Register_Number."&Term_Year_Id=".$Term_Year_Id) }}" class="btn btn-warning tombol-gandeng">
                         Hitung Tagihan <span class="fa fa-list-alt" style="color:black" aria-hidden="true"></span>
                       </a>
                   </div>

                   {{-- Hitung tagihan --}}
                    <div class="bootstrap-admin-box-title right text-success">
                        Jadwal Khusus Pembayaran
                    </div>
                </div>
                <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-sm table-font-sm">
                      <thead class="thead-default thead-green">
                        <tr >
                            <th>
                                Th Akademik
                            </th>
                            <th>
                                Tgl Tagih
                            </th>
                            <th>
                                Keterangan
                            </th>
                            <th>
                                Jumlah
                            </th>
                            <th><i class="glyphicon glyphicon-cog" style="color:white"></i></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $array_sum = array();
                        foreach ($fnc_Cost_Sched_Personal as $d_fnc_Cost_Sched_Personal) {
                          $array_sum[] = $d_fnc_Cost_Sched_Personal->Amount;
                        ?>
                            <tr>
                                <td>
                                    {{$d_fnc_Cost_Sched_Personal->Year_Id}}
                                </td>
                                <td align="center" style="white-space:nowrap">
                                  <?php
                                  echo date_format(new DateTime($d_fnc_Cost_Sched_Personal->Start_Date),"d F Y"); ?>
                                  <b> - </b>
                                  <?php
                                  echo date_format(new DateTime($d_fnc_Cost_Sched_Personal->End_Date),"d F Y"); ?>
                                </td>
                                <td>
                                    {{$d_fnc_Cost_Sched_Personal->Explanation}}
                                </td>
                                <td align="right" style="white-space:nowrap">
                                    <?php echo number_format($d_fnc_Cost_Sched_Personal->Amount, 0,',','.') ?>
                                </td>
                                <td align="center" style="white-space:nowrap">
                                    @if(in_array('Set Biaya Registrasi Personal-CanEdit',$acc))
                                      <a href="{{ asset('/set_biaya_registrasi_personal/edit/'.$d_fnc_Cost_Sched_Personal->Cost_Sched_Personal_Id)}}" title="Ubah" class="btn btn-sm btn-warning">
                                          <i class="fa fa-edit" style="color:white"></i>
                                      </a>&nbsp;
                                    @endif

                                    @if(in_array('Set Biaya Registrasi Personal-CanDelete',$acc))
                                      <input type="hidden" id="urldelete<?php echo $d_fnc_Cost_Sched_Personal->Cost_Sched_Personal_Id ?>" name="" value="{{ asset('/set_biaya_registrasi_personal/delete/'.$d_fnc_Cost_Sched_Personal->Cost_Sched_Personal_Id)}}">

                                      <a href="javascript:" title="Hapus" class="btn btn-sm btn-danger confirmation" onclick="confirmdelete(<?php echo $d_fnc_Cost_Sched_Personal->Cost_Sched_Personal_Id ?>)">
                                          <i class="fa fa-trash" style="color:white"></i>
                                      </a>&nbsp;
                                    @endif


                                    <a href="{{ asset('/set_biaya_registrasi_personal/detail/'.$d_fnc_Cost_Sched_Personal->Cost_Sched_Personal_Id)}}" title="Detail" class="btn btn-sm btn-info">
                                        <i class="fa fa-list" style="color:white"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                          }
                            $sumTotalAmountCsp = array_sum($array_sum);
                          ?>
                        <tr>
                            <td colspan="3" align="center"><b>Total</b></td>
                            <td align="right">
                                <b><?php
                                if (isset($sumTotalAmountCsp)) {
                                  echo number_format($sumTotalAmountCsp, 0,',','.');
                                }
                                ?></b>
                            </td>
                            <td></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default bootstrap-admin-no-table-panel">
                        <div class="panel-heading">
                            <div class="pull-right">

                            </div>
                            <div class="bootstrap-admin-box-title right text-success">
                                Jadwal Seharusnya
                            </div>
                        </div>
                        <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
                          <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-sm table-font-sm">
                              <thead class="thead-default thead-green">
                                <tr >
                                    <th>Ke</th>
                                    <th>Th/Smt</th>
                                    <th>Tgl Tagih</th>
                                    <th>Jumlah</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $sumTotalAmountCs = 0;
                                $array_sum2 = array();
                                if ($Cost_Sched != null)
                                {
                                    if ($Count_Cost_Sched > 0)
                                    {
                                        foreach ($Cost_Sched as $d_Cost_Sched)
                                        {
                                          $array_sum2[] = $d_Cost_Sched->Total_Amount;
                                          ?>
                                            <tr>
                                                <td align="center">
                                                  <?php
                                                    if ($d_Cost_Sched->Payment_Order == 0)
                                                    {
                                                        ?><i>Lunas</i><?php
                                                    }
                                                    else
                                                    {
                                                        echo $d_Cost_Sched->Payment_Order;
                                                    }
                                                    ?>
                                                </td>
                                                <td align="center">
                                                    {{$d_Cost_Sched->Term_Year_Name}}
                                                </td>
                                                <td style="text-align:center;"><?php echo Date('d-m-Y',strtotime($d_Cost_Sched->Start_Date)) ?> - <?php echo Date('d-m-Y',strtotime($d_Cost_Sched->End_Date)) ?></td>
                                                <td align="right">
                                                    <?php echo number_format($d_Cost_Sched->Total_Amount,0,',','.') ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        $sumTotalAmountCs = array_sum($array_sum2);
                                    }
                                    else
                                    {
                                      ?>
                                        <tr>
                                            <td colspan="4" align="center">
                                                <p>Belum Disetting.</p>
                                                silahkan
                                                <a href="{{ asset('set_biaya_registrasi/create?Term_Year_Id='.$Term_Year_Id.'&Entry_Year_Id='.$q_Student->Entry_Year_Id.'&Entry_Period_Type_Id='.$q_Student->Entry_Period_Type_Id.'&Department_Id='.$q_Student->Department_Id.'&Class_Prog_Id='.$q_Student->Class_Prog_Id)}}" class="text-success">
                                                    Set Biaya Registrasi
                                                </a>&nbsp;
                                                terlebih dahulu.
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                <tr>
                                    <td colspan="3" align="center"><b>Total</b></td>
                                    <td align="right">
                                        <b><?php echo number_format($sumTotalAmountCs,0,',','.')?></b>
                                    </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default bootstrap-admin-no-table-panel">
                        <div class="panel-heading">
                            <div class="pull-right">

                            </div>
                            <div class="bootstrap-admin-box-title right text-success">
                                Riwayat Pembayaran
                            </div>
                        </div>
                        <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
                          <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="CustomerGrid">
                              <thead class="thead-default thead-green">
                                <tr >
                                    <th style="display:none"></th>
                                    <th>No</th>
                                    <th>Ke</th>
                                    <th>Tgl Bayar</th>
                                    <th>Jumlah</th>
                                    <th><i class="glyphicon glyphicon-cog"></i></th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                    $no = 1;
                                    $sumPayment_Amount = 0;
                                    $array_sum3 = array();
                                    if ($Student_Payment != null)
                                    {
                                        foreach ($Student_Payment as $d_fnc_Student_Payment)
                                        {
                                          $array_sum3[] = $d_fnc_Student_Payment->Total_Amount;
                                           ?>
                                            <tr>
                                                <td style="display:none">{{$d_fnc_Student_Payment->Reff_Payment_Id}}</td>
                                                <td><?php echo $no ?></td>
                                                <td align="center">
                                                    <?php if ($d_fnc_Student_Payment->Installment_Order == "0"){ ?>
                                                        <i>Lunas</i>
                                                    <?php }else{
                                                        echo $d_fnc_Student_Payment->Installment_Order;
                                                    }?>
                                                </td>
                                                <td align="center" style="white-space:nowrap">
                                                    {{$d_fnc_Student_Payment->Payment_Date}}
                                                </td>
                                                <td align="right">
                                                    <?php echo number_format($d_fnc_Student_Payment->Total_Amount,0,',','.') ?>
                                                </td>
                                                <td align="center" style="white-space:nowrap">
                                                    <a title="Lihat Details" class="btn btn-sm btn-info details" href="javascript:;" onclick="modal_show(<?php  echo $d_fnc_Student_Payment->Reff_Payment_Id ?>)"><i class="glyphicon glyphicon-search"></i></a>&nbsp;
                                                    <a title="Cetak" class="btn btn-sm btn-success cetak" href="{{ asset('riwayat_pembayaran_details/pdf/'.$d_fnc_Student_Payment->Reff_Payment_Id)}}" target="_blank"><i class="glyphicon glyphicon-print"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                            $no++;
                                        }
                                        $sumPayment_Amount = array_sum($array_sum3);
                                    }
                                ?>
                                <tr>
                                    <td colspan="3" align="center"><b>Total</b></td>
                                    <td align="right">
                                        <b><?php echo number_format($sumPayment_Amount,0,',','.') ?></b>
                                    </td>
                                    <td></td>
                                </tr>
                              </tbody>
                            </table>
                            <!-- <input type="hidden" id="url_riwayat" name="" value="{{asset('riwayat_pembayaran_details?ReffPaymentId=')}}"> -->
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
                            <script type="text/javascript">

                            </script>
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
                        </div>
                    </div>
                </div>
            </div>
          <?php
        } ?>
      </div>
    </div>
  </div>
  <script type="text/javascript">
  function confirmdelete(param) {
    var href = $("#urldelete"+param).val();
    swal({
      title: "Apa anda yakin?",
      text: "Data akan terhapus , anda tidak akan bisa mengembalikan data yang sudah di hapus",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Ya, Hapus!",
      cancelButtonText: "Batal Hapus!",
      closeOnConfirm: false
    },function(){
      window.location.href = href;
    });
  }
  </script>
</section>
@endsection
