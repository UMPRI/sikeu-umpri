<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Login</title>
    <!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
    <link rel="shortcut icon" href="{{ asset('img/logo_univ.png') }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet">  -->
    <!-- <link rel="stylesheet" href="stylecus.css"> -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,700,700i" rel="stylesheet">
    <!-- <link href="{{ asset('awsom/css/all.css') }}" rel="stylesheet"> -->


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<style>
/*
div.atas span {
    width: 279px;
    height: 23px;
    font-family: 'Roboto', sans-serif;
    font-size: 19px;
    font-weight: bold;
    font-style: normal;
    font-stretch: normal;
    line-height: 1.21;
    letter-spacing: normal;
    text-align: left;
    color: #0F002E;
} */

form .logo {
    width: 100px;
    height: auto;
    display: block;
    margin-bottom: 25px;

}


html, body {
    max-width: 100%;
    overflow-x: hidden;
    overflow-y: hidden;
    height: 100%;
    background-color: rgba(254, 254, 254, 0.3);;
}

.bg-login {
    background-image: url("img/sikeu_steve johnson_unsplash.png");
    background-size: 100%  100%;
    background-repeat: no-repeat;
    right: 0;
}



html, body
{
    margin: 0px;
}


div.atas
{
    font-family: 'Raleway', sans-serif;
    margin: 15px auto;
    margin-right: 10px;
    text-align: center;
    color: #0F002E;
}
div.atas .web
{
    text-decoration: none;
    font: 16px 'Raleway', sans-serif;
    /* margin: 0px 10px; */
    padding: 10px 10px;
    position: relative;
    z-index: 0;
    cursor: pointer;
    /* width: 118px;
    height: 16px; */
    font-weight: 300;
    font-style: normal;
    font-stretch: normal;
    line-height: 1.14;
    letter-spacing: normal;
    text-align: center;
    color: #0F002E;
    top: 8px;
}

div.atas i {
    /* width: 21.1px; */
    /* height: 18.1px; */
    cursor: pointer;
}


/* div.borderXwidth a:before, div.borderXwidth a:after
{
    position: absolute;
    opacity: 0;
    width: 0%;
    height: 2px;
    content: '';
    background: #FFF;
    transition: all 0.3s;
}
div.borderXwidth a:before
{
    left: 0px;
    top: 0px;
}
div.borderXwidth a:after
{
    right: 0px;
    bottom: 0px;
}
div.borderXwidth a:hover:before, div.borderXwidth a:hover:after
{
    opacity: 1;
    width: 100%;
} */

form {
    width: 80%;
    margin: 4em auto;
    padding: 3em 2em 2em 2em;


}

.group {
    position: relative;
    margin-bottom: 35px;
}

input {
    font-size: 14px;
    padding: 10px 10px 10px 10px;
    -webkit-appearance: none;
    display: block;
    background:transparent;
    color: rgb(0, 0, 0);
    width: 100%;
    /* border-radius: 2px; */
    border: none;
    border-bottom: 1px solid #0F002E ;
    /* box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.16); */
}

input:focus { outline: none; }


/* Label */

label {
    color: #0F002E;
    font-size: 14px;
    font-weight: normal;
    position: absolute;
    pointer-events: none;
    left: 5px;
    top: 10px;
    transition: all 0.2s ease;
}


/* active */

input:focus ~ label, input.used ~ label {
    top: -20px;
  transform: scale(.75); left: -2px;
    /* font-size: 14px; */
    color: #0F002E;
}


/* Underline */

.bar {
    position: relative;
    display: block;
    width: 100%;
}

.bar:before, .bar:after {
    content: '';
    height: 2px;
    width: 0;
    bottom: 1px;
    position: absolute;
    background: #0F002E;
    transition: all 0.2s ease;
}

.bar:before { left: 50%; }

.bar:after { right: 50%; }


/* active */

input:focus ~ .bar:before, input:focus ~ .bar:after { width: 50%; }


/* Highlight */

.highlight {
    position: absolute;
    height: 60%;
    width: 100px;
    top: 25%;
    left: 0;
    pointer-events: none;
    opacity: 0.5;
}

input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus, 
input:-webkit-autofill:active  {
    -webkit-box-shadow: 0 0 0 30px white inset !important;
}


/* active */

input:focus ~ .highlight {
    animation: inputHighlighter 0.3s ease;
}


/* Animations */

