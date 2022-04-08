<?php
  require_once '../core/init.php';
  if (!isLoggedInStudent()) {
      Session::flash('warning', 'You must login to access that page');
     Redirect::to('student-login');
    }

    $user = new User();
    $uniqueid = $user->data()->stud_unique_id;

  if (verifyCheck()) {
    Session::flash('emailVerify', 'Please verify your email address!', 'warning');
    Redirect::to('student-verify');
  }elseif(isOTPsetUser($uniqueid)){
      Redirect::to('student-otp');
    }


  require APPROOT . '/includes/sthead.php';
  require APPROOT . '/includes/stnav.php';


  $user = new User();
  $general = new General();
  $show = new Show();
  $db = Database::getInstance();
  $userlevel = $user->data()->stud_level;
  $usersession = $user->data()->stu_id;
  $userunique = $user->data()->stud_unique_id;

 ?>
<style media="screen">
.activeImg{
  width: 70px;
  height: 70px;
  border-radius: 50%;
}
.card-title{
  color:#fff !important;
}
.form-control{
  color: #fff;
}
option{
  color: #fff;
  background: #000;
}
</style>
<div class="content">
  <div class="container-fluid">
    <!-- first role monitor users -->
    <!-- check student have filled placement form -->

    <div class="row">
      <div class="col-md-2"></div>
      <div class="col-md-8">
        <div class="card">
          <div class="card-header card-header-tabs card-header-warning">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <span class="nav-tabs-title">Form:</span>
                <ul class="nav nav-tabs" data-tabs="tabs">
                  <li class="nav-item">
                    <a class="nav-link active" href="#profile" data-toggle="tab">
                      <i class="material-icons fa fa-user-plus fa-lg"></i> MAKE COMPLAIN
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane active">
                <hr>
                <form class="form" action="#" method="post" enctype="multipart/form-data" id="complainForm">

                      <input type="hidden" name="stud_unique_id" id="stud_unique_id" value="<?=$usersession?>" class="form-control">

                      <input type="hidden" name="level" id="level" value="<?=$userlevel?>" class="form-control">

                  <div class="form-group">
                    <label for="title">Complain Title: <sup class="text-danger
                      ">*</sup></label>
                      <input type="text" name="title" id="title" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="complain">Complain: <sup class="text-danger
                      ">*</sup></label>
                      <textarea name="complain" id="complain" rows="5"  class="form-control"></textarea>
                  </div>
                  <div class="form-group">
                    <button type="button" name="save" id="saveBtn" class="btn btn-info btn-block">Complain</button>
                    <div class="clear-fix"></div>
                    <div id="showError"></div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-2"></div>
    </div>

  <!-- end check here -->


  </div>
</div>

<?php
  require APPROOT . '/includes/stfooter.php';
 ?>

 <script>
$(document).ready(function(){

  $('#saveBtn').click(function(e){
     e.preventDefault();

       $.ajax({
         url:'script/feedback-process.php',
         method:'POST',
         data:$('#complainForm').serialize()+'&action=complainNow',
         success:function(data){
           console.log(data);
           if (data==='success') {
             $('#complainForm')[0].reset();
              $('#showError').html('<span class="text-success text-bold">Your Complain have been sent!</span>');
           }else{
             $('#showError').html(data);
           }
         }
       })

  })

})


 </script>
<!--  <script type="text/javascript" src="scripts.js"></script>
 <script type="text/javascript" src="activity.js"></script> -->
 <!-- <script type="text/javascript" src="notify.js"></script> -->
