@extends('shared._layout')
@section('pageTitle', 'Set_Biaya_Registrasi')
@section('content')

<!-- Main content -->
<section class="content">
  <style media="screen">
  span.error {
      display: block;
      visibility: hidden;
      color: red;
      font-size: 90%;
  }
  </style>
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Setting Biaya Registrasi</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ asset('/set_biaya_registrasi?Term_Year_Id='.$Term_Year_Id.'&Entry_Year_Id='.$Entry_Year_Id.'&Entry_Period_Type_Id='.$Entry_Period_Type_Id)}}" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Create</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <div class="row">
              <div class="col-md-6">
                <div class="panel panel-default panel-green" >
                    <div class="panel-heading" style="padding:10px 20px;">
                      <div class="pull-right">
                      </div>
                      <div class="bootstrap-admin-box-title right">
                          <b>Master</b>
                      </div>
                  </div>
                  <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content" style="background-color:white;padding:10px;color:#333;">
                    <div class="form-horizontal">
                      <form class="form-horizontal" method="POST" action="create_post">
                        {{ csrf_field() }}

                          <div class="form-group">
                              <label class = "control-label col-md-4">Th Akademik</label>
                              <div class="col-md-12">
                                  <input type="text" value="<?php echo $Term_Year->Term_Year_Name ?>" disabled class="form-control form-control-sm">
                                  <input type="Hidden" value="<?php echo $Term_Year->Term_Year_Id ?>" name="Term_Year_Id" id="Term_Year_Id" required>
                              </div>
                          </div>

                          <div class="form-group">
                              <label class = "control-label col-md-4">Angkatan</label>
                              <div class="col-md-12">
                                  <input type="text" value="<?php echo $Entry_Year->Entry_Year_Id ?>" disabled class="form-control form-control-sm">
                                  <input type="Hidden" value="<?php echo $Entry_Year->Entry_Year_Id ?>" name="Entry_Year_Id" id="Entry_Year_Id" required>
                              </div>
                          </div>

                          <div class="form-group">
                              <label class = "control-label col-md-4">Aturan</label>
                              <div class="col-md-12">
                                  <input type="text" value="<?php echo $Entry_Period_Type->Entry_Period_Type_Name ?>" disabled class="form-control form-control-sm">
                                  <input type="Hidden" value="<?php echo $Entry_Period_Type->Entry_Period_Type_Id ?>" name="Entry_Period_Type_Id" id="Entry_Period_Type_Id" required>
                              </div>
                          </div>
                          <?php
                          if ($d_Departments != null && $d_ClassProgs != null)
                          {
                              $Department_Id = $d_Departments->Department_Id;
                              $Class_Prog_Id = $d_ClassProgs->Class_Prog_Id;
                              ?>
                              <input type="hidden" name="Payment_Order" value="1" id="Payment_Order" required readonly>
                              <div class="form-group">
                                  <label class = "control-label col-md-4">Prodi</label>
                                  <div class="col-md-12">
                                      <input type="text" value="<?php echo $d_Departments->Department_Name." - ".$d_Departments->Acronym ?>" disabled class="form-control form-control-sm">
                                      <input type="hidden" name="Department_Id" id="Department_Id" value="<?php echo $Department_Id ?>" required>
                                  </div>
                              </div>

                              <div class="form-group">
                                  <label class = "control-label col-md-4"></label>
                                  <div class="col-md-12">
                                      <input type="text" value="<?php echo $d_ClassProgs->Class_Program_Name ?>" disabled class="form-control form-control-sm">
                                      <input type="hidden" name="Class_Prog_Id" id="Class_Prog_Id" value="<?php echo $Class_Prog_Id ?>" required>
                                  </div>
                              </div>
                          <?php }
                          else
                          { ?>

                              <input type="hidden" name="Payment_Order" value="1" id="Payment_Order" required readonly>

                              <div class="form-group">
                                  <label class = "control-label col-md-4">Prodi</label>
                                  <div class="col-md-12">
                                      <select class="form-control form-control-sm" name="Department_Id" id="Department_Id" required>
                                        <option value="">-- Pilih Prodi --</option>
                                        <?php
                                        foreach ($Departments as $department) {
                                          ?>
                                          <option value="<?php echo $department->Department_Id ?>"><?php echo $department->Department_Name." - ".$department->Acronym ?></option>
                                          <?php
                                        }
                                         ?>
                                      </select>
                                      <span class="error">Prodi harus di pilih</span>
                                  </div>
                              </div>

                              <div class="form-group">
                                  <label class = "control-label col-md-4">Program Kelas</label>
                                  <div class="col-md-12">
                                    <select class="form-control form-control-sm" name="Class_Prog_Id" id="Class_Prog_Id" required>
                                      <option value="">-- Pilih Program Kelas --</option>
                                      <?php
                                      foreach ($ClassProgs as $Class_Prog) {
                                        ?>
                                        <option value="<?php echo $Class_Prog->Class_Prog_Id ?>"><?php echo $Class_Prog->Class_Program_Name ?></option>
                                        <?php
                                      }
                                       ?>
                                    </select>
                                    <span class="error">Program Kelas harus di pilih</span>
                                  </div>
                              </div>
                          <?php } ?>
                          <div class="row">

                            <div class="form-group panggil_date">
                              <label for="Nama" class = "control-label col-md-4">Mulai</Label>
                                <div class="col-md-12 col-sm-12">
                                  <div class="input-group date">
                                    <?php
                                    if(isset($Start_Date)){
                                      ?>
                                      <input type="text" name="Start_Date" id='Start_Date' style="background: white;" class="form-control form-control-sm" value="<?php echo $Start_Date ?>">
                                      <?php
                                    }else{
                                      ?>
                                      <input type="text" name="Start_Date" id='Start_Date' style="background: white;" class="form-control form-control-sm" value="">
                                      <?php } ?>
                                    </div>
                                  </div>
                                </div>

                                <div class="form-group panggil_date">
                                  <label for="Nama" class = "control-label col-md-4">Selesai</Label>
                                    <div class="col-md-12 col-sm-12">
                                      <?php
                                      if(isset($End_Date)){
                                        ?>
                                        <input type="text" name="End_Date" id='End_Date' style="background: white;" class="form-control form-control-sm" value="<?php echo $End_Date ?>">
                                        <?php
                                      }else{
                                        ?>
                                        <input type="text" name="End_Date" id='End_Date' style="background: white;" class="form-control form-control-sm" value="">
                                        <?php } ?>

                                      </div>
                                    </div>

                          </div>
                      </form>
                    </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="panel panel-default panel-green" >
                        <div class="panel-heading" style="padding:10px 20px;">
                              <div class="pull-right">
                              </div>
                              <div class="bootstrap-admin-box-title right">
                                  <b>Detail Item</b>
                              </div>
                          </div>
                          <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content" style="background-color:white;padding:10px;color:#333;">
                            <div class="form-horizontal">
                              <div class="form-group">
                                  <label class = "control-label col-md-4">Nama Biaya</label>
                                  <div class="col-md-8">
                                    <select class="form-control form-control-sm" name="Cost_Item_Id" id="Cost_Item_id">
                                      <option value="">-- Pilih Biaya --</option>
                                      <?php
                                        foreach ($Biayas as $biaya) {
                                          ?>
                                          <option value="<?php echo $biaya->Cost_Item_Id ?>"><?php echo $biaya->Cost_Item_Name ?></option>
                                          <?php
                                        }
                                       ?>
                                    </select>
                                    <span id="Cost_Item_id_message" style="visibility:hidden;color:red">Harus Diisi</span>
                                  </div>
                              </div>

                              <div class="form-group">
                                  <label class = "control-label col-md-4">Biaya</label>
                                  <div class="col-md-8">
                                      <input type="number" name="SAmount" id="SAmount" value="0" min="0" class="form-control form-control-sm auto">
                                      <input type="hidden" name="Amount" id="Amount" value="0">
                                  </div>
                              </div>

                              <div class="form-group">
                                  <div class="col-md-offset-4 col-md-8">
                                      <button type="button" id="add" class="btn btn-primary">
                                          Tambah <span class="glyphicon glyphicon-plus" style="color:white" aria-hidden="true"></span>
                                      </button>

                                  </div>
                              </div>
                          </div>
                          <input type="hidden" id="url" name="url" value="{{asset('set_biaya_registrasi/create_post')}}">
                          <input type="hidden" id="href" name="href" value="{{asset('set_biaya_registrasi?Term_Year_Id='.$Term_Year_Id.'&Entry_Year_Id='.$Entry_Year_Id.'&Entry_Period_Type_Id='.$Entry_Period_Type_Id)}}">
                          <div id="BiayaRegistrasiDetails" class="table-responsive">

                          </div>
                          <div style="text-align:right">
                              <br />
                              <button type="button" id="submit" class="btn btn-success">
                                  Simpan <span class="glyphicon glyphicon-floppy-save" style="color:white" aria-hidden="true"></span>
                              </button>
                          </div>
                          </div>
                      </div>
                  </div>
            </div>
            <script type="text/javascript">
              $(document).ready(function () {
                $("#Start_Date").kendoDatePicker();
                $("#End_Date").kendoDatePicker();
              });


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
  <script type="text/javascript">
        //Date Picker
        // $('.panggil_date .input-group.date').datepicker({
        //     format: "DD, d MM yyyy",
        //     language: "id",
        //     todayBtn: "linked",
        //     keyboardNavigation: false,
        //     forceParse: false,
        //     calendarWeeks: false,
        //     autoclose: true
        // });

        function replaceAll(find, replace, str) {
            while (str.indexOf(find) > -1) {
                str = str.replace(find, replace);
            }
            return str;
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

        $(document).ready(function () {
            var BiayaRegistrasiDetails = [];

            //Remove button click function
            $('#remove').click(function () {
                BiayaRegistrasiDetails.pop();

                //populate order items
                GeneratedItemsTable();
            });

            //Add button click function
            $('#add').click(function () {

                //Check validation of order item
                var isValidItem = true;
                var pesan = $('#Cost_Item_id').val();
                if ($('#Cost_Item_id').val().trim() == '') {
                    isValidItem = false;
                    $('#Cost_Item_id_message').css('visibility', 'visible');
                }
                else {
                    $('#Cost_Item_id_message').css('visibility', 'hidden');
                }

                if ($('#SAmount').val().trim() == '') {
                    isValidItem = false;
                    $('#SAmount').siblings('span.error').css('visibility', 'visible');
                }
                else {
                    $('#SAmount').siblings('span.error').css('visibility', 'hidden');
                }

                if ($('#Amount').val().trim() == '') {
                    isValidItem = false;
                    $('#Amount').siblings('span.error').css('visibility', 'visible');
                }
                else {
                    $('#Amount').siblings('span.error').css('visibility', 'hidden');
                }

                //Add item to list if valid
                if (isValidItem) {
                    BiayaRegistrasiDetails.push({
                        Cost_Item_id: parseInt($('#Cost_Item_id').val().trim()),
                        Cost_Item_Name: $("#Cost_Item_id option:selected").text(),
                        SAmount: $('#SAmount').val().trim(),
                        Amount: parseInt(replaceAll(',', '', $('#SAmount').val())),
                    });
                    //Clear fields
                    $('#Cost_Item_id').val('').focus();
                    $('#SAmount').val('');

                }
                //populate order items
                GeneratedItemsTable();

            });
            //Save button click function
            $('#submit').click(function () {
                //validation of order
                var isAllValid = true;
                if (BiayaRegistrasiDetails.length == 0) {
                    $('#BiayaRegistrasiDetails').html('<span style="color:red;">Detail Item belum ditambahkan</span>');
                    isAllValid = false;
                }

                if ($('#Entry_Year_Id').val().trim() == '') {
                    $('#Entry_Year_Id').siblings('span.error').css('visibility', 'visible');
                    isAllValid = false;
                }
                else {
                    $('#Entry_Year_Id').siblings('span.error').css('visibility', 'hidden');
                }

                if ($('#Entry_Period_Type_Id').val().trim() == '') {
                    $('#Entry_Period_Type_Id').siblings('span.error').css('visibility', 'visible');
                    isAllValid = false;
                }
                else {
                    $('#Entry_Period_Type_Id').siblings('span.error').css('visibility', 'hidden');
                }

                if ($('#Payment_Order').val().trim() == '') {
                    $('#Payment_Order').siblings('span.error').css('visibility', 'visible');
                    isAllValid = false;
                }
                else {
                    $('#Payment_Order').siblings('span.error').css('visibility', 'hidden');
                }

                if ($('#Department_Id').val().trim() == '') {
                    $('#Department_Id').siblings('span.error').css('visibility', 'visible');
                    isAllValid = false;
                }
                else {
                    $('#Department_Id').siblings('span.error').css('visibility', 'hidden');
                }

                if ($('#Class_Prog_Id').val().trim() == '') {
                    $('#Class_Prog_Id').siblings('span.error').css('visibility', 'visible');
                    isAllValid = false;
                }
                else {
                    $('#Class_Prog_Id').siblings('span.error').css('visibility', 'hidden');
                }

                if ($('#Term_Year_Id').val().trim() == '') {
                    $('#Term_Year_Id').siblings('span.error').css('visibility', 'visible');
                    isAllValid = false;
                }
                else {
                    $('#Term_Year_Id').siblings('span.error').css('visibility', 'hidden');
                }

                if ($('#Start_Date').val().trim() == '') {
                    $('#Start_Date').siblings('span.error').css('visibility', 'visible');
                    isAllValid = false;
                }
                else {
                    $('#Start_Date').siblings('span.error').css('visibility', 'hidden');
                }

                if ($('#End_Date').val().trim() == '') {
                    $('#End_Date').siblings('span.error').css('visibility', 'visible');
                    isAllValid = false;
                }
                else {
                    $('#End_Date').siblings('span.error').css('visibility', 'hidden');
                }

                //Save if valid
                if (isAllValid) {
                    // var getdata = {
                    //     Entry_Year_Id: parseInt($('#Entry_Year_Id').val().trim()),
                    //     Entry_Period_Type_Id: parseInt($('#Entry_Period_Type_Id').val().trim()),
                    //     Payment_Order: parseInt($('#Payment_Order').val().trim()),
                    //     Department_Id: parseInt($('#Department_Id').val().trim()),
                    //     Class_Prog_Id: parseInt($('#Class_Prog_Id').val().trim()),
                    //     Term_Year_Id: parseInt($('#Term_Year_Id').val().trim()),
                    //     Start_Date: $('#Start_Date').val().trim(),
                    //     End_Date: $('#End_Date').val().trim(),
                    //     BiayaRegistrasiDetails: BiayaRegistrasiDetails
                    // }
                    var data_array = JSON.stringify( BiayaRegistrasiDetails );

                    $(this).val('Please wait...');

                    $.ajax({
                      headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                        // url: $('#url').val(),
                        // url:'http://localhost:8080/stkip_muhammadiyah_bb/public/set_biaya_registrasi/create_post',
                        // type: "POST",
                        // data: JSON.stringify(data),
                        // dataType: "JSON",
                        // contentType: "application/json",
                        data : {
                            Entry_Year_Id: parseInt($('#Entry_Year_Id').val().trim()),
                            Entry_Period_Type_Id: parseInt($('#Entry_Period_Type_Id').val().trim()),
                            Payment_Order: parseInt($('#Payment_Order').val().trim()),
                            Department_Id: parseInt($('#Department_Id').val().trim()),
                            Class_Prog_Id: parseInt($('#Class_Prog_Id').val().trim()),
                            Term_Year_Id: parseInt($('#Term_Year_Id').val().trim()),
                            Start_Date: $('#Start_Date').val().trim(),
                            End_Date: $('#End_Date').val().trim(),
                            BiayaRegistrasiDetails: data_array
                        },
                        type: "POST",
                        url: "{{ url('set_biaya_registrasi/create_post') }}",
                        success: function (d) {
                            //check is successfully save to database
                            if (d.status == 1) {
                                //will send status from server side
                                //alert('Successfully done.');
                                swal('Selesai!', d.exp, 'success');
                                //clear form
                                BiayaRegistrasiDetails = [];
                                $('#Entry_Year_Id').val('');
                                $('#Entry_Period_Type_Id').val('');
                                $('#Payment_Order').val('');
                                $('#Department_Id').val('');
                                $('#Class_Prog_Id').val('');
                                $('#Term_Year_Id').val('');
                                $('#Start_Date').val('');
                                $('#End_Date').val('');
                                $('#BiayaRegistrasiDetails').empty();
                                window.location.href = $('#href').val();
                            }
                            else {
                               // alert('Failed');
                                swal('Gagal simpan!!', d.exp, 'error');
                            }
                            $('#submit').val('Save');
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            swal('Error!! '+xhr.status, thrownError, 'error');
                            $('#submit').val('Save');
                        }
                    });
                }

            });

            //function for show added items in table
            function GeneratedItemsTable() {
                if (BiayaRegistrasiDetails.length > 0) {
                    var $table = $('<table class="table table-striped table-bordered table-hover table-sm table-font-sm">');
                    $table.append('<thead class="thead-default thead-green"><tr><th>Nama Biaya</th><th>Biaya</th><th><i class="glyphicon glyphicon-cog"></i></th></tr></thead>');
                    var $tbody = $('<tbody/>');
                    var $total = 0;
                    $.each(BiayaRegistrasiDetails, function (i, val) {
                        var $row = $('<tr/>');
                        $row.append($('<td/>').html(val.Cost_Item_Name));
                        $row.append($('<td/>').attr('align', 'right').html(val.SAmount));
                        var $remove = $('<a href="#" class="btn-sm btn-danger">Hapus <span class="glyphicon glyphicon-trash"></span></a>');
                        $remove.click(function (e) {
                            e.preventDefault();
                            BiayaRegistrasiDetails.splice(i, 1);
                            GeneratedItemsTable();
                        });
                        $row.append($('<td/>').attr('align', 'center').html($remove));
                        $tbody.append($row);
                        $total += val.Amount;
                    });
                    var $frow = $('<tr/>');
                    $frow.append($('<td/>').attr('align', 'right').html('<b>Total</b>'));
                    $frow.append($('<td/>').attr('align', 'right').html(thousandFormat($total)));
                    $frow.append($('<td/>').html(''));
                    $tbody.append($frow);
                    console.log("current", BiayaRegistrasiDetails);
                    $table.append($tbody);
                    $('#BiayaRegistrasiDetails').html($table);
                }
                else {
                    $('#BiayaRegistrasiDetails').html('');
                }
            }
        });
    </script>
</section>
@endsection
