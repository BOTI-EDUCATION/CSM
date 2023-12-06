<section class="espace-listing">
    <section class="content-header row mt-3 boti-flex boti-align-center">
        <div class="content-header-left col-md-6 col-xs-12">
            <h1 class="text-blue-dark">
                <b>Recrutement :</b>
            </h1>
        </div>
        <div class="content-header-right col-md-6 col-xs-12 text-right">
            <div class="btn-group">
                <button type="button" class="act dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                </button>
                <div class="dropdown-menu float-md-left open-left arrow" style="float: right !important; left: auto; right: 0;">
                    <a class="dropdown-item" href="<?= URL::admin('/recrute/candidat_item') ?>" target="_blank">Nouveau CV</a>
                    <a class="dropdown-item" href="<?= URL::admin('/recrute/new_offres') ?>" target="_blank">Nouvelle Offre</a>
                    <a class="dropdown-item" href="<?php echo URL::admin('recrute/candidats') ?>"> Demandes de candidatures</a>
                    <a class="dropdown-item" href="<?php echo URL::admin('recrute/cvs') ?>"> Bibliothèque de CVs</a>
                    <a class="dropdown-item" href="<?php echo URL::admin('rh/salaires') ?>"> Etat des salaires</a>

                </div>
            </div>
        </div>
    </section>
    <br>
    <div class="row mt-3">
        <div class="nav-item col-md-4" role="presentation">
            <a href="javascript:void(0)" class="nav-item nav-link  p-2 active background-design shadow" role="tab" aria-controls="home-tab-pane" aria-selected="true" style="display: flex;display: flex;background: white;border-radius: 10px;">
                <div class="iconePick icon-center" style="background: #ffefed;">
                    <svg viewBox="0 0 24 24" width="30px" height="30px" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path d="M9 7H5C3.89543 7 3 7.89543 3 9V18C3 19.1046 3.89543 20 5 20H19C20.1046 20 21 19.1046 21 18V9C21 7.89543 20.1046 7 19 7H15M9 7V5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7M9 7H15" stroke="#e76f68" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                    </svg>
                </div>
                <div class="statis">
                    <h3><?php echo count($jobs) ?></h3>
                    <p>Offers</p>
                </div>
            </a>
        </div>

        <div class="nav-item col-md-4" role="presentation">
            <a href="<?php echo URL::admin('recrute/candidats') ?>" class="nav-item nav-link  p-2 active background-design shadow" role="tab" aria-controls="home-tab-pane" aria-selected="true" style="display: flex;display: flex;background: white;border-radius: 10px;">
                <div class="iconePick icon-center" style="background: #FFF2D6;">
                    <svg viewBox="0 0 24 24" width="30px" height="30px" xmlns="http://www.w3.org/2000/svg" fill="#000000">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <defs>
                                <style>
                                    .a,
                                    .b {
                                        fill: none;
                                        stroke: #f7ad3e;
                                        stroke-linecap: round;
                                        stroke-width: 1.5px;
                                    }

                                    .a {
                                        stroke-linejoin: round;
                                    }

                                    .b {
                                        stroke-linejoin: bevel;
                                    }
                                </style>
                            </defs>
                            <path class="a" d="M2.5,21.02394l.78984-2.87249C4.5964,13.39978,8.0482,10.992,11.5,10.992"></path>
                            <circle class="b" cx="11.5" cy="5.99202" r="5"></circle>
                            <circle class="a" cx="16.5" cy="18.00798" r="5"></circle>
                            <polyline class="a" points="16.202 15.383 16.202 18.362 19.128 18.362"></polyline>
                        </g>
                    </svg>


                </div>
                <div class="statis">
                    <h3><?php echo  $candidats ?> </h3>
                    <p>Canditatures reçues</p>
                </div>
            </a>
        </div>

        <div class="nav-item col-md-4" role="presentation">
            <a href="javascript:void(0)" class="nav-item nav-link  p-2 d-flex active background-design shadow" role="tab" aria-controls="home-tab-pane" aria-selected="true" style="display: flex;display: flex;background: white;border-radius: 10px;">
                <div class="iconePick icon-center" style="background: #E8EDFF;">
                    <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path d="M16 18L18 20L22 16M12 15H8C6.13623 15 5.20435 15 4.46927 15.3045C3.48915 15.7105 2.71046 16.4892 2.30448 17.4693C2 18.2044 2 19.1362 2 21M15.5 3.29076C16.9659 3.88415 18 5.32131 18 7C18 8.67869 16.9659 10.1159 15.5 10.7092M13.5 7C13.5 9.20914 11.7091 11 9.5 11C7.29086 11 5.5 9.20914 5.5 7C5.5 4.79086 7.29086 3 9.5 3C11.7091 3 13.5 4.79086 13.5 7Z" stroke="#6c84db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                    </svg>


                </div>
                <div class="statis">
                    <h3><?php echo  $candidats_qualifier ?> </h3>
                    <p>Candidats qualifiées</p>
                </div>
            </a>
        </div>


    </div>

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
                                            <?php echo $job->getCondidatsCount(false); ?>
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
                                        <a data-toggle="tooltip" data-placement="top" title="<?= $job->Activation?'Désactiver':'Activer' ?> l'offre" href="<?php echo URL::admin('recrute/toggle_offre/' . $job->getPK('ID')) ?>" class="btn-actions">
                                            <i class="fa fa-<?= $job->Activation?'ban':'check' ?>"></i>
                                        </a>
                                        <a data-toggle="tooltip" target="_blank" data-placement="top" title="Formulaire de candidature" href="<?php echo URL::base('recrutement?offer=' . $job->get('Alias')) ?>" class="btn-actions">
                                            <i class="fa fa-globe"></i>
                                        </a>
                                        <a data-toggle="tooltip" target="_blank" data-placement="top" title="Modifier" href="<?php echo URL::admin('recrute/new_offres/' . $job->getPK('ID')) ?>" class="btn-actions">
                                            <i class="fa fa-pen"></i>
                                        </a>
                                        <a data-toggle="tooltip" target="_blank" data-placement="top" title="Condidats de <?php echo  $job->get('Alias') ?>" href="<?php echo URL::admin('recrute/candidats?offer=' . $job->get('Alias')) ?>" class="btn-actions">
                                            <i class="fa fa-users"></i>
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
<style>
    .icon-center {
        margin-right: 15px;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>