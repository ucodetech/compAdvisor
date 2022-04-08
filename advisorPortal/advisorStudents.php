<style media="screen">
.chatBox{
  width:80%;
  height: 400px !important;
  border:2px solid #a83d1e;
  overflow-y: scroll;


}
.chatBox .incoming_message{
  width: 90%;
  height: auto;
  padding:6px;
  background:#f87c08;
  display: block;
  color:#000;
  margin-right: 5px !important;
  margin-left: 0px !important;
  margin-top:5px;
  border-top-left-radius: 0px;
  border-top-right-radius: 30px;
  border-bottom-left-radius: 30px;
  border-bottom-right-radius: 20px;

}
.chatBox .outgoing_message{
  width: 90%;
  height: auto;
  padding:6px;
  background:#84359a;
  display: block;
  color: #002;
  margin-left: 44px;
  margin-right: 0px !important;
  border-top-left-radius: 30px;
  border-top-right-radius: 0px;
  border-bottom-left-radius: 20px;
  border-bottom-right-radius: 30px;



}
/* Hide scrollbar for Chrome, Safari and Opera */
.chatBox::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.chatBox {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
.formBox{
  width:80%;
  height: 75px !important;
  border:2px solid #a83d1e;
  border-top:none;
  border-bottom-right-radius: 30px;
  border-bottom-left-radius: 30px;
  padding: 0px;
  margin-bottom: 10px;
}
.chatHeader{
  width:80%;
  height: 75px !important;
  border:2px solid #a83d1e;
  border-bottom: none;
  border-top-right-radius: 30px;
  border-top-left-radius: 30px;
  padding: 4px;

}
.formBox form{
  padding:0px;
  width: 80%;
  margin: 0px;


}
.formBox form .row{
  padding:0px;
  margin-left: 1px;
  margin-bottom: 1px;

}
textarea{
width: 100%;
background: none;
border:2px solid #a83d1e;
border-radius: 10px;
color:#fff;
}
.profileChat{
  width: 60px;
  height: 60px;
  border-radius: 50%;
}
.containerImage {
  display: inline-flex;
  margin-left:2px;
}
.containerInfo .{
  margin-left:4px !important;
  padding:0px;
  display: inline-flex;
}
.incomeWrapper{
  display:inline-flex;
}

</style>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title">Students Under Me</h4>
        <p class="card-category">List of Students Under Me</p>
      </div>
      <div class="card-body table-responsive"  id="studentsUnderme">

      </div>
    </div>
  </div>
</div>
<hr class="hr2">
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header card-header-tabs card-header-warning">
        <div class="nav-tabs-navigation">
          <div class="nav-tabs-wrapper">
            <span class="nav-tabs-title">Chat Box</span>
            <ul class="nav nav-tabs" data-tabs="tabs">
              <li class="nav-item">
                <a class="nav-link active" href="#profile" data-toggle="tab">
                  <i class="material-icons fa fa-search fa-lg"></i> Chat
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
            <div class="row">
              <div class="col-lg-6 col-md-12">
                <h3 class="text-left">Student on Queue</h3>
                <hr>
                <div class="container" id="queue">
                </div>
              </div>
              <div class="col-lg-6 col-md-12">
                <!-- check chat if on or off -->
                <?php
                    // query process turn off
                    if (isset($_POST['TurnOffChat'])) {
                        $advisor_uniquid = $_POST['advisor'];
                        $db->query("UPDATE session_table SET chat_status = 0 WHERE advisor_unique_id = '$advisor_uniquid' ");
                        $db->query("UPDATE messages SET on_going_chat = 0 WHERE advisor_unique_id = '$advisor_uniquid' ");
                    }

                    // query process turn on
                    if (isset($_POST['TurnOnChat'])) {
                        $advisor_uniquid = $_POST['advisor'];
                        $db->query("UPDATE session_table SET chat_status = 1, chat_active_time = NOW() WHERE advisor_unique_id = '$advisor_uniquid' ");
                    }


                    $check = $advisor->checkOnOff($advisor->data()->admin_uniqueid);
                    if ($check->chat_status == 1) {
                    echo '<form class="text-center" action="#" method="post">
                          <input type="hidden" name="advisor" value="'.$advisor->data()->admin_uniqueid.'">
                          <button type="submit" name="TurnOffChat" class="btn btn-danger btn-sm m-2" id="TurnOffChat">Turn Off Chat</button>
                      </form>';
                    }elseif($check->chat_status == 0){
                      echo ' <form class="text-center" action="#" method="post">
                              <input type="hidden" name="advisor" value="'.$advisor->data()->admin_uniqueid.'">
                              <button type="submit" name="TurnOnChat" class="btn btn-info btn-sm m-2" id="TurnOnChat">Turn On Chat</button>
                          </form>';
                    }
                 ?>


                <!-- chat header -->
                  <div class="container chatHeader">
                      <div class="containerImage">
                        <img src="profile/<?=$advisor->data()->admin_passport;?>" alt="photo" class="profileChat">
                        <div class="containerInfo ml-2">
                            <span class="text-info">Mrs. Odikta</span><br>
                            <?php if ($check->chat_status == 1): ?>
                            <span class="text-success activeStatus"><i class=" fa fa-circle text-success"></i> Online</span>
                            <?php else: ?>
                              <span class="text-danger activeStatus"><i class=" fa fa-circle text-danger"></i> <small class="text-muted timeStatus"><i><?=timeAgo($check->chat_active_time)?></i></small></span>
                            <?php endif; ?>

                            <span id="showStudentOnline"></span>
                        </div>
                      </div>

                  </div>
                  <!-- end chat header -->
                <!-- message box -->

                <div class="container chatBox"  id="chatBox">

                </div>
                <!-- end of message box -->
                <!-- form box -->
                <div class="container formBox">
                  <form class="form" action="#" method="POST" id="chatboxForm">
                      <input type="hidden" name="level" id="level" value="<?=$advisor->data()->advisor_level?>">
                    <div class="row">
                      <div class="form-group col-md-10">
                        <textarea name="message" id="message" cols="2" class="message"></textarea>
                      </div>
                      <div class="form-group col-md-2">
                        <button type="button" name="send" id="send" class="btn btn-primary btn-sm"><i class="fa fa-telegram"></i></button>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="clear-fix"></div>
                      <div id="err">
                      </div>
                    </div>
                  </form>
                </div>
                <!-- end of form box -->
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
