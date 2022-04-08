<?php
require_once '../../core/init.php';
$general = new General();
$advisor = new Admin();
$show = new Show();
$db = Database::getInstance();
$date = date('M,d Y');
if (isset($_POST['action']) && $_POST['action'] == 'fetchChat') {

    $check = $advisor->checkOnOff($advisor->data()->admin_uniqueid);
    $check2 = $general->checkDatePullChat();
   ?>
    <?php if ($check->chat_status == 1): ?>
        <!-- check if check2 returned value -->
      <?php if ($check2): ?>
      <?php foreach ($check2 as $outin): ?>
    <?php if ($outin->on_going_chat == 0): ?>
      <?php if ($outin->outgoing_message != ''): ?>
      <small class="text-muted text-italic">Previous Messages &nbsp; <?=timeAgo($check->chatDate)?> <?=pretty_dates($check->chatDate)?></small>
      <p class="incoming_message"><?=$outin->outgoing_message?></p>
    <?php endif?>
      <?php if ($outin->incoming_message != ''): ?>
        <small class="text-muted text-italic">Previous Messages &nbsp; <?=timeAgo($check->chatDate)?> <?=pretty_dates($check->chatDate)?></small>
      <p class="outgoing_message"><?=$outin->incoming_message?></p>
        <?php endif?>
    <?php else: ?>
      <?php
      // student incoming
      if ($outin->incoming_message != '') {
        $stud = new User($outin->stu_session_id);
        $passport = '<img src="../studentsPortal/profile/'.$stud->data()->passport.'" class="img-fluid" style="width:50px; height:50px; border-radius:50%;">';
        ?>

        <div class="incomeWrapper">
          <span><?=$passport?></span>

          <p class="incoming_message">
            <?

                echo $outin->incoming_message;
            ?>
        </p>

      </div>

      <?
        }
    ?>
      <?
      // advisor outgoing
        if ($outin->outgoing_message != '') {
          ?>
        <p class="outgoing_message">
          <?
              echo $outin->outgoing_message;
            }
            ?>
        </p>
    <?php endif; ?>

  <?php endforeach; ?>
<?php endif; ?>
<!--end of check if check2 returned value -->
  <?php else: ?>
    <p class="incoming_message">Currently Offline and Invisible to students</p>
  <?php endif;?>
  <?
}

if (isset($_POST['action']) && $_POST['action'] == 'sendMessage') {


  $level = Input::get('level');
  $message = Input::get('message');
  $advisor_uniquid = $advisor->data()->admin_uniqueid;
  $advisor_sessionid = $advisor->data()->id;
  $message = $show->test_input($message);

// grab current student u are chatting
  $getUser = $db->query("SELECT * FROM session_table  WHERE advisor_unique_id = '$advisor_uniquid' ");
  if ($getUser->count()) {
    $user = $getUser->first();
    $userunique = $user->stu_unique_id;
    $usersession = $user->stu_session_id;
  }

    $send = $general->sendInchat($advisor_uniquid,$advisor_sessionid,$level, $message,$userunique, $usersession);
    if ($send)
      echo 'success';






}
