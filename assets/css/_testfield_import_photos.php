<?php

exit("Permission denied");

use Models\ActionLog;
use Models\Eleve;

ini_set('memory_limit', '-1');
set_time_limit(1800);

$files = scandir(_basepath . \Config::get('path-images-users'));
$count = 0;
foreach ($files as  $file) {
    $massar = explode('.', $file)[0];
    if ($massar) {
        $eleve = Eleve::getByMassar($massar);
        if ($eleve) {
            $count++;
            echo $massar . " Passed";
            echo "<pre/>";
            $user = $eleve->User;
            $user->set('Image', $file);
            //  $user->getSmallSizeImage();
            $user->save();
        }
    }
}
echo "<pre/>";
echo $count++;
