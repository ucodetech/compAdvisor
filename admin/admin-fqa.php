<?php
  require_once '../core/init.php';
  if (!isIsLoggedIn()){
      Session::flash('warning', 'You need to login to access that page!');
      Redirect::to('admin-login');
  }
  if (!hasPermissionSuper()){
      Session::flash('denied', 'You do not have permission to access that page!');
      Redirect::to('admin-dashboard');
  }
  $admin = new Admin();
  $useremail = $admin->data()->admin_email;
  $uniqueid = $admin->data()->admin_uniqueid;
  if (otpCheck()) {
    Session::flash('emailVerify', 'Please verify your email!', 'warning');
    Redirect::to('admin-verify');
  }elseif(isOTPset($uniqueid)){
    Redirect::to('admin-otp');
  }
  require APPROOT . '/includes/adminhead.php';
  require APPROOT . '/includes/adminnav.php';

  $general = new General();





 ?>
<style media="screen">
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
    <div class="row">
      <!-- table -->
      <div class="col-lg-8 col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">Frequently Asked Question</h4>
            <p class="card-category">List Of question</p>
          </div>
          <div class="card-body table-responsive"  id="faq">

          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-warning">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <span class="nav-tabs-title">Form:</span>
                <ul class="nav nav-tabs" data-tabs="tabs">
                  <li class="nav-item">
                    <a class="nav-link active" href="#profile" data-toggle="tab">
                      <i class="material-icons fa fa-user-plus fa-lg"></i> Add FAQ
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
                <form class="form" action="#" method="post" id="addFAQForm">
                  <div class="form-group">
                    <label for="question">Question: <sup class="text-danger
                      ">*</sup></label>
                      <input type="text" name="question" id="question" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="level">Level: <sup class="text-danger
                      ">*</sup></label>
                      <select class="form-control" name="level" id="level">
                        <option value="">Select Level</option>
                        <option value="ND">ND</option>
                        <option value="HND">HND</option>
                      </select>
                  </div>
                  <div class="form-group">
                    <label for="answer">Answer: <sup class="text-danger
                      ">*</sup></label>
                      <textarea name="answer" id="answer" class="form-control" rows="10"></textarea>
                  </div>

                  <div class="form-group">
                    <button type="button" name="save" id="saveBtn" class="btn btn-info btn-block">Save</button>
                    <div class="clear-fix"></div>
                    <div id="showError"></div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<!-- Modal view detail faq -->
<div class="modal fade" id="FaqDetail" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">FAQ Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
      <div class="modal-body text-primary table-responsive" id="grabFaq">

      </div>
      <div class="modal-footer">
           <hr class="invisible">
        <div id="showResponse"></div>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- end detail -->

<!-- edit modal faq -->
<div class="modal fade" id="FaqEdit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">FAQ Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
      <div class="modal-body bg-dark text-primary table-responsive" id="grabFaqEdit">

      </div>
      <div class="modal-footer">
           <hr class="invisible">
        <div id="showResponse"></div>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- end edit -->

<?php
  require APPROOT . '/includes/adminfooter.php';
 ?>
<script type="text/javascript">
  $(document).ready(function(){

     //assign students
  $('body').on('click', '.FaqDetailIcon', function(e){
    e.preventDefault();
    faq_id = $(this).attr('d-id');
    $.ajax({
      url: 'scripts/super-process.php',
      method: 'post',
      data: {faq_id:faq_id},
      success:function(data){
        $('#grabFaq').html(data);
      }
    })
  });
  $('body').on('click', '.FaqEditIcon', function(e){
    e.preventDefault();
    faq_ide = $(this).attr('e-id');
    $.ajax({
      url: 'scripts/super-process.php',
      method: 'post',
      data: {faq_ide:faq_ide},
      success:function(data){
        $('#grabFaqEdit').html(data);
      }
    })
  });

 $('body').on('click', '.editFAQBtn', function(e){
    e.preventDefault();
    $.ajax({
      url: 'scripts/super-process.php',
      method: 'post',
      data: $('#editFAQForm').serialize()+'&action=editfaq',
      success:function(data){
        if (data==='success') {
          fetchFAQ();
         $('#showMsg').html(data);
        }else{
          $('#showMsg').html(data);
        }

     }
    })
  })


    $('#saveBtn').click(function(e){
      e.preventDefault();
      $.ajax({
        url:'scripts/super-process.php',
        method:'post',
        data:$('#addFAQForm').serialize()+'&action=addFaq',
        beforeSend:function(){
          $('#saveBtn').html('Saving...');
        },
        success:function(response){
          if (response==='success') {
              $('#addFAQForm')[0].reset();
              $('#showError').html('<div id="" class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert"> &times;</button><i class="fa fa-check"></i>&nbsp; <span>FAQ Added successfully!</span></div>');
              fetchFAQ();
          }else{
            $('#showError').html(response);
            // setTimeout(function(){
            //     $('#showError').html('');
            // },10000);

          }
        },
        complete:function(){
          $('#saveBtn').html('SAVE');
        }
      })
    });

    //fetch admin

    fetchFAQ();
    function fetchFAQ(){
      action = "fetchFaq";
      $.ajax({
        url:'scripts/super-process.php',
        method:'post',
        data:{action:action},
        success:function(response){
          $('#faq').html(response);
          $('#showfaq').DataTable({
              "paging": true,
              "lengthChange": true,
              "searching": true,
              "ordering": true,
              "order": [0,'desc'],
              "info": true,
              "hover": false,
              "autoWidth": true,
              "responsive": false,
              "lengthMenu": [[10,10, 25, 50, -1], [10, 25, 50, "All"]]
          });
        }
      })
    }

    // setInterval(function(){
    //   fetchAdminData();
    // },1000)

  })
</script>
