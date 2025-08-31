@extends('shared._layout')
@section('pageTitle', 'User')
@section('content')

<section class="content">


  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah User</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('administrator/user?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            @if ($error=="Berhasil Menambah User")
              <p class="alert alert-success">{{ $error }}</p>
            @else
              <p class="alert alert-danger">{{ $error }}</p>
            @endif
          @endforeach
        @endif
        {!! Form::open(['url' => route('user.store') , 'method' => 'POST', 'class' => 'form', 'role' => 'form']) !!}
        <div class="form-group">
          {!! Form::label('', 'Nama', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-12">
            <input type="text" name="nama" min="1" value="{{ old('nama') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('', 'Email', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-12">
            <input type="email" name="email" min="1" value="{{ old('email') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('', 'Password', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-12">
            <input type="password" name="password" min="1" value="" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('', 'Confirm Password', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-12">
            <input type="password" name="confirm" min="1" value="" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('', 'Peran User', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-12">
            <select class="form-control form-control-sm" name="role">
              @foreach ( $role as $data )
                <option  <?php if(old('role') == $data->id ){ echo "selected"; } ?> value="{{ $data->id }}">{{ $data->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('', 'Fakultas', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-12">
            <select class="form-control form-control-sm" name="fakultas">
                <option value="">All</option>
              @foreach ( $fakultas as $dataa )
                <option <?php if(old('fakultas') == $dataa->Faculty_Id ){ echo "selected"; } ?> value="{{ $dataa->Faculty_Id }}">{{ $dataa->Faculty_Name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <!-- <div class=" col-md-12 col-xs-12"> -->
          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>
        <!-- </div> -->
        <!-- <center><a onclick="tambah()" class="btn btn-primary">OK</a></center> -->
        {!! Form::close() !!}
      </div>
    </div>
  </div>



<!-- /.row -->

</section>
@endsection
