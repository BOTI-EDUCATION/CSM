<style>
    .td-right {
        direction: ltr;

    }

    .notes tr td {
        height: 22px;
    }
</style>


<table border="1">
    <tr>

        <td align="center" valign="middle">
            <?php for ($i = 1; $i <= 1; $i++) { ?>&nbsp; <br><?php } ?>
        <span style="font-size: 21px;padding: 0px;">
            <?php echo $compte->get('Label') ?>
        </span>
        <br>
        <br>
        <?php if (isAllowed('print_bulletin_show_autorisation')) { ?>
            <span style="font-size: 15px;font-weight:100">Autorisation du ministère de l'éducation nationale n° <span class="bold"><?php echo $compte->get('AutorisationNum') ?></span> du <span class="bold"><?php echo Tools::dateFormat($compte->get('AutorisationDate'), '%d/%m/%Y')  ?></span></span>
        <?php  } ?>
        </td>
        <td align="center">
            <?php for ($i = 1; $i <= 1; $i++) { ?>&nbsp; <br><?php } ?>
        <?php $logo = (Config::get('logo') ? URL::base('assets/images/schools/' . Config::get('logo')) : URL::base('assets/images/logo.103983.png')); ?>
        <img src="<?php echo $logo ?>" style="width:70px;" />
        <?php for ($i = 1; $i <= 1; $i++) { ?>&nbsp; <br><?php } ?>
        </td>
        <td align="center" valign="middle">
            <?php for ($i = 1; $i <= 1; $i++) { ?>&nbsp; <br><?php } ?>
        <span style="font-size: 21px;padding: 0px;">
            <?php echo $compte->get('LabelAr') ?: $compte->get('Label') ?>
        </span>
        <br>
        <br>
        <?php if (isAllowed('print_bulletin_show_autorisation')) { ?>
            <span style="font-size: 15px;font-weight:100">
                رقم ترخيص وزارة التربية الوطنية : <br />
                <span class="bold"><?php echo Tools::dateFormatAr($compte->get('AutorisationDate'), '%Y/%m/%d') ?></span> بتاريخ <span class="bold"><?php echo $compte->get('AutorisationNum') ?></span>
            </span>
        <?php } ?>
        </td>
    </tr>
</table>

<table class="doc-title" border="1">
    <tr>
        <td class="td-right" rowspan="3" align="center" style="font-size: 21px;">
            <?php if ($semestre == 1) { ?>
                <?php for ($i = 1; $i <= 1; $i++) { ?>&nbsp; <br><?php } ?>
            <span style="font-weight: bold;"> بيان النقط <br> </span>
        <?php } else { ?>
            <?php for ($i = 1; $i <= 1; $i++) { ?>&nbsp; <br><?php } ?>
        <span style="font-weight: bold;"> بيان النقط <br />الدورة الثانية</span>
    <?php } ?>
        </td>
        <td style="font-size: 21px;" align="right">
            <span style="font-weight: 100;"><?php echo strtoupper($inscription->get('Eleve')->get('NomAr')) . ' ' . ucfirst($inscription->get('Eleve')->get('PrenomAr')) ?></span>
            <span style="font-weight: bold;">الإسم العائلي والشخصي : </span>
        </td>
    </tr>
    <tr>
        <td class="td-right" style="font-size: 21px;" align="right">
            <span style="font-weight: 100;"><?php echo $inscription->get('Classe')->get('Label') ?> / <?php echo $inscription->get('Promotion')->get('Label') ?></span>
            <span style="font-weight: bold;">المستوى الدراسي : </span>
        </td>
    </tr>
    <tr>
        <td class="td-right" style="font-size: 21px;" align="right">
            <span style="font-weight: 100;"><?php echo $inscription->get('Promotion')->get('Label') ?></span>
            <span style="font-weight: bold;">السنة الدراسية :</span>
        </td>
    </tr>
</table>

