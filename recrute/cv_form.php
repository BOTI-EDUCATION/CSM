<link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/css/pages/pick/pick.css">
<div class="espace-listing">
    <div style="width: 800px;margin: auto;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="darkBlue" id="">
                    <b>Qualification de la candidature</b>
                </h4>
                <h5 class="darkBlue" id="">
                    <?= !$is_new ? "Poste :  " . $candidat->get('Offre')->get('Title') : '' ?>
                </h5>
            </div>
            <form action="<?php echo URL::admin('/recrute/candidat_item/')  ?: null ?>" class="inscription-form-container" method="post" enctype="multipart/form-data">
                <?= cf_fields() ?>
                <div class="modal-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" hidden value="<?php echo !$is_new ? $id : '' ?>" name="candidat">

                            <div class="d-flex align-center space-bet">
                                <label>Nom *: </label>
                                <div class="form-group">
                                    <input type="text" value="<?= !$is_new ? $candidat->get('Nom') : '' ?>" name="nom" id="titre" class="inu" value="" required />
                                    <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                        ce demande est déja exist!
                                    </span>
                                    <span id="errorField"></span>
                                </div>
                            </div>
                            <div class="d-flex align-center space-bet">
                                <label>Prenom *: </label>
                                <div class="form-group">
                                    <input type="text" value="<?= !$is_new ? $candidat->get('Prenom') : '' ?>" name="prenom" id="prenom" class="inu" value="" required />
                                    <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                        ce demande est déja exist!
                                    </span>
                                    <span id="errorField"></span>
                                </div>
                            </div>
                            <div class="d-flex align-center space-bet">
                                <label>GSM *: </label>
                                <div class="form-group">
                                    <input type="text" name="tel" value="<?= !$is_new ? $candidat->get('GSM') : '' ?>" id="tel" class="inu " value="" required />
                                    <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                        ce demande est déja exist!
                                    </span>
                                    <span id="errorField"></span>
                                </div>
                            </div>
                            <div class="d-flex align-center space-bet">
                                <label>Email *: </label>
                                <div class="form-group">
                                    <input type="email" name="email" value="<?= !$is_new ? $candidat->get('Email') : '' ?>" id="email" class="inu " value="" required />
                                    <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                        ce demande est déja exist!
                                    </span>
                                    <span id="errorField"></span>
                                </div>
                            </div>
                            <div class="d-flex align-center space-bet">
                                <label>CIN *: </label>
                                <div class="form-group">
                                    <input type="text" value="<?= !$is_new ? $candidat->get('CIN') : '' ?>" name="cin" id="cin" class="inu" value="" required />
                                    <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                        ce demande est déja exist!
                                    </span>
                                    <span id="errorField"></span>
                                </div>
                            </div>
                            <div class="d-flex align-center space-bet">
                                <label>Pays *: </label>
                                <div class="form-group">
                                    <input type="text" value="<?= !$is_new ? $candidat->get('Pays') : '' ?>" id="pays" name="pays" class="inu" required />
                                    <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                        ce demande est déja exist!
                                    </span>
                                    <span id="errorField"></span>
                                </div>
                            </div>
                            <div class="d-flex align-center space-bet">
                                <label>Ville *: </label>
                                <div class="form-group">
                                    <input type="text" value="<?= !$is_new ? $candidat->get('Ville') : '' ?>" id="ville" name="ville" class="inu" required />
                                    <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                        ce demande est déja exist!
                                    </span>
                                    <span id="errorField"></span>
                                </div>
                            </div>
                            <div class="d-flex align-center space-bet">
                                <label>Date de naissance : </label>
                                <div class="form-group">
                                    <input type="text" id="datenaissance" name="datenaissance" class="inu datepicker" required />
                                    <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                        ce demande est déja exist!
                                    </span>
                                    <span id="errorField"></span>
                                </div>
                            </div>
                            <div class="d-flex  space-bet">
                                <label>Sexe : </label>
                                <div class="form-group">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="card no-shodow">
                                                <input name="campus" class="radio" type="radio" value="homme" <?php if (!$is_new) {
                                                                                                                    echo $candidat->get('Homme') == 1 ? 'checked' : '';
                                                                                                                }  ?>>
                                                <span class="plan-details">
                                                    <img src="<?php echo URL::base() ?>/assets/icons/man.jpg" class="av-img">
                                                    <span class="plan-type">Homme</span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="card no-shodow">
                                                <input name="campus" class="radio" value="Femme" type="radio" <?php if (!$is_new) {
                                                                                                                    echo $candidat->get('Homme') == 0 ? 'checked' : '';
                                                                                                                } ?>>
                                                <span class="plan-details" aria-hidden="true">
                                                    <img src="<?php echo URL::base() ?>/assets/icons/woman.jpg" class="av-img">
                                                    <span class="plan-type">Femme</span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-center space-bet">
                        <label>Offre d’emploi candidaté * : </label>
                        <div class="form-group">
                            <input type="text" name="secteur" id="secteur" class="inu " value="" required />
                            <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                ce demande est déja exist!
                            </span>
                            <span id="errorField"></span>
                        </div>
                    </div>

                    <div class="d-flex align-center space-bet">
                        <label>Poste * : </label>
                        <div class="form-group">
                            <input type="text" name="poste" id="post" class="inu " value="" required />
                            <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                ce demande est déja exist!
                            </span>
                            <span id="errorField"></span>
                        </div>
                    </div>

                    <div class="d-flex align-center space-bet">
                        <label>Spécialité * : </label>
                        <div class="form-group">
                            <select name="specialite" id="specialite" class=" select2" required>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Enseignant(e)' ? 'selected' : '' ?>>Enseignant(e)</option>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Directeur(trice) pédagogique' ? 'selected' : '' ?>>Directeur(trice) pédagogique</option>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Surveillant(e) pédagogique' ? 'selected' : '' ?>>Surveillant(e) pédagogique</option>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Personnel administratif / Assistant(e)' ? 'selected' : '' ?>>Personnel administratif / Assistant(e)</option>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Responsable/Technicien informatique' ? 'selected' : '' ?>>Responsable/Technicien informatique</option>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Psychologue scolaire' ? 'selected' : '' ?>>Psychologue scolaire</option>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Bibliothécaire' ? 'selected' : '' ?>>Bibliothécaire</option>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Responsable de la communication' ? 'selected' : '' ?>>Responsable de la communication</option>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Responsable Qualité' ? 'selected' : '' ?>>Responsable Qualité</option>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Encadrant pédagogique' ? 'selected' : '' ?>>Encadrant pédagogique</option>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Coach sportif' ? 'selected' : '' ?>>Coach sportif</option>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Chef de cuisine' ? 'selected' : '' ?>>Chef de cuisine</option>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Infirmière scolaire' ? 'selected' : '' ?>>Infirmière scolaire</option>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Agent de sécurité' ? 'selected' : '' ?>>Agent de sécurité</option>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Aide Enseignante Maternelle' ? 'selected' : '' ?>>Aide Enseignante Maternelle</option>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Aide Transport scolaire ' ? 'selected' : '' ?>>Aide Transport scolaire </option>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Personnel de nettoyage et d\'entretien' ? 'selected' : '' ?>>Personnel de nettoyage et d'entretien</option>
                                <option <?= !$is_new && $candidat->get('Specialite') == 'Intervenant en musique, arts plastiques, langues étrangères, etc.' ? 'selected' : '' ?>>Intervenant en musique, arts plastiques, langues étrangères, etc.</option>
                            </select>
                            <!-- <input type="text" value="<?php //!$is_new ? $candidat->get('Specialite') : '' 
                                                            ?>"
                                name="specialite" id="specialite" class="inu" value="" required /> -->
                            <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                ce demande est déja exist!
                            </span>
                            <span id="errorField"></span>
                        </div>
                    </div>
                    <div class="d-flex align-center space-bet">
                        <label>CV : </label>
                        <div class="form-group">
                            <input type="file" value="<?= !$is_new ? $candidat->get('CV') : '' ?>" name="cv" id="cv" class="inu dropify " value="" />
                            <input style="font-weight: normal !important;" hidden type="origin_cv" id="origin_cv" class="form-control main-input inu" value="<?= !$is_new ? $candidat->get('CV') : '' ?>" name="origin_cv" placeholder="Cv" />

                            <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                ce demande est déja exist!
                            </span>
                            <span id="errorField"></span>
                        </div>
                    </div>
                    <div class="d-flex align-center space-bet">
                        <label>Diplome * : </label>
                        <div class="form-group">
                            <input type="file" name="diplome" id="diplome" class="inu dropify " value="" />
                            <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                ce demande est déja exist!
                            </span>
                            <span id="errorField"></span>
                        </div>
                    </div>
                    <div class="d-flex align-center space-bet">
                        <label>Diplome etablissement * : </label>
                        <div class="form-group">
                            <input type="text" name="diplomeetablissement" id="diplomeetablissement" class="inu " value="" required />
                            <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                ce demande est déja exist!
                            </span>
                            <span id="errorField"></span>
                        </div>
                    </div>
                    <div class="d-flex align-center space-bet">
                        <label>Dernière expérience * : </label>
                        <div class="form-group">
                            <input type="text" name="dernierexperience" id="dernierexperience" class="inu " value="<?php !$is_new ? $candidat->Experience : '' ?>" required />
                            <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                ce demande est déja exist!
                            </span>
                            <span id="errorField"></span>
                        </div>
                    </div>
                    <div class="d-flex align-center space-bet">
                        <label>Enseignant : </label>
                        <div class="form-group">
                            <input type="checkbox" name="enseignant" id="switchery" data-toggle="toggle-section" data-target=".matiere_enseigne" class="switchery" />
                            <label for="switchery" class="font-medium-2 text-bold-600 ml-1"></label>
                        </div>
                    </div>
                    <div class="d-flex align-center space-bet matiere_enseigne " style="display: none;">
                        <label>Matière Enseignée : </label>
                        <div class="form-group">
                            <select name="matiere_enseigne[]" id="matiere_enseigne" data-placeholder="Matière" class="select2" disabled multiple>
                                <option>Arabe</option>
                                <option>Français</option>
                                <option>Anglais</option>
                                <option>Mathématiques</option>
                                <option>Sciences de la vie et de la terre</option>
                                <option>Physique / Chimie</option>
                                <option>Histoire-géographie</option>
                                <option>Education islamique</option>
                                <option>Education artistique</option>
                                <option>Education physique et sportive</option>
                                <option>Philosophie</option>
                                <option>Informatique</option>
                                <option>Robotique</option>
                                <option>Arts plastiques</option>
                                <option>Théâtre</option>
                                <option>Danse</option>
                                <option>Espagnol</option>
                            </select>
                            <span id="errorField"></span>
                        </div>
                    </div>
                    <div class="d-flex align-center space-bet matiere_enseigne " style="display: none;">
                        <label>Cycles : </label>
                        <div class="form-group">
                            <select name="cycles[]" id="cycles" data-placeholder="Cycles" class="select2" disabled multiple>
                                <option>Crèche-maternelle</option>
                                <option>Primaire</option>
                                <option>Collège</option>
                                <option>Lycée</option>
                            </select>
                            <span id="errorField"></span>
                        </div>
                    </div>


                    <div class="d-flex align-center space-bet">
                        <label>Mots clés : </label>
                        <div class="form-group">
                            <input type="text" id="tags" class="form-control" name="tags" data-role="tagsinput">
                            <small>(Bonne communication, certificat montessori ...)</small>
                            <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                ce demande est déja exist!
                            </span>
                            <span id="errorField"></span>
                        </div>
                    </div>

                    <div class="d-flex align-center space-bet">
                        <label>Adresse : </label>
                        <div class="form-group">
                            <input type="text" name="adresse" value="<?= !$is_new ? $candidat->get('Adresse') : '' ?>" id="adresse" class="inu " />
                            <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                ce demande est déja exist!
                            </span>
                            <span id="errorField"></span>
                        </div>
                    </div>
                    <div class="d-flex align-center space-bet">
                        <label>Années d'expérience : </label>

                        <div class="form-group">
                            <select name="experience" class="select2">
                                <option <?php if (!$is_new) {
                                            echo $candidat->get('Experience') === 'Debutant' ? 'selected' : '';
                                        } ?> value="<?php echo  'Debutant' ?>"> <?php echo 'Debutant' ?> </option>
                                <?php for ($i = 1; $i < 15; $i++) { ?>
                                    <option value="<?php echo  $i ?>" <?php if (!$is_new) {
                                                                            echo  $candidat->get('Experience') == $i ? 'selected' : '';
                                                                        } ?>> <?php echo $i ?> </option>
                                <?php }  ?>
                            </select>
                        </div>

                    </div>

                    <div class="d-flex align-center space-bet">
                        <label>Note / Appréciation :</label>
                        <div class="form-group">
                            <select name="appreciation" id="cycles" data-placeholder="Appréciations" class="select2">
                                <?php foreach (\Models\RH\CV::appreciations() as $key => $item) { ?>
                                    <option value="<?php echo $item ?>"> <?php echo $item ?></option>
                                <?php } ?>
                            </select>
                            <span id="errorField"></span>
                        </div>

                    </div>

                    <div class="d-flex align-center space-bet">
                        <label>Commentaire : </label>
                        <div class="form-group">
                            <input type="text" name="remarques" id="remarques" class="inu" value="<?php !$is_new ? $candidat->Commentaire : '' ?>" />
                            <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                ce demande est déja exist!
                            </span>
                            <span id="errorField"></span>
                        </div>
                    </div>


                    <!-- <div class="d-flex align-center space-bet">
                        <label>Commentaire : </label>
                        <div class="form-group">
                            <input type="text" name="commentaire" value="<?= !$is_new ? $candidat->get('Commentaire') : '' ?>" id="commentaire" class="inu " value="" required />
                            <span class="text-danger errorName" style="color:#dc3545; margin-top:5px; display:none">
                                ce demande est déja exist!
                            </span>
                            <span id="errorField"></span>
                        </div>
                    </div> -->
                    <button type="submit" class="saving d-block btn-block mt-3" style="width:100%;">Enregistrer</button>
                </div>
        </div>
    </div>
    <div class="modal-footer">
    </div>
    </form>
</div>
</div>

</div>
<style>
    .btn-main {
        background: #<?php echo $mainColor ?> !important;
    }

    .inscription-form-container label:not(.card) {
        color: #000 !important;
        font-weight: 500;
    }

    /* .inscription-form-container label.card input~.plan-details {
        border: 5px solid #fff;
    } */

    .inscription-form-container label.card-homme input:checked~.plan-details {
        background-color: #265ff1;
        border-color: #265ff1;
        box-shadow: 0px 5px 10px rgb(38 95 241 / 60%);
        color: #fff;
    }

    .inscription-form-container label.card-femme input:checked~.plan-details {
        background-color: #ff2c5d;
        border-color: #ff2c5d;
        box-shadow: 0px 5px 10px rgb(255 44 93 / 60%);
        color: #fff;
    }

    /* .inscription-form-container label.card-homme {
        background-color: #265ff1;
        color: #fff;
    }

    .inscription-form-container label.card-femme {
        background-color: #ff2c5d;
        color: #fff;
    } */

    .inscription-form-container input {
        color: #000 !important;
        font-weight: bold;
    }

    .form-control {
        border: 1px solid #c7c7c7 !important;
        font-weight: bold !important;
        color: #000 !important;
    }

    input:hover {
        border: 1px solid #7336ff !important;
    }

    input:focus {
        border: 1px solid #7336ff !important;
    }

    input:active {
        border: 1px solid #7336ff !important;
    }

    .inscription-form-container .form-group {
        margin-bottom: 9px !important;
    }

    <blade media|%20(max-width%3A%20991px)%20%7B>.inscription-form-container h1 {
        white-space: pre-wrap;
        width: 100%;
        margin-bottom: 20px;
        line-height: 1.3;
        font-size: 25px;
        overflow: hidden;
        width: none !important;
        padding: none !important;
        margin: none !important;
    }
    }

    .inscription_success p {
        font-size: 20px !important;
    }

    .inscription_success h6 {
        font-size: 29px !important;
    }


    :root {
        --card-line-height: 1.2em;
        --card-padding: 1em;
        --card-radius: 0.5em;
        --color-green: #<?php echo $mainColor ?>;
        --color-gray: #efefef;
        --color-dark-gray: #c4d1e1;
        --radio-border-width: 1px;
        --radio-size: 1.5em;
    }


    .card {
        background-color: #fff;
        border-radius: var(--card-radius);
        position: relative;
    }

    .card:hover {
        box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.15);
    }

    .radio {
        visibility: hidden;
        background: transparent;
        font-size: inherit;
        margin: 0;
        position: absolute;
        right: calc(var(--card-padding) + var(--radio-border-width));
        top: calc(var(--card-padding) + var(--radio-border-width));
    }



    .plan-details {
        border: var(--radio-border-width) solid var(--color-gray);
        border-radius: var(--card-radius);
        cursor: pointer;
        display: flex;
        align-items: center;
        padding: var(--card-padding);
        transition: border-color 0.2s ease-out;
    }

    .card:hover .plan-details {
        border-color: var(--color-green);
    }

    .radio:checked~.plan-details {
        border-color: var(--color-green);
    }

    .radio:focus~.plan-details {
        box-shadow: 0 0 0 2px var(--color-dark-gray);
    }

    .radio:disabled~.plan-details {
        color: var(--color-dark-gray);
        cursor: default;
    }

    .radio:disabled~.plan-details .plan-type {
        color: var(--color-dark-gray);
    }

    .card:hover .radio:disabled~.plan-details {
        border-color: var(--color-gray);
        box-shadow: none;
    }

    .card:hover .radio:disabled {
        border-color: var(--color-gray);
    }

    .card:hover .plan-type {
        color: var(--color-green);
        /* font-size: 1.5rem;
		font-weight: bold;
		line-height: 1em; */
    }

    .radio:checked~.plan-details .plan-type {
        color: var(--color-green);

    }


    .plan-cost {
        font-size: 2.5rem;
        font-weight: bold;
        padding: 0.5rem 0;
    }

    .slash {
        font-weight: normal;
    }

    .plan-cycle {
        font-size: 2rem;
        font-variant: none;
        border-bottom: none;
        cursor: inherit;
        text-decoration: none;
    }

    .hidden-visually {
        border: 0;
        clip: rect(0, 0, 0, 0);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        white-space: nowrap;
        width: 1px;
    }

    .av-img {
        width: 30px !important;
        margin: 0 !important;
        margin-bottom: 0 !important;
    }

    .no-shodow {
        box-shadow: none !important;
    }
</style>