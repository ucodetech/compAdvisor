<?php
require_once '../../core/init.php';
$general = new General();
$admin = new Admin();


if (isset($_POST['action']) && $_POST['action'] == 'update_admin') {
  $admin->updateAdminLog($admin->data()->id);

}
