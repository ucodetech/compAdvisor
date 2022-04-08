<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../../core/init.php';
$show = new Show();
$validate = new Validate();
$admin = new Admin();
$general = new General();

// fetch supervisors
if (isset($_POST['action']) && $_POST['action']== 'fatchAdvisors') {
  $data = $admin->grabAdvisor();
  if ($data) {
    echo $data;
  }
}

// add faq
if (isset($_POST['action']) && $_POST['action']== 'addFaq') {
  if (Input::exists()) {
    $validation = $validate->check($_POST, array(
      'question' => array(
        'required' => true
      ),
      'level' => array(
        'required' => true
      ),
      'answer' => array(
        'required' => true
      ),
    ));
    if ($validation->passed()) {
        $general->create('fqa_table',array(
          'question' => Input::get('question'),
          'answer' => Input::get('answer'),
          'level' => Input::get('level')
        ));
        echo 'success';
    }else{
      foreach ($validation->errors() as $error ) {
        echo $show->showMessage('danger', $error, 'warning');
        return false;
      }
    }
  }
}

// fetch fqa
if (isset($_POST['action']) && $_POST['action']== 'fetchFaq') {
  $data = $general->grabFQA();
  if ($data) {
    echo $data;
  }
}

// fetch details
if (isset($_POST['faq_id']) && !empty($_POST['faq_id'])) {
  $faq_id = $_POST['faq_id'];
  $data = $general->grabFQA2($faq_id);
  if ($data) {
    echo '
        <div class="row">
          <div class="col-lg-12">
          <div class="row">
            <div class="col-lg-8">
            <h5 class="text-center">
            Qustion: '.$data->question.'
            </h5>
            </div>
            <div class="col-lg-4">
            <h5 class="text-center">
            Level: '.$data->level.'
            </h5>
            </div>
          </div>
          </div>
          <hr class="hr2">
          <div class="col-lg-12">
            <p class="text-center text-info">Answer: '.$data->answer.'</p>
          </div>
        </div>
    ';
  }
}

// fetch for edit
if (isset($_POST['faq_ide']) && !empty($_POST['faq_ide'])) {
  $faq_ide = $_POST['faq_ide'];
  $data = $general->grabFQA2($faq_ide);
  if ($data) {
    echo '
    <form class="form" action="#" method="post" id="editFAQForm">
      <div class="form-group">
      <input type="hidden" name="fid" id="fid" value="'.$data->id.'">
        <label for="editquestion">Question: <sup class="text-danger
          ">*</sup></label>
          <input type="text" name="editquestion" id="editquestion" class="form-control" value="'.$data->question.'">
      </div>
      <div class="form-group">
        <label for="editlevel">Level: <sup class="text-danger
          ">*</sup></label>
          <select class="form-control" name="editlevel" id="editlevel">
            <option value="" '.(($data->level == '')?' selected': '').'>Select Level</option>
            <option value="ND"'.(($data->level == 'ND')?' selected': '').'>ND</option>
            <option value="HND"'.(($data->level == 'HND')?' selected': '').'>HND</option>
          </select>
      </div>
      <div class="form-group">
        <label for="editanswer">Answer: <sup class="text-danger
          ">*</sup></label>
          <textarea name="editanswer" id="editanswer" class="form-control" rows="10">'.$data->answer.'</textarea>
      </div>

      <div class="form-group">
        <button type="button" name="save" id="editFAQBtn" class="btn btn-info btn-block editFAQBtn">Edit</button>
        <div class="clear-fix"></div>
        <div id="showMsg"></div>
      </div>
    </form>
    ';
  }
}

// edit faq
if (isset($_POST['action']) && $_POST['action']== 'editfaq') {
  $id = Input::get('fid');
  if (Input::exists()) {
    $validation = $validate->check($_POST, array(
      'editquestion' => array(
        'required' => true
      ),
      'editlevel' => array(
        'required' => true
      ),
      'editanswer' => array(
        'required' => true
      ),
    ));

    if ($validation->passed()) {
        $general->edit('fqa_table',$id, array(
          'question' => Input::get('editquestion'),
          'answer' => Input::get('editanswer'),
          'level' => Input::get('editlevel')
        ));
        echo 'success';
    }else{
      foreach ($validation->errors() as $error ) {
        echo $show->showMessage('danger', $error, 'warning');
        return false;
      }
    }
  }
}