@keyframes inputHighlighter {
    from { background: #0F002E; }
    to 	{ width: 0; background: transparent; }
}


/* Button */

.button {
  position: relative;
  display: inline-block;
  padding: 12px 24px;
  margin: .3em 0 1em 0;
  width: 100%;
  vertical-align: middle;
  color: #fff;
  font-size: 16px;
  line-height: 20px;
  -webkit-font-smoothing: antialiased;
  text-align: center;
  letter-spacing: 1px;
  background: transparent;
  border: 0;
  border-bottom: 2px solid #0F002E;
  cursor: pointer;
  transition: all 0.15s ease;
}
.button:focus { outline: 0; }


/* Button modifiers */

.buttonBlue {
  background: #c5d464;
  text-shadow: 1px 1px 0 #7d8640(39, 110, 204, .5);
}

.buttonBlue:hover { background: #7d8640; }


/* Ripples container */

.ripples {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
  background: transparent;
}


/* Ripples circle */

.ripplesCircle {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  opacity: 0;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: #FFF);
}

.ripples.is-active .ripplesCircle {
  animation: ripples .4s ease-in;
}


/* Ripples animation */

@keyframes ripples {
  0% { opacity: 0; }

  25% { opacity: 1; }

  100% {
    width: 200%;
    padding-bottom: 200%;
    opacity: 0;
  }
}

#submenu{
    display: block;
    visibility: hidden;
    position: relative;

}



@media screen and (max-width: 960px) {
  #submenu{
        display: inline;
        visibility: visible;
        right: -9rem;

  }

  #submenu .fa-th {
        font-size: 30px;
  }

  .kanen{
      display: none;
  }
  .col-6{
      max-width: none;
      flex: none;

  }
  #brand{
    visibility: hidden;
    display: block;
}

}




form img{
    width: 100%;
    height: auto;

}

.dropdown-toggle{
   color: transparent;
}
.dropdown-toggle:hover {
    color: transparent;
 }

</style>
</head>




