<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<?php 
    // Fungsi header dengan mengirimkan raw data excel
    header("Content-type: application/vnd-ms-excel");
    
    // // Mendefinisikan nama file ekspor "biaya-sks.xls"
    header("Content-Disposition: attachment; filename=biaya-sks.xls");
?>
<body>
    <center>
        <h1>Data Biaya per SKS </h1>
    </center>
    <div class="table-responsive">
        <?php
        if (isset($pesan)) {
            echo $pesan;
          }
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
        }else{
         ?>
        <table class="table table-striped table-bordered table-hover table-sm table-font-sm" border="1" id="dataTables-example">
          <thead class="thead-default thead-green">
            <tr>
              <th width="w-4">Prodi</th>
              <?php
                $jumlah_entry_year = 0;
                $array_entry_year = array();
                foreach ($q_Entry_Year_FCCS as $Entry_Year) {
                  ?>
                  <th width="w-4"><?php echo $Entry_Year->Entry_Year_Id ?></th>
                  <?php
                  $jumlah_entry_year++;
                  $array_entry_year[] = $Entry_Year->Entry_Year_Id;
                }
               ?>
            </tr>
          </thead>
          <tbody>
            <?php
            $i=1;
            foreach ($q_Department_FCCS as $Department) {
              ?>
              <tr>
                <td><?php echo $Department->Department_Name ?></td>
                <!-- di sini query dalam department -->
                <?php
                for ($i=0; $i < $jumlah_entry_year; $i++) {
                  // $angka_array = $i - 1;
                  $entry_year_id_kolom_ini = $array_entry_year[$i];
                  ?>
                  <td>
                    <?php
                    $q_biaya_per_sks = DB::table('fnc_course_cost_sks')
                    ->where('Term_Year_Id','=',$Term_Year_Id)
                    ->where('Class_Prog_Id','=',$Class_Prog_Id)
                    ->where('Department_Id','=',$Department->Department_Id)
                    ->where('Entry_Year_Id','=',$entry_year_id_kolom_ini)
                    ->OrderBy('Entry_Year_Id','desc')
                    ->get();
                    $c_biaya_per_sks = DB::table('fnc_course_cost_sks')
                    ->where('Term_Year_Id','=',$Term_Year_Id)
                    ->where('Class_Prog_Id','=',$Class_Prog_Id)
                    ->where('Department_Id','=',$Department->Department_Id)
                    ->where('Entry_Year_Id','=',$entry_year_id_kolom_ini)
                    ->OrderBy('Entry_Year_Id','desc')
                    ->count();
                    if ($c_biaya_per_sks > 0) {
                      foreach ($q_biaya_per_sks as $data_sks) {
                        ?>
                        <span style="white-space:nowrap">
                          <?php echo number_format($data_sks->Amount_Per_Sks,0,'.','.') ?>
                        </span>
                        <?php
                      }
                    }else{
                      ?>
                      -
                      <?php
                    }
                    ?>
                  </td>
                  <?php
                }
                 ?>
              </tr>
              <?php
              $i++;
            }
             ?>
          </tbody>
        </table>
      <?php } ?>
      </div>
</body>
</html>