<style>
    .notes tr td {
        height: 22px;
    }
</style>

<table border="1" cellpadding="1">
    <tr>
        <td align="center" valign="middle" style="padding: 3px;">
            <span style="font-size: 20px;padding: 0px;">
                <?php echo $compte->get('Label') ?>
            </span>
            <?php if (isAllowed('print_bulletin_show_autorisation')) { ?>
                <span style="font-size: 15px;font-weight:100">Autorisation du ministère de l'éducation nationale n° <span class="bold"><?php echo $compte->get('AutorisationNum') ?></span> du <span class="bold"><?php echo Tools::dateFormat($compte->get('AutorisationDate'), '%d/%m/%Y')  ?></span></span>
            <?php  } ?>
        </td>

        <td align="center" valign="middle">
            <?php $logo = (Config::get('logo') ? URL::base('assets/images/schools/' . Config::get('logo')) : URL::base('assets/images/logo.103983.png')); ?>
            <img src="<?php echo $logo ?>" style="width:70px;" />
        </td>
        <td align="center" valign="middle">
            <span style="font-size: 20px;padding: 0px;">
                <?php echo $compte->get('LabelAr') ?: $compte->get('Label') ?>
            </span>
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
        <td class="td-left" style="font-size:18px;">
            <span style="font-weight: bold;">Nom et Prénom : </span>
            <span style="font-weight: 100;" align="center"><?php echo strtoupper($inscription->get('Eleve')->get('User')->get('Nom')) . ' ' . ucfirst($inscription->get('Eleve')->get('User')->get('Prenom')) ?></span>
        </td>
        <td class="td-right" rowspan="3" align="center" style="font-size:18px">
            <?php if ($semestre == 1) { ?>
                <?php for ($i = 1; $i <= 1; $i++) { ?>&nbsp; <br><?php } ?>
            <span style="font-weight: bold;">Relevé des notes</span>
        <?php } else { ?>
            <?php for ($i = 1; $i <= 1; $i++) { ?>&nbsp; <br><?php } ?>
        <span style="font-weight: bold;">Relevé des notes <br /> MI-SEMESTRE `2`</span>
    <?php } ?>
        </td>
    </tr>
    <tr>
        <td class="td-left" style="font-size: 18px;">
            <span style="font-weight: bold;"> Classe : </span>
            <span style="font-weight: 100;font-size:18px" align="center"><?php echo $inscription->get('Classe')->get('Label')   ?></span>
        </td>
    </tr>
    <tr>
        <td class="td-left" style="font-size: 18px;">
            <span style="font-weight: bold;">Année scolaire : </span>
            <span style="font-weight: 100;font-size:18px;" align="center"><?php echo $inscription->get('Promotion')->get('Label') ?></span>
        </td>
    </tr>
</table>
<br>
<br>
<table class="notes" border="1" cellpadding="0">
    <tr>
        <td align="center" colspan="2" style="font-size: 18px;padding:3px"><b>Matière</b></td>
        <?php foreach ($selected_type_evaluations as $key_evaluation => $evaluation) {
           foreach ($evaluation->range as $i) { 
        ?>
                <td align="center" height="10px" style="font-size: 18px;">
                    <b>
                        <?php echo $evaluation->label ?>
                        <?php if ($evaluation->count != 1) { ?>
                            <?php echo ($i + 1) ?>
                        <?php  } ?>
                    </b>
                </td>
        <?php }
        } ?>
        <?php if ($show_appreciation) { ?>
            <td align="center" width="auto" style="font-size: 18px;"> <b> Appréciations </b></td>
        <?php  } ?>
    </tr>
    <?php
    foreach ($niveaux_unites as $niveau_unite) {
        $unite = $niveau_unite->get('Unite');
        $matieres = $unite->getProgramtionMatieres($niveau->get('ID'));
        $evatuations = $niveau_unite->getProgramationEvaluationsControles($semestre);

    ?>

        <?php foreach ($matieres as $key => $matiere) { ?>
            <tr>
                <?php if (count($matieres) == 1) { ?>
                    <td align="center" colspan="2" style="font-size: 18px;">
                        <?php for ($i = 0; $i < count($matieres) / 2; $i++) {
                            echo "<br>";
                        }  ?>
                        <b> <?php echo $unite->get('Label') ?> </b>
                    </td>
                <?php  } else { ?>
                    <?php if ($key == 0) { ?>
                        <td align="center" style="font-size: 18px;" rowspan="<?php echo count($matieres) ?>">
                            <?php for ($i = 0; $i < count($matieres) / 2; $i++) {
                                echo "<br>";
                            }  ?>
                            <b> <?php echo $unite->get('Label') ?></b>
                        </td>
                    <?php } ?>
                    <td align="center" style="font-size: 18px;">
                        <b> <?php echo $matiere->get('Label') ?></b>
                    </td>
                <?php } ?>
                <?php foreach ($selected_type_evaluations as $key_evaluation => $evaluation) {
                    foreach ($evaluation->range as $i) {
                        $examen = isset($examens[$matiere->get('ID')][$key_evaluation][$i]) ? $examens[$matiere->get('ID')][$key_evaluation][$i] : null;
                        $note = $examen ? Models\Note::hasNote($examen->getPK(true), $inscription->getPK(true)) : null;
                        $appréciations = $note ? $note->get('Appreciation') : '';
                ?>
                        <td align="center" style="font-size: 18px;">
                            <?php echo  Tools::numberFormat($note ? $note->get('Valeur') : 0) ?>
                        </td>
                <?php }
                }  ?>
                <?php if ($key == 0 && $show_appreciation) { ?>
                    <td align="center" rowspan="<?php echo count($matieres) ?>">
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



<table>
    <tr style="width:100%;">
        <td style="width:100%;display: inline-block;text-align:right">
            <br />
            <h3 style="font-size: 18px;padding: 0px;">
                <?php for ($i = 0; $i <= 10; $i++) { ?>&nbsp;<?php } ?> La Direction
            </h3>
        </td>
    </tr>
</table>