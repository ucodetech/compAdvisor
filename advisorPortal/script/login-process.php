<?php
require_once '../../core/init.php';

$advisor = new Admin();
$validate = new Validate();
$show = new Show();

if (isset($_POST['action']) && $_POST['action'] == 'loginAdvisor') {

    $username = $show->test_input($_POST['username']);
    $password = $show->test_input($_POST['password']);

    if (empty($_POST['username'])) {
        echo $show->showMessage('danger','Username is required', 'warning');
        return false;
    }
    if (empty($_POST['password'])) {
        echo $show->showMessage('danger','Password is required', 'warning');
        return false;
    }


    $login = $advisor->login($username, $password);
    if ($login) {
        echo 'success';
    }


}
