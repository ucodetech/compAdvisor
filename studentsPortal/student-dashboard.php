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



  $getAdvisor = $db->query("SELECT * FROM admin WHERE admin_permissions = 'advisor' AND advisor_level = '$userlevel'");
  if ($getAdvisor->count()) {
    $ad = $getAdvisor->first();

  }

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

     <?php include 'chatForm.php';?>
  <!-- end check here -->


  </div>
</div>

<?php
  require APPROOT . '/includes/stfooter.php';
 ?>

 <script>
$(document).ready(function(){
          // fetch my chat with supervisor
          fetch_chat();
           function fetch_chat(){
               action = 'fetchChat';
               $.ajax({
                   url:'script/chat-process.php',
                   method:'post',
                   data:{action:action},
                   success:function (response){
                       // console.log(response);
                       $('#chatBox').html(response);
                   }
               });
           }


           setInterval(function () {
               fetch_chat();
           },1000);



// check header status for advisor
// fetch students under me


 setInterval(function(){
   fetch_chatStatus();
}, 1000);


fetch_chatStatus();
 function fetch_chatStatus(){
     action = 'chatHeaderStatus';
     advisoruniqueid = '<?=$ad->admin_uniqueid?>';
     $.ajax({
         url:'script/chat-process.php',
         method:'post',
         data:{action:action, advisoruniqueid:advisoruniqueid},
         success:function (response){
             console.log(response);
             $('#showStatus').html(response);
         }
     });
 }








  $('#send').click(function(e){
     e.preventDefault();
     var message = $('#message').val();
     if (message.length == '') {
       alert('write message');
     }else{
       $.ajax({
         url:'script/chat-process.php',
         method:'POST',
         data:$('#chatboxForm').serialize()+'&action=sendMessage',
         success:function(data){
           console.log(data);
           if (data==='success') {
             $('#chatboxForm')[0].reset();
             fetch_chat();
           }

         }
       })
     }


  })

})


 </script>
<!--  <script type="text/javascript" src="scripts.js"></script>
 <script type="text/javascript" src="activity.js"></script> -->
 <!-- <script type="text/javascript" src="notify.js"></script> -->
