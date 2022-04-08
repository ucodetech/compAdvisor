<?php
require_once  '../../core/init.php';
$user = new User();
$feedback = new Feedback();
$show = new Show();
$validate = new Validate();

if (isset($_POST['action']) && $_POST['action'] == 'complainNow') {

    if (Input::exists()) {
        $validation = $validate->check($_POST, array(
            'stud_unique_id' => array(
                'required' => true,
            ),
            'level' => array(
                'required' => true,
            ),
            'title' => array(
                'required' => true,
            ),
            'complain' => array(
                'required' => true,
            ),

        ));
        if ($validation->passed()) {
            $userid = Input::get('stud_unique_id');
            try {
                $feedback->feedBack(array(
                    'stu_session_id' => Input::get('stud_unique_id'),
                    'level' => Input::get('level'),
                    'complain_title' => Input::get('title'),
                    'complain' => Input::get('complain')

                ));
                // $feedback->notifyMe($userid, 'Sent Feedback');
                echo 'success';

            } catch (Exception $e) {
                echo $show->showMessage('danger', $e->getMessage(), 'warning');
                return false;
            }

        } else {
            foreach ($validation->errors() as $error) {
                echo $show->showMessage('danger', $error, 'warning');
                return false;
            }
        }

    }
}
