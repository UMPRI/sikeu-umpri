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
          <b>Edit</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <div class="row">
              <div class="col-md-6">
                  <form class="form-horizontal" method="POST" action="{{ asset('/set_biaya_registrasi_personal/edit_post/'.$fnc_cost_sched_personal->Cost_Sched_Personal_Id)}}">
                    {{ csrf_field() }}

                    <div class="form-horizontal">
                      <input type="hidden" name="Register_Number" value="<?php echo $acd_student->Register_Number ?>">
                      <input type="hidden" name="Term_Year_Id" value="<?php echo $mstr_term_year->Term_Year_Id ?>">
                      <div class="form-group">
                          <label for="Nama" class = "control-label col-md-4">Nama</Label>
                          <div class="col-md-8">
                              <input type="text" value="<?php echo $acd_student->Full_Name ?>" class="form-control form-control-sm" disabled="disabled" />
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="Nama" class = "control-label col-md-4">Th Akademik</Label>
                          <div class="col-md-8">
                              <input type="text" value="<?php echo $mstr_term_year->Term_Year_Name ?>" class="form-control form-control-sm" disabled="disabled" />
                          </div>
                      </div>

                      <div class="form-group panggil_date">
                          <label for="Nama" class = "control-label col-md-4">Mulai</Label>
                          <div class="col-md-5 col-sm-5">
                              <div class="input-group date">
                                <input type="text" name="Start_Date" class="form-control" id='datetimepicker1' value="<?php echo $fnc_cost_sched_personal->Start_Date ?>" readonly>
                              </div>
                          </div>
                      </div>
                      <script type="text/javascript">
                        $('#datetimepicker1').kendoDatePicker();
                      </script>
                      <div class="form-group panggil_date">
                          <label for="Nama" class = "control-label col-md-4">Selesai</Label>
                          <div class="col-md-5 col-sm-5">
                              <div class="input-group date">
                                <input type="text" name="End_Date" class="form-control " id='datetimepicker2' value="<?php echo $fnc_cost_sched_personal->End_Date ?>" readonly>
                              </div>
                          </div>
                      </div>
                      <script type="text/javascript">
                        $('#datetimepicker2').kendoDatePicker();
                      </script>
                      <div class="form-group">
                          <label for="Nama" class = "control-label col-md-4">Keterangan</Label>
                          <div class="col-md-8">
                              <textarea name="Explanation" class = "form-control"><?php echo $fnc_cost_sched_personal->Explanation ?></textarea>
                          </div>
                      </div>

                      {{-- <div class="form-group">
                          <div class="col-md-offset-4 col-md-8">
                              <button type="submit" name="submit" class="btn btn-success">
                                  Simpan cek <span class="glyphicon glyphicon-floppy-save" style="color:white" aria-hidden="true"></span>
                              </button>
                          </div>
                      </div> --}}
                    </div>
                  </form>
                  </div>
                  <input type="hidden" name="url" id="url" value="{{ asset('set_biaya_registrasi_personal/create?Register_Number='.$acd_student->Register_Number.'&Term_Year_Id='.$mstr_term_year->Term_Year_Id.'&Payment_Order=')}}">
                  <div class="col-md-6">
                      <div class="panel panel-default bootstrap-admin-no-table-panel">
                          <div class="panel-heading">
                              <div class="pull-right">
                                  <div class="tombol-gandeng">
                                      <a href="{{ asset('set_biaya_registrasi_personal_detail/create?Cost_Sched_Personal_Id='.$fnc_cost_sched_personal->Cost_Sched_Personal_Id)}}" class="btn-sm btn-success" style="font-style:italic;font-size:small;text-decoration:none;">Tambah data <i class="fa fa-plus"></i>&nbsp;</a>
                                  </div>
                              </div>
                              <br>
                              <div class="bootstrap-admin-box-title right text-success">
                                  <b>Detail</b>
                              </div>
                          </div>
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
                                      <th><i class="fa fa-cog"></i></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $no = 1;
                                    foreach ($fnc_cost_sched_personal_detail as $d_fnc_cost_sched_personal_Detail) {
                                      ?>
                                      <tr>
                                        <td><?php echo $no ?></td>
                                        <td><?php echo $d_fnc_cost_sched_personal_Detail->Cost_Item_Name ?></td>
                                        <td><?php echo $d_fnc_cost_sched_personal_Detail->Percentage ?></td>
                                        <td style="text-align:right;"><?php echo number_format($d_fnc_cost_sched_personal_Detail->Amount,0,',','.') ?></td>
                                        <td style="text-align:center;">
                                          <a href="{{ asset('set_biaya_registrasi_personal_detail/edit/'.$d_fnc_cost_sched_personal_Detail->Cost_Sched_Personal_Detail_Id)}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                                          <a href="{{ asset('set_biaya_registrasi_personal_detail/delete/'.$d_fnc_cost_sched_personal_Detail->Cost_Sched_Personal_Detail_Id)}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                        </td>
                                      </tr>
                                      <?php
                                      $no++;
                                    }
                                   ?>
                                </tbody>
                              </table>
                          </div>
                      </div>
                      <div style="text-align:right">
                          <br />
                          <a href="{{ asset('set_biaya_registrasi_personal/simpan?Register_Number='.$fnc_cost_sched_personal->Register_Number."&Term_Year_Id=".$fnc_cost_sched_personal->Term_Year_Id) }}" class="btn btn-success">
                            Simpan <span class="glyphicon glyphicon-floppy-save" style="color:white" aria-hidden="true"></span>
                          </a>
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
