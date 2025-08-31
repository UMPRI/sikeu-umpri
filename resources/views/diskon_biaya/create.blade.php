@extends('shared._layout')
@section('pageTitle', 'Diskon_Biaya')
@section('content')

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
            <a href="#" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
            </div>
            <div class="bootstrap-admin-box-title right text-white">
            <b>Create</b>
            </div>
        </div>
        <br>
        <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
                <div class="panel panel-default panel-green" >
                    
                    {{-- masterr--------------------------------------------------------- --}}
                    <div class="panel-heading" style="padding:10px 20px;">
                        <div class="pull-right">
                        </div>
                        <div class="bootstrap-admin-box-title right">
                            <b>Master</b>
                        </div>
                    </div>
                    <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content" style="background-color:white;padding:10px;color:#333;">
                        <div class="form-horizontal">
                            <form class="" action="" method="post">
                                <div class="form-group">
                                    <label class = "control-label col-md-12">Kode Diskon</label>
                                    <div class="col-md-12">
                                        <input type="text" name="Discount_Code" class="form-control form-control-sm" placeholder="Masukan Kode Diskon" id="Discount_Code">
                                        <span id="Discount_Code_Msg" style="visibility: hidden ;color: red;font-family: 'Courier New', Courier, monospace">Kode Diskon Harus Diisi</span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class = "control-label col-md-12">Nama Diskon</label>
                                    <div class="col-md-12">
                                        <input type="text" name="Cost_Scheme_Discount_Name" class="form-control form-control-sm" placeholder="Masukan Nama Diskon" id="Cost_Scheme_Discount_Name">
                                        <span id="Cost_Scheme_Discount_Name_Msg" style="visibility: hidden ;color: red;font-family: 'Courier New', Courier, monospace">Nama Diskon Harus Diisi</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class = "control-label col-md-12">Masa Berlaku</label>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <input type="number" min="1" max="8" name="Term_Total" class="form-control form-control-sm" placeholder="Masukan Masa Berlaku" id="Term_Total">
                                                <span id="Term_Total_Msg" style="visibility: hidden ;color: red;font-family: 'Courier New', Courier, monospace">Masa Berlaku Harus Diisi</span>
                                            </div>
                                            <div class= "col-md-4">Semester</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <input type="hidden" id="href" value="{{ asset('diskon_biaya') }}">

                            </form>
                        </div>
                    </div>


                    {{-- detail item ------------------------------------------- --}}
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
                            <label class = "control-label col-md-8">Nama Biaya</label>
                            <div class="col-md-5">
                                <select class="form-control form-control-sm" name="Cost_Item_Id" id="Cost_Item_id">
                                <option value="">-- Pilih Biaya --</option>
                                <?php 
                                foreach ($d_costItem as $costItem) {?>
                                    <option value="<?php echo $costItem->Cost_Item_Id?>"><?php echo $costItem->Cost_Item_Name?></option>
                                <?php }?>
                                </select>
                                <span id="Cost_Item_id_message" style="visibility:hidden;color:red">Harus Diisi</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class = "control-label col-md-8">Diskon</label>
                                <div class="col-md-5">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <input type="number" name="Discount" id="Discount" value="0" min="0" class="form-control form-control-sm auto">
                                            <input type="hidden" name="Amount" id="Amount" value="0">
                                        </div>
                                        <div class="col-md-2">%</div>
                                    </div>
                                    <span id="Discount_message" style="visibility:hidden;color:red">Harus Diisi</span>
                                </div>
                                
                            
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-8">
                                <button type="button" id="add" class="btn btn-primary">
                                    Tambah Item <span class="glyphicon glyphicon-plus" style="color:white" aria-hidden="true"></span>
                                </button>

                            </div>
                        </div>
                    </div>
                    
                    <div id="DiscountSchemeDetail" class="table-responsive">

                    </div>

                    {{-- --------------pilih mahasiswa--------------------------------------- --}}
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
                                <input type="hidden" name="Term_Year_Id" value="">
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
                                <span class="exist" style="visibility: hidden">Mahasiswa sudah ada dalam list</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-offset-4 col-md-8">
                                    <button type="button" id="add2" class="btn btn-primary">
                                        Tambah <span class="glyphicon glyphicon-plus" style="color:white" aria-hidden="true"></span>
                                    </button>

                                </div>
                            </div>
                            <span class="error">Mahasiswa harus dipilih</span>
                        </div>
                        
                        <div id="Mahasiswa" class="table-responsive">

                        </div>

                        {{-- kendo mahasiswa--------------------------------------- --}}
                        <script type="text/javascript">
                            $(document).ready(function () {
                            $("#Register_Number").width(250).kendoDropDownList();
            
                            $("#Department_Id").width(250).kendoDropDownList({
                                dataTextField: "Department_Name",
                                dataValueField: "Department_Id",
                                dataSource: {
                                    transport: {
                                        read: {
                                            dataType: "json",
                                            url: "{{asset('diskon_biaya/Department_Id')}}",
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
                                                url: "{{asset('diskon_biaya/Register_Number')}}",
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
                        </script>

                    </div>
                    <div style="text-align:right">
                        <br />
                        <button type="button" id="submit" class="btn btn-success">
                            Simpan Diskon<span class="glyphicon glyphicon-floppy-save" style="color:white" aria-hidden="true"></span>
                        </button>
                    </div>
                    </div>
                </div>
        </div>
    </div>



    <script type="text/javascript">   
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
            // tampil tabel item biaya==========================
            var DiscountSchemeDetail = [];
            
            //Remove button click function item biaya
            $('#remove').click(function () {
                DiscountSchemeDetail.pop();

                //populate order items
                GeneratedItemsTable();
            });

            //Add button click function itam biaya
            $('#add').click(function () {
                
                //Check validation of order item
                var isValidItem = true;
                
                if ($('#Cost_Item_id').val().trim() == '') {
                    isValidItem = false;
                    $('#Cost_Item_id_message').css('visibility', 'visible');
                }
                else {
                    $('#Cost_Item_id_message').css('visibility', 'hidden');
                }

                if ($('#Discount').val() == '0') {
                    isValidItem = false;
                    $('#Discount_message').siblings('span.error').css('visibility', 'visible');
                }
                else {
                    $('#Discount_message').siblings('span.error').css('visibility', 'hidden');
                }


                //Add item to list if valid item biaya
                if (isValidItem) {
                    DiscountSchemeDetail.push({
                        Cost_Item_id: parseInt($('#Cost_Item_id').val().trim()),
                        Cost_Item_Name: $("#Cost_Item_id option:selected").text(),
                        Discount: $('#Discount').val().trim(),
                        Amount: parseInt($('#Discount').val()),
                    });
                    //Clear fields
                    $('#Cost_Item_id').val('').focus();
                    $('#Discount').val('0');

                }
                //populate order items 
                GeneratedItemsTable();

            });


            // tampil data mahasiswa ===========================================
            var Mahasiswa = [];
            
            //Remove button click function Mahasiswa
            $('#remove2').click(function () {
                Mahasiswa.pop();

                //populate order items
                GeneratedItemsTable2();
            });

            //Add button click function
            $('#add2').click(function () {
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
                GeneratedItemsTable2();

            });



            //Save button click function
            $('#submit').click(function () {
                //validation of order
                var isAllValid = true;

                var Discount_Code = $("#Discount_Code").val();
                var Cost_Scheme_Discount_Name = $("#Cost_Scheme_Discount_Name").val();
                var Term_Total = $("#Term_Total").val();
                
                if (Discount_Code == ''){
                    $('#Discount_Code_Msg').css('visibility', 'visible');
                    isAllValid = false;
                }else{
                    $('#Discount_Code_Msg').css('visibility', 'hidden');
                }

                if (Cost_Scheme_Discount_Name == ''){
                    $('#Cost_Scheme_Discount_Name_Msg').css('visibility', 'visible');
                    isAllValid = false;
                }else{
                    $('#Cost_Scheme_Discount_Name_Msg').css('visibility', 'hidden');
                }

                if (Term_Total == ''){
                    $('#Term_Total_Msg').css('visibility', 'visible');
                    isAllValid = false;
                }else{
                    $('#Term_Total_Msg').css('visibility', 'hidden');
                }

                if (DiscountSchemeDetail.length == 0) {
                    $('#DiscountSchemeDetail').html('<span style="color:red;">Detail Item belum ditambahkan</span>');
                    isAllValid = false;
                }

                if (Mahasiswa.length == 0) {
                    $('#Mahasiswa').html('<span style="color:red;">Data Mahasiswa  belum ditambahkan</span>');
                    isAllValid = false;
                }

                
                
                // Save if valid ----------------------------------------------------------
                if (isAllValid) {
                    var data_mahasiswa = JSON.stringify(Mahasiswa);
                    var data_item_biaya = JSON.stringify(DiscountSchemeDetail);

                    $(this).val('Please wait...');
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {
                            Discount_Code : Discount_Code,
                            Cost_Scheme_Discount_Name : Cost_Scheme_Discount_Name,
                            Term_Total : Term_Total,
                            Mahasiswa : data_mahasiswa,
                            Item_Biaya : data_item_biaya
                        },
                        type: "POST",
                        url: "{{ url('diskon_biaya/create_post') }}",
                        beforeSend : function() {
                            $(".post_submitting").show().html("<center><i class='fa fa-refresh fa-spin' style='font-size:24px'></i></center>");
                        },
                        success: function(d){
                            //status from controller --------------------
                            if (d.status == 1) {
                                swal('Selesai!', d.exp, 'success');

                                window.location.href = $('#href').val();
                            } else {
                                swal('Gagal simpan!!', d.exp, 'error');
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            swal('Error!! '+xhr.status, thrownError, 'error');
                        }
                    });
                }

            });



            //function for show added items in table item biaya
            function GeneratedItemsTable() {
                if (DiscountSchemeDetail.length > 0) {
                    var $table = $('<table class="table table-striped table-bordered table-hover table-sm table-font-sm">');
                    $table.append('<thead class="thead-default thead-green"><tr><th>Nama Biaya</th><th>Diskon</th><th><i class="glyphicon glyphicon-cog"></i></th></tr></thead>');
                    var $tbody = $('<tbody/>');
                    var $total = 0;
                    $.each(DiscountSchemeDetail, function (i, val) {
                        var $row = $('<tr/>');
                        $row.append($('<td/>').html(val.Cost_Item_Name));
                        $row.append($('<td/>').attr('align', 'right').html(val.Discount));
                        var $remove = $('<a href="#" class="btn-sm btn-danger">Hapus <span class="glyphicon glyphicon-trash"></span></a>');
                        $remove.click(function (e) {
                            e.preventDefault();
                            DiscountSchemeDetail.splice(i, 1);
                            GeneratedItemsTable();
                        });
                        $row.append($('<td/>').attr('align', 'center').html($remove));
                        $tbody.append($row);
                    });
                    var $frow = $('<tr/>');
                    $tbody.append($frow);
                    console.log("current", DiscountSchemeDetail);
                    $table.append($tbody);
                    $('#DiscountSchemeDetail').html($table);
                }
                else {
                    $('#DiscountSchemeDetail').html('');
                }
            }


            //function for show added items in table mahasiswa
            function GeneratedItemsTable2() {
                    if (Mahasiswa.length > 0) {
                        var $table = $('<table class="table table-striped table-bordered table-hover table-sm table-font-sm">');
                        $table.append('<thead class="thead-default thead-green"><tr><th>NO</th><th>Mahasiswa</th><th><i class="glyphicon glyphicon-cog"></i></th></tr></thead>');
                        var $tbody = $('<tbody/>');
                        var $no = 1;
                        $.each(Mahasiswa, function (i, val) {
                            var $row = $('<tr/>');
                            $row.append($('<td/>').attr('align', 'center').html($no));
                            $row.append($('<td/>').html(val.NimName));
                            var $remove2 = $('<a href="#" class="btn-sm btn-danger">Hapus <span class="glyphicon glyphicon-trash"></span></a>');
                            $remove2.click(function (e) {
                                e.preventDefault();
                                Mahasiswa.splice(i, 1);
                                GeneratedItemsTable2();
                            });
                            $row.append($('<td/>').attr('align', 'center').html($remove2));
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