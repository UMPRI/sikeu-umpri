@extends('shared._layout')
@section('pageTitle', 'student-allowed-krs')
@section('content')

<section class="content">

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Izinkan Mahasiswa KRS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('perizinan-krs.update') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id-Edit">
                        <input type="hidden" name="termYearId" value={{$termYearId}}>
                        <input type="hidden" name="entryYearId" value={{$entryYearId}}>
                        <input type="hidden" name="departmentId" value={{$departmentId}}>
                        <input type="hidden" name="q" value={{$q}}>
                        <input type="hidden" name="page" value={{$page}}>
                        Apakah anda yakin mengizinkan <span id="nama-Edit"></span> dengan nim <span id="nim-Edit"></span> untuk bisa melakukan KRS?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Izinkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Data Perizinan KRS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('perizinan-ujian.delete') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="termYearId" value={{$termYearId}}>
                    <input type="hidden" name="entryYearId" value={{$entryYearId}}>
                    <input type="hidden" name="departmentId" value={{$departmentId}}>
                    <input type="hidden" name="q" value={{$q}}>
                    <input type="hidden" name="page" value={{$page}}>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id-Delete">
                        Apakah anda yakin membatalkan perizinan <span id="nama-Delete"></span> dengan nim <span id="nim-Delete"></span> untuk bisa melakukan KRS?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-danger">Batalkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editExamModal" tabindex="-1" role="dialog" aria-labelledby="editExamModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editExamModalLabel">Izinkan Mahasiswa Ujian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('perizinan-ujian.update') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id-EditExam">
                        <input type="hidden" name="termYearId" value={{$termYearId}}>
                        <input type="hidden" name="entryYearId" value={{$entryYearId}}>
                        <input type="hidden" name="departmentId" value={{$departmentId}}>
                        <input type="hidden" name="q" value={{$q}}>
                        <input type="hidden" name="page" value={{$page}}>
                        Apakah anda yakin mengizinkan <span id="nama-EditExam"></span> dengan nim <span id="nim-EditExam"></span> untuk bisa mengikuti ujian ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Izinkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteExamModal" tabindex="-1" role="dialog" aria-labelledby="deleteExamModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteExamModalLabel">Delete Data Perizinan Ujian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('perizinan-ujian.delete') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="termYearId" value={{$termYearId}}>
                    <input type="hidden" name="entryYearId" value={{$entryYearId}}>
                    <input type="hidden" name="departmentId" value={{$departmentId}}>
                    <input type="hidden" name="q" value={{$q}}>
                    <input type="hidden" name="page" value={{$page}}>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id-DeleteExam">
                        Apakah anda yakin membatalkan perizinan <span id="nama-DeleteExam"></span> dengan nim <span id="nim-DeleteExam"></span> untuk bisa mengikuti ujian ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-danger">Batalkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid-title">
        <div class="title-laporan">
            <h3 class="text-white">Perizinan KRS dan Ujian</h3>
        </div>
    </div>
    <div class="container">
        <div class="panel panel-default bootstrap-admin-no-table-panel">
            <div class="panel-heading-green">
                <div class="pull-right tombol-gandeng dua">
                    
                </div>
                <div class="bootstrap-admin-box-title right text-white">
                    <b>List Mahasiswa Diizinkan KRS dan Ujian</b>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <form class="form-inline" action="{{ route('perizinan-krs.index') }}" method="GET">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label for="termYearId">Tahun Akademik </label>
                                <select name="termYearId" class="form-control form-control-sm col-sm-12" id="termYearId" onchange = "this.form.submit()">
                                    <option value="">--- Pilih Th/Stm ---</option>
                                    @foreach($termYears as $termYear)
                                        <option value="{{ $termYear->Term_Year_Id }}" <?php if($termYear->Term_Year_Id == $termYearId){ echo "selected";} ?>>{{ $termYear->Term_Year_Name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label for="entryYearId"> Tahun Angkatan </label>
                                <select name="entryYearId" class="form-control form-control-sm col-sm-12" id="entryYearId" onchange = "this.form.submit()">
                                    <option value="">--- Pilih Tahun Angkatan ---</option>
                                    @foreach($entryYears as $entryYear)
                                        <option value="{{ $entryYear->Entry_Year_Id }}" <?php if($entryYear->Entry_Year_Id == $entryYearId){ echo "selected";} ?>>{{ $entryYear->Entry_Year_Name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="departmentId"> Program Studi </label>
                                <select name="departmentId" class="form-control form-control-sm col-sm-12" id="departmentId" onchange = "this.form.submit()">
                                    <option value="">--- Pilih Program Studi ---</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->Department_Id }}" <?php if($department->Department_Id == $departmentId){ echo "selected";} ?>>{{ $department->Department_Name }} - {{ $department->Acronym }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group col-md-2">
                                <label for="q"> Cari </label>
                                <input name="q" class="form-control form-control-sm col-sm-12" id="q" placeholder="Cari berdasarkan NIM / Nama" value="{{ trim($q) }}">
                            </div>

                            <div class="form-group col-md-1" style="display:block !important;">
                                &nbsp;<br>
                                <button type="submit" class="btn btn-success">Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm table-font-sm" id="tableManualAllowed">
                <thead class="thead-default thead-green">
                    <tr>
                        <th width="w-4">NIM</th>
                        <th width="w-4">Nama</th>
                        <th width="w-4">Status KRS</th>
                        <th width="w-4">By</th>
                        <th width="w-4">Status Ujian</th>
                        <th width="w-4"><i class="glyphicon glyphicon-cog"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr
                        @if($student->Student_Allowed_Krs_Id != null)
                            class="alert-success"
                        @else
                            class="alert-danger"
                        @endif
                    >
                        <td>{{ $student->Nim }}</td>
                        <td>{{ $student->Full_Name }}</td>
                        <td>
                            @if($student->Student_Allowed_Krs_Id != null)
                                <span style='color:green;'>Diizinkan</span>
                            @else
                                <span style='color:red;'>Belum Diizinkan</span>
                            @endif
                        </td>
                        <td>
                            @if($student->Student_Allowed_Krs_Id != null && $student->Is_Auto == true)
                                System
                            @elseif($student->Student_Allowed_Krs_Id != null && $student->Is_Auto != true)
                                Manual
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($student->Student_Allowed_Exam_Id != null)
                                <span style='color:green;'>Diizinkan</span>
                            @else
                                <span></span>
                            @endif
                        </td>
                        <td style="text-align:center">
                            @if($student->Student_Allowed_Krs_Id != null && $student->Is_Auto == true)
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id="{{ $student->Student_Allowed_Krs_Id }}" data-nama="{{ $student->Full_Name }}" data-nim="{{ $student->Nim }}">Batalkan KRS</button>
                            @elseif($student->Student_Allowed_Krs_Id != null && $student->Is_Auto != true)
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id="{{ $student->Student_Allowed_Krs_Id }}" data-nama="{{ $student->Full_Name }}" data-nim="{{ $student->Nim }}">Batalkan KRS</button>
                            @else
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal" data-id="{{ $student->Register_Number }}" data-nama="{{ $student->Full_Name }}" data-nim="{{ $student->Nim }}">Izinkan KRS</button>
                            @endif
                            &nbsp;
                            @if($student->Student_Allowed_Exam_Id != null)
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteExamModal" data-id="{{ $student->Student_Allowed_Exam_Id }}" data-nama="{{ $student->Full_Name }}" data-nim="{{ $student->Nim }}">Batalkan Ujian</button>
                            @else
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editExamModal" data-id="{{ $student->Register_Number }}" data-nama="{{ $student->Full_Name }}" data-nim="{{ $student->Nim }}">Izinkan Ujian</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $students->appends(request()->query())->links() }}
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#editModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var nim = button.data('nim');
                var nama = button.data('nama');

                console.log("data to be inserted, id = "+id);
                
                $('#id-Edit').val(id);
                $('#nama-Edit').text(nama);
                $('#nim-Edit').text(nim);
            });

            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var nim = button.data('nim');
                var nama = button.data('nama');
                
                $('#id-Delete').val(id);
                $('#nama-Delete').text(nama);
                $('#nim-Delete').text(nim);
            });

            $('#editExamModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var nim = button.data('nim');
                var nama = button.data('nama');

                console.log("data to be inserted, id = "+id);
                
                $('#id-EditExam').val(id);
                $('#nama-EditExam').text(nama);
                $('#nim-EditExam').text(nim);
            });

            $('#deleteExamModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var nim = button.data('nim');
                var nama = button.data('nama');
                
                $('#id-DeleteExam').val(id);
                $('#nama-DeleteExam').text(nama);
                $('#nim-DeleteExam').text(nim);
            });
        });
    </script>

</section>

@endsection