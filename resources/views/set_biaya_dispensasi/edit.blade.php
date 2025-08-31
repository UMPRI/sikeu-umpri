@extends('shared._layout')
@section('pageTitle', 'Item_Biaya')
@section('content')

<!-- Main content -->
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Set Dispensasi Key-In Personal</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">

        <div class="pull-right tombol-gandeng dua">
          <a href="{{ asset('/set_biaya_keyin?param='.$param.'&Term_Year_Id='.$Term_Year_Id)}}" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
        </div>
        <div class="pull-right">
            <?php
            if (isset($q_Student))
            {
                ?>
                <div class="margin-badge">
                  <p class="badge badge-warning">Nama : <?php echo $q_Student->Full_Name ?></p><br />
                  <p class="badge badge-warning">Prodi : <?php echo $q_Student->Department_Name ?></p>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit</b>
        </div>
      </div>
      <br>
      <br>
      <br>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <?php
          if (isset($pesan)) {
            echo $pesan;
          }
         ?>
        <form class="form-horizontal" method="POST" action="{{ asset('set_biaya_keyin/edit_post/'.$q_fnc_student_cost_krs_personal->Student_Cost_Krs_Personal_Id) }}">
          {{ csrf_field() }}
            <div class="form-group">
                <label for="email" class="col-md-4 control-label">Thn/Smtr</label>
                <div class="col-md-12">
                    <input id="" type="text" class="form-control form-control-sm"  value="{{ $q_Term_Year->Term_Year_Name }}" required oninvalid="this.setCustomValidity('Thn/Smtr tidak boleh kosong')" oninput="setCustomValidity('')" readonly>
                    <input type="hidden" name="nim" value="{{ $param }}">
                    <input type="hidden" name="Student_Id" value="{{ $q_Student->Student_Id }}">
                    <input type="hidden" name="Term_Year_Id" value="{{ $q_Term_Year->Term_Year_Id }}">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-4 control-label">Keterangan Dispensasi</label>
                <div class="col-md-12">
                  <textarea name="Description" class="form-control form-control-sm" rows="3" cols="10" required oninvalid="this.setCustomValidity('Keterangan tidak boleh kosong')" oninput="setCustomValidity('')">{{ $q_fnc_student_cost_krs_personal->Description }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-4 control-label">Diskon</label>
                <div class="col-md-3">
                  <div class="input-group">
                    <input id="" type="Number" min="0" max="100" class="form-control form-control-sm" name="Percent" value="{{ $q_fnc_student_cost_krs_personal->Percent }}" required oninvalid="this.setCustomValidity('Diskon tidak boleh kosong')" oninput="setCustomValidity('')">
                    <span class="input-group-addon">%</span>
                  </div>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-4 control-label">Jenis Matakuliah</label>
                <div class="col-md-12">
                    <select class="form-control form-control-sm" name="Course_Type_id">
                      <option>--Pilih Jenis Mata Kuliah--</option>
                      @foreach ($q_Acd_Course_Type as $ACT)
                        <option value="{{ $ACT->Course_Type_Id }}"
                          <?php if ($ACT->Course_Type_Id == $q_fnc_student_cost_krs_personal->Course_Type_id) { ?>
                          <?php echo "selected"; } ?>>{{ $ACT->Course_Type_Name }}</option>
                      @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12 " style="text-align:center;">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
