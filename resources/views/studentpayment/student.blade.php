@extends('shared._layout')
@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid-title">
      <div class="title-laporan">
        <h3 class="text-white">Data Mahasiswa</h3>
      </div>
    </div>
    <div class="container">
      <div class="panel panel-default bootstrap-admin-no-table-panel">
        <div class="panel-heading-green">
          <div class="pull-right tombol-gandeng dua">
            <a href="{{ asset('Set_Aturan_Pengembalian/Create') }}" class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Batal</a>
          </div>
          <div class="bootstrap-admin-box-title right text-white">
            <b>Index</b>
          </div>
        </div>
          <br>
          <form class="form-inline" action="" method="GET">
            <div class="form-group mb-3">
              <label for="Entry_Year_Id">Tahun Angkatan </label>
              <select name="Entry_Year_Id" class="form-control form-control-sm col-sm-12" id="Term_Year_Id" onchange = "this.form.submit()">
                <option value="">--- Pilih Angkatan ---</option>

                @foreach ($Entry_Year as $Entry_Year)
                  <option value="{{ $Entry_Year->Entry_Year_Id }}" <?php if($Entry_Year_Id != null && $Entry_Year_Id == $Entry_Year->Entry_Year_Id){ echo "selected";} ?> >{{ $Entry_Year->Entry_Year_Id }}</option>
                @endforeach

              </select>
            </div>
            <div class="form-group mb-3">
              <label for="Department_Id"> Prodi </label>
              <select name="Department_Id" class="form-control form-control-sm col-sm-12" id="Entry_Year_Id" onchange = "this.form.submit()">
                <option value="">--- Pilih Prodi ---</option>
                @foreach ($Department as $Department)
                  <option value="{{ $Department->Department_Id }}" <?php if($Department_Id != null && $Department_Id == $Department->Department_Id){ echo "selected";} ?> >{{ $Department->Department_Name }}</option>
                @endforeach
              </select>
            </div>

          </form>
          <form class="form" action="" method="get">
            <div class="form-group row text-green">
              <div class="col-md-2">
                <label for="Row_Page"> Baris / Halaman </label>
              </div>
              <div class="col-md-3">
                <input type="text" name="Row_Page" value="{{ $Row_Page }}" placeholder="Baris/Halaman" class="form-control form-control-sm col-sm-12">
              </div>
              <div class="col-md-1">
                <label for="Row_Page"> Cari : </label>
              </div>
              <div class="col-md-3">
                <input type="text" name="search" value="" placeholder="Cari Berdasarkan Nim" class="form-control form-control-sm col-sm-12">
              </div>
              {{-- hideen woy --}}
              <input type="hidden" name="Entry_Year_Id" value="{{ $Entry_Year_Id }}">
              <input type="hidden" name="Department_Id" value="{{ $Department_Id }}">
              <div class="col-md-2">
                <input type="submit" value="Cari" class="btn btn-success">
              </div>

            </div>
          </form>
      </div>

        <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <div class="table-responsive">
            <?php
            if (isset($message)) {
              if ($message != null) {
                ?>
                <div class="alert alert-danger">
                  <h1>
                    Tidak dapat terhubung ke database !
                  </h1>
                </div>
                <?php
              }
            }else{ ?>

            <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="dataTables-example">
              <thead class="thead-default thead-green">
                <tr>
                  <th width="w-4">Nim</th>
                  <th width="w-4">Nama Mahasiswa</th>
                  <th width="w-4">Tempat Lahir</th>
                  <th width="w-4">Tgl Lahir</th>
                  <th width="w-4">No. Hp</th>
                  <th width="w-4">Jenis Kelamin</th>
                  <th width="w-4"><i class="glyphicon glyphicon-cog"></i></th>
                </tr>
              </thead>
              <tbody>
                <?php
                // $i=1;
                // $page = 1;
                // if (isset($_GET['page'])) {
                //   $page = $_GET['page'];
                // }
                // $no = ($page- 1) * $rowpage + 1;
                ?>
                @foreach ($Student as $student)
                  <tr>
                    <td>{{ $student->Nim }}</td>
                    <td>{{ $student->Full_Name }}</td>
                    <td>{{ $student->Birth_Place }}</td>
                    <td style="text-align:center">{{ date("d F Y",strtotime($student->Birth_Date)) }}</td>
                    <td>{{ $student->Phone_Mobile }}</td>
                    <td>{{ $student->Gender_Type }}</td>
                    <td align="center" width="20%">
                      <table style="border:0px; background-color:none;">
                        <tr style="border:0px; background-color:none;">
                          <td style="border:0px; background-color:none;">
                            <a href="{{ asset('Set_Aturan_Pengembalian/Create?Student_Id='.$student->Student_Id) }}" class="btn btn-warning btn-sm">Pilih</a>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>

          <?php } ?>

          </div>
          {{ $Student->render("pagination::bootstrap-4") }}

        </div>
      </div>
    </div>

  </section>

@endsection
