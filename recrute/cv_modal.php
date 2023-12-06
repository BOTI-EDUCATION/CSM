<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                style="font-size: 3rem;color:#696969">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form enctype="multipart/form-data">
            <?= cf_fields() ?>

            <div class="row mx-0">
                <div class="col-md-6 col-lg-7">
                    <div class="row mx-0">
                        <div class="col-md-12">
                            <div class="boti-flex" class="align-items: center;">
                                <img src="<?= URL::base() ?>/assets/icons/<?php echo $cv->get('Homme')  ?   'man.jpg' : 'woman.jpg' ?>"
                                    width="100px" alt="a">
                                <div class="text-box ml-1">
                                    <h3> <b><?= $cv->get('Nom') . ' ' . $cv->get('Prenom') ?></b> </h3>
                                    <h5><i class="fa mr-1 fa-credit-card"></i> <?= $cv->get('CIN') ?></h5>
                                    <h5><i class="fa mr-1 fa-envelope"></i> <?= $cv->get('Email') ?></h5>
                                    <h5><i class="fa mr-1 fa-phone"></i> <?= $cv->get('GSM') ?></h5>
                                    <h5><i class="fa mr-1 fa-map-marker "></i> <?= ($cv->get('Adresse')?$cv->get('Adresse').' ,':'').$cv->get('Ville').' ,'.$cv->get('Pays') ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <table class=" table-borderless ">
                                <tr>
                                    <td class="text-left"> <h5> <b> Specialite : </b> </h5> </td>
                                    <td class="text-left"> <h5> <?= $cv->get('Specialite') ?> </h5> </td>
                                </tr>
                                <tr>
                                    <td class="text-left"> <h5> <b> Experience : </b> </h5> </td>
                                    <td class="text-left"> <h5> <?= $cv->get('Experience') ?> </h5> </td>
                                </tr>
                                <tr>
                                    <td class="text-left"> <h5> <b> Date : </b> </h5> </td>
                                    <td class="text-left"> <h5> <?= $cv->get('Date') ?> </h5> </td>
                                </tr>
                            </table>
                        </div>
                        <!-- <div class="col-md-12 mt-2">
                            <a href="<?php echo URL::admin('recrute/candidat_item/' . $cv->getPK('ID')) ?>" class="btn btn-success btn-rounded btn-block text-center">
                                <i class="fa fa-check mr-1"></i> Valider le candidat
                            </a>
                            <a 
                                class="btn btn-danger btn-rounded btn-block text-center  delete-action"
                                href="<?php echo URL::admin('recrute/delete_condidat/' . $cv->getPK('ID')) ?>" 
                                data-action="<?php echo URL::admin('recrute/delete_condidat/') . $cv->getPk() ?>"
                            >
                                <i class="fa fa-trash mr-1"></i> Annuler la candidature
                            </a>
                            
                        </div> -->
                    </div>
                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="center-cv">
                        <?php if ($cv->get('CV') && explode('.', $cv->get('CV'))[1] == 'pdf') { ?>
                        <iframe src="<?= $cv->getCv() ?>" frameborder="0" width="100%" height="550px"></iframe>
                        <?php } ?>
                        <h3 class="mt-2">
                            <a href="<?= $cv->getCv() ?>" download="<?= $cv->getCv() ?>"
                                class="btn btn-success">download</a>
                        </h3>
                    </div>
                </div>
            </div>

            <?php if(false){ ?>
                <div class="row" style="display: flex;justify-content: space-between;">
                    <div class="col" style="display: flex;">
                        <img src="<?= URL::base() ?>/assets/icons/<?php echo $cv->get('Homme')  ?   'man.jpg' : 'woman.jpg' ?>"
                            width="100px" alt="a">
                        <div class="text-box">
                            <h3><?= $cv->get('Nom') . ' ' . $cv->get('Prenom') ?></h3>
                            <h3><?= 'age xx' ?></h3>
                        </div>
                    </div>
                    <div class="col">
                        <h4>Email : <?= $cv->get('Email') ?></h4>
                        <h4>Tél : <?= $cv->get('GSM') ?></h4>
                    </div><br>
                </div>
                <div class="center-cv">
                    <?php if ($cv->get('CV') && explode('.', $cv->get('CV'))[1] == 'pdf') { ?>
                    <iframe src="<?= $cv->getCv() ?>" frameborder="0" height="550px"></iframe>
                    <?php } ?>
                    <h3 class="mt-2">
                        <a href="<?= $cv->getCv() ?>" download="<?= $cv->getCv() ?>"
                            class="btn btn-success">download</a>
                    </h3>
                </div>
                <div class="modal-footer">
                    <div style="text-align: left;">
    
                        <a data-toggle="tooltip" data-placement="bottom" title="Valider le candidat"
                            href="<?php echo URL::admin('recrute/candidat_item/' . $cv->getPK('ID')) ?>"
                            class="btn-actions">
                            <i class="fa-sharp fa-solid fa-check"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Supprimer"
                            href="<?php echo URL::admin('recrute/delete_condidat/' . $cv->get('ID')) ?>"
                            class="btn-actions<" data-closest=".classe-item" data-toggle="tooltip"
                            data-placement="top" title="Supprimer"
                            data-action="<?php echo URL::admin('recrute/delete_condidat/') . $cv->getPk() ?>">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                    <button type="button" class="anul_without_back" data-dismiss="modal">Fermer</button>
                </div>
            <?php } ?>

        </form>
    </div>
</div>
<style>
    .center-cv {
        /* min-height: 200px; */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
</style>