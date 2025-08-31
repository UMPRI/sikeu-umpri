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
  span.exist {
      display: block;
      visibility: hidden;
      color: red;
      font-size: 90%;
  }
  </style>
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Setting Biaya Tambahan</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ asset('/set_biaya_tambahan?Term_Year_Id='.$Term_Year_Id.'')}}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left"></i> Batal</a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Create</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
            <div class="col-md-12">
                    <div class="panel panel-default panel-green" >
                        <div class="panel-heading" style="padding:10px 20px;">
                              <div class="pull-right">
                              </div>
                              <div class="bootstrap-admin-box-title right">
                                  <b>Mahasiswa</b>
                              </div>
                          </div>
                          <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content" style="background-color:white;padding:10px;color:#333;">
                            <div class="form-horizontal">
                              <form class="" action="" method="get">
                                <div class="form-group">
                                  <input type="hidden" name="Term_Year_Id" value="<?php echo $Term_Year_Id ?>">
                                  <label class = "control-label col-md-4">Prodi</label>
                                  <div class="col-md-8">
                                    <input name="Department_Id" id="Department_Id">
                                  </div>
                                </div>
                              </form>
                              <div class="form-group">
                                <label class = "control-label col-md-4">Mahasiswa</label>
                                <div class="col-md-8">
                                  <input name="Register_Number" id="Register_Number">
                                  <span class="exist">Mahasiswa sudah ada dalam list</span>
                                </div>
                              </div>

                              <div class="form-group">
                                  <div class="col-md-offset-4 col-md-8">
                                      <button type="button" id="add" class="btn btn-primary">
                                          Tambah <span class="glyphicon glyphicon-plus" style="color:white" aria-hidden="true"></span>
                                      </button>

                                  </div>
                              </div>
                              <span class="error">Mahasiswa harus dipilih</span>
                          </div>
                          <input type="hidden" id="url" name="url" value="{{asset('set_biaya_registrasi/create_post')}}">
                          <input type="hidden" id="href" name="href" value="{{ asset('set_biaya_tambahan?Term_Year_Id='.$Term_Year_Id) }}">
                          <div id="Mahasiswa" class="table-responsive">

                          </div>

                          </div>
                      </div>
            </div>
            <div class="col-md-12">
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
                            <span class="error">Tidak Boleh Kosong !</span>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class = "control-label col-md-4">Nama Biaya</label>
                          <div class="col-md-12">
                            <select class="form-control form-control-sm" name="Cost_Item_id" id="Cost_Item_id" required>
                              <option value="">-- Pilih Biaya --</option>
                              <?php
                              foreach ($Cost_Item as $value) {
                                ?>
                                <option value="<?php echo $value->Cost_Item_Id ?>"><?php echo $value->Cost_Item_Name ?></option>
                                <?php
                              }
                              ?>
                            </select>
                            <span class="error">Tidak Boleh Kosong !</span>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class = "control-label col-md-4">Biaya</label>
                          <div class="col-md-12">
                            <input type="number" value="0" min="0" name="Amount" class="form-control form-control-sm" id="Amount">
                            <span class="error">Tidak Boleh Kosong !</span>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class = "control-label col-md-4">Keterangan</label>
                          <div class="col-md-12">
                            <input type="test" name="Explanation" class="form-control form-control-sm" id="Explanation">
                            <span class="error">Tidak Boleh Kosong !</span>
                          </div>
                        </div>
                        <div class="form-group panggil_date">
                          <label for="Nama" class = "control-label col-md-4">Mulai</Label>
                              <div class="col-md-12 col-sm-12">
                                  <div class="input-group date">
                                    <?php
                                    if(isset($Start_Date)){
                                      ?>
                                      <input type="text" name="Start_Date" id="Start_Date" value="<?php echo $Start_Date ?>" >
                                      <?php
                                    }else{
                                      ?>
                                      <input type="text" name="Start_Date" id="Start_Date" value="" >
                                    <?php } ?>
                                    <span class="error">Tidak Boleh Kosong !</span>
                                  </div>
                              </div>
                            </div>

                            <div class="form-group panggil_date">
                              <label for="Nama" class = "control-label col-md-4">Selesai</Label>
                                  <div class="col-md-12 col-sm-12">
                                      <div class="input-group date">
                                        <?php
                                        if(isset($End_Date)){
                                          ?>
                                          <input type="text" name="End_Date" id="End_Date" value="<?php echo $End_Date ?>" >
                                          <?php
                                        }else{
                                          ?>
                                          <input type="text" name="End_Date" id="End_Date" value="" >
                                        <?php } ?>
                                        <span class="error">Tidak Boleh Kosong !</span>
                                      </div>
                                  </div>
                                </div>
                                <div style="text-align:center">
                                    <br />
                                    <button type="button" id="submit" class="btn btn-success btn-lg">
                                        Simpan <span class="fa fa-save" style="color:white" aria-hidden="true"></span>
                                    </button>
                                </div>

                              </form>
                            </div>
                          </div>
                        </div>
            </div>
            </div>
            <script type="text/javascript">
              $(document).ready(function () {
                $("#Start_Date").width(250).kendoDatePicker();
                $("#End_Date").width(250).kendoDatePicker();
                $("#Register_Number").width(250).kendoDropDownList();

                $("#Department_Id").width(250).kendoDropDownList({
                    dataTextField: "Department_Name",
                    dataValueField: "Department_Id",
                    dataSource: {
                        transport: {
                            read: {
                                dataType: "json",
                                url: "{{asset('set_biaya_tambahan/Department_Id')}}",
                            }
                        }
                    },
                    change : function () {
                      $("#Register_Number").kendoDropDownList({
                          filter: "startswith",
                          dataTextField: "Full_Name",
                          dataValueField: "Register_Number",
                          dataSource: {
                              transport: {
                                read: function(options){
                                  var Department_Id = $('#Department_Id').val();
                                  $.ajax({
                                    url: "{{asset('set_biaya_tambahan/Register_Number')}}",
                                    type: "GET",
                                    serverFiltering: true,
                                    data: {Department_Id : Department_Id},
                                    dataType: "json",

                                    success: function (res) {
                                      options.success(res);
                                    }
                                  });
                                }

                              }
                          }
                      })
                    }
                });

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
            var Mahasiswa = [];

            //Remove button click function
            $('#remove').click(function () {
                Mahasiswa.pop();

                //populate order items
                GeneratedItemsTable();
            });

            //Add button click function
            $('#add').click(function () {
                //Check validation of order item
                var Register_Num = $('#Register_Number').val().trim();
                var Full_Name = $("#Register_Number").data("kendoDropDownList").text();

                var isValidItem = true;
                if ($('#Register_Number').val().trim() == '') {
                    isValidItem = false;
                    $('#Register_Number').siblings('span.error').css('visibility', 'visible');
                }
                else {
                    $('#Register_Number').siblings('span.error').css('visibility', 'hidden');
                }

                var result = $.grep(Mahasiswa, function (e) { return e.Register_Number == $('#Register_Number').val().trim(); });
                if (result.length == 1) {
                    isValidItem = false;
                    $('#Register_Number').siblings('span.exist').css('visibility', 'visible');
                }
                else {
                    $('#Register_Number').siblings('span.exist').css('visibility', 'hidden');
                }

                //Add item to list if valid
                if (isValidItem) {
                    Mahasiswa.push({
                      Register_Number: Register_Num,
                      NimName: Full_Name
                    });
                    //Clear fields
                    $('#Register_Number').val('').focus();

                }
                //populate order items
                GeneratedItemsTable();

            });
            //Save button click function
            $('#submit').click(function () {
                //validation of order
                var isAllValid = true;
                if (Mahasiswa.length == 0) {
                    $('#Mahasiswa').html('<span style="color:red;">Detail Item belum ditambahkan</span>');
                    isAllValid = false;
                }

                if ($('#Cost_Item_id').val().trim() == '') {
                    $('#Cost_Item_id').siblings('span.error').css('visibility', 'visible');
                    isAllValid = false;
                }
                else {
                    $('#Cost_Item_id').siblings('span.error').css('visibility', 'hidden');
                }

                if ($('#Explanation').val().trim() == '') {
                    $('#Explanation').siblings('span.error').css('visibility', 'visible');
                    isAllValid = false;
                }
                else {
                    $('#Explanation').siblings('span.error').css('visibility', 'hidden');
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

                if ($('#Amount').val().trim() == '') {
                    $('#Amount').siblings('span.error').css('visibility', 'visible');
                    isAllValid = false;
                }
                else {
                    $('#Amount').siblings('span.error').css('visibility', 'hidden');
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
                    var data_array = JSON.stringify(Mahasiswa);

                    $(this).val('Please wait...');
                    $.ajax({
                      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        // url: $('#url').val(),
                        // url:'http://localhost:8080/stkip_muhammadiyah_bb/public/set_biaya_registrasi/create_post',
                        // type: "POST",
                        // data: JSON.stringify(data),
                        // dataType: "JSON",
                        // contentType: "application/json",
                        data : {
                          Term_Year_Id: parseInt($('#Term_Year_Id').val().trim()),
                          Cost_Item_id: parseInt($('#Cost_Item_id').val().trim()),
                          SAmount: $('#Amount').val().trim(),
                          Amount: parseInt(replaceAll(',', '', $('#Amount').val())),
                          Explanation: $('#Explanation').val().trim(),
                          Start_Date: $('#Start_Date').val().trim(),
                          End_Date: $('#End_Date').val().trim(),
                          Mahasiswa: data_array
                        },
                        type: "POST",
                        url: "{{ url('set_biaya_tambahan/create_post') }}",
                        beforeSend : function() {
                          $(".post_submitting").show().html("<center><i class='fa fa-refresh fa-spin' style='font-size:24px'></i></center>");
                        },
                        success: function (d) {
                            //check is successfully save to database
                            if (d.status == 1) {
                                //will send status from server side
                                //alert('Successfully done.');
                                swal('Selesai!', d.exp, 'success');
                                //clear form
                                Mahasiswa = [];
                                $('#Term_Year_Id').val('');
                                $('#Cost_Item_id').val('');
                                $('#SAmount').val('');
                                $('#Explanation').val('');
                                $('#Start_Date').val('');
                                $('#End_Date').val('');
                                $('#Mahasiswa').empty();
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
                if (Mahasiswa.length > 0) {
                    var $table = $('<table class="table table-striped table-bordered table-hover table-sm table-font-sm">');
                    $table.append('<thead class="thead-default thead-green"><tr><th>NO</th><th>Mahasiswa</th><th><i class="glyphicon glyphicon-cog"></i></th></tr></thead>');
                    var $tbody = $('<tbody/>');
                    var $no = 1;
                    $.each(Mahasiswa, function (i, val) {
                        var $row = $('<tr/>');
                        $row.append($('<td/>').attr('align', 'center').html($no));
                        $row.append($('<td/>').html(val.NimName));
                        var $remove = $('<a href="#" class="btn-sm btn-danger">Hapus <span class="glyphicon glyphicon-trash"></span></a>');
                        $remove.click(function (e) {
                            e.preventDefault();
                            Mahasiswa.splice(i, 1);
                            GeneratedItemsTable();
                        });
                        $row.append($('<td/>').attr('align', 'center').html($remove));
                        $tbody.append($row);
                        $no += 1;
                    });
                    console.log("current", Mahasiswa);
                    $table.append($tbody);
                    $('#Mahasiswa').html($table);
                }
                else {
                    $('#Mahasiswa').html('');
                }
            }
        });
    </script>
</section>
@endsection
