<?php

$arr_enc = [];
foreach ($lignesShowed as $item) {
	foreach ($item['services'] as $key => $service) {
		$amount =  Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($encaissement->get('Inscription'), $item['mois_key'], $service['service']);
		$service_amount =  Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($encaissement->get('Inscription'), $item['mois_key'], $service['service']);

		$arr_enc[$service['service']->Label]['months'][] = $item['mois_key'];
		$arr_enc[$service['service']->Label]['montants'][] = $service['montant'];
		$arr_enc[$service['service']->Label]['restes'][] = $service_amount - $amount;
	}
}


?>

<table>
	<tr>
		<td width="10%"></td>
		<td width="80%" align="center">
			<img src="<?php echo URL::absolute($etablissement->getImage()) ?>" alt="" width="80px">
			<p>

				<?php if ($encaissement->Canceled) { ?>
					<span style="text-align:center;margin:0;"> <span class="bold"> Reçu d'annulation </span> </span>
					<br />
				<?php } ?>

				<b>Reçu N°</b> <?php echo $encaissement->get('NumRecu') ?> <b>إيصال رقم</b>

			</p>
		</td>
		<td width="10%"></td>
	</tr>
</table>
<div style="line-height:10px"></div>

<table>
	<tr>
		<td width="19%">
			<table border="2" align="center" cellpadding="5" style="font-size:13px;line-height:18.5px">
				<tr>
					<td align="center" style="line-height:19.5px">
						السنة الدراسية
						<br>
						Année scolaire <br>
						<?php echo $encaissement->get('Promotion') ? $encaissement->get('Promotion')->get('Label') : '' ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php for ($i = 0; $i < count($lignesShowed) / 2; $i++) {  ?>
							<br>
						<?php } ?>
						<?php echo $etablissement->get('Ville') ?>
						<br>
						<?php echo Tools::dateFormat($encaissement->get('DatePaiement'), '%d/%m/%Y') ?>
						<br>
						الإدارة
						<br>
						Administartion
						<br>
						<br>
						<?php foreach ($lignesShowed as $key => $value) { ?>
							<br>
						<?php   } ?>
					</td>
				</tr>
			</table>
		</td>
		<td width="1%"></td>
		<td width="80%">
			<table cellpadding="5" style="font-size:13px">

				<tr style="background-color: #ddd;" align="center">
					<td width="20%" style="border-right:1px solid black;border-top:2px solid black;border-left:2px solid black">
						القسم
						<br>
						classe
					</td>
					<td width="60%" style="border-right:1px solid black;border-top:2px solid black">
						التلميذ (ة)
						<br>
						Eléve
					</td>
					<td width="20%" style="border-right:2px solid black;border-top:2px solid black">
						رقم التسجيل
						<br>
						Matricule
					</td>
				</tr>


				<tr align="center">
					<td width="20%" style="border-right:1px solid black;border-left:2px solid black;">
						<?php if ($encaissement->get('Promotion')->Label == '2023/2024') { ?>
							<?php echo $encaissement->get('Inscription')->get('Niveau') ?  $encaissement->get('Inscription')->get('Niveau')->Label  : '' ?>
						<?php } else { ?>
							<?php echo $encaissement->get('Inscription')->get('Classe') ?  $encaissement->get('Inscription')->get('Classe')->Label  : '' ?>
						<?php } ?>
					</td>
					<td width="60%" style="border-right:1px solid black;"><?php echo $encaissement->get('Inscription')->get('Eleve')->get('User')->getNomComplet() ?></td>
					<td width="20%" style="border-right:2px solid black;"><?php echo $encaissement->get('Inscription')->get('Eleve')->Matricule ?></td>
				</tr>

				<tr>
					<td width="35%" style="border-bottom:1px solid black; border-right:1px solid black;border-top:2px solid black;border-left:2px solid black"> Frais </td>
					<td width="25%" style="border-bottom:1px solid black; border-right:1px solid black;border-top:2px solid black" align="center">
						Mois شهر-أشهر
					</td>
					<td width="20%" style="border-bottom:1px solid black; border-right:1px solid black;border-top:2px solid black" align="right">
						Montant مبلغ
					</td>
					<td width="20%" style="border-bottom:1px solid black; border-right:1px solid black;border-top:2px solid black;
						border-right:2px solid black" align="right">
						Reste الباقي
					</td>
				</tr>

				<?php
				$total = 0;
				$services_enc = [];
				$last_service = '';


				foreach ($arr_enc as $key => $value) {
					$mont_sum = 0;
					foreach ($value['montants'] as $montant_) {
						# code...
						$mont_sum += (float)$montant_;
						// echo '<pre>';
						// print_r($montant_);
						// echo '</pre>';
					} ?>
				

					<tr style="line-height:11px;">
						<td width="35%" style="border-left:2px solid black"> <?php echo $key ?></td>
						<td width="25%" style="border-left:1px solid black;border-right:1px solid black" align="center">
							<?php echo  implode(',', $value['months']) ?>
						</td>
						<td width="20%" style="border-right:1px solid black" align="right">
							<?php $total +=  $mont_sum;
							echo  Tools::numberFormat($mont_sum, 2)  ?>
						</td>
						<td width="20%" style="border-right:1px solid black;border-right:2px solid black" align="right">
							<?php echo array_sum($value['restes']) ?>
						</td>
					</tr>

				<?php } ?>

		


				<tr>
					<td style="border-bottom:1px solid black;border-top:1px solid black; border-left:2px solid black;border-right:1px solid black"> Total</td>
					<td style="border-bottom:1px solid black;border-top:1px solid black; border-right:1px solid black" colspan="2" align="center">
						<b><?php echo \Tools::numberFormat($encaissement->get('Montant'), 2) ?></b>
					</td>
					<td style="border-bottom:1px solid black; border-top:1px solid black; border-right:2px solid black" align="right">المجموع</td>
				</tr>

				<tr>
					<td style="border-bottom:2px solid black;border-left:2px solid black;border-right:1px solid black"> Mode de paiement</td>
					<td colspan="2" style="border-bottom:2px solid black;border-right:1px solid black" align="center">
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
					<td style="border-bottom:2px solid black;border-right:2px solid black" align="right">الأداء </td>
				</tr>
				<?php if ($paiementDetail) {

				?>

					<?php if ($encaissement->EncaissementPar) {
					?>
						<tr>
							<td colspan="3">
								<?php if ($encaissement->EncaissementPar) { ?>
									Payé par <?php echo $encaissement->EncaissementPar ?>
								<?php } ?>

							</td>
						</tr>
					<?php } ?>
				<?php } ?>
			</table>
		</td>
	</tr>

