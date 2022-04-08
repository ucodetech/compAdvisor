<?php

 /**
  * General
  */
 class General
 {

 	private  $_db,
           $_user,
           $_super;


  function __construct()
  {
    $this->_db = Database::getInstance();
   $this->_user = new User() ;
   $this->_super = new Admin();

  }

  public function superNow()
  {
   return $this->_super;
  }



  public function updateAdmin(){
       $super = $this->superNow()->getAdminId();
        $sql = "UPDATE admin SET sudo_lastLogin = NOW() WHERE id = '$super' ";
        $this->_db->query($sql);
        return true;
    }


  public function totalCount($tablename){
    $sql = "SELECT * FROM $tablename";
    $count =  $this->_db->query($sql);
    if ($count->count()) {
      return $count->count();
    }else{
      return '0';
    }
  }
  public function totalCount2(){
    $sql = "SELECT * FROM admin WHERE admin_permissions = 'advisor' ";
    $count =  $this->_db->query($sql);
    if ($count->count()) {
      return $count->count();
    }else{
      return '0';
    }
  }
     public function totalCountApproved($tablename, $val){
         $sql = "SELECT * FROM $tablename WHERE approved = $val AND deleted = 0 ";
         $count =  $this->_db->query($sql);
         if ($count->count()) {
             return $count->count();
         }else{
             return '0';
         }
     }


  public function verified_admin($status){
    $count =  $this->_db->get('admin', array('sudo_verified', '=', $status));
    if ($count->count()) {
      return $count->count();
    }else{
      return '0';
    }
  }




  //Reply to user feedback
public function replyFeedback($userid, $message){
    $this->_db->insert('notifications', array(
      'user_id' => $userid,
      'type' => 'user',
      'message' => $message
    ));
    return true;
  }


public function updateFeedbackReplied($feedid){
    $this->_db->update('feedback','id', $feedid , array(
      'replied' => 1
    ));
    return true;
  }




public function getDepartment()
{
  $dept = $this->_db->get('department', array('department_name', '=', 'COMPUTER SCIENCE'));
  if ($dept->count()) {
      return $dept->first();
  }else {
    return false;
  }
}
     public function getSchool()
     {
         $sch = $this->_db->get('schoolsTable', array('deleted', '=', 0));
         if ($sch->count()){
             return $sch->results();
         }else {
             return false;
         }
     }
 public function getState()
     {
         $state = $this->_db->get('states', array('deleted', '=', 0));
         if ($state->count()){
             return $state->results();
         }else {
             return false;
         }
     }
 public function getLGA()
     {
         $lga = $this->_db->get('lga', array('deleted', '=', 0));
         if ($lga->count()){
             return $lga->results();
         }else {
             return false;
         }
       }

public function updateStudents($memberId,$fields = array()){
      $this->_db->update('students','stud_unique_id', $memberId,$fields);
      return true;

}



    public function loggedUsers(){
        $sql = "SELECT * FROM students WHERE stud_last_login > DATE_SUB(NOW(), INTERVAL 5 SECOND)";
        $data = $this->_db->query($sql);
        if ($data->count()) {
            return $data->results();
        }else{
            return false;
        }
    }

// faq processes
public function create($table, $fields=array())
{
  if (!$this->_db->insert($table, $fields)) {
      throw new \Exception("Error Processing Request", 1);

  }
}
public function edit($table, $id, $fields=array())
{
  if (!$this->_db->update($table,'id', $id, $fields)) {
      throw new \Exception("Error updating records", 1);

  }
}
// fetch faq
public function grabFQA()
{
  $dat = $this->_db->get('fqa_table', array('deleted', '=', 0));
  if ($dat->count()) {
      $rows = $dat->results();
      $output = '';
      $output .='<table class="table table-hover" id="showfaq">
          <thead class="text-warning">
            <th>#</th>
            <th>Question</th>
            <th>Answer</th>
            <th>Level</th>
            <th>Action</th>
          </thead>
          <tbody>
            ';
            $x = 0;
      foreach ($rows as $fq) {
        $x = $x+1;
        $output .='
        <tr>
          <td>'.$x.'</td>
          <td>'.wrap($fq->question).'...</td>
          <td>'.wrap($fq->answer).'...</td>
          <td>'.$fq->level.'</td>
          <td>
          <a href="#" d-id="'.$fq->id.'" data-toggle="modal" data-target="#FaqDetail" class="text-primary FaqDetailIcon" title="Admin Detail"><i class="fa fa-info-circle fa-lg"></i></a>&nbsp;
          <a href="#" e-id="'.$fq->id.'" data-toggle="modal" data-target="#FaqEdit" class="text-success  FaqEditIcon" title="Edit FAQ"><i class="fa fa-edit fa-lg"></i></a>&nbsp;
          </td>

        </tr>
        ';

      }
      $output .='
              </tbody>
            </table>';

    return $output;


  }else{
    return '<span class="text-info">No record found</span>';
  }
}

public function grabFQA3()
{
  $level = $this->_super->data()->advisor_level;
  $dat = $this->_db->get('fqa_table', array('level', '=', $level));
  if ($dat->count()) {
      $rows = $dat->results();
      $output = '';
      $output .='<table class="table table-hover" >
          <thead class="text-warning">
            <th>#</th>
            <th>Question</th>
            <th>Answer</th>
            <th>Level</th>
            <th>Action</th>
          </thead>
          <tbody>
            ';
            $x = 0;
      foreach ($rows as $fq) {
        $x = $x+1;
        $output .='
        <tr>
          <td>'.$x.'</td>
          <td>'.wrap($fq->question).'...</td>
          <td>'.wrap($fq->answer).'...</td>
          <td>'.$fq->level.'</td>
          <td>
          <a href="#" d-id="'.$fq->id.'" data-toggle="modal" data-target="#FaqDetail" class="text-primary FaqDetailIcon" title="Admin Detail"><i class="fa fa-info-circle fa-lg"></i></a>&nbsp;
          <a href="#" e-id="'.$fq->id.'" data-toggle="modal" data-target="#FaqEdit" class="text-success  FaqEditIcon" title="Edit FAQ"><i class="fa fa-edit fa-lg"></i></a>&nbsp;
          </td>

        </tr>
        ';

      }
      $output .='
              </tbody>
            </table>';

    return $output;


  }else{
    return '<span class="text-info">No record found</span>';
  }
}


public function grabFQA2($faq_ide)
{
  $get = $this->_db->get('fqa_table', array('id', '=', $faq_ide));
  if ($get->count()){
    return $get->first();
  }else{
    return false;
  }
}

public function grabComplain()
{
  $dat = $this->_db->get('complain_table', array('resolved', '=', 'no'));
  if ($dat->count()) {
      $rows = $dat->results();
      $output = '';
      $output .='<table class="table table-hover">
          <thead class="text-warning">
            <th>#</th>
            <th>Student Name</th>
            <th>Reg No</th>
            <th>Level</th>
            <th>Complain</th>
            <th>Date</th>
            <th>Resolved</th>
            <th>Action</th>
          </thead>
          <tbody>
            ';
            $x = 0;
      foreach ($rows as $cp) {
        $student = new User($cp->stu_session_id);
        $name = $student->data()->stud_fname .' '. $student->data()->stud_lname;
        $RegNo = $student->data()->stud_regNo;
        $x = $x+1;
        $output .='
        <tr>
          <td>'.$x.'</td>
          <td>'.$name.'</td>
          <td>'.$RegNo.'</td>
          <td>'.$cp->level.'</td>
          <td>'.wrap($cp->complain).'...</td>
          <td>'.pretty_dates($cp->dateComplained).'...</td>
          <td>'.$cp->resolved.'</td>
          <td>
          <a href="#" d-id="'.$cp->id.'" data-toggle="modal" data-target="#CPDetail" class="text-primary CPDetailIcon" title="Complain Detail"><i class="fa fa-info-circle fa-lg"></i></a>&nbsp;
          
          </td>

        </tr>
        ';

      }
      $output .='
              </tbody>
            </table>';

    return $output;


  }else{
    return '<span class="text-info">No record found</span>';
  }
}


public function grabComplainDetail($id)
{
  $dat = $this->_db->get('complain_table', array('id', '=', $id));
  if ($dat->count()) {
      return $dat->first();
  }else{
    return false;
  }
}

public function grabComplainInput()
{
  $dat = $this->_db->get('complain_table', array('resolved', '=', 'no'));
  if ($dat->count()) {
      return $dat->results();
  }else{
    return false;
  }
}

public function checkDatePullChat()
{
  $admin = new Admin();
    $date = date('M d, Y');
    $advisor = $admin->data()->advisor_level;
  $get = $this->_db->query("SELECT * FROM messages WHERE chat_level = '$advisor' AND on_going_chat = 1 ");
  if ($get->count()) {
      return $get->results();
  }else{
    return false;
  }
}

public function chatInsert($fields = array())
{
  return $this->_db->insert('messages', $fields);
}

public function sendInchat($advisor_uniquid,$advisor_sessionid,$level, $message,$userunique, $usersession)
{
  $this->_db->insert('messages', array(
    'advisor_unique_id' => $advisor_uniquid,
    'advisor_session_id' => $advisor_sessionid,
    'chat_level' => $level,
    'outgoing_message' => $message,
    'stu_unique_id' => $userunique,
    'stu_session_id' => $usersession ,
    'on_going_chat' => 1,
  ));
  return true;
}


public function sendInchatStu($stud_uniquid,$stud_sessionid,$level, $message)
{
  $this->_db->insert('messages', array(
    'stu_unique_id' => $stud_uniquid,
    'stu_session_id' => $stud_sessionid,
    'chat_level' => $level,
    'incoming_message' => $message,
    'on_going_chat' => 1,
  ));
  return true;


}

public function checkDatePullChatStu($useruniqueid)
{
  $get = $this->_db->query("SELECT * FROM messages WHERE stu_unique_id = '$useruniqueid'AND on_going_chat = 1 ");
  if ($get->count()) {
      return $get->results();
  }else{
    return false;
  }
}



}//end of class
