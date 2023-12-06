<?php

$proms = ['2022/2023', '2023/2024'];
$lingesShowed = [];
$amounts = [];
$service_amounts = [];
foreach ($lignes as $ligne) {
	$lingesShowed[$ligne->Inscription->ID][$ligne->EncaissementRubrique->ID][$ligne->Mois] = $ligne->Montant;
}



// dd($lingesShowed);
?>
<table>
	<tr>
		<td width="30%" align="left"> <img src="<?php echo URL::absolute($etablissement->getImage()) ?>" style="width:80px" alt="">
		</td>
		<td width="40%" align="center">
			<br>
			<?php if ($encaissement->Canceled) { ?>
				<span style="text-align:center;margin:0;"> <span class="bold"> Reçu d'annulation </span> </span>
				<br />
			<?php } ?>
			<p style="font-size:10px"><b>Reçu N°</b> <?php echo $encaissement->get('NumRecu') ?> <b>إيصال رقم</b> </p>
		</td>
		<td width="30%" align="right">
			<img src="<?php echo URL::absolute($etablissement->getImage()) ?>" style="width:80px" alt="">
		</td>
	</tr>
</table>

<!-- <div style="line-height:10px"></div> -->

<table style="font-size:10px" cellpadding="3" border="2">

	<tr>
		<td align="center" style="line-height:19.5px" colspan="2">
			السنة الدراسية
			Année scolaire <br> <?php echo $encaissement->get('Promotion')->get('Label') ?>
		</td>

		<td colspan="3" align="center">
			<?php echo $etablissement->get('Ville') ?>
			الإدارة
			Administartion
			<br>
			<?php echo Tools::dateFormat($encaissement->get('DatePaiement'), '%d/%m/%Y') ?>
		</td>
	</tr>

	<tr style="background-color:#ddd">
		<td width="40%"> Élève</td>
		<td width="25%"> Frais</td>
		<td width="10%"> Mois</td>
		<td width="15%"> Montant</td>
		<td width="10%"> Reste</td>
	</tr>

	<?php

	$k = 0;
	foreach ($lingesShowed as $ins_id => $lignes) {
		$eleve = Models\Inscription::where(['ID' => $ins_id])->first();
		$first = true;
	?>

		<!-- <?php if (in_array($encaissement->get('Promotion')->get('Label'), $proms)) { ?>
            <span style="font-size:9px">(<?php echo $eleve->Classe ? $eleve->Classe->Label : $eleve->Niveau->Label ?>)</span>
        <?php  } ?> -->
		<?php
		foreach ($lignes as $frais => $details) {
			$service = Models\FIN\EncaissementRubrique::where(['ID' => $frais])->first();
			if (count(array_keys($details)) > 1) {
				$amount = 0;
				$service_amount = 0;
				foreach (array_keys($details) as $m) {
					$amount += Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($eleve, $m == 0 ? null : $m, $service);
					$service_amount += Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($eleve, $m == 0 ? null : $m, $service);
				}
			} else {
				$month = implode(',', array_keys($details));
				$amount = Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($eleve, $month == 0 ? null : $month, $service);
				$service_amount = Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($eleve, $month == 0 ? null : $month, $service);
			}

		?>
			<tr>
				<?php if ($first) {
					$first = false; ?>
					<td width="40%" rowspan="<?php echo  count($lignes) ?>"><?php echo $eleve->Eleve->User->getNomComplet() . ' - ' . $eleve->Eleve->Matricule; ?>
						<span style="font-size:7px">(<?php echo $eleve->Niveau->Label ?>)</span>
					</td>
				<?php } ?>
				<td width="25%"> <?php echo  $service->get('Label') ?> </td>
				<td width="10%"> <?php echo  implode(",", array_keys($details)) ?> </td>
				<td width="15%"> <?php echo Tools::numberFormat(array_sum($details)) ?> </td>

				<td width="10%">
					<?php echo Tools::numberFormat($service_amount - $amount); ?>
				</td>


			</tr>
		<?php    } ?>

	<?php  } ?>

	<tr>
		<td>Total</td>
		<td colspan="3" align="cenetr"><b> <?php echo \Tools::numberFormat($encaissement->get('Montant'), 2) ?></b></td>
		<td align="right"> المجموع</td>
	</tr>

	<tr>
		<td>Mode de paiement </td>
		<td colspan="3" align="center">
			<?php echo $encaissement->getPaiementModeLabel(); ?>
			<br>
			<?php $paiementDetail = $encaissement->getDetailmode(); ?>
			<span>
				<?php if ($paiementDetail) { ?>
					<?php if ($encaissement->PaiementMode == 'cheque') { ?>
						<?php if ($paiementDetail->get('NumCheque')) { ?>
							N° <?php echo $paiementDetail->get('NumCheque') ?> رقم
						<?php } ?>

						<?php if ($paiementDetail->get('Banque')) { ?>
							/
							<?php echo $paiementDetail->get('Banque')->get('Label') ?> بنك
						<?php } ?>

					<?php } ?>

					<?php if ($encaissement->PaiementMode == 'cheque' && $paiementDetail->get('Tireur')) { ?>
						<br>
						Tireur <?php echo $paiementDetail->get('Tireur') ?>
					<?php } ?>
				<?php } ?>
			</span>
		</td>
		<td align="right">الأداء</td>
	</tr>

	<?php

	$enc_par = '';
	$pDetail = '';
	try {
		$enc_par = $encaissement->EncaissementPar;
		$ptDetail = $paiementDetail->get('Tireur');
	} catch (\Throwable $th) {
		$enc_par = '';
		$pDetail = '';
		//throw $th;
	}
	if ($encaissement->EncaissementPar || $pDetail) { ?>
		<tr>
			<td colspan="5" align="center">
				<?php if ($encaissement->EncaissementPar) { ?>
					Payé par <?php echo $encaissement->EncaissementPar ?>
				<?php } ?>

				<?php if ($paiementDetail && $encaissement->PaiementMode == 'cheque' && $pDetail) { ?>
					Tireur <?php echo $pDetail ?>
				<?php } ?>
			</td>
		</tr>
	<?php } ?>
