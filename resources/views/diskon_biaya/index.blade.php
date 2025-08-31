@extends('shared._layout')
@section('pageTitle', 'Diskon_Biaya')

@section('content')
<section class="content">
    <div class="container-fluid-title">
    <div class="title-laporan">
        <h3 class="text-white">Diskon Biaya</h3>
      </div>
    </div>
    <div class="container">
      <div class="panel panel-default bootstrap-admin-no-table-panel">
        <div class="panel-heading-green">
          <div class="pull-right tombol-gandeng dua">
           <a href="{{ asset('diskon_biaya/create') }}" class="btn btn-primary btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>
          </div>
          <div class="bootstrap-admin-box-title right text-white">
            <b>Diskon Biaya</b>
          </div>
        </div>
        <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <form class="row text-green" action="{{ asset('diskon_biaya') }}" method="GET" style="padding-top:30px;padding-left:15px;">
            <label style="width:90px;display: inline-block;">Tampilkan :</label>
            <select class="form-control col-md-2" value="" name="rowpage" onchange="this.form.submit()">
              {{-- {{ $rowpage }} --}}
              <option value="10">10 Baris</option>
              <option value="20">20 Baris</option>
              <option value="50">50 Baris</option>
              <option value="100">100 Baris</option>
              <option value="9999999999">Semua Baris</option>
            </select>&nbsp;&nbsp;
            
  
            <label style="width:50px;display: inline-block;"> Cari :</label>
            <input type="text" name="search" class="form-control form-control-sm col-md-3" placeholder="Cari Berdasarkan Diskon Biaya">&nbsp;
            {{-- <input type="number" name="rowpage" min="1" class="form-control form-control-sm col-md-1" value="{{ $rowpage }}" placeholder="Baris Halaman">&nbsp; --}}
            {{-- <input type="submit" name="" class="btn btn-primary btn-sm" value=""> --}}
            <button type="submit" name="" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
          </form>
          
          <br>
          <button type="submit" class="btn btn-warning btn-sm">Hitung Tagihan <i class="fa fa-list-alt"></i></button>
          <br>

          <br>
          <div class="table-responsive">
            <?php
            if (isset($message)) {
              if ($message != null) {
                ?>
                <div class="alert alert-danger">
                  <h1>
                    Tidak dapat terhubung ke database !
                  </h1>
                </div>
                <?php
              }
            }else{
             ?>
            
            {{-- <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="dataTables-example">
              <thead class="thead-default thead-green">
                <tr>
                  <th width="w-1">No.</th>
                  <th width="w-4">Kode Diskon</th>
                  <th width="w-4">Nama Diskon</th>
                  <th width="w-4"><i class="glyphicon glyphicon-cog"></i></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table> --}}
            {{-- tabel data---------------- --}}
            <div id="example">
              <div id="grid_diskon"></div>
              
              <script type="text/x-kendo-template" id="toolGrid">
                  <div class="float-left">
                    <a role="button" class="k-button k-button-icontext k-grid-add" href="javascript:"><span class="k-icon k-i-plus"></span> Tambah Data</a>
                  </div>
                  <div class="toolbar" style="float:right">
                    <label class="category-label" for="category">Kode Diskon :</label>
                    <input type="search" class="k-textbox" id="search" style="width: 150px" placeholder="" />
                  </div>
              </script>

              <script type="text/x-kendo-template" id="toolGridMember">
                <div class="float-left">
                  <a role="button" class="k-button k-button-icontext k-grid-add" href="javascript:"><span class="k-icon k-i-plus"></span> Tambah member</a>
                </div>
                <div class="toolbar" style="float:right">
                  <label class="category-label" for="category">Nim Member :</label>
                  <input type="search" class="k-textbox" id="searchMember" style="width: 150px" placeholder="" />
                </div>
            </script>

              <script type="text/x-kendo-template" id="menu">
                <div class="tabstrip">
                  <ul>
                    <li class="k-state-active">
                        Member
                    </li>
                    <li>
                        Detail Diskon
                    </li>
                  </ul> 
                  <div>
                      <div class="Member_Discount" id="Member_Discount"></div>
                  </div>
                  <div>
                      <div class="Detail_Discount"></div>
                  </div>
                </div>
            
            </script>

            {{-- pop up themeplate------------------ --}}
            <script type="text/x-kendo-template" id="PopupTemplate">
                #if(data.isNew()) {#
                    #var createTemp = kendo.template($("\#createPopupTemplate").html());#
                    #=createTemp(data)#
                #} else {#
                    #var editTemp = kendo.template($("\#editPopupTemplate").html());#
                    #=editTemp(data)#
                #}#
            </script>

            
            <script type="text/x-kendo-template" id="editPopupTemplate">
              <div class="k-edit-label"><label for="kode diskon">Kode Diskon</label></div>
              <div data-container-for="kode diskon" class="k-edit-field">
                  <input type="text" class="k-input k-textbox input-width-modal" name="kode diskon" required="required" data-bind="value:kode diskon" validationMessage="Field tidak boleh kosong">
              </div>
              <div class="k-edit-label"><label for="nama diskon">Nama Diskon</label></div>
              <div data-container-for="nama diskon" class="k-edit-field">
                  <input type="text" class="k-input k-textbox input-width-modal" name="nama diskon" required="required" data-bind="value:nama diskon" validationMessage="Field tidak boleh kosong">
              </div>
            </script> 

            <script type="text/x-kendo-template" id="createPopupTemplate">
              <div class="k-edit-label"><label for="kode diskon">Kode Diskon</label></div>
              <div data-container-for="kode diskon" class="k-edit-field">
                  <input type="text" class="k-input k-textbox input-width-modal" name="kode diskon" required="required" data-bind="value:kode diskon" validationMessage="Field tidak boleh kosong">
              </div>
              <div class="k-edit-label"><label for="nama diskon">Nama Diskon</label></div>
              <div data-container-for="nama diskon" class="k-edit-field">
                  <input type="text" class="k-input k-textbox input-width-modal" name="nama diskon" required="required" data-bind="value:nama diskon" validationMessage="Field tidak boleh kosong">
              </div>
              
            </script>

            {{-- pop up themeplate Member ------------------ --}}
            <script type="text/x-kendo-template" id="PopupTemplateMember">
              #if(data.isNew()) {#
                  #var createTemp = kendo.template($("\#createPopupTemplateMember").html());#
                  #=createTemp(data)#
              #} else {#
                  #var editTemp = kendo.template($("\#editPopupTemplateMember").html());#
                  #=editTemp(data)#
              #}#
          </script>

          
          <script type="text/x-kendo-template" id="editPopupTemplateMember">
            <div class="k-edit-label"><label for="prodi">Prodi</label></div>
            <div data-container-for="prodi" class="k-edit-field">
                <input type="text" class="k-input k-textbox input-width-modal" name="prodi" required="required" data-bind="value:prodi" id="Department_Id" validationMessage="Field tidak boleh kosong">
            </div>
            <div class="k-edit-label"><label for="nama diskon">Nama Diskon</label></div>
            <div data-container-for="nama diskon" class="k-edit-field">
                <input type="text" class="k-input k-textbox input-width-modal" name="nama diskon" required="required" data-bind="value:nama diskon" validationMessage="Field tidak boleh kosong">
            </div>
          </script> 

          <script type="text/x-kendo-template" id="createPopupTemplateMember">
            <div class="k-edit-label"><label for="prodi">Prodi</label></div>
            <div data-container-for="prodi" class="k-edit-field">
                <input type="text" class="k-input k-textbox input-width-modal" name="prodi" required="required" data-bind="value:prodi" id="Department_Id" validationMessage="Field tidak boleh kosong">
            </div>
            <div class="k-edit-label"><label for="nama diskon">Nama Diskon</label></div>
            <div data-container-for="nama diskon" class="k-edit-field">
                <input type="text" class="k-input k-textbox input-width-modal" name="nama diskon" required="required" data-bind="value:nama diskon" validationMessage="Field tidak boleh kosong">
            </div>
            
          </script>
          
              <script>
                $(document).ready(function() {

                  $("#search").keyup(function() {
                      var searchValue = $('#search').val();
                      console.log(searchValue);
                      $("#grid_diskon").data("kendoGrid").dataSource.filter({
                          field: "Discount_Code",
                          operator: "contains",
                          value: searchValue
                      });
                  }); //end SEARCH

                  // $("#searchMember").keyup(function() {
                  //     var searchValue = $('#searchMember').val();
                  //     console.log(searchValue);
                  //     $("#Member_Discount").data("kendoGrid").dataSource.filter({
                  //         field: "Register_Number",
                  //         operator: "contains",
                  //         value: searchValue
                  //     });
                  // }); //end SEARCHMEMBER

                })

                var grid = $('#grid_diskon').kendoGrid({
                  dataSource: {
                      transport: {
                          read: function(options) {
                              $.ajax({
                                  dataType: 'json',
                                  url: 'diskon_biaya/getDiscount',
                                  type: 'GET',
                                  success: function(res) {
                                      options.success(res);
                                  }
                              })
                          }, //read

                          create: function(options) {
                            $.ajax({
                              headers: {
                                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                                },
                              url: "{{ route('discount.create') }}",
                              type: "POST",
                              dataType: "json",
                              data: options.data,
                              success: function (res) {
                                options.success(res);
                                $("#grid_diskon").data("kendoGrid").dataSource.read();
                              },
                              error: function (xhr, ajaxOptions, thrownError) {
                                kendo.alert("Gagal .");
                              }
                            });
                          },

                      }, //transport grid
                      schema: {
                          model: {
                                    id: "Cost_Scheme_Discount_Id",
                                    fields: {
                                        id: { editable: false, nullable: true },
                                        Discount_Code: { type: "text", validation: { required: true } },
                                        Cost_Scheme_Discount_Name: {  type: "text", validation: { required: true } },
                                    }
                                }
                      },
                      serverPaging: true,
                      pageSize: 10,
                  }, //dataSourceGrid
                  sortable: true,
                  pageable: {
                      refresh: true,
                      pageSizes: true,
                      buttonCount: 5
                  },
                  detailTemplate: kendo.template($("#menu").html()),
                  detailInit: detailInit,
                  toolbar: kendo.template($("#toolGrid").html()),
                  // toolbar: ["save", "cancel"],
                  dataBinding: function() {
                      if (this.dataSource.pageSize() != null) {
                          record = (this.dataSource.page() - 1) * this.dataSource.pageSize();
                      } else {
                          record = (this.dataSource.page() - 1);
                      }
                  },
                  columns: [{
                          template: "#= ++record #",
                          title: "No",
                          width: 50
                      },
                      {
                          field: 'Discount_Code',
                          title: 'Kode Diskon',
                      }, {
                          field: 'Cost_Scheme_Discount_Name',
                          title: 'Nama Diskon',
                      }

                  ],
                  editable: {
                    mode: "popup",
                    template: $("#PopupTemplate").html()
                  },
                  edit: function (e) {
                    $('.k-window-title').text(e.model.isNew() ? "Tambah" : "Edit");
                  },
                })//end grid


                //detail data
                function detailInit(e){
                  var detailRow = e.detailRow;
                  var id = e.data.Cost_Scheme_Discount_Id;
                  
                  detailRow.find(".tabstrip").kendoTabStrip({
                      animation: {
                          open: {
                              effects: "fadeIn"
                          }
                      }
                  });
                  
                  detailRow.find(".Member_Discount").kendoGrid({
                        dataSource: {
                            transport: {
                                read: function(options) {
                                    $.ajax({
                                        dataType: "json",
                                        url: '{{url('')}}/diskon_biaya/memberDiscount/'+ id ,
                                        type: "GET",
                                        data: options.data,
                                        success: function(res) {
                                            options.success(res);
                                        }
                                    });
                                },//read
                            },
                        schema: {
                            model: {
                                      id: "Cost_Scheme_Discount_Member_Id",
                                      fields: {
                                          id: { editable: false, nullable: true },
                                          Register_Number: { type: "text", validation: { required: true } },
                                          Cost_Scheme_Discount_Id: {  type: "text", validation: { required: true } },
                                      }
                                  }
                        },
                        batch: true,
                        serverPaging:true,
                        pageSize:5,
                    },
                    navigatable: true,
                    pageable: {
                        refresh: true,
                        pageSizes: true,
                        buttonCount: 5
                    },
                    toolbar: kendo.template($("#toolGridMember").html()),
                    scrollable: false,
                    sortable: true,
                    columns: [
                                  { field: "Nim", title: "Nim", format: "{0:c}", width: 120 },
                                  { field: "Full_Name", title: "Nama", width: 120 }
                              ],
                    editable: {
                      mode: "popup",
                      template: $("#PopupTemplateMember").html()
                    },
                    edit: function (e) {
                      $('.k-window-title').text(e.model.isNew() ? "Tambah Member" : "Edit Member");
                    },
                    // editable: true
                });

                detailRow.find(".Detail_Discount").kendoGrid({
                        dataSource: {
                            transport: {
                                read: function(options) {
                                    $.ajax({
                                        dataType: "json",
                                        url: '{{url('')}}/diskon_biaya/detailDiscount/'+ id ,
                                        type: "GET",
                                        data: options.data,
                                        success: function(res) {
                                            options.success(res);
                                        }
                                    });
                                },//read
                                
                                // update:function(options){
                                //     $.ajax({
                                //         headers: {
                                //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                //         },
                                //         dataType:'json',
                                //         url:'Entri/storePresensi',
                                //         type:'POST',
                                //         data:options.data,
                                //         success:function(res){
                                //             options.success(res);
                                //             $(".presensi").data("kendoGrid").dataSource.read();
                                //         }
                                //     })
                                // },
                                
                            },
                        // schema :{
                        //     data: 'data',
                        //     total: 'total',
                        //     model: {
                        //         id: 'Krs_Id',
                        //         fields: {
                        //             Nim: {
                        //                 editable: false,
                        //             },
                        //             Full_Name: {
                        //                 editable: false,
                        //             },

                        //         Attendance: {
                        //             type: "number",
                        //             validation: {
                        //                 min: 1,
                        //                 max: 14
                        //             }
                        //         },
                        //         Attendance_Score: {
                        //                 editable: false,
                        //         },
                        //     }
                        //   }
                        // },
                        batch: true,
                        serverPaging:true,
                        pageSize:5,
                    },
                    navigatable: true,
                    pageable: {
                        refresh: true,
                        pageSizes: true,
                        buttonCount: 5
                    },
                    toolbar: ["save", "cancel"],
                    scrollable: false,
                    sortable: true,
                    columns: [
                                  { field: "Cost_Item_Name", title: "Nama Item", width: 120 },
                                  { field: "Cost_Item_Name", title: "Nama Item", width: 120 }
                              ],
                    editable: true
                });
                }

                
              </script>


              
          </div>
            
          <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
        function confirmdelete(param) {
          var form = $('#confirm'+param).parents('form');
          swal({
            title: "Apa anda yakin?",
            text: "Data akan terhapus , anda tidak akan bisa mengembalikan data yang sudah di hapus",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal Hapus!",
            closeOnConfirm: false
          },function(){
            $("#delete"+param).submit();
          });
        }
    </script>
  </section>
@endsection
