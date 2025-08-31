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


<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Role</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('administrator/role?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Role")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif

          <div class="row">
            <div class="col-sm-6">
              {!! Form::open(['url' => route('role.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
              <div class="form-group">
                {!! Form::label('', 'Nama', ['class' => 'col-md-4 form-label']) !!}
                <div class="col-md-12">
                  <input type="text" name="nama" value="{{ old('nama') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('', 'Deskripsi', ['class' => 'col-md-4 form-label']) !!}
                <div class="col-md-12">
                  <input type="text" name="deskripsi" value="{{ old('deskripsi') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
                </div>
                  <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>
              </div>
            </div>
            <div class="col-sm-6">
              {!! Form::label('', 'Otoritas', ['class' => 'col-md-4 form-label']) !!}
              <div>
                <div id="treeview_container" class="hummingbird-treeview well h-scroll-large">
                  <ul id="treeview" class="hummingbird-base checktree">

                    <li>
                      <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> KEUANGAN</label>
                      <ul>
                        <li>
                            <i class="fa fa-plus"></i> <label> <input id="node-0-0" data-id="custom-0" type="checkbox">Administrator</label>
                            <ul>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Pengaturan Peran User</label>
                                <ul>
                                  <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesskeu->where('name', 'role-CanView')->first()->id }}"  value="{{ $accesskeu->where('name', 'role-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'role-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesskeu->where('name', 'role-CanAdd')->first()->id }}" type="checkbox" value="{{ $accesskeu->where('name', 'role-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'role-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesskeu->where('name', 'role-CanEdit')->first()->id }}" type="checkbox" value="{{ $accesskeu->where('name', 'role-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'role-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesskeu->where('name', 'role-CanDelete')->first()->id }}" type="checkbox" value="{{ $accesskeu->where('name', 'role-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'role-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Manajemen User</label>
                                <ul>
                                  <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesskeu->where('name', 'user-CanView')->first()->id }}" type="checkbox" value="{{ $accesskeu->where('name', 'user-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'user-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesskeu->where('name', 'user-CanAdd')->first()->id }}" type="checkbox" value="{{ $accesskeu->where('name', 'user-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'user-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesskeu->where('name', 'user-CanEdit')->first()->id }}" type="checkbox" value="{{ $accesskeu->where('name', 'user-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'user-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesskeu->where('name', 'user-CanDelete')->first()->id }}" type="checkbox" value="{{ $accesskeu->where('name', 'user-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'user-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Ubah Password User</label>
                                <ul>
                                  <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesskeu->where('name', 'ubahpassword-CanView')->first()->id }}" type="checkbox" value="{{ $accesskeu->where('name', 'ubahpassword-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'ubahpassword-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesskeu->where('name', 'ubahpassword-CanAdd')->first()->id }}" type="checkbox" value="{{ $accesskeu->where('name', 'ubahpassword-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'ubahpassword-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesskeu->where('name', 'ubahpassword-CanEdit')->first()->id }}" type="checkbox" value="{{ $accesskeu->where('name', 'ubahpassword-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'ubahpassword-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="accesskeu[]" id="{{ $accesskeu->where('name', 'ubahpassword-CanDelete')->first()->id }}" type="checkbox" value="{{ $accesskeu->where('name', 'ubahpassword-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'ubahpassword-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                          </ul>
                        </li>
                        <li>
                          <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Master</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Item Biaya</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Item Biaya-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Item Biaya-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Item Biaya-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Item Biaya-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Item Biaya-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'Item Biaya-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Item Biaya-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Item Biaya-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                        <li>
                          <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Biaya Mata Kuliah</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Jenis Matakuliah</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Jenis Mata Kuliah-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Set Jenis Mata Kuliah-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Jenis Mata Kuliah-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Set Jenis Mata Kuliah-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Jenis Mata Kuliah-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Set Jenis Mata Kuliah-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Biaya Per SKS</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Biaya Per Sks-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Biaya Per Sks-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Biaya Per Sks-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Biaya Per Sks-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Biaya Per Sks-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'Biaya Per Sks-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Biaya Per Sks-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Biaya Per Sks-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Biaya Per Paket</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Biaya Per Paket-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Biaya Per Paket-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Biaya Per Paket-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Biaya Per Paket-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Biaya Per Paket-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'Biaya Per Paket-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Biaya Per Paket-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Biaya Per Paket-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                        <li>
                          <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Biaya Registrasi</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Biaya Registrasi</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi-CanDelete')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi-CanHitungTagihan')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi-CanHitungTagihan')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi-CanCopyData')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi-CanCopyData')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Biaya Registrasi(Resume)</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi Resume-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi Resume-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi Resume-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi Resume-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi Resume-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi Resume-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi Resume-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi Resume-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Biaya Tambahan</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Tambahan-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Tambahan-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Tambahan-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Tambahan-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Tambahan-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Tambahan-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Tambahan-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Tambahan-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                        <li>
                          <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Pembayaran Mahasiswa</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Biaya Registrasi Personal</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi Personal-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi Personal-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi Personal-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi Personal-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi Personal-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi Personal-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi Personal-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi Personal-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                        <li>
                          <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Retur</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Aturan Pengembalian/Return</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanCetak')->first()->id }}"> {{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanCetak')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Entry Pengembalian/Return</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanAdd')->first()->description }}</label></li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                        <li>
                          <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Teller</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Entry Pembayaran Mahasiswa</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanViewDetail')->first()->id }}"> {{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanViewDetail')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanEditTagihan')->first()->id }}"> {{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanEditTagihan')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBayaTagihan')->first()->id }}"> {{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBayaTagihan')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanPrintTagihan')->first()->id }}"> {{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanPrintTagihan')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBatalTagihan')->first()->id }}"> {{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBatalTagihan')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Riwayat Pembayaran Details-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Riwayat Pembayaran Details-CanView')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan</label>
                              <ul>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan Pembayaran Prodi</label>
                                  <ul>
                                    <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Laporan Prodi-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Laporan Prodi-CanView')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan Pembayaran Mahasiswa</label>
                                  <ul>
                                    <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Laporan Mahasiswa-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Laporan Mahasiswa-CanView')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan Pembayaran Per Bank</label>
                                  <ul>
                                    <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Laporan Bank-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Laporan Bank-CanView')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Riwayat Pembayaran Mahasiswa</label>
                                  <ul>
                                    <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Riwayat Pembayaran Mahasiswa-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Riwayat Pembayaran Mahasiswa-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Riwayat Pembayaran Details Create-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Riwayat Pembayaran Details Create-CanView')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox"> Pembayaran Mahasiswa Per Item</label>
                                  <ul>
                                    <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Pembayaran Mahasiswa Item-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Pembayaran Mahasiswa Item-CanView')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox"> Laporan Tunggakan Mahasiswa</label>
                                  <ul>
                                    <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanViewDetail')->first()->id }}"> {{ $accesskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanViewDetail')->first()->description }}</label></li>
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
          {{-- <div class=" col-md-12 col-xs-12" style="padding:2%;">
            <div class="table-responsive">
            <table class="table table-striped table-font-sm">
                <thead class="thead-default thead-green">
                    <tr>
                        <th width="85%">SIMAKAD</th>
                        <th width="15%"><input type="checkbox" id="allsimak" /></th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($access as $acc)
                    <tr>
                      <td>{{ $acc->description }}</td>
                      <td><center><input class="rolesimak" type="checkbox" name="access[]" value="{{ $acc->id }}"><center></td>
                    </tr>
                  @endforeach
                </tbody>
            </table>
            </div>
            <div class="table-responsive">
            <table class="table table-striped table-font-sm">
                <thead class="thead-default thead-green">
                    <tr>
                        <th width="85%">KEUANGAN</th>
                        <th width="15%"><input type="checkbox" id="allkeu" /></th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($accesskeu as $acck)
                    <tr>
                      <td>{{ $acck->description }}</td>
                      <td><center><input class="rolekeu" type="checkbox" name="accesskeu[]" value="{{ $acck->id }}"><center></td>
                    </tr>
                  @endforeach
                </tbody>
            </table>
            </div>

          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center> --}}

          {!! Form::close() !!}
      </div>
    </div>
  </div>


  <script type="text/javascript">
    $(document).ready(function () {
      $('#allsimak').click(function () {
          $('.rolesimak').prop("checked", this.checked);
      });
    });
    $(document).ready(function () {
      $('#allkeu').click(function () {
          $('.rolekeu').prop("checked", this.checked);
      });
    });
  </script>

  <script>
$("#treeview").hummingbird();
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</section>

@endsection
