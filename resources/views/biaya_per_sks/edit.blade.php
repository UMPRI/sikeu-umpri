@extends('shared._layout')
@section('pageTitle', 'Item_Biaya')
@section('content')

<!-- Main content -->
<section class="content">
  <?php
    foreach ($q_Course_Cost_Sks as $data) {
      # code...

   ?>
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Biaya Per SKS</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ asset('/biaya_per_sks?Term_Year_Id='.$data->Term_Year_Id.'&Class_Prog_Id='.$data->Class_Prog_Id)}}" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <?php
          if (isset($pesan)) {
            echo $pesan;
          }
         ?>
        <form class="form-horizontal" method="POST" action="{{ asset('biaya_per_sks/edit_post/'.$data->Course_Cost_Sks_Id)}}">
          {{ csrf_field() }}
          <input type="hidden" name="Course_Cost_Sks_Id" value="<?php echo $data->Course_Cost_Sks_Id ?>" required>
          <div class="form-group">
              <label for="Term_Year_Id" class="col-md-4 control-label">Th/Smt</label>
              <div class="col-md-12">
                    <input id="" type="text" class="form-control form-control-sm" name="" value="<?php echo $data->Term_Year_Name ?>" readonly>
              </div>
          </div>
          <div class="form-group">
              <label for="email" class="col-md-4 control-label">Program Kelas</label>
              <div class="col-md-12">
                    <input id="" type="text" class="form-control form-control-sm" name="" value="<?php echo $data->Class_Program_Name ?>" readonly>
              </div>
          </div>
          <div class="form-group">
              <label for="email" class="col-md-4 control-label">Angkatan</label>
              <div class="col-md-12">
                      <input id="" type="text" class="form-control form-control-sm" name="" value="<?php echo $data->Entry_Year_Name ?>" readonly>
              </div>
          </div>
          <div class="form-group">
              <label for="email" class="col-md-4 control-label">Prodi</label>
              <div class="col-md-12">
                      <input id="" type="text" class="form-control form-control-sm" name="" value="<?php echo $data->Department_Name.' - '.$data->Acronym; ?>" readonly>
              </div>
          </div>
            <div class="form-group">
                <label for="email" class="col-md-4 control-label">Biaya</label>
                <div class="col-md-12">
                    <input id="" type="number" class="form-control form-control-sm" name="Amount_Per_Sks" value="<?php echo $data->Amount_Per_Sks ?>" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12 " style="text-align:center;">
                    <button type="submit" class="btn btn-success">Edit</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
<?php } ?>
</section>
@endsection
