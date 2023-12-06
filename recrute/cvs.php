<section class="espace-listing">
    <section class="content-header row mt-3 boti-flex boti-align-center">
        <div class="content-header-left col-md-6 col-xs-12">
            <h1 class="text-blue-dark">
                <b>Bibliothèque de CV (<?= count($cvs) ?>)</b>
            </h1>
        </div>
        <div class="content-header-right col-md-6 col-xs-12 text-right">
            <button class="border-none act-filter mr-right-5" data-toggle="modal" data-target="#iti-modal">
                <i class="fa-solid fa-sliders deg-90"></i> Filter
            </button>
            <div class="btn-group">
                <button type="button" class="act dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                </button>
                <div class="dropdown-menu float-md-left open-left arrow" style="float: right !important; left: auto; right: 0;">

                    <a class="dropdown-item" href="<?= URL::admin('/recrute/candidat_item') ?>" target="_blank">Nouveau CV</a>
                    <a class="dropdown-item" href="<?= URL::admin('/recrute/cvs?export_excel') ?>"> Export en Excel</a>
                    <a class="dropdown-item" href="<?php echo URL::admin('recrute/offres') ?>"> Offres de recrutement</a>
                    <a class="dropdown-item" href="<?php echo URL::admin('recrute/candidats') ?>"> Demandes de candidatures</a>
                    <a class="dropdown-item" href="<?= URL::base('/recrutement') ?>" target="_blank">Candidature en ligne</a>

                </div>
            </div>
        </div>
    </section>

    <section class="content-body mt-3">
        <div class="card border-radius-10 " style="overflow-x: auto;">
            <div class="card-body ">
                <table class="table table-hover has-cmds datatable">
                    <thead>
                        <tr>
                            <th>Nom et Prénom</th>
                            <th>Ville</th>
                            <th>Age</th>
                            <th>Matière enseignée</th>
                            <th>Quartier</th>
                            <th>Années experience</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cvs as $cv) {
                        ?>
                            <tr data-id="<?php echo $cv->get('Nom') . ' ' . $cv->get('Prenom') ?>">
                                <td style="width:400px;">
                                    <div class="boti-flex">
                                        <div>
                                            <img src="<?php echo URL::base() ?>/assets/icons/<?php echo $cv->get('Homme')  == 1 ? 'man.jpg' : 'woman.jpg' ?>" style="width:50px">
                                        </div>
                                        <div class="text-left">
                                            <span class="d-block text-sm" style="font-size: 12px;">
                                                <?php echo $cv->get('Nom') . ' ' . $cv->get('Prenom') ?>
                                            </span>
                                            <span style="font-size:9px"> <i class="fa fa-envelope"></i> <?php echo $cv->get('Email'); ?> </span>
                                            <span style="font-size:9px"> <i class="fa fa-phone"></i> <?php echo $cv->get('GSM'); ?> </span>
                                        </div>
                                    </div>
                                </td>

                                <td style="width:400px;">
                                    <div class="boti-flex  boti-align-center" style="justify-content:center;white-space: nowrap;">
                                        <div class="mr-left-15 text-left">
                                            <?php echo $cv->get('Ville'); ?>
                                        </div>
                                    </div>
                                </td>

                                <td style="width:400px;">
                                    <div class="boti-flex  boti-align-center" style="justify-content:center;white-space: nowrap;">
                                        <div class="mr-left-15 text-left">
                                            <?php echo $cv->getAge(); ?>
                                        </div>
                                    </div>
                                </td>

                                <td style="width:400px;">
                                    <div class="boti-flex  boti-align-center" style="justify-content:center;white-space: nowrap;">
                                        <div class="mr-left-15 text-left">
                                            <?php echo $cv->get('Specialite'); ?>
                                        </div>
                                    </div>
                                </td>
                                <td style="width:400px;">
                                    <div class="boti-flex  boti-align-center" style="justify-content:center;white-space: nowrap;">
                                        <div class="mr-left-15 text-left">
                                            <?php echo $cv->get('Adresse'); ?>
                                        </div>
                                    </div>
                                </td>
                                <td style="width:400px;">
                                    <div class="boti-flex  boti-align-center" style="justify-content:center;white-space: nowrap;">
                                        <div class="mr-left-15 text-left">
                                            <?php echo $cv->get('Experience'); ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-xs-center">
                                        <a href="#modal_item" data-toggle="modal" data-action="<?php echo URL::admin('recrute/cv_modal/' . $cv->get('ID')) ?>" data-loadto="#modal_item" class="plus loadcontent btn-actions">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</section>
<!--------------------------------------------------------- MODAL FILTER --------------------------------------------------------->
<div class="modal fade text-xs-left" id="iti-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-md" style="width: 350px" role="document">
        <div class="modal-content padding-bottom-8">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark" style="color: #12122C;"></i>
                </button>
                <h2 class="text-blue-dark" id="">
                    <b>Filtre</b>
                </h2>
            </div>
            <div class="modal-contents">
                <form method="get" enctype="multipart/form-data">
                    <div class="modal-body padding-bottom-null">
                        <div class="row">
                            <div class="col-md-12">


                                <div class="form-group_1 mb-2">
                                    <select name="annee_experience" class="inu select2" data-placeholder="">
                                        <option value="tous" selected>Expériences</option>
                                        <option value="Debutant" <?php echo isset($annee_experience) && $annee_experience == 'Debutant' ?  'selected' : '' ?>>Débutant</option>
                                        <?php for ($i = 1; $i <= 20; $i++) { ?>
                                            <option value="<?php echo $i ?>" <?php echo isset($annee_experience) && $annee_experience == $i ?  'selected' : '' ?>><?php echo $i ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group_1 mb-2">
                                    <select name="age" class="inu select2" data-placeholder="">
                                        <option value="age" selected>Age</option>
                                        <?php for ($i = 1; $i <= 40; $i++) { ?>
                                            <option value="<?php echo $i ?>" <?php echo isset($age) && $age == $i ?  'selected' : '' ?>><?php echo $i ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group_1 mb-2">
                                    <select name="secteur" class="inu select2" data-placeholder="">
                                        <option value="secteur" selected>Tous secteurs</option>
                                        <?php foreach (($secteurs) as $item) { ?>
                                            <option value="<?php echo $item['Secteur'] ?>" <?php echo isset($secteur) && $secteur == $item['Secteur'] ?  'selected' : '' ?>><?php echo $item['Secteur'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group_1 mb-2">
                                    <select name="tag" class="inu select2" data-placeholder="">
                                        <option value="tag" selected>Tous les tag</option>
                                        <?php foreach ($tags as $item) { ?>
                                            <option value="<?php echo $item ?>" <?php echo isset($tag) && $tag == $item ?  'selected' : '' ?>><?php echo $item ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="w-100 font-light saving_medium">Appliquer</button>
                        <input type="reset" class="reset" value="Réinitialiser les filtres">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_item" tabindex="-1" role="basic" aria-hidden="true">
</div>