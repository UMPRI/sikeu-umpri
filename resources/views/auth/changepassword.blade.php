@extends('shared._layout')
@section('pageTitle', 'Item_Biaya')
@section('content')


<!-- Main content -->
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Ubah Password</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Index</b>
        </div>
      </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <br>
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
          @if (session('success'))
              <div class="alert alert-success">
                  {{ session('success') }}
              </div>
          @endif
        <form class="form-horizontal" method="POST" action="{{ asset('changePassword') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                <label for="new-password" class="col-md-4 control-label">Password Lama</label>

                <div class="col-md-6">
                    <input id="current-password" type="password" class="form-control" name="current-password" required>

                    @if ($errors->has('current-password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('current-password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
                <label for="new-password" class="col-md-4 control-label">Password Baru</label>

                <div class="col-md-6">
                    <input id="new-password" type="password" class="form-control" name="new-password" required>

                    @if ($errors->has('new-password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('new-password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="new-password-confirm" class="col-md-4 control-label">Konfirmasi Password Baru</label>

                <div class="col-md-6">
                    <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-success">
                        Ubah Password
                    </button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
