<section class="espace-listing">
    <section class="content-header row mt-3 boti-flex boti-align-center">
        <div class="content-header-left col-md-6 col-xs-12">
            <h1 class="text-blue-dark">
                <b>Offres de recrutement :</b>
            </h1>
        </div>
        <div class="content-header-right col-md-6 col-xs-12 text-right">
            <div class="btn-group">
                <a href="<?php echo URL::admin('recrute/new_offres') ?>" class="act-new boti-bg-blue-dark"> + Nouvelle offre</a>
            </div>
        </div>
    </section>

    <section class="content-body mt-3">
        <div class="card border-radius-10 ">
            <div class="card-body ">
                <table class="table table-hover has-cmds datatable">
                    <thead>
                        <tr>
                            <th>Poste</th>
                            <th class="text-xs-center">Date</th>
                            <th class="text-xs-center">Expiration</th>
                            <th class="text-xs-center">Candidatures</th>
                            <th class="text-xs-center">Etat</th>
                            <th class="text-xs-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($jobs as $job) {
                        ?>
                            <tr data-id="<?php echo $job->Title ?>">
                                <td style="width:400px;">
                                    <div class="boti-flex  boti-align-center" style="justify-content:center">
                                        <div class="mr-left-15 text-left">
                                            <?php echo $job->Title; ?>
                                        </div>
                                    </div>
                                </td>
                                <td style="width:400px;">
                                    <div class="boti-flex  boti-align-center" style="justify-content:center">
                                        <div class="mr-left-15 text-left">
                                            <?php echo date('Y:m:d', strtotime($job->DatePublication)); ?>
                                        </div>
                                    </div>
                                </td>
                                <td style="width:400px;">
                                    <div class="boti-flex  boti-align-center" style="justify-content:center">
                                        <div class="mr-left-15 text-left">
                                            <?php echo date('Y:m:d', strtotime($job->DateFin)); ?>
                                        </div>
                                    </div>
                                </td>
                                <td style="width:400px;">
                                    <div class="boti-flex  boti-align-center" style="justify-content:center">
                                        <div class="mr-left-15 text-left">
                                            <?php echo $job->getCondidatsCount(); ?>
                                        </div>
                                    </div>
                                </td>
                                <td style="width:400px;">
                                    <div class="boti-flex  boti-align-center" style="justify-content:center">
                                        <div class="mr-left-15 text-left">
                                            <?php if ($job->Activation) { ?>
                                                <img width="50px;" src="<?php echo URL::base() . 'assets/icons/check.svg' ?>" alt="">
                                            <?php } else { ?>
                                                <img width="50px;" src="<?php echo URL::base() . 'assets/icons/cancel.svg' ?>" alt="">
                                            <?php } ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-xs-center">
                                        <a data-toggle="tooltip" target="_blank" data-placement="top" title="Formulaire de candidature" href="<?php echo URL::base('recrutement?offer=' . $job->get('Alias')) ?>" class="btn-actions">
                                            <i class="fa fa-globe"></i>
                                        </a>
                                        <a data-toggle="tooltip" target="_blank" data-placement="top" title="Condidats de <?php echo  $job->get('Alias') ?>" href="<?php echo URL::admin('candidats?offer=' . $job->get('Alias')) ?>" class="btn-actions">
                                            <i class="fa fa-users"></i>
                                        </a>
                                        <a data-toggle="tooltip" data-placement="bottom" title="Modifier" href="<?php echo URL::admin('recrute/new_offres/' . $job->getPK('ID')) ?>" class="btn-actions">
                                            <i class="fa-sharp fa-solid fa-pen"></i>
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