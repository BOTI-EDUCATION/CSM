<style>

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
            <?php for ($i = 0; $i <= 2; $i++) { ?>&nbsp; <br> <?php } ?>
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


<table cellpadding="1" class="doc-title" border="1">
    <tr>
        <td class="td-right" rowspan="2" align="center" style="font-size: 21px;">
            <?php if ($semestre == 1) { ?>
                <span style="font-weight: bold;"> نتائج <br> الدورة الاولى </span>
            <?php } else { ?>
                <span style="font-weight: bold;"> نتائج <br />الدورة الثانية</span>
            <?php } ?>
        </td>
        <td style="font-size: 14px;" align="right" style="font-size: 21px;">
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
</table>

<br>
<br>

<table cellpadding="1" class="doc-title" border="1" style="width:100%;padding: 0px !important">
    <tr>
        <td align="center" width="307px" style="font-size: 21px;"> <b> الملاحظات </b></td>
        <td align="center" width="100px" colspan="2" style="font-size: 21px;"><b> معدل المواد </b></td>
        <td align="center" width="314px" colspan="2" style="font-size: 21px;"><b>المواد</b></td>
    </tr>

    <?php
    $moyenne = 0;
    $somme_cf = 0;
    $moyenne_s1 = 0;
    $somme_cf_s1 = 0;
    foreach ($niveaux_unites as $niveau_unite) {
        $unite = $niveau_unite->get('Unite');
        $matieres = $unite->getProgramtionMatieres($niveau->get('ID'));
        $moyenne_unite  =  Models\Evaluation::getMoyennesUnite($inscription, $niveau_unite, $semestre);
        if ($final_bulletin) {
            $moyenne_unite_s1  =  Models\Evaluation::getMoyennesUnite($inscription, $niveau_unite, 1);
        }

    ?>


        <?php foreach ($matieres as $key => $matiere) { ?>
            <tr style="padding: 5px;">
                <?php
                // final_bulletin moyene s1
                if ($key == 0 && $final_bulletin) {
                    $unite_cf = $unite->getCoefficient($niveau, $type_coefficient);
                    $somme_cf_s1 += $unite_cf;

                    $unite_note  = $unite_cf * Tools::noteNumber($moyenne_unite_s1['MoyenneUnite']);
                    $moyenne_s1 += $unite_note;
                }
                ?>

                <td align="center">
                    ----
                </td>

                <?php if (count($matieres) == 1) { ?>

                    <td align="center" colspan="2" style="font-size: 18px;">
                        <?php
                        $unite_cf = $unite->getCoefficient($niveau, $type_coefficient);
                        $somme_cf += $unite_cf;

                        $unite_note  = $unite_cf *  Tools::noteNumber($moyenne_unite['MoyenneUnite']);
                        $moyenne += $unite_note;

                        echo Tools::noteNumber($moyenne_unite['MoyenneUnite']);
                        ?>
                    </td>

                    <td align="center" colspan="2" style="font-size: 21px;">
                        <b> <?php echo $unite->get('LabelAr') ?: $unite->get('Label')  ?> </b>
                    </td>


                <?php  } else { ?>

                    <?php if ($key == 0) { ?>

                        <td align="center" rowspan="<?php echo count($matieres) ?>" style="font-size: 18px;">
                            <?php for ($i = 0; $i < (count($matieres) / 2); $i++) { ?>
                                &nbsp;<br>
                            <?php  } ?>
                            <?php
                            $unite_cf = $unite->getCoefficient($niveau, $type_coefficient);
                            $somme_cf += $unite_cf;
                            
                            $unite_note  = $unite_cf *  Tools::noteNumber($moyenne_unite['MoyenneUnite']);
                            $moyenne += $unite_note;

                            echo Tools::noteNumber($moyenne_unite['MoyenneUnite']);
                            ?>
                        </td>

                    <?php } ?>

                    <td align="center" style="font-size: 18px;">
                        <?php
                        echo Tools::noteNumber($moyenne_unite['NotesMatieres'][$matiere->ID]['moyenne_matiere']);
                        ?>
                    </td>

                    <td align="center" style="font-size: 18px;">
                        <?php echo $matiere->get('LabelAr') ?: $matiere->get('Label')  ?>
                    </td>

                    <?php if ($key == 0) { ?>
                        <td align="center" style="font-size: 21px;" rowspan="<?php echo count($matieres) ?>">
                            <b> <?php echo $unite->get('LabelAr') ?: $unite->get('Label') ?></b>
                        </td>
                    <?php } ?>

                <?php } ?>

            </tr>
        <?php  } ?>


    <?php  } ?>
</table>
<br>
<br>

<table border="1">
    <tr>

        <td align="center" style="font-weight:bold;height:90px;font-size: 18px;">
            عدد الغيابات
        </td>
        <td rowspan="3" style="font-weight:bold;font-size: 21px;" align="right">
            <?php if ($final_bulletin) { ?>
                <?php echo Tools::numberFormat($moyenne_s1 = $moyenne_s1 / $somme_cf_s1)  ?>

                <br>
                <?php echo Tools::numberFormat($moyenne_s2 = $moyenne / $somme_cf)  ?>

                <br>
                <?php echo Tools::numberFormat(($moyenne_s1 + $moyenne_s2) / 2)  ?>

            <?php } else { ?>
                <?php echo Tools::numberFormat($moyenne / count($niveaux_unites))  ?>
            <?php }  ?>
        </td>
        <td rowspan="3" style="font-weight:bold;font-size: 21px;" align="right">
            <?php if ($final_bulletin) { ?>
                معدل الدورة الاولى
                <br>
                معدل الدورة الثانية
                <br>
                معدل عام
            <?php } else { ?>
                معدل عام
            <?php }  ?>
        </td>
        <td rowspan="3" align="center" style="font-weight:bold;font-size: 21px;"> الإدارة </td>
    </tr>
    <tr>
        <td align="center" style="font-weight:bold;height:90px;font-size: 18px;">
            الاستحقاق
        </td>
    </tr>
</table>