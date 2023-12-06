<style>
	table.header {
		width: 100%;
	}

	table.header tr td {
		font-size: 15px;
		list-style-type: none;
		margin: 0;
		font-weight: 100;
		padding: 0;
	}

	table.header tr td.td-left {
		text-align: left;
		direction: ltr;
		font-size: 12px;
		width: 80%;
	}

	table.header tr td.td-left h3 {
		font-size: 18px;
		font-weight: 600;
		text-transform: uppercase;
	}

	table.header tr td.td-right {
		text-align: right;
		direction: rtl;
		width: 20%;
	}


	.bold {
		font-weight: bold;
	}

	.text-right {
		text-align: right;
		display: block;
	}
</style>

<table cellpadding="5">
	<tr style="width:100%;">
		<td style="width:100%;text-align:center;">
			<img src="<?php echo URL::absolute($etablissement->getImage()) ?>" style="width:150px;" />

			<?php if ($encaissement->Canceled) { ?>
				<br />
				<span style="text-align:center;margin:0;"> <span class="bold"> Reçu d'annulation </span> </span>
			<?php } ?>

		</td>
	</tr>
	<tr style="width:100%;">
		<td style="width:100%;text-align:center;">
			<table>
				<tr>
					<td>
						<span style="text-align:center;margin:0;"> Année Scolaire : <?= $encaissement->Promotion->Label ?> </span>
					</td>
					<td>
						<span style="text-align:center;margin:0;"> Reçu N° : <?php echo $encaissement->get('NumRecu') ?> </span>
					</td>
					<td>
						<span style="text-align:center;margin:0;"> Le <?php echo date('d/m/Y', strtotime($encaissement->get('DatePaiement'))) ?> </span>
					</td>
				</tr>
			</table>

		</td>
	</tr>
	<tr style="width:100%;">
		<td>
			<table style="padding: 0px;width: 100%;">
				<tr>
					<td style="width: 100%;border: 2px solid #000;">
						<table cellpadding="5">
							<tr>
								<td width="40%" style="border: 1px solid #333;text-align: center;">Eleves</td>
								<td width="40%" style="border: 1px solid #333;text-align: center;">Frais</td>
								<td width="10%" style="border: 1px solid #333;text-align: center;">Mois</td>
								<td width="10%" style="border: 1px solid #333;text-align: center;">Montant</td>
							</tr>
							<?php foreach ($encaissement->encaissementLignes() as $encaissementLigne) {
								$amount =   Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($encaissementLigne->Inscription,  $encaissementLigne->Mois, $encaissementLigne->EncaissementRubrique);
								$service_amount = Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($encaissementLigne->Inscription,  $encaissementLigne->Mois,  $encaissementLigne->EncaissementRubrique);
								$reste = $service_amount - $amount

							?>
								<tr>
									<td style="font-size: 12px;border: 1px solid #333"> <?= $encaissementLigne->Inscription->Eleve->User->getNomComplet() ?> (<?= ($encaissementLigne->Inscription->Classe ? $encaissementLigne->Inscription->Classe->Label : $encaissementLigne->Inscription->Niveau->Label) ?>) </td>
									<td style="font-size: 12px;border: 1px solid #333"> <?= $encaissementLigne->EncaissementRubrique->Label ?> </td>
									<td style="font-size: 12px;border: 1px solid #333;text-align: center;"> <?= $encaissementLigne->Mois != 0 ? $encaissementLigne->Mois : '-' ?> </td>
									<td style="font-size: 12px;border: 1px solid #333;text-align: center;">
										<?= \Tools::numberFormat($encaissementLigne->Montant, 2) ?>
										<?php if ($reste) { ?>
											<span style="font-size:10px"> <u> reste <?php echo \Tools::numberFormat($reste, 2)  ?> </u> </span>
										<?php } ?>

									</td>
								</tr>

							<?php } ?>

							<tr>
								<td style="border: 1px solid #333" colspan="2">Total</td>
								<td style="border: 1px solid #333;text-align: center;" colspan="2"> <b><?= \Tools::numberFormat($encaissement->Montant, 2) ?></b> </td>
							</tr>
							<tr>
								<td style="border: 1px solid #333" colspan="2">Mode de paiement</td>
								<?php
								$mode = '';
								if ($encaissement->PaiementMode == "espece") {
									$mode = 'Espèce';
								} elseif ($encaissement->PaiementMode == "cheque") {
									$mode = 'Chèque';
								} else {
									$mode = $encaissement->PaiementMode;
								}
								?>
								<td style="border: 1px solid #333;text-align: center;" colspan="2"><?= $mode ?></td>
							</tr>

						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="font-size: 10px;">
			Je déclare avoir pris connaissance du réglement intérieur et financier de l'école
		</td>
	</tr>
	<tr>
		<td style="font-size: 10px;">
			NB: Frais d'inscription non remboursables. / L'uniforme scolaire est obligatoire / Transport selon disponibilité.
		</td>
	</tr>
