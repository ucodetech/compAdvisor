<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../../core/init.php';

$advisor = new Admin();
$show = new Show();
$validate = new Validate();
$db  = Database::getInstance();
$level = $advisor->data()->advisor_level;
$advisor_uniquid = $advisor->data()->admin_uniqueid;
$general = new General();

// fetch supervisors
if (isset($_POST['action']) && $_POST['action']== 'fetchStudentsUnderMe') {
  $sup_id = $advisor->data()->advisor_level;
  $data = $advisor->getAnyTableAll('students','stud_level', $sup_id);
  if ($data) {
    $output = '';

    $output .= '<table class="table table-condensed">
  <thead>
    <th>#</th>
    <th>Passport</th>
    <th>Fullname</th>
    <th>Phone No</th>
    <th>Matric No.</th>
    <th>Department</th>
    <th>School</th>
  </thead>
  <tbody>

  ';
  foreach ($data as $get) {
    $Passport = '<img src="../studentsPortal/profile/'.$get->passport.'" alt="'.$get->stud_fname.'" class="activeImg">';
   $output .= '
   <tr>
      <td>'.$get->stu_id.'</td>
      <td>'.$Passport.'</td>
      <td>'.$get->stud_fname. ' '.$get->stud_lname.' '.$get->stud_oname.'</td>
      <td>'.$get->stud_tel.'</td>
      <td>'.$get->stud_regNo.'</td>
      <td>'.$get->stud_dept.'</td>
      <td>'.$get->stud_school.'</td>

    </tr>';
  }


$output .='
 </tbody>
</table>';
echo $output;
}else{
  echo '<h4 class="text-center text-warning">No record Found</h4>';
}
}

// fetch student on queue
if (isset($_POST['action']) && $_POST['action'] == 'fetchStudentsOnQueue') {
    $que = $db->query("SELECT * FROM queue_table WHERE chat_level = '$level'");
    if ($que->count()) {
        $result = $que->results();
        foreach ($result as $queue) {
          $stud = new User($queue->stu_session_id);
          ?>
          <div class="queueWrapper">
            <div class="row p-0">
              <div class="col-md-10">
                <img src="../studentsPortal/profile/<?=$stud->data()->passport?>" alt="<?=$stud->data()->stud_fname?>" class="activeImg">
                <span>
                <?=$stud->data()->stud_fname. ' '. $stud->data()->stud_lname?> ->  <?=strtoupper($stud->data()->stud_regNo);?>
              </span>
              </div>
              <div class="col-md-2 mt-2">

                  <button type="button" u-id="<?=$stud->data()->stud_unique_id?>" name="chatStudentBtn" class="btn btn-info btn-sm chatStudentBtn" id="chatStudentBtn">Activate</button>
              </div>
            </div>
          </div>
          <?
        }
    }
}


// fetch student online
if (isset($_POST['action']) && $_POST['action'] == 'fetchStudentsOnline') {
  $whoisonline = $advisor->checkOnline($advisor->data()->admin_uniqueid);
  if ($whoisonline) {
    $studentid = $whoisonline->stu_session_id;
    if ($studentid !='') {
    $stud = new User($studentid);
    $studname = $stud->data()->stud_fname. ' '. $stud->data()->stud_lname;
    $studregno = $stud->data()->stud_regNo;

    echo '
        <br><small class="text-center text-italic text-info">'.$studname.' with '.$studregno.' is Online</small>

    ';
  }else{
    echo '
        <br><small class="text-center text-italic text-info">The student have ended the chat</small>';
      }
  }

}

if (isset($_POST['studentId']) && !empty($_POST['studentId'])) {
    $studentId = $_POST['studentId'];

    //get from queue table
    $queue = $db->query("SELECT * FROM queue_table WHERE stu_unique_id = '$studentId' ");
    if ($queue->count()) {
        $found = $queue->first();
        $uniqueid = $found->stu_unique_id;
        $sessionid = $found->stu_session_id;

        $update = $db->query("UPDATE session_table SET stu_unique_id = '$uniqueid', stu_session_id = '$sessionid' WHERE  advisor_unique_id = '$advisor_uniquid' ");
        if ($update) {
          $db->query("DELETE FROM queue_table WHERE stu_unique_id = '$studentId' ");
         $db->query("UPDATE messages SET on_going_chat = 0 WHERE chat_level = '$level' ");
           echo 'success';
        }


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
  $data = $general->grabFQA3();
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
            Complain Title: '.$data->complain_title.'
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
            <p class="text-center text-info">Complain: '.$data->complain.'</p>
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
        <label for="editquestion">Complain Title: <sup class="text-danger
          ">*</sup></label>
          <input type="text" name="editquestion" id="editquestion" class="form-control" value="'.$data->complain_title.'">
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
        <label for="editanswer">Complain: <sup class="text-danger
          ">*</sup></label>
          <textarea name="editanswer" id="editanswer" class="form-control" rows="10">'.$data->complain.'</textarea>
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
