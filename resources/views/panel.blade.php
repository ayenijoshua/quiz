@extends('layouts.master')

@section('title')
Products
@endsection

@section('scripts')

@endsection

@section('content')

 <header>
      <div class="collapse bg-dark" id="navbarHeader">
        <div class="container">
          <div class="row">
            <div class="col-sm-8 col-md-7 py-4">
              <h4 class="text-white">About</h4>
              <p class="text-muted">THis is a test Application</p>
            </div>
            <div class="col-sm-4 offset-md-1 py-4">
              <h4 class="text-white">Logout</h4>
              <ul class="list-unstyled">
                <li><a href="/" class="text-white">Log Out</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
     <div class="navbar navbar-dark bg-dark box-shadow bg-inverse" style="background-color: black; ">
        <div class="container d-flex justify-content-between">
         
            <a href="#" class="navbar-brand d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
            <strong><?=$name?></strong>
          </a>
            <button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation"></button>
          <button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon" style="color:white;"></span>
            <span class="navbar-toggler-icon" style="color:white;"></span>
            <span class="navbar-toggler-icon" style="color:white;"></span>
          </button>
        </div>
      </div>
    </header>

   

      <section class="container" style="">
          <div class="jumbotron">
        <div class="container">
        <h1>Welcome to Your Question Page!</h1>
        <p>YOu can also upload your profile picture</p>
        <form id="upload" action="manage-user" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="msg2"></div>
            <input type="hidden" name="action" value="img-upload">
        <input type="file" data-default-file="<?=$img_path?>" name="img" class="upload dropify">
            <button type="submit" class="btn btn-primary upload_btn">Upload</button>
            </form>
        </div>
</div>
      </section>

<section class="container" style="">
          <div class="row">
              <div class="col-md-6 col-md-offset-3">
                  <?php
                  foreach($questions as $quest){
                   ?>   
                  <p><?=$quest->question?></p>
                  <select class="form-control select2">
                      <option>select</option>
                      <option value="<?=$quest->option1?>"><?=$quest->option1?></option>
                        <option <?=$quest->option2?>><?=$quest->option2?></option>
                         <option  <?=$quest->option3?>><?=$quest->option3?></option>
                  </select>
                  <hr>
                 <?php } ?>
                      <button class="btn btn-primary">Button</button>
              </div>
          </div>
      </section>
     

@endsection