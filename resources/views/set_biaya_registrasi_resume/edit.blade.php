@extends('shared._layout')
@section('pageTitle', 'Set_Biaya_Registrasi_Resume')
@section('content')

<!-- Main content -->
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Set Biaya Registrasi (Resume)</h3>
    </div>
  </div>
    <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url()->previous()}}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left"></i> Batal</a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <div class="row">
              <div class="col-md-12">
                  <form class="form-horizontal" method="POST" action="">
                    {{ csrf_field() }}
                      <input type="hidden" name="Cost_Sched_Detail_Id" value="<?php echo $Fnc_Cost_Sched_Detail->Cost_Sched_Detail_Id ?>" required>
                      <input type="hidden" name="Redirect" value="{{ url()->previous()}}" required>
                      <div class="form-group">
                          <label for="Nama" class = "control-label col-md-4">Nama Biaya</Label>
                          <div class="col-md-3">
                              <select class="form-control form-control-sm" name="Cost_Item_Id"  id="Cost_Item_Id" required>
                                <option value="">-- Pilih Biaya --</option>
                                <?php
                                foreach ($Fnc_Cost_Item as $d_Cost_Item) {
                                  ?>
                                  <option value="<?php echo $d_Cost_Item->Cost_Item_Id ?>" <?php if($d_Cost_Item->Cost_Item_Id == $Fnc_Cost_Sched_Detail->Cost_Item_id){ echo "selected";} ?>><?php echo $d_Cost_Item->Cost_Item_Name ?></option>
                                  <?php
                                }
                                 ?>
                              </select>
                          </div>
                      </div>

                      <div class="form-group panggil_date">
                          <label for="Nama" class = "control-label col-md-4">Biaya</Label>
                          <div class="col-md-5 col-sm-5">
                              <div class="input-group date">
                                <input type="number" name="Amount" value="<?php echo $Fnc_Cost_Sched_Detail->Amount ?>" min="0" required class="form-control form-control-sm">
                              </div>
                          </div>
                      </div>

                      <div class="form-group">
                          <div class="col-md-offset-4 col-md-8">
                              <button type="submit" name="submit" class="btn btn-success">
                                  Simpan <span class="glyphicon glyphicon-floppy-save" style="color:white" aria-hidden="true"></span>
                              </button>
                          </div>
                      </div>
                    </div>
                  </div>
            </div>
      </div>
    </div>
  </div>
</section>
@endsection
