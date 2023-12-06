<div class="espace-listing">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 3rem;color:#696969">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo URL::base('/recrutement/post_job') ?>" method="post" enctype="multipart/form-data">
                <div class="lds-ripple-container">
                    <div class="lds-ripple">
                        <div></div>
                        <div></div>
                    </div>
                </div>
                <input type="hidden" name="cf_token" value="<?php echo cf_token(); ?>" />
                <div class="text-center">
                    <h1 style="color:#2532a1"> Candidature en ligne</h1>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="card">
                            <input name="campus" class="radio" type="radio" value="homme" checked>
                            <span class="plan-details">
                                <span class="plan-type">Homme</span>

                            </span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="card">
                            <input name="campus" class="radio" value="Femme" type="radio">
                            <span class="plan-details" aria-hidden="true">
                                <span class="plan-type">Femme</span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <!-- <label for="">Nom</label> -->
                            <input style="font-weight: normal !important;" type="text" id="nom" class="form-control main-input" name="nom" placeholder="Nom *" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <!-- <label for="">Prenom</label> -->
                            <input style="font-weight: normal !important;" type="text" id="prenom" class="form-control main-input" name="prenom" placeholder="Prénom *" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <!-- <label for="">Prenom</label> -->
                            <input style="font-weight: normal !important;" type="text" id="cin" class="form-control main-input" name="cin" placeholder="CIN *" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <!-- <label for="">Prenom</label> -->
                            <input style="font-weight: normal !important;" type="text" id="specialite" class="form-control main-input" name="specialite" placeholder="Spécialité *" required />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <!-- <label for="">Prenom</label> -->
                            <input style="font-weight: normal !important;" type="file" id="cv" class="form-control main-input" name="cv" placeholder="Votre CV *" required />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <!-- <label for="">Prenom</label> -->
                            <input style="font-weight: normal !important;" type="text" id="pay" class="form-control main-input" name="pays" placeholder="Pays *" required value="Maroc"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <!-- <label for="">Prenom</label> -->
                            <input style="font-weight: normal !important;" type="text" id="ville" class="form-control main-input" name="ville" placeholder="Ville *" required />
                        </div>
                    </div>
                </div>
                

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <!-- <label for="">Adresse</label> -->
                            <input style="font-weight: normal !important;" type="text" id="adresse" class="form-control main-input" name="adresse" placeholder="Adresse " required />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <!-- <label for="">GSM</label> -->
                            <input style="font-weight: normal !important;" type="text" id="tel" class="form-control main-input" name="tel" placeholder="GSM *" required />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <!-- <label for="">Email</label> -->
                            <input style="font-weight: normal !important;" type="email" id="email" class="form-control main-input" name="email" placeholder="Email " />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <!-- <label for="">Niveau d’enseinement</label> -->
                            <select style="font-weight: normal !important;" name="experience" class="form-control main-input select2" placeholder="Experience *" required>
                                <option value="<?php echo  'Debutant' ?>"> <?php echo 'Debutant' ?> </option>
                                <?php for ($i = 1; $i < 15; $i++) { ?>
                                    <option value="<?php echo  $i ?>"> <?php echo $i ?> </option>
                                <?php }  ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <!-- <label for="">Email</label> -->
                            <input style="font-weight: normal !important;" type="text" id="commentaire" class="form-control main-input" name="commentaire" placeholder="Commentaire " />
                        </div>
                    </div>
                </div>
                <input type="text" hidden value="<?php echo $alias ?>" name="job_offer">
                <br>

                <button class="btn btn-main btn-block text-center text-uppercase">Envoyer votre candidature</button>
            </form>
        </div>
    </div>
</div>