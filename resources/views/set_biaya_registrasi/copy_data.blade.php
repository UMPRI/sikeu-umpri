@extends('shared._layout')
@section('pageTitle', 'Set_Biaya_Registrasi_Copy_data')
@section('content')

<!-- Main content -->
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Setting Biaya Registrasi</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{asset('set_biaya_registrasi?Term_Year_Id='.$Term_Year_Id.'&Entry_Year_Id='.$Entry_Year_Id.'&Entry_Period_Type_Id='.$Entry_Period_Type_Id)}}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Copy Data</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <form class="" action="{{asset('set_biaya_registrasi/copy_data')}}" method="post">
          {{ csrf_field() }}
          <div class="form-horizontal">
            <div class="form-group">
              <label class = "control-label col-md-4">Th Akademik</label>
              <div class="col-md-8">
                <input type="text" value="{{$Term_Year->Term_Year_Name}}" disabled="disabled" class="form-control" />
                <input type="hidden" name="Term_Year_Id" value="{{$Term_Year_Id}}" required>
              </div>
            </div>
            <div class="form-group">
              <label class = "control-label col-md-4">Th Angkatan</label>
              <div class="col-md-8">
                <input type="text" value="{{$Entry_Year->Entry_Year_Id}}" disabled="disabled" class="form-control" />
                <input type="hidden" name="Entry_Year_Id" value="{{$Entry_Year_Id}}" required>
              </div>
            </div>
            <div class="form-group">
              <label class = "control-label col-md-4">Aturan</label>
              <div class="col-md-8">
                <input type="text" value="{{$Entry_Period_Type->Entry_Period_Type_Name}}" disabled="disabled" class="form-control" />
                <input type="hidden" name="Entry_Period_Type_Id_Dest" value="{{$Entry_Period_Type_Id}}" required>
              </div>
            </div>
          </div><hr />
          <i>Copy dari :</i>
          <div class="form-horizontal">
            <div class="form-group">
              <label class = "control-label col-md-4">Aturan</label>
              <div class="col-md-8">
                <select class="form-control" name="Entry_Period_Type_Id_Source" required>
                  <option value="">-- Pilih Aturan --</option>
                    <?php foreach ($Entry_Period_Type_Id_Source as $key) {
                      ?>
                      <option value="<?php echo $key->Entry_Period_Type_Id ?>"><?php echo $key->Entry_Period_Type_Name ?></option>
                      <?php
                    }
                    ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-offset-4 col-md-8">
                <button type="submit" name="submit" class="btn btn-success">
                  Copy Data <span class="glyphicon glyphicon-import" style="color:white" aria-hidden="true"></span>
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
