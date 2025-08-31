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
          <b>Edit Biaya Registrasi Personal</b>
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
                      <div class="form-group row">
                          <div class="col-md-3">Nama</div>
                          <div class="col-md-6">
                            : &nbsp <?php echo $acd_student->Full_Name ?>
                          </div>
                          {{-- <div class="col-md-8">
                              <input type="text" value="" class="form-control form-control-sm" disabled="disabled" />
                          </div> --}}
                      </div>

                      <div class="form-group row">
                          <div class="col-md-3">Th Akademik</div>
                          <div class="col-md-6">
                            : &nbsp <?php echo $mstr_term_year->Term_Year_Name ?>
                          </div>
                          {{-- <label for="Nama" class = "control-label col-md-4">Th Akademik</Label>
                          <div class="col-md-8">
                              <input type="text" value="" class="form-control form-control-sm" disabled="disabled" />
                          </div> --}}
                      </div>

                      <div class="form-group row">
                          <div class="col-md-3">Mulai</div>
                          <div class="col-md-6">
                            : &nbsp <?php echo date('d F Y', strtotime($fnc_cost_sched_personal->Start_Date)) ?>
                          </div>
                          {{-- <label for="Nama" class = "control-label col-md-4">Mulai</Label>
                          <div class="col-md-5 col-sm-5">
                              <div class="input-group date">
                                <input type="text" name="Start_Date" class="form-control" id='datetimepicker1' value="" readonly>
                              </div>
                          </div> --}}
                      </div>

                      <div class="form-group row">
                          <div class="col-md-3">Selesai</div>
                          <div class="col-md-6">
                            : &nbsp <?php echo  date('d F Y', strtotime($fnc_cost_sched_personal->End_Date)) ?>
                          </div>
                          {{-- <label for="Nama" class = "control-label col-md-4">Selesai</Label>
                          <div class="col-md-5 col-sm-5">
                              <div class="input-group date">
                                <input type="text" name="End_Date" class="form-control " id='datetimepicker2' value="" readonly>
                              </div>
                          </div> --}}
                      </div>

                      <div class="form-group row">
                          <div class="col-md-3">Keterangan</div>
                          <div class="col-md-6">
                            <textarea name="Explanation" class = "form-control"><?php echo $fnc_cost_sched_personal->Explanation ?></textarea>
                          </div>
                          {{-- <label for="Nama" class = "control-label col-md-4">Keterangan</Label>
                          <div class="col-md-8">
                              <textarea name="Explanation" class = "form-control"></textarea>
                          </div> --}}
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
                        <input type="hidden" id="href" name="href" value="{{asset('set_biaya_registrasi_personal?param='.$acd_student->Register_Number.'&Term_Year_Id='.$mstr_term_year->Term_Year_Id)}}">


                        </div>
                        </div>

                  </div>


                  <input type="hidden" name="url" id="url" value="{{ asset('set_biaya_registrasi_personal/create?Register_Number='.$acd_student->Register_Number.'&Term_Year_Id='.$mstr_term_year->Term_Year_Id.'&Payment_Order=')}}">
                  <div class="col-md-6">
                      <div class="panel panel-default bootstrap-admin-no-table-panel">

                          <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
                            <div id="BiayaRegistrasiDetails" class="table-responsive">

                            </div>
                          </div>


                          {{-- //modal ------------------------------------ --}}
                          <!-- Modal -->
                          <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                              <div class="modal-content">
                                <div class="modal-header bg-super-blue">
                                  <h5 class="modal-title text-white" id="exampleModalLabel">Edit Biaya Detail</h5>
                                  <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <form>
                                    <div class="form-group">
                                        <label for="Cost_Item_Id_M" class="col-form-label">Nama Biaya</label>
                                        <select class="form-control form-control-sm" name="Cost_Item_Id_M" id="Cost_Item_Id_M">
                                          <option value="">-- Pilih Biaya --</option>
                                          <?php foreach ($fnc_cost_item as $biaya) { ?>
                                            <option value="{{ $biaya->Cost_Item_Id }}">{{ $biaya->Cost_Item_Name }}</option>
                                          <?php }?>
                                        </select>
                                        <span id="Cost_Item_Id_Msg" style="color:red;visibility:hidden">Item Biaya Harus Diisi</span>
                                    </div>
                                    <div class="form-group" id="tempat_biaya_M" style="display:none">
                                        <label class = "control-label" id="biaya_asli_M"></label>
                                        <input type="hidden" id="biaya_asli_amount_M">
                                    </div>
                                    <br>
                                    <div class="form-group" id="tempat_diskon_M" style="display:none">
                                      <label for="Percentage_M" class="col-form-label">Diskon</label>
                                      <input type="text" class="form-control form-control-sm" id="Percentage_M">
                                    </div>
                                    <div class="form-group">
                                      <label for="Amount_M" class="col-form-label">Biaya</label>
                                      <input type="text" class="form-control form-control-sm" id="Amount_M">
                                    </div>
                                  </form>
                                </div>

                                {{-- // hidden data $Cost_Sched_Personal_Id ------------------ --}}
                                  <input type="hidden" name="Cost_Sched_Personal_Detail_Id_M" id="Cost_Sched_Personal_Detail_Id_M">
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                  <button type="button" class="btn btn-success" id="update_detail">Update</button>
                                </div>
                              </div>
                            </div>
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
              $(document).ready(function () {
                var BiayaRegistrasiDetails = [];
                var array = <?php echo json_encode($fnc_cost_sched_personal_detail); ?>;
                for(var i =0; i<array.length;i++){
                    BiayaRegistrasiDetails[i] = array[i];
                }
                GeneratedItemsTable();
                //generate array to table view
                window.onload = function(){
                    GeneratedItemsTable();
                };

                $('#Cost_Item_Id').change(function () {
                  var Cost_Item_Id = $("#Cost_Item_Id").val();
                  var regnum = <?php echo $acd_student->Register_Number; ?>;
                  var termyear = <?php echo $mstr_term_year->Term_Year_Id; ?>;
                  var url = {!! json_encode(url('/')) !!};

                  // sistem tampilkan data pembayaran ------------
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

                  // siste diskon --------------------
                  $('#Percentage').keyup(function () {
                    var biaya_normal = $('#biaya_asli').val();
                    var diskon = $('#Percentage').val() / 100;

                    var hasildiskon = biaya_normal * diskon;
                    var totalbayar = biaya_normal - hasildiskon;
                    //hasilnya boss
                    $('#SAmount').val(totalbayar);
                  });

                  // add button--------------------
                  $("#add").click(function () {
                    // cek validasi + save data wajin
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
                      //tambah detil ke database
                      var Cost_Sched_Personal_Id = {{ $fnc_cost_sched_personal->Cost_Sched_Personal_Id }};
                      var Cost_Item_Id =  $('#Cost_Item_Id').val();
                      var Amount = parseInt(replaceAll(',', '', $('#SAmount').val().trim()));
                      var Percentage = $('#Percentage').val();
                      $.ajax({
                        type: 'POST',
                        headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('/') }}/set_biaya_registrasi_personal/insert_detail_post_ajax",
                        data: {
                          Cost_Sched_Personal_Id: Cost_Sched_Personal_Id,
                          Cost_Item_Id: Cost_Item_Id,
                          Amount: Amount,
                          Percentage: Percentage,
                        },
                        success: function (res) {

                          if (res.status == 1) {
                            swal('Selesai!', res.exp, 'success');
                            location.reload();
                            // BiayaRegistrasiDetails = [];
                            // var array = ;
                            // for(var i =0; i<array.length;i++){
                            //     BiayaRegistrasiDetails[i] = array[i];
                            // }
                            // GeneratedItemsTable();
                            // //generate array to table view
                            // window.onload = function(){
                            //     GeneratedItemsTable();
                            // };


                          }else {
                              swal('Gagal simpan!!', res.exp, 'error');
                          }
                        },

                        error: function (xhr, ajaxOptions, thrownError) {
                            swal('Error!! '+xhr.status, thrownError, 'error');
                            $('#submit').val('Save');
                        }

                      });

                      // BiayaRegistrasiDetails.push({
                      //     Cost_Item_id: parseInt($('#Cost_Item_Id').val().trim()),
                      //     Cost_Item_Name: $("#Cost_Item_Id option:selected").text(),
                      //     Percentage: $('#Percentage').val(),
                      //     SAmount: $('#SAmount').val().trim(),
                      //     Amount: parseInt(replaceAll(',', '', $('#SAmount').val().trim()))
                      //
                      // });
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


                })




                //function for show added items in table
                function GeneratedItemsTable() {

                    if (BiayaRegistrasiDetails.length > 0) {
                        var $table = $('<table class="table table-striped table-bordered table-hover table-sm table-font-sm">');
                        $table.append('<thead class="thead-default thead-green"><tr><th>Nama Biaya</th><th>Diskon</th><th>Biaya</th><th colspan="2"><i class="glyphicon glyphicon-cog" ></i></th></tr></thead>');
                        var $tbody = $('<tbody/>');
                        var $total = 0;
                        $.each(BiayaRegistrasiDetails, function (i, val) {
                            var $row = $('<tr/>');
                            $row.append($('<td/>').html(val.Cost_Item_Name));
                            $row.append($('<td/>').attr('align', 'right').html(val.Percentage));
                            $row.append($('<td/>').attr('align', 'right').html(val.Amount));
                            var $remove = $('<a href="#" class="btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span></a>');
                            var $edit = $('<a href="#" class="btn-sm btn-primary" data-toggle="modal" data-target="#exampleModalCenter"><span class="fa fa-pencil-square-o"></span></a>');
                            $remove.click(function (e) {
                                e.preventDefault();
                                // delete detail-------------------------
                                $.ajax({
                                  type: 'GET',
                                  url: "{{ url('/') }}/set_biaya_registrasi_personal/delete_detail_ajax/" + val.Cost_Sched_Personal_Detail_Id,
                                  success: function (res) {
                                    if (res.status == 1) {
                                      swal('Berhasil Hapus Data !', res.exp, 'success');
                                      location.reload();

                                    }else {
                                        swal('Gagal Hapus!!', res.exp, 'error');
                                    }
                                  },

                                  error: function (xhr, ajaxOptions, thrownError) {
                                      swal('Error!! '+xhr.status, thrownError, 'error');
                                      $('#submit').val('Save');
                                  }

                                });
                                // BiayaRegistrasiDetails.splice(i, 1);
                                // GeneratedItemsTable();
                            });
                            //
                            $edit.click(function (e) {
                                e.preventDefault();
                                $('#Cost_Item_Id_M').val(val.Cost_Item_id);
                                $('#Percentage_M').val(val.Percentage);
                                $("#Amount_M").val(val.Amount);
                                $("#Cost_Sched_Personal_Detail_Id_M").val(val.Cost_Sched_Personal_Detail_Id);

                                var Cost_Item_Id = $("#Cost_Item_Id_M").val();
                                var regnum = <?php echo $acd_student->Register_Number; ?>;
                                var termyear = <?php echo $mstr_term_year->Term_Year_Id; ?>;
                                var url = {!! json_encode(url('/')) !!};

                                // sistem tampilkan data pembayaran ------------
                                $.ajax({
                                  type: 'GET',
                                  url: url + '/set_biaya_registrasi_personal/create_amount?Register_Number='+ regnum +'&Term_Year_Id='+termyear+'&Cost_Item_Id='+Cost_Item_Id,
                                  success: function(data) {
                                    if (data != 0) {
                                      $('#tempat_biaya_M').css("display","inline");
                                      $('#tempat_diskon_M').css("display","inline");
                                      $('#biaya_asli_M').text("Biaya Normal : Rp. "+ thousandFormat(data)).css({ 'font-weight': 'bold' });
                                      $('#biaya_asli_amount_M').val(data);
                                    }else{
                                      $('#tempat_biaya_M').css("display","none");
                                      $('#tempat_diskon_M').css("display","none");
                                    }

                                  }
                                });
                                // e.preventDefault();
                                // BiayaRegistrasiDetails.splice(i, 1);
                                // GeneratedItemsTable();
                            });
                            $row.append($('<td style="width:45px;"/>').attr('align', 'center').html($remove));
                            $row.append($('<td style="width:45px;"/>').attr('align', 'center').html($edit));
                            $tbody.append($row);
                            $total += val.Amount;
                        });
                        var $frow = $('<tr/>');
                        $frow.append($('<td/>').attr('align', 'right').html('<b>Total</b>'));
                        $frow.append($('<td/>').html(''));
                        $frow.append($('<td/>').attr('align', 'right').html(thousandFormat($total)));
                        $frow.append($('<td colspan="2"/>').html(''));
                        $tbody.append($frow);
                        // console.log("current", BiayaRegistrasiDetails);
                        $table.append($tbody);
                        $('#BiayaRegistrasiDetails').html($table);
                    }
                    else {
                        $('#BiayaRegistrasiDetails').html('');
                    }
                }

                //funtion hapus string amaount
                function replaceAll(find, replace, str) {
                    while (str.indexOf(find) > -1) {
                        str = str.replace(find, replace);
                    }
                    return str;
                }

                // funtion tausan format
                function thousandFormat(n) {
                    var rx = /(\d+)(\d{3})/;
                    return String(n).replace(/^\d+/, function (w) {
                        while (rx.test(w)) {
                            w = w.replace(rx, '$1.$2');
                        }
                        return w;
                    });
                }

                // in the modal sistem--------------------------------------

                // $('#Cost_Item_Id').change(function () {
                //   var Cost_Item_Id = $("#Cost_Item_Id").val();
                //   var regnum = <?php echo $acd_student->Register_Number; ?>;
                //   var termyear = <?php echo $mstr_term_year->Term_Year_Id; ?>;
                //   var url = {!! json_encode(url('/')) !!};
                //
                //   // sistem tampilkan data pembayaran ------------
                //   $.ajax({
                //     type: 'GET',
                //     url: url + '/set_biaya_registrasi_personal/create_amount?Register_Number='+ regnum +'&Term_Year_Id='+termyear+'&Cost_Item_Id='+Cost_Item_Id,
                //     success: function(data) {
                //       if (data != 0) {
                //         $('#tempat_biaya').css("display","inline");
                //         $('#tempat_diskon').css("display","inline");
                //         $('#biaya_asli').val(data);
                //       }else{
                //         $('#tempat_biaya').css("display","none");
                //         $('#tempat_diskon').css("display","none");
                //       }
                //
                //     }
                //   });

                $('#Cost_Item_Id_M').change(function () {
                  Cost_Item_Id = $("#Cost_Item_Id_M").val();
                  regnum = <?php echo $acd_student->Register_Number; ?>;
                  termyear = <?php echo $mstr_term_year->Term_Year_Id; ?>;
                  url = {!! json_encode(url('/')) !!};

                  // sistem tampilkan data pembayaran ------------
                  $.ajax({
                    type: 'GET',
                    url: url + '/set_biaya_registrasi_personal/create_amount?Register_Number='+ regnum +'&Term_Year_Id='+termyear+'&Cost_Item_Id='+Cost_Item_Id,
                    success: function(data) {
                      if (data != 0) {
                        $('#tempat_biaya_M').css("display","inline");
                        $('#tempat_diskon_M').css("display","inline");
                        $('#biaya_asli_M').text("Biaya Normal : Rp. "+ thousandFormat(data)).css({ 'font-weight': 'bold' });
                        $('#biaya_asli_amount_M').val(data);
                      }else{
                        $('#tempat_biaya_M').css("display","none");
                        $('#tempat_diskon_M').css("display","none");
                      }

                    }
                  });


                });

                // siste diskon --------------------
                $('#Percentage_M').keyup(function () {
                  var biaya_normal = $('#biaya_asli_amount_M').val();
                  var diskon = $('#Percentage_M').val() / 100;

                  var hasildiskon = biaya_normal * diskon;
                  var totalbayar = biaya_normal - hasildiskon;
                  //hasilnya boss
                  $('#Amount_M').val(totalbayar);
                });

                $('#update_detail').click(function () {
                  var Cost_Sched_Personal_Detail_Id = $("#Cost_Sched_Personal_Detail_Id_M").val();
                  var Cost_Sched_Personal_Id = <?php echo  $fnc_cost_sched_personal->Cost_Sched_Personal_Id ?>;
                  var Cost_Item_Id = $("#Cost_Item_Id_M").val();
                  var Amount = $('#Amount_M').val();
                  var Percentage = $('#Percentage_M').val();
                  var url = {!! json_encode(url('/')) !!};

                  // var url = url + '/set_biaya_registrasi_personal/edit_detail_post_ajax/'+Cost_Sched_Personal_Detail_Id;
                  $.ajax({
                    type: "POST",
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                      Cost_Sched_Personal_Id: Cost_Sched_Personal_Id,
                      Cost_Item_Id: Cost_Item_Id,
                      Amount: Amount,
                      Percentage: Percentage
                    },
                    url: url + '/set_biaya_registrasi_personal/edit_detail_post_ajax/'+Cost_Sched_Personal_Detail_Id,
                    success: function (res) {
                      if (res.status == 1) {
                        swal('Berhasil Update Data !', res.exp, 'success');
                        location.reload();

                      }else {
                          swal('Gagal Hapus!!', res.exp, 'error');
                      }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                      swal('Error!! '+xhr.status, thrownError, 'error');
                    }
                  });
                });

              });
            </script>

      </div>
    </div>
  </div>
</section>
@endsection
