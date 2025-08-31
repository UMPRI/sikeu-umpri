@extends('shared._layout')
@section('pageTitle', 'Role')
@section('content')
  <style>
    body { background-color:#fafafa;}
    .stylish-input-group .input-group-addon{
        background: white !important;
    }
    .stylish-input-group .form-control{
        //border-right:0;
        box-shadow:0 0 0;
        border-color:#ccc;
    }
    .stylish-input-group button{
        border:0;
        background:transparent;
    }

    .h-scroll {
        background-color: #fcfdfd;
        height: 260px;
        overflow-y: scroll;
    }
    ul {
      list-style-type: none;
      margin: 3px;
    }
    ul.checktree li:before {
      height: 1em;
      width: 12px;
      border-bottom: 1px dashed;
      content: "";
      display: inline-block;
      top: -0.3em;
    }
    ul.checktree li { border-left: 1px dashed; }
    ul.checktree li:last-child:before { border-left: 1px dashed; }
    ul.checktree li:last-child { border-left: none; }
  </style>
<?php

foreach ($query as $data_edit) {


  $accekeu = array();
  foreach ($accesskeu as $value) {
    $accekeu[] = $value->id;
  }
?>
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Detail Role</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('administrator/role?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Detail</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
          @endif
          <div class="row col-md-12 col-xs-12">
            {!! Form::label('', 'Nama', ['class' => 'col-md-4 col-xs-12']) !!}:
            <label for="" class="col-md-4 col-xs-12">{{$data_edit->name}}</label>
          </div>
          <div class="row col-md-12 col-xs-12">
            {!! Form::label('', 'Deskripsi', ['class' => 'col-md-4 col-xs-12']) !!}:
            <label for="" class="col-md-4 col-xs-12">{{$data_edit->description}}</label>
          </div>
          <div class="row col-md-12 col-xs-12">
          {!! Form::label('', 'Otoritas', ['class' => 'col-md-4 col-xs-12']) !!}:
          </div>
          <div class=" col-md-12 col-xs-12" style="padding:2%;">
            <div>
              <div id="treeview_container" class="hummingbird-treeview well h-scroll-large">
                <ul id="treeview" class="hummingbird-base checktree">

                  <li>
                    <i class="fa fa-plus"></i> <label> <input id="keuangan" data-id="custom-0" type="checkbox"> KEUANGAN</label>
                    <ul>
                      <li>
                          <i class="fa fa-plus"></i> <label> <input id="node-0-0" data-id="custom-0" type="checkbox">Administrator</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Pengaturan Peran User</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'role-CanView')->first()->id }}"  value="{{ $accesseskeu->where('name', 'role-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'role-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesseskeu->where('name', 'role-CanAdd')->first()->id }}" type="checkbox" value="{{ $accesseskeu->where('name', 'role-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'role-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesseskeu->where('name', 'role-CanEdit')->first()->id }}" type="checkbox" value="{{ $accesseskeu->where('name', 'role-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'role-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesseskeu->where('name', 'role-CanDelete')->first()->id }}" type="checkbox" value="{{ $accesseskeu->where('name', 'role-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'role-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Manajemen User</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesseskeu->where('name', 'user-CanView')->first()->id }}" type="checkbox" value="{{ $accesseskeu->where('name', 'user-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'user-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesseskeu->where('name', 'user-CanAdd')->first()->id }}" type="checkbox" value="{{ $accesseskeu->where('name', 'user-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'user-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesseskeu->where('name', 'user-CanEdit')->first()->id }}" type="checkbox" value="{{ $accesseskeu->where('name', 'user-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'user-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesseskeu->where('name', 'user-CanDelete')->first()->id }}" type="checkbox" value="{{ $accesseskeu->where('name', 'user-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'user-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Ubah Password User</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesseskeu->where('name', 'ubahpassword-CanView')->first()->id }}" type="checkbox" value="{{ $accesseskeu->where('name', 'ubahpassword-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'ubahpassword-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesseskeu->where('name', 'ubahpassword-CanAdd')->first()->id }}" type="checkbox" value="{{ $accesseskeu->where('name', 'ubahpassword-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'ubahpassword-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesseskeu->where('name', 'ubahpassword-CanEdit')->first()->id }}" type="checkbox" value="{{ $accesseskeu->where('name', 'ubahpassword-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'ubahpassword-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesseskeu->where('name', 'ubahpassword-CanDelete')->first()->id }}" type="checkbox" value="{{ $accesseskeu->where('name', 'ubahpassword-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'ubahpassword-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                        </ul>
                      </li>
                      <li>
                        <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Master</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Item Biaya</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Item Biaya-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Item Biaya-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Item Biaya-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Item Biaya-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Item Biaya-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Item Biaya-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Item Biaya-CanEdit')->first()->id }}" value="{{ $accesseskeu->where('name', 'Item Biaya-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'Item Biaya-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Item Biaya-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Item Biaya-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Item Biaya-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                      <li>
                        <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Biaya Mata Kuliah</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Jenis Matakuliah</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Biaya Per SKS</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Biaya Per Sks-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Biaya Per Sks-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Biaya Per Sks-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Biaya Per Sks-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Biaya Per Sks-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Biaya Per Sks-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Biaya Per Sks-CanEdit')->first()->id }}" value="{{ $accesseskeu->where('name', 'Biaya Per Sks-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'Biaya Per Sks-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Biaya Per Sks-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Biaya Per Sks-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Biaya Per Sks-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Biaya Per Paket</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Biaya Per Paket-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Biaya Per Paket-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Biaya Per Paket-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Biaya Per Paket-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Biaya Per Paket-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Biaya Per Paket-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Biaya Per Paket-CanEdit')->first()->id }}" value="{{ $accesseskeu->where('name', 'Biaya Per Paket-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'Biaya Per Paket-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Biaya Per Paket-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Biaya Per Paket-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Biaya Per Paket-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                      <li>
                        <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Biaya Registrasi</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Biaya Registrasi</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanEdit')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanDelete')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanHitungTagihan')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanHitungTagihan')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanHitungTagihan')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanCopyData')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanCopyData')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanCopyData')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Biaya Registrasi(Resume)</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanEdit')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Biaya Tambahan</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanEdit')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                      <li>
                        <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Pembayaran Mahasiswa</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Biaya Registrasi Personal</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanEdit')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                      <li>
                        <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Retur</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Aturan Pengembalian/Return</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanCetak')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanCetak')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanCetak')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanEdit')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Entry Pengembalian/Return</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanAdd')->first()->description }}</label></li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                      <li>
                        <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Teller</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Entry Pembayaran Mahasiswa</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanViewDetail')->first()->id }}" value="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanViewDetail')->first()->id }}"> {{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanViewDetail')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanEditTagihan')->first()->id }}" value="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanEditTagihan')->first()->id }}"> {{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanEditTagihan')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBayaTagihan')->first()->id }}" value="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBayaTagihan')->first()->id }}"> {{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBayaTagihan')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanPrintTagihan')->first()->id }}" value="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanPrintTagihan')->first()->id }}"> {{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanPrintTagihan')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBatalTagihan')->first()->id }}" value="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBatalTagihan')->first()->id }}"> {{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBatalTagihan')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Riwayat Pembayaran Details-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Riwayat Pembayaran Details-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Riwayat Pembayaran Details-CanView')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan</label>
                            <ul>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan Pembayaran Prodi</label>
                                <ul>
                                  <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Laporan Prodi-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Laporan Prodi-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Laporan Prodi-CanView')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan Pembayaran Mahasiswa</label>
                                <ul>
                                  <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Laporan Mahasiswa-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Laporan Mahasiswa-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Laporan Mahasiswa-CanView')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan Pembayaran Per Bank</label>
                                <ul>
                                  <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Laporan Bank-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Laporan Bank-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Laporan Bank-CanView')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Riwayat Pembayaran Mahasiswa</label>
                                <ul>
                                  <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Riwayat Pembayaran Mahasiswa-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Riwayat Pembayaran Mahasiswa-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Riwayat Pembayaran Mahasiswa-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Riwayat Pembayaran Details Create-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Riwayat Pembayaran Details Create-CanView')->first()->id }}"> Export Riwayat Pembayaran Detail</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox"> Pembayaran Mahasiswa Per Item</label>
                                <ul>
                                  <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Pembayaran Mahasiswa Item-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Pembayaran Mahasiswa Item-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Pembayaran Mahasiswa Item-CanView')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox"> Laporan Tunggakan Mahasiswa</label>
                                <ul>
                                  <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanViewDetail')->first()->id }}" value="{{ $accesseskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanViewDetail')->first()->id }}"> {{ $accesseskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanViewDetail')->first()->description }}</label></li>
                                </ul>
                              </li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>

                </ul>
              </div>
            </div>
          </div>
          </div>
      </div>
    </div>
  </div>



<!-- /.row -->
<?php $acceekeu = json_encode($accekeu);?>
<script>
function contains(arr2, element) {
    for (var i = 0; i < arr2.length; i++) {
        if (arr2[i] === element) {
            return true;
        }
    }
    return false;
}

$(document).ready(function () {
  $("#treeview").hummingbird();
  var arr2 = <?php echo $acceekeu; ?>;

  arr2.forEach(function(entry) {
    if (contains(arr2,entry) == true) {
      $("#treeview").hummingbird("disableNode",{attr:"id",name: entry,state:true});
    }
  });
  // $("#treeview").hummingbird("checkNode",{attr:"id",name: "node-0-1-2-1",state:true});
  // $("#treeview").hummingbird("checkNode",{attr:"id",name: "node-0-1-2-2",state:true});

});
</script>
</section>
<?php } ?>
@endsection
