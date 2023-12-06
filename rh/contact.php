<?php

require_once 'header.php';

$result = array();
$result['data'] = array();
$result['empty'] = false;
$result['empty_icon'] = URL::absolute(URL::base('assets/icons/no_result.png'));
$result['empty_text'] = 'Vous n’avez aucune demande <br> pour le moment';

try {
	$parent = new Models\Parentt($_GET['parent_id']);
} catch (Exception $e) {
	exit('Element introuvable');
}

try {
	$eleve = new Models\Eleve($_GET['eleve_id']);
} catch (Exception $e) {
	exit('Element introuvable');
}

changeLang($parent, $eleve);

$result['translation'] = array(
	'title' => __translate('contact.title'),
);

$key = $_GET['key'];

// checkUserKey($parent->get('User'), $key, $result);
checkParrainage($parent, $eleve, $result);
compteBloque($eleve->get('User'), $result);



$result['data']['icone'] = 'https://image.flaticon.com/icons/svg/1049/1049875.svg';
$result['data']['logo'] = (Config::get('logo') ? URL::absolute(URL::base('assets/images/schools/' . Config::get('logo'))) : URL::absolute(URL::base('assets/images/logo.92874874.png')));
$result['data']['title'] = __translate('contact.title');
$result['data']['text'] =  __translate('contact.des');
$result['data']['website'] = Config::has('website') ? Config::get('website'): null;
$result['data']['social_text'] =   __translate('contact.social_text');

switch ($eleve->getInscription()->Niveau->Cycle->ID) {
	case 1:
		$result['data']['tel'] = Config::has('tel_prescolaire') && Config::get('tel_prescolaire') && Config::get('tel_prescolaire') != '' ? Config::get('tel_prescolaire') : (Config::has('tel') ? Config::get('tel') : '+212 5 22 22 22 22');
		break;
	case 2:
		$result['data']['tel'] = Config::has('tel_primaire') && Config::get('tel_primaire') && Config::get('tel_primaire') != '' ? Config::get('tel_primaire') : (Config::has('tel') ? Config::get('tel') : '+212 5 22 22 22 22');
		break;
	case 3:
		$result['data']['tel'] = Config::has('tel_college') && Config::get('tel_college') && Config::get('tel_college') != '' ? Config::get('tel_college') : (Config::has('tel') ? Config::get('tel') : '+212 5 22 22 22 22');
		break;
	case 4:
		$result['data']['tel'] = Config::has('tel_lycee') && Config::get('tel_lycee') && Config::get('tel_lycee') != '' ? Config::get('tel_lycee') : (Config::has('tel') ? Config::get('tel') : '+212 5 22 22 22 22');
		break;
	default:
		$result['data']['tel'] = Config::has('tel') ? Config::get('tel') : '+212 5 22 22 22 22';
		break;
}


if (Config::has('facebook') && Config::get('facebook')) {
	$result['data']['socials'][] = array(
		'link' => Config::has('facebook') ? Config::get('facebook') : '#',
		'icone' => 'logo-facebook',
		'color' => '#4c6bac',
	);
}
if (Config::has('twitter') && Config::get('twitter')) {
	$result['data']['socials'][] = array(
		'link' => Config::has('twitter') ? Config::get('twitter') : '#',
		'icone' => 'logo-twitter',
		'color' => '#53b2ed',
	);
}
if (Config::has('instagram') && Config::get('instagram')) {
	$result['data']['socials'][] = array(
		'link' => Config::has('instagram') ? Config::get('instagram') : '#',
		'icone' => 'logo-instagram',
		'color' => '#f13e76',
	);
}
if (Config::has('youtube') && Config::get('youtube')) {
	$result['data']['socials'][] = array(
		'link' => Config::has('youtube') ? Config::get('youtube') : '#',
		'icone' => 'logo-youtube',
		'color' => '#FF0000',
	);
}

sendResult($result);
