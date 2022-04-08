<?php
  require_once '../core/init.php';
  if (!isIsLoggedIn()) {
      Session::flash('warning', 'You must login to access that page');
     Redirect::to('inds-login');
    }

    $advisor = new Admin();
    $uniqueid = $advisor->data()->admin_uniqueid;
    $db = Database::getInstance();
    $general = new General();

  if (otpCheck()) {
    Session::flash('emailVerify', 'Please verify your email address!', 'warning');
    Redirect::to('ad-verify');
  }elseif(isOTPset($uniqueid)){
      Redirect::to('ad-otp');
    }


  require APPROOT . '/includes/indhead.php';
  require APPROOT . '/includes/indnav.php';




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
     <?php include 'advisorStudents.php';?>

  <!-- end check here -->


  </div>
</div>

<?php
  require APPROOT . '/includes/indfooter.php';
 ?>
 <script>
     $(document).ready(function(){

        // fetch students under me
          fetch_StudentsUnderMe();
         function fetch_StudentsUnderMe(){
             action = 'fetchStudentsUnderMe';
             $.ajax({
                 url:'script/super-process.php',
                 method:'post',
                 data:{action:action},
                 success:function (response){
                     // console.log(response);
                     $('#studentsUnderme').html(response);
                 }
             });
         }
         // fetch students on queue
           fetch_queue();
          function fetch_queue(){
              action = 'fetchStudentsOnQueue';
              $.ajax({
                  url:'script/super-process.php',
                  method:'post',
                  data:{action:action},
                  success:function (response){
                      // console.log(response);
                      $('#queue').html(response);
                  }
              });
          }
          setInterval(function(){
            fetch_queue();
         }, 1000);

         // fetch the student that is ONLINE

         // fetch students on queue
           fetch_student_online();
          function fetch_student_online(){
              action = 'fetchStudentsOnline';
              $.ajax({
                  url:'script/super-process.php',
                  method:'post',
                  data:{action:action},
                  success:function (response){
                      // console.log(response);
                      $('#showStudentOnline').html(response);
                  }
              });
          }
          setInterval(function(){
            fetch_student_online();
         }, 1000);



         $('#search').click(function(e){
            e.preventDefault();
            $.ajax({
              url:'script/super-process.php',
              method:'POST',
              data:$('#viewLogbookForm').serialize()+'&action=searchLogbook',
              beforeSend:function(){
                $('#search').html('Searching...');
              },
              success:function(data){
                // console.log(data);
                $('#err').html(data);
              },
              complete:function(){
                $('#search').html('Search');
              }
            })

         })

         // fetch students under me
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

          setInterval(function(){
            fetch_chat();
         }, 1000);



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

// remove student from queue chat him or her
$('body').on('click', '.chatStudentBtn', function(e){
  e.preventDefault();
  studentId = $(this).attr('u-id');
  $.ajax({
    url:'script/super-process.php',
    method:'POST',
    data:{studentId:studentId},
    success:function(data){
      console.log(data);
      if (data==='success') {
        fetch_chat();
        fetch_queue();

      }

    }
  })
})



     });
 </script>
<!--  <script type="text/javascript" src="scripts.js"></script>-->
 <script type="text/javascript" src="activity.js"></script>
 <!-- <script type="text/javascript" src="notify.js"></script> -->
