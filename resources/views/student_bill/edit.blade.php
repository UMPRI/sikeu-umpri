@extends('shared._layout')
@section('pageTitle', 'Student_Bill')
@section('content')

<!-- Main content -->
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Daftar Tagihan</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ asset('/Entry_Pembayaran_Mahasiswa?param='.$param)}}" class="btn btn-sm btn-danger"><i class="fa fa-arrow-left"></i> Batal</a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit Tagihan</b>
        </div>
      </div>
      <br>
        <p class="badge badge-warning" style="color:black">Nama : {{$acd_student->Full_Name}}</p></br>
        <p class="badge badge-warning" style="color:black">Prodi : {{$acd_student->Department_Name}}</p>
      </div>
      <div style="padding-left:2em; padding-top:1em;padding-right:2em">
          <div class="pull-right">
          </div>
          <?php
            if ($ListTagihan != null) {
           ?>
           <div class="table-responsive">
             <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="listTagihan">
               <thead class="thead-default thead-green">
                 <tr>
                   <th>

                   </th>
                   <th>
                     Tahun
                   </th>
                   <th>
                     Biaya
                   </th>
                   <th>
                     Tahap Ke
                   </th>
                   <th>
                     Tgl Mulai
                   </th>
                   <th>
                     Tgl Akhir
                   </th>
                   <th>
                     Tgl Jumlah
                   </th>
                 </tr>
               </thead>
               <tbody>
                 <?php
                 $no = 1;
                 $sumAmount =0;
                 foreach ($ListTagihan as $tagihan) {
                   ?>
                   <tr>
                     <td style="display:none"><?php echo $tagihan['Student_Bill_Id'] ?></td>
                     <td align="center">
                       <?php
                       if ($tagihan['Is_Must_Paid'] == 1){
                         ?>
                         <input type="checkbox" name="ToAdd" id="ToAdd" checked="checked" disabled="disabled" />
                         <?php
                       }else{
                         if ($tagihan['Is_Forced_To_Pay'] == 1){ ?>
                           <input type="checkbox" name="ToAdd" id="ToAdd" checked="checked" class="forcedToPay" />
                           <?php
                         }else{ ?>
                           <input type="checkbox" name="ToAdd" id="ToAdd" class="forcedToPay" />
                           <?php
                         }
                       }
                       ?>
                     </td>
                     <td><?php echo substr($tagihan['Term_Year_Id'],0,4) ?></td>
                     <td><?php echo $tagihan['Cost_Item_Name'] ?></td>
                     <td>
                       <?php if ($tagihan['Payment_Order'] == 0) {
                         echo "Lunas";
                       }else{
                         echo $tagihan['Payment_Order'];
                       } ?>
                     </td>
                     <td><?php echo  date("d M Y",strtotime($tagihan["Start_Date"])) ?></td>
                     <td><?php echo  date("d M Y",strtotime($tagihan["End_Date"])) ?></td>
                     <td><?php echo number_format($tagihan['Amount'],'0',',','.') ?></td>
                   </tr>
                   <?php
                   $sumAmount += $tagihan['Amount'];
                   $no++;
                 }
                 ?>
               </tbody>
               <tfoot>
                 <tr>
                   <td colspan="6" align="right">
                     <b>Jumlah</b>
                   </td>
                   <td align="right">

                     <?php echo number_format($sumAmount,'0',',','.') ?>
                   </td>
                 </tr>
               </tfoot>
             </table>
           </div>
           <br>
              <a href="{{ asset('/Entry_Pembayaran_Mahasiswa?param='.$param)}}" class="btn btn-success">Selesai <i class="glyphicon glyphicon-ok"></i></a>
              <br><br>

            <?php } ?>
      </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function(){
      $("#listTagihan .forcedToPay").change(function () {
          var StudentBillId = $(this).closest("tr").find("td").eq(0).html();
          var IsForcedToPay = false;
          if ($(this).is(':checked')) {
              IsForcedToPay = true;
          }
          $.ajax({
              // headers: {
              // 		"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
              // },
              url: "/Entry_Pembayaran_Mahasiswa/Edit_Post",
              type: "GET",
              dataType: "json",
              data: {
                  Student_bill_id: StudentBillId,
                  Is_forced_to_pay: IsForcedToPay
                  // _token: '{{csrf_token()}}'
              },
              contentType: "application/json; charset=utf-8",
              success: function (response) {
                  //alert(response + " Success");
              }
          });
      });
    });
  </script>
</section>
@endsection