<br>

<table class="notes" border="1" style="width:100%;padding: 0px !important">

    <tr>
        <td align="center" style="font-size: 21px;" width="200px"> <b> الملاحظات </b></td>

        <?php foreach ($selected_type_evaluations as $key_evaluation => $evaluation) {
            for ($i = $evaluation->count - 1; $i >= 0; $i--) {
        ?>
                <td class="no-padding" align="center" style="font-size: 21px;"> <b>
                        <?php echo $evaluation->labelAr ?: $evaluation->label ?>
                        <?php if ($evaluation->count != 1) { ?>
                            <?php echo ($i + 1) ?>
                        <?php  } ?>
                    </b> </td>
        <?php }
        }
        ?>

        <td align="center" colspan="2" style="font-size: 21px;" width="341px"><b>المواد</b></td>
    </tr>

    <?php
    foreach ($niveaux_unites as $niveau_unite) {
        $unite = $niveau_unite->get('Unite');
        $matieres = $unite->getProgramtionMatieres($niveau->get('ID'));
        $evatuations = $niveau_unite->getProgramationEvaluationsControles($semestre);

    ?>

        <?php foreach ($matieres as $key => $matiere) { ?>

            <tr>
                <?php
                $notes = array();
                foreach ($selected_type_evaluations as $key_evaluation => $evaluation) {
                    for ($i = $evaluation->count - 1; $i >= 0; $i--) {
                        $examen = isset($examens[$matiere->get('ID')][$key_evaluation][$i]) ? $examens[$matiere->get('ID')][$key_evaluation][$i] : null;
                        $note = $examen ? Models\Note::hasNote($examen->getPK(true), $inscription->getPK(true)) : null;
                        $notes[] = $note ? $note->get('Valeur') : 0;
                    }
                }
                ?>

                <?php if ($key == 0) { ?>
                    <td align="center" rowspan="<?php echo count($matieres) ?>">
                        <?php
                            $appreciation =  Models\EvaluationsRelevesAppreciations::hasAppreciation($inscription->ID, $unite->ID, $classe->ID, 1, $semestre);
                            if ($appreciation) {
                                echo $appreciation->Appreciation;
                            }
                        ?>
                    </td>
                <?php } ?>

                <?php foreach ($notes as $note) { ?>
                    <td align="center" style="font-size: 18px;">
                        <?php echo  Tools::numberFormat($note) ?>
                    </td>
                <?php } ?>

                <?php if (count($matieres) == 1) { ?>

                    <td align="center" colspan="2" style="font-size: 18px;">
                        <?php for ($i = 0; $i < count($matieres) / 2; $i++) {
                            echo "<br>";
                        }  ?>
                        <b> <?php echo html($unite->get('LabelAr') ?: $unite->get('Label')) ?> </b>
                    </td>

                <?php  } else { ?>

                    <td align="center" style="font-size: 18px;">
                        <?php echo html($matiere->get('LabelAr') ?: $matiere->get('Label')) ?>
                    </td>

                    <?php if ($key == 0) { ?>
                        <td align="center" style="font-size: 18px;" rowspan="<?php echo count($matieres) ?>">
                            <?php for ($i = 0; $i < count($matieres) / 2; $i++) {
                                echo "<br>";
                            }  ?>
                            <?php echo html($unite->get('LabelAr') ?$unite->get('LabelAr')): $unite->get('Label')) ?>
                        </td>
                    <?php } ?>


                <?php } ?>
            </tr>

        <?php  } ?>
    <?php  } ?>
</table>
<br>
<br>
<table>
    <tr style="width:100%;">
        <td style="width:100%;display: inline-block;text-align:right">
            <h3 style="font-size: 30px;padding: 0px;">
                <?php for ($i = 0; $i <= 10; $i++) { ?>&nbsp;<?php } ?> الإدارة
            </h3>
        </td>
    </tr>
</table>