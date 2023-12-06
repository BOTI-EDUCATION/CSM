<?php

use Models\RH\Candidat;
use Models\RH\CV;
use Models\RH\JobOffer;
use Models\User;


/**
 * Controller Class 
 */

class ContentController
{
    function __construct()
    {
        Session::getInstance()->requireLogin(true);
        if (Request::isPost()) {

            $_SESSION['flash_error'] = NULL;
            $_SESSION['previous_post'] = NULL;
        }
    }



    public function index()
    {
        $jobs = JobOffer::getList(array('order' => array('DatePublication' => false)));
        $candidats = Candidat::getCount();
        $candidats_qualifier = CV::getCount();
        return loadView('recrute/main', [
            'jobs' => $jobs,
            'candidats' => $candidats,
            'candidats_qualifier' => $candidats_qualifier,
            'css' => ['/css/pages/general/main.css']
        ]);
    }



    public function candidats()
    {
        $offer = isset($_GET['offer']) && $_GET['offer'] ? JobOffer::where(['Alias' => $_GET['offer']])->first()  : null;
        $where = [];
        $where[] = 'Validation IS NULL';
        if ($offer) {
            $where['Offre'] = $offer->get('ID');
        }
        $condidats = Candidat::getList(['where' => $where, 'order' => ['Date' => false]]);
        return loadView('recrute/candidats', [
            'condidats' => $condidats,
            'offer' => $offer,
            'css' => ['/css/pages/general/main.css']
        ]);
    }



    public function candidat_modul($id = null)
    {
        $candidat =  new Candidat($id);
        loadView('recrute/candidat_modul', array(
            'candidat' => $candidat,
        ), 'null_layout');
    }

    public function cv_modal($id = null)
    {
        $cv =  new \Models\RH\CV($id);
        loadView('recrute/cv_modal', array(
            'cv' => $cv,
        ), 'null_layout');
    }

    public function delete_condidat($id = null)
    {
        $candidat = new Candidat($id);
        $candidat->delete();

        Models\ActionLog::catchLog("Recrute: Suprimer d'une Candidature de " . ($candidat->Nom . '' . $candidat->Prenom), $candidat);
        return URL::redirect(URL::admin('candidats'));
    }


    public function candidat_item($id = null)
    {
        $is_new = $id ? false : true;

        if (Request::isPost()) {

            if (isset($_POST['candidat']) && $_POST['candidat']) {
                $candidat  = new Candidat($_POST['candidat']);
                $candidat
                    ->setJson('Validation', [
                        'date' => date('Y-m-d H:i:s'),
                    ])
                    ->set('ValidationUser', Session::user()->get('ID'));
                $candidat->save();
            }

            $cv  = new Models\RH\CV();
            $cv
                ->set('Candidat', $candidat)
                ->set('Nom', $_POST['nom'])
                ->set('Prenom', $_POST['prenom'])
                ->set('CIN', $_POST['cin'])
                ->set('Homme', $_POST['sexe'] == 'homme' ? 1 : 0)
                ->set('Pays', $_POST['pays'])
                ->set('Ville', $_POST['ville'])
                ->set('Experience', $_POST['experience'])
                ->set('Remarques', $_POST['remarques'])
                ->set('Poste', $_POST['poste'])
                ->set('Appreciation', $_POST['appreciation'])
                ->set('DernierExperience', $_POST['dernierexperience'])
                ->set('DiplomeEtablissement', $_POST['diplomeetablissement'])
                ->set('Secteur', isset($_POST['secteur']) ? $_POST['secteur'] : null)
                ->set('Specialite', isset($_POST['specialite']) ? $_POST['specialite'] : null)
                ->set('Enseignant', isset($_POST['enseignant']) && $_POST['enseignant'] ? 1 : null)
                ->setJson('MatiereEnseigne', isset($_POST['matiere_enseigne']) ? $_POST['matiere_enseigne'] : null)
                ->setJson('Cycles', isset($_POST['cycles']) ? $_POST['cycles'] : null)
                ->set('DateNaissance', $_POST['datenaissance'])
                ->set('Adresse', $_POST['adresse'])
                ->setJson('Tags',  explode(' ', $_POST['tags']))
                ->set('GSM', $_POST['tel'])
                ->set('Email', $_POST['email'])
                ->set('CV', $_POST['origin_cv'])
                ->set('Date', date('Y-m-d h:i:s'))
                ->set('User', Session::user()->get('ID'));

            if ($_FILES['cv']) {
                if ($_FILES['cv']['error'] == 0) {

                    $cv->set('CV',  Upload::storeImage('cv', Config::get('path-offers')));
                }
            }
            if ($_FILES['diplome']) {
                if ($_FILES['diplome']['error'] == 0) {

                    $cv->set('Diplome',  Upload::storeImage('diplome', Config::get('path-offers')));
                }
            }

            $cv->save();

            Models\ActionLog::catchLog("Recrute: " . ($id ? 'Validation' : 'Creation') . " d'une Candidature de " . ($cv->Nom . '' . $cv->Prenom), $cv);
            return URL::redirect(URL::admin('recrute/cvs'));
        }

        $candidat = $id ?  new Candidat($id) : null;
        loadView('recrute/cv_form', array(
            'candidat' => $candidat,
            'is_new' => $is_new,
            'id' => $id,
        ));
    }



    public function validate_condidat($id = null)
    {
        $candidat = new Candidat($id);
        $candidat
            ->set('Validation', true)
            ->set('ValidationUser', Session::user()->get('ID'));
        $candidat->save();
        return URL::redirect(URL::admin('candidats'));
    }

    public function remove_validate_condidat($id = null)
    {
        $candidat = new Candidat($id);
        $candidat
            ->set('Validation', null)
            ->set('ValidationUser', Session::user()->get('ID'));
        $candidat->save();
        return URL::redirect(URL::admin('candidats'));
    }


    function offres()
    {
        $jobs = JobOffer::getList(array('order' => array('DatePublication' => false)));
        return loadView('recrute/jobs', [
            'jobs' => $jobs,
            'css' => ['/css/pages/general/main.css']
        ]);
    }


    function new_offres($id = null)
    {
        $offer = $id ?  new JobOffer($id) : new JobOffer();
        $isUpdate = $id ? true : false;
        if (Request::isPost()) {
            $offer->Title = Request::get('title');
            $offer->Alias = implode('-', explode(' ', Request::get('title')));
            $offer->Presentation = Request::get('presentation');
            $offer->DatePublication = Request::get('datepublication');
            $offer->DateFin = Request::get('datefin');
            $offer->Date = date('Y:m:d H:i:s');
            $offer->Activation = Request::get('activation');
            $offer->User = Session::user()->ID;
            if ($_FILES['offerid'] && !$id) {
                // $offer->set('Image',  Upload::storeImage('offerid', Config::get('path-offers')));
                Tools::compressImage($_FILES['file']['tmp_name'], $_FILES['file']['tmp_name'], 75);
                $offer->set('Image',  GoogleStorage::storeImage('offerid', \Config::get('path-offers')));
            }
            $offer->save();
            return URL::redirect(URL::admin('recrute/offres'));
        }

        return loadView('recrute/form', [
            'offer' => $offer,
            'js' => [
                "https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.11/handlebars.js",
                "vendors/js/medium-editor/medium-editor.min.js",
                "https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.1/js/jquery.fileupload.min.js",
                "vendors/js/medium-editor/medium-editor-insert-plugin.js",

            ],
            'css' => [
                "vendors/css/medium-editor/themes/default.min.css",
                "vendors/css/medium-editor/medium-editor.min.css",
                "https://cdnjs.cloudflare.com/ajax/libs/medium-editor-insert-plugin/2.5.0/css/medium-editor-insert-plugin-frontend.min.css",
                "https://cdnjs.cloudflare.com/ajax/libs/medium-editor-insert-plugin/2.4.1/css/medium-editor-insert-plugin.css"
            ],
            'isUpdate' => $isUpdate,
        ]);
    }

    function toggle_offre($id = null)
    {
        $offer = new JobOffer($id);
        $offer->set('Activation', ($offer->get('Activation') ? null : 1))->save();
        return URL::redirect(URL::admin('recrute'));
    }

    function delete_offre($id)
    {
        $offer = new JobOffer($id);
        $offer->delete();
        return URL::redirect(URL::admin('recrute/offres'));
        exit(json_encode(['msg' => 'offre supprimer avec succées']));
    }


    function cvs()
    {
        $where = [];
        $annee_experience = isset($_GET['annee_experience']) && $_GET['annee_experience'] && $_GET['annee_experience'] != 'tous'  ? $_GET['annee_experience'] : null;
        $secteur = isset($_GET['secteur']) && $_GET['secteur'] && $_GET['secteur'] != 'secteur' ? $_GET['secteur'] : null;
        $age = null;
        $tag = null;
        $where['Experience'] = $annee_experience;
        $where['Secteur'] = $secteur;
        if (isset($_GET['age']) && $_GET['age'] && $_GET['age'] != 'age') {
            $age = $_GET['age'];
            list($year, $month, $day) = explode("-", date("Y-m-d"));
            $range = $year - $age;
            $date_of_birth = date('Y', strtotime("{$range}-{$month}-{$day}"));
            $where[] =  "DateNaissance LIKE '%"  . $date_of_birth . "%'";
        }
        if (isset($_GET['tag']) && $_GET['tag'] && $_GET['tag'] != 'tag') {
            $tag = $_GET['tag'];
            $where[] =  "Tags LIKE '%"  . $tag . "%'";
        }
        $cvs = CV::getList(['where' => $where]);
        $secteurs = CV::getSecteurs();
        $tags = CV::getTags();
        if (isset($_GET['export_excel'])) {
            ini_set('memory_limit', '-1');
            set_time_limit(1800);
            header('Content-Type: text/plain; charset=utf8');
            loadLib('phpexcel');

            if (!PHPExcel_Settings::setLocale('fr_FR'))
                echo 'Locale not set ' . PHP_EOL;

            $objPHPExcel = new PHPExcel();

            // $objPHPExcel->getSheet(); // at index 0
            $objPHPExcel->getActiveSheet(); // Same same, by default active sheet is at 0

            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Abdellah');

            $objPHPExcel->getActiveSheet()
                ->setCellValue('A1', 'Nom')
                ->setCellValue('B1', 'Prenom')
                ->setCellValue('C1', 'Sexe')
                ->setCellValue('D1', 'Téléphone')
                ->setCellValue('E1', 'CIN')
                ->setCellValue('F1', 'Email');


            $startRow = 2;

            $i = 0;
            while ($i < count($cvs)) {
                $currentRow = $startRow + $i;
                $objPHPExcel->getActiveSheet()
                    ->setCellValue('A' . $currentRow, $cvs[$i]->get('Nom'))
                    ->setCellValue('B' . $currentRow, $cvs[$i]->get('Prenom'))
                    ->setCellValue('C' . $currentRow, $cvs[$i]->get('Homme'))
                    ->setCellValue('D' . $currentRow, $cvs[$i]->get('GSM')  == 1 ? 'Homme' : 'Femme')
                    ->setCellValue('E' . $currentRow, $cvs[$i]->get('CIN'))
                    ->setCellValue('F' . $currentRow, $cvs[$i]->get('CIN'))
                    ->setCellValue('F' . $currentRow, $cvs[$i]->get('Email'));
                $i++;
            }

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

            $fileName = 'cvs_';
            // redirect output to client browser
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $fileName . date('Ymd-His') . '.xlsx"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            // return URL::admin('candidats/rh_cvs');
            exit;
        }
        loadView('recrute/cvs', array(
            'cvs' => $cvs,
            'age' => $age,
            'tag' => $tag,
            'tags' => $tags,
            'secteur' => $secteur,
            'annee_experience' => $annee_experience,
            'secteurs' => $secteurs,
            'css' => ['/css/pages/general/main.css']
        ));
    }
}

/* Router options */
$action = Request::getArgs(0) ? Request::getArgs(0) : 'index';
$id = Request::getArgs(1);
$controller = new ContentController;

if (!method_exists('ContentController', $action))
    $controller->index();


$controller->{$action}($id);
