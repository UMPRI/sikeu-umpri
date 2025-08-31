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
                      <input type="hidden" name="Register_Number" id="Register_Number" value="<?php echo $Student->Register_Number ?>">
                      <input type="hidden" name="Term_Year_Id" id="Term_Year_Id" value="<?php echo $Term_Year->Term_Year_Id ?>">
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
                                  <input type="text" name="Start_Date" id='Start_Date' class="form-control " value="">
                              </div>
                              <span id="Start_Date_Msg" style="color:red;visibility:hidden">Tanggal Harus Diisi</span>
                          </div>
                      </div>
                      <script type="text/javascript">
                        $('#Start_Date').kendoDatePicker();
                      </script>
                      <div class="form-group panggil_date">
                          <label for="Nama" class = "control-label col-md-4">Selesai</Label>
                          <div class="col-md-5 col-sm-5">
                              <div class="input-group date">
                                  <input type="text" name="End_Date" id='End_Date' class="form-control " value="">
                              </div>
                              <span id="End_Date_Msg" style="color:red;visibility:hidden">Tanggal Harus Diisi</span>
                          </div>
                      </div>
                      <script type="text/javascript">
                          $('#End_Date').kendoDatePicker();
                      </script>
                      <div class="form-group">
                          <label for="Nama" class = "control-label col-md-4">Keterangan</Label>
                          <div class="col-md-8">
                              <textarea name="Keterangan" id="Keterangan" class = "form-control"></textarea>
                              <span id="Keterangan_Msg" style="color:red;visibility:hidden">Keterangan Harus Diisi</span>
                          </div>
                      </div>

                    </div>
                  </div>
                  {{-- <div class="col-md-6">
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
                  </div> --}}
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
                                    <select class="form-control form-control-sm" name="Cost_Item_Id" id="Cost_Item_Id">
                                      <option value="">-- Pilih Biaya --</option>
                                      <?php foreach ($fnc_cost_item as $biaya) { ?>
                                        <option value="{{ $biaya->Cost_Item_Id }}">{{ $biaya->Cost_Item_Name }}</option>
                                      <?php }?>
                                    </select>
                                    <span id="Cost_Item_Id_Msg" style="color:red;visibility:hidden">Item Biaya Harus Diisi</span>
                                  </div>
                              </div>
                              <div class="form-group" id="tempat_biaya" style="display:none">
                                  <label class = "control-label col-md-4">Biaya Normal</label>
                                  <div class="col-md-8">
                                    <input type="text" name="biaya_asli" id="biaya_asli" style="background:white" class="form-control form-control-sm auto" readonly>
                                  </div>
                              </div>

                              <div class="form-group" id="tempat_diskon" style="display:none">
                                  <label class = "control-label col-md-4">Diskon</label>
                                  <div class="col-md-8">
                                      <input type="number" name="Percentage" id="Percentage" value="0" min="0" max="100" class="form-control form-control-sm auto">
                                      <span id="Percentage_Msg" style="color:red;visibility:hidden">Biaya Harus Diisi</span>
                                  </div>
                              </div>

                              <div class="form-group">
                                  <label class = "control-label col-md-4">Biaya</label>
                                  <div class="col-md-8">
                                      <input type="number" name="SAmount" id="SAmount" value="0" min="0" class="form-control form-control-sm auto">
                                      <input type="hidden" name="Amount" id="Amount" value="0">
                                      <span id="SAmount_Msg" style="color:red;visibility:hidden">Biaya Harus Diisi</span>
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
                          <input type="hidden" id="href" name="href" value="{{asset('set_biaya_registrasi_personal?param='.$Register_Number.'&Term_Year_Id='.$Term_Year_Id)}}">
                          <div id="BiayaRegistrasiDetails" class="table-responsive">

                          </div>

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

                $(document).ready(function () {
                  var BiayaRegistrasiDetails = [];

                  //delete button------------------
                  $('#remove').click(function () {
                      BiayaRegistrasiDetails.pop();

                      //populate order items
                      GeneratedItemsTable();
                  });

                  // item_biaya change -----------------
                  $("#Cost_Item_Id").change(function () {
                      var Cost_Item_Id = $("#Cost_Item_Id").val();
                      var regnum = "<?php echo $Student->Register_Number; ?>";
                      var termyear = <?php echo $Term_Year->Term_Year_Id; ?>;
                      var url = {!! json_encode(url('/')) !!}
                      $.ajax({
                  			type: 'GET',
                  			url: url + '/set_biaya_registrasi_personal/create_amount?Register_Number='+ regnum +'&Term_Year_Id='+termyear+'&Cost_Item_Id='+Cost_Item_Id,
                  			success: function(data) {
                          if (data != 0) {
                            $('#tempat_biaya').css("display","inline");
                            $('#tempat_diskon').css("display","inline");
                            $('#biaya_asli').val(data);
                          }else{
                            $('#tempat_biaya').css("display","none");
                            $('#tempat_diskon').css("display","none");
                          }

                  			}
                  		});
                  });

                  //diskon sistem---------------------------
                  $('#Percentage').keyup(function () {
                    var biaya_normal = $('#biaya_asli').val();
                    var diskon = $('#Percentage').val() / 100;

                    var hasildiskon = biaya_normal * diskon;
                    var totalbayar = biaya_normal - hasildiskon;
                    //hasilnya boss
                    $('#SAmount').val(totalbayar);
                  })

                  // add button--------------------
                  $("#add").click(function () {
                    // cek validasi
                    var isValidItem = true;
                    var pesan = $('#Cost_Item_Id').val();


                    if ($('#Cost_Item_Id').val().trim() == '') {
                        isValidItem = false;
                        $('#Cost_Item_Id_Msg').css('visibility', 'visible');
                    }else {
                        isValidItem = true;
                        $('#Cost_Item_Id_Msg').css('visibility', 'hidden');
                    }

                    if ($('#SAmount').val().trim() == 0) {
                        isValidItem = false;
                        $('#SAmount_Msg').css('visibility', 'visible');
                    }
                    else {
                      isValidItem = true;
                      $('#SAmount_Msg').css('visibility', 'hidden');
                    }

                    if ($('#Amount').val().trim() == '') {
                        isValidItem = false;
                        $('#Amount').siblings('span.error').css('visibility', 'visible');
                    }
                    else {
                        $('#Amount').siblings('span.error').css('visibility', 'hidden');
                    }

                    // if ($('#Percentage').val().trim() == 0) {
                    //     isValidItem = false;
                    //     $('#Percentage_Msg').css('visibility', 'visible');
                    // }
                    // else {
                    //   isValidItem = true;
                    //   $('#Percentage_Msg').css('visibility', 'hidden');
                    // }

                    if (isValidItem) {
                      BiayaRegistrasiDetails.push({
                          Cost_Item_id: parseInt($('#Cost_Item_Id').val().trim()),
                          Cost_Item_Name: $("#Cost_Item_Id option:selected").text(),
                          Percentage: $('#Percentage').val(),
                          SAmount: $('#SAmount').val().trim(),
                          Amount: parseInt(replaceAll(',', '', $('#SAmount').val().trim()))

                      });
                      //Clear fields
                      $('#Cost_Item_Id').val('').focus();
                      $('#tempat_biaya').css("display","none");
                      $('#tempat_diskon').css("display","none");
                      $('#Percentage').val('');
                      $('#SAmount').val('');


                      GeneratedItemsTable();

                    }else {

                    }


                  });


                  // submit data button--------------------
                  $('#submit').click(function () {

                    var isValidItemStart_Date = true;
                    var isValidItemEnd_Date = true;
                    var isValidItemKeterangan = true;
                    var isValidItem = true;

                    if ($('#Start_Date').val() == '') {
                        isValidItemStart_Date = false;
                        $('#Start_Date_Msg').css('visibility', 'visible');
                    }else {
                        isValidItemStart_Date = true;
                        $('#Start_Date_Msg').css('visibility', 'hidden');
                    }

                    if ($('#End_Date').val() == '') {
                        isValidItemEnd_Date = false;
                        $('#End_Date_Msg').css('visibility', 'visible');
                    }else {
                        isValidItemEnd_Date = true;
                        $('#End_Date_Msg').css('visibility', 'hidden');
                    }

                    if ($('#Keterangan').val() == '') {
                        isValidItemKeterangan = false;
                        $('#Keterangan_Msg').css('visibility', 'visible');
                    }else {
                        isValidItemKeterangan = true;
                        $('#Keterangan_Msg').css('visibility', 'hidden');
                    }

                    if (BiayaRegistrasiDetails.length == 0) {
                        $('#BiayaRegistrasiDetails').html('<span style="color:red;">Detail Item belum ditambahkan</span>');
                        isValidItem = false;
                    }else{
                      isValidItem = true;
                    }

                    // alert(isValidItem);

                    //ambil data fnc_cost_sched_personal-----------------------
                    var Register_Number = $('#Register_Number').val();
                    var Term_Year_Id = $('#Term_Year_Id').val();
                    var Start_Date = $('#Start_Date').val();
                    var End_Date = $('#End_Date').val();
                    var Keterangan = $('#Keterangan').val();

                    // fnc_cost_sched_personal_detail ------------------------



                    // save prosesssws..................
                    if (isValidItemStart_Date && isValidItemEnd_Date && isValidItemKeterangan && isValidItem ) {

                      var data_array = JSON.stringify( BiayaRegistrasiDetails );


                      $(this).val('Please wait...');

                      $.ajax({
                        headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('/') }}/set_biaya_registrasi_personal/create_post_ajax",
                        type: "POST",
                        data: {
                          Register_Number: Register_Number,
                          Term_Year_Id: Term_Year_Id,
                          Start_Date: Start_Date,
                          End_Date: End_Date,
                          Keterangan: Keterangan,
                          BiayaRegistrasiDetails: data_array
                        },
                        success: function (res) {

                          if (res.status == 1) {
                            swal('Selesai!', res.exp, 'success');

                            //clear form
                            BiayaRegistrasiDetails = [];
                            window.location.href = $('#href').val();

                          }else {
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
                          $table.append('<thead class="thead-default thead-green"><tr><th>Nama Biaya</th><th>Diskon</th><th>Biaya</th><th><i class="glyphicon glyphicon-cog"></i></th></tr></thead>');
                          var $tbody = $('<tbody/>');
                          var $total = 0;
                          $.each(BiayaRegistrasiDetails, function (i, val) {
                              var $row = $('<tr/>');
                              $row.append($('<td/>').html(val.Cost_Item_Name));
                              $row.append($('<td/>').attr('align', 'right').html(val.Percentage));
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
                          $frow.append($('<td/>').html(''));
                          $frow.append($('<td/>').attr('align', 'right').html(thousandFormat($total)));
                          $frow.append($('<td/>').html(''));
                          $tbody.append($frow);
                          // console.log("current", BiayaRegistrasiDetails);
                          $table.append($tbody);
                          $('#BiayaRegistrasiDetails').html($table);
                      }
                      else {
                          $('#BiayaRegistrasiDetails').html('');
                      }
                  }
                });

                //funtion hapus string amaount
                function replaceAll(find, replace, str) {
                    while (str.indexOf(find) > -1) {
                        str = str.replace(find, replace);
                    }
                    return str;
                }
            </script>

      </div>
    </div>
  </div>
</section>
@endsection
