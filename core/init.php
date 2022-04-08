<?php
session_start();
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'db' => 'courseAdvisor'

	),
	'remember' => array(
		'cookie_name' => 'advisorHash',
		'cookie_expiry' => '604800'
	),
	'session' => array(
		'session_admin' => 'advisorAdmin',
		'session_advisor' => 'advisorAdvisor',
		'session_students' => 'advisorStudent',
		'token_name' => 'token'
	)
);

//APP ROOT
define('APPROOT', dirname(dirname(__FILE__)));

//URL ROOT

define('URLROOT', 'http://localhost/compAdvisor/');

//SITE NAME
define('SITENAME', 'Course Advisor System');
define('APPVERSION', '1.0.0');
define('ADMIN', 'CONTROL ROOM');
define('NAVNAME', 'CCA');
define('DASHBOARD', 'CCA Panel');
// define('EMAIL', 'youremail@gmail.com');
// define('PASSWORD', 'passwaord\\\===\\\@');
// define('AUDIOPATH', 'uploads/sermon/');




spl_autoload_register(function ($class) {
	require_once(APPROOT . '/classes/' . $class . '.php');
});


require_once(APPROOT . '/helpers/session_helper.php');
require_once(APPROOT . '/helpers/session.php');
