@extends('shared._layout')
@section('pageTitle', 'Item_Biaya')
@section('content')

<?php
  $access = auth()->user()->akses();
  $acc = $access;
?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid-title">
  <div class="title-laporan">
      <h3 class="text-white">Item Biaya</h3>
    </div>
  </div>
  <div class="container"> 
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          @if(in_array('Item Biaya-CanAdd',$acc)) <a href="{{ asset('/item_biaya/create')}}" class="btn btn-primary btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Item Biaya</b>
        </div>
      </div>
      <div class="bootstrap-admin-no-table-panel-content">
        <form class="row text-green" action="" method="GET" style="padding-top:30px;padding-left:15px;">
          <label style="width:90px;display: inline-block;">Tampilkan :</label>
          <select class="form-control form-control-sm col-md-2" value="{{ $rowpage }}" name="rowpage" onchange="this.form.submit()">
            <option value="10"  <?php if( $rowpage  == "10"){ echo "selected";}?>>10 Baris</option>
            <option value="20"  <?php if( $rowpage  == "20"){ echo "selected";}?>>20 Baris</option>
            <option value="50"  <?php if( $rowpage  == "50"){ echo "selected";}?>>50 Baris</option>
            <option value="100" <?php if( $rowpage  == "100"){ echo "selected";}?>>100 Baris</option>
            <option value="9999999999"  <?php if( $rowpage  == "9999999999"){ echo "selected";}?>>Semua Baris</option>
          </select>&nbsp;&nbsp;

          <label style="width:50px;display: inline-block;"> Cari :</label>
          <input type="text" name="search" class="form-control form-control-sm col-md-3" placeholder="Cari Berdasarkan Kode Biaya">&nbsp;
          {{-- <input type="number" name="rowpage" min="1" class="form-control form-control-sm col-md-1" value="{{ $rowpage }}" placeholder="Baris Halaman">&nbsp; --}}
          {{-- <input type="submit" name="" class="btn btn-primary btn-sm" value=""> --}}
          <button type="submit" name="" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
        </form>
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
          <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="dataTables-example">
            <thead class="thead-default thead-green">
              <tr>
                <th width="w-1">No.</th>
                <th width="w-4">Kode Biaya</th>
                <th width="w-4">Nama</th>
                <th width="w-4">Akronim</th>
                <th width="w-4"><i class="glyphicon glyphicon-cog"></i></th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i=1;
              $page = 1;
              if (isset($_GET['page'])) {
                $page = $_GET['page'];
              }
              $no = ($page- 1) * $rowpage + 1;
              foreach ($q_costItem as $cost_item) {
                ?>
                <tr>
                  <td class="td-nom"><b><?php echo $no; ?>.</b></td>
                  <td>{{ $cost_item->Cost_Item_Code }}</td>
                  <td>{{ $cost_item->Cost_Item_Name }}</td>
                  <td>{{ $cost_item->Acronym }}</td>
                  <td align="center" width="20%">
                    <table style="border:0px; background-color:none;">
                      <tr style="border:0px; background-color:none;">
                        <td style="border:0px; background-color:none;">
                            @if(in_array('Item Biaya-CanEdit',$acc))<a href="{{ asset('/item_biaya/edit/'.$cost_item->Cost_Item_Id)}}" class="btn btn-info btn-sm">EDIT</a>@endif
                        </td>
                        <td style="border:0px; background-color:none;">
                          <form class="" action="{{ asset('/item_biaya/delete/'.$cost_item->Cost_Item_Id)}}" method="post" id="delete<?php echo $i ?>">
                            {{ csrf_field() }}
                              @if(in_array('Item Biaya-CanDelete',$acc))<button type="button" onclick="confirmdelete(<?php echo $i ?>)" id="confirm<?php echo $i ?>" class="btn btn-danger btn-sm">HAPUS</button>@endif
                          </form>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <?php
                $no++;
                $i++;
              }
               ?>
            </tbody>
          </table>
        <?php } ?>
        </div>
        {{$q_costItem->render("pagination::bootstrap-4")}}
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
