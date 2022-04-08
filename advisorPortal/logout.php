<?php
require_once  '../core/init.php';
$advisor  = new Admin();
$advisor->logout();
Redirect::to('ad-login');
