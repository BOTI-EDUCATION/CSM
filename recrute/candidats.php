<section class="espace-listing">
    <section class="content-header row mt-3 boti-flex boti-align-center">
        <div class="content-header-left col-md-6 col-xs-12">
            <h1 class="text-blue-dark">
                <b>Canditatures en ligne :</b>
            </h1>
        </div>
        <div class="content-header-right col-md-6 col-xs-12 text-right">
            <button class="border-none act-filter mr-right-5" data-toggle="modal" data-target="#iti-modal">
                <i class="fa-solid fa-sliders deg-90"></i> Filtrer
            </button>
            <div class="btn-group">
                <button type="button" class="act dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                </button>
                <div class="dropdown-menu float-md-left open-left arrow" style="float: right !important; left: auto; right: 0;">
                    <a class="dropdown-item" href="<?php echo URL::admin('recrute/cvs') ?>"> Bibliothèque de CVs</a>
                    <a class="dropdown-item" href="<?php echo URL::admin('recrute/offres') ?>"> Offres de recrutement</a>
                    <a class="dropdown-item" href="<?= URL::base('/recrutement') ?>" target="_blank">Candidature en ligne</a>
                </div>
            </div>
            <!-- <div class="btn-group">
                <a href="<?php echo URL::admin('recrute/cvs') ?>" class="act-new boti-bg-blue-dark"> Candidats validées</a>
            </div> -->
        </div>
    </section>

    <section class="content-body mt-3">
        <div class="card border-radius-10 ">
            <div class="card-body ">
                <table class="table table-hover has-cmds datatable">
                    <thead>
                        <tr>
                            <th>Candidat</th>
                            <th>Offre</th>
                            <th>Ville</th>
                            <th>Spécialité</th>
                            <th>Expérience</th>
                            <th class="text-xs-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($condidats as $candidat) {
                        ?>
                            <tr data-id="<?php echo $candidat->get('Nom') . ' ' . $candidat->get('Prenom') ?>">
                                <td style="width:400px;">
                                    <div class="boti-flex">
                                        <div>
                                            <img src="<?php echo URL::base() ?>/assets/icons/<?php echo $candidat->get('Homme')  == 1 ? 'man.jpg' : 'woman.jpg' ?>" style="width:50px">
                                        </div>
                                        <div class="text-left">
                                            <span class="d-block text-sm" style="font-size: 12px;">
                                                <?php echo $candidat->get('Nom') . ' ' . $candidat->get('Prenom') ?>
                                            </span>
                                            <span style="font-size:9px"> <i class="fa fa-envelope"></i> <?php echo $candidat->get('Email'); ?> </span>
                                            <span style="font-size:9px"> <i class="fa fa-phone"></i> <?php echo $candidat->get('GSM'); ?> </span>
                                        </div>
                                    </div>
                                </td>

                                <td style="width:400px;">
                                    <div class="boti-flex  boti-align-center" style="justify-content:center">
                                        <div class="mr-left-15 text-left">
                                            <?php echo $candidat->get('Offre') ? $candidat->get('Offre')->get('Title') : '---' ?>
                                        </div>
                                    </div>
                                </td>
                                <td style="width:400px;">
                                    <div class="boti-flex  boti-align-center" style="justify-content:center">
                                        <div class="mr-left-15 text-left">
                                            <?php echo $candidat->get('Ville'); ?>
                                        </div>
                                    </div>
                                </td>
                                <td style="width:400px;">
                                    <div class="boti-flex  boti-align-center" style="justify-content:center">
                                        <div class="mr-left-15 text-left">
                                            <?php echo $candidat->get('Specialite'); ?>
                                        </div>
                                    </div>
                                </td>
                                <td style="width:400px;">
                                    <div class="boti-flex  boti-align-center" style="justify-content:center">
                                        <div class="mr-left-15 text-left">
                                            <?php echo $candidat->get('Experience'); ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-xs-center">
                                        <a href="#modal_item" data-toggle="modal" data-action="<?php echo URL::admin('recrute/candidat_modul/' . $candidat->get('ID')) ?>" data-loadto="#modal_item" class="plus loadcontent btn-actions">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="<?= $candidat->getCv() ?>" download="<?= $candidat->getCv() ?>" data-toggle="tooltip" data-placement="top" title="Télécharger le CV"  class="plus btn-actions">
                                            <i class="fa fa-file"></i>
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
<!--------------------------------------------------------- MODAL CANDIDAT --------------------------------------------------------->
<div class="modal fade" id="modal_item" tabindex="-1" role="basic" aria-hidden="true">
</div>

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
                                    <select name="offer" class="inu select2" data-placeholder="">
                                        <option value="tous" selected>Offres</option>
                                        <?php foreach (Models\RH\JobOffer::getList() as $job) { ?>
                                            <option value="<?php echo $job->Alias ?>" <?php echo isset($offer) && $offer->get('Alias') == $job->Alias ?  'selected' : '' ?>><?php echo $job->Alias ?></option>
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
<style>
    .circle {
        width: 50px;
        height: 50px;
        display: block;
        border-radius: 50%;
    }

    .bg-circle-success {

        background-color: #03a9f4;
    }

    .bg-circle-pink {
        background-color: pink;
    }
</style>