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
          <a href="{{ asset('set_biaya_tambahan?Term_Year_Id='.$Term_Year->Term_Year_Id)}}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit</b>
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
                          <div class="form-group">
                            <input type="hidden" name="Term_Year_Id" value="<?php echo $Term_Year_Id ?>">
                            <label class = "control-label col-md-4">Prodi</label>
                            <div class="col-md-8">
                              <input name="Department_Id" id="Department_Id">
                            </div>
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
                    <input type="hidden" id="href" name="href" value="{{ asset('set_biaya_tambahan?Term_Year_Id='.$Term_Year_Id) }} ">
                    <div id="Mahasiswa" class="table-responsive">

                    </div>
                    <div style="text-align:right">
                      <br />
                      <button type="button" id="submit" class="btn btn-success">
                        Simpan <span class="fa fa-save" style="color:white" aria-hidden="true"></span>
                      </button>
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
                      <form class="form-horizontal" method="POST" action="edit_post">
                        {{ csrf_field() }}
                        <input type="Hidden" value="<?php echo $fnc_Cost_Sched_Personal_Plus->Cost_Sched_Personal_Plus_Id ?>" name="Cost_Sched_Personal_Plus_Id" id="Cost_Sched_Personal_Plus_Id" required>
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
                                      <option value="<?php echo $value->Cost_Item_Id ?>" <?php if($value->Cost_Item_Id == $fnc_Cost_Sched_Personal_Plus->Cost_Item_id){ echo "selected";} ?>><?php echo $value->Cost_Item_Name ?></option>
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
                                  <input type="number" value="<?php echo $fnc_Cost_Sched_Personal_Plus->Amount ?>" min="0" name="Amount" class="form-control form-control-sm" id="Amount">
                                  <span class="error">Tidak Boleh Kosong !</span>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class = "control-label col-md-4">Keterangan</label>
                              <div class="col-md-12">
                                  <input type="test" value="<?php echo $fnc_Cost_Sched_Personal_Plus->Explanation ?>" name="Explanation" class="form-control form-control-sm" id="Explanation">
                                  <span class="error">Tidak Boleh Kosong !</span>
                              </div>
                          </div>
                          <div class="form-group panggil_date">
                              <label for="Nama" class = "control-label col-md-4">Mulai</Label>
                              <div class="col-md-12 col-sm-12">
                                  <div class="input-group date">
                                    <?php
                                    $Start_Date = $fnc_Cost_Sched_Personal_Plus->Start_Date;
                                    if(isset($Start_Date)){
                                      ?>
                                      <input type="text" style="background: white;" name="Start_Date" id="Start_Date" class="form-control form-control-sm" value="<?php echo Date("m/d/Y",strtotime($Start_Date)) ?>" readonly>
                                      <?php
                                    }else{
                                      ?>
                                      <input type="text" style="background: white;" name="Start_Date" id="Start_Date" class="form-control form-control-sm" value="" readonly>
                                    <?php } ?>
                                    <span class="error">Tidak Boleh Kosong !</span>
                                  </div>
                              </div>
                          </div>
                          <script type="text/javascript">
                            $('#Start_Date').datepicker({
                                uiLibrary: 'bootstrap4',
                                format: 'mm/dd/yyyy',
                            });
                          </script>
                          <div class="form-group panggil_date">
                              <label for="Nama" class = "control-label col-md-4">Selesai</Label>
                              <div class="col-md-12 col-sm-12">
                                  <div class="input-group date">
                                    <?php
                                    $End_Date = $fnc_Cost_Sched_Personal_Plus->End_Date;
                                    if(isset($End_Date)){
                                      ?>
                                      <input type="text" style="background: white;" name="End_Date" id="End_Date" class="form-control form-control-sm" value="<?php echo Date("m/d/Y",strtotime($End_Date)) ?>" readonly>
                                      <?php
                                    }else{
                                      ?>
                                      <input type="text" style="background: white;" name="End_Date" id="End_Date" class="form-control form-control-sm" value="" readonly>
                                    <?php } ?>
                                    <span class="error">Tidak Boleh Kosong !</span>
                                  </div>
                              </div>
                          </div>
                          <script type="text/javascript">
                              $('#End_Date').datepicker({
                                  uiLibrary: 'bootstrap4',
                                  format: 'mm/dd/yyyy',
                              });
                          </script>
                      </form>
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
            <?php
            // $array_plus_detail = [];
            // $ii =0;
            // foreach ($q_Cost_Sched_Personal_Plus_Detail as $data) {
            //   $array_plus_detail[$ii]['Register_Number'] = $data->Register_Number;
            //   $array_plus_detail[$ii]['NimName'] = $data->Nim." | ".$data->Full_Name;
            //   $array_plus_detail[$ii]['Cost_Sched_Personal_Plus_Id'] = $data->Cost_Sched_Personal_Plus_Id;
            //   $ii++;
            // }
            // echo json_encode($array_plus_detail);
            ?>
      </div>
    </div>
  </div>
  <script type="text/javascript">
        var Mahasiswa = [];
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
        <?php 
          foreach ($q_Cost_Sched_Personal_Plus_Detail as $data) { ?>
            Mahasiswa.push({
                Cost_Sched_Personal_Plus_Id: parseInt("<?php echo $data->Cost_Sched_Personal_Plus_Id ?>"),
                Register_Number: '<?php echo $data->Register_Number ?>',
                NimName: '<?php echo $data->Nim." | ".$data->Full_Name ?>'
            });
          <?php }
        ?>

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
          // var array = ;
          // for(var i =0; i<array.length;i++){
          //     Mahasiswa[i] = array[i];
          // }
          //generate array to table view
          GeneratedItemsTable();
          window.onload = function(){
              GeneratedItemsTable();
          };
          //Remove button click function
          $('#remove').click(function () {
              Mahasiswa.pop();

              //populate order items
              GeneratedItemsTable();
          });
          //Add button click function
          $('#add').click(function () {
              //Check validation of order item
              var isValidItem = true;
              if ($('#Register_Number').val().trim() == '') {
                alert("register number kosong");
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
                      Cost_Sched_Personal_Plus_Id: parseInt($('#Cost_Sched_Personal_Plus_Id').val().trim()),
                      Register_Number: $("#Register_Number").data("kendoDropDownList").value(),
                      NimName: $("#Register_Number").data("kendoDropDownList").text()
                  });
                  console.log('mahasiswa', Mahasiswa);
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

                //Save if valid
                if (isAllValid) {
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
                          Cost_Sched_Personal_Plus_Id: parseInt($('#Cost_Sched_Personal_Plus_Id').val().trim()),
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
                        url: "{{ url('set_biaya_tambahan/edit_post/'.$fnc_Cost_Sched_Personal_Plus->Cost_Sched_Personal_Plus_Id) }}",
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
