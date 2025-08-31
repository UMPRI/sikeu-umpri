@extends('shared._layout')
@section('pageTitle', 'Set_Biaya_Registrasi_Personal')
@section('content')

<!-- Main content -->
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Set Biaya Registrasi Personal(Detail)</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ asset('/set_biaya_registrasi_personal/edit/'.$Cost_Sched_Personal_Id)}}" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Create</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <div class="row">
              <div class="col-md-12">
                  <form class="form-horizontal" method="POST" action="create_post">
                    {{ csrf_field() }}
                      <input type="hidden" name="Cost_Sched_Personal_Id" value="<?php echo $Cost_Sched_Personal_Id ?>">
                      <div class="form-group">
                          <label for="Nama" class = "control-label col-md-4">Nama Biaya</Label>
                          <div class="col-md-3">
                              <input type="hidden" name="url" id="url" value="{{ asset('set_biaya_registrasi_personal_detail/create?Cost_Sched_Personal_Id='.$Cost_Sched_Personal_Id.'&Cost_Item_Id=')}}">
                              <select class="form-control form-control-sm" name="Cost_Item_Id" onchange="SelectionChanged()" id="Cost_Item_Id">
                                <option value="">-- Pilih Biaya --</option>
                                <?php
                                foreach ($fnc_cost_item as $d_Cost_Item) {
                                  ?>
                                  <option value="<?php echo $d_Cost_Item->Cost_Item_Id ?>" <?php if($d_Cost_Item->Cost_Item_Id == $Cost_Item_Id){ echo "selected";} ?>><?php echo $d_Cost_Item->Cost_Item_Name ?></option>
                                  <?php
                                }
                                 ?>
                              </select>
                          </div>
                      </div>
                      <?php
                      if ($Amount != 0) {
                        ?>
                        <div class="form-group panggil_date">
                            <label for="Nama" class = "control-label col-md-4">Biaya Normal</Label>
                            <div class="col-md-5 col-sm-5">
                                <div class="input-group date">
                                  <input type="number" name="Normal_Amount" value="<?php echo $Amount ?>" min="0" required class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <?php
                      }
                      ?>

                      <div class="form-group panggil_date">
                          <label for="Nama" class = "control-label col-md-4">Biaya Yang Harus Di Bayar</Label>
                          <div class="col-md-5 col-sm-5">
                              <div class="input-group date">
                                <input type="number" name="Amount" value="0" min="0" required class="form-control form-control-sm">
                              </div>
                          </div>
                      </div>

                      <?php
                      if ($Amount != 0) {
                        ?>
                        <div class="form-group panggil_date">
                            <label for="Nama" class = "control-label col-md-4">Diskon</Label>
                            <div class="col-md-5 col-sm-5">
                                <div class="input-group date">
                                  <input type="number" name="Percentage" value="0" min="0" max="100" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <?php
                      }
                      ?>

                      <div class="form-group">
                          <div class="col-md-offset-4 col-md-8">
                              <button type="submit" name="submit" class="btn btn-success">
                                  Simpan <span class="glyphicon glyphicon-floppy-save" style="color:white" aria-hidden="true"></span>
                              </button>
                          </div>
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
                  var Cost_Item_Id = $("#Cost_Item_Id").val();
                  var asset = $("#url").val();
                  var url = asset+Cost_Item_Id;
                  url = url.replace(-1, Cost_Item_Id); //replace -1 with CourseId
                  window.location = url;
              }
            </script>
      </div>
    </div>
  </div>
</section>
@endsection
