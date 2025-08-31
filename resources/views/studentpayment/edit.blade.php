@extends('shared._layout')
@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid-title">
      <div class="title-laporan">
        <h3 class="text-white">Set Aturan Pengembalian</h3>
      </div>
    </div>
    <div class="container">
      <div class="panel panel-default bootstrap-admin-no-table-panel">
        <div class="panel-heading-green">
          <div class="pull-right tombol-gandeng dua">
            <a href="{{ asset('/Set_Aturan_Pengembalian?from_date='.$_GET['from_date'].'&until_date='.$_GET['until_date']) }}" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
          </div>
          <div class="bootstrap-admin-box-title right text-white">
            <b>Edit</b>
          </div>
        </div>
        <br>

        <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <form class="form-horizontal" method="POST" action="{{ asset('Set_Aturan_Pengembalian/edit_post') }}">
            {{ csrf_field() }}
              <div class="form-group">
                  <label for="email" class="col-md-4 control-label">Nim</label>
                  <div class="col-md-12">
                      <input id="" type="text" class="form-control form-control-sm" value="{{ $nim }}" name="nim" required readonly>
                  </div>
              </div>
              <div class="form-group">
                  <label for="email" class="col-md-4 control-label">Cost Item</label>
                  <div class="col-md-12">
                    <select class="form-control" name="cost_item_id">
                      @foreach ($cost_item as $cost)
                        <option value="{{ $cost->Cost_Item_Id }}" <?php if ($cost_item_id == $cost->Cost_Item_Id) { echo "selected";}?> >{{ $cost->Cost_Item_Name }}</option>
                      @endforeach
                    </select>
                      {{-- <input id="" type="text" class="form-control form-control-sm" name="item_name" required> --}}
                  </div>
              </div>
              <div class="form-group">
                  <label for="email" class="col-md-4 control-label">Total Amount</label>
                  <div class="col-md-12">
                      <input id="" type="text" class="form-control form-control-sm" value="{{ number_format($total_amount, 0,'.','.') }}" name="total_amount" required>
                  </div>
                  <input type="hidden" name="Student_Payment_Id" value="{{ $student_payment_id }}">
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
  </section>
@endsection