</table>
<br />
<br />
<br />
<br />
<table cellpadding="0">
	<tr style="width:100%;height:0px;">
		<td style="border-bottom: .3px dashed grey;"></td>
	</tr>
</table>
<br />
<br />

<table cellpadding="5">
	<tr style="width:100%;">
		<td style="width:100%;text-align:center;">

			<img src="<?php echo URL::absolute($etablissement->getImage()) ?>" style="width:150px;" />

			<?php if ($encaissement->Canceled) { ?>
				<br />
				<span style="text-align:center;margin:0;"> <span class="bold"> Reçu d'annulation </span> </span>
			<?php } ?>

		</td>
	</tr>
	<tr style="width:100%;">
		<td style="width:100%;text-align:center;">
			<table>
				<tr>
					<td>
						<span style="text-align:center;margin:0;"> Année Scolaire : <?= $encaissement->Promotion->Label ?> </span>
					</td>
					<td>
						<span style="text-align:center;margin:0;"> Reçu N° : <?php echo $encaissement->get('NumRecu') ?> </span>
					</td>
					<td>
						<span style="text-align:center;margin:0;"> Le <?php echo date('d/m/Y', strtotime($encaissement->get('DatePaiement'))) ?></span>
					</td>
				</tr>
			</table>

		</td>
	</tr>

	<tr style="width:100%;">
		<td>
			<table style="padding: 0px;width: 100%;">
				<tr>
					<td style="width: 100%;border: 2px solid #000;">
						<table cellpadding="5">
							<tr>
								<td style="border: 1px solid #333;text-align: center;">Eleves</td>
								<td style="border: 1px solid #333;text-align: center;">Frais</td>
								<td style="border: 1px solid #333;text-align: center;">Mois</td>
								<td style="border: 1px solid #333;text-align: center;">Montant</td>
							</tr>
							<?php foreach ($encaissement->encaissementLignes() as $encaissementLigne) {

								$amount =   Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($encaissementLigne->Inscription,  $encaissementLigne->Mois, $encaissementLigne->EncaissementRubrique);
								$service_amount = Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($encaissementLigne->Inscription,  $encaissementLigne->Mois,  $encaissementLigne->EncaissementRubrique);
								$reste = $service_amount - $amount

							?>
								<tr>
									<td style="font-size: 12px;border: 1px solid #333"> <?= $encaissementLigne->Inscription->Eleve->User->getNomComplet() ?> </td>
									<td style="font-size: 12px;border: 1px solid #333"> <?= $encaissementLigne->EncaissementRubrique->Label ?> </td>
									<td style="font-size: 12px;border: 1px solid #333;text-align: center;"> <?= $encaissementLigne->Mois != 0 ? $encaissementLigne->Mois : '-' ?> </td>
									<td style="font-size: 12px;border: 1px solid #333;text-align: center;">
										<?= \Tools::numberFormat($encaissementLigne->Montant, 2) ?>
										<?php if ($reste) { ?>
											<span style="font-size:10px"> <u> reste <?php echo \Tools::numberFormat($reste, 2)  ?> </u> </span>
										<?php } ?>
									</td>
								</tr>

							<?php } ?>

							<tr>
								<td style="border: 1px solid #333" colspan="2">Total</td>
								<td style="border: 1px solid #333;text-align: center;" colspan="2"> <b> <?= \Tools::numberFormat($encaissement->Montant, 2) ?> </b></td>
							</tr>
							<tr>
								<td style="border: 1px solid #333" colspan="2">Mode de paiement</td>
								<?php
								$mode = '';
								if ($encaissement->PaiementMode == "espece") {
									$mode = 'Espèce';
								} elseif ($encaissement->PaiementMode == "cheque") {
									$mode = 'Chèque';
								} else {
									$mode = $encaissement->PaiementMode;
								}
								?>
								<td style="border: 1px solid #333;text-align: center;" colspan="2"><?= $mode ?></td>
							</tr>

						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="font-size: 10px;">
			Je déclare avoir pris connaissance du réglement intérieur et financier de l'école
		</td>
	</tr>
	<tr>
		<td style="font-size: 10px;">
			NB: Frais d'inscription non remboursables. / L'uniforme scolaire est obligatoire / Transport selon disponibilité.
		</td>
	</tr>
</table>