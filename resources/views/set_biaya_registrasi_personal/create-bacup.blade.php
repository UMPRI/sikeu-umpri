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
          <a href="{{ asset('/set_biaya_registrasi_personal?param='.$Student->Register_Number.'&Term_Year_Id='.$Term_Year->Term_Year_Id)}}" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Create</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <div class="row">
              <div class="col-md-6">
                  <form class="form-horizontal" method="POST" action="create_post">
                    {{ csrf_field() }}

                    <div class="form-horizontal">
                      <input type="hidden" name="Register_Number" value="<?php echo $Student->Register_Number ?>">
                      <input type="hidden" name="Term_Year_Id" value="<?php echo $Term_Year->Term_Year_Id ?>">
                      <div class="form-group">
                          <label for="Nama" class = "control-label col-md-4">Nama</Label>
                          <div class="col-md-8">
                              <input type="text" value="<?php echo $Student->Full_Name ?>" class="form-control form-control-sm" disabled="disabled" />
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="Nama" class = "control-label col-md-4">Th Akademik</Label>
                          <div class="col-md-8">
                              <input type="text" value="<?php echo $Term_Year->Term_Year_Name ?>" class="form-control form-control-sm" disabled="disabled" />
                          </div>
                      </div>

                      <div class="form-group panggil_date">
                          <label for="Nama" class = "control-label col-md-4">Mulai</Label>
                          <div class="col-md-5 col-sm-5">
                              <div class="input-group date">
                                <?php
                                if(isset($Start_Date)){
                                  ?>
                                  <input type="text" name="Start_Date" id='datetimepicker1' class="form-control " value="<?php echo Date('m/d/Y',strtotime($Start_Date)) ?>">
                                  <?php
                                }else{
                                  ?>
                                  <input type="text" name="Start_Date" id='datetimepicker1' class="form-control " value="">
                                <?php } ?>
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
                                <?php
                                if(isset($End_Date)){
                                  ?>
                                  <input type="text" name="End_Date" id='datetimepicker2' class="form-control " value="<?php echo Date('m/d/Y',strtotime($End_Date)) ?>">
                                  <?php
                                }else{
                                  ?>
                                  <input type="text" name="End_Date" id='datetimepicker2' class="form-control " value="">
                                <?php } ?>
                              </div>
                          </div>
                      </div>
                      <script type="text/javascript">
                          $('#datetimepicker2').kendoDatePicker();
                      </script>
                      <div class="form-group">
                          <label for="Nama" class = "control-label col-md-4">Keterangan</Label>
                          <div class="col-md-8">
                              <textarea name="Explanation" class = "form-control"></textarea>
                          </div>
                      </div>

                      <div class="form-group">
                          <div class="col-md-offset-4 col-md-8">
                              <button type="submit" name="submit" class="btn btn-success">
                                  Simpan <span class="glyphicon glyphicon-floppy-save" style="color:white" aria-hidden="true"></span>
                              </button>
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
                                          Persen
                                      </th>
                                      <th>
                                          Biaya
                                      </th>
                                      <th></th>
                                  </tr>
                                </thead>
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
