<?php
require_once '../../core/init.php';
$general = new General();
$user = new User();
$show = new Show();
$db = Database::getInstance();
$dateToday = date("M d, Y ");
$userlevel = $user->data()->stud_level;
if (isset($_POST['action']) && $_POST['action'] == 'fetchChat') {

    $check = $user->checkOnOffStu($userlevel);
    $check2 = $general->checkDatePullChatStu($user->data()->stud_unique_id);
   ?>
    <?php if ($check->chat_status == 1): ?>
        <!-- check if check2 returned value -->
      <?php if ($check2): ?>
      <?php foreach ($check2 as $outin): ?>
   <?php if ($outin->on_going_chat == 0 && pretty_dates($outin->chatDate) == $dateToday): ?>
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
        ?>
        <p class="incoming_message">
          <?

              echo $outin->incoming_message;
          ?>
      </p>
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
    <p class="incoming_message">Please Hold while the Advisor comes online! meanwhile as you wait, you can access the Frequently Asked Question to see if your problem is been solved or mention by other students <a href="student-faq" class="text-info">FAQ</a>  </p>
  <?php endif;?>
  <?
}

if (isset($_POST['action']) && $_POST['action'] == 'sendMessage') {

  $level = $userlevel;
  $message = Input::get('message');
  $stud_uniquid = $user->data()->stud_unique_id;
  $stud_sessionid = $user->data()->stu_id;
  $message = $show->test_input($message);

    $send = $general->sendInchatStu($stud_uniquid,$stud_sessionid,$level, $message);
    if ($send)
      echo 'success';






}

// fetch chat header status


if (isset($_POST['action']) && $_POST['action'] == 'chatHeaderStatus') {
    $advisorunique_id = Input::get('advisoruniqueid');
    $check = $user->checkOnOff($advisorunique_id);
    if ($check->chat_status == 1){
    ?>
       <span class="text-success activeStatus"><i class=" fa fa-circle text-success"></i> Online</span>
       <?
   }else{
   ?>
     <span class="text-danger activeStatus"><i class=" fa fa-circle text-danger"></i> <small class="text-muted timeStatus"><i><?=timeAgo($check->chat_active_time)?></i></small></span>
  <?
}
}