// complain process

// fetch fqa
if (isset($_POST['action']) && $_POST['action']== 'fetchComplain') {
  $data = $general->grabComplain();
  if ($data) {
    echo $data;
  }
}

// fetch details
if (isset($_POST['complain_id']) && !empty($_POST['complain_id'])) {
  $complain_id = $_POST['complain_id'];
  $data = $general->grabComplainDetail($complain_id);
  if ($data) {
    echo '
        <div class="row">
          <div class="col-lg-12">
          <div class="row">
            <div class="col-lg-8">
            <h5 class="text-center">
            Qustion: '.$data->question.'
            </h5>
            </div>
            <div class="col-lg-4">
            <h5 class="text-center">
            Level: '.$data->level.'
            </h5>
            </div>
          </div>
          </div>
          <hr class="hr2">
          <div class="col-lg-12">
            <p class="text-center text-info">Answer: '.$data->answer.'</p>
          </div>
        </div>
    ';
  }
}

// fetch for edit
if (isset($_POST['complain_ide']) && !empty($_POST['complain_ide'])) {
  $complain_ide = $_POST['complain_ide'];
  $data = $general->grabComplainDetail($complain_ide);
  if ($data) {
    echo '
    <form class="form" action="#" method="post" id="editFAQForm">
      <div class="form-group">
      <input type="hidden" name="fid" id="fid" value="'.$data->id.'">
        <label for="editquestion">Question: <sup class="text-danger
          ">*</sup></label>
          <input type="text" name="editquestion" id="editquestion" class="form-control" value="'.$data->question.'">
      </div>
      <div class="form-group">
        <label for="editlevel">Level: <sup class="text-danger
          ">*</sup></label>
          <select class="form-control" name="editlevel" id="editlevel">
            <option value="" '.(($data->level == '')?' selected': '').'>Select Level</option>
            <option value="ND"'.(($data->level == 'ND')?' selected': '').'>ND</option>
            <option value="HND"'.(($data->level == 'HND')?' selected': '').'>HND</option>
          </select>
      </div>
      <div class="form-group">
        <label for="editanswer">Answer: <sup class="text-danger
          ">*</sup></label>
          <textarea name="editanswer" id="editanswer" class="form-control" rows="10">'.$data->answer.'</textarea>
      </div>

      <div class="form-group">
        <button type="button" name="save" id="editFAQBtn" class="btn btn-info btn-block editFAQBtn">Edit</button>
        <div class="clear-fix"></div>
        <div id="showMsg"></div>
      </div>
    </form>
    ';
  }
}

// update complain
if (isset($_POST['action']) && $_POST['action']== 'resolveComplain') {
  if (Input::exists()) {
    $complain = Input::get('complain');

    $validation = $validate->check($_POST, array(
      'complain' => array(
        'required' => true
      ),
      'status' => array(
        'required' => true
      ),
      'student' => array(
        'required' => true
      )

    ));
    if (Input::get('status') === 'yes') {
        $dec = 'yes';
        $pro = 'completed';
    }else{
        $dec = 'no';
        $pro = Input::get('status');
    }
    $student = Input::get('student');
    if ($validation->passed()) {
        $general->edit('complain_table',$complain, array(
          'status' => $pro,
          'resolved' => $dec
        ));

        $ge = $db->query("SELECT * FROM complain_table WHERE id = '$complain'");
        if ($ge->count()) {
          $ro = $ge->first();
          if ($ro->resolved == 'yes') {
            $message = 'Your complained have been Resolved';
            $general->create('notification', array(
              'stu_session_id' => $student,
              'type' => 'Complain',
              'title' => $ro->complain_title,
              'message' => $message,

            ));
          }
        }
        echo 'success';
    }else{
      foreach ($validation->errors() as $error ) {
        echo $show->showMessage('danger', $error, 'warning');
        return false;
      }
    }
  }
}
