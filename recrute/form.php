<section class="espace-listing">
	<section class="content-body">

		<div class="card listing-form border-radius-10">
			<div class="card-header">
				<h1 class="text-blue-dark"> Gestion des offres > <b> Ajout </b> </h1>
				<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
				<div class="heading-elements">
					<a href="javascript:window.history.back()" class="btn"><i class="fa-solid fa-xmark"></i></a>
				</div>
			</div>
			<div class="progresse-step"></div>
			<div class="card-body collapse in">
				<div class="card-block">
					<form method="post" action="" class="form form-horizontal" enctype="multipart/form-data">

						<?= cf_fields() ?>
						<div class="form-body">

							<div class="form-group row">
								<label class="col-md-3 label-control" for="titre">Titre *</label>
								<div class="col-md-6">
									<input type="text" required placeholder="Titre" name="title" class="form-control " <?php if ($isUpdate) { ?> value="<?php echo html($offer->get('Title')) ?>" <?php } ?> required data-validation-required-message="Ce champ est obligatoires">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-3 label-control" for="presentation">Description de l’offre *</label>
								<div class="col-md-6">
									<textarea name="presentation" class="form-control editable medium-editor-textarea" required required data-validation-required-message="Ce champ est obligatoires"><?php if ($isUpdate) {
																																											echo html($offer->get('Presentation')) ?> <?php } ?></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-3 label-control" for="label">Ajoutez une image</label>
								<div class="col-md-6">
									<input type="file" name="offerid" class="dropify" <?php if ($isUpdate) { ?> data-default-file="<?php echo $offer->getImage() ?>" <?php } ?>>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-3 label-control" for="details">Date de publication</label>
								<div class="col-md-6">
									<input type="text" class="form-control datepicker" name="datepublication" <?php if ($isUpdate) { ?> value="<?php echo html($offer->get('DatePublication')) ?>" <?php } ?>>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-3 label-control" for="details">Date d’expiration</label>
								<div class="col-md-6">
									<input type="text" class="form-control datepicker" name="datefin" <?php if ($isUpdate) { ?> value="<?php echo html($offer->get('DateFin')) ?>" <?php } ?>>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-3 label-control" for="details">Offre publiée</label>
								<div class="col-md-6">
									<label class="custom-control custom-checkbox">
										<input type="checkbox" name="activation" <?php if ($isUpdate) {
																						echo $offer->Activation ? 'checked' : "";
																					}; ?> class="custom-control-input">
										<span class="custom-control-indicator"></span>
										<span class="custom-control-description">OUI</span>
									</label>
								</div>
							</div>


							<div class="form-group row">
								<label class="col-md-3 label-control" for=""></label>
								<button type="submit" class="btn btn-done">
									Enregistrer
								</button>
							</div>
						</div>
				</div>


				<?php if ($isUpdate) { ?>
					<input type="hidden" name="id" value="<?php echo $offer->get('ID') ?>">
				<?php } ?>

				</form>
			</div>
		</div>

	</section>
</section>