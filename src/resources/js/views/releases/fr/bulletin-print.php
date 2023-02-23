<style>
    .notes tr td {
        height: 22px;
    }
</style>

<table border="1">
    <tr>
        <td align="center" valign="middle">
            <h3 style="font-size: 30px;padding: 0px;">
                <?php echo $compte->get('Label') ?>
            </h3>
            <?php if (isAllowed('print_bulletin_show_autorisation')) { ?>
                <span style="font-size: 15px;font-weight:100">Autorisation du ministère de l'éducation nationale n° <span class="bold"><?php echo $compte->get('AutorisationNum') ?></span> du <span class="bold"><?php echo Tools::dateFormat($compte->get('AutorisationDate'), '%d/%m/%Y')  ?></span></span>
            <?php } ?>
        </td>
        <td align="center">
            <?php for ($i = 0; $i <= 2; $i++) { ?>&nbsp; <br /> <?php } ?>
        <br />
        <?php $logo = (Config::get('logo') ? URL::base('assets/images/schools/' . Config::get('logo')) : URL::base('assets/images/logo.103983.png')); ?>
        <img src="<?php echo $logo ?>" style="width:100px;" />

        </td>
        <td align="center" valign="middle">
            <h3 style="font-size: 30px;padding: 0px;">
                <?php echo $compte->get('LabelAr') ?: $compte->get('Label') ?>
            </h3>
            <?php if (isAllowed('print_bulletin_show_autorisation')) { ?>
                <span style="font-size: 15px;font-weight:100">
                    رقم ترخيص وزارة التربية الوطنية : <br />
                    <span class="bold"><?php echo Tools::dateFormatAr($compte->get('AutorisationDate'), '%Y/%m/%d') ?></span> بتاريخ <span class="bold"><?php echo $compte->get('AutorisationNum') ?></span>
                </span>
            <?php } ?>
        </td>
    </tr>
</table>

<table cellpadding="1" class="doc-title" border="1" style="width:100%;padding: 0px !important">
    <tr>

        <td class="td-left" style="font-size: 21px;">
            <span style="font-weight: bold;">Nom et Prénom : </span>
            <span style="font-weight: 100;" align="center"><?php echo strtoupper($inscription->get('Eleve')->get('User')->get('Nom')) . ' ' . ucfirst($inscription->get('Eleve')->get('User')->get('Prenom')) ?></span>
        </td>
        <td class="td-right" rowspan="2" align="center">
            <?php if ($semestre == 1) { ?>
                <span style="font-weight: bold;text-transform:uppercase;text-decoration:underline">BULLETIN<br>MI-SEMESTRE `1`</span>
            <?php } else { ?>
                <span style="font-weight: bold;text-transform:uppercase;text-decoration:underline">BULLETIN<br />MI-SEMESTRE `2`</span>
            <?php } ?>
        </td>
    </tr>

    <tr>
        <td class="td-left" style="font-size: 21px;">
            <span style="font-weight: bold;">Classe : </span>
            <span style="font-weight: 100;" align="center"><?php echo $inscription->get('Classe')->get('Label') ?> / <?php echo $inscription->get('Promotion')->get('Label') ?></span>
        </td>
    </tr>
</table>