</table>
<?php if (false) { ?>
	<table style="font-size:10px" cellpadding="3" border="2">

		<tr>
			<td align="center" style="line-height:19.5px" colspan="2">
				السنة الدراسية
				Année scolaire <br> <?php echo $encaissement->get('Promotion')->get('Label') ?>
			</td>

			<td colspan="3" align="center">
				<?php echo $etablissement->get('Ville') ?>
				الإدارة
				Administartion
				<br>
				<?php echo Tools::dateFormat($encaissement->get('DatePaiement'), '%d/%m/%Y') ?>
			</td>
		</tr>

		<tr style="background-color:#ddd">
			<td width="40%"> Élève</td>
			<td width="25%"> Frais</td>
			<td width="10%"> Mois</td>
			<td width="15%"> Montant</td>
			<td width="10%"> Reste</td>
		</tr>
		<!-- <?php if (in_array($encaissement->get('Promotion')->get('Label'), $proms)) { ?>
                            <span style="font-size:9px">(<?php echo $eleve->Classe ? $eleve->Classe->Label : $eleve->Niveau->Label ?>)</span>
                        <?php  } ?> -->
		<?php
		foreach ($lingesShowed as $ins_id => $lignes) {
			$eleve = Models\Inscription::where(['ID' => $ins_id])->first();
			$first = true;
		?>

			<?php foreach ($lignes as $frais => $details) {
				$service = Models\FIN\EncaissementRubrique::where(['ID' => $frais])->first();
				if (count(array_keys($details)) > 1) {
					$amount = 0;
					$service_amount = 0;
					foreach (array_keys($details) as $m) {
						$amount += Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($eleve, $m == 0 ? null : $m, $service);
						$service_amount += Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($eleve, $m == 0 ? null : $m, $service);
					}
				} else {
					$month = implode(',', array_keys($details));
					$amount = Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($eleve, $month == 0 ? null : $month, $service);
					$service_amount = Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($eleve, $month == 0 ? null : $month, $service);
				}


			?>
				<tr>
					<?php if ($first) {
						$first = false; ?>
						<td width="40%" rowspan="<?php echo  count($lignes) ?>"><?php echo $eleve->Eleve->User->getNomComplet() . ' - ' . $eleve->Eleve->Matricule; ?>
							<span style="font-size:8px">(<?php echo $eleve->Niveau->Label ?>)</span>
						</td>
					<?php } ?>
					<td width="25%"> <?php echo  $service->get('Label') ?> </td>
					<td width="10%"> <?php echo  implode(",", array_keys($details)) ?> </td>
					<td width="15%"> <?php echo Tools::numberFormat(array_sum($details)) ?> </td>
					<td width="10%">
					<td width="10%">
						<?php echo Tools::numberFormat($service_amount - $amount); ?>
					</td>
					</td>
				</tr>
			<?php    } ?>

		<?php  } ?>

		<tr>
			<td>Total</td>
			<td colspan="3" align="cenetr"><b> <?php echo \Tools::numberFormat($encaissement->get('Montant'), 2) ?></b></td>
			<td align="right"> المجموع</td>
		</tr>

		<tr>
			<td>Mode de paiement </td>
			<td colspan="3" align="center">


				<?php echo $encaissement->getPaiementModeLabel(); ?>
				<br>
				<?php $paiementDetail = $encaissement->getDetailmode(); ?>
				<span>
					<?php if ($paiementDetail) { ?>
						<?php if ($encaissement->PaiementMode == 'cheque') { ?>
							<?php if ($paiementDetail->get('NumCheque')) { ?>
								N° <?php echo $paiementDetail->get('NumCheque') ?> رقم
							<?php } ?>

							<?php if ($paiementDetail->get('Banque')) { ?>
								/
								<?php echo $paiementDetail->get('Banque')->get('Label') ?> بنك
							<?php } ?>

						<?php } ?>

						<?php if ($encaissement->PaiementMode == 'cheque' && $paiementDetail->get('Tireur')) { ?>
							<br>
							Tireur <?php echo $paiementDetail->get('Tireur') ?>
						<?php } ?>
					<?php } ?>
				</span>
			</td>
			<td align="right">الأداء</td>
		</tr>
		<?php
		$enc_par = '';
		$pDetail = '';
		try {
			$enc_par = $encaissement->EncaissementPar;
			$ptDetail = $paiementDetail->get('Tireur');
		} catch (\Throwable $th) {
			$enc_par = '';
			$pDetail = '';
			//throw $th;
		}
		// if ($encaissement->EncaissementPar || $paiementDetail->get('Tireur')) {
		if ($encaissement->EncaissementPar || $pDetail) {
		?>
			<tr>
				<td colspan="7" align="center">
					<?php if ($encaissement->EncaissementPar) { ?>
						Payé par <?php echo $encaissement->EncaissementPar ?>
					<?php } ?>

					<?php if ($paiementDetail && $encaissement->PaiementMode == 'cheque' && $pDetail) { ?>
						Tireur <?php echo $pDetail ?>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
	</table>
<?php } ?>

<p style="font-size:10px" align="center">
	<?php echo $etablissement->get('Adresse') ?>, <?php echo $etablissement->get('Ville') ?> -Tel:<?php echo $etablissement->get('Tel') ?>
</p>

<div></div>
<div style="border-top:2x dashed black;"></div>
<div></div>


<table>
	<tr>
		<td width="30%" align="left"> <img src="<?php echo URL::absolute($etablissement->getImage()) ?>" style="width:80px" alt="">
		</td>
		<td width="40%" align="center">
			<br>
			<?php if ($encaissement->Canceled) { ?>
				<span style="text-align:center;margin:0;"> <span class="bold"> Reçu d'annulation </span> </span>
				<br />
			<?php } ?>
			<p style="font-size:10px"><b>Reçu N°</b> <?php echo $encaissement->get('NumRecu') ?> <b>إيصال رقم</b> </p>
		</td>
		<td width="30%" align="right">
			<img src="<?php echo URL::absolute($etablissement->getImage()) ?>" style="width:80px" alt="">
		</td>
	</tr>
</table>

<!-- <div style="line-height:10px"></div> -->


<table style="font-size:10px" cellpadding="3" border="2">

	<tr>
		<td align="center" style="line-height:19.5px" colspan="2">
			السنة الدراسية
			Année scolaire <br> <?php echo $encaissement->get('Promotion')->get('Label') ?>
		</td>

		<td colspan="3" align="center">
			<?php echo $etablissement->get('Ville') ?>
			الإدارة
			Administartion
			<br>
			<?php echo Tools::dateFormat($encaissement->get('DatePaiement'), '%d/%m/%Y') ?>
		</td>
	</tr>

	<tr style="background-color:#ddd">
		<td width="40%"> Élève</td>
		<td width="25%"> Frais</td>
		<td width="10%"> Mois</td>
		<td width="15%"> Montant</td>
		<td width="10%"> Reste</td>
	</tr>

	<?php

	$k = 0;
	foreach ($lingesShowed as $ins_id => $lignes) {
		$eleve = Models\Inscription::where(['ID' => $ins_id])->first();
		$first = true;
	?>

		<!-- <?php if (in_array($encaissement->get('Promotion')->get('Label'), $proms)) { ?>
            <span style="font-size:9px">(<?php echo $eleve->Classe ? $eleve->Classe->Label : $eleve->Niveau->Label ?>)</span>
        <?php  } ?> -->
		<?php
		foreach ($lignes as $frais => $details) {
			$service = Models\FIN\EncaissementRubrique::where(['ID' => $frais])->first();
			if (count(array_keys($details)) > 1) {
				$amount = 0;
				$service_amount = 0;
				foreach (array_keys($details) as $m) {
					$amount += Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($eleve, $m == 0 ? null : $m, $service);
					$service_amount += Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($eleve, $m == 0 ? null : $m, $service);
				}
			} else {
				$month = implode(',', array_keys($details));
				$amount = Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($eleve, $month == 0 ? null : $month, $service);
				$service_amount = Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($eleve, $month == 0 ? null : $month, $service);
			}

		?>
			<tr>
				<?php if ($first) {
					$first = false; ?>
					<td width="40%" rowspan="<?php echo  count($lignes) ?>"><?php echo $eleve->Eleve->User->getNomComplet() . ' - ' . $eleve->Eleve->Matricule; ?>
						<span style="font-size:7px">(<?php echo $eleve->Niveau->Label ?>)</span>
					</td>
				<?php } ?>
				<td width="25%"> <?php echo  $service->get('Label') ?> </td>
				<td width="10%"> <?php echo  implode(",", array_keys($details)) ?> </td>
				<td width="15%"> <?php echo Tools::numberFormat(array_sum($details)) ?> </td>

				<td width="10%">
					<?php echo Tools::numberFormat($service_amount - $amount); ?>
				</td>


			</tr>
		<?php    } ?>

	<?php  } ?>

	<tr>
		<td>Total</td>
		<td colspan="3" align="cenetr"><b> <?php echo \Tools::numberFormat($encaissement->get('Montant'), 2) ?></b></td>
		<td align="right"> المجموع</td>
	</tr>

	<tr>
		<td>Mode de paiement </td>
		<td colspan="3" align="center">
			<?php echo $encaissement->getPaiementModeLabel(); ?>
			<br>
			<?php $paiementDetail = $encaissement->getDetailmode(); ?>
			<span>
				<?php if ($paiementDetail) { ?>
					<?php if ($encaissement->PaiementMode == 'cheque') { ?>
						<?php if ($paiementDetail->get('NumCheque')) { ?>
							N° <?php echo $paiementDetail->get('NumCheque') ?> رقم
						<?php } ?>

						<?php if ($paiementDetail->get('Banque')) { ?>
							/
							<?php echo $paiementDetail->get('Banque')->get('Label') ?> بنك
						<?php } ?>

					<?php } ?>

					<?php if ($encaissement->PaiementMode == 'cheque' && $paiementDetail->get('Tireur')) { ?>
						<br>
						Tireur <?php echo $paiementDetail->get('Tireur') ?>
					<?php } ?>
				<?php } ?>
			</span>
		</td>
		<td align="right">الأداء</td>
	</tr>
	<?php if ($encaissement->EncaissementPar || $pDetail) { ?>
		<tr>
			<td colspan="5" align="center">
				<?php if ($encaissement->EncaissementPar) { ?>
					Payé par <?php echo $encaissement->EncaissementPar ?>
				<?php } ?>

				<?php if ($paiementDetail && $encaissement->PaiementMode == 'cheque' && $pDetail) { ?>
					Tireur <?php echo $pDetail ?>
				<?php } ?>
			</td>
		</tr>
	<?php } ?>
</table>


<p style="font-size:10px" align="center">
	<?php echo $etablissement->get('Adresse') ?>, <?php echo $etablissement->get('Ville') ?> -Tel:<?php echo $etablissement->get('Tel') ?>
</p>