@extends('shared._layout')
@section('pageTitle', 'Item_Biaya')
@section('content')


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
          <a href="{{ asset('/item_biaya')}}" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Create</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <?php
          if (isset($pesan)) {
            echo $pesan;
          }
         ?>
        <form class="form-horizontal" method="POST" action="create_post">
          {{ csrf_field() }}
            <div class="form-group">
                <label for="email" class="col-md-4 control-label">Kode Item</label>
                <div class="col-md-12">
                    <input id="" type="text" class="form-control form-control-sm" name="item_code" value="" required oninvalid="this.setCustomValidity('Kode tidak boleh kosong')" oninput="setCustomValidity('')">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-4 control-label">Nama Item</label>
                <div class="col-md-12">
                    <input id="" type="text" class="form-control form-control-sm" name="item_name" value="" required oninvalid="this.setCustomValidity('Nama item tidak boleh kosong')" oninput="setCustomValidity('')">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-4 control-label">Akronim</label>
                <div class="col-md-12">
                    <input id="" type="text" class="form-control form-control-sm" name="acronym" value="" required oninvalid="this.setCustomValidity('Akronim tidak boleh kosong')" oninput="setCustomValidity('')">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12 " style="text-align:center;">
                    <button type="submit" class="btn btn-success">Tambah</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