<br>
<br>
<table cellpadding="1" class="doc-title" border="1" style="width:100%;padding: 0px !important">

    <tr>
        <td align="center" colspan="2" width=" <?php echo $show_appreciation ? '300px' : '480px' ?>" style="font-size: 21px;"><b>Matiére </b></td>
        <td align="center" colspan="2" width=" <?php echo $show_appreciation ? '150px' : 'auto' ?>" style="font-size: 21px;"><b>Moyenne Matiére </b></td>
        <?php if ($show_appreciation) { ?>
            <td align="center" width="auto" style="font-size: 21px;"> <b> Appréciations </b> </td>
        <?php  } ?>
    </tr>

    <?php
    $moyenne = 0;
    $somme_cf = 0;
    $moyenne_s1 = 0;
    $somme_cf_s1 = 0;
    foreach ($niveaux_unites as $niveau_unite) {



        $unite = $niveau_unite->get('Unite');
        $matieres = $unite->getProgramtionMatieres($niveau->get('ID'), $type_coefficient);
        $evatuations = $niveau_unite->getProgramationEvaluationsControles($semestre);
        $moyenne_unite  =  Models\Evaluation::getMoyennesUnite($inscription, $niveau_unite, $semestre);

        if (!count($matieres)) {
            continue;
        }

        if ($final_bulletin) {
            $moyenne_unite_s1  =  Models\Evaluation::getMoyennesUnite($inscription, $niveau_unite, 1);
        }
        // final_bulletin moyene s1
        if ($final_bulletin) {
            $unite_cf = $unite->getCoefficient($niveau, $type_coefficient);
            $somme_cf_s1 += $unite_cf;

            $unite_note  = $unite_cf * Tools::noteNumber($moyenne_unite_s1['MoyenneUnite']);
            $moyenne_s1 += $unite_note;
        }

        // moyene selected semestre
        $unite_cf = $unite->getCoefficient($niveau, $type_coefficient);

        $somme_cf += $unite_cf;

        $unite_note  = $unite_cf *  Tools::noteNumber($moyenne_unite['MoyenneUnite']);
        $moyenne += $unite_note;


    ?>

        <?php foreach ($matieres as $key => $matiere) { ?>
            <tr style="padding: 5px;">


                <?php if (count($matieres) == 1) { ?>
                    <td align="center" colspan="2" style="font-size: 21px;">
                        <b> <?php echo $unite->get('Label') ?> </b>
                    </td>
                    <td align="center" colspan="2" style="font-size: 18px;">
                        <?php echo Tools::noteNumber($moyenne_unite['MoyenneUnite']); ?>
                    </td>
                <?php  } else { ?>
                    <?php if ($key == 0) { ?>
                        <td align="center" <?php echo $show_appreciation ? '100px' : '240' ?> style="font-size: 21px;" rowspan="<?php echo count($matieres) ?>">
                            <b> <?php echo $unite->get('Label') ?></b>
                        </td>
                    <?php } ?>

                    <td align="center" <?php echo $show_appreciation ? '200px' : '240' ?> style="font-size: 18px;">
                        <?php echo $matiere->get('Label') ?>
                    </td>

                    <td align="center" style="font-size: 18px;">
                        <?php
                        echo Tools::noteNumber(isset($moyenne_unite['NotesMatieres'][$matiere->ID]['moyenne_matiere']) ? $moyenne_unite['NotesMatieres'][$matiere->ID]['moyenne_matiere'] : 0);
                        ?>
                    </td>

                    <?php if ($key == 0) { ?>
                        <td align="center" rowspan="<?php echo count($matieres) ?>" style="font-size: 18px;">
                            <?php for ($i = 0; $i < (count($matieres) / 2); $i++) { ?>
                                &nbsp;<br>
                            <?php  } ?>
                            <?php
                            echo Tools::noteNumber($moyenne_unite['MoyenneUnite']);
                            ?>
                        </td>
                    <?php } ?>

                <?php } ?>
                <?php if ($show_appreciation) { ?>
                    <td align="center">
                        <?php
                        $appreciation =  Models\EvaluationsRelevesAppreciations::hasAppreciation($inscription->ID, $unite->ID, $classe->ID, 1, $semestre);
                        if ($appreciation) {
                            echo $appreciation->Appreciation;
                        }
                        ?>
                    </td>
                <?php } ?>
            </tr>
        <?php  } ?>
    <?php  } ?>
</table>
<br>
<br>

<table border="1">
    <tr>
        <td rowspan="3" align="center" style="font-weight:bold;font-size: 21px;">
            <br>
            Administration
        </td>
        <td rowspan="3" style="font-weight:bold;font-size: 21px;">
            <?php if ($final_bulletin) { ?>
                Moyenne S1
                <br>
                Moyenne S2
                <br>
                Moyenne générale
            <?php } else { ?>
                Moyenne générale
            <?php }  ?>
        </td>

        <td rowspan="3" style="font-weight:bold;font-size: 21px;">
            <?php if ($final_bulletin) { ?>
                <?php echo Tools::noteNumber($moyenne_s1 = $moyenne_s1 / $somme_cf_s1)  ?>

                <br>
                <?php echo Tools::noteNumber($moyenne_s2 = $moyenne / $somme_cf)  ?>

                <br>
                <?php echo Tools::noteNumber(($moyenne_s1 + $moyenne_s2) / 2)  ?>

            <?php } else { ?>
                <?php echo Tools::noteNumber($moyenne / $somme_cf)  ?>
            <?php }  ?>
        </td>
        <td align="center" style="font-weight:bold;height:90px;font-size: 18px;">
            <br>
            Nombre d'absences(jours)
            <br>
            <?php echo  $abs_dip->absences_count_days; ?>
        </td>
    </tr>
    <tr>
        <td align="center" style="font-weight:bold;height:90px;font-size: 18px;">
            <br>
            Mérite
        </td>
    </tr>
</table>