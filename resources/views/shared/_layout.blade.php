

<!DOCTYPE html>
<html lang="en">
<head>
  <title>SIKEU - {{ Request::route()->getName() }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html" charset="utf-8"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{ asset('img/logo_univ.png') }}" />
  <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
  <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
  <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/glyphicon.css') }}" rel="stylesheet">
  <link href="{{ asset('sweatalert/sweetalert.css') }}" rel="stylesheet">
  <link href="{{ asset('css/gijgo.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/jquery.ui.css') }}" rel="stylesheet">
  <link href="{{ asset('css/kendo.common.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/kendo.custom.css') }}" rel="stylesheet">
  <link href="{{ asset('js/slimselect.min.css') }}" rel="stylesheet">
  {{-- <link href="{{ asset('css/kendo.nova.mobile.min.css') }}" rel="stylesheet"> --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
  
  
  

  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
  <script src="{{ asset('js/kendo.all.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('sweatalert/sweetalert.min.js') }}"></script>
  <script src="{{ asset('js/gijgo.min.js') }}"></script>
  <script src="{{ asset('js/number_format.js') }}"></script>
  <script src="{{ asset('js/slimselect.min.js') }}"></script>
  {{-- <script src="{{ asset('js/jquery.searchabledropdown-1.0.8.min.js') }}"></script>
  <script src="{{ asset('js/jquery.searchabledropdown-1.0.8.src.js') }}"></script> --}}
  <script src="{{ asset('treeview/hummingbird-treeview.js') }}"></script>
  <link href="{{ asset('treeview/hummingbird-treeview.css') }}" rel="stylesheet">
  <style media="screen">
    .judul1{
      font-size: 24px;
      color: white;
    }
    .judul2{
      color: white;
    }
    .aktif{
      background-color: rgba(0,0,0,0.2);
    }
    .error-database{
      color:red;
      animation-name: error-database;
      animation-duration: 0.5s;
    }

  </style>
</head>
<body>
  <?php
  //
  try {
    DB::connection()->getPdo();
  } catch (\Exception $e) {
    ?>
    <div class="col-sm-12 alert alert-danger error-database">
      <b>Gagal Menyambung ke Database !</b> Tidak dapat terhubung ke database . Hubungi admin .
    </div>
    <?php
  }
   ?>
   <?php
   function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
		}
		return $temp;
	}

	function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim(penyebut($nilai));
		} else {
			$hasil = trim(penyebut($nilai));
		}
		return $hasil;
	}
    ?>
  @include('sweet::alert')
  <nav class="navbar navbar-expand-lg navbar-dark bg-green">
    <div class="kotak-logo">
        <a class="navbar-brand" href="#" style="margin:0px;padding:4px;width:auto;">
          <img src="{{ url('img/navbar.png')}}" alt="gambar kosong" class="logo-brand isi-kotak-logo" >
        </a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav ml-auto">
        @if (Auth::check())
          <li class="nav-item <?php if(Request::route()->getName() == 'home'){ echo 'active' ;};?>">
            <a class="nav-link" href="{{ asset('') }}">Home <span class="sr-only">(current)</span></a>
          </li>
   
          @if (auth()->user()->roles()->first() != null)
            @php
              $access = auth()->user()->akses();
              $acc = $access;

            @endphp
        
        {{-- @if(in_array('role-CanView',$acc) || in_array('user-CanView',$acc)) --}}
        <li class="nav-item dropdown <?php
              if(
                strpos(Request::url(),'administrator/user') != false ||
                // strpos(Request::url(),'administrator/ubahpassword') != false ||
                strpos(Request::url(),'administrator/role') != false
              ){ echo 'active' ;};?>" id="administrator">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Administrator
              </a>
              <div class="dropdown-menu bg-green dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                {{-- {{ url('administrator/user') }} --}}
                {{-- {{ url('administrator/ubahpassword') }}
                {{ url('administrator/role') }} --}}
                <a class="administrator dropdown-item <?php if(strpos(Request::url(),'administrator/role') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('administrator/role') }}" >Pengaturan Peran User</a>
                 <a class="administrator dropdown-item <?php if(strpos(Request::url(),'administrator/user') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('administrator/user') }}">Manajemen User</a>
                 <a class="administrator dropdown-item <?php if(strpos(Request::url(),'administrator/ubahpassword') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('administrator/ubahpassword') }}" >Ubah Password User</a>
              </div>
        </li>
        {{-- @endif --}}

        @if(in_array('Item Biaya-CanView',$acc))
        <li class="nav-item dropdown <?php if(Request::route()->getName() == 'Item Biaya'){ echo 'active' ;};?>">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Master
          </a>
          <div class="dropdown-menu bg-green dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item <?php if(Request::route()->getName() == 'Item Biaya'){ echo 'dropdown-item-active' ;};?>" href="{{ asset('item_biaya')}}">Item Biaya</a>
            {{-- <a class="dropdown-item" href="{{ asset('diskon_biaya')}}">Diskon Biaya</a> --}}
          </div>
        </li>
        @endif


        @if(in_array('Set Jenis Mata Kuliah-CanView',$acc) || in_array('Biaya Per Sks-CanView',$acc) || in_array('Biaya Per Paket-CanView',$acc))
        <li class="nav-item dropdown <?php
            if(
              Request::route()->getName() == 'Set Jenis Mata Kuliah' ||
              Request::route()->getName() == 'Biaya Per Sks' ||
              Request::route()->getName() == 'Biaya Per Paket'
            ){ echo 'active' ;};?>">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Biaya Mata Kuliah
          </a>
          <div class="dropdown-menu bg-green dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            @if(in_array('Set Jenis Mata Kuliah-CanView',$acc))<a class="dropdown-item <?php if(Request::route()->getName() == 'Set Jenis Mata Kuliah'){ echo 'dropdown-item-active' ;};?>" href="{{ asset('set_jenis_mata_kuliah')}}">Set Jenis Mata Kuliah</a>@endif
            @if(in_array('Biaya Per Sks-CanView',$acc))<a class="dropdown-item <?php if(Request::route()->getName() == 'Biaya Per Sks'){ echo 'dropdown-item-active' ;};?>" href="{{ asset('biaya_per_sks')}}">Biaya Per SKS</a>@endif
            @if(in_array('Biaya Per Paket-CanView',$acc))<a class="dropdown-item <?php if(Request::route()->getName() == 'Biaya Per Paket'){ echo 'dropdown-item-active' ;};?>" href="{{ asset('biaya_per_paket')}}">Biaya Per Paket</a>@endif
          </div>
        </li>
        @endif


        @if (in_array('Set Biaya Registrasi-CanView',$acc) || in_array('Set Biaya Tambahan-CanView',$acc) || in_array('Set Biaya Registrasi Resume-CanView',$acc))
        <?php // if (in_array(1,$cost_sched) || in_array(1,$cost_sched_resume) || in_array(1,$cost_sched_plus) || in_array(1,$bank_payment_sched_online)) { ?>
        <li class="nav-item dropdown <?php
            if(
              Request::route()->getName() == 'Set Biaya Registrasi' ||
              Request::route()->getName() == 'Set Biaya Registrasi Resume' ||
              Request::route()->getName() == 'Set Biaya Tambahan' ||
              // Request::route()->getName() == 'Set Jadwal Pembayaran Bank Online'
              starts_with(Request::route()->getName(), 'set-minimum.')
            ){ echo 'active' ;};?>">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Biaya Registrasi
          </a>
          <div class="dropdown-menu bg-green dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            @if (in_array('Set Biaya Registrasi-CanView',$acc))<a class="dropdown-item <?php if(Request::route()->getName() == 'Set Biaya Registrasi'){ echo 'dropdown-item-active' ;};?>" href="{{ asset('set_biaya_registrasi')}}">Set Biaya Registrasi</a>@endif
            @if (in_array('Set Biaya Registrasi Resume-CanView',$acc))<a class="dropdown-item <?php if(Request::route()->getName() == 'Set Biaya Registrasi Resume'){ echo 'dropdown-item-active' ;};?>" href="{{asset('set_biaya_registrasi_resume/resume')}}">Set Biaya Registrasi (Resume)</a>@endif
            @if (in_array('Set Biaya Tambahan-CanView',$acc))<a class="dropdown-item <?php if(Request::route()->getName() == 'Set Biaya Tambahan'){ echo 'dropdown-item-active' ;};?>" href="{{asset('set_biaya_tambahan')}}">Set Biaya Tambahan</a>@endif
            <a class="dropdown-item <?php if(starts_with(Request::route()->getName(), 'set-minimum.')){ echo 'dropdown-item-active' ;};?>" href="{{ route('set-minimum.index') }}">Setting Minimum Pembayaran Open</a>
            <!-- <a class="dropdown-item <?php if(Request::route()->getName() == 'Set Jadwal Pembayaran Bank Online'){ echo 'dropdown-item-active' ;};?>" href="#" onclick="menukosong()">Set Jadwal Pembayaran Bank Online</a> -->
          </div>
        </li>
        @endif


        @if (in_array('Set Biaya Registrasi Personal-CanView',$acc))
        <li class="nav-item dropdown <?php
            if(
              Request::route()->getName() == 'Set Biaya Registrasi Personal' ||
              starts_with(Request::route()->getName(), 'perizinan-krs.')
            ){ echo 'active' ;};?>">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Pembayaran Mahasiswa
          </a>
          <div class="dropdown-menu bg-green dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            @if (in_array('Set Biaya Registrasi Personal-CanView',$acc)) <a class="dropdown-item <?php if(Request::route()->getName() == 'Set Biaya Registrasi Personal'){ echo 'dropdown-item-active' ;};?>" href="{{ asset('set_biaya_registrasi_personal')}}">Set Biaya Registrasi Personal</a>@endif
            <a class="dropdown-item" href="{{ asset('set_biaya_keyin') }}">Set Biaya Key-In Personal</a>
            <a class="dropdown-item <?php if(starts_with(Request::route()->getName(), 'perizinan-krs.')){ echo 'dropdown-item-active' ;};?>" href="{{ route('perizinan-krs.index') }}">Perizinan KRS dan Ujian</a>
            <!--ini sek belum ada route--><a class="dropdown-item" href="#" onclick="menukosong()">Set Blacklist Mahasiswa</a>
            <!--ini sek belum ada route--><a class="dropdown-item" href="#" onclick="menukosong()">Surat Keterangan Bebas Tunggakan SPP</a>
          </div>
        </li>
        @endif


        @if (in_array('Set Aturan Pengembalian-CanView',$acc))
        <li class="nav-item dropdown <?php
            if(
              Request::route()->getName() == 'Set Aturan Pengembalian'
            ){ echo 'active' ;};?>">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Retur
          </a>
          <div class="dropdown-menu bg-green dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            @if (in_array('Set Aturan Pengembalian-CanView',$acc)) <a class="dropdown-item <?php if(Request::route()->getName() == 'Set Aturan Pengembalian'){ echo 'dropdown-item-active' ;};?>" href="{{ asset('Set_Aturan_Pengembalian') }}">Set Aturan Pengembalian/Return</a>@endif
            <a class="dropdown-item" href="{{ asset('Set_Aturan_Pengembalian/Create') }}">Entry Pengembalian/Return</a>
          </div>
        </li>
        @endif


        @if (in_array('Entry Pembayaran Mahasiswa-CanView',$acc) || in_array('Laporan Prodi-CanView',$acc) || in_array('Laporan Mahasiswa-CanView',$acc) ||
             in_array('Laporan Bank-CanView',$acc) || in_array('Riwayat Pembayaran Mahasiswa-CanView',$acc) || in_array('Pembayaran Mahasiswa Item-CanView',$acc) ||
             in_array('Laporan Tunggakan Mahasiswa-CanView',$acc))
        <li class="nav-item dropdown <?php
            if(
              Request::route()->getName() == 'Entry Pembayaran Mahasiswa' ||
              Request::route()->getName() == 'Laporan Prodi' ||
              Request::route()->getName() == 'Laporan Mahasiswa' ||
              Request::route()->getName() == 'Riwayat Pembayaran Mahasiswa' ||
              Request::route()->getName() == 'Laporan Bank' ||
              Request::route()->getName() == 'Pembayaran Mahasiswa Item' ||
              Request::route()->getName() == 'Laporan Tunggakan Mahasiswa' ||
              starts_with(Request::route()->getName(), 'payment-receipt.')
            ){ echo 'active' ;};?>">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Teller
          </a>
          <div class="dropdown-menu bg-green dropdown-menu-right dropdown" aria-labelledby="navbarDropdownMenuLink">
            @if (in_array('Entry Pembayaran Mahasiswa-CanView',$acc))<a class="dropdown-item" href="{{asset('Entry_Pembayaran_Mahasiswa')}}" <?php if(Request::route()->getName() == 'Entry Pembayaran Mahasiswa'){ echo "aktif"; } ?>>Entry Pembayaran Mahasiswa</a>@endif
            <a class="dropdown-item" href="{{route('payment-receipt.index')}}" <?php if(starts_with(Request::route()->getName(), 'payment-receipt.')){ echo "aktif"; } ?>>Slip Pembayaran Mahasiswa</a>
            <a class="dropdown-item dropdown-toggle dropdown-submenu <?php
                if(
                  Request::route()->getName() == 'Laporan Prodi' ||
                  Request::route()->getName() == 'Laporan Mahasiswa' ||
                  Request::route()->getName() == 'Riwayat Pembayaran Mahasiswa' ||
                  Request::route()->getName() == 'Laporan Bank' ||
                  Request::route()->getName() == 'Pembayaran Mahasiswa Item' ||
                  Request::route()->getName() == 'Laporan Tunggakan Mahasiswa'
                ){ echo 'aktif' ;};?>" href="#">Laporan</a>
                <div class="dropdown-menu bg-green dropdown-menu-right">
                  @if (in_array('Laporan Prodi-CanView',$acc))
                    <a class="dropdown-item <?php if(Request::route()->getName() == 'Laporan Prodi'){ echo 'dropdown-item-active aktif' ;};?>" href="{{ asset('laporan/Lp_Prodi')}}">Laporan Pembayaran Prodi</a>
                  @endif

                  @if (in_array('Laporan Mahasiswa-CanView',$acc))
                    <a class="dropdown-item <?php if(Request::route()->getName() == 'Laporan Mahasiswa'){ echo 'dropdown-item-active aktif' ;};?>" href="{{ asset('laporan/Lp_Mahasiswa')}}">Laporan Pembayaran Mahasiswa</a>
                  @endif

                  @if (in_array('Laporan Bank-CanView',$acc))
                    <a class="dropdown-item <?php if(Request::route()->getName() == 'Riwayat Bank'){ echo 'dropdown-item-active aktif' ;};?>" href="{{ asset('laporan/Lp_Bank')}}">Laporan Pembayaran Per Bank</a>
                  @endif

                  @if (in_array('Riwayat Pembayaran Mahasiswa-CanView',$acc))
                    <a class="dropdown-item <?php if(Request::route()->getName() == 'Riwayat Pembayaran Mahasiswa'){ echo 'dropdown-item-active aktif' ;};?>" href="{{ asset('laporan/Rp_Mahasiswa')}}">Riwayat Pembayaran Mahasiswa</a>
                  @endif

                  @if (in_array('Pembayaran Mahasiswa Item-CanView',$acc))
                    <a class="dropdown-item <?php if(Request::route()->getName() == 'Pembayaran Mahasiswa Item'){ echo 'dropdown-item-active aktif' ;};?>" href="{{ asset('laporan/P_Mahasiswa_Item')}}">Pembayaran Mahasiswa Per Item</a>
                  @endif

                  @if (in_array('Laporan Tunggakan Mahasiswa-CanView',$acc))
                    <a class="dropdown-item <?php if(Request::route()->getName() == 'Laporan Tunggakan Mahasiswa'){ echo 'dropdown-item-active aktif' ;};?>" href="{{ asset('laporan/Lp_Tunggakan_Mahasiswa')}}">Laporan Tunggakan Mahasiswa</a>
                  @endif
                </div>
          </div>
        </li>
        @endif

        @endif
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-user"></i>
          </a>
          <div class="dropdown-menu bg-green dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            <a class="administrator dropdown-item <?php if(strpos(Request::url(),'administrator/ubahpasswordsaya') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('administrator/ubahpasswordsaya') }}">Ubah Password</a>
            {{-- <a class="dropdown-item" href="{{ asset('changePassword') }}">Ubah Password</a> --}}
            <a class="dropdown-item" href="{{ route('logout') }}" id="btn-logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Log Out</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
          </div>
        </li>

      @else
        <li class="nav-item dropdown">
          <a class="nav-link" href="{{ asset('login') }}">
            Log In
          </a>
        </li>
      @endif
      </ul>
    </div>
  </nav>

<div class="container-fluid">
        @yield('content')
</div>
<footer class="footer footer-green">
  Copyright © 2019 - All Rights Reserved <br>
  Universitas Muhammadiyah Pringsewu Lampung
</footer>

<script type="text/javascript">
$('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
if (!$(this).next().hasClass('show')) {
  $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
}
var $subMenu = $(this).next(".dropdown-menu");
$subMenu.toggleClass('show');


$(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
  $('.dropdown-submenu .show').removeClass("show");
});


return false;
});
</script>

<script>
    $(document).ready(function(){
        $('.select2').select2();
    });
</script>

<script type="text/javascript">
  function menukosong() {
    swal('Maaf.....!','Halaman Masih Dalam Pengerjaan','warning');
  }
</script>


</body>
</html>
