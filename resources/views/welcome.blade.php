@extends('shared._layout')
@section('pageTitle', 'Home')
@section('content')

<div class="container" style="margin-top:50px;margin-bottom:50px;box-shadow: 0px">
    <div class="row">
        <div class="col-md-3">
            <div class="card hovercard">
                <div class="cardheader">
                </div>
                <div class="avatar">
                    <img alt="" src="{{ asset('img/profile.png') }}">
                </div>
                <div class="info">
                    <div class="title">
                        <a href="#"> <?php echo Auth::user()->name;?></a>
                    </div>
                    <div class="desc">
                       
                            <b>     <?php echo Auth::user()->name;?></b>
                      
                    </div>
                    <div class="desc">
                        <b>    <?php echo Auth::user()->name;?></b>
                    </div>
                    <hr />
                    <div class="desc">
                        <?php echo Auth::user()->name;?>
                    
                    </div>
                </div>
                <div class="bottom">
                    <button class="btn btn-sm btn-default" title="fitur ini sedang dikembangkan">Ubah Password</button>
                    <button class="btn btn-sm btn-primary" title="fitur ini sedang dikembangkan">Ganti Foto Profil</button>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card" style="text-align: center; padding-top: 100px; padding-bottom: 100px;">
           
            <center>
              <img alt="" src="{{ asset('img/header.png') }}" width="800pc"><br /><br /><br />
            </center>
                {{-- <h1>SISTEM KEUANGAN</h1> --}}
            </div>
        </div>
    </div>

</div>
@endsection
