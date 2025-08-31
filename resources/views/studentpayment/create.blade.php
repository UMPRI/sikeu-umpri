@extends('shared._layout')
@section('content')
  <!-- Main content -->
  <?php
  $access = auth()->user()->roles()->first()->accesskeuangans()->get();
  $acc = array();
  foreach ($access as $value) {
    $acc[] = $value->name;
  }

  ?>
  <section class="content">
    <div class="container-fluid-title">
      <div class="title-laporan">
        <h3 class="text-white">Entry Pengembalian/Retur</h3>
      </div>
    </div>
    <div class="container">
      <div class="panel panel-default bootstrap-admin-no-table-panel">
        <div class="panel-heading-green">
          <div class="pull-right tombol-gandeng dua">
            <a href="{{ asset('Set_Aturan_Pengembalian')}}" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
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
           <div class="form-group">
             <label for="email" class="col-md-4 control-label"></label>
             <div class="tombol-gandeng dua">
               <a href="{{ asset('Set_Aturan_Pengembalian/Student') }}" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Pilih Mahasiswa</a>
             </div>
           </div>
           <?php
           if ($Student != null) { ?>
             <form class="form-horizontal" method="POST" action="{{ asset('Set_Aturan_Pengembalian/Create_Post') }}">
               {{ csrf_field() }}
                 <div class="form-group">
                     <label for="email" class="col-md-4 control-label">Nim</label>
                     <div class="col-md-12">
                         <input id="" type="text" class="form-control form-control-sm" name="nim" value="{{ $Student->Nim }}" readonly>
                     </div>
                 </div>
                 <div class="form-group">
                     <label for="email" class="col-md-4 control-label">Nama Mahasiswa</label>
                     <div class="col-md-12">
                         <input id="" type="text" class="form-control form-control-sm" name="name" value="{{ $Student->Full_Name }}" readonly>
                     </div>
                 </div>
                 <div class="form-group">
                     <label for="email" class="col-md-4 control-label">Cost_Item_Id</label>
                     <div class="col-md-12">
                       <select name="cost_item_id" class="form-control">
                         <option value="">--- Pilih Cost_Item_Id ---</option>
                         @foreach ($Cost_Item as $Cost_Item)
                           <option value="{{ $Cost_Item->Cost_Item_Id }}">{{ $Cost_Item->Cost_Item_Name }}</option>
                         @endforeach
                       </select>
                     </div>
                 </div>
                 <div class="form-group">
                     <label for="email" class="col-md-4 control-label">Nominal</label>
                     <div class="col-md-12">
                         <input id="" type="text" class="form-control form-control-sm" name="nominal" required oninvalid="this.setCustomValidity('Nominal Tidak Boleh Kosong')" oninput="setCustomValidity('')">
                     </div>
                 </div>

                 <div class="form-group">
                     <div class="col-md-12 " style="text-align:center;">
                        @if(in_array('Set Aturan Pengembalian-CanCetak',$acc))
                         <button type="submit" class="btn btn-success">Simpan</button>
                       @endif
                     </div>
                 </div>
             </form>
           <?php } else { ?>
              <div class="form-group">
                  <label for="email" class="col-md-4 control-label">Nim</label>
                  <div class="col-md-12">
                      <input id="" type="text" class="form-control form-control-sm" name="nim" value="-" readonly>
                  </div>
              </div>
              <div class="form-group">
                  <label for="email" class="col-md-4 control-label">Nama Mahasiswa</label>
                  <div class="col-md-12">
                      <input id="" type="text" class="form-control form-control-sm" name="name" value="-" readonly>
                  </div>
              </div>
              <div class="form-group">
                  <label for="email" class="col-md-4 control-label">Cost_Item_Id</label>
                  <div class="col-md-12">
                    <select name="cost_item_id" class="form-control">
                      <option value="">--- Pilih Cost_Item_Id ---</option>
                      @foreach ($Cost_Item as $Cost_Item)
                        <option value="{{ $Cost_Item->Cost_Item_Id }}">{{ $Cost_Item->Cost_Item_Name }}</option>
                      @endforeach
                    </select>
                  </div>
              </div>
              <div class="form-group">
                  <label for="email" class="col-md-4 control-label">Nominal</label>
                  <div class="col-md-12">
                      <input id="" type="text" class="form-control form-control-sm" name="nominal" value="" >
                  </div>
              </div>
{{--
              <div class="form-group">
                  <div class="col-md-12 " style="text-align:center;">
                      <button type="submit" class="btn btn-success">Simpan</button>
                  </div>
              </div> --}}

          <?php } ?>
        </div>
      </div>
    </div>
  </section>
@endsection