<body>
    <div class="row" style="height: 100%">
        <div class=" col-6 " style="text-align:left">
        <?php
            try {
            DB::connection()->getPdo();
            } catch (\Exception $e) {
            ?>
            <div class="col-sm-12 alert alert-danger"><center>
                <b>Sambungan Gagal !</b><br>
                Tidak dapat tersambung ke database, Silakan Hubungi Admin :<br>
                <!-- <?php echo $e->getMessage(); ?> -->
            </div>
            <?php
            }
            ?>
            <div id="brand" class="col-6" style="margin-top:16px; margin-left:44px">            
                    <span style="color: rgb(48,12,118); font-family:'Roboto', sans-serif; font-size:19px; font-weight:bold; font-style: normal; font-stretch:normal; line-height: 1.21; letter-spacing:normal; text-align: left;">                    
                        UNIVERSITAS MUHAMMADIYAH <br>PRINGSEWU LAMPUNG</span>
                    </div>
                    <center>
                        <div id='submenu' class=" col-3 nav-item dropdown dropleft  " style="color:grey; display: block; top: 5px;">
                                <a style="color:#0F002E;" nav-link dropdown-toggle float-right href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-th sub-nav"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="http://simakad-itbad.umy.ac.id"><i class="fas fa-book"></i><span style="margin-left: 8px;">SIMAK</span></a>
                                <a class="dropdown-item" href="http://sikeu-itbad.umy.ac.id"><i class="fas fa-donate"></i></i><span style="margin-left: 8px;">SI KEU</span></a>
                                <a class="dropdown-item" href="http://simsdm-itbad.umy.ac.id"><i class="fas fa-user-tie"></i></i><span style="margin-left: 8px;">SIM SDM</span></a>
                                <a class="dropdown-item" href="http://dosen-itbad.umy.ac.id"><i class="fas fa-chalkboard-teacher"></i><span style="margin-left: 8px;">Portal Dosen</span></a>
                                <a class="dropdown-item" href="http://mahasiswa-itbad.umy.ac.id"><i class="fas fa-user-graduate"></i><span style="margin-left: 8px;">SI Mahasiswa</span></a>
                                <a class="dropdown-item" href="http://anggaran-itbad.umy.ac.id"><i class="fas fa-shopping-basket"></i><span style="margin-left: 8px;">SI Anggaran</span></a>
                                <a class="dropdown-item" href="http://aipt-itbad.umy.ac.id"><i class="fas fa-trophy"></i><span style="margin-left: 8px;">AIPT</span></a>
                                <a class="dropdown-item" href="http://penmaru-itbad.umy.ac.id"><i class="fas fa-graduation-cap"></i><span style="margin-left: 8px;">Penmaru</span></a>
                                <a class="dropdown-item" href="http://simsdm-itbad.umy.ac.id"><i class="fas fa-star"></i><span style="margin-left: 8px;">Super Admin</span></a>
                                <a class="dropdown-item web" href="http://itbad.ac.id/" target="_blank" style="color:#0F002E">Website</a></a>
                                </div>
                        </div>
                    </center>


            <!-- Default form login -->

            <form class="text-center border-light p-5 col-8" method="POST" action="{{ route('login') }}" style="margin-top: 0px;">

                <center> <img class="logo" style="margin-top: -40px;" src="{{ asset('img/logo_univ.png') }}"></center>

                <p class="h4 " style="font-weight: 300; font-style: normal; font-size: 24 px;font-family: 'Roboto', sans-serif; color:#37392e">
                    <span >Selamat Datang di</span>
                    <span>Sistem Informasi Keuangan</span>
                </p>
                <br>
                <br>

                <!-- Email -->

                    {{ csrf_field() }}

                    <center>
                      <div class="group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input id="email" type="email" name="email" required autofocus><span class="highlight"></span><span class="bar"></span>
                        <label>Alamat E-mail</label>
                              @if ($errors->has('email'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('email') }}</strong>
                                  </span>
                              @endif
                      </div>
                      <div class="group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input id="password" type="password" name="password" required><span class="highlight"></span><span class="bar"></span>
                        <label>Password</label>
                        @if ($errors->has('password'))
                          <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                          </span>
                        @endif
                      </div>
                      
                      <button class="btn btn-sm" type="submit" style="float:left;background-image: linear-gradient(52deg, rgba(48,12,118,1) 0%, rgba(49,194,247,1) 100%);color:white; width: 133px; height: 37px; border-radius: 3px;box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.16); font-size: 18px;border-radius:20px !important; ">Masuk</button>
                    </center>
                <!-- Sign in button -->
            </form>


            <div class=""style="bottom: 1px; left:10px ; margin-left: 80px; margin-top: -30px;"  >
                <span style="
                font-family: 'Raleway', sans-serif;
                font-size: 14px;
                font-weight: 600;
                font-style: normal;
                font-stretch: normal;
                line-height: 1.11;
                letter-spacing: normal;
                text-align: left;
                color: #0F002E;">
                Belum punya hak akses?</span>
                <br>
                <span style="
                font-family: 'Raleway', sans-serif;
                font-size: 12px;
                font-weight: 600;
                font-style: normal;
                font-stretch: normal;
                line-height: 1.11;
                letter-spacing: normal;
                text-align: left;
                color: #707070;">
                Silakan hubungi admin kampus untuk mendapatkan hak akses </span>

            </div>
        </div>

        <div class="col-6 bg-login kanen">
            <div class="atas borderXwidth"style="height:16px;display:block; float:right ">
                <a class="web" href="http://umpri.ac.id/" target="_blank">UMPRI LAMPUNG</a>
                <div class="nav-item dropdown dropleft " style="height:16px; color:grey; display: block; float:right;">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-th sub-nav" style="color:#0F002E"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                          <a class="dropdown-item" href="http://simakad-itbad.umy.ac.id"><i class="fas fa-book"></i><span style="margin-left: 8px;">SIMAK</span></a>
                          <a class="dropdown-item" href="http://sikeu-itbad.umy.ac.id"><i class="fas fa-donate"></i></i><span style="margin-left: 8px;">SI KEU</span></a>
                          <a class="dropdown-item" href="http://simsdm-itbad.umy.ac.id"><i class="fas fa-user-tie"></i></i><span style="margin-left: 8px;">SIM SDM</span></a>
                          <a class="dropdown-item" href="http://dosen-itbad.umy.ac.id"><i class="fas fa-chalkboard-teacher"></i><span style="margin-left: 8px;">Portal Dosen</span></a>
                          <a class="dropdown-item" href="http://mahasiswa-itbad.umy.ac.id"><i class="fas fa-user-graduate"></i><span style="margin-left: 8px;">SI Mahasiswa</span></a>
                          <a class="dropdown-item" href="http://anggaran-itbad.umy.ac.id"><i class="fas fa-shopping-basket"></i><span style="margin-left: 8px;">SI Anggaran</span></a>
                          <a class="dropdown-item" href="http://aipt-itbad.umy.ac.id"><i class="fas fa-trophy"></i><span style="margin-left: 8px;">AIPT</span></a>
                          <a class="dropdown-item" href="http://penmaru-itbad.umy.ac.id"><i class="fas fa-graduation-cap"></i><span style="margin-left: 8px;">Penmaru</span></a>
                          <a class="dropdown-item" href="http://simsdm-itbad.umy.ac.id"><i class="fas fa-star"></i><span style="margin-left: 8px;">Super Admin</span></a>
                        </div>
                 </div>
            </div>
        </div>

</body>








<script type="text/javascript">
$(window, document, undefined).ready(function() {

  $('input').blur(function() {
    var $this = $(this);
    if ($this.val())
      $this.addClass('used');
    else
      $this.removeClass('used');
  });


    if ($('#email').val() != null){
      $('#email').addClass('used');
    }else{
      $('#email').removeClass('used');
    }

    if ($('#password').val() != null){
      $('#password').addClass('used');
    }else{
      $('#password').removeClass('used');
    }

  var $ripples = $('.ripples');

  $ripples.on('click.Ripples', function(e) {

    var $this = $(this);
    var $offset = $this.parent().offset();
    var $circle = $this.find('.ripplesCircle');
//
    var x = e.pageX - $offset.left;
    var y = e.pageY - $offset.top;

    $circle.css({
      top: y + 'px',
      left: x + 'px'
    });

    $this.addClass('is-active');

  });

  $ripples.on('animationend webkitAnimationEnd mozAnimationEnd oanimationend MSAnimationEnd', function(e) {
  	$(this).removeClass('is-active');
  });

});
</script>

</html>
