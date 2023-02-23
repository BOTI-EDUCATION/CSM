<?php

header("Access-Control-Allow-Origin: *");

// -- Start Session if not already started
if ((function_exists('session_status') && session_status() == PHP_SESSION_NONE) || session_id() == '')
	session_start();

require_once _basepath . 'includes/autoload.php';
require_once _basepath . 'includes/functions.php';
require_once _basepath . 'includes/mails.php';

ini_set('date.timezone', 'Etc/GMT-1'); // Timezone to GMT + 1
if (Config::get('display-errors'))
	ini_set('display_errors', 'on');


function send($response)
{
	exit(json_encode($response));
}

function sendAsyncResult($result)
{
	$response = array();
	$response  = $result;
	echo json_encode($response);
}

function sendResult($result)
{
	$response = array();
	$response = $result;
	send($response);
}


function compteBloque($user, $result)
{

	if (!$user->get('Enabled')) {

		$tokensAndroid = array();
		$tokensIos = array();
		if ($user->get('TokenDevice') == 'ios')
			$tokensIos[] = $user->get('TokenID');
		else
			$tokensAndroid[] = $user->get('TokenID');

		$message = 'Votre compte est bloqué.';

		if ($tokensAndroid) {
			$messageAndroid =  array(
				"title" => 'Compte bloqué !',
				'body' 	=> $message,
				"content-available" => '0',
				'icon'	=> 'myicon',
				'sound' => 'mySound',
			);
			$message_status = send_notification_firebase($tokensAndroid, $messageAndroid, 'android');
		}

		if ($tokensIos) {
			$messageIos =  array(
				"title" => 'Compte bloqué !',
				'body' 	=> $message,
				'icon'	=> 'myicon',
				'sound' => 'mySound',
			);
			$message_status = send_notification_firebase($tokensIos, $messageIos, 'ios');
		}

		$notif = new Models\Notif();
		$notif
			->set('Label', 'Compte bloqué ! Parent : ' . $user->getNomComplet())
			->set('Message', $message)
			->set('Date', date('Y-m-d H:i:s'));
		$notif->save();

		sendResult($result);
	}
}

function checkUserKey($user, $key, $result)
{

	$result['disconnect'] = false;
	if (sha1($user->get('Key')) != $key) {
		$result['disconnect'] = true;
		sendResult($result);
	}
}

function checkParrainage($parent, $eleve, $result)
{


	$parrainage = Models\Parrainage::checkParrainage($parent, $eleve);

	if (!$parrainage) {
		$result['disconnect'] = true;
		sendResult($result);
	}

	if (!$eleve->get('User')->get('Enabled')) {
		$result['disconnect'] = true;
		sendResult($result);
	}
}



// check languages 
$versionAr =  Models\Config::getByAlias('version_ar');
$versionAr = $versionAr && $versionAr->get('Value') ? true : false;
$_translation =  require _basepath . "lang/fr.php";

if ((isset($_GET['parent_id']) && $_GET['parent_id']) || (isset($_POST['parent_id']) && $_POST['parent_id'])) {

	$parentID = "A";
	if ((isset($_GET['parent_id']) && $_GET['parent_id'])) {
		$parentID = $_GET['parent_id'];
	}

	if ((isset($_POST['parent_id']) && $_POST['parent_id'])) {
		$parentID = $_POST['parent_id'];
	}

	try {
		$parent = new Models\Parentt($parentID);

		if ($parent && file_exists($file_lang =  _basepath . "lang/" . $parent->Lang . ".php")) {
			$_translation  =  require $file_lang;
		}
	} catch (Exception $e) {
	}
}
// end check languages



function checkLang($parent)
{
	global $_translation;
	if ($parent && file_exists($file_lang =  _basepath . "lang/" . $parent->Lang . ".php")) {
		$_translation  =  require $file_lang;
	}
}


// unsed
function changeLang($parent, $eleve)
{
	//
}

function __translate($key_message, $args = array())
{
	global $_translation;
	$keys  = explode('.', $key_message);
	$message =  $_translation;
	foreach ($keys as $key) {
		if (!isset($message[$key])) {
			return  $key_message;
		}
		$message  = $message[$key];
	}

	foreach ($args as $key => $value) {
		$message = str_replace(':' . $key, $value, $message);
	}
	return $message;
}
