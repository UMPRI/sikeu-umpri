@extends('shared._layout')
@section('pageTitle', 'set_jenis_mata_kuliah')
@section('content')

<?php
$access = auth()->user()->roles()->first()->accesskeuangans()->get();
$acc = array();
foreach ($access as $value) {
  $acc[] = $value->name;
}
?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Set Jenis Mata Kuliah</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Set Jenis Matakuliah</b>
        </div>
      </div>
        <br>
        <form class="form-inline" action="" method="GET">
          <div class="form-group mb-3">
            <label for="Term_Year_Id">Th/Smt </label>
            <select name="Term_Year_Id" class="form-control form-control-sm col-sm-12" id="Term_Year_Id" onchange = "this.form.submit()">
              <option value="">--- Pilih Th/Stm ---</option>
              <?php
                foreach ($q_Term_Year as $Term_Year) {
                  ?>
                    <option value="<?php echo $Term_Year->Term_Year_Id ?>" <?php if($Term_Year->Term_Year_Id == $Term_Year_Id){ echo "selected";} ?>><?php echo $Term_Year->Term_Year_Name ?></option>
                  <?php
                }
              ?>
            </select>
          </div>
          <div class="form-group mb-3">
            <label for="Department_Id"> Prodi </label>
            <select name="Department_Id" class="form-control form-control-sm col-sm-12" id="Department_Id" onchange = "this.form.submit()">
              <option value="">--- Pilih Prodi ---</option>
              <?php
                foreach ($q_Department as $Department) {
                  ?>
                    <option value="<?php echo $Department->Department_Id ?>" <?php if($Department->Department_Id == $Department_Id){ echo "selected";} ?>><?php echo $Department->Department_Name ?></option>
                  <?php
                }
              ?>
            </select>
          </div>
          <div class="form-group mb-3">
            <label for="Class_Prog_Id"> Program Kelas </label>
            <select name="Class_Prog_Id" class="form-control form-control-sm col-sm-12" id="Class_Prog_Id" onchange = "this.form.submit()">
              <option value="">--- Pilih Program Kelas ---</option>
              <?php
                foreach ($q_Class_Prog as $Class_Prog) {
                  ?>
                    <option value="<?php echo $Class_Prog->Class_Prog_Id ?>" <?php if($Class_Prog->Class_Prog_Id == $Class_Prog_Id){ echo "selected";} ?>><?php echo $Class_Prog->Class_Program_Name ?></option>
                  <?php
                }
              ?>
            </select>
          </div>
        </form>
      <hr>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <form class="" action="{{ asset('set_jenis_mata_kuliah/create')}}" method="post">
          {{ csrf_field() }}
          <input type="hidden" name="Is_Sks" value="1">
          <input type="hidden" name="Is_Delete" value="0">
        <input type="hidden" name="Class_Prog_Id" value="<?php echo $Class_Prog_Id ?>">
        <input type="hidden" name="Term_Year_Id" value="<?php echo $Term_Year_Id ?>">
        <input type="hidden" name="Department_Id" value="<?php echo $Department_Id ?>">

        <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content in">
            <div class="row">
                <div class="col-md-5">
                    <div class="panel panel-default panel-green">
                        <div class="panel-heading">
                            <div class="bootstrap-admin-box-title right text-green"><b>Mata Kuliah Ditawarkan</b></div>
                        </div>
                        <div class="table-responsive" style="background-color:white;color:black;">
                            <table class="table table-striped table-bordered table-hover table-sm table-font-sm">
                              <?php
                              foreach ($acd_offered_course as $aoc)
                                { ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" value="<?php echo $aoc->Course_Id ?>" name="CourseId[]" id="CourseId" />
                                        </td>
                                        <td>
                                            <?php echo $aoc->Course_Name." ".$aoc->Course_Code ?>
                                        </td>
                                    </tr>
                                <?php }
                               ?>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-horizontal">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-md-1">
                              @if (in_array('Set Jenis Mata Kuliah-CanAdd',$acc)) <!--aku bingung wkwk-->
                                <button type="submit" name="btnSKS" id="btnSKS" title="set tipe SKS" class="btn btn-light"><span class="glyphicon glyphicon-chevron-right"></span></button>
                              @endif

                              @if (in_array('Set Jenis Mata Kuliah-CanDelete',$acc)) <!--aku bingung wkwk-->
                                <button type="submit" name="btnDelSKS" id="btnDelSKS" title="Hapus SKS" class="btn btn-light"><span class="glyphicon glyphicon-chevron-left"></span></button>
                              @endif

                            </div>
                            <div class="col-md-11">
                              <div class="panel panel-default panel-green">
                                <div class="panel-heading">
                                  <div class="bootstrap-admin-box-title right text-green"><b>SKS</b></div>
                                </div>
                                <div class="table-responsive" style="background-color:white;color:black;">
                                  <table class="table table-striped table-bordered table-hover table-sm table-font-sm">
                                    <?php
                                      foreach ($Course_Cost_Type_Sks as $ccts)
                                          { ?>
                                              <tr>
                                                  <td>
                                                      <input type="checkbox" value="<?php echo $ccts->Course_Cost_Type_Id ?>" name="delSKS[]" id="delSKS" />
                                                  </td>
                                                  <td>
                                                      <?php echo $ccts->Course_Name." ".$ccts->Course_Code ?>
                                                  </td>
                                              </tr>
                                        <?php  }
                                     ?>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-md-1">
                              @if (in_array('Set Jenis Mata Kuliah-CanAdd',$acc)) <!--aku bingung wkwk-->
                                <button type="submit" name="btnPaket" id="btnPaket" title="set tipe Paket" class="btn btn-light"><span class="glyphicon glyphicon-chevron-right"></span></button>
                              @endif

                              @if (in_array('Set Jenis Mata Kuliah-CanDelete',$acc)) <!--aku bingung wkwk-->
                                <button type="submit" name="btnDelPaket" id="btnDelPaket" title="Hapus Paket" class="btn btn-light"><span class="glyphicon glyphicon-chevron-left"></span></button>
                              @endif

                            </div>
                            <div class="col-md-11">
                              <div class="panel panel-default panel-green">
                                <div class="panel-heading">
                                  <div class="bootstrap-admin-box-title right text-green"><b>Paket</b></div>
                                </div>
                                <div class="table-responsive" style="background-color:white;color:black;">
                                  <table class="table table-striped table-bordered table-hover table-sm table-font-sm">
                                    <?php
                                      foreach ($Course_Cost_Type_Paket as $cctt)
                                          { ?>
                                              <tr>
                                                  <td>
                                                      <input type="checkbox" value="<?php echo $cctt->Course_Cost_Type_Id ?>" name="delPaket[]" id="delPaket" />
                                                  </td>
                                                  <td>
                                                      <?php echo $cctt->Course_Name." ".$cctt->Course_Code ; ?>
                                                  </td>
                                              </tr>
                                      <?php
                                          }
                                     ?>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
  <script type="text/javascript">
        $(document).ready(function () {
            $("#btnPaket").click(function () {
                $("#Is_Sks").val(0);
            });
            $("#btnDelSKS").click(function () {
                $("#Is_Delete").val(1);
            });
            $("#btnDelPaket").click(function () {
                $("#Is_Delete").val(1);
                $("#Is_Sks").val(0);
            });
        });
    </script>
</section>
@endsection
