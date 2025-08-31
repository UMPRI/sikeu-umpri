@extends('shared._layout')
@section('pageTitle', 'Item_Biaya')
@section('content')

<!-- Main content -->
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Biaya Per SKS</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ asset('/biaya_per_sks?Term_Year_Id='.$Term_Year_Id.'&Class_Prog_Id='.$Class_Prog_Id)}}" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
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
                <label for="Term_Year_Id" class="col-md-4 control-label">Th/Smt</label>
                <div class="col-md-12">
                    <?php
                      foreach ($q_Term_Year as $data_Term_Year) {
                        ?>
                        <input id="" type="text" class="form-control form-control-sm" name="" value="<?php echo $data_Term_Year->Term_Year_Name ?>" readonly>
                        <?php
                      }
                     ?>
                    <input id="Term_Year_Id" type="hidden" class="form-control form-control-sm" name="Term_Year_Id" value="<?php echo $Term_Year_Id ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-4 control-label">Program Kelas</label>
                <div class="col-md-12">
                  <?php
                    foreach ($q_Class_Prog as $data_Class_Prog) {
                      ?>
                      <input id="" type="text" class="form-control form-control-sm" name="" value="<?php echo $data_Class_Prog->Class_Program_Name ?>" readonly>
                      <?php
                    }
                   ?>
                  <input id="Class_Prog_Id" type="hidden" class="form-control form-control-sm" name="Class_Prog_Id" value="<?php echo $Class_Prog_Id ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-4 control-label">Angkatan</label>
                <div class="col-md-12">
                  <?php
                    if ($Entry_Year_Id != null) {
                      foreach ($q_Entry_Year as $data_Entry_Year) {
                        ?>
                        <input id="" type="text" class="form-control form-control-sm" name="" value="<?php echo $data_Entry_Year->Entry_Year_Name ?>" readonly>
                        <input type="hidden" name="Entry_Year_Id" value="<?php echo $data_Entry_Year->Entry_Year_Id ?>" required>
                        <?php
                      }
                    }else{
                      ?>
                      <select class="form-control form-control-sm" name="Entry_Year_Id" required>
                        <option value="">-- Pilih Angkatan --</option>
                        <?php
                          foreach ($q_Entry_Year as $data_Entry_Year) {
                            ?>
                            <option value="<?php echo $data_Entry_Year->Entry_Year_Id ?>"><?php echo $data_Entry_Year->Entry_Year_Name ?></option>
                            <?php
                          }
                         ?>
                      </select>
                      <?php
                    }
                   ?>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-4 control-label">Prodi</label>
                <div class="col-md-12">
                  <?php
                    if ($Department_Id != null) {
                      foreach ($q_Department as $data_Department) {
                        ?>
                        <input id="" type="text" class="form-control form-control-sm" name="" value="<?php echo $data_Department->Department_Name ?>" readonly>
                        <input type="hidden" name="Department_Id" value="<?php echo $data_Department->Department_Id ?>" required>
                        <?php
                      }
                    }else{
                      ?>
                      <select class="form-control form-control-sm" name="Department_Id" required>
                        <option value="">-- Pilih Prodi --</option>
                        <?php
                          foreach ($q_Department as $data_Department) {
                            ?>
                            <option value="<?php echo $data_Department->Department_Id ?>"><?php echo $data_Department->Department_Name." - ".$data_Department->Acronym; ?></option>
                            <?php
                          }
                         ?>
                      </select>
                      <?php
                    }
                   ?>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-4 control-label">Biaya</label>
                <div class="col-md-12">
                    <input id="" type="number" class="form-control form-control-sm" name="Amount_Per_Sks" value="" required>
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
