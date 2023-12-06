<?php


use Models\Connexion;

exit("Permission denied");
ini_set('memory_limit', '-1');
set_time_limit(3600);
// loadLib('sms');

$users = [];
$count = 0;
// $promo  =  Models\Promotion::promotion_actuelle();
// $promotion_id =  $promo->ID;
// $new_inscriptions = \Models\Inscription::where(['Promotion' => $promotion_id, 'Depart IS NULL'])->get();
// $parents =  [];

// foreach ($new_inscriptions as $key => $inscription) {
//     $parrinages = \Models\Parrainage::getList(array('where' => array('Eleve' => $inscription->Eleve->ID)));
//     foreach ($parrinages as $p) {
//         $parents[$p->Parent->getKey()] = $p->Parent;
//     }
// }

// echo count($parents) . " parents";
// exit();
$config_password_generator = Config::get('config_password_generator');
$msgs = explode('[]', Config::has('config_send_sms') ? Config::get('config_send_sms') : '');
$users = array();
$parents = Models\Parentt::getList();
foreach ($parents as $key => $p) {
    try {
        if (($user = $p->User) && $user->Tel) {
            // $user->Tel = format_phone($user->Tel);
            // $user->save();
            // $user->Tel  = format_phone($user->Tel);
            // $password =   $user->Tel;
            // $password = $user->get('Email');
            $password = $config_password_generator . substr($user->get('Tel'), -2);
            $user->set('Key', \Tools::getRandChars(30, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));
            $user->set('Password', \Tools::passwordCrypt($password, $user->get('Key')));
            $user->save();
            $users[] = $user;
        }
    } catch (Exception $th) {
        continue;
    }
}


////////////////////////////////////////////////////////////////\
echo "Count : " . count($users) . " Password updated";
echo "<pre/>";
// exit;
// // test
// $user =  $users[0];
// $password = $config_password_generator . substr($user->get('Tel'), -2);
//$smsId = smsCasaNet('0669653769', $msgs[0] . ' Login : ' . $user->Tel . ' , Mot de passe : ' . $password);
// $smsId = smsCasaNet('0665612409', $msgs[0] . ' Login : ' . $user->Tel . ' , Mot de passe : ' . $password);
$smsId = smsCasaNet('0675515906', $msgs[0] . ' Login : ' . $user->Tel . ' , Mot de passe : ' . $password);
$smsId = smsCasaNet('0665642603', $msgs[0] . ' Login : ' . $user->Tel . ' , Mot de passe : ' . $password);
// exit();

$count  = 0;
$sent   = 0;
foreach ($users as $key => $user) {
    // if ($count > 200 && $count <= 463) {
    //$password = $user->Tel;
    $password = $config_password_generator . substr($user->get('Tel'), -2);
    $smsId = smsCasaNet($user->Tel, $msgs[0] . ' Login : ' . $user->Tel . ' , Mot de passe : ' . $password);
    echo "sent " . $user->Tel;
    echo "<pre/>";
    // }
    $count++;
}
echo $count . " sms sent updated ";
exit;