</table>

<p style="font-size:12px" align="center">
	<?php echo $etablissement->get('Adresse') ?>, <?php echo $etablissement->get('Ville') ?> -Tel:<?php echo $etablissement->get('Tel') ?>
</p>

<div></div>
<div style="border-top: 2px dashed black;"></div>
<div></div>

<table>
	<tr>
		<td width="10%"></td>
		<td width="80%" align="center">
			<img src="<?php echo URL::absolute($etablissement->getImage()) ?>" alt="" width="80px">
			<p>
				<?php if ($encaissement->Canceled) { ?>
					<span style="text-align:center;margin:0;"> <span class="bold"> Reçu d'annulation </span> </span>
					<br />
				<?php } ?>
				<b>Reçu N°</b> <?php echo $encaissement->get('NumRecu') ?> <b>إيصال رقم</b>
			</p>
		</td>
		<td width="10%"></td>
	</tr>
</table>

<div style="line-height:10px"></div>

<table>
	<tr>
		<td width="19%">
			<table border="2" align="center" cellpadding="5" style="font-size:13px;line-height:18.5px">
				<tr>
					<td align="center" style="line-height:19.5px">
						السنة الدراسية
						<br>
						Année scolaire <br> <?php echo  $encaissement->get('Promotion') ? $encaissement->get('Promotion')->get('Label') : '' ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php for ($i = 0; $i < count($lignesShowed) / 2; $i++) {  ?>
							<br>
						<?php } ?>
						<?php echo $etablissement->get('Ville') ?>
						<br>
						<?php echo Tools::dateFormat($encaissement->get('DatePaiement'), '%d/%m/%Y') ?>
						<br>
						الإدارة
						<br>
						Administartion
						<br>
						<br>
						<?php foreach ($lignesShowed as $key => $value) { ?>
							<br>
						<?php   } ?>
					</td>
				</tr>
			</table>
		</td>
		<td width="1%"></td>
		<td width="80%">
			<table cellpadding="5" style="font-size:13px">

				<tr style="background-color: #ddd;" align="center">
					<td width="20%" style="border-right:1px solid black;border-top:2px solid black;border-left:2px solid black">
						القسم
						<br>
						classe
					</td>
					<td width="60%" style="border-right:1px solid black;border-top:2px solid black">
						التلميذ (ة)
						<br>
						Eléve
					</td>
					<td width="20%" style="border-right:2px solid black;border-top:2px solid black">
						رقم التسجيل
						<br>
						Matricule
					</td>
				</tr>

				<tr align="center">
					<td width="20%" style="border-right:1px solid black;border-left:2px solid black;">
						<?php if ($encaissement->get('Promotion')->Label == '2023/2024') { ?>
							<?php if ($encaissement->get('Inscription')->get('Classe')) { ?>
								<?php echo $encaissement->get('Inscription')->get('Classe')->Label ?>
								<?php } else {
								if ($encaissement->get('Inscription')->get('Niveau')) {
								?>
									<?php echo $encaissement->get('Inscription')->get('Niveau')->Label ?>
							<?php }
							} ?>
						<?php } else { ?>
							<?php echo $encaissement->get('Inscription')->get('Classe') ?  $encaissement->get('Inscription')->get('Classe')->Label  : '' ?>
						<?php } ?>
					</td>
					<td width="60%" style="border-right:1px solid black;"><?php echo $encaissement->get('Inscription')->get('Eleve')->get('User')->getNomComplet() ?></td>
					<td width="20%" style="border-right:2px solid black;"><?php echo $encaissement->get('Inscription')->get('Eleve')->Matricule ?></td>
				</tr>

				<tr>
					<td width="30%" style="border-bottom:1px solid black; border-right:1px solid black;border-top:2px solid black;border-left:2px solid black"> Frais </td>
					<td width="30%" style="border-bottom:1px solid black; border-right:1px solid black;border-top:2px solid black" align="center">
						Mois شهر-أشهر
					</td>
					<td width="20%" style="border-bottom:1px solid black; border-right:1px solid black;border-top:2px solid black" align="right">
						Montant مبلغ
					</td>
					<td width="20%" style="border-bottom:1px solid black; border-right:1px solid black;border-top:2px solid black;
						border-right:2px solid black" align="right">
						Reste الباقي
					</td>
				</tr>

				<?php
				$total = 0;
				$services_enc = [];
				$last_service = '';


				foreach ($arr_enc as $key => $value) {
					$mont_sum = 0;
					foreach ($value['montants'] as $montant_) {
						# code...
						$mont_sum += (float)$montant_;
						// echo '<pre>';
						// print_r($montant_);
						// echo '</pre>';
					} ?>
				

					<tr style="line-height:11px;">
						<td width="35%" style="border-left:2px solid black"> <?php echo $key ?></td>
						<td width="25%" style="border-left:1px solid black;border-right:1px solid black" align="center">
							<?php echo  implode(',', $value['months']) ?>
						</td>
						<td width="20%" style="border-right:1px solid black" align="right">
							<?php $total +=  $mont_sum;
							echo  Tools::numberFormat($mont_sum, 2)  ?>
						</td>
						<td width="20%" style="border-right:1px solid black;border-right:2px solid black" align="right">
							<?php echo array_sum($value['restes']) ?>
						</td>
					</tr>

				<?php } ?>


				<tr>
					<td style="border-bottom:1px solid black;border-top:1px solid black; border-left:2px solid black;border-right:1px solid black"> Total</td>
					<td style="border-bottom:1px solid black;border-top:1px solid black; border-right:1px solid black" colspan="2" align="center">
						<b><?php echo \Tools::numberFormat($encaissement->get('Montant'), 2) ?></b>
					</td>
					<td style="border-bottom:1px solid black; border-top:1px solid black; border-right:2px solid black" align="right">المجموع</td>
				</tr>

				<tr>
					<td style="border-bottom:2px solid black;border-left:2px solid black;border-right:1px solid black"> Mode de paiement</td>
					<td colspan="2" style="border-bottom:2px solid black;border-right:1px solid black" align="center">
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
					<td style="border-bottom:2px solid black;border-right:2px solid black" align="right">الأداء </td>
				</tr>
				<?php if ($paiementDetail) { ?>

					<?php if ($encaissement->EncaissementPar) { ?>
						<tr>
							<td colspan="3">
								<?php if ($encaissement->EncaissementPar) { ?>
									Payé par <?php echo $encaissement->EncaissementPar ?>
								<?php } ?>

							</td>
						</tr>
					<?php } ?>
				<?php } ?>
			</table>
		</td>
	</tr>
</table>

<p style="font-size:12px;" align="center">
	<?php echo $etablissement->get('Adresse') ?>, <?php echo $etablissement->get('Ville') ?> -Tel:<?php echo $etablissement->get('Tel') ?>
</p>