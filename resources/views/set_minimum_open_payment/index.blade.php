@extends('shared._layout')
@section('pageTitle', 'set-minimum-open-payment')
@section('content')

<section class="content">
    
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('set-minimum.create') }}" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <input type="hidden" name="termYearId" value={{$termYearId}}>
                        <input type="hidden" name="entryYearId" value={{$entryYearId}}>
                        <div class="form-group">
                            <label for="departmentId-Create" class="col-form-label">Program Studi:</label>
                            <select name="departmentId" class="form-control" id="departmentId-Create">
                                <option> -- Pilih Program Studi -- </option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->Department_Id }}">{{ $department->Department_Name }} - {{ $department->Acronym }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount-Create" class="col-form-label">Nominal minimum:</label>
                            <input type="number" name="amount" class="form-control" id="amount-Create">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('set-minimum.update') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="minimumId-Edit">
                        <input type="hidden" name="termYearId" value={{$termYearId}}>
                        <input type="hidden" name="entryYearId" value={{$entryYearId}}>
                        <div class="form-group">
                            <label for="departmentId-Edit" class="col-form-label">Program Studi:</label>
                            <select name="departmentId" class="form-control" id="departmentId-Edit">
                                <option> -- Pilih Program Studi -- </option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->Department_Id }}">{{ $department->Department_Name }} - {{ $department->Acronym }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount-Edit" class="col-form-label">Nominal minimum:</label>
                            <input type="number" name="amount" class="form-control" id="amount-Edit">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('set-minimum.delete') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="termYearId" value={{$termYearId}}>
                    <input type="hidden" name="entryYearId" value={{$entryYearId}}>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="minimumId-Delete">
                        Apakah anda yakin ingin menghapus setting minimum nominal open payment untuk <span id="nameDelete"></span> ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid-title">
        <div class="title-laporan">
            <h3 class="text-white">Setting Minimum Pembayaran Open Payment</h3>
        </div>
    </div>
    <div class="container">
        <div class="panel panel-default bootstrap-admin-no-table-panel">
            <div class="panel-heading-green">
                <div class="pull-right tombol-gandeng dua">
                    @if($termYearId != null && $entryYearId != null)
                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#createModal">Tambah data &nbsp;<i class="fa fa-plus"></i></button>
                    @endif
                </div>
                <div class="bootstrap-admin-box-title right text-white">
                    <b>Setting Minimum Pembayaran Open Payment</b>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-8">
                    <form class="form-inline" action="{{ route('set-minimum.index') }}" method="GET">
                        <div class="form-group mb-3">
                            <label for="termYearId">Tahun Akademik </label>
                            <select name="termYearId" class="form-control form-control-sm col-sm-12" id="termYearId" onchange = "this.form.submit()">
                                <option value="">--- Pilih Th/Stm ---</option>
                                @foreach($termYears as $termYear)
                                    <option value="{{ $termYear->Term_Year_Id }}" <?php if($termYear->Term_Year_Id == $termYearId){ echo "selected";} ?>>{{ $termYear->Term_Year_Name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="entryYearId"> Tahun Angkatan </label>
                            <select name="entryYearId" class="form-control form-control-sm col-sm-12" id="entryYearId" onchange = "this.form.submit()">
                                <option value="">--- Pilih Tahun Angkatan ---</option>
                                @foreach($entryYears as $entryYear)
                                    <option value="{{ $entryYear->Entry_Year_Id }}" <?php if($entryYear->Entry_Year_Id == $entryYearId){ echo "selected";} ?>>{{ $entryYear->Entry_Year_Name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-info" style="font-size:11px;">
                        <b>Tentang menu ini</b><br>
                        <p>Menu ini digunakan untuk melakukan setting berapa minimal dari total pembayaran yang dibayarkan melalui open payment untuk diizinkan melakukan KRS pada portal mahasiswa.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="tableSetMinimumOpenPayment">
                    <thead class="thead-default thead-green">
                        <tr>
                            <th width="w-4">Prodi</th>
                            <th width="w-4">Nominal Minimum</th>
                            <th width="w-4"><i class="glyphicon glyphicon-cog"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($setMinimums as $setMinimum)
                        <tr>
                            <td>{{ $setMinimum->Department_Name }} - {{ $setMinimum->Acronym }}</td>
                            <td>{{ number_format($setMinimum->Amount, '0', ',', '.') }}</td>
                            <td style="text-align:center">
                                <button type="button" class="btn btn-sm btn-primary"  data-toggle="modal" data-target="#editModal" data-minimumid="{{ $setMinimum->Minimum_Installment_Id }}" data-departmentid="{{ $setMinimum->Department_Id }}" data-amount="{{ $setMinimum->Amount }}">Edit</button>
                                <button type="button" class="btn btn-sm btn-danger"  data-toggle="modal" data-target="#deleteModal" data-minimumid="{{ $setMinimum->Minimum_Installment_Id }}" data-departmentname="{{ $setMinimum->Department_Name }} - {{ $setMinimum->Acronym }}">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#editModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('minimumid');
                var departmentId = button.data('departmentid');
                var amount = button.data('amount');

                console.log('data to be edited : id = '+id+', departmentId = '+departmentId+', amount = '+amount);
                
                $('#minimumId-Edit').val(id);
                $('#departmentId-Edit').val(departmentId).change();
                $('#amount-Edit').val(amount);
            });

            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('minimumid');
                var departmentName = button.data('departmentname');

                console.log('data to be edited : id = '+id+', departmentName = '+departmentName);
                
                $('#minimumId-Delete').val(id);
                $('#nameDelete').text(departmentName);
            });
        });
    </script>

</section>

@endsection