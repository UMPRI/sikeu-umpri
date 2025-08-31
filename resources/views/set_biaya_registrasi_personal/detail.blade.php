@extends('shared._layout')
@section('pageTitle', 'Set_Biaya_Registrasi_Personal')
@section('content')

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
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ asset('/set_biaya_registrasi_personal?param='.$acd_student->Register_Number.'&Term_Year_Id='.$mstr_term_year->Term_Year_Id)}}" class="btn btn-danger btn-sm">Kembali <i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Detail</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <div class="row" style="font-size:11px;">
              <div class="col-md-6">

                    <div class="form-horizontal">
                      <div class="form-group">
                          <div class="col-md-12">
                            Nama:
                              <?php echo $acd_student->Full_Name ?>
                          </div>
                      </div>

                      <div class="form-group">
                          <div class="col-md-12">
                            Th Akademik:
                              <?php echo $mstr_term_year->Term_Year_Name ?>
                          </div>
                      </div>

                      <div class="form-group">
                          <div class="col-md-12">
                            Tahap Ke:
                            <?php
                            if($fnc_cost_sched_personal->Payment_Order == 0){
                              ?>
                              Lunas

                              <?php
                            }else{
                              ?>
                              <?php echo $fnc_cost_sched_personal->Payment_Order ?>
                              <?php
                            } ?>
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-horizontal">
                      <div class="form-group panggil_date">
                          <div class="col-md-12 col-sm-12">
                            Tanggal Tagih:
                                <?php
                                echo date_format(new DateTime($fnc_cost_sched_personal->Start_Date),"d-m-Y"); ?>
                            -
                                <?php
                                echo date_format(new DateTime($fnc_cost_sched_personal->End_Date),"d-m-Y"); ?>
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-md-12">
                            Keterangan:
                              <?php echo $fnc_cost_sched_personal->Explanation ?>
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                      <div class="panel panel-default bootstrap-admin-no-table-panel">
                          <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
                              <table class="table table-striped table-bordered table-hover table-sm table-font-sm">
                                <thead class="thead-default thead-green">
                                  <tr>
                                      <th>No</th>
                                      <th>
                                          Nama Biaya
                                      </th>
                                      <th>
                                          Diskon
                                      </th>
                                      <th>
                                          Biaya
                                      </th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $total = 0;
                                  $no = 1;
                                    foreach ($fnc_cost_sched_personal_detail as $d_Fnc_Cost_Sched_Personal_Detail) {
                                      ?>
                                      <tr>
                                        <td><?php echo $no ?></td>
                                        <td><?php echo $d_Fnc_Cost_Sched_Personal_Detail->Cost_Item_Name ?></td>
                                        <td><?php echo $d_Fnc_Cost_Sched_Personal_Detail->Percentage ?></td>
                                        <td style="text-align:right;"><?php echo number_format($d_Fnc_Cost_Sched_Personal_Detail->Amount,0,',','.') ?></td>
                                      </tr>
                                      <?php
                                      $no++;
                                      $total += $d_Fnc_Cost_Sched_Personal_Detail->Amount;
                                    }
                                   ?>
                                   <tr>
                                     <td colspan="3" style="text-align:center;"><b>Total :</b></td>
                                     <td style="text-align:right;"><b><?php echo number_format($total,0,',','.') ?></b></td>
                                   </tr>
                                </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
            </div>
            <script type="text/javascript">
              function initializeRemotelyValidatingElementsWithAdditionalFields($form) {
                  var remotelyValidatingElements = $form.find("[data-val-remote]");

                  $.each(remotelyValidatingElements, function (i, element) {
                      var $element = $(element);

                      var additionalFields = $element.attr("data-val-remote-additionalfields");

                      if (additionalFields.length == 0) return;

                      var rawFieldNames = additionalFields.split(",");

                      var fieldNames = $.map(rawFieldNames, function (fieldName) { return fieldName.replace("*.", ""); });

                      $.each(fieldNames, function (i, fieldName) {
                          $form.find("#" + fieldName).change(function () {
                              // force re-validation to occur
                              $element.removeData("previousValue");
                              $element.valid();
                          });
                      });
                  });
              }

              $(document).ready(function () {
                  initializeRemotelyValidatingElementsWithAdditionalFields($("#myFormId"));
              });
            </script>
            <script>
              function SelectionChanged() {
                  var PaymentOrder = $("#Payment_Order").val();
                  var asset = $("#url").val();
                  var url = asset+PaymentOrder;
                  url = url.replace(-1, PaymentOrder); //replace -1 with CourseId
                  window.location = url;
              }
            </script>
      </div>
    </div>
  </div>
</section>
@endsection
