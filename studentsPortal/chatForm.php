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

</style>

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
                <h3 class="text-left">Frequently Asked questions</h3>
                <hr>
                <div class="container">
                  <?php
                    $faq = $db->query("SELECT * FROM fqa_table WHERE  level = '$userlevel' ");
                    if ($faq->count()) {
                      $f = $faq->results();
                      foreach ($f as $fq) {
                        ?>
                        <div id="accordion">
                        <div class="card">
                          <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                              <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?=$fq->id;?>" aria-expanded="true" aria-controls="collapse<?=$fq->id;?>">
                                <?=$fq->question;?>
                              </button>
                            </h5>
                          </div>

                          <div id="collapse<?=$fq->id;?>" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                              <?=$fq->answer;?>
                            </div>
                          </div>
                        </div>
                      </div>
                        <?
                      }

                    }else{
                      echo '<h5 class="text-danger text-italic">No Record Found</h5>';
                    }
                   ?>
                </div>
              </div>
              <div class="col-lg-6 col-md-12">
                <!-- check chat if on or off -->
                <?php
                    // query process turn off
                    if (isset($_POST['joinQueue'])) {
                        $user_uniquid = $_POST['user'];
                        $getqueue = $db->query("SELECT * FROM queue_table WHERE stu_unique_id = '$user_uniquid'");
                        if ($getqueue->count()) {

                            }else{
                              $db->query("INSERT INTO  queue_table (stu_unique_id, stu_session_id, chat_level) VALUES ('$user_uniquid', '$usersession', '$userlevel') ");
                              echo $show->showMessage('info', 'You have joined queue please hold on to be activated!', 'check');

                            }
                        }





                    // query process turn on
                    if (isset($_POST['activateChat'])) {
                        $user_uniquid = $_POST['user'];
                        $db->query("UPDATE session_table SET stu_unique_id
                           = '$user_uniquid',stu_session_id = '$usersession' WHERE chat_level = '$userlevel' ");
                           echo $show->showMessage('info', 'You can now chat with the Advisor', 'check');
                    }

                    //end chat
                    // query process turn off
                    if (isset($_POST['endChat'])) {
                        $user_uniquid = $_POST['user'];
                        $getqueue = $db->query("SELECT * FROM session_table WHERE chat_status = 1 AND stu_unique_id = '$user_uniquid' ");
                        if ($getqueue->count()) {
                            $db->query("UPDATE session_table SET stu_unique_id = NULL, stu_session_id = NULL ");
                            echo $show->showMessage('warning', 'You have ended the chat', 'warning');
                        }

                    }


                    $message = '';
                    $check = $user->checkOnOffStu($userlevel);
                    if ($check) {
                      if ($check->stu_unique_id != '') {
                        if ($check->stu_unique_id != $userunique){
                       echo '<form class="text-center" action="#" method="post">
                         <input type="hidden" name="user" value="'.$user->data()->stud_unique_id.'">
                            <button type="submit" name="joinQueue" class="btn btn-danger btn-sm m-2" id="joinQueue">Join Queue</button>
                        </form>';
                      }else{
                        echo '<form class="text-center" action="#" method="post">
                          <input type="hidden" name="user" value="'.$user->data()->stud_unique_id.'">
                             <button type="submit" name="endChat" class="btn btn-warning btn-sm m-2" id="endChat">End Chat</button>
                         </form>';
                      }
                      }elseif($check->stu_unique_id == ''){
                        echo ' <form class="text-center" action="#" method="post">
                      <input type="hidden" name="user" value="'.$user->data()->stud_unique_id.'">
                          <button type="submit" name="activateChat" class="btn btn-info btn-sm m-2" id="activateChat">Activate Chat</button>
                            </form>';
                      }
                    }


                 ?>


                <!-- chat header -->

                  <div class="container chatHeader">
                      <div class="containerImage">
                        <img src="../advisorPortal/profile/<?=$ad->admin_passport;?>" alt="photo" class="profileChat">
                        <div class="containerInfo ml-2">
                            <span class="text-info">Mrs. Odikta</span><br>
                            <div id="showStatus">

                            </div>
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
