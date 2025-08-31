@extends('shared._layout')
@section('pageTitle', 'payment-receipt')
@section('content')

<section class="content">

    <div class="container-fluid-title">
        <div class="title-laporan">
            <h3 class="text-white">Bukti Slip Pembayaran</h3>
        </div>
    </div>
    <div class="container">
        <div class="panel panel-default bootstrap-admin-no-table-panel">
            <div class="panel-heading-green">
                <div class="pull-right tombol-gandeng dua">
                    
                </div>
                <div class="bootstrap-admin-box-title right text-white">
                    <b>List Bukti Slip Pembayaran</b>
                </div>
            </div>
            <br>
            <form class="form-inline row" action="{{ route('payment-receipt.index') }}" method="GET">
                <div class="form-group col-md-3">
                    <label for="q"> Status </label>
                    <select name="status" class="form-control form-control-sm col-sm-12" onchange="this.form.submit()">
                        <option <?php echo $status == false ? 'selected' : '' ?> value=false >Belum Dibayarkan</option>
                        <option <?php echo $status == true ? 'selected' : '' ?> value=true >Sudah Dibayarkan</option>
                    </select>
                </div>
                
                <div class="form-group col-md-3">
                    <label for="q"> Cari </label>
                    <input name="q" class="form-control form-control-sm col-sm-12" id="q" placeholder="Cari berdasarkan NIM / Nama" value="{{ trim($q) }}">
                </div>

                <div class="form-group col-md-1" style="display:block !important;">
                    &nbsp;<br>
                    <button type="submit" class="btn btn-success">Cari</button>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-strip table-bordered table-hover table-sm table-font-sm" id="tableManualAllowed">
                <thead class="thead-default thead-green">
                    <tr>
                        <th width="w-4">NIM</th>
                        <th width="w-4">Nama</th>
                        <th width="w-4">Program Studi</th>
                        <th width="w-4">Berkas</th>
                        <th width="w-4">Tgl Upload</th>
                        <th width="w-4"><i class="glyphicon glyphicon-cog"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paymentReceipts as $paymentReceipt)
                    <tr>
                        <td>{{ $paymentReceipt->Nim }}</td>
                        <td>{{ $paymentReceipt->Full_Name }}</td>
                        <td>{{ $paymentReceipt->Full_Name }}</td>
                        <td><a href="{{ $receiptHost }}/storage{{ $paymentReceipt->File_Name }}" target="_blank">{{ $paymentReceipt->File_Name }}</a></td>
                        <td>{{ Date("d M Y, H:i:s", strtotime("+7 hours", strtotime($paymentReceipt->Created_Date))) }} WIB</td>
                        <td style="text-align:center">
                            <a href="{{ asset('Entry_Pembayaran_Mahasiswa') }}?param={{ $paymentReceipt->Nim }}&receipt={{ $paymentReceipt->Payment_Receipt_Id }}" class="btn btn-primary">Bayarkan</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $paymentReceipts->appends(request()->query())->links() }}
        </div>
    </div>

</section>

@endsection