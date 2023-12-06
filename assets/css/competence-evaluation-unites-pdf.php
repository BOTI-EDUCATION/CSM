<style>
    .page-break {
        page-break-after: always;
    }
</style>

<table cellpadding="-9" width="730px">
    <tbody>

        <tr>
            <td>
                <?php $logo = URL::base('assets/images/schools/fleming-header.png'); ?>
                <img src="<?php echo $logo ?>" style="width: 800px; height: 120px;" />
            </td>
        </tr>
    </tbody>
</table>


<table>
    <tr>
        <td align="center">
            <span style="font-size: 25px;">Évaluation des compétences : <span><b>semestre <?php echo $semestre == 1 ? '1' : '2'; ?> </b></span> </span>
        </td>
    </tr>
</table>
<br><br>
<table>
    <tr style="font-size: 16px; text-align:center">
        <td style="width:18%">
            <span><b>Nom & Prénom :</b></span>
        </td>
        <td style="width:26%">
            <span> <?php echo strtoupper($inscription->get('Eleve')->get('User')->get('Nom')) . ' ' . ucfirst($inscription->get('Eleve')->get('User')->get('Prenom')) ?></span>
        </td>
        <td style="width:12%">
            <span><b>Classe :</b></span>
        </td>
        <td style="width:12%">
            <span><?php echo $inscription->get('Classe')->get('Label') ?></span>
        </td>
        <td style="width:17%">
            <span><b>Année scolaire :</b></span>
        </td>
        <td style="width:13%">
            <span> <?php echo $inscription->get('Promotion')->get('Label') ?></span>
        </td>
    </tr>
</table>

<br><br>

<table style="width: 100%; font-size:14px; text-align:center;" cellpadding="0" border="1">
    <?php foreach ($unitesComp as $key => $competences) {
        $unite =  Models\Unite::where(array('ID' => $key))->first();
        // $one = $competences[0]->get('ID');
    ?>
        <tr style="background-color: #dadada; font-size:18px;border:none;">

            <td colspan="2" style="background-color: <?php echo $unite->Color ?>;">
                <b><?php echo $unite->get('Label') ?></b>
            </td>

        </tr>

        <?php foreach ($selectedEvaluations as $key => $evaluation) {

            if (!isset($competences[$evaluation->ID])) continue;
        ?>
            <tr style="background-color: #dadada; font-size:16px;">
                <td width="60%" style="text-align:center">
                    <b><?php echo $evaluation->get('Label')  ?></b>
                </td>
                <td width="40%" style="text-align:center">
                    Niveau de maîtrise
                </td>
            </tr>
            <?php foreach ($competences[$evaluation->ID] as $ke => $competence) {  ?>
                <?php $maitrise = Models\CompetenceEvualionNotes::getCode($inscription, $competence->ID, $evaluation->get('ID')); ?>

                <tr>
                    <?php if ($unite->get('Label') == 'Arabe' || $unite->get('Label') == 'Education islamique') { ?>
                        <td style="text-align:right;"> <?php echo $competence->Titre  ?> </td>
                    <?php } else { ?>

                        <td style="text-align:left">
                            <?php echo $competence->Titre  ?>

                        </td>
                    <?php } ?>
                    <?php if ($maitrise) { ?>
                        <td style="text-align:left;color:<?php echo $maitrise['color']  ?>">
                            <?php echo $maitrise['niveau'] ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        <?php } ?>
    <?php } ?>
</table>


<br><br>
<table cellpadding="1">
    <tr>
        <td width="400px" style="border: 1px solid black;">
            <table cellpadding="2">
                <tr style="border-top:1px solid black;">
                    <td colspan="2" width="400px" align="center" class="cell-border" style="font-size:16px;">
                        Mentions</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr style="border-top:1px solid black;">
                    <td width="100px">Encouragements</td>
                    <td width="95px">
                        <table>
                            <tr>
                                <td class="cell-border" width="30px" style="border: 1px solid black;"></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                    <td width="100px">Tableau d'honneur</td>
                    <td width="95px">
                        <table>
                            <tr>
                                <td class="cell-border" width="30px" style="border: 1px solid black;"></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="100px">Félicitations</td>
                    <td width="95px">
                        <table>
                            <tr>
                                <td class="cell-border" width="30px" style="border: 1px solid black;"></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                    <td width="100px"></td>
                    <td width="95px">
                        <table>
                            <tr>
                                <td class="cell-border" width="30px"></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>

                </tr>

            </table>

        </td>

    </tr>

</table>
<br><br>


<table>
    <tr>
        <td width="85%" align="right">
            <table>
                <tr>
                    <td style="font-size:21px; color:black; position: absolute;">Visa direction</td>
                </tr>
            </table>
        </td>
        <td width="15%"></td>
    </tr>
</table>