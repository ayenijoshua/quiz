@extends('layouts.master')

@section('title')
Products
@endsection

@section('scripts')

@endsection

@section('content')
  
      <hr><hr>
      
      <div class="container">
      <div class="row">
          <div class="col-md-6 col-md-offset-3">
      <ul id="myTab" class="nav nav-tabs">
<li class="active">
<a href="#signin" id="all-stud" data-toggle="tab">
Sign in
</a>
</li>
<li class="">
<a href="#signup" id="" data-toggle="tab">
Sign up
</a>
</li>

</ul>
      
<div id="myTabContent" class="tab-content">
<div class="tab-pane fade in active" id="signin">
   <div class="modal-body">
    <div id="" class="table-responsive">  
    <div id="withdraw-body">
      
        <form id="login-form" action="manage-user" method="post">
            {{csrf_field()}}
            <span class="msg"></span>
      <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
      <label for="inputEmail" class="sr-only">Email address</label>
      <input type="email" id="inputEmail" class="form-control" name="email" placeholder="Email address">
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" name="password" class="form-control " placeholder="Password" >
      <div class="checkbox mb-3">
        
      </div>
       <input type="hidden" id="inputPassword" name="action" value="signin">
      <button class="btn btn-lg btn-primary btn-block login_btn" id="login" type="submit">Sign in</button>
       </form>
        <hr>
        <a href="auth" class="btn btn-lg btn-primary btn-block" type="button">Sign in with FB</a>
    </div>
    </div>
   </div>
    
</div>
    <div class="tab-pane fade " id="signup">
    <div class="modal-body">
    <div id="" class="table-responsive">  
    <div id="loan-body">
        <form id="signup-form" action="manage-user" method="post">
         {{csrf_field()}}
            <span class="msg1"></span>
      <h1 class="h3 mb-3 font-weight-normal">Please sign up</h1>
      <label for="inputEmail" class="sr-only">Email address</label>
      <input type="text" id="inputEmail" class="form-control" name="name" placeholder="Email address"  >
      <input type="email" id="inputEmail" class="form-control" name="email" placeholder="Email address"  >
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" >
      <input type="password" id="inputPassword" name="confirm_password" class="form-control" placeholder="Password" >
      <div class="checkbox mb-3">
        
      </div>
      <input type="hidden" id="inputPassword" name="action" value="signup">
      <button class="btn btn-lg btn-primary btn-block signup_btn" id="" type="submit">Sign up</button>
       </form>
    </div>
    </div>
    </div>
    
</div>
    
   



</div>
              </div>
          
          </div>
  </div>
   
 @endsection
  
      
   

    <!-- Bootstrap core JavaScript
    ================================================== -->
 
