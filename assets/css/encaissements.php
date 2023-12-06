<?php

use Models\Inscription;
use Models\Niveau;
use Models\Promotion;
use Models\Classe;
use Models\FIN\EncaissementRubriqueInscription;
use Models\FIN\TempEncaissements;

// ControllerRole('admin', 'resp_financier', 'agent_plus', 'direction', 'agent_financier');

loadLib('tcpdf');
class MYPDF extends TCPDF
{
	public function Header()
	{
		$headerData = $this->getHeaderData();
		$this->SetFont('helvetica', 'B', 10);
		$this->writeHTML($headerData['string']);
	}
}

Session::getInstance()->requireLogin(true);
// POSTs
if (Request::isPost() && isset($_POST['op'])) {
	switch ($_POST['op']) {
		case 'relance-paiement':

			try {
				$inscription = new Models\Inscription($_POST['inscription']);
			} catch (Exception $e) {
				throw new Exception("Not Found Item", 404);
			}

			$eleve = $inscription->get('Eleve');

			$notifLabel = $_POST['titre'];
			$notifMessage = $_POST['message'];

			fcm_send($eleve->simpleTokens(), $notifLabel, $notifMessage, 'eleve_paiements');

			$inscription = $eleve->getInscription();
			$notif = new Models\Notif();
			$notif
				->set('Label', $notifLabel)
				->set('Inscription', $inscription)
				->set('Message', $notifMessage)
				->set('Date', date('Y-m-d H:i:s'));

			$notif->save();

			$get = array();
			$get['promotion'] = $inscription->get('Promotion')->get('ID');
			$get['eleve'] = $inscription->get('Eleve')->get('ID');

			URL::redirect(URL::admin('encaissements/etat_encaissement?' . http_build_query($get)));

		case 'filtre_etat_compte':

			$filtreEtatCompteDataHolder = array(
				'services' => null,
				'comptes' => null,
				'periode' => false,
				'filtre_periode' => false,
			);

			if ($_POST['services'] != 'all')
				$filtreEtatCompteDataHolder['services'] = $_POST['services'];

			if ($_POST['comptes'] != 'all')
				$filtreEtatCompteDataHolder['comptes'] = $_POST['comptes'];

			$filtreEtatCompteDataHolder['periode'] = $_POST['periode'];
			$filtreEtatCompteDataHolder['filtre_periode'] = isset($_POST['filtre_periode']);

			$_SESSION['filtreEtatCompteDataHolder'] = serialize($filtreEtatCompteDataHolder);

			URL::redirect(URL::admin('encaissements/etat_service'));

		case 'filtre':

			$filtreEncaissementDataHolder = array(
				'classe' => null,
				'mode' => null,
				'niveau' => null,
				'site' => null,
				'zone_recherche' => null,
				'periode' => false,
				'filtre_periode' => false,
				'promotion' => null,
				'user' => null,
				'service' => $service,
				'compte_bancaire' => null,
			);

			if ($_POST['classe'] != 'all')
				$filtreEncaissementDataHolder['classe'] = $_POST['classe'];

			if ($_POST['niveau'] != 'all')
				$filtreEncaissementDataHolder['niveau'] = $_POST['niveau'];



			if ($_POST['site'] != 'all')
				$filtreEncaissementDataHolder['site'] = $_POST['site'];

			if ($_POST['mode'] != 'all')
				$filtreEncaissementDataHolder['mode'] = $_POST['mode'];

			if ($_POST['promotion'] != 'all')
				$filtreEncaissementDataHolder['promotion'] = $_POST['promotion'];

			if ($_POST['user'] != 'all')
				$filtreEncaissementDataHolder['user'] = $_POST['user'];

			if ($_POST['service'] != 'all')
				$filtreEncaissementDataHolder['service'] = $_POST['service'];

			if ($_POST['compte_bancaire'] != 'all')
				$filtreEncaissementDataHolder['compte_bancaire'] = $_POST['compte_bancaire'];

			if ($_POST['zone_recherche'])
				$filtreEncaissementDataHolder['zone_recherche'] = $_POST['zone_recherche'];


			$filtreEncaissementDataHolder['periode'] = $_POST['periode'];
			$filtreEncaissementDataHolder['filtre_periode'] = isset($_POST['filtre_periode']);



			$filtreEncaissementDataHolder['montant_min'] = $_POST['montant_min'];
			$filtreEncaissementDataHolder['montant_max'] = $_POST['montant_max'];
			$filtreEncaissementDataHolder['filtre_range_montant'] = isset($_POST['filtre_range_montant']);
			$_SESSION['filtreEncaissementDataHolder'] = serialize($filtreEncaissementDataHolder);

			URL::redirect(URL::admin('encaissements'));

		case 'journalier_filtre':

			$filtreEncaissementDataHolder = array(
				'classe' => null,
				'mode' => null,
				'niveau' => null,
				'zone_recherche' => null,
				'periode' => false,
				'filtre_periode' => false,
				'promotion' => null,
				'user' => null,
				'compte_bancaire' => null,
			);

			if ($_POST['classe'] != 'all')
				$filtreEncaissementDataHolder['classe'] = $_POST['classe'];

			if ($_POST['niveau'] != 'all')
				$filtreEncaissementDataHolder['niveau'] = $_POST['niveau'];

			if ($_POST['mode'] != 'all')
				$filtreEncaissementDataHolder['mode'] = $_POST['mode'];

			if ($_POST['promotion'] != 'all')
				$filtreEncaissementDataHolder['promotion'] = $_POST['promotion'];

			if ($_POST['user'] != 'all')
				$filtreEncaissementDataHolder['user'] = $_POST['user'];

			if ($_POST['compte_bancaire'] != 'all')
				$filtreEncaissementDataHolder['compte_bancaire'] = $_POST['compte_bancaire'];

			if ($_POST['zone_recherche'])
				$filtreEncaissementDataHolder['zone_recherche'] = $_POST['zone_recherche'];

			$filtreEncaissementDataHolder['periode'] = $_POST['periode'];
			$filtreEncaissementDataHolder['filtre_periode'] = isset($_POST['filtre_periode']);


			$_SESSION['filtreEncaissementDataHolder'] = serialize($filtreEncaissementDataHolder);

			URL::redirect(URL::admin('encaissements/etat_journalier'));

		case 'encaisser':

			$pk = (isset($_POST['id']) && $_POST['id']) ? $_POST['id'] : null;

			try {
				$encaissement = new Models\FIN\Encaissement($pk);
			} catch (Exception $e) {
				exit('Element introuvable');
			}

			$encaissement
				->set('Encaisse', date('Y-m-d H:i:s'))
				->set('EncaisseDate', $_POST['date'])
				->set('NumOperation', $_POST['operation'])
				->set('EncaisseBy', Session::getInstance()->getCurUser())
				->save();

			$_SESSION['numRecuAdded'] = $encaissement->get('NumRecu');

			URL::redirect(URL::admin('encaissements'));

		case '_encaissement':

			$pk = (isset($_POST['id']) && $_POST['id']) ? $_POST['id'] : null;

			try {
				$encaissement = new Models\FIN\Encaissement($pk);
			} catch (Exception $e) {
				exit('Element introuvable');
			}

			$promotion = new Models\Promotion($_POST['promotion']);
			$eleve = new Models\Eleve($_POST['eleve']);

			$inscriptions = Models\Inscription::getList(array('where' => array('Promotion' => $promotion->get('ID'), 'Eleve' => $eleve->get('ID'))));
			if ($inscriptions)
				$inscription = $inscriptions[0];

			$paiementmode = new Models\FIN\PaiementMode($_POST['paiementmode']);

			$paiementDetailID = null;
			if ($paiementmode->get('Alias') == 'virement') {
				$paiementvirementdetail = new Models\FIN\PaiementVirementDetail();

				$paiementvirementdetail
					->set('NumCompte', $_POST['numcompte'])
					->set('NumPiece', $_POST['numpiece'])
					->save();

				$paiementDetailID = $paiementvirementdetail->get('ID');
			} elseif ($paiementmode->get('Alias') == 'cheque') {

				$paiementchequedetail = new Models\FIN\PaiementchequeDetail();

				$paiementchequedetail
					->set('Banque', $_POST['banque'])
					->set('NumCheque', $_POST['numcheque'])
					->set('Tireur', $_POST['tireur'])
					->set('Client', $_POST['client'])
					->save();

				$paiementDetailID = $paiementchequedetail->get('ID');
			}

			$encaissement
				->set('Inscription', $inscription)
				->set('Promotion', $promotion)
				->set('PaiementMode', $paiementmode->get('Alias'))
				->set('Partenaire', $_POST['partenaire'])
				->set('DetailMode', $paiementDetailID)
				->set('UserBy', Session::getInstance()->getCurUser())
				->set('NumRecu', $_POST['numrecu'])
				->set('Remarque', $_POST['remarque'])
				->set('DatePaiement',  isset($_POST['datepaiement']) ? $_POST['datepaiement'] : date('Y-m-d'))
				->set('Date', date('Y-m-d H:i:s'))
				->save();

			if (isset($_POST['encaisse'])) {
				$encaissement
					->set('Encaisse', date('Y-m-d H:i:s'))
					->set('EncaisseBy', Session::getInstance()->getCurUser());
			}

			$montantTotal = 0;
			foreach ($_POST['montants'] as $ind => $value) {
				$encaissementLigne = new Models\FIN\EncaissementLigne();
				$encaissementLigne
					->set('Encaissement', $encaissement)
					->set('Montant', $_POST['montants'][$ind])
					->set('EncaissementRubrique', $_POST['encaissementrubriques'][$ind]);

				if ($encaissementLigne->get('EncaissementRubrique')->get('Mensuel'))
					$encaissementLigne->set('Mois', $_POST['mois'][$ind]);


				$montantTotal += $encaissementLigne->get('Montant');

				$encaissementLigne->save();
			}

			$encaissement
				// ->set('Montant', $montantTotal)
				->save();

			$lignes = $encaissement->encaissementLignes();
			$montantTotal = 0;
			foreach ($lignes as $ligne) {
				$montantTotal += $ligne->get('Montant');
			}

			$encaissement
				->set('Montant', $montantTotal)
				->save();

			if (!$pk) {

				$promotion = Models\Promotion::promotion_actuelle();
				if ($promotion->recuEncaissement() == $encaissement->get('NumRecu')) {
					$promotion->recuEncaissement(true);
				}
				URL::redirect(URL::admin('encaissements/' . $encaissement->get('ID') . '/recu'));
			}

			URL::redirect(URL::admin('encaissements'));
		case 'facture_global_increment_nemuro':

			$params = \Models\Params::getByAlias('encaissement_facture_global_start');
			$params->set('Value', $params->get('Value') + 1);
			$params->save();

		case 'edit-encaissement':
			$pk = (isset($_POST['id']) && $_POST['id']) ? $_POST['id'] : null;

			try {
				$encaissement = new Models\FIN\Encaissement($pk);
			} catch (Exception $e) {
				exit('Element introuvable');
			}
			$paiementmode = new Models\FIN\PaiementMode($_POST['paiementmode']);

			if ($encaissement->getDetailmode()) {
				$encaissement->getDetailmode()->delete();
			}

			$paiementDetailID = null;
			if ($paiementmode->get('Alias') == 'virement') {
				$paiementvirementdetail = new Models\FIN\PaiementVirementDetail();
				$paiementvirementdetail
					->set('NumCompte', $_POST['numcompte'])
					->set('NumPiece', $_POST['numpiece'])
					->save();

				$paiementDetailID = $paiementvirementdetail->get('ID');
			} elseif ($paiementmode->get('Alias') == 'cheque') {
				$paiementchequedetail = new Models\FIN\PaiementchequeDetail();
				$paiementchequedetail
					->set('Banque', $_POST['banque'])
					->set('NumCheque', $_POST['numcheque'])
					->set('Tireur', $_POST['tireur'])
					->save();

				$paiementDetailID = $paiementchequedetail->get('ID');
			}
			$encaissement
				->set('PaiementMode', $paiementmode->get('Alias'))
				->set('Partenaire', $_POST['partenaire'])
				->set('DetailMode', $paiementDetailID)
				->set('UserBy', Session::getInstance()->getCurUser())
				->set('Remarque', $_POST['remarque'])
				->set('EcheanceCheque', $_POST['date_echeance'])
				->set('Date', date('Y-m-d H:i:s'))
				->save();
			//log
			Models\ActionLog::catchLog(
				"edited 'Encaissement'  of " . $encaissement->get('Inscription')->get('Eleve')->get('User')->getNomComplet(),
				$encaissement
			);
			exit(json_encode(array(
				'msg' => 'l\'encaissement a ètè bien modifié',
			)));

		case 'encaissement':

			set_time_limit(0);
			\DB::begin();

			try {
				$encaissement = new Models\FIN\Encaissement();
			} catch (Exception $e) {
				exit('Element introuvable');
			}

			$eleve = new Models\Eleve($_POST['eleve']);
			$promotion = new Models\Promotion($_POST['promotion']);
			$inscription = $eleve->getInscription($promotion);


			// mode paiement
			$paiementmode = new Models\FIN\PaiementMode($_POST['paiementmode']);
			$compteBancaire  =  null;
			if (isset($_POST['compte_bancaire'])) {
				$compteBancaire =   new Models\FIN\CompteBancaire($_POST['compte_bancaire']);
			}

			$paiementDetailID = null;
			$paiementDetail = [
				'mode' => 'virement',
			];

			if ($paiementmode->get('Alias') == 'virement') {

				$paiementDetail['mode'] = $paiementmode->get('Alias');
				$paiementDetail['modNumComptee'] =  $_POST['numcompte'];
				$paiementDetail['NumPiece'] = $_POST['numpiece'];

				$paiementvirementdetail = new Models\FIN\PaiementVirementDetail();
				$paiementvirementdetail
					->set('NumCompte', $_POST['numcompte'])
					->set('NumPiece', $_POST['numpiece'])
					->save();

				$paiementDetailID = $paiementvirementdetail->get('ID');
			} elseif ($paiementmode->get('Alias') == 'cheque') {

				$paiementDetail['mode'] = $paiementmode->get('Alias');
				$paiementDetail['NumCheque'] = $_POST['numpiece'];
				$paiementDetail['Tireur'] = $_POST['tireur'];

				$paiementchequedetail = new Models\FIN\PaiementchequeDetail();
				$paiementchequedetail
					->set('Banque', $_POST['banque'])
					->set('NumCheque', $_POST['numcheque'])
					->set('Tireur', $_POST['tireur'])
					->save();
				$paiementDetailID = $paiementchequedetail->get('ID');
			} elseif ($paiementmode->get('Alias') == 'tpe') {
				$paiementDetail['mode'] = $paiementmode->get('Alias');
				$paiementDetail['NumTransaction'] =  $_POST['numtransaction'];

				$paiementchequedetail = new Models\FIN\PaiementTpeDetail();
				$paiementchequedetail
					->set('NumTransaction', $_POST['numtransaction'])
					->save();
				$paiementDetailID = $paiementchequedetail->get('ID');
			}

			// new encaissements
			$encaissement
				->set('Inscription', $inscription)
				->set('Promotion', $promotion)
				->set('PaiementMode', $paiementmode->get('Alias'))
				->set('CompteBancaire', $compteBancaire ?  $compteBancaire->getKey() : null)
				->setJson('PaiementDetail', $paiementDetail)
				->set('Partenaire',  $compteBancaire ? $compteBancaire->get('Etablissement')->getKey() : null)
				->set('DetailMode', $paiementDetailID)
				->set('UserBy', Session::getInstance()->getCurUser())
				->set('NumRecu', $promotion->recuEncaissement(true))
				->set('Remarque', $_POST['remarque'])
				->set('DatePaiement', isset($_POST['datepaiement']) ? $_POST['datepaiement'] : date('Y-m-d'))
				->set('EcheanceCheque', $_POST['date_echeance'])
				->set('Date', date('Y-m-d H:i:s'));


			if (isset($_FILES['file']) && $_FILES['file']['error'] != UPLOAD_ERR_NO_FILE) {
				$fileError = GoogleStorage::checkUpload('file');
				errorPage($fileError);
				$encaissement->set('File', GoogleStorage::store('file', Config::get('path-encaissements')));
			}

			$encaissement->save();
			// save

			// save encaissments lignes
			$can_manually_edit_amount_encaissment_money  = isAllowed('can_manually_edit_amount_encaissment_money');
			$grille = Models\FIN\RubriquePrice::grille();
			$niveau = $inscription->get('Niveau');
			$months_list = $promotion->months()->zero_list;
			$selectedServices = 0;
			foreach ($months_list as $month_key => $m) {

				$rubriques = Models\FIN\EncaissementRubriqueInscription::rubriques($inscription, $month_key);
				foreach ($rubriques  as $rubrique) {

					$rubriqueInscription = Models\FIN\EncaissementRubriqueInscription::inscriptionHasRubrique($inscription, $rubrique, $month_key);

					if (isset($_POST['month_' . $month_key . '_service_' . $rubrique->get('ID')])) {
						$montant =  Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($inscription, $month_key, $rubrique);

						if ($can_manually_edit_amount_encaissment_money) {
							$montant =  $_POST["montant_month_" . $month_key . "_service_" . $rubrique->get('ID')];
						}

						if ($montant) {
							$selectedServices++;
							$encaissementLigne = new Models\FIN\EncaissementLigne();
							$encaissementLigne
								->set('Inscription', $inscription)
								->set('Encaissement', $encaissement)
								->set('Montant', $montant)
								->set('EncaissementRubrique', $rubrique->get('ID'))
								->set('Mois', $month_key);

							$encaissementLigne->save();

							$rubriqueInscription->set('MontantEncaisse', ($rubriqueInscription->get('MontantEncaisse') ?: 0) + $montant);
							$rubrique_fnc_array = $rubriqueInscription->getArray('Encaissements') ?: array();

							array_push($rubrique_fnc_array, $encaissement->ID);

							$rubriqueInscription->setJson(
								'Encaissements',
								$rubrique_fnc_array

							);

							$rubriqueInscription->save();
						}
					}
				}
			}
			// end

			if ($selectedServices == 0) {
				flashMessage()->success("Veuillez sélectionner au moins un service");
				URL::redirect(URL::admin('encaissements/add/' . $inscription->get('ID')));
				exit();
			}

			$lignes = $encaissement->encaissementLignes();
			$montantTotal = 0;
			foreach ($lignes as $ligne) {
				$montantTotal += $ligne->get('Montant');
			}

			$encaissement
				->set('Montant', $montantTotal)
				->save();

			DB::commit();
			//log
			Models\ActionLog::catchLog(
				"create 'Encaissement'  of " . $inscription->get('Eleve')->get('User')->getNomComplet(),
				$encaissement
			);

			// if (isset($_POST['print_recu'])) {
			URL::redirect(URL::admin('encaissements/' . $encaissement->get('ID') . '/recu'));
			// }
			//URL::back();

		case 'notif_modal':
			if (isset($_POST['ins'])) {
				$inscriptions = array();
				$montants  = array();
				foreach ($_POST['ins'] as $id) {
					try {
						$inscription = new Models\Inscription($id);
					} catch (\Throwable $th) {
						continue;
					}

					$userTokens = $inscription->get('Eleve')->simpleTokens();
					if ($userTokens) $inscriptions[] = $inscription;

					$montants[$id] = Request::get('ins_' . $id);
				}

				if (empty($inscriptions)) {
					http_response_code(404);
					exit(json_encode(array(
						'msg' => "Nous n'avons trouvé aucun parent pour envoyer des notifications.",
					)));
				} else {
					exit(json_encode(array(
						'msg' => "Nous traitons votre demande.",
						'html' =>  getView("encaissements/etat-impayes-notification-modal", array(
							'inscriptions' => $inscriptions,
							'montants' => $montants,
						), "null_layout")
					)));
				}
			}
			break;

		case 'notif_parent_modal':

			if (isset($_POST['parents'])) {
				$parents = array();
				$montants  = array();
				foreach ($_POST['parents'] as $id) {
					try {
						$parent = new Models\Parentt($id);
					} catch (\Throwable $th) {
						continue;
					}

					$userTokens = $parent->User->simpleToken();
					if ($userTokens) $parents[] = $parent;

					$montants[$id] = Request::get('montant_' . $id);
				}

				if (empty($parents)) {
					http_response_code(404);
					exit(json_encode(array(
						'msg' => "Nous n'avons trouvé aucun parent pour envoyer des notifications.",
					)));
				} else {
					exit(json_encode(array(
						'msg' => "Nous traitons votre demande.",
						'html' =>  getView("encaissements/etat-impayes-notification-modal", array(
							'parents' => $parents,
							'montants' => $montants,
						), "null_layout")
					)));
				}
			}
			break;
		case 'send_parent_notifs':
			if (isset($_POST['parents'])) {
				foreach ($_POST['parents'] as $id) {
					try {
						$parent = new Models\Parentt($id);
					} catch (\Throwable $th) {
						continue;
					}
					$tokens = $parent->get('User')->simpleToken();
					if ($tokens) {
						$nom = $parent->get('User')->getNomComplet();
						$montant = Request::get('montant_' . $id);
						$title = 'Retard de paiement';
						if (Request::get('message')) {
							$message = str_replace('%NomParent%', $nom, Request::get('message'));
							$message = str_replace('%MontantTotal%', $montant, $message);
							fcm_send($tokens, $title, $message);

							// save notif
							$notif = new Models\Notif();
							$notif
								->set('Label', $title)
								->set('Message', $message)
								->setJson('Users',   [$parent->get('User')->getKey()])
								->set('Date', date('Y-m-d H:i:s'));
							$notif->save();
						}
					}
				}

				exit(json_encode(array(
					'msg' => "Les notifications ont été envoyées avec succès.",
				)));
			}

		case 'send_notifs':
			if (isset($_POST['ins'])) {
				foreach ($_POST['ins'] as $id) {
					try {
						$inscription = new Models\Inscription($id);
					} catch (\Exception $ex) {
						continue;
					}

					$tokens = $inscription->get('Eleve')->simpleTokens();
					if ($tokens) {
						$nom = $inscription->get('Eleve')->get('User')->getNomComplet();
						$montant = Request::get('ins_' . $id);
						$title = 'Retard de paiement';
						if (Request::get('message')) {
							$message = str_replace('%prenom_eleve%', $nom, Request::get('message'));
							$message = str_replace('%montant_total%', $montant, $message);
							fcm_send($tokens, $title, $message);

							// save notif
							$notif = new Models\Notif();
							$notif
								->set('Label', $title)
								->set('Message', $message)
								->set('Inscription', $inscription)
								->setJson('Users',  array_values(array_map(fn ($p) => $p->User->ID, $inscription->Eleve->parents())))
								->set('Date', date('Y-m-d H:i:s'));
							$notif->save();
						}
					}
				}
				exit(json_encode(array(
					'msg' => "Les notifications ont été envoyées avec succès.",
				)));
			}
			break;
	}
}

/**
 * Controller Class
 */

class ContentController
{

	function __construct()
	{
		Session::getInstance()->requireLogin(true);
		$this->auth_user =  Session::getInstance()->getCurUser();
	}


	function index()
	{

		if (Request::getArgs(0)) {
			try {
				$encaissement = new Models\FIN\Encaissement(Request::getArgs(0));
			} catch (Exception $e) {
				exit('Element introuvable');
			}

			if (Request::getArgs(1) == 'recu') {
				if ($encaissement->get('Inscriptions'))
					URL::redirect(URL::admin('encaissements/recu/' . Request::getArgs(0)));

				ini_set('memory_limit', '-1');
				set_time_limit(20);
				loadLib('tcpdf');
				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


				if (_school_alias == 'lesentier') {
					$fontArabicBold = TCPDF_FONTS::addTTFfont(_basepath . 'assets/fonts/ADOBEARABIC-BOLD.OTF', '', '', 96);
					$pdf->SetFont($fontArabicBold, '', 12, '', false);
				}

				// set document information
				$pdf->SetCreator(PDF_CREATOR);
				$pdf->SetAuthor('Nicola Asuni');
				$pdf->SetTitle('Recu');
				$pdf->SetSubject('TCPDF Tutorial');
				$pdf->setFont('dejavusans');
				$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

				// remove default header/footer
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);

				// set default monospaced font
				// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

				// set margins
				// $pdf->SetMargins(19, 70, 19, 0);

				// set auto page breaks
				$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

				// set image scale factor
				$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

				// ---------------------------------------------------------

				$pdf->AddPage('P', 'A4');
				$months_list = _zero_months_list;
				$lignesShowed = array();
				$lignesShowedParService = array();
				$lignes = $encaissement->encaissementLignes();
				$inscription = $encaissement->Inscription;
				$etablissement = $encaissement->Inscription->getSite()->Etablissement;

				foreach ($lignes as $ligne) {
					$service =  $ligne->get('EncaissementRubrique');
					$key =  ((!$service->get('Mensuel')  && $ligne->get('Mois') == 9) ||  !$ligne->get('Mois')) ? 0 : $ligne->get('Mois');
					if (!isset($lignesShowed[$key])) {
						$lignesShowed[$key] = array(
							'mois_key' => $ligne->get('Mois'),
							'mois' => $key != 0 ? $months_list[$ligne->get('Mois')] : 'Frais annuels',
							'services' =>  array()
						);
					}

					if (!isset($lignesShowedParService[$service->getKey()])) {
						$lignesShowedParService[$service->getKey()] = array(
							'mois_keys' => [],
							'mois' => [],
							'service' =>  $service,
							'montants' => []
						);
					}


					$lignesShowed[$key]['services'][] = array(
						'service' => $service,
						'montant' => $ligne->get('Montant'),
					);

					$lignesShowedParService[$service->getKey()]['mois_keys'][] = $ligne->get('Mois');
					$lignesShowedParService[$service->getKey()]['mois'][] =  $key != 0 ? $months_list[$ligne->get('Mois')] : 'Frais annuels';
					$lignesShowedParService[$service->getKey()]['montants'][] = \Tools::numberFormat($ligne->get('Montant'), 2);
				}

				$temp_path = _basepath . Config::get('admin') . '/pages/encaissements/impression/schools/' . Config::getSchool() . '/encaissements-recu.php';

				// define some HTML content with style
				$html = '';
				ob_start();
				if (file_exists($temp_path)) {
					include_once $temp_path;
				} else {
					include_once _basepath . Config::get('admin') . '/pages/encaissements/encaissements-recu.php';
				}
				$html = ob_get_contents();
				ob_clean();
				// echo $html;
				// exit;
				ob_start();
				// output the HTML content
				$pdf->writeHTML($html, true, false, true, false, '');

				$pdf->lastPage();
				// ---------------------------------------------------------
				ob_end_clean();

				//  recu
				// $save_folder_to = _basepath . "/assets/schools/" . _school_alias . "/files/fnc/recus/";
				// if (!file_exists($save_folder_to)) {
				// 	mkdir($save_folder_to, 0777, true);
				// }

				// $save_to = $save_folder_to . $encaissement->ID . '.pdf';
				// $pdf->Output($save_to, 'F');

				//Close and output PDF document


				$pdf->Output($encaissement->get('NumRecu') . '.pdf', 'I');
			}
		} elseif (isset($_GET['exportetat'])) {

			ini_set('memory_limit', '-1');
			header('Content-Type: text/plain; charset=utf8');

			set_time_limit(1800);
			loadLib('phpexcelsheet');
			$months_list = _zero_months_list;

			$query =  "SELECT Nom,Prenom, Label, Promotion, i.ID AS Inscription,i.Classe as Classe FROM ins_inscriptions i,gen_eleves e ,users u, gen_promotions p WHERE i.Eleve = e.ID and u.ID = e.User and i.Promotion = p.ID and p.Actuelle = 1";
			if (isset($_GET['new_promotion'])) {
				$query =  "SELECT Nom,Prenom, Label, Promotion, i.ID AS Inscription,i.Classe as Classe FROM ins_inscriptions i,gen_eleves e ,users u, gen_promotions p WHERE i.Eleve = e.ID and u.ID = e.User and i.Promotion = p.ID and p.InscriptionActive = 1";
			}

			$encaissements  = DB::reader($query);
			// print_r($lignes);exit;
			if (!PHPExcel_Settings::setLocale('fr_FR'))
				echo 'Locale not set ' . PHP_EOL;

			try {
				$objPHPExcel = new PHPExcel();
				// $objPHPExcel->getSheet(); // at index 0
				$sheet = $objPHPExcel->getActiveSheet(); // Same same, by default active sheet is at 0

				$col = 0;
				$startRow = 1;

				$sheet->getCellByColumnAndRow($col++, $startRow)->setValue('Nom Complet');
				$sheet->getCellByColumnAndRow($col++, $startRow)->setValue('Classe');
				$sheet->getCellByColumnAndRow($col++, $startRow)->setValue('Frais Inscription');

				foreach ($months_list as $key => $month) {
					$sheet->getCellByColumnAndRow($col++, $startRow)->setValue($month);
				}

				$i = 0;
				$startRow++;
				while ($i < count($encaissements)) {

					$currentRow = $startRow + $i;
					$col = 0;

					$ins =  new Models\Inscription($encaissements[$i]['Inscription']);
					$classe =  new Models\Classe($encaissements[$i]['Classe']);

					$sheet->getCellByColumnAndRow($col++, $currentRow)->setValue($encaissements[$i]['Nom'] . " " . $encaissements[$i]['Prenom']);
					$sheet->getCellByColumnAndRow($col++, $currentRow)->setValue($classe->get('Label'));


					$frais_inscription = Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($ins, 0, new Models\FIN\EncaissementRubrique(1));
					$sheet->getCellByColumnAndRow($col++, $currentRow)->setValue($frais_inscription);
					foreach ($months_list as $key => $month) {

						$frais = Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($ins, $key);

						$sheet->getCellByColumnAndRow($col++, $currentRow)->setValue($frais);
					}
					$i++;
				}
				$objPHPExcel->getActiveSheet()
					->getStyle('B' . $startRow . ':B' . count($encaissements))
					->getNumberFormat()
					->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME);

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);

				$fileName = 'Encaissements-';
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="' . $fileName . date('Y-m-d h:i:s') . '.xlsx"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
			} catch (Exception  $e) {
				echo $e->getMessage();
			}

			//URL::redirect(URL::admin('encaissements'));
		} elseif (isset($_GET['export'])) {
			ini_set('memory_limit', '-1');
			set_time_limit(1800);
			header('Content-Type: text/plain; charset=utf8');
			loadLib('phpexcelsheet');

			if (!PHPExcel_Settings::setLocale('fr_FR'))
				echo 'Locale not set ' . PHP_EOL;


			try {
				$objPHPExcel = new PHPExcel();

				// $objPHPExcel->getSheet(); // at index 0
				$objPHPExcel->getActiveSheet(); // Same same, by default active sheet is at 0


				$objPHPExcel->getActiveSheet()

					->setCellValue('A1', 'Nom Complet')
					->setCellValue('B1', 'Classe')
					->setCellValue('C1', 'Mois')
					->setCellValue('D1', 'Montant')
					// ->setCellValue('E1', 'MontantRepas')
					// ->setCellValue('F1', 'MontantGouter')
					->setCellValue('G1', 'Date paiement');

				$startRow = 2;
				$encaissements  = DB::reader("SELECT * FROM ins_inscriptions i,fnc_encaissements fnc,gen_eleves e , users u , ins_classes c WHERE i.ID = fnc.Inscription and i.Eleve = e.ID and u.ID = e.User and i.Classe = c.ID");
				$i = 0;
				while ($i < count($encaissements)) {
					$currentRow = $startRow + $i;
					//	if (($e = $encaissements[$i]) && ($ins = $e->get('Inscription')) && ($el = $ins->get('Eleve')) &&  ($c = $ins->get('Classe')) && ($u = $el->get('User'))) {
					$objPHPExcel->getActiveSheet()
						->setCellValue('A' . $currentRow, $encaissements[$i]['Nom'] . " " . $encaissements[$i]['Prenom'])
						->setCellValue('B' . $currentRow, $encaissements[$i]['Label'])
						->setCellValue('C' . $currentRow, $encaissements[$i]['Mois'])
						->setCellValue('D' . $currentRow, $encaissements[$i]['Montant'])
						// ->setCellValue('E' . $currentRow, $encaissements[$i]->get('MontantRepas') ? $encaissements[$i]->get('MontantRepas') : '-')
						// ->setCellValue('F' . $currentRow, $encaissements[$i]->get('MontantGouter') ? $encaissements[$i]->get('MontantGouter') : '-')
						->setCellValue('G' . $currentRow, $encaissements[$i]['Date']);
					$i++;
					//}
				}
				$objPHPExcel->getActiveSheet()
					->getStyle('B' . $startRow . ':B' . count($encaissements))
					->getNumberFormat()
					->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME);

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);

				$fileName = 'Encaissements';
				// redirect output to client browser
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="' . $fileName . date('Ymd-His') . '.xlsx"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

				$objWriter->save('php://output');
				// print_r($objPHPExcel);

			} catch (Exception  $e) {
				echo $e->getMessage();
			}
			//URL::redirect(URL::admin('encaissements'));
		}

		$niveaux = Models\Niveau::getList();
		$sites = Models\Site::getList();
		$classes = Models\Classe::getList();
		$promotions = Models\Promotion::getList();
		$promotion = Models\Promotion::promotion_actuelle();
		$classe = null;
		$niveau = null;
		$site = null;

		$mode = null;
		$user = roleIs('agent_plus', 'resp_financier') ? Session::user() : null;
		$compte_bancaire = null;
		$zone_recherche = null;
		$filtre_range_montant =  null;
		$periode = null;
		$filtre_periode = null;
		$service = null;
		$filter = array(
			'classe' => null,
			'niveau' => null,
			'site' => null,
			'service' => null,
			'zone_recherche' => null,
			'periode' => null,
			'montant_max' => null,
			'montant_min' => null,
			'promotion' => null,
			'user' => null,
			'compte_bancaire' => null,
		);



		$encaissements = array();
		if (isset($_SESSION['filtreEncaissementDataHolder'])) {

			$filtreEncaissementDataHolder = unserialize($_SESSION['filtreEncaissementDataHolder']);



			if ($filtreEncaissementDataHolder['classe']) {
				$classe = new Models\Classe($filtreEncaissementDataHolder['classe']);
				$filter['classe'] = $classe;
			}

			if ($filtreEncaissementDataHolder['niveau']) {
				$niveau = new Models\Niveau($filtreEncaissementDataHolder['niveau']);
				$filter['niveau'] = $niveau;
			}


			if (isset($filtreEncaissementDataHolder['site']) && $filtreEncaissementDataHolder['site']) {
				$site = new Models\Site($filtreEncaissementDataHolder['site']);
				$filter['site'] = $site;
				$classes = Models\Classe::getList(['where' => ['Site' => $site->getKey()]]);
			}

			if ($filtreEncaissementDataHolder['mode']) {
				$mode = Models\FIN\PaiementMode::getByAlias($filtreEncaissementDataHolder['mode']);
				$filter['mode'] = $mode;
			}

			if ($filtreEncaissementDataHolder['promotion']) {
				$promotion = new Models\Promotion($filtreEncaissementDataHolder['promotion']);
				$filter['promotion'] = $promotion;
			}


			if ($filtreEncaissementDataHolder['user']) {
				$user = new Models\User($filtreEncaissementDataHolder['user']);
				$filter['user'] = $user;
			}

			if (isset($filtreEncaissementDataHolder['service']) && $filtreEncaissementDataHolder['service']) {
				$service = new Models\FIN\EncaissementRubrique($filtreEncaissementDataHolder['service']);
				$filter['service'] = $service;
			}


			if ($filtreEncaissementDataHolder['compte_bancaire']) {
				$compte_bancaire = new Models\FIN\CompteBancaire($filtreEncaissementDataHolder['compte_bancaire']);
				$filter['compte_bancaire'] = $compte_bancaire;
			}

			if (isset($_SESSION['numRecuAdded'])) {
				$zone_recherche = $_SESSION['numRecuAdded'];
				$filtreEncaissementDataHolder['zone_recherche'] = $_SESSION['numRecuAdded'];
				$filter['zone_recherche'] = $zone_recherche;
				unset($_SESSION['numRecuAdded']);
			} elseif ($filtreEncaissementDataHolder['zone_recherche']) {
				$zone_recherche = $filtreEncaissementDataHolder['zone_recherche'];
				$filter['zone_recherche'] = $zone_recherche;
			}
			$filtre_periode = $filtreEncaissementDataHolder['filtre_periode'];
			if ($filtre_periode) {
				$periode = $filtreEncaissementDataHolder['periode'];
				$filter['periode'] = $periode;
			}


			$filtre_range_montant = isset($filtreEncaissementDataHolder['filtre_range_montant']) ? $filtreEncaissementDataHolder['filtre_range_montant'] : null;

			if ($filtre_range_montant) {
				$filter['montant_min'] = $filtreEncaissementDataHolder['montant_min'];
				$filter['montant_max'] = $filtreEncaissementDataHolder['montant_max'];
			}
			$_SESSION['filtreEncaissementDataHolder'] = serialize($filtreEncaissementDataHolder);
			$encaissements = Models\FIN\Encaissement::getEncaissements($filter);
		}


		if (isset($_GET['imprimer'])) {

			loadView('encaissements/encaissements-etat', array(
				'encaissements' => $encaissements,
				'niveaux' => $niveaux,
				'niveau' => $niveau,
				'classe' => $classe,
				'classes' => $classes,
				'compte_bancaire' => $compte_bancaire,
				'user' => $user,
				'service' => $service,
				'zone_recherche' => $zone_recherche,
				'periode' => $periode,
				'filtre_periode' => $filtre_periode,
				'montant_min' => $filter['montant_min'],
				'montant_max' => $filter['montant_max'],
				'filtre_range_montant' => $filtre_range_montant,
				'periode' => $periode,
				'filtre_periode' => $filtre_periode,
				'navKey' => 'encaissements',
			), '_layout-empty');
		}


		if (isset($_GET['journal_imprimer'])) {

			loadLib('tcpdf');
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('Nicola Asuni');
			$pdf->SetTitle('Recu');
			$pdf->SetSubject('TCPDF Tutorial');
			$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

			// remove default header/footer
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);

			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			$pdf->AddPage('P', 'A4');

			$html = getView('encaissements/journal_encaissements', array(
				'encaissements' => $encaissements,
				'niveaux' => $niveaux,
				'niveau' => $niveau,
				'classe' => $classe,
				'promotions' => $promotions,
				'promotion' => $promotion,
				'mode' => $mode,
				'classes' => $classes,
				'compte_bancaire' => $compte_bancaire,
				'user' => $user,
				'service' => $service,
				'zone_recherche' => $zone_recherche,
				'periode' => $periode,
				'montant_min' => $filter['montant_min'],
				'montant_max' => $filter['montant_max'],
				'filtre_range_montant' => $filtre_range_montant,
				'periode' => $periode,
				'filtre_periode' => $filtre_periode,
				'filtre_periode' => $filtre_periode,
				'navKey' => 'encaissements',
			), 'null_layout');

			// echo $html;


			// output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');

			$pdf->lastPage();
			ob_end_clean();
			$pdf->Output('dddd.pdf', 'I');
			exit;
		}

		if (isset($_GET['print_pdf'])) {

			loadLib('tcpdf');
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('Jamal');
			$pdf->SetTitle('Etat Encaissements');

			// remove default header/footer
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);

			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			$pdf->AddPage('P', 'A4');

			$html = getView('encaissements/encaissements-list-pdf', array(
				'encaissements' => $encaissements,
				'niveaux' => $niveaux,
				'niveau' => $niveau,
				'classe' => $classe,
				'promotions' => $promotions,
				'promotion' => $promotion,
				'mode' => $mode,
				'classes' => $classes,
				'user' => $user,
				'service' => $service,
				'compte_bancaire' => $compte_bancaire,
				'zone_recherche' => $zone_recherche,
				'periode' => $periode,
				'filtre_periode' => $filtre_periode,
				'navKey' => 'encaissements',
			), 'null_layout');

			// echo $html;


			// output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');

			$pdf->lastPage();
			ob_end_clean();
			$pdf->Output('dddd.pdf', 'I');
			exit;
		}


		if (isset($_GET['export_excel'])) {


			set_time_limit(-1);
			header('Content-Type: text/plain; charset=utf8');
			loadLib('phpexcel');
			if (!PHPExcel_Settings::setLocale('fr_FR'))
				echo 'Locale not set ' . PHP_EOL;

			$objPHPExcel = new PHPExcel();
			$activeSheet = $objPHPExcel->getActiveSheet();
			$activeSheet->setCellValue('A1', "Etat des encaissements");
			$activeSheet->mergeCells('A1:F2');


			$activeSheet->setCellValue('A3', "M");
			$activeSheet->setCellValue('B3', "Nom et prénom ");
			$activeSheet->setCellValue('C3', "Num reçu");
			$activeSheet->setCellValue('D3', "Montant");
			$activeSheet->setCellValue('E3', "M.reg");
			$activeSheet->setCellValue('F3', "Année scolaire");
			$activeSheet->setCellValue('G3', "Date paiement");
			$activeSheet->setCellValue('H3', "Détails");


			$row = 4;
			$mode_reg = [
				'total' => [
					'label' => 'Total',
					'regle' => 0,
					'annule' => 0,
				],
			];
			foreach ($encaissements as $key => $item) {

				if (!isset($mode_reg[$item->PaiementMode]) && $item->getPaiementMode()) {
					$mode_reg[$item->PaiementMode] = [
						'label' => $item->getPaiementMode()->get('Label'),
						'regle' => 0,
						'annule' => 0,

					];
				}


				$mode_reg[$item->PaiementMode]['regle'] += $item->Montant;
				$mode_reg['total']['regle'] += $item->Montant;

				if ($item->Canceled) {
					$mode_reg[$item->PaiementMode]['annule'] += $item->Montant;
					$mode_reg['total']['annule'] += $item->Montant;

					$activeSheet->getStyle("A$row:G$row")->applyFromArray(
						array(
							'fill' => array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb' => 'FF0000')
							)
						)
					);
				}


				$lignesShowed = array();
				$eleves  = [];
				$matricules  = [];
				$lignes = $item->encaissementLignes();

				foreach ($lignes as $ligne) {

					$service =  $ligne->get('EncaissementRubrique');
					$matricules[$ligne->get('Inscription')->Eleve->ID] = $ligne->get('Inscription')->Eleve->get('Matricule');
					$eleves[$ligne->get('Inscription')->ID] = $ligne->get('Inscription')->get('Eleve')->get('User')->getNomComplet() . ' (' . ($ligne->get('Inscription')->get('Classe') ? $ligne->get('Inscription')->get('Classe')->Label : ' --- ') . ' ) ';
					$key =  $service->get('ID');

					if (!isset($lignesShowed[$key])) {
						$lignesShowed[$key] = (object)array(
							'service' =>  $service->get('Label'),
							'montant' => $ligne->get('Montant'),
							'months' =>  array()
						);
					} else {
						$lignesShowed[$key]->montant += $ligne->get('Montant');
					}

					$lignesShowed[$key]->months[] =  ((!$service->get('Mensuel')  && $ligne->get('Mois') == 0) || !$ligne->get('Mois')) ? 'FA' : $ligne->get('Mois');
				}
				$details = '';
				foreach ($lignesShowed as $s) {
					$details .= 	$s->service . ' ' . Tools::numberFormat($s->montant) . ' [ ' . implode(',', $s->months) . ' ] ' . ', ';
				}

				$activeSheet->setCellValue("A$row", implode(',', $matricules));
				$activeSheet->setCellValue("B$row", implode(',', $eleves));
				$activeSheet->setCellValue("C$row", $item->get('NumRecu'));
				$activeSheet->setCellValue("D$row", ($item->Canceled ? '-' : '') . $item->get('Montant'));
				$activeSheet->setCellValue("E$row", $item->getPaiementMode() ? $item->getPaiementMode()->get('Label') : '----');
				$activeSheet->setCellValue("F$row", $item->Promotion->get('Label'));
				$activeSheet->setCellValue("G$row", $item->get('DatePaiement'));
				$activeSheet->setCellValue("H$row", $details);

				$row += 1;
			}
			$row += 3;



			$activeSheet->setCellValue("A$row", 'Mode de règlement');
			$activeSheet->setCellValue("B$row", 'Réglé');
			$activeSheet->setCellValue("C$row", 'Annulé');
			$activeSheet->setCellValue("D$row", 'Total');

			$row++;

			foreach (array_reverse($mode_reg) as $mode) {
				$activeSheet->setCellValue("A$row", $mode['label']);
				$activeSheet->setCellValue("B$row", \Tools::numberFormat($mode['regle'], 2));
				$activeSheet->setCellValue("C$row", \Tools::numberFormat(-$mode['annule'], 2));
				$activeSheet->setCellValue("D$row",  \Tools::numberFormat($mode['regle'] - $mode['annule'], 2));
				$row++;
			}

			excel_styling($activeSheet, array(
				'header' => array('A1:H3'),
				'body' => array("A4:G$row"),
			));

			foreach (range('A', 'U') as $col)
				$activeSheet->getColumnDimension($col)->setAutoSize(true);


			// redirect output to client browser
			$title = 'Etat des encaissements';
			$fileTitle = alais_string($title) . '_' . date('Ymd-His') . '.xlsx';
			// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); didn't work on windows
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="' . $fileTitle . '"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			ob_end_clean();
			$objWriter->save('php://output');
			// print_r($objPHPExcel);

			exit;
		}

		// foreach($encaissements as $item) {
		// $lignes = $item->encaissementLignes();
		// $montantTotal = 0;
		// foreach($lignes as $ligne) {
		// $montantTotal += $ligne->get('Montant');
		// }

		// $item
		// ->set('Montant', $montantTotal)
		// ->save()
		// ;
		// }

		loadView('encaissements/encaissements-list', array(
			'encaissements' => $encaissements,
			'niveaux' => $niveaux,
			'sites' => $sites,
			'site' => $site,
			'niveau' => $niveau,
			'classe' => $classe,
			'promotions' => $promotions,
			'promotion' => $promotion,
			'mode' => $mode,
			'user' => $user,
			'service' => $service,
			'classes' => $classes,
			'compte_bancaire' => $compte_bancaire,
			'zone_recherche' => $zone_recherche,
			'periode' => $periode,
			'montant_min' => $filter['montant_min'],
			'montant_max' => $filter['montant_max'],
			'filtre_range_montant' => $filtre_range_montant,
			'filtre_periode' => $filtre_periode,
			'navKey' => 'encaissements',
		));
	}


	function suivi()
	{

		$niveaux = Models\Niveau::getList();
		$classes = Models\Classe::getList();
		$promotions = Models\Promotion::getList();
		$promotion = Models\Promotion::promotion_actuelle();
		$classe = null;
		$niveau = null;
		$mode = null;
		$user = roleIs('agent_plus', 'resp_financier') ? Session::user() : null;
		$compte_bancaire = null;
		$zone_recherche = null;
		$filtre_range_montant =  null;
		$periode = null;
		$filtre_periode = null;
		$service = null;

		$filter = array(
			'classe' => null,
			'niveau' => null,
			'service' => null,
			'zone_recherche' => null,
			'periode' => null,
			'montant_max' => null,
			'montant_min' => null,
			'promotion' => null,
			'user' => null,
			'compte_bancaire' => null,
		);

		$encaissements = Models\FIN\Encaissement::getEncaissements($filter);
		$result_encaissements = [];




		foreach ($encaissements as $key => $item) {

			if (!isset($result_encaissements[alais_string($item->Date)])) {
				$depenses  = Models\FIN\Depense::where(['Date' => $item->get('DatePaiement')])->get();
				$total_depenses = 0;

				foreach ($depenses as $key => $d) {
					$total_depenses += $d->Montant;
				}
				$result_encaissements[alais_string($item->Date)] = array(
					'depenses' => $depenses,
					'depenses_motant' => $total_depenses,
					'encaissements' => [],
					'encaissements_motant' => 0,
					'date' => $item->Date,

				);
			}
			$result_encaissements[alais_string($item->Date)]['encaissements'][] = $item;
			$result_encaissements[alais_string($item->Date)]['encaissements_motant'] += $item->Montant;
		}


		loadView('encaissements/encaissements-suivi', array(
			'encaissements' => $encaissements,
			'result_encaissements' => $result_encaissements,
			'niveaux' => $niveaux,
			'niveau' => $niveau,
			'classe' => $classe,
			'promotions' => $promotions,
			'promotion' => $promotion,
			'mode' => $mode,
			'user' => $user,
			'service' => $service,
			'classes' => $classes,
			'compte_bancaire' => $compte_bancaire,
			'zone_recherche' => $zone_recherche,
			'periode' => $periode,
			'montant_min' => $filter['montant_min'],
			'montant_max' => $filter['montant_max'],
			'filtre_range_montant' => $filtre_range_montant,
			'filtre_periode' => $filtre_periode,
			'navKey' => 'encaissements',
		));
	}


	function rapport_financiers()
	{
		// ControllerRole('admin', 'agent_plus', 'resp_financier');
		$promotion =  Models\Promotion::promotion_actuelle();

		// $enc_query = "SELECT SUM(Montant) as Amount FROM fnc_encaissementlignes WHERE Canceled IS NULL AND Inscription in (SELECT ID FROM  ins_inscriptions WHERE Depart IS NULL AND  Promotion = " . $promotion->get('ID') . ")";
		// $ca_query = "SELECT SUM(Montant) as Amount FROM fnc_encaissementinscriptions WHERE Inscription in (SELECT ID FROM ins_inscriptions WHERE Depart IS NULL AND Promotion = " . $promotion->get('ID') . ")";

		$enc_query = "SELECT SUM(Montant) as Amount FROM fnc_encaissementlignes WHERE Canceled IS NULL AND Inscription in (SELECT ID FROM  ins_inscriptions WHERE Promotion = " . $promotion->get('ID') . ")";
		$ca_query = "SELECT SUM(Montant) as Amount FROM fnc_encaissementinscriptions WHERE Inscription in (SELECT ID FROM ins_inscriptions WHERE  Promotion = " . $promotion->get('ID') . ")";

		$ca = \DB::scallar($ca_query);
		$enc = \DB::scallar($enc_query);
		return loadView('encaissements/rapport_financiers', array(
			'ca' => $ca,
			'enc' => $enc,
		));
	}

	function add()
	{
		if (Request::getArgs(2)) {
			$promotion = new Models\Promotion(Request::getArgs(2));
		} else {
			$promotion = Models\Promotion::promotion_actuelle();
		}
		if (Request::getArgs(1)) {
			URL::redirect(URL::admin('encaissements/new_encaissement?inscription=' . Request::getArgs(1) . '&promotion=' . $promotion->get('ID')));
		}

		URL::redirect(URL::admin('encaissements/new_encaissement'));
	}

	function temp(string $route = '')
	{
		if (!$route) loadView('encaissements/encaissements-temp', [], '_layout');

		switch ($route) {
			case 'journal_simplifie':
				$this->journal_simplifie();
				break;

			default:
				# code...
				break;
		}
	}

	private function journal_simplifie()
	{
		// ControllerRole('admin', 'agent_plus', 'resp_pedago');
		if (Request::isPost()) {
			set_time_limit(-1);

			loadLib('phpexcel');
			if (isset($_FILES['file'])) {
				$file = $_FILES['file'];
				if ($file['tmp_name'] && !$file['error']) {
					$fileName = $file['name'];
					$tempName = $file['tmp_name'];
					$extension = strtoupper(pathinfo($fileName, PATHINFO_EXTENSION));

					if ($extension == 'XLSX' || $extension == 'ODS' || $extension == 'XLS') {
						try {
							$fileType = PHPExcel_IOFactory::identify($tempName);
							$objReader = PHPExcel_IOFactory::createReader($fileType);
							$objPHPExcel = $objReader->load($tempName);
						} catch (Exception $e) {
							die($e->getMessage());
						}

						$sheet = $objPHPExcel->getSheet(0);

						$dataSheet = $sheet->toArray();

						if (isset($_POST['replace'])) DB::noQuery("DELETE FROM `fnc_temp_encaissements`");

						foreach ($dataSheet as $rowIndex => $row) {
							if ($rowIndex < 2) continue;

							$encaissement = new TempEncaissements();

							$encaissement
								->set('Massar', $row[0])
								->set('Promotion', $_POST['promotion'])
								->set('Nom', $row[1])
								->set('NumRecu', $row[2])
								->set('Montant', $row[3])
								->set('PaiementMode', $row[4])
								->set('NumCheque', $row[5])
								->set('Details', $row[6])
								->set('Date', date('Y-m-d', strtotime($row[7])))
								->save();
						}
					}
				}
			}
			URL::redirect(URL::admin('encaissements/temp/journal_simplifie'));
		}


		$filter = isset($_GET['filter']) ? true : false;
		$search = $_GET['search'] ?? '';

		$where = [];

		if ($search) $where[] = "(Massar LIKE '%" . $search . "%' OR Nom  LIKE '%" . $search . "%' OR NumRecu LIKE '%" . $search . "%')";

		$journal = TempEncaissements::getList(['where' => $where]);
		$promotions = Promotion::getList();
		$promotionActuelle = Promotion::actuelle();

		loadView('encaissements/encaissements-journal-simplifie', [
			'journal' => $journal,
			'filter' => $filter,
			'search' => $search,
			'promotions' => $promotions,
			'promotionActuelle' => $promotionActuelle,
		], '_layout');
	}

	function encaissements_item()
	{
		$item = new  Models\Fin\Encaissement(Request::getArgs(1));
		loadView('encaissements/encaissements-list-item', array(
			'item' => $item
		), 'null_layout');
	}

	function encaissements_edit()
	{
		$encaissement = new  Models\Fin\Encaissement(Request::getArgs(1));
		exit($this->encaissementsWithLigneView($encaissement));
	}

	function encaissementsWithLigneView($encaissement)
	{

		$inscriptions_lignes = array();
		$encaissementsLignes = $encaissement->encaissementLignes();

		foreach ($encaissementsLignes as $item) {

			$inscription =  $item->Inscription;
			if (!isset($inscriptions_lignes[$inscription->ID])) {
				$inscriptions_lignes[$inscription->ID] = array(
					'inscription' => $inscription,
					'lignes' => array(),
				);
			}
			$inscriptions_lignes[$inscription->ID]['lignes'][] = $item;
		}

		return getView('encaissements/encaissements-list-item-edit', array(
			'encaissement' => $encaissement,
			'count_lignes' => count($encaissementsLignes),
			'inscriptions_lignes' => $inscriptions_lignes,
		), 'null_layout');
	}



	function delete_encaissement()
	{
		$encaissement = new Models\FIN\Encaissement(Request::getArgs(1));


		$user = Session::getInstance()->getCurUser();
		if (!($encaissement->get('UserBy')->get('ID') == $user->get('ID') || roleIs('admin'))) {
			sendResponse(array(
				'msg' => "Vous n'avez pas l'autorisation pour annuler ce paiement ",
			));
		}


		$encaissement->cancelEncaissement(Request::has('avoir'), Request::get('comment'));


		\Log::fnc("Annuler l'encaissement : " . $encaissement->getKey());


		// foreach ($encaissement->encaissementLignes() as  $ligne) {
		// 	$ligne->setJson('Canceled', array(
		// 		'date' => date('Y-m-d H:i:s'),
		// 		'user' => $user->ID,
		// 	));
		// 	$ligne->save();
		// }

		// $encaissement->setJson('Canceled', array(
		// 	'date' => date('Y-m-d H:i:s'),
		// 	'comment' => Request::get('comment'),
		// 	'user' => $user->ID,
		// ));



		// if (Request::has('avoir')) {
		// 	$avoir = new Models\FIN\Avoir();

		// 	$avoir->fill([
		// 		'Ref' => $encaissement->ID . '_' . $encaissement->NumRecu,
		// 		'Promotion' => $encaissement->Promotion,
		// 		'Encaissement' => $encaissement->ID,
		// 		'NumRecuEncaissement' => $encaissement->NumRecu,
		// 		'Amount' => $encaissement->Montant,
		// 		'User' => $user,
		// 		'Commentaire' => Request::get('comment'),
		// 		'Date' => date('Y-m-d H:i:s'),
		// 	]);

		// 	$avoir->saveHistory('add');

		// 	$avoir->save();
		// }




		sendResponse(array(
			'msg' => "Paiement Annulé",
		));
	}

	function edit_ligne()
	{

		$ligne = new Models\FIN\EncaissementLigne(Request::getArgs(1));
		$encaissement = $ligne->Encaissement;

		$ligne_montant = isset($_POST['montant']) ? $_POST['montant'] : null;
		if ($ligne) {
			$ligne->set('Montant', $ligne_montant);
		}
		$ligne->save();


		// update Encaissment Montant
		$lignes = $encaissement->encaissementLignes();
		$montantTotal = 0;
		$selectedInscriptionsID = array();
		$selectedRubriquesID = array();

		foreach ($lignes as $ligne) {
			// montant
			$montantTotal += $ligne->get('Montant');
			// inscriptons
			if (!in_array($ligne->Inscription->ID, $selectedInscriptionsID)) {
				$selectedInscriptionsID[] = $ligne->Inscription->ID;
			}
			// rubriques
			if (!in_array($ligne->EncaissementRubrique->ID, $selectedRubriquesID)) {
				$selectedRubriquesID[]  = $ligne->EncaissementRubrique->ID;
			}
		}

		$encaissement
			->setJson('Inscriptions', $selectedInscriptionsID)
			->setJson('EncaissementRubriques', $selectedRubriquesID)
			->set('Montant', $montantTotal)
			->save();
		// end 

		sendResponse(array(
			'msg' => "Encaissement mis à jour avec succes",
			'html' => $this->encaissementsWithLigneView($encaissement),
		));
	}

	function encaisser()
	{

		$encaissement = new Models\FIN\Encaissement(Request::getArgs(1));
		$encaissement
			->set('Encaisse', date('Y-m-d H:i:s'))
			->set('EncaisseDate', $_POST['date'])
			->set('NumOperation', $_POST['operation'])
			->set('EncaisseBy', Session::getInstance()->getCurUser())
			->save();

		$_SESSION['numRecuAdded'] = $encaissement->get('NumRecu');



		sendResponse(array(
			'msg' => "Encaissement marqué encaissé",
			'html' => $this->encaissementsWithLigneView($encaissement),
		));
	}

	function delete_ligne()
	{

		$ligne = new Models\FIN\EncaissementLigne(Request::getArgs(1));
		$encaissement = $ligne->Encaissement;
		$deleted_log_message = "Details Encaissement deleted (ligne) " . $encaissement->ID . " MONTANT " . $ligne->get("Montant");
		$ligne->delete();

		Models\ActionLog::catchLog($deleted_log_message, $ligne);

		// update Encaissment Montant
		$lignes = $encaissement->encaissementLignes();
		$montantTotal = 0;
		$selectedInscriptionsID = array();
		$selectedRubriquesID = array();

		foreach ($lignes as $ligne) {
			// montant
			$montantTotal += $ligne->get('Montant');
			// inscriptons
			if (!in_array($ligne->Inscription->ID, $selectedInscriptionsID)) {
				$selectedInscriptionsID[] = $ligne->Inscription->ID;
			}
			// rubriques
			if (!in_array($ligne->EncaissementRubrique->ID, $selectedRubriquesID)) {
				$selectedRubriquesID[]  = $ligne->EncaissementRubrique->ID;
			}
		}

		$encaissement
			->setJson('Inscriptions', $selectedInscriptionsID)
			->setJson('EncaissementRubriques', $selectedRubriquesID)
			->set('Montant', $montantTotal)
			->save();
		// end 

		sendResponse(array(
			'msg' => "Encaissement supprimé",
			'html' => $this->encaissementsWithLigneView($encaissement),
		));
	}

	function etat_service()
	{

		if (isset($_GET['reinitialiser']) && isset($_SESSION['filtreEtatCompteDataHolder']))
			unset($_SESSION['filtreEtatCompteDataHolder']);

		$paiements = array();
		$services = Models\FIN\EncaissementRubrique::all(array('where' => array('Optionnel' => true)));;
		$comptes = Models\Partenaire::getList();

		$servicesFiltre = array();
		$comptesFiltre = array();
		$zone_recherche = null;
		$periode = null;
		$filtre_periode = null;
		$filter = array(
			'services' => null,
			'comptes' => null,
			'periode' => null,
		);

		$filtred = false;
		if (isset($_SESSION['filtreEtatCompteDataHolder'])) {


			$filtred = true;

			$filtreEtatCompteDataHolder = unserialize($_SESSION['filtreEtatCompteDataHolder']);

			$filtre_periode = $filtreEtatCompteDataHolder['filtre_periode'];

			if ($filtreEtatCompteDataHolder['services']) {
				$filter['services'] = $filtreEtatCompteDataHolder['services'];
			}

			if ($filtreEtatCompteDataHolder['comptes']) {
				$filter['comptes'] = $filtreEtatCompteDataHolder['comptes'];
			}

			if ($filtre_periode) {
				$periode = $filtreEtatCompteDataHolder['periode'];
				$filter['periode'] = $periode;
			}

			$_SESSION['filtreEtatCompteDataHolder'] = serialize($filtreEtatCompteDataHolder);
		}

		$encaissements = array();
		$periodeExplode = array();
		$layout = '_layout';
		if ($filtred) {

			$query = Models\FIN\EncaissementLigne::sqlQuery() . <<<END
		JOIN (SELECT `ID` AS `J1ID`, `Inscription` AS `J1Inscription`, `Partenaire` AS `J1Partenaire`, `DatePaiement` AS `J1DatePaiement`, `Promotion` AS `J1Promotion` FROM `fnc_encaissements`) AS `j1` ON `fnc_encaissementlignes`.`Encaissement` = `j1`.`J1ID` 
		JOIN (SELECT `ID` AS `J2ID`, `Classe` AS `J2Classe` FROM `ins_inscriptions`) AS `j2` ON `j1`.`J1Inscription` = `j2`.`J2ID` 
END;

			$where = array();

			if ($filter['comptes']) {
				$where[] = 'J1Partenaire IN (' . implode(',', $filter['comptes']) . ')';
			}

			if ($filter['services']) {
				$where[] = 'EncaissementRubrique IN (' . implode(',', $filter['services']) . ')';
			}
			if ($filter['periode']) {
				$periodeExplode =  explode(' - ', $periode);
				$where[] = 'DATE(J1DatePaiement) BETWEEN \'' . $periodeExplode[0] . '\' AND \'' . $periodeExplode[1] . '\'';
			} else {
				$promotion = Models\Promotion::promotion_actuelle();
				$where['J1Promotion'] = $promotion->get('ID');
			}

			$encaissements = Models\FIN\EncaissementLigne::getList(array('where' => $where), $query);
		}

		if ($encaissements)
			$layout = '_layout-empty';

		loadView('encaissements/encaissements-etat-compte', array(
			'isUpdate' => true,
			'servicesFiltre' => $servicesFiltre,
			'comptesFiltre' => $comptesFiltre,
			'services' => $services,
			'encaissements' => $encaissements,
			'comptes' => $comptes,
			'filtre_periode' => $filtre_periode,
			'periode' => $periode,
			'periodeExplode' => $periodeExplode,
			'navKey' => 'eleves',
		), $layout);
	}

	function etat_encaissement()
	{

		$promotions = Models\Promotion::getList();
		$encaissements = array();
		$eleves = array();
		$inscription = null;
		$parrainages = array();
		$paiementsResult = array();
		$rubriquesInscriptionList = array();
		$promotion = null;
		if (isset($_GET['promotion'])) {

			try {
				$promotion = new Models\Promotion($_GET['promotion']);
			} catch (Exception $e) {
				exit('Element introuvable');
			}

			$eleves = Models\Eleve::activeEleves($promotion, null, null, false);
		} else {
			$eleves = Models\Eleve::activeEleves(null, null, null, false);
		}

		if (isset($_GET['eleve']) && $_GET['eleve'] && $promotion) {
			try {
				$eleve = new Models\Eleve($_GET['eleve']);
			} catch (Exception $e) {
				exit('Element introuvable');
			}

			$inscriptions = Models\Inscription::getList(array('where' => array('Eleve' => $eleve->get('ID'))));
			foreach ($inscriptions as $item) {

				if ($promotion->get('ID') == $item->get('Promotion')->get('ID'))
					$inscription = $item;
			}
		}

		$rubriquesInscription = array();
		$rubriques = array();
		$grille = array();
		if ($inscription) {

			$parrainages = Models\Parrainage::getList(array('where' => array('Eleve' => $inscription->get('Eleve')->get('ID'))));
			//$encaissements = Models\FIN\Encaissement::getList(array('where' => array('Canceled IS NULL', 'Inscription' => $inscription->get('ID'))));
			$encaissements = Models\FIN\Encaissement::getEncaissementOfInscription($inscription);
			$months_list = _months_list;
			$months = _months_keys;
			$rubriques = array();
			$rubriquesList = Models\FIN\EncaissementRubrique::getList();
			foreach ($rubriquesList as $item)
				$rubriques[$item->get('ID')] = $item;


			$rubriquesInscriptionList = Models\FIN\EncaissementRubriqueInscription::getList(array('where' => array('Inscription' => $inscription->get('ID'))));
			foreach ($rubriquesInscriptionList as $item)
				$rubriquesInscription[$item->get('EncaissementRubrique')->get('ID')] = $item;

			if (isset($_GET['print'])) {


				$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


				// set document information
				$pdf->SetCreator(PDF_CREATOR);
				$pdf->SetTitle('Etat de relance');

				// set default header data
				$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 061', PDF_HEADER_STRING);

				$headerHtml = '';
				ob_start();
				include_once _basepath . Config::get('admin') . '/pages/encaissements/encaissements-etat-relance-header.php';
				$headerHtml = ob_get_contents();
				ob_clean();

				$pdf->setHeaderData($ln = '', $lw = 0, $ht = '', $headerHtml, $tc = array(0, 0, 0), $lc = array(0, 0, 0));

				// set header and footer fonts
				$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
				$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

				// set margins
				$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
				$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
				$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

				// set default monospaced font
				// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

				// set margins
				// $pdf->SetMargins(19, 70, 19, 0);

				// set auto page breaks
				$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

				// set image scale factor
				$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

				// ---------------------------------------------------------

				$pdf->AddPage('L', 'A4');

				// define some HTML content with style
				$html = '';
				ob_start();
				include_once _basepath . Config::get('admin') . '/pages/encaissements/encaissements-etat-relance.php';
				$html = ob_get_contents();
				ob_clean();

				// echo $html;
				// exit;


				// output the HTML content
				$pdf->writeHTML($html, true, false, true, false, '');

				$pdf->lastPage();

				// ---------------------------------------------------------

				//Close and output PDF document
				$pdf->Output($inscription->get('Eleve')->get('User')->getNomComplet() . '.pdf', 'I');
			}
		}

		// Filtre list for PDF export
		$filtreQueryString = http_build_query($_GET);
		if ($filtreQueryString)
			$filtreQueryString = '?' . $filtreQueryString;

		loadView('encaissements/encaissements-etat-encaissement', array(
			'eleve' => $inscription ? $inscription->get('Eleve') : null,
			'inscription' => $inscription,
			'niveau' => $inscription ? $inscription->get('Niveau') : null,
			'parrainages' => $parrainages,
			'promotions' => $promotions,
			'encaissements' => $encaissements,
			'promotion' => $promotion,
			'eleves' => $eleves,
			'inscriptionActuelle' => $inscription,
			'rubriquesInscription' => $rubriquesInscription,
			'rubriques' => $rubriques,
			'filtreQueryString' => $filtreQueryString,
			'grille' => $grille,
			'isUpdate' => true,
			'navKey' => 'eleves',
		));
	}

	function new_encaissement_v1()
	{
		set_time_limit(0);
		$promotions = Models\Promotion::getList();
		$inscription = null;
		$promotion =  Models\Promotion::promotion_actuelle();
		$grille = Models\FIN\RubriquePrice::grille();
		if (isset($_GET['promotion'])) {
			try {
				$promotion = new Models\Promotion($_GET['promotion']);
			} catch (Exception $e) {
				URL::redirect(URL::admin('encaissements/new_encaissement'));
			}
		}

		$inscriptions = Models\Inscription::getList(array(
			'where' => array(
				'Promotion' => $promotion->get('ID')
			)
		));

		if (isset($_GET['inscription']) && $_GET['inscription'] && $promotion) {
			try {
				$inscription = new Models\Inscription($_GET['inscription']);

				if ($promotion->get('ID') != $inscription->get('Promotion')->get('ID')) {
					$inscription  = $inscription->get('Eleve')->getInscription($promotion);
				}
			} catch (Exception $e) {
				URL::redirect(URL::admin('encaissements/new_encaissement'));
			}
		}

		loadView('encaissements/encaissements_form_v2', array(
			'eleve' => $inscription ? $inscription->get('Eleve') : null,
			'inscription' => $inscription,
			'niveau' => $inscription ? $inscription->get('Niveau') : null,
			'promotion' => $promotion,
			'promotions' => $promotions,
			'inscriptions' => $inscriptions,
			'months_list' => $promotion->months()->list,
			'grille' => $grille,
		));
	}

	function new_encaissement()
	{
		set_time_limit(0);
		$promotions = Models\Promotion::getList();
		$inscription = null;
		$promotion =  Models\Promotion::promotion_actuelle();
		$grille = Models\FIN\RubriquePrice::grille();
		if (isset($_GET['promotion'])) {
			try {
				$promotion = new Models\Promotion($_GET['promotion']);
			} catch (Exception $e) {
				URL::redirect(URL::admin('encaissements/new_encaissement'));
			}
		}

		$inscriptions = Models\Inscription::getList(array(
			'where' => array(
				'Promotion' => $promotion->get('ID')
			)
		));

		if (isset($_GET['inscription']) && $_GET['inscription'] && $promotion) {
			try {
				$inscription = new Models\Inscription($_GET['inscription']);

				if ($promotion->get('ID') != $inscription->get('Promotion')->get('ID')) {
					$inscription  = $inscription->get('Eleve')->getInscription($promotion);
				}
			} catch (Exception $e) {
				URL::redirect(URL::admin('encaissements/new_encaissement_v2'));
			}
		}
		$eleve = null;
		try{
			if($inscription){
				$eleve = $inscription->get('Eleve');
			}
		}catch(Exception $th){
			$eleve = null;
		}


					// 'eleve' => $inscription ? $inscription->get('Eleve') : null,

		loadView('encaissements/encaissements_form', array(
			'eleve' => $eleve,
			'inscription' => $inscription,
			'niveau' => $inscription ? $inscription->get('Niveau') : null,
			'promotion' => $promotion,
			'promotions' => $promotions,
			'inscriptions' => $inscriptions,
			'months_list' => $promotion->months()->list,
			'grille' => $grille,
			// 'css' => ['css/designkit.css']
		));
	}

	function encaissement_save()
	{

		set_time_limit(0);
		\DB::begin();

		try {
			$encaissement = new Models\FIN\Encaissement();
		} catch (Exception $e) {
			exit('Element introuvable');
		}

		$eleve = new Models\Eleve($_POST['eleve']);
		$promotion = new Models\Promotion($_POST['promotion']);
		$inscription = $eleve->getInscription($promotion);



		// mode paiement
		$paiementmode = new Models\FIN\PaiementMode($_POST['paiementmode']);
		$compteBancaire  =  null;
		if (isset($_POST['compte_bancaire'])) {
			$compteBancaire =   new Models\FIN\CompteBancaire($_POST['compte_bancaire']);
		}

		$paiementDetailID = null;
		$paiementDetail = [
			'mode' => 'virement',
		];

		$avoirs =  null;

		if ($paiementmode->get('Alias') == 'avoir') {
			if (!Request::get('avoirs')) {
				flashMessage()->success("Veuillez sélectionner un avoir");
				URL::back();
				exit();
			}

			$avoirs  = new Models\FIN\Avoir(Request::get('avoirs'));
			$paiementDetail['mode'] = $paiementmode->get('Alias');
			$paiementDetail['avoirs'] =  $avoirs->asArray();
		} elseif ($paiementmode->get('Alias') == 'virement') {

			$paiementDetail['mode'] = $paiementmode->get('Alias');
			$paiementDetail['modNumComptee'] =  $_POST['numcompte'];
			$paiementDetail['NumPiece'] = $_POST['numpiece'];

			$paiementvirementdetail = new Models\FIN\PaiementVirementDetail();
			$paiementvirementdetail
				->set('NumCompte', $_POST['numcompte'])
				->set('NumPiece', $_POST['numpiece'])
				->save();

			$paiementDetailID = $paiementvirementdetail->get('ID');
		} elseif ($paiementmode->get('Alias') == 'cheque') {

			$paiementDetail['mode'] = $paiementmode->get('Alias');
			$paiementDetail['NumCheque'] = $_POST['numpiece'];
			$paiementDetail['Tireur'] = $_POST['tireur'];

			$paiementchequedetail = new Models\FIN\PaiementchequeDetail();
			$paiementchequedetail
				->set('Banque', $_POST['banque'])
				->set('NumCheque', $_POST['numcheque'])
				->set('Tireur', $_POST['tireur'])
				->save();
			$paiementDetailID = $paiementchequedetail->get('ID');
		} elseif ($paiementmode->get('Alias') == 'tpe') {
			$paiementDetail['mode'] = $paiementmode->get('Alias');
			$paiementDetail['NumTransaction'] =  $_POST['numtransaction'];

			$paiementchequedetail = new Models\FIN\PaiementTpeDetail();
			$paiementchequedetail
				->set('NumTransaction', $_POST['numtransaction'])
				->save();
			$paiementDetailID = $paiementchequedetail->get('ID');
		}

		// new encaissements
		$encaissement
			->set('Inscription', $inscription)
			->set('Promotion', $promotion)
			->set('PaiementMode', $paiementmode->get('Alias'))
			->set('CompteBancaire', $compteBancaire ?  $compteBancaire->getKey() : null)
			->setJson('PaiementDetail', $paiementDetail)
			->set('Partenaire',  $compteBancaire ? $compteBancaire->get('Etablissement')->getKey() : null)
			->set('DetailMode', $paiementDetailID)
			->set('UserBy', Session::getInstance()->getCurUser())
			->set('NumRecu', $promotion->recuEncaissement(true))
			->set('Remarque', $_POST['remarque'])
			->set('DatePaiement', isset($_POST['datepaiement']) ? $_POST['datepaiement'] : date('Y-m-d'))
			->set('EcheanceCheque', $_POST['date_echeance'])
			->set('Date', date('Y-m-d H:i:s'));


		if (isset($_FILES['file']) && $_FILES['file']['error'] != UPLOAD_ERR_NO_FILE) {
			$fileError = GoogleStorage::checkUpload('file');
			errorPage($fileError);
			$encaissement->set('File', GoogleStorage::store('file', Config::get('path-encaissements')));
		}

		$encaissement->save();
		// save

		// save encaissments lignes
		$can_manually_edit_amount_encaissment_money  = isAllowed('can_manually_edit_amount_encaissment_money');
		$grille = Models\FIN\RubriquePrice::grille();
		$niveau = $inscription->get('Niveau');
		$months_list = $promotion->months()->zero_list;
		$selectedServices = 0;
		foreach ($months_list as $month_key => $m) {

			$rubriques = Models\FIN\EncaissementRubriqueInscription::rubriques($inscription, $month_key);
			foreach ($rubriques  as $rubrique) {

				$rubriqueInscription = Models\FIN\EncaissementRubriqueInscription::inscriptionHasRubrique($inscription, $rubrique, $month_key);

				if (isset($_POST['month_' . $month_key . '_service_' . $rubrique->get('ID')])) {
					$montant =  Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($inscription, $month_key, $rubrique);

					if ($can_manually_edit_amount_encaissment_money) {
						$montant =  $_POST["montant_month_" . $month_key . "_service_" . $rubrique->get('ID')];
					}

					if ($montant) {

						$_amount =   Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($inscription, $month_key, $rubrique);
						$_service_amount = Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($inscription, $month_key, $rubrique);

						if ($_amount < $_service_amount) {
							$selectedServices++;
							$encaissementLigne = new Models\FIN\EncaissementLigne();
							$encaissementLigne
								->set('Inscription', $inscription)
								->set('Encaissement', $encaissement)
								->set('Montant', $montant)
								->set('EncaissementRubrique', $rubrique->get('ID'))
								->set('Mois', $month_key);

							$encaissementLigne->save();

							$rubriqueInscription->set('MontantEncaisse', ($rubriqueInscription->get('MontantEncaisse') ?: 0) + $montant);
							$rubrique_fnc_array = $rubriqueInscription->getArray('Encaissements') ?: array();

							array_push($rubrique_fnc_array, $encaissement->ID);

							$rubriqueInscription->setJson(
								'Encaissements',
								$rubrique_fnc_array

							);

							$rubriqueInscription->save();
						}
					}
				}
			}
		}

		// end

		if ($selectedServices == 0) {
			flashMessage()->success("Veuillez sélectionner au moins un service");
			URL::back();
			exit();
		}

		$lignes = $encaissement->encaissementLignes();
		$montantTotal = 0;
		foreach ($lignes as $ligne) {
			$montantTotal += $ligne->get('Montant');
		}

		$encaissement
			->set('Montant', $montantTotal)
			->save();

		if ($avoirs) {
			if ($montantTotal > ($avoirs->Amount - $avoirs->ConsumedAmount)) {
				flashMessage()->success("Le montant de l'avoir choisi est inférieur du montant de l'encaissement.");
				URL::back();
				exit();
			}
			$avoirs->consume($encaissement);
		}

		DB::commit();
		//log
		Models\ActionLog::catchLog(
			"create 'Encaissement'  of " . $inscription->get('Eleve')->get('User')->getNomComplet(),
			$encaissement
		);

		// if (isset($_POST['print_recu'])) {
		URL::redirect(URL::admin('encaissements/' . $encaissement->get('ID') . '/recu'));
		// }
		//URL::back();
	}

	function  new_encaissement_history_paiements($inscription)
	{
		$inscription  =  new Models\Inscription($inscription);
		$encaissements = Models\FIN\Encaissement::getList(array('where' => array('( Inscription = ' . $inscription->get('ID') . ') OR (`ID` IN (SELECT `Encaissement` FROM `fnc_encaissementlignes` WHERE `Inscription` = ' . $inscription->get('ID') . ' ))'), 'order' => array('Date' => false)));

		sendResponse(getView('encaissements/encaissements_form_history_paiements', [
			'encaissements' => $encaissements,
			'inscription' => $inscription,
		], 'null_layout'));
	}

	function  new_encaissement_history_recouvrements($inscription)
	{
		$inscription  =  new Models\Inscription($inscription);
		$recouvrements = Models\FIN\Recouvrement::getList(array('where' => array('Inscription' => $inscription->get('ID')), 'order' => ['DateAction' => false]));

		sendResponse(getView('encaissements/encaissements_form_history_recouvrements', [
			'recouvrements' =>  $recouvrements,
			'inscription' => $inscription
		], 'null_layout'));
	}

	function add_recouvrement($inscription = null)
	{
		$inscription  =  new Models\Inscription($inscription);
		if (Request::isPost()) {
			$recouvrement = new Models\FIN\Recouvrement();
			$recouvrement->fill([
				'Inscription' => $inscription->getKey(),
				'Action' => Request::get('action'),
				'User' => Session::user()->getKey(),
				'DateAction' => Request::get('date'),
				'Date' => date('Y-m-d H:i:s')

			]);

			$recouvrement->setJson('Done', [
				'commentaire' => Request::get('commentaire'),
				'response' => Request::get('res'),
				'user' => Session::user()->getKey(),
			]);

			$recouvrement->save();


			if (Request::get('planifier')) {

				$recouvrementScheduled = new Models\FIN\Recouvrement();
				$recouvrementScheduled->fill([
					'Inscription' => $inscription->getKey(),
					'Action' => Request::get('planifier_action'),
					'User' => Session::user()->getKey(),
					'DateAction' => Request::get('date'),
					'Date' => date('Y-m-d H:i:s')
				]);

				$recouvrement->setJson('Scheduled', [
					'commentaire' => Request::get('commentaire'),
					'user' => Session::user()->getKey(),
				]);
				$recouvrementScheduled->save();
			}

			sendResponse([
				'msg' => 'Ok'
			]);
		}

		sendResponse(getView('encaissements/recouvrement_form_modal', [
			'inscription' => $inscription,
		], 'null_layout'));
	}


	function recouvrements()
	{
		$promotion = Promotion::promotion_actuelle();
		$recouvrements = Models\FIN\Recouvrement::query();

		$selectedAction = null;
		$periode =  null;
		$state =  'all';
		$filtre_periode =  null;

		if (isset($_GET['action']) && $_GET['action'] && $_GET['action'] != 'all') {
			$selectedAction = $_GET['action'];
			$recouvrements->where(['Action' => $selectedAction]);
		}


		if (($filtre_periode = isset($_GET['filtre_periode'])) && isset($_GET['periode']) && $_GET['periode']) {
			$periode  = $_GET['periode'];
			$start = explode(' - ', $periode)[0];
			$end = explode(' - ', $periode)[1];
			$recouvrements->between('DateAction', $start, $end);
		}

		if (isset($_GET['state']) && $_GET['state'] != 'all') {
			$state  = $_GET['state'];
			if ($state) {
				$recouvrements->whereNotNull('Done');
			} else {
				$recouvrements->whereNotNull('Scheduled')
					->whereNull('Done');
			}
		}


		if (isset($_GET['export_excel'])) {

			set_time_limit(-1);
			header('Content-Type: text/plain; charset=utf8');
			loadLib('phpexcel');

			if (!PHPExcel_Settings::setLocale('fr_FR'))
				echo 'Locale not set ' . PHP_EOL;

			$objPHPExcel = new PHPExcel();

			$activeSheet = $objPHPExcel->getActiveSheet();
			$activeSheet->setCellValue('A1', "Listes des recouvrements");

			$activeSheet->setCellValue('A1', "Action")->getStyle('A1')->getFont()->setBold(true);
			$activeSheet->setCellValue('B1', "Eleve")->getStyle('B1')->getFont()->setBold(true);
			$activeSheet->setCellValue('C1', "Parent")->getStyle()->getFont('C1')->setBold(true);
			$activeSheet->setCellValue('D1', "Collaborateur")->getStyle('D1')->getFont()->setBold(true);
			$activeSheet->setCellValue('E1', "Résultat")->getStyle('E1')->getFont()->setBold(true);
			$activeSheet->setCellValue('F1', "Date")->getStyle('F1')->getFont()->setBold(true);
			$activeSheet->setCellValue('G1', "Remarque")->getStyle('F1')->getFont()->setBold(true);

			$row = 2;
			foreach ($recouvrements->get() as $item) {
				$inscription =  $item->Inscription;
				$eleve =  $inscription->Eleve;
				$parents = $eleve->parents();
				$parent = $parents ? array_shift($parents) : null;
				$activeSheet->setCellValue("A$row", Models\FIN\Recouvrement::actions($item->get('Action'))['label']);
				$activeSheet->setCellValue("B$row", $eleve->get('User')->getNomComplet());
				$activeSheet->setCellValue("C$row", $parent ?  $parent->User->getNomComplet() : '---');
				$activeSheet->setCellValue("D$row", $item->User->getNomComplet());
				$activeSheet->setCellValue("E$row", $item->get('Done') ? $item->getJsonProperty('Done', 'response') : 'Planifier');
				$activeSheet->setCellValue("F$row", Tools::dateFormat($item->DateAction));
				$activeSheet->setCellValue("G$row", $item->getJsonProperty('Done') ? $item->getJsonProperty('Done', 'commentaire') ?? '----' : '');

				$row += 1;
			}

			foreach (range('A', 'U') as $col)
				$activeSheet->getColumnDimension($col)->setAutoSize(true);

			// redirect output to client browser
			$title = 'Listes des recouvrements ';
			$fileTitle = alais_string($title) . '_' . date('Ymd-His') . '.xlsx';
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="' . $fileTitle . '"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			ob_end_clean();
			$objWriter->save('php://output');
			exit;
		}


		return loadView('encaissements/recouvrements-list', array(
			'recouvrements' => $recouvrements->get(),
			'selectedAction' => $selectedAction,
			'periode' => $periode,
			'state' => $state,
			'filtre_periode' => $filtre_periode,
			'promotion' => $promotion,
		));
	}


	function avoirs()
	{

		$promotion = Promotion::promotion_actuelle();
		$avoirs = Models\FIN\Avoir::query()->get();

		return loadView('encaissements/avoirs-list', array(
			'avoirs' => $avoirs,
			'promotion' => $promotion,
		));
	}


	function avoirs_item($avoir)
	{

		$avoir = new Models\FIN\Avoir($avoir);


		if (Request::isPost()) {


			if (Request::has('validated')) {
				$avoir->setJson('Validated', array(
					'date' => date('Y-m-d H:i:s'),
					'comment' => Request::get('commentaire'),
					'user' =>  Session::user()->ID,
				));

				$avoir->saveHistory('validated');
			}


			if (Request::has('refused')) {

				$avoir->setJson('Refused', array(
					'date' => date('Y-m-d H:i:s'),
					'comment' => Request::get('commentaire'),
					'user' =>  Session::user()->ID,
				));
				if (Request::has('restore_encaissement')) {
					$avoir->restoreEncaissement();
				}

				$avoir->saveHistory('refused');
			}

			$avoir->save();

			URL::back();
		}

		return loadView('encaissements/avoirs-list-item-modal', array(
			'avoir' => $avoir,
		), 'null_layout');
	}

	function facture_global()
	{
		$promotions = Models\Promotion::getList();
		$encaissements = array();
		$eleves = array();
		$inscription = null;
		$parrainages = array();
		$paiementsResult = array();
		$rubriquesInscriptionList = array();
		$promotion =  Models\Promotion::promotion_actuelle();
		$grille = Models\FIN\RubriquePrice::grille();

		if (isset($_GET['promotion'])) {
			try {
				$promotion = new Models\Promotion($_GET['promotion']);
			} catch (Exception $e) {
				URL::redirect(URL::admin('encaissements/encaissements_facture_global'));
			}
		}

		$inscriptions = Models\Inscription::getList(array(
			'where' => array(
				'Promotion' => $promotion->get('ID')
			)
		));

		if (isset($_GET['inscription']) && $_GET['inscription'] && $promotion) {
			try {
				$inscription = new Models\Inscription($_GET['inscription']);

				if ($promotion->get('ID') != $inscription->get('Promotion')->get('ID')) {
					$inscription  = $inscription->get('Eleve')->getInscription($promotion);
				}
			} catch (Exception $e) {
				URL::redirect(URL::admin('encaissements/encaissements_facture_global'));
			}
		}

		if ($inscription && isset($_GET['export'])) {
			loadLib('tcpdf');
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('Nicola Asuni');
			$pdf->SetTitle('Recu');
			$pdf->SetSubject('TCPDF Tutorial');
			$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

			// remove default header/footer
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);

			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			$pdf->AddPage('P', 'A4');

			$html =  getView('encaissements/encaissements_facture_global_pdf', array(
				'eleve' => $inscription ? $inscription->get('Eleve') : null,
				'inscription' => $inscription,
				'niveau' => $inscription ? $inscription->get('Niveau') : null,
				'promotion' => $promotion,
				'promotions' => $promotions,
				'inscriptions' => $inscriptions,
				'grille' => $grille,
			), 'null_layout');

			// output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');

			$pdf->lastPage();
			ob_end_clean();
			$pdf->Output('dddd.pdf', 'I');
			exit;
		}



		loadView('encaissements/encaissements_facture_global', array(
			'eleve' => $inscription ? $inscription->get('Eleve') : null,
			'inscription' => $inscription,
			'niveau' => $inscription ? $inscription->get('Niveau') : null,
			'promotion' => $promotion,
			'promotions' => $promotions,
			'inscriptions' => $inscriptions,
			'grille' => $grille,
		));
	}

	function etat_services()
	{
		$promotions = Models\Promotion::getList();
		$promotion = Models\Promotion::actuelle();

		if (isset($_GET['promotion'])) {
			$promotion = new Models\Promotion($_GET['promotion']);
		}

		$months_list = _zero_months_list;

		$countInscription = Models\Inscription::getCount(
			array(
				'where' => array(
					'Promotion' => $promotion->get('ID')
				),
			)
		);

		$services = (object)array(
			'annules' => Models\FIN\EncaissementRubrique::annulesService(),
			'mensuels' => Models\FIN\EncaissementRubrique::mensuelServices(),
		);

		loadView('encaissements/etat-services', array(
			'promotion' => $promotion,
			'promotions' => $promotions,
			'months_list' => $months_list,
			'countInscription' => $countInscription,
			'services' => $services

		));
	}

	function etat_inscriptions_services()
	{
		$promotions = Models\Promotion::getList();
		$promotion = Models\Promotion::promotion_actuelle();
		$grille = Models\FIN\RubriquePrice::grille();
		$months_list = _months_list;
		$month   =  null;

		$services = (object)array(
			'annules' => Models\FIN\EncaissementRubrique::where(['Mensuel' => false])->get(),
			'mensuels' => Models\FIN\EncaissementRubrique::where(['Mensuel' => true])->get(),
		);

		$service =  isset($services->annules[0]) ? $services->annules[0] : $services->mensuels[0];


		$niveaux = Models\Niveau::getList();
		$niveau = $niveaux[0];

		if (isset($_GET['service'])) {
			$service =  new  Models\FIN\EncaissementRubrique($_GET['service']);
		}

		if (isset($_GET['promotion'])) {
			$promotion = new Models\Promotion($_GET['promotion']);
		}

		if (isset($_GET['month']) && $_GET['month'] != 'all') {
			$month = $_GET['month'];
		}


		$inscriptions = $service->taken_by_inscriptions($promotion, $month);

		$data = [
			'promotion' => $promotion,
			'promotions' => $promotions,
			'months_list' => $months_list,
			'month' => $month,
			'services' => $services,
			'service' => $service,
			'inscriptions' => $inscriptions,
			'grille' => $grille
		];

		if (isset($_GET['export'])) $this->export_inscriptions_services($data);
		if (isset($_GET['export_excel'])) $this->export_excel_inscriptions_services($data);
		if (isset($_GET['export_classe'])) $this->export_inscriptions_services_classe($data);


		loadView('encaissements/etat-inscriptions-services', $data);
	}

	private function export_inscriptions_services_classe($data)
	{
		$inscriptions = $data['inscriptions'];
		$inscriptionsTable = [];
		$undefined = ['classe' => null, 'inscriptions' => []];

		foreach ($inscriptions as $inscription) {
			$classe = $inscription->Classe;
			if ($classe) {
				if (!isset($inscriptionsTable[$classe->ID])) $inscriptionsTable[$classe->ID] = ['classe' => $classe, 'inscriptions' => []];
				$inscriptionsTable[$classe->ID]['inscriptions'][] = $inscription;
			} else {
				$undefined['inscriptions'][] = $inscription;
			}
		}

		if (!empty($undefined['inscriptions'])) $inscriptionsTable['undefined'] = $undefined;

		$data['inscriptionsTable'] = $inscriptionsTable;

		$title = 'etat inscriptions services par classe';

		set_time_limit(-1);
		loadLib('tcpdf');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$fontArabicBold = TCPDF_FONTS::addTTFfont(_basepath . 'assets/fonts/ADOBEARABIC-BOLD.OTF', 'TrueTypeUnicode', '', 96);
		$pdf->SetFont($fontArabicBold, '', 14, '', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('');
		$pdf->SetTitle($title);
		$pdf->SetSubject('');
		$pdf->SetKeywords('');
		// remove default header/footer
		$pdf->setPrintHeader(false);
		// $pdf->setPrintFooter(false);
		// set header and footer fonts
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		// set margins
		$pdf->SetMargins(5, 3, 5);
		// set image scale factor
		//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->AddPage('P', 'A4');
		$html = getView('encaissements/etat-inscriptions-services-classe-pdf', $data, 'null_layout');

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();
		ob_end_clean();
		// ---------------------------------------------------------

		//Close and output PDF document
		$fileTitle = strtolower(str_replace(' ', '_', $title)) . ' ' . date('Ymd-His') . '.pdf';
		$pdf->Output($fileTitle, 'I');
	}

	private function export_inscriptions_services($data)
	{
		$title = 'etat inscriptions services';

		set_time_limit(-1);
		loadLib('tcpdf');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$fontArabicBold = TCPDF_FONTS::addTTFfont(_basepath . 'assets/fonts/ADOBEARABIC-BOLD.OTF', 'TrueTypeUnicode', '', 96);
		$pdf->SetFont($fontArabicBold, '', 14, '', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('');
		$pdf->SetTitle($title);
		$pdf->SetSubject('');
		$pdf->SetKeywords('');
		// remove default header/footer
		$pdf->setPrintHeader(false);
		// $pdf->setPrintFooter(false);
		// set header and footer fonts
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		// set margins
		$pdf->SetMargins(5, 3, 5);
		// set image scale factor
		//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->AddPage('P', 'A4');
		$html = getView('encaissements/etat-inscriptions-services-pdf', $data, 'null_layout');

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();
		ob_end_clean();
		// ---------------------------------------------------------

		//Close and output PDF document
		$fileTitle = strtolower(str_replace(' ', '_', $title)) . ' ' . date('Ymd-His') . '.pdf';
		$pdf->Output($fileTitle, 'I');
	}

	private function export_excel_inscriptions_services($data)
	{
		$title = 'Etat des services par élève';
		set_time_limit(-1);
		header('Content-Type: text/plain; charset=utf8');
		loadLib('phpexcelsheet');


		$objPHPExcel = new PHPExcel();
		$activeSheet = $objPHPExcel->getActiveSheet();
		$activeSheet->mergeCells('A1:D1');

		$activeSheet->getStyle("A1:d1")->applyFromArray(array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		));

		$activeSheet->setCellValue('A1', $title);
		$activeSheet->setCellValue('A3', "Nom et prénom ");
		$activeSheet->setCellValue('B3', "Classe");
		$activeSheet->setCellValue('C3', "Adresse");
		$activeSheet->setCellValue('D3', "Etat");



		$row = 4;
		foreach ($data['inscriptions'] as $inscription) {

			$total_payer =  Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($inscription,  $data['month'], $data['service']);
			$total =  Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($inscription,  $data['month'], $data['service']);

			$activeSheet->setCellValue('A' . $row, $inscription->get('Eleve')->get('User')->getNomComplet());
			$activeSheet->setCellValue('B' . $row, $inscription->get('Niveau')->get('Label') . ' ' . ($inscription->get('Classe') ? ' - ' . $inscription->get('Classe')->get('Label') : ''));
			$activeSheet->setCellValue('C' . $row, $inscription->get('Eleve')->get('User') ? $inscription->get('Eleve')->get('User')->get('Adresse') : '---');
			$activeSheet->setCellValue('D' . $row, $total <= $total_payer ? 'Payé' : 'Impayé ');

			$row++;
		}


		foreach (range('A', 'U') as $col) {
			$activeSheet->getColumnDimension($col)->setAutoSize(true);
		}

		// redirect output to client browser
		$fileTitle = alais_string($title) . '_' . date('Ymd-His') . '.xlsx';
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $fileTitle . '"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		$objWriter->save('php://output');
		exit;
	}


	function etat_inscriptions_services_transport()
	{
		$promotions = Models\Promotion::getList();
		$promotion = Models\Promotion::promotion_actuelle();
		$grille = Models\FIN\RubriquePrice::grille();
		$months_list = _months_list;
		$month   =  null;

		$services = (object)array(
			// 'annules' => Models\FIN\EncaissementRubrique::where(['Optionnel' => true, 'Mensuel' => false  ])->get(),
			'annules' => [],
			'mensuels' => Models\FIN\EncaissementRubrique::where(['Optionnel' => true, 'Mensuel' => true, 'Function' => 'transport'])->get(),
		);

		$service =  isset($services->mensuels[0]) ? $services->mensuels[0] : null;


		$niveaux = Models\Niveau::getList();
		$niveau = $niveaux[0];

		if (isset($_GET['service'])) {
			$service =  new  Models\FIN\EncaissementRubrique($_GET['service']);
		}

		if (isset($_GET['promotion'])) {
			$promotion = new Models\Promotion($_GET['promotion']);
		}

		if (isset($_GET['month']) && $_GET['month'] != 'all') {
			$month = $_GET['month'];
		}


		$inscriptions = $service ? $service->taken_by_inscriptions($promotion, $month) : [];

		$data = [
			'promotion' => $promotion,
			'promotions' => $promotions,
			'months_list' => $months_list,
			'month' => $month,
			'services' => $services,
			'service' => $service,
			'inscriptions' => $inscriptions,
			'grille' => $grille
		];

		if (isset($_GET['export'])) $this->export_inscriptions_services_transport($data);
		if (isset($_GET['export_excel'])) $this->export_excel_inscriptions_services_transport($data);
		if (isset($_GET['export_classe'])) $this->export_inscriptions_services_classe_transport($data);


		loadView('encaissements/etat-inscriptions-services-transport', $data);
	}

	private function export_inscriptions_services_classe_transport($data)
	{
		$inscriptions = $data['inscriptions'];
		$inscriptionsTable = [];
		$undefined = ['classe' => null, 'inscriptions' => []];

		foreach ($inscriptions as $inscription) {
			$classe = $inscription->Classe;
			if ($classe) {
				if (!isset($inscriptionsTable[$classe->ID])) $inscriptionsTable[$classe->ID] = ['classe' => $classe, 'inscriptions' => []];
				$inscriptionsTable[$classe->ID]['inscriptions'][] = $inscription;
			} else {
				$undefined['inscriptions'][] = $inscription;
			}
		}

		if (!empty($undefined['inscriptions'])) $inscriptionsTable['undefined'] = $undefined;

		$data['inscriptionsTable'] = $inscriptionsTable;

		$title = 'etat inscriptions services par classe';

		set_time_limit(-1);
		loadLib('tcpdf');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$fontArabicBold = TCPDF_FONTS::addTTFfont(_basepath . 'assets/fonts/ADOBEARABIC-BOLD.OTF', 'TrueTypeUnicode', '', 96);
		$pdf->SetFont($fontArabicBold, '', 14, '', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('');
		$pdf->SetTitle($title);
		$pdf->SetSubject('');
		$pdf->SetKeywords('');
		// remove default header/footer
		$pdf->setPrintHeader(false);
		// $pdf->setPrintFooter(false);
		// set header and footer fonts
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		// set margins
		$pdf->SetMargins(5, 3, 5);
		// set image scale factor
		//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->AddPage('P', 'A4');
		$html = getView('encaissements/etat-inscriptions-services-classe-pdf', $data, 'null_layout');

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();
		ob_end_clean();
		// ---------------------------------------------------------

		//Close and output PDF document
		$fileTitle = strtolower(str_replace(' ', '_', $title)) . ' ' . date('Ymd-His') . '.pdf';
		$pdf->Output($fileTitle, 'I');
	}

	private function export_inscriptions_services_transport($data)
	{
		$title = 'etat inscriptions services';

		set_time_limit(-1);
		loadLib('tcpdf');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$fontArabicBold = TCPDF_FONTS::addTTFfont(_basepath . 'assets/fonts/ADOBEARABIC-BOLD.OTF', 'TrueTypeUnicode', '', 96);
		$pdf->SetFont($fontArabicBold, '', 14, '', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('');
		$pdf->SetTitle($title);
		$pdf->SetSubject('');
		$pdf->SetKeywords('');
		// remove default header/footer
		$pdf->setPrintHeader(false);
		// $pdf->setPrintFooter(false);
		// set header and footer fonts
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		// set margins
		$pdf->SetMargins(5, 3, 5);
		// set image scale factor
		//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->AddPage('P', 'A4');
		$html = getView('encaissements/etat-inscriptions-services-pdf', $data, 'null_layout');

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();
		ob_end_clean();
		// ---------------------------------------------------------

		//Close and output PDF document
		$fileTitle = strtolower(str_replace(' ', '_', $title)) . ' ' . date('Ymd-His') . '.pdf';
		$pdf->Output($fileTitle, 'I');
	}

	private function export_excel_inscriptions_services_transport($data)
	{
		$title = 'Etat des services par élève';
		set_time_limit(-1);
		header('Content-Type: text/plain; charset=utf8');
		loadLib('phpexcelsheet');


		$objPHPExcel = new PHPExcel();
		$activeSheet = $objPHPExcel->getActiveSheet();
		$activeSheet->mergeCells('A1:D1');

		$activeSheet->getStyle("A1:d1")->applyFromArray(array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		));

		$activeSheet->setCellValue('A1', $title);
		$activeSheet->setCellValue('A3', "Nom et prénom ");
		$activeSheet->setCellValue('B3', "Classe");
		$activeSheet->setCellValue('C3', "Adresse");
		$activeSheet->setCellValue('D3', "Etat");



		$row = 4;
		foreach ($data['inscriptions'] as $inscription) {

			$total_payer =  Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($inscription,  $data['month'], $data['service']);
			$total =  Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($inscription,  $data['month'], $data['service']);

			$activeSheet->setCellValue('A' . $row, $inscription->get('Eleve')->get('User')->getNomComplet());
			$activeSheet->setCellValue('B' . $row, $inscription->get('Niveau')->get('Label') . ' ' . ($inscription->get('Classe') ? ' - ' . $inscription->get('Classe')->get('Label') : ''));
			$activeSheet->setCellValue('C' . $row, $inscription->get('Eleve')->get('User') ? $inscription->get('Eleve')->get('User')->get('Adresse') : '---');
			$activeSheet->setCellValue('D' . $row, $total <= $total_payer ? 'Payé' : 'Impayé ');

			$row++;
		}


		foreach (range('A', 'U') as $col) {
			$activeSheet->getColumnDimension($col)->setAutoSize(true);
		}

		// redirect output to client browser
		$fileTitle = alais_string($title) . '_' . date('Ymd-His') . '.xlsx';
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $fileTitle . '"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		$objWriter->save('php://output');
		exit;
	}

	function etat_eleves_services()
	{
		$promotions = Models\Promotion::getList();
		$promotion = Models\Promotion::actuelle();
		$grille = Models\FIN\RubriquePrice::grille();
		$classes = Models\Classe::all(array('where' => array('Promotion' => $promotion->ID)));
		$months_list = _months_list;
		$month   =  null;
		$selectedServices = array();
		$selectedServicesID = array();
		$services = Models\FIN\EncaissementRubrique::all(array('where' => array('Optionnel' => true)));

		if (isset($_GET['service'])) {
			$selectedServicesID =  (array)$_GET['service'];
			foreach ($selectedServicesID as $id) {
				$selectedServices[] =  new Models\FIN\EncaissementRubrique($id);
			}
		}

		if (isset($_GET['promotion'])) {
			$promotion = new Models\Promotion($_GET['promotion']);
		}

		if (isset($_GET['month']) && $_GET['month'] != 'all') {
			$month = $_GET['month'];
		}

		$inscriptions = Models\FIN\EncaissementRubrique::services_taken_by_inscriptions($promotion, isset($_GET['service']) && $_GET['service']  ? implode(",", $_GET['service']) : null, $month);



		if ($inscriptions && isset($_GET['export'])) {
			ini_set('max_execution_time', 1800);
			loadLib('tcpdf');
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('Nicola Asuni');
			$pdf->SetTitle('Recu');
			$pdf->SetSubject('TCPDF Tutorial');
			$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

			// remove default header/footer
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);

			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			$pdf->AddPage('P', 'A4');

			$html =  getView('encaissements/etat-eleves-services-pdf', array(
				'promotion' => $promotion,
				'promotions' => $promotions,
				'months_list' => $months_list,
				'month' => $month,
				'services' => $services,
				'selectedServicesID' => $selectedServicesID,
				'selectedServices' => $selectedServices,
				'classes' => $classes,
				'inscriptions' => $inscriptions,
				'grille' => $grille
			), 'null_layout');
			// echo $html;
			// exit;
			// output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');

			$pdf->lastPage();
			ob_end_clean();
			$pdf->Output('dddd.pdf', 'I');
			exit;
		}


		loadView('encaissements/etat-eleves-services', array(
			'promotion' => $promotion,
			'promotions' => $promotions,
			'months_list' => $months_list,
			'month' => $month,
			'services' => $services,
			'selectedServicesID' => $selectedServicesID,
			'selectedServices' => $selectedServices,
			'classes' => $classes,
			'inscriptions' => $inscriptions,
			'grille' => $grille
		));
	}

	function _etat_impayes()
	{
		$promotions = Models\Promotion::getList();
		$promotion = Models\Promotion::promotion_actuelle();
		$grille = Models\FIN\RubriquePrice::grille();
		$months_list = _months_list;
		$month   =  null;
		$niveau = null;
		$service =  null;
		$select_pre_month = null;
		$services = (object)array(
			'annules' => Models\FIN\EncaissementRubrique::annulesService(),
			'mensuels' => Models\FIN\EncaissementRubrique::mensuelServices(),
		);


		$niveaux = Models\Niveau::getList();

		if (!isset($_GET['niveau'])) {
			$niveau = $niveaux[0];
		}

		if (isset($_GET['service']) && $_GET['service'] != 'all') {
			$service =  new  Models\FIN\EncaissementRubrique($_GET['service']);
		}

		if (isset($_GET['niveau']) && $_GET['niveau'] != 'all') {
			$niveau =  new  Niveau($_GET['niveau']);
		}

		if (isset($_GET['promotion'])) {
			$promotion = new Models\Promotion($_GET['promotion']);
		}

		if (isset($_GET['month']) && $_GET['month'] != 'all') {
			$month = $_GET['month'];
		}

		if (isset($_GET['select_pre_month']) && $_GET['select_pre_month']) {
			$select_pre_month = true;
		}
		$inscriptions = Models\FIN\EncaissementRubrique::unpaidInscriptions($promotion, $service, $month, $niveau, $select_pre_month);


		loadView('encaissements/etat-impayes', array(
			'promotion' => $promotion,
			'promotions' => $promotions,

			'months_list' => $months_list,
			'month' => $month,

			'services' => $services,
			'service' => $service,

			'niveaux' => $niveaux,
			'niveau' => $niveau,

			'grille' => $grille,
			'inscriptions' => $inscriptions,
			'select_pre_month' => $select_pre_month
		));
	}


	function etat_cheques()
	{
		$banques = Models\FIN\Banque::getList();
		$banque = null;
		$periode =  date('Y-m-d') . " - " . date('Y-m-d', strtotime("+1 week"));

		if (isset($_GET['banque']) && $_GET['banque'] && $_GET['banque'] != 'all') {
			$banque = new Models\FIN\Banque($_GET['banque']);
		}

		if (isset($_GET['periode']) && $_GET['periode']) {
			$periode  = $_GET['periode'];
		}
		$start = explode(' - ', $periode)[0];
		$end = explode(' - ', $periode)[1];
		$encaissements = Models\FIN\Encaissement::getEncaissementsBy('cheque', $banque, $start, $end, null, Request::get('tireur'), Request::get('numCheque'));



		if (Request::isPost()) {
			$encaissements_depots  =  Models\FIN\Encaissement::query()->whereIn('ID', Request::get('encaissements') ?: [])->get();
			$referenceDepot =  time();
			$dateDepot = Request::get('date_depot');
			$commentaire = Request::get('commentaire');
			$depots_cheques = [];
			foreach ($encaissements_depots as $enc) {
				$cheque_deposed = Models\FIN\ChequeDepots::where(['Encaissement' => $enc->getKey()])->first();
				if (!$cheque_deposed || ($cheque_deposed && $cheque_deposed->Impayes)) {
					$mode = $enc->getDetailmode();
					$depots_cheques[] = Models\FIN\ChequeDepots::create([
						'Encaissement' => $enc,
						'Promotion' => $enc->Promotion,
						'User' => Session::user(),
						'Banque' => $mode->get('Banque'),
						'ReferenceDepot' => $referenceDepot,
						'DateDepot' => $dateDepot,
						'Commentaire' => $commentaire,
						'NumeroCheque' => $mode->get('NumCheque'),
						'Montant' => $enc->get('Montant'),
						'Tireur' => $mode->get('Tireur'),
					]);
				}
			}

			if (isset($_POST['imprimer_etat'])) {

				set_time_limit(-1);
				loadLib('tcpdf');
				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$fontArabicBold = TCPDF_FONTS::addTTFfont(_basepath . 'assets/fonts/ADOBEARABIC-BOLD.OTF', 'TrueTypeUnicode', '', 96);
				$pdf->SetFont($fontArabicBold, '', 14, '', false);

				// set document information
				$pdf->SetCreator(PDF_CREATOR);
				$pdf->SetAuthor('');
				$pdf->SetTitle('etat_cheques');
				$pdf->SetSubject('');
				$pdf->SetKeywords('');

				// remove default header/footer
				$pdf->setPrintHeader(false);
				// $pdf->setPrintFooter(false);

				// set header and footer fonts
				$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
				// set margins
				$pdf->SetMargins(5, 3, 5);

				// set image scale factor
				$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
				$pdf->AddPage('A4');


				$html = getView('encaissements/etat-depots-cheques-pdf', [
					'depots_cheques' => $depots_cheques
				], 'null_layout');
				// echo $html;
				// exit;
				$pdf->writeHTML($html, true, false, true, false, '');
				$pdf->lastPage();
				ob_end_clean();
				// ---------------------------------------------------------

				//Close and output PDF document
				$pdf->Output('fiche-etat-cheques.pdf', 'I');
			}

			URL::back();
		}




		$data =   array(
			'banques' => $banques,
			'banque' => $banque,
			'periode' => $periode,
			'encaissements' => $encaissements,
			'tireur' => Request::get('tireur'),
			'numCheque' => Request::get('numCheque'),
		);

		loadView('encaissements/etat-cheques', $data);
	}

	function cheques_deposes()
	{

		$cheques_deposer = Models\FIN\ChequeDepots::all();
		$data =   array(
			'cheques_deposer' => $cheques_deposer,
		);
		loadView('encaissements/cheques-deposes', $data);
	}

	function operations_banques()
	{

		$banques = Models\FIN\Banque::getList();

		// $periode =  date('Y-m-d') . " - " . date('Y-m-d', strtotime("+1 week"));
		// if (isset($_GET['periode']) && $_GET['periode']) {
		// 	$periode  = $_GET['periode'];
		// }

		// $start = explode(' - ', $periode)[0];
		// $end = explode(' - ', $periode)[1];
		$encaissements = Models\FIN\Encaissement::getEncaissementsBy(['tpe', 'virement', 'versement'], null, null, null, false);


		$data =   array(
			'banques' => $banques,
			//'periode' => $periode,
			'encaissements' => $encaissements,
		);

		loadView('encaissements/operations-banques', $data);
	}


	function traite_operation_banque($fncId)
	{
		$encaissement  =  new Models\FIN\Encaissement($fncId);

		if (Request::isPost()) {
			DB::begin();

			$msg = 'Encaissé';
			if (Request::get('encaisse')) {
				$msg = 'Encaissé';
				$encaissement->set('Encaisse', date('Y-m-d H:i:s'))
					->set('EncaisseDate',  Request::get('transition_date'))
					->set('NumOperation', Request::get('transition_nemuro'))
					->set('EncaisseBy', Session::user())
					->save();
			} else {


				foreach ($encaissement->encaissementLignes() as  $ligne) {
					$ligne->setJson('Canceled', array(
						'date' => date('Y-m-d H:i:s'),
						'user' => Session::user()->ID,
					));
					$ligne->save();
				}

				$encaissement->setJson('Canceled', array(
					'date' => date('Y-m-d H:i:s'),
					'comment' => Request::get('impayer_commentaire'),
					'user' =>  Session::user()->ID,
				));

				$encaissement->save();
			}

			Models\ActionLog::catchLog(($encaissement->Canceled ? "Opération non encaissée" : "Encaissement de l’opération") . $encaissement->get('PaiementMode') . " , numéro de reçu " . $encaissement->get('NumRecu') . " montant de  " . $encaissement->get('Montant') . " par " . (Session::user()->getNomComplet()));
			DB::commit();
			sendResponse([
				'msg' => $msg,
			]);
		}

		sendResponse(getView('encaissements/traite-operation-banque', [
			'encaissement' => $encaissement,
		], 'null_layout'));
	}


	function traite_depot_cheques($depot_cheque)
	{
		$depot_cheque  =  new Models\FIN\ChequeDepots($depot_cheque);


		if (Request::isPost()) {
			DB::begin();

			$msg = 'Encaissé';
			if (Request::get('encaisse')) {

				$msg = 'Encaissé';
				$depot_cheque->setJson('Encaisse', [
					'user' => Session::user(),
					'date' => Request::get('transition_date'),
					'transition_nemuro' => Request::get('transition_nemuro'),
					'date_action' => date('Y-m-d H:i:s'),
				]);
				$depot_cheque->save();

				$encaissement = $depot_cheque->Encaissement;

				$encaissement->set('Encaisse', date('Y-m-d H:i:s'))
					->set('EncaisseDate',  Request::get('transition_date'))
					->set('NumOperation', Request::get('transition_nemuro'))
					->set('EncaisseBy', Session::user())
					->save();
			} else {

				$msg = 'Impayé';
				$depot_cheque->setJson('Impayes', [
					'user' => Session::user()->ID,
					'date' => Request::get('impayer_date'),
					'impayer_commentaire' => Request::get('impayer_commentaire'),
					'canceled_encaissement' => Request::get('impayer_canceled_encaissement'),
					'date_action' => date('Y-m-d H:i:s'),
				]);

				$depot_cheque->save();

				if (Request::get('impayer_canceled_encaissement')) {
					$encaissement = $depot_cheque->Encaissement;
					foreach ($encaissement->encaissementLignes() as  $ligne) {
						$ligne->setJson('Canceled', array(
							'date' => date('Y-m-d H:i:s'),
							'user' => Session::user()->ID,
						));
						$ligne->save();
					}

					$encaissement->setJson('Canceled', array(
						'date' => date('Y-m-d H:i:s'),
						'comment' => Request::get('impayer_commentaire'),
						'user' =>  Session::user()->ID,
					));

					$encaissement->save();
				}
			}

			DB::commit();
			sendResponse([
				'msg' => $msg,
			]);
		}

		sendResponse(getView('encaissements/traite-depot-cheques', [
			'depot_cheque' => $depot_cheque,
		], 'null_layout'));
	}

	function __etat_remises()
	{

		$months_list = _months_list;
		$promotions = Models\Promotion::getList();
		$niveaux = Models\Niveau::getList();
		$classes = Models\Classe::getList();
		$services = Models\FIN\EncaissementRubrique::getList();
		$promotion = Models\Promotion::actuelle();


		$selectedNiveau = isset($_GET['niveau']) ? $_GET['niveau'] : $niveaux[0]->ID;
		$selectedClasse = isset($_GET['classe']) ? $_GET['classe'] : null;
		$_promotion = isset($_GET['promotion']) &&  $_GET['promotion'] ? $_GET['promotion'] : null;
		$where = array();

		if ($_promotion) {
			$promotion = new Models\Promotion($_promotion);
		}

		if ($selectedNiveau && $selectedNiveau != 'all') {
			$where['Niveau'] =  $selectedNiveau;
		}

		if ($selectedClasse && $selectedClasse != 'all') {
			$where['Classe'] =  $selectedClasse;
		}

		$where['Promotion'] =  $promotion->get('ID');

		// dd($where);
		$inscriptions = Models\Inscription::getList(
			array(
				'where' => $where
			)
		);

		if (isset($_GET['print_pdf'])) {

			ini_set('memory_limit', '-1');
			set_time_limit(20);
			loadLib('tcpdf');
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('BELHARRADI JAMAL');
			$pdf->SetTitle('Etat impayer');
			$pdf->SetSubject('Etat impayer');
			$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

			// remove default header/footer
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);

			// set default monospaced font
			// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// set margins
			// $pdf->SetMargins(19, 70, 19, 0);

			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			// ---------------------------------------------------------
			$pdf->AddPage('P', 'A4');

			$html = getView('encaissements/etat-remises-pdf', array(
				'months_list' => $months_list,
				'promotion' => $promotion,
				'promotions' => $promotions,
				'inscriptions' => $inscriptions,
				'niveaux' =>	$niveaux,
				'classes' =>	$classes,
				'selectedNiveau' => new Models\Niveau($selectedNiveau),
				'selectedClasse' => new Models\Classe($selectedClasse),
				'services' => $services,

			), 'null_layout');

			ob_start();
			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->lastPage();
			ob_clean();

			$pdf->Output('etat-remises.pdf', 'I');
		}

		loadView('encaissements/etat-remises', array(
			'months_list' => $months_list,
			'promotion' => $promotion,
			'promotions' => $promotions,
			'inscriptions' => $inscriptions,
			'niveaux' =>	$niveaux,
			'classes' =>	$classes,
			'selectedNiveau' => $selectedNiveau,
			'selectedClasse' => $selectedClasse,
			'services' => $services,


		));
	}

	function etat_remises()
	{

		$months_list = _months_list;
		$promotions = Models\Promotion::getList();
		$niveaux = Models\Niveau::getList();
		$classes = Models\Classe::getList();
		$cycles = Models\Cycle::getList();
		$discounts = Models\FIN\DiscountBase::getList();
		$services = Models\FIN\EncaissementRubrique::getList();
		$promotion = Models\Promotion::actuelle();


		$selectedNiveau = isset($_GET['niveau']) ? $_GET['niveau'] : $niveaux[0]->ID;
		$selectedClasse = isset($_GET['classe']) ? $_GET['classe'] : null;
		$selectedCycle = isset($_GET['cycle']) ? $_GET['cycle'] : null;
		$selectedDiscount = isset($_GET['discount']) ? $_GET['discount'] : null;

		$_promotion = isset($_GET['promotion']) &&  $_GET['promotion'] ? $_GET['promotion'] : null;
		$where = array();

		if ($_promotion) {
			$promotion = new Models\Promotion($_promotion);
		}

		if ($selectedNiveau && $selectedNiveau != 'all') {
			$where['Niveau'] =  $selectedNiveau;
		}

		if ($selectedClasse && $selectedClasse != 'all') {
			$where['Classe'] =  $selectedClasse;
		}

		if ($selectedDiscount && $selectedDiscount != 'all') {
			$where[] = "(`ID` IN (SELECT `Inscription` FROM `fnc_encaissementinscriptions` WHERE `Discounts` LIKE '%\"" . $selectedDiscount . "\"%' ))";
		}

		if ($selectedCycle && $selectedCycle != 'all') {
			$where[] = "(`Niveau` IN (SELECT `ID` FROM `gen_niveaux` WHERE `Cycle` = " . $selectedCycle . " ))";
		}

		$where['Promotion'] =  $promotion->get('ID');

		$inscriptions = Models\Inscription::getList(
			array(
				'where' => $where
			)
		);


		// dd($inscriptions);

		$results = [
			'services_remises' => [],
			'inscriptions' => [],
			'total_remises' => 0,
		];

		foreach ($inscriptions as $inscription) {

			if (!isset($results['inscriptions'][$inscription->getKey()])) {
				$results['inscriptions'][$inscription->getKey()] = [
					'inscription' => $inscription,
					'services' => [],
					'total_remises' => [],
				];
			}


			$total_eleve_remise = 0;
			foreach ($services as $service) {

				$inscriptionRubrique = Models\FIN\EncaissementRubriqueInscription::inscriptionHasRubrique($inscription, $service);
				$montantDefaut =  $service->getGrilleAmount($inscription->Promotion, $inscription->Niveau, $inscription->getSite());
				$is_remise =  $inscriptionRubrique && $inscriptionRubrique->Montant != $montantDefaut;
				$remise = 0;
				if ($is_remise) {
					$remise  =  $montantDefaut - $inscriptionRubrique->get('Montant');
				}
				$annule_remise  =  $service->Mensuel ?  $remise * count($inscription->Promotion->months()->list) : $remise;

				if (!isset($results['inscriptions'][$inscription->getKey()]['services'][$service->getKey()])) {
					$results['inscriptions'][$inscription->getKey()]['services'][$service->getKey()] = [];
				}

				$results['inscriptions'][$inscription->getKey()]['services'][$service->getKey()] = [
					'service' => $service,
					'montant_defaut' => $montantDefaut,
					'inscription_rubrique' => $inscriptionRubrique,
					'is_remise' => $is_remise,
					'remise' => $remise,
					'annuel_remise' => $annule_remise,
				];

				if (!isset($results['services_remises'][$service->getKey()])) {
					$results['services_remises'][$service->getKey()] =	0;
				}

				$results['services_remises'][$service->getKey()] +=	$annule_remise;
				$total_eleve_remise += 	$annule_remise;
			}

			$results['inscriptions'][$inscription->getKey()]['total_remises'] = $total_eleve_remise;
			$results['total_remises'] += $total_eleve_remise;
		}

		if (isset($_GET['print_pdf'])) {

			ini_set('memory_limit', '-1');
			set_time_limit(20);
			loadLib('tcpdf');
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('BELHARRADI JAMAL');
			$pdf->SetTitle('Etat des remises');
			$pdf->SetSubject('Etat des remises');

			// remove default header/footer
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);

			// set default monospaced font
			// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// set margins
			// $pdf->SetMargins(19, 70, 19, 0);

			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			// ---------------------------------------------------------
			$pdf->AddPage('L', 'A4');

			$html = getView('encaissements/new-etat-remises-pdf', array(
				'months_list' => $months_list,
				'promotion' => $promotion,
				'promotions' => $promotions,
				'inscriptions' => $inscriptions,
				'niveaux' =>	$niveaux,
				'classes' =>	$classes,
				'discounts' =>	$discounts,
				'cycles' =>	$cycles,
				'selectedCycle' => $selectedCycle,
				'selectedNiveau' => new Models\Niveau($selectedNiveau),
				'selectedClasse' => $selectedClasse && $selectedClasse != 'all' ? new Models\Classe($selectedClasse) : null,
				'selectedDiscount' => $selectedDiscount,
				'services' => $services,
				'results' => $results
			), 'null_layout');

			ob_start();
			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->lastPage();
			ob_clean();
			$pdf->Output('etat-remises.pdf', 'I');
		}



		if (isset($_GET['export_excel'])) {


			set_time_limit(-1);
			header('Content-Type: text/plain; charset=utf8');
			loadLib('phpexcel');
			if (!PHPExcel_Settings::setLocale('fr_FR'))
				echo 'Locale not set ' . PHP_EOL;

			$objPHPExcel = new PHPExcel();
			$activeSheet = $objPHPExcel->getActiveSheet();
			// $activeSheet->setCellValue('A1', "Etat des encaissements");
			// $activeSheet->mergeCells('A1:F3');


			$activeSheet->setCellValue('A1', "Elève");
			$activeSheet->setCellValue('A2', "Total remises services");
			$activeSheet->setCellValue('B1', "Total des remises / elève");
			$activeSheet->setCellValue('B2', Tools::numberformat($results["total_remises"]));

			$alphabets = range('A', 'Z');


			$index = 2;
			// http://localhost/botischool/admin/encaissements/etat_remises?export_excel
			foreach ($services as $s) {
				if ($results['services_remises'][$s->getKey()] != 0) {
					if ($s->Mensuel == "1") {
						$next = $index + 2;
					} else {
						$next = $index + 1;
					}
					$activeSheet->mergeCells("$alphabets[$index]1:$alphabets[$next]1")->setCellValue("$alphabets[$index]1", $s->Label);
					$activeSheet->mergeCells("$alphabets[$index]2:$alphabets[$next]2")->setCellValue("$alphabets[$index]2", Tools::numberformat($results['services_remises'][$s->getKey()]));
					$index = $next + 1;
					// $activeSheet->setCellValue("$alphabets[$index]1", $s->Label);
					// $activeSheet->setCellValue("$alphabets[$index]2", $results['services_remises'][$s->getKey()]);
					// $index++;
				}
			}

			$index1 = 3;
			foreach ($results['inscriptions'] as $inscription_data) {
				$inscription = $inscription_data['inscription'];
				$next = $index1 + 1;
				$activeSheet->mergeCells("A$index1:A$index1")->setCellValue("A$index1", $inscription->Eleve->User->getNomComplet());
				$activeSheet->mergeCells("B$index1:B$index1")->setCellValue("B$index1", Tools::numberformat($inscription_data['total_remises']));

				$indexAlp = 2;
				foreach ($inscription_data['services'] as $service_data) {
					$s = $service_data['service'];
					$inscriptionRubrique = $service_data['inscription_rubrique'];
					if ($results['services_remises'][$s->getKey()]) {
						if ($inscriptionRubrique) {

							// print_r($inscriptionRubrique." | ");
							if ($s->Mensuel == "1") {
								$cellValue = "Remise mensuelle :" . Tools::numberFormat($service_data['remise']) . " | " . "Remise annuelle :" . Tools::numberFormat($service_data['annuel_remise']) . " | " . "Frais de service :" . Tools::numberFormat($service_data['montant_defaut']);
								$activeSheet->setCellValue($alphabets[$indexAlp] . $index1, "Frais de service");
								$activeSheet->setCellValue($alphabets[$indexAlp + 1] . $index1, "Remise mensuelle");
								$activeSheet->setCellValue($alphabets[$indexAlp + 2] . $index1, "Remise annuelle");
								$activeSheet->setCellValue($alphabets[$indexAlp] . ($index1 + 1), Tools::numberFormat($service_data['montant_defaut']));
								$activeSheet->setCellValue($alphabets[$indexAlp + 1] . ($index1 + 1), Tools::numberFormat($service_data['remise']));
								$activeSheet->setCellValue($alphabets[$indexAlp + 2] . ($index1 + 1), Tools::numberFormat($service_data['annuel_remise']));
								$indexAlp = $indexAlp + 3;
							} else {
								$cellValue = "Remise totale :" . Tools::numberFormat($service_data['annuel_remise']) . " | " . "Frais de service :" . Tools::numberFormat($service_data['montant_defaut']);
								$activeSheet->setCellValue($alphabets[$indexAlp] . ($index1), "Frais de service");
								$activeSheet->setCellValue($alphabets[$indexAlp + 1] . ($index1), "Remise totale");
								$activeSheet->setCellValue($alphabets[$indexAlp] . ($index1 + 1), Tools::numberFormat($service_data['montant_defaut']));
								$activeSheet->setCellValue($alphabets[$indexAlp + 1] . ($index1 + 1), Tools::numberFormat($service_data['annuel_remise']));
								$indexAlp = $indexAlp + 2;
							}
							// $activeSheet->setCellValue("$alphabets[$indexAlp]$index1", $cellValue);	
						}
					}
				}

				$index1 = $next + 1;
			}


			foreach (range('A', 'U') as $col)
				$activeSheet->getColumnDimension($col)->setAutoSize(true);

			// redirect output to client browser
			$title = 'Etat des remises';
			$fileTitle = alais_string($title) . '_' . date('Ymd-His') . '.xlsx';
			// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); didn't work on windows
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="' . $fileTitle . '"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			ob_end_clean();
			$objWriter->save('php://output');
			// print_r($objPHPExcel);
			exit;
		}

		loadView('encaissements/new-etat-remises', array(
			'months_list' => $months_list,
			'promotion' => $promotion,
			'promotions' => $promotions,
			'inscriptions' => $inscriptions,
			'niveaux' =>	$niveaux,
			'classes' =>	$classes,
			'discounts' =>	$discounts,
			'cycles' =>	$cycles,
			'selectedCycle' => $selectedCycle,
			'selectedNiveau' => $selectedNiveau,
			'selectedClasse' => $selectedClasse,
			'selectedDiscount' => $selectedDiscount,
			'services' => $services,
			'results' => $results
		));
	}

	function services_discount_state()
	{

		$months_list = _months_list;
		$promotions = Models\Promotion::getList();
		$niveaux = Models\Niveau::getList();
		$classes = Models\Classe::getList();
		$services = Models\FIN\EncaissementRubrique::getList();
		$promotion = Models\Promotion::actuelle();



		$selectedNiveau = isset($_GET['niveau']) ? $_GET['niveau'] : $niveaux[0]->ID;
		$selectedClasse = isset($_GET['classe']) ? $_GET['classe'] : null;
		$_promotion = isset($_GET['promotion']) ? $_GET['promotion'] : null;

		$where = array();

		if ($_promotion) {
			$promotion = new Models\Promotion($_promotion);
		}

		if ($selectedNiveau && $selectedNiveau != 'all') {
			$where['Niveau'] =  $selectedNiveau;
		}

		if ($selectedClasse && $selectedClasse != 'all') {
			$where['Classe'] =  $selectedClasse;
		}

		$where['Promotion'] =  $promotion->get('ID');

		// dd($where);
		$inscriptions = Models\Inscription::getList(
			array(
				'where' => $where
			)
		);


		loadView('encaissements/services-discount-state', array(
			'months_list' => $months_list,
			'promotion' => $promotion,
			'promotions' => $promotions,
			'inscriptions' => $inscriptions,
			'niveaux' =>	$niveaux,
			'classes' =>	$classes,
			'selectedNiveau' => $selectedNiveau,
			'selectedClasse' => $selectedClasse,
			'services' => $services,


		));
	}

	function retards_paiement()
	{
		$filtreText = array();
		set_time_limit(0);
		$promotion = Models\Promotion::promotion_actuelle();
		$classes = Models\Classe::getList(array('where' => array('Promotion' => $promotion->get('ID')), 'order' => array('Niveau' => true)));

		$services = Models\FIN\EncaissementRubrique::getList();
		$months_list = _zero_months_list;
		$months = _zero_months_keys;


		$service = null;
		if (isset($_GET['service']) && $_GET['service'] && $_GET['service'] != 'all') {

			try {
				$service = new Models\FIN\EncaissementRubrique($_GET['service']);
			} catch (Exception $e) {
				exit('Element introuvable');
			}
			$filtreText[] = $service->get('Label');
		}

		$mois = 0;
		if (isset($_GET['mois'])) {
			$mois = $_GET['mois'];
		}

		$classe = null;
		$inscriptions = array();
		$etatRetardsPaiement = array();
		if (isset($_GET['classe']) && $_GET['classe'] != 'all') {

			try {
				$classe = new Models\Classe($_GET['classe']);
			} catch (Exception $e) {
				exit('Element introuvable');
			}
			$filtreText[] = $classe->get('Label');

			$inscriptions = $classe->getInscriptions();
		} elseif (isset($_GET['classe']) && $_GET['classe'] == 'all') {

			$promotion = Models\Promotion::promotion_actuelle();
			$inscriptions = $promotion->getInscriptions();
		}

		if (!$inscriptions)
			$inscriptions = array();

		$totalRetards = 0;
		// header('Content-type: text/plain');
		foreach ($inscriptions as $inscription) {

			$etatRetard = array();
			$etatRetard['inscription'] = $inscription;

			$total_services = Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($inscription, $mois, $service);

			if ($total_services == 0)
				continue;

			$total_encaissements = Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($inscription, $mois, $service);

			// if($total_services <= $total_encaissements)
			// continue;

			$etatRetard['tuteurs'] = Models\Parrainage::getList(array('where' => array('Eleve' => $inscription->get('Eleve')->get('ID'))));

			$etatRetard['total_services'] = $total_services;
			$etatRetard['total_encaissements'] = $total_encaissements;

			$totalRetards += ($total_services - $total_encaissements);

			$ptc = 0;
			if ($total_services) {
				$ptc = ($total_encaissements * 100) / $total_services;
			}

			$etatRetard['ptc'] = $ptc;
			if ($ptc == 100) {
				continue;
			}

			$etatRetardsPaiement[] = $etatRetard;
		}

		// if ($etatRetardsPaiement) {

		// 	foreach ($etatRetardsPaiement as $key => $row) {
		// 		$ptcs[$key]  = $row['ptc'];
		// 		$inscriptionsList[$key] = $row['inscription'];
		// 	}
		// 	array_multisort($ptcs, SORT_ASC, $inscriptionsList, SORT_ASC, $etatRetardsPaiement);
		// }


		if (isset($_GET['mois']) && $_GET['mois']) {
			$mois = $_GET['mois'];
			$filtreText[] = $months_list[$mois];
		}

		$filtreText[] = \Tools::numberFormat($totalRetards, 2) . ' dhs';


		if (isset($_GET['export_pdf'])) {


			$title = 'Retards de paiement par classe';

			$filtersTitle = $filtreText;
			array_pop($filtersTitle);
			$filtersTitle = implode(', ', ($filtersTitle));

			set_time_limit(-1);
			loadLib('tcpdf');
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$fontArabicBold = TCPDF_FONTS::addTTFfont(_basepath . 'assets/fonts/ADOBEARABIC-BOLD.OTF', 'TrueTypeUnicode', '', 96);
			$pdf->SetFont($fontArabicBold, '', 14, '', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('');
			$pdf->SetTitle($title);
			$pdf->SetSubject('');
			$pdf->SetKeywords('');
			// remove default header/footer
			$pdf->setPrintHeader(false);
			// $pdf->setPrintFooter(false);
			// set header and footer fonts
			$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			// set margins
			$pdf->SetMargins(5, 3, 5);
			// set image scale factor
			//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			$pdf->AddPage('P', 'A4');
			$html = getView(
				'encaissements/encaissements-retards-pdf',
				array(
					'title' => $title,
					'filtersTitle' => $filtersTitle,
					'filtreText' => $filtreText,
					'totalRetards' => $totalRetards,
					'classe' => $classe,
					'services' => $services,
					'service' => $service,
					'etatRetardsPaiement' => $etatRetardsPaiement,
					'mois' => $mois,
					'classes' => $classes,
					'inscriptions' => $inscriptions,
					'months' => $months,
					'months_list' => $months_list,
					'navKey' => 'encaissements',
				),
				'null_layout'
			);

			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->lastPage();
			ob_end_clean();
			// ---------------------------------------------------------

			//Close and output PDF document
			$fileTitle = strtolower(str_replace(' ', '_', $title)) . ' ' . date('Ymd-His') . '.pdf';
			$pdf->Output($fileTitle, 'I');
		}

		loadView('encaissements/encaissements-retards', array(
			'filtreText' => $filtreText,
			'totalRetards' => $totalRetards,
			'classe' => $classe,
			'services' => $services,
			'service' => $service,
			'etatRetardsPaiement' => $etatRetardsPaiement,
			'mois' => $mois,
			'classes' => $classes,
			'inscriptions' => $inscriptions,
			'months' => $months,
			'months_list' => $months_list,
			'navKey' => 'encaissements',
		));
	}

	function discount_requests($request =  null)
	{

		if ($request) {
			$request =  new Models\FIN\DiscountRequest($request);
			if (Request::isPost() && isset($_POST['op'])) {
				switch ($_POST['op']) {
					case 'accept':
						DB::begin();

						$service = $request->Rubrique;
						$inscription = $request->Inscription;
						$promotion = $inscription->Promotion;
						$months_list =  $promotion->months()->list;
						if ($service->get('Mensuel')) {

							foreach ($months_list  as $month_key => $month) {

								$ligne = Models\FIN\EncaissementLigne::getLigne($inscription, $service, $month_key);
								$inscriptionRubrique = $inscription->inscriptionServices($service, $month_key);
								if (!$inscriptionRubrique) {
									$inscriptionRubrique = new Models\FIN\EncaissementRubriqueInscription();
									$inscriptionRubrique->set('DateAjout', date('Y-m-d H:i:s'))
										->set('User', Session::getInstance()->getCurUser())
										->set('Inscription', $inscription)
										->set('EncaissementRubrique', $service)
										->set('Promotion', $inscription->Promotion)
										->set('Eleve', $inscription->Eleve)
										->set('Niveau', $inscription->Niveau->ID)
										->set('Classe', $inscription->Classe->ID)
										->set('Mois', $month_key);
								}

								if (!$ligne) {
									$inscriptionRubrique
										->set('Montant', $request->Price)
										->set('Remarques', $request->Motif)
										->setJson('Discounts', $request->Discounts)
										->set('DiscountsAmount', $request->DiscountsAmount);
								}

								$inscriptionRubrique->save();
							}
						} else {

							$ligne = Models\FIN\EncaissementLigne::getLigne($inscription, $service, 0);
							$inscriptionRubrique = $inscription->inscriptionServices($service, 0);

							if (!$inscriptionRubrique) {
								$inscriptionRubrique = new Models\FIN\EncaissementRubriqueInscription();
								$inscriptionRubrique->set('DateAjout', date('Y-m-d H:i:s'))
									->set('User', Session::getInstance()->getCurUser())
									->set('Inscription', $inscription)
									->set('EncaissementRubrique', $service)
									->set('Promotion', $inscription->Promotion)
									->set('Eleve', $inscription->Eleve)
									->set('Niveau', $inscription->Niveau->ID)
									->set('Classe', $inscription->Classe->ID)
									->set('Mois', 0);
							}

							if (!$ligne) {
								$inscriptionRubrique
									->set('Montant', $request->Price)
									->setJson('Discounts', $request->Discounts)
									->set('DiscountsAmount', $request->DiscountsAmount)
									->set('Remarques', $request->Motif);
							}

							$inscriptionRubrique->save();
						}

						$request->setJson('Validation', [
							'user' => $this->auth_user->ID,
							'date' => date('Y-m-d H:i:s'),
							'note' => 'test'
						]);

						$request->save();
						DB::commit();
						sendResponse([
							'msg' => 'OK'
						]);
						break;
					case 'cancel':
						$request->setJson('Refus', [
							'user' => $this->auth_user->ID,
							'date' => date('Y-m-d H:i:s'),
							'note' => 'test'
						]);
						$request->save();
						sendResponse([
							'msg' => 'OK'
						]);
						break;
				}
			}

			loadView('encaissements/discounts_request_confirme', ['request' => $request], 'null_layout');
		}

		$requests = \Models\FIN\DiscountRequest::order(['ID' => false])->get();

		return loadView('encaissements/discounts_request', array(
			'requests' => $requests,
			'promotion' => \Models\Promotion::promotion_actuelle()
		));
	}


	function paiements()
	{
		$filtreText = array();

		$promotion = Models\Promotion::promotion_actuelle();
		$classes = Models\Classe::getList(array('where' => array('Promotion' => $promotion->get('ID')), 'order' => array('Niveau' => true)));

		$services = Models\FIN\EncaissementRubrique::getList();
		$months_list = _months_list;
		$months = _months_keys;


		$service = null;
		if (isset($_GET['service']) && $_GET['service'] && $_GET['service'] != 'all') {

			try {
				$service = new Models\FIN\EncaissementRubrique($_GET['service']);
			} catch (Exception $e) {
				exit('Element introuvable');
			}
			$filtreText[] = $service->get('Label');
		}

		$mois = null;
		if (isset($_GET['mois']) && $_GET['mois'] &&  $_GET['mois'] != 'all') {
			$mois = $_GET['mois'];
		}

		$classe = null;
		if (isset($_GET['classe']) && $_GET['classe'] != 'all') {

			try {
				$classe = new Models\Classe($_GET['classe']);
			} catch (Exception $e) {
				exit('Element introuvable');
			}
			$filtreText[] = $classe->get('Label');
		}


		$query = Models\FIN\EncaissementLigne::sqlQuery() . <<<END
		JOIN (SELECT `ID` AS `J1ID`, `Inscription` AS `J1Inscription`, `Promotion` AS `J1Promotion` FROM `fnc_encaissements`) AS `j1` ON `fnc_encaissementlignes`.`Encaissement` = `j1`.`J1ID` 
		JOIN (SELECT `ID` AS `J2ID`, `Classe` AS `J2Classe` FROM `ins_inscriptions`) AS `j2` ON `j1`.`J1Inscription` = `j2`.`J2ID` 
END;

		$where = array('`Canceled` IS NULL');
		if ($service)
			$where['EncaissementRubrique'] = $service->get('ID');


		if ($mois)
			$where['Mois'] = $mois;


		if ($classe)
			$where['J2Classe'] = $classe->get('ID');


		$promotion = Models\Promotion::promotion_actuelle();
		$where['J1Promotion'] = $promotion->get('ID');

		$_encaissements = array();
		$encaissements = array();

		$_encaissements = Models\FIN\EncaissementLigne::getList(array('where' => $where), $query);

		if (isset($_GET['mois']) && $_GET['mois'] && $_GET['mois'] != 'all') {
			$mois = $_GET['mois'];
			$filtreText[] = $months_list[$mois];
		}

		foreach ($_encaissements as $fnc_ligne) {

			if (!isset($encaissements[$fnc_ligne->Inscription->ID])) {
				$encaissements[$fnc_ligne->Inscription->ID] = array(
					'inscription' => $fnc_ligne->Inscription,
					'lignes' => array(),
				);
			}

			$encaissements[$fnc_ligne->Inscription->ID]['lignes'][] = $fnc_ligne;
		}


		$print = false;
		$layout = '_layout';
		if (isset($_GET['print'])) {
			$print = true;
			$layout = '_layout-empty';
		}

		// Filtre list for PDF export
		$filtreQueryString = http_build_query($_GET);
		if ($filtreQueryString)
			$filtreQueryString = '?' . $filtreQueryString;

		loadView('encaissements/encaissements-paiements', array(
			'filtreText' => $filtreText,
			'classe' => $classe,
			'services' => $services,
			'service' => $service,
			'mois' => $mois,
			'classes' => $classes,
			'encaissements' => $encaissements,
			'months' => $months,
			'months_list' => $months_list,
			'print' => $print,
			'filtreQueryString' => $filtreQueryString,
			'navKey' => 'encaissements',
		), $layout);
	}

	function _etat_global_paiement()
	{
		$etat_global_classes = array();
		$classes = Models\Classe::getList(array('order' => array('Niveau' => true)));
		$months_list = _months_list;
		$months = _months_keys;

		$mois = null;
		if (isset($_GET['mois']) && $_GET['mois']) {
			$mois = $_GET['mois'];
		}

		if ($mois) {
			foreach ($classes as $classe) {
				$etat_global_classe = array();
				$etat_global_classe['classe'] = $classe;

				$inscriptions = $classe->getInscriptions();
				if (!$inscriptions)
					$inscriptions = array();
				$etat_global_classe['inscriptions'] = count($inscriptions);

				$total_encaissements = $classe->total_encaissements($mois);

				$total_services = 0;
				foreach ($inscriptions as $inscription) {
					$total_services += $inscription->total_services($mois);
				}

				$ptc = 0;
				if ($total_services) {
					$ptc = ($total_encaissements * 100) / $total_services;
				}


				$etat_global_classe['ca_previsionnel'] = $total_services;
				$etat_global_classe['encaissements'] = $total_encaissements;
				$etat_global_classe['ptc'] = $ptc;

				$etat_global_classes[] = $etat_global_classe;
			}

			foreach ($etat_global_classes as $key => $row) {
				$ptcs[$key]  = $row['ptc'];
				$inscriptionsList[$key] = $row['inscriptions'];
			}
			array_multisort($ptcs, SORT_ASC, $inscriptionsList, SORT_ASC, $etat_global_classes);
		}


		loadView('encaissements/encaissements-etat-global', array(
			'etat_global_classes' => $etat_global_classes,
			'mois' => $mois,
			'months' => $months,
			'months_list' => $months_list,
			'navKey' => 'encaissements',
		));
	}

	function etat_global_paiement()
	{
		$etat_global_classes = array();
		$classes = Models\Classe::getList(array('order' => array('Niveau' => true)));
		$months_list = _zero_months_list;
		$months = _zero_months_keys;

		$mois = null;
		if (isset($_GET['mois'])) {
			$mois = $_GET['mois'];
		}

		if (!is_null($mois)) {
			foreach ($classes as $classe) {
				$etat_global_classe = array();
				$etat_global_classe['classe'] = $classe;

				$inscriptions = $classe->getInscriptions();
				if (!$inscriptions)
					$inscriptions = array();
				$etat_global_classe['inscriptions'] = count($inscriptions);

				$total_services = Models\FIN\EncaissementRubriqueInscription::getCAMontant(array('Classe' => $classe->ID, 'Mois' => $mois));

				$total_encaissements = Models\FIN\EncaissementLigne::getECMontant(array('Classe' => $classe->ID, 'Mois' => $mois));


				$ptc = 0;
				if ($total_services) {
					$ptc = ($total_encaissements * 100) / $total_services;
				}


				$etat_global_classe['ca_previsionnel'] = $total_services;
				$etat_global_classe['encaissements'] = $total_encaissements;
				$etat_global_classe['ptc'] = $ptc;

				$etat_global_classes[] = $etat_global_classe;
			}

			foreach ($etat_global_classes as $key => $row) {
				$ptcs[$key]  = $row['ptc'];
				$inscriptionsList[$key] = $row['inscriptions'];
			}
			array_multisort($ptcs, SORT_ASC, $inscriptionsList, SORT_ASC, $etat_global_classes);
		}


		loadView('encaissements/encaissements-etat-global', array(
			'etat_global_classes' => $etat_global_classes,
			'mois' => $mois,
			'months' => $months,
			'months_list' => $months_list,
			'navKey' => 'encaissements',
		));
	}

	function encaissements_depenses()
	{

		$promotion =  Models\Promotion::promotion_actuelle();
		$classes = Models\Classe::getList(array('where' => array('Promotion' => $promotion->get('ID')), 'order' => array('Niveau' => true)));

		$months_list = _zero_months_list;
		$months = _zero_months_keys;


		$montshLabels = array();

		foreach ($months as $month) {
			$montshLabels[] = $months_list[$month];
			// total_encaissements($mois)
		}

		$retardPaiements = array();

		foreach ($months as $month) {

			$retardPaiements[$month] = array(
				'month' => $month,
				'mois' => $month ? Tools::dateFormat('2021-' . $month . '-01', '%b') : 'FA',
				'annee' => ($month < 9) ? $promotion->get('Annee') : ($promotion->get('Annee') - 1),
				'class' =>  '',
				'previsions' => 0,
				'encaissements' => 0,
				'retards' => 0,
			);
		}

		$datasets = array();



		// Prévisions de mois
		$dataset = array();
		$dataset['label'] = "Prévisions";
		$dataset['data'] = array();
		$dataset['backgroundColor'] = "rgb(149, 203, 239)";
		$dataset['hoverBackgroundColor'] = "rgb(149, 203, 239, .9)";
		$dataset['borderColor'] = "transparent";

		foreach ($months as $month) {

			$total_services = Models\Fin\EncaissementRubrique::total($promotion, $month);
			$dataset['data'][] = $total_services;

			$retardPaiements[$month]['previsions'] = $total_services;
		}

		$datasets[] = $dataset;

		// end

		// Encaissements du mois
		$dataset = array();
		$dataset['label'] = "Encaissements";
		$dataset['data'] = array();
		$dataset['backgroundColor'] = "rgb(186, 221, 151)";
		$dataset['hoverBackgroundColor'] = "rgb(186, 221, 151, .9)";
		$dataset['borderColor'] = "transparent";

		foreach ($months as $month) {

			$total = Models\FIN\Encaissement::total_encaissements($month, $promotion);
			$dataset['data'][] = $total;
			$retardPaiements[$month]['encaissements'] = $total;

			$retardPaiements[$month]['retards'] =  $retardPaiements[$month]['previsions'] - $retardPaiements[$month]['encaissements'];

			if ($retardPaiements[$month]['retards']) {
				$retardPaiements[$month]['class'] = 'red_month';
			}
		}

		$datasets[] = $dataset;

		// end 

		// Dépenses du mois
		$dataset = array();
		$dataset['label'] = "Dépenses";
		$dataset['data'] = array();
		$dataset['backgroundColor'] = "rgb(228, 88, 90)";
		$dataset['hoverBackgroundColor'] = "rgb(228, 88, 90, .9)";
		$dataset['borderColor'] = "transparent";

		foreach ($months as $month) {

			$total = Models\FIN\Depense::total_depense($month);
			$dataset['data'][] = $total;
		}

		$datasets[] = $dataset;

		// end 


		$app['datasets'] = $datasets;
		$app['months']['labels'] = $montshLabels;

		// if (Config::getSchool() == 'demo') {
		// 	dd($app);
		// }

		//dd($retardPaiements);
		loadView('encaissements/encaissements-depenses', array(
			'navKey' => 'encaissements',
			'app' => $app,
			'retardPaiements' => $retardPaiements,
		));
	}

	function invoice($id = NULL)
	{

		$pk = (isset($id) && $id) ? $id : null;
		$auth = Session::user();

		try {
			$inscription = new Models\Inscription($pk);
		} catch (Exception $e) {
			exit('Element introuvable');
		}

		// save multiple rubriques
		$can_discount = roleIs('admin') || $auth->hasAutorisation('apply_discount_price');
		$eleve = $inscription->get('Eleve');
		$months_list =  $inscription->Promotion->months()->list;
		$paymentPossibilities =  [
			'yearly' => 'Annuel',
			'quarterly'  => 'Trimestriel',
			'monthly'  =>  'Mensuel',
		];

		if (Request::isPost()) {

			try {
			} catch (Exception $e) {
				exit('Element introuvable');
			}

			$_rubriquesList = Models\FIN\EncaissementRubrique::getList();
			$rubriquesList = array();
			foreach ($_rubriquesList as $item) {
				$rubriquesList[$item->ID] = $item;
			}

			$grille = Models\FIN\RubriquePrice::grille();
			$niveau = $inscription->get('Niveau');




			// affectattion des services
			if (isset($_POST['single_rubrique']) && $_POST['single_rubrique'] && isset($_POST['checkrubrique_' . $_POST['single_rubrique']]) || !$item->get('Optionnel')) {
				DB::begin();
				// save just rubrique
				$rubrique =  $rubriquesList[$_POST['single_rubrique']];
				if ($rubrique->get('Mensuel')) {

					$montant = $rubrique->getAmount($grille, $niveau);
					$remarques = '';

					foreach ($months_list as $month_key => $month) {

						// ligne 
						$ligne = Models\FIN\EncaissementLigne::getLigne($inscription, $rubrique, $month_key);
						// inscriptionService
						$inscriptionService = EncaissementRubriqueInscription::inscriptionHasRubrique($inscription, $rubrique, $month_key);
						$key_month_checked = 'rubrique_' . $rubrique->get('ID') . '_month_' . $month_key;
						$key_month_montant = 'rubrique_montant_' . $rubrique->get('ID') . '_month_' . $month_key;
						$key_remarques = 'rubrique_remarques_' . $rubrique->get('ID') . '_month_' . $month_key;


						if (isset($_POST[$key_month_checked]) || !$rubrique->get('Optionnel')) {

							if ($can_discount && isset($_POST[$key_month_montant])) {
								$montant = $_POST[$key_month_montant];
							}

							if (isset($_POST[$key_remarques])) {
								$remarques = $_POST[$key_remarques];
							}


							if (!$inscriptionService) {
								$inscriptionService = new Models\FIN\EncaissementRubriqueInscription();
								$inscriptionService->set('DateAjout', date('Y-m-d H:i:s'))
									->set('User', $auth)
									->set('Inscription', $inscription)
									->set('EncaissementRubrique', $rubrique)
									->set('Promotion', $inscription->Promotion)
									->set('Eleve', $inscription->Eleve)
									->set('Niveau', $inscription->Niveau)
									->set('Mois', $month_key);
							}

							if (!$ligne) {
								$inscriptionService
									->set('Montant', $montant)
									->set('Remarques', $remarques);
							}

							$inscriptionService->save();
						} else {

							if ($inscriptionService) {
								$encaissement_lignes = \Models\FIN\EncaissementLigne::getList(array(
									'where' => [
										'Canceled IS NULL',
										'EncaissementRubrique' => $rubrique->ID,
										'Inscription' => $inscription->ID,
										'Mois' => $month_key
									]
								));

								if (empty($encaissement_lignes)) {
									$inscriptionService->delete();
								}
							}
						}
					}
				}

				DB::commit();
				//log
				Models\ActionLog::catchLog(
					"Discount  of " . $inscription->get('Eleve')->get('User')->getNomComplet(),
					$inscription
				);
				sendAsJson(array('msg' => "Les tarifs scolaires pour services " . $rubrique->Label . " sont bien mis à jour."));
			} else {
				DB::begin();

				foreach ($rubriquesList as $item) {

					$remarques = '';
					$montant = $item->getAmount($grille, $niveau);

					$key_rubrique = 'checkrubrique_' . $item->get('ID');
					$key_remarques = 'remarques_' . $item->get('ID');
					$key_montant = 'montant_' . $item->get('ID');

					if (isset($_POST[$key_rubrique]) || !$item->get('Optionnel')) {

						if ($can_discount && isset($_POST[$key_montant])) {
							$montant = $_POST[$key_montant];
						}

						if (isset($_POST[$key_remarques])) {
							$remarques = $_POST[$key_remarques];
						}

						if ($item->get('Mensuel')) {

							foreach ($months_list as $month_key => $month) {

								// ligne 
								$ligne = Models\FIN\EncaissementLigne::getLigne($inscription, $item, $month_key);
								// inscriptionService
								$inscriptionService = EncaissementRubriqueInscription::inscriptionHasRubrique($inscription, $item, $month_key);


								$key_month_checked = 'rubrique_' . $item->get('ID') . '_month_' . $month_key;
								$key_month_montant = 'rubrique_montant_' . $item->get('ID') . '_month_' . $month_key;
								$key_remarques = 'rubrique_remarques_' . $item->get('ID') . '_month_' . $month_key;

								if (isset($_POST[$key_month_checked]) || !$item->get('Optionnel')) {
									if ($can_discount && isset($_POST[$key_month_montant])) {
										$montant = $_POST[$key_month_montant];
									}

									if (isset($_POST[$key_remarques])) {
										$remarques = $_POST[$key_remarques];
									}


									if (!$inscriptionService) {
										$inscriptionService = new Models\FIN\EncaissementRubriqueInscription();
										$inscriptionService->set('DateAjout', date('Y-m-d H:i:s'))
											->set('User', $auth)
											->set('Inscription', $inscription)
											->set('EncaissementRubrique', $item)
											->set('Promotion', $inscription->Promotion)
											->set('Eleve', $inscription->Eleve)
											->set('Niveau', $inscription->Niveau)
											->set('Mois', $month_key);
									}

									if (!$ligne) {
										$inscriptionService
											->set('Montant', $montant)
											->set('Remarques', $remarques);
									}

									$inscriptionService->save();
								} else {

									if ($inscriptionService) {
										$encaissement_lignes = \Models\FIN\EncaissementLigne::getList(array(
											'where' => [
												'Canceled IS NULL',
												'EncaissementRubrique' => $item->ID,
												'Inscription' => $inscription->ID,
												'Mois' => $month_key
											]
										));

										if (empty($encaissement_lignes)) {
											$inscriptionService->delete();
										}
									}
								}
							}
						} else {

							// ligne
							$ligne = Models\FIN\EncaissementLigne::getLigne($inscription, $item, 0);
							// inscriptionServices
							$inscriptionService = Models\FIN\EncaissementRubriqueInscription::inscriptionHasRubrique($inscription, $item, 0);

							if (!$inscriptionService) {
								$inscriptionService = new Models\FIN\EncaissementRubriqueInscription();
								$inscriptionService->set('DateAjout', date('Y-m-d H:i:s'))
									->set('User', $auth)
									->set('Inscription', $inscription)
									->set('EncaissementRubrique', $item)
									->set('Promotion', $inscription->Promotion)
									->set('Eleve', $inscription->Eleve)
									->set('Niveau', $inscription->Niveau)
									->set('Mois', 0);
							}


							if (!$ligne) {
								$inscriptionService
									->set('Montant', $montant)
									->set('Remarques', $remarques);
							}

							$inscriptionService->save();
						}
					} else {

						$_items = Models\FIN\EncaissementRubriqueInscription::getList(array(
							'where' => array(
								'Inscription' => $inscription->ID,
								'EncaissementRubrique' => $item->ID,
							)
						));

						foreach ($_items as $_item) {
							$encaissement_lignes = \Models\FIN\EncaissementLigne::getList(array(
								'where' => array(
									'Canceled IS NULL',
									'EncaissementRubrique' => $_item->ID,
									'Inscription' => $inscription->ID,
									'Mois' => $_item->Mois
								)
							));
							if (empty($encaissement_lignes)) {
								$_item->delete();
							}
						}
					}
				}

				//log
				Models\ActionLog::catchLog(
					"Discount  of " . $inscription->get('Eleve')->get('User')->getNomComplet(),
					$inscription
				);

				DB::commit();
			}

			sendAsJson(array('msg' => "Les tarifs scolaires sont bien mis à jour."));
		} else {

			$rubriques =  Models\FIN\EncaissementRubrique::getList();
			$grille = Models\FIN\RubriquePrice::grille();
			$data['paymentPossibilities'] = $paymentPossibilities;
			$data['eleve'] = $eleve;
			$data['inscriptionActuelle'] = $inscription;
			$data['niveau'] = $inscription->get('Niveau');
			$data['rubriques'] = $rubriques;
			$data['inscription'] = $inscription;
			$data['grille'] = $grille;
			$data['months_list'] = $months_list;
			$data['isUpdate'] = true;
			$data['can_discount'] = false;

			return loadView('encaissements/invoice', isset($data) ? $data : NULL);
		}
	}

	function rubriques_rate($id = NULL)
	{

		$pk = (isset($id) && $id) ? $id : null;
		$auth = Session::user();

		try {
			$inscription = new Models\Inscription($pk);
		} catch (Exception $e) {
			exit('Element introuvable');
		}

		// save multiple rubriques
		$can_discount = roleIs('admin') || $auth->hasAutorisation('apply_discount_price');
		$eleve = $inscription->get('Eleve');
		$months_list =  $inscription->Promotion->months()->list;

		if (Request::isPost()) {

			try {
			} catch (Exception $e) {
				exit('Element introuvable');
			}

			$_rubriquesList = Models\FIN\EncaissementRubrique::getList();
			$rubriquesList = array();
			foreach ($_rubriquesList as $item) {
				$rubriquesList[$item->ID] = $item;
			}

			$grille = Models\FIN\RubriquePrice::grille();
			$niveau = $inscription->get('Niveau');

			// affectattion des services
			if (isset($_POST['single_rubrique']) && $_POST['single_rubrique'] && isset($_POST['checkrubrique_' . $_POST['single_rubrique']]) || !$item->get('Optionnel')) {
				DB::begin();
				// save just rubrique
				$rubrique =  $rubriquesList[$_POST['single_rubrique']];
				if ($rubrique->get('Mensuel')) {

					$montant = $rubrique->getAmount($grille, $niveau);
					$remarques = '';

					foreach ($months_list as $month_key => $month) {

						// ligne 
						$ligne = Models\FIN\EncaissementLigne::getLigne($inscription, $rubrique, $month_key);
						// inscriptionService
						$inscriptionService = EncaissementRubriqueInscription::inscriptionHasRubrique($inscription, $rubrique, $month_key);
						$key_month_checked = 'rubrique_' . $rubrique->get('ID') . '_month_' . $month_key;
						$key_month_montant = 'rubrique_montant_' . $rubrique->get('ID') . '_month_' . $month_key;
						$key_remarques = 'rubrique_remarques_' . $rubrique->get('ID') . '_month_' . $month_key;


						if (isset($_POST[$key_month_checked]) || !$rubrique->get('Optionnel')) {

							if ($can_discount && isset($_POST[$key_month_montant])) {
								$montant = $_POST[$key_month_montant];
							}

							if (isset($_POST[$key_remarques])) {
								$remarques = $_POST[$key_remarques];
							}


							if (!$inscriptionService) {
								$inscriptionService = new Models\FIN\EncaissementRubriqueInscription();
								$inscriptionService->set('DateAjout', date('Y-m-d H:i:s'))
									->set('User', $auth)
									->set('Inscription', $inscription)
									->set('EncaissementRubrique', $rubrique)
									->set('Promotion', $inscription->Promotion)
									->set('Eleve', $inscription->Eleve)
									->set('Niveau', $inscription->Niveau)
									->set('Mois', $month_key);
							}

							if (!$ligne) {
								$inscriptionService
									->set('Montant', $montant)
									->set('Remarques', $remarques);
							}

							$inscriptionService->save();
						} else {

							if ($inscriptionService) {
								$encaissement_lignes = \Models\FIN\EncaissementLigne::getList(array(
									'where' => [
										'Canceled IS NULL',
										'EncaissementRubrique' => $rubrique->ID,
										'Inscription' => $inscription->ID,
										'Mois' => $month_key
									]
								));

								if (empty($encaissement_lignes)) {
									$inscriptionService->delete();
								}
							}
						}
					}
				}

				DB::commit();
				//log
				Models\ActionLog::catchLog(
					"Discount  of " . $inscription->get('Eleve')->get('User')->getNomComplet(),
					$inscription
				);
				sendAsJson(array('msg' => "Les tarifs scolaires pour services " . $rubrique->Label . " sont bien mis à jour."));
			} else {
				DB::begin();

				foreach ($rubriquesList as $item) {

					$remarques = '';
					$montant = $item->getAmount($grille, $niveau);

					$key_rubrique = 'checkrubrique_' . $item->get('ID');
					$key_remarques = 'remarques_' . $item->get('ID');
					$key_montant = 'montant_' . $item->get('ID');

					if (isset($_POST[$key_rubrique]) || !$item->get('Optionnel')) {

						if ($can_discount && isset($_POST[$key_montant])) {
							$montant = $_POST[$key_montant];
						}

						if (isset($_POST[$key_remarques])) {
							$remarques = $_POST[$key_remarques];
						}

						if ($item->get('Mensuel')) {

							foreach ($months_list as $month_key => $month) {

								// ligne 
								$ligne = Models\FIN\EncaissementLigne::getLigne($inscription, $item, $month_key);
								// inscriptionService
								$inscriptionService = EncaissementRubriqueInscription::inscriptionHasRubrique($inscription, $item, $month_key);


								$key_month_checked = 'rubrique_' . $item->get('ID') . '_month_' . $month_key;
								$key_month_montant = 'rubrique_montant_' . $item->get('ID') . '_month_' . $month_key;
								$key_remarques = 'rubrique_remarques_' . $item->get('ID') . '_month_' . $month_key;

								if (isset($_POST[$key_month_checked]) || !$item->get('Optionnel')) {
									if ($can_discount && isset($_POST[$key_month_montant])) {
										$montant = $_POST[$key_month_montant];
									}

									if (isset($_POST[$key_remarques])) {
										$remarques = $_POST[$key_remarques];
									}


									if (!$inscriptionService) {
										$inscriptionService = new Models\FIN\EncaissementRubriqueInscription();
										$inscriptionService->set('DateAjout', date('Y-m-d H:i:s'))
											->set('User', $auth)
											->set('Inscription', $inscription)
											->set('EncaissementRubrique', $item)
											->set('Promotion', $inscription->Promotion)
											->set('Eleve', $inscription->Eleve)
											->set('Niveau', $inscription->Niveau)
											->set('Mois', $month_key);
									}

									if (!$ligne) {
										$inscriptionService
											->set('Montant', $montant)
											->set('Remarques', $remarques);
									}

									$inscriptionService->save();
								} else {

									if ($inscriptionService) {
										$encaissement_lignes = \Models\FIN\EncaissementLigne::getList(array(
											'where' => [
												'Canceled IS NULL',
												'EncaissementRubrique' => $item->ID,
												'Inscription' => $inscription->ID,
												'Mois' => $month_key
											]
										));

										if (empty($encaissement_lignes)) {
											$inscriptionService->delete();
										}
									}
								}
							}
						} else {

							// ligne
							$ligne = Models\FIN\EncaissementLigne::getLigne($inscription, $item, 0);
							// inscriptionServices
							$inscriptionService = Models\FIN\EncaissementRubriqueInscription::inscriptionHasRubrique($inscription, $item, 0);

							if (!$inscriptionService) {
								$inscriptionService = new Models\FIN\EncaissementRubriqueInscription();
								$inscriptionService->set('DateAjout', date('Y-m-d H:i:s'))
									->set('User', $auth)
									->set('Inscription', $inscription)
									->set('EncaissementRubrique', $item)
									->set('Promotion', $inscription->Promotion)
									->set('Eleve', $inscription->Eleve)
									->set('Niveau', $inscription->Niveau)
									->set('Mois', 0);
							}


							if (!$ligne) {
								$inscriptionService
									->set('Montant', $montant)
									->set('Remarques', $remarques);
							}

							$inscriptionService->save();
						}
					} else {

						$_items = Models\FIN\EncaissementRubriqueInscription::getList(array(
							'where' => array(
								'Inscription' => $inscription->ID,
								'EncaissementRubrique' => $item->ID,
							)
						));

						foreach ($_items as $_item) {
							$encaissement_lignes = \Models\FIN\EncaissementLigne::getList(array(
								'where' => array(
									'Canceled IS NULL',
									'EncaissementRubrique' => $_item->ID,
									'Inscription' => $inscription->ID,
									'Mois' => $_item->Mois
								)
							));
							if (empty($encaissement_lignes)) {
								$_item->delete();
							}
						}
					}
				}

				//log
				Models\ActionLog::catchLog(
					"Discount  of " . $inscription->get('Eleve')->get('User')->getNomComplet(),
					$inscription
				);

				DB::commit();
			}

			sendAsJson(array('msg' => "Les tarifs scolaires sont bien mis à jour."));
		} else {

			$rubriques = array();
			$rubriquesList = Models\FIN\EncaissementRubrique::getList();
			foreach ($rubriquesList as $item)
				$rubriques[$item->get('ID')] = $item;

			$grille = Models\FIN\RubriquePrice::grille();
			$data['eleve'] = $eleve;
			$data['inscriptionActuelle'] = $inscription;
			$data['niveau'] = $inscription->get('Niveau');
			$data['rubriques'] = $rubriques;
			$data['inscription'] = $inscription;
			$data['grille'] = $grille;
			$data['months_list'] = $months_list;
			$data['isUpdate'] = true;
			$data['can_discount'] = false;




			return loadView('encaissements/rubriques_rate', isset($data) ? $data : NULL);
		}
	}

	function encaissement_famille()
	{

		if (Request::isPost()) {
			$this->encaissement_famille_save();
		}

		$months_list = _zero_months_list;
		$parent = null;
		$inscriptions = array();
		$eleves = array();
		$promotions = Models\Promotion::getList();
		$parents = Models\Parentt::getList();
		$can_manually_edit_amount_encaissment_money  = isAllowed('can_manually_edit_amount_encaissment_money');

		$promotion =  Models\Promotion::promotion_actuelle();
		$grille = Models\FIN\RubriquePrice::grille();

		if (isset($_GET['promotion'])) {
			try {
				$promotion = new Models\Promotion($_GET['promotion']);
			} catch (Exception $e) {
				URL::redirect(URL::admin('encaissements/encaissement_famille'));
			}
		}

		if (isset($_GET['parent'])) {
			try {
				$parent = new Models\Parentt($_GET['parent']);
				$inscriptions =  $parent->getInscriptions($promotion);
				$eleves =  $parent->eleves();
			} catch (Exception $e) {
				//	URL::redirect(URL::admin('encaissements/encaissement_famille'));
			}
		}

		loadView('encaissements/encaissements_form_family', array(
			'parents' => $parents,
			'parent' => $parent,
			'inscriptions' => $inscriptions,
			'eleves' => $eleves,
			'promotion' => $promotion,
			'promotions' => $promotions,
			'months_list' => $promotion->months()->zero_list,
			'grille' => $grille,
			'can_manually_edit_amount_encaissment_money' => $can_manually_edit_amount_encaissment_money
		));
	}

	function update_encaissement()
	{
		$encaissement = new Models\FIN\Encaissement(Request::getArgs(1));

		$paiementmode = $encaissement->getDetailmode();

		if ($paiementmode) {
			$paiementmode->delete();
		}


		$paiementmode = new Models\FIN\PaiementMode($_POST['paiementmode']);
		$compteBancaire  =  null;
		if (isset($_POST['compte_bancaire'])) {
			$compteBancaire =   new Models\FIN\CompteBancaire($_POST['compte_bancaire']);
		}

		$paiementDetailID = null;
		$paiementDetail = [
			'mode' => 'virement',
		];

		if ($paiementmode->get('Alias') == 'virement') {

			$paiementDetail['mode'] = $paiementmode->get('Alias');
			$paiementDetail['modNumComptee'] =  $_POST['numcompte'];
			$paiementDetail['NumPiece'] = $_POST['numpiece'];
			$paiementvirementdetail = new Models\FIN\PaiementVirementDetail();
			$paiementvirementdetail
				->set('NumCompte', $_POST['numcompte'])
				->set('NumPiece', $_POST['numpiece'])
				->save();

			$paiementDetailID = $paiementvirementdetail->get('ID');
		} elseif ($paiementmode->get('Alias') == 'cheque') {

			$paiementDetail['mode'] = $paiementmode->get('Alias');
			$paiementDetail['NumCheque'] = $_POST['numpiece'];
			$paiementDetail['Tireur'] = $_POST['tireur'];

			$paiementchequedetail = new Models\FIN\PaiementchequeDetail();
			$paiementchequedetail
				->set('Banque', $_POST['banque'])
				->set('NumCheque', $_POST['numcheque'])
				->set('Tireur', $_POST['tireur'])
				->save();
			$paiementDetailID = $paiementchequedetail->get('ID');
		} elseif ($paiementmode->get('Alias') == 'tpe') {

			$paiementDetail['mode'] = $paiementmode->get('Alias');
			$paiementDetail['NumTransaction'] =  $_POST['numtransaction'];

			$paiementchequedetail = new Models\FIN\PaiementTpeDetail();
			$paiementchequedetail
				->set('NumTransaction', $_POST['numtransaction'])
				->save();
			$paiementDetailID = $paiementchequedetail->get('ID');
		}


		$encaissement->set('PaiementMode', $paiementmode->get('Alias'));
		$encaissement->set('DetailMode', $paiementDetailID);
		$encaissement->set('CompteBancaire', $compteBancaire ?  $compteBancaire->getKey() : null);
		$encaissement->setJson('PaiementDetail', $paiementDetail);
		$encaissement->set('Partenaire',  $compteBancaire ? $compteBancaire->get('Etablissement')->getKey() : null);
		$encaissement->set('Remarque', $_POST['remarque']);

		if (isset($_POST['date_echeance']) && $_POST['date_echeance']) {
			$encaissement->set('EcheanceCheque', $_POST['date_echeance']);
		}

		if (isset($_POST['datepaiement']) && $_POST['datepaiement']) {
			$encaissement->set('DatePaiement', $_POST['datepaiement']);
		}

		if (isset($_FILES['file']) && $_FILES['file']['error'] != UPLOAD_ERR_NO_FILE) {
			$fileError = GoogleStorage::checkUpload('file');
			errorPage($fileError);
			$encaissement->set('File', GoogleStorage::store('file', Config::get('path-encaissements')));
		}

		$encaissement->save();

		//	dd($encaissement->ID);
		sendResponse(array(
			'msg' => "Mode de paiement mis à jour.",
			'html' => $this->encaissementsWithLigneView($encaissement),
		));
	}


	function encaissement_famille_save()
	{

		$can_manually_edit_amount_encaissment_money  = isAllowed('can_manually_edit_amount_encaissment_money');
		$promotion =  Models\Promotion::promotion_actuelle();
		$parent = null;
		$inscriptions = array();
		$selectedInscriptionsID = array();
		$selectedRubriquesID = array();

		if (isset($_POST['promotion'])) {
			try {
				$promotion = new Models\Promotion($_POST['promotion']);
			} catch (Exception $e) {
				exit('Element introuvable');
			}
		}

		if (isset($_POST['parent'])) {
			try {
				$parent = new Models\Parentt($_POST['parent']);
				$inscriptions =  $parent->getInscriptions($promotion);
				// $eleves =  $parent->eleves();
			} catch (Exception $e) {
				exit('Element introuvable');
			}
		}


		if (!$parent) {
			URL::back();
		}

		$months_list =  $promotion->months()->zero_list;
		$encaissement = new Models\FIN\Encaissement();
		$paiementmode = new Models\FIN\PaiementMode($_POST['paiementmode']);


		DB::begin();

		$compteBancaire  =  null;
		if (isset($_POST['compte_bancaire'])) {
			$compteBancaire =   new Models\FIN\CompteBancaire($_POST['compte_bancaire']);
		}

		$paiementDetailID = null;
		$paiementDetail = [
			'mode' => 'virement',
		];
		$avoirs =  null;

		if ($paiementmode->get('Alias') == 'avoir') {

			if (!Request::get('avoirs')) {
				flashMessage()->success("Veuillez sélectionner un avoir");
				URL::back();
				exit();
			}

			$avoirs  = new Models\FIN\Avoir(Request::get('avoirs'));
			$paiementDetail['mode'] = $paiementmode->get('Alias');
			$paiementDetail['avoirs'] =  $avoirs->asArray();
		} elseif ($paiementmode->get('Alias') == 'virement') {

			$paiementDetail['mode'] = $paiementmode->get('Alias');
			$paiementDetail['modNumComptee'] =  $_POST['numcompte'];
			$paiementDetail['NumPiece'] = $_POST['numpiece'];

			$paiementvirementdetail = new Models\FIN\PaiementVirementDetail();
			$paiementvirementdetail
				->set('NumCompte', $_POST['numcompte'])
				->set('NumPiece', $_POST['numpiece'])
				->save();

			$paiementDetailID = $paiementvirementdetail->get('ID');
		} elseif ($paiementmode->get('Alias') == 'cheque') {

			$paiementDetail['mode'] = $paiementmode->get('Alias');
			$paiementDetail['NumCheque'] = $_POST['numpiece'];
			$paiementDetail['Tireur'] = $_POST['tireur'];

			$paiementchequedetail = new Models\FIN\PaiementchequeDetail();
			$paiementchequedetail
				->set('Banque', $_POST['banque'])
				->set('NumCheque', $_POST['numcheque'])
				->set('Tireur', $_POST['tireur'])
				->save();
			$paiementDetailID = $paiementchequedetail->get('ID');
		} elseif ($paiementmode->get('Alias') == 'tpe') {


			$paiementDetail['mode'] = $paiementmode->get('Alias');
			$paiementDetail['NumTransaction'] =  $_POST['numtransaction'];

			$paiementchequedetail = new Models\FIN\PaiementTpeDetail();
			$paiementchequedetail
				->set('NumTransaction', $_POST['numtransaction'])
				->save();
			$paiementDetailID = $paiementchequedetail->get('ID');
		}



		$encaissement
			->set('EncaissementPar', $parent->User->getNomComplet())
			->set('Promotion', $promotion)
			->set('PaiementMode', $paiementmode->get('Alias'))
			->set('CompteBancaire', $compteBancaire ?  $compteBancaire->getKey() : null)
			->setJson('PaiementDetail', $paiementDetail)
			->set('Partenaire',  $compteBancaire ? $compteBancaire->get('Etablissement')->getKey() : null)
			->set('DetailMode', $paiementDetailID)
			->set('UserBy', Session::getInstance()->getCurUser())
			->set('NumRecu', $promotion->recuEncaissement(true))
			->set('Remarque', $_POST['remarque'])
			->set('DatePaiement',  isset($_POST['datepaiement']) ? $_POST['datepaiement'] : date('Y-m-d'))
			->set('EcheanceCheque', $_POST['date_echeance'])
			->set('Date', date('Y-m-d H:i:s'));


		if (isset($_FILES['file']) && $_FILES['file']['error'] != UPLOAD_ERR_NO_FILE) {
			$fileError = GoogleStorage::checkUpload('file');
			errorPage($fileError);
			$encaissement->set('File', GoogleStorage::store('file', Config::get('path-encaissements')));
		}

		$encaissement->save();


		// save the money
		$selectedServices = 0;
		foreach ($months_list as $month_key => $m) {

			foreach ($inscriptions as $ins) {
				$post_inscription_month_key = "inscription_" . $ins->ID . "_month_" . $month_key;

				if (isset($_POST[$post_inscription_month_key])) {
					$rubriques = Models\FIN\EncaissementRubriqueInscription::rubriques($ins, $month_key);
					foreach ($rubriques as $rubrique) {
						$post_inscription_month_key = "inscription_" . $ins->ID . "_month_" . $month_key . "_service_" . $rubrique->get('ID');
						$post_amount_inscription_month_key = "amount_inscription_" . $ins->ID . "_month_" . $month_key . "_service_" . $rubrique->get('ID');

						if (isset($_POST[$post_inscription_month_key])) {
							$rubriqueInscription = Models\FIN\EncaissementRubriqueInscription::inscriptionHasRubrique($ins, $rubrique, $month_key);
							$montant =  Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($ins, $month_key, $rubrique);

							if ($can_manually_edit_amount_encaissment_money && isset($_POST[$post_amount_inscription_month_key])) {
								$montant =  $_POST[$post_amount_inscription_month_key];
							}

							if ($montant) {

								$_amount =   Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($ins, $month_key, $rubrique);
								$_service_amount = Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($ins, $month_key, $rubrique);

								if ($_amount < $_service_amount) {

									$selectedServices++;
									$encaissementLigne = new Models\FIN\EncaissementLigne();
									$encaissementLigne
										->set('Inscription', $ins)
										->set('Encaissement', $encaissement)
										->set('Montant', $montant)
										->set('EncaissementRubrique', $rubrique)
										->set('Mois', $month_key) // if 0  s service yearly
										->save();
									$rubriqueInscription->set('MontantEncaisse', ($rubriqueInscription->get('MontantEncaisse') ?: 0) + $montant);
									$rubrique_fnc_array = $rubriqueInscription->getArray('Encaissements') ?: array();
									array_push($rubrique_fnc_array, $encaissement->ID);
									$rubriqueInscription->setJson(
										'Encaissements',
										$rubrique_fnc_array

									);
									$rubriqueInscription->save();
								}
							}
						}
					}
				}
			}
		}

		if ($selectedServices == 0) {
			$encaissement->delete();
			flashMessage()->success("Veuillez sélectionner au moins un service avec montant ");
			URL::back();
		}

		$lignes = $encaissement->encaissementLignes();
		$montantTotal = 0;
		$selectedInscriptionsID = array();
		$selectedRubriquesID = array();

		foreach ($lignes as $ligne) {
			// montant
			$montantTotal += $ligne->get('Montant');
			// inscriptons
			if (!in_array($ligne->Inscription->ID, $selectedInscriptionsID)) {
				$selectedInscriptionsID[] = $ligne->Inscription->ID;
			}
			// rubriques
			if (!in_array($ligne->EncaissementRubrique->ID, $selectedRubriquesID)) {
				$selectedRubriquesID[]  = $ligne->EncaissementRubrique->ID;
			}
		}


		$encaissement
			->setJson('Inscriptions', $selectedInscriptionsID)
			->setJson('EncaissementRubriques', $selectedRubriquesID)
			->set('Montant', $montantTotal)
			->save();


		if ($avoirs) {
			if ($montantTotal > ($avoirs->Amount - $avoirs->ConsumedAmount)) {
				flashMessage()->success("Le montant de l'avoir choisi est inférieur du montant de l'encaissement.");
				URL::back();
				exit();
			}
			$avoirs->consume($encaissement);
		}

		Models\ActionLog::catchLog(
			"create 'Encaissement'  of " . $parent->get('User')->getNomComplet(),
			$encaissement
		);

		DB::commit();

		// if (isset($_POST['print_recu'])) {
		URL::redirect(URL::admin('encaissements/' . $encaissement->get('ID') . '/recu'));
		// }

		// URL::back();
	}


	function etat_paiements()
	{

		$selectedCycle = isset($_GET['cycle']) ? $_GET['cycle'] : 'all';
		$selectedNiveau = isset($_GET['niveau']) ? $_GET['niveau'] : 'all';
		$selectedClasse = isset($_GET['classe']) ? $_GET['classe'] : 'all';
		$selectedService = isset($_GET['service']) ? $_GET['service'] : 'all';
		$selectedMonth = isset($_GET['mois']) ? $_GET['mois'] : 'all';
		$selectedPromotion = isset($_GET['promotion']) ? $_GET['promotion'] : null;


		$cycles = Models\Cycle::getList();
		$niveaux =	array();
		$classes = array();

		$where = array();
		if ($selectedCycle != 'all') $where['Cycle'] = $selectedCycle;
		$niveaux = Models\Niveau::getList(array('where' => $where));

		$where = array();
		if ($selectedNiveau != 'all') $where['Niveau'] = $selectedNiveau;
		elseif ($selectedCycle != 'all') $where[] = "Niveau IN (SELECT ID FROM gen_niveaux WHERE Cycle = $selectedCycle)";
		$classes = Models\Classe::getList(array('where' => $where));

		$services = Models\fin\EncaissementRubrique::getList();
		$months = array(9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre', 1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet');


		$classesList = array();
		$selectedCycles = array();
		$selectedNiveaux = array();
		$selectedClasses = array();
		$inscriptions = array();

		if ($selectedClasse != 'all') {
			$where = array();
			$where['ID'] = $selectedClasse;
			$classesList = Models\Classe::getList(array('where' => $where));
		} elseif ($selectedNiveau != 'all') {
			$where = array();
			$where['Niveau'] = $selectedNiveau;
			$classesList = Models\Classe::getList(array('where' => $where));
		} elseif ($selectedCycle != 'all') {
			$where = array();
			$where[] = "Niveau IN (SELECT ID FROM gen_niveaux WHERE Cycle = $selectedCycle)";
			$classesList = Models\Classe::getList(array('where' => $where));
		} else {
			$classesList = Models\Classe::getList();
		}

		foreach ($classesList as $classe) {
			$classeID = $classe->get('ID');
			$niveau = $classe->get('Niveau');
			$niveauID = $niveau->get('ID');
			$cycle = $niveau->get('Cycle');
			$cycleID = $cycle->get('ID');

			$inscriptions[$classeID] = Models\Inscription::getList(array('where' => array('Classe' => $classeID)));
			$selectedClasses[$niveauID][] = $classe;

			if (!isset($selectedNiveaux[$cycleID]) || !in_array($niveau, $selectedNiveaux[$cycleID]))
				$selectedNiveaux[$cycleID][] = $niveau;

			if (!in_array($cycle, $selectedCycles))
				$selectedCycles[] = $cycle;
		}

		$selectedServices = $services;
		$selectedMonths = $months;

		$promotions = Models\Promotion::getList();

		if (!$selectedPromotion) {
			$selectedPromotion = Models\Promotion::actuelle()->getKey();
		}

		$countInscription = Models\Inscription::getCount(
			array(
				'where' => array(
					'Promotion' => $selectedPromotion
				),
			)
		);

		if (Request::has('export_excel')) {

			ini_set('memory_limit', '-1');
			set_time_limit(1800);

			loadLib('phpexcelsheet');
			if (!PHPExcel_Settings::setLocale('fr_FR'))
				echo 'Locale not set ' . PHP_EOL;

			try {

				$objPHPExcel = new PHPExcel();
				$sheet = $objPHPExcel->getActiveSheet();

				// $logo = Config::get('logo') ? _basepath . 'assets/images/schools/' . Config::get('logo') : _basepath . 'assets/images/logo.png';
				// $sheet->mergeCells('A1:B9');
				// $objDrawing = new PHPExcel_Worksheet_Drawing();
				// $objDrawing->setName(Config::get('nom_ecole'));
				// $objDrawing->setDescription(Config::get('nom_ecole'));
				// $objDrawing->setPath($logo);
				// $objDrawing->setCoordinates('A1');
				// //setOffsetX works properly
				// $objDrawing->setOffsetX(5);
				// $objDrawing->setOffsetY(5);
				// //set width, height
				// $objDrawing->setWidth(80);
				// $objDrawing->setHeight(80);
				// $objDrawing->setWorksheet($sheet);


				$sheet->getCellByColumnAndRow(0, 3)->setValue('Etat global de paiement')
					->getStyle()
					->getFont()
					->setSize(20);


				$ca_montant_total = 0;
				$ca_nombre_total = 0;

				$ec_montant_total = 0;
				$ec_nombre_total = 0;

				$row = 11;
				foreach ($selectedCycles as $cycle) {


					$cycleID = $cycle->get('ID');
					$col = 0;
					$sheet->getCellByColumnAndRow($col++, $row)->setValue($cycle->Label)
						->getStyle()
						->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						->getStartColor()
						->setRGB('FFA500');

					$ca_montant_total +=  $ca_montant = Models\FIN\EncaissementRubriqueInscription::getCAMontant(array(
						'Cycle' =>  $cycleID,
						'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
						'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
						'Promotion' => $selectedPromotion,
					));
					$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant)
						->getStyle()
						->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						->getStartColor()
						->setRGB('FFA500');


					$ca_nombre_total += $ca_nombre = Models\FIN\EncaissementRubriqueInscription::getCANombre(array(
						'Cycle' =>  $cycleID,
						'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
						'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
						'Promotion' => $selectedPromotion,

					));
					$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre)
						->getStyle()
						->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						->getStartColor()
						->setRGB('FFA500');


					$ec_montant_total += $ec_montant = Models\FIN\EncaissementLigne::getECMontant(array(
						'Cycle' =>  $cycleID,
						'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
						'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
						'Promotion' => $selectedPromotion,

					));
					$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_montant)
						->getStyle()
						->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						->getStartColor()
						->setRGB('FFA500');



					$ec_nombre_total += $ec_nombre = Models\FIN\EncaissementLigne::getECNombre(array(
						'Cycle' =>  $cycleID,
						'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
						'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
						'Promotion' => $selectedPromotion,


					));
					$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_nombre)
						->getStyle()
						->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						->getStartColor()
						->setRGB('FFA500');


					$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant - $ec_montant)
						->getStyle()
						->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						->getStartColor()
						->setRGB('FFA500');
					$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre - $ec_nombre)
						->getStyle()
						->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						->getStartColor()
						->setRGB('FFA500');


					$row += 2;

					foreach ($selectedNiveaux[$cycleID] as $niveau) {

						$niveauID = $niveau->get('ID');
						$col = 0;
						$sheet->getCellByColumnAndRow($col++, $row)->setValue("  " . $niveau->Label)
							->getStyle()
							->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()
							->setRGB('FFA500');

						$ca_montant = Models\FIN\EncaissementRubriqueInscription::getCAMontant(array(
							'Cycle' =>  $cycleID,
							'Niveau' =>  $niveauID,
							'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
							'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
							'Promotion' => $selectedPromotion,
						));
						$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant)
							->getStyle()
							->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()
							->setRGB('FFA500');

						$ca_nombre = Models\FIN\EncaissementRubriqueInscription::getCANombre(array(
							'Cycle' =>  $cycleID,
							'Niveau' =>  $niveauID,
							'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
							'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
							'Promotion' => $selectedPromotion,

						));
						$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre)
							->getStyle()
							->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()
							->setRGB('FFA500');


						$ec_montant = Models\FIN\EncaissementLigne::getECMontant(array(
							'Cycle' =>  $cycleID,
							'Niveau' =>  $niveauID,
							'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
							'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
							'Promotion' => $selectedPromotion,

						));
						$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_montant)
							->getStyle()
							->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()
							->setRGB('FFA500');



						$ec_nombre = Models\FIN\EncaissementLigne::getECNombre(array(
							'Cycle' =>  $cycleID,
							'Niveau' =>  $niveauID,
							'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
							'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
							'Promotion' => $selectedPromotion,


						));
						$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_nombre)
							->getStyle()
							->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()
							->setRGB('FFA500');


						$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant - $ec_montant)
							->getStyle()
							->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()
							->setRGB('FFA500');

						$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre - $ec_nombre)
							->getStyle()
							->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()
							->setRGB('FFA500');


						$row += 2;

						foreach ($selectedClasses[$niveauID] as $classe) {
							$classeID = $classe->get('ID');

							$col = 0;
							$sheet->getCellByColumnAndRow($col++, $row)->setValue("    " . $classe->Label)
								->getStyle()
								->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()
								->setRGB('FFA500');

							$ca_montant = Models\FIN\EncaissementRubriqueInscription::getCAMontant(array(
								'Cycle' =>  $cycleID,
								'Niveau' =>  $niveauID,
								'Classe' => $classeID,
								'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
								'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
								'Promotion' => $selectedPromotion,
							));
							$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant)
								->getStyle()
								->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()
								->setRGB('FFA500');


							$ca_nombre = Models\FIN\EncaissementRubriqueInscription::getCANombre(array(
								'Cycle' =>  $cycleID,
								'Niveau' =>  $niveauID,
								'Classe' => $classeID,
								'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
								'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
								'Promotion' => $selectedPromotion,

							));

							$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre)

								->getStyle()
								->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()
								->setRGB('FFA500');


							$ec_montant = Models\FIN\EncaissementLigne::getECMontant(array(
								'Cycle' =>  $cycleID,
								'Niveau' =>  $niveauID,
								'Classe' => $classeID,
								'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
								'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
								'Promotion' => $selectedPromotion,

							));

							$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_montant)
								->getStyle()
								->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()
								->setRGB('FFA500');



							$ec_nombre = Models\FIN\EncaissementLigne::getECNombre(array(
								'Cycle' =>  $cycleID,
								'Niveau' =>  $niveauID,
								'Classe' => $classeID,
								'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
								'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
								'Promotion' => $selectedPromotion,


							));
							$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_nombre)
								->getStyle()
								->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()
								->setRGB('FFA500');



							$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant - $ec_montant)
								->getStyle()
								->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()
								->setRGB('FFA500');

							$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre - $ec_nombre)
								->getStyle()
								->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()
								->setRGB('FFA500');

							$row += 2;

							foreach ($inscriptions[$classeID] as $inscription) {

								$col = 0;
								$sheet->getCellByColumnAndRow($col++, $row)->setValue("      " . $inscription->get('Eleve')->get('User')->getNomComplet());
								$ca_montant = Models\FIN\EncaissementRubriqueInscription::getCAMontant(array(
									'Cycle' =>  $cycleID,
									'Niveau' =>  $niveauID,
									'Inscription' => $inscription->ID,
									'Classe' => $classeID,
									'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
									'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
									'Promotion' => $selectedPromotion,
								));
								$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant);


								$ca_nombre = Models\FIN\EncaissementRubriqueInscription::getCANombre(array(
									'Cycle' =>  $cycleID,
									'Niveau' =>  $niveauID,
									'Inscription' => $inscription->ID,
									'Classe' => $classeID,
									'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
									'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
									'Promotion' => $selectedPromotion,

								));
								$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre);


								$ec_montant = Models\FIN\EncaissementLigne::getECMontant(array(
									'Cycle' =>  $cycleID,
									'Niveau' =>  $niveauID,
									'Classe' => $classeID,
									'Inscription' => $inscription->ID,
									'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
									'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
									'Promotion' => $selectedPromotion,

								));
								$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_montant);



								$ec_nombre = Models\FIN\EncaissementLigne::getECNombre(array(
									'Cycle' =>  $cycleID,
									'Niveau' =>  $niveauID,
									'Inscription' => $inscription->ID,
									'Classe' => $classeID,
									'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
									'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
									'Promotion' => $selectedPromotion,


								));
								$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_nombre);


								$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant - $ec_montant);
								$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre - $ec_nombre);
								$row++;
							}

							$row++;
						}

						$row++;
					}

					$row++;
				}

				$col = 0;
				$sheet->getCellByColumnAndRow($col++, $row)->setValue('Total')->getStyle()
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('FFA500');
				$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant_total)
					->getStyle()
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('FFA500');

				$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre_total)
					->getStyle()
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('FFA500');

				$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_montant_total)
					->getStyle()
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('FFA500');
				$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_nombre_total)

					->getStyle()
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('FFA500');
				$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant_total - $ec_montant_total)
					->getStyle()
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('FFA500');

				$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre_total - $ec_nombre_total)
					->getStyle()
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setRGB('FFA500');

				$row++;

				foreach (range('A', 'A') as $columnID) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
						->setAutoSize(true);
				}

				// redirect output to client browser
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="etat global de paiement' . date('Y_m_d_h_i_s') . '.xlsx"');
				header('Cache-Control: max-age=0');


				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				//dd($objWriter);
				$objWriter->save('php://output');
				exit;
			} catch (Exception $e) {
				echo $e->getMessage();
				exit;
			}
		}

		$data = array();
		$data['cycles'] = $cycles;
		$data['niveaux'] = $niveaux;
		$data['classes'] = $classes;
		$data['inscriptions'] = $inscriptions;
		$data['services'] = $services;
		$data['months'] = $months;
		$data['promotions'] = $promotions;
		$data['countInscription'] = $countInscription;
		$data['selectedCycle'] = $selectedCycle;
		$data['selectedNiveau'] = $selectedNiveau;
		$data['selectedClasse'] = $selectedClasse;
		$data['selectedService'] = $selectedService;
		$data['selectedMonth'] = $selectedMonth;
		$data['selectedServices'] = $selectedServices;
		$data['selectedMonths'] = $selectedMonths;
		$data['selectedCycles'] = $selectedCycles;
		$data['selectedNiveaux'] = $selectedNiveaux;
		$data['selectedClasses'] = $selectedClasses;
		$data['selectedPromotion'] = $selectedPromotion;

		loadView('encaissements/etat-paiements', $data);
	}
	// function etat_paiements()
	// {

	// 	$selectedCycle = isset($_GET['cycle']) ? $_GET['cycle'] : 'all';
	// 	$selectedNiveau = isset($_GET['niveau']) ? $_GET['niveau'] : 'all';
	// 	$selectedClasse = isset($_GET['classe']) ? $_GET['classe'] : 'all';
	// 	$selectedService = isset($_GET['service']) ? $_GET['service'] : 'all';
	// 	$selectedMonth = isset($_GET['mois']) ? $_GET['mois'] : 'all';
	// 	$selectedPromotion = isset($_GET['promotion']) ? $_GET['promotion'] : null;


	// 	$cycles = Models\Cycle::getList();
	// 	$niveaux =	array();
	// 	$classes = array();

	// 	$where = array();
	// 	if ($selectedCycle != 'all') $where['Cycle'] = $selectedCycle;
	// 	$niveaux = Models\Niveau::getList(array('where' => $where));

	// 	$where = array();
	// 	if ($selectedNiveau != 'all') $where['Niveau'] = $selectedNiveau;
	// 	elseif ($selectedCycle != 'all') $where[] = "Niveau IN (SELECT ID FROM gen_niveaux WHERE Cycle = $selectedCycle)";
	// 	$classes = Models\Classe::getList(array('where' => $where));

	// 	$services = Models\fin\EncaissementRubrique::getList();
	// 	$months = array(9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre', 1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet');


	// 	$classesList = array();
	// 	$selectedCycles = array();
	// 	$selectedNiveaux = array();
	// 	$selectedClasses = array();
	// 	$inscriptions = array();

	// 	if ($selectedClasse != 'all') {
	// 		$where = array();
	// 		$where['ID'] = $selectedClasse;
	// 		$classesList = Models\Classe::getList(array('where' => $where));
	// 	} elseif ($selectedNiveau != 'all') {
	// 		$where = array();
	// 		$where['Niveau'] = $selectedNiveau;
	// 		$classesList = Models\Classe::getList(array('where' => $where));
	// 	} elseif ($selectedCycle != 'all') {
	// 		$where = array();
	// 		$where[] = "Niveau IN (SELECT ID FROM gen_niveaux WHERE Cycle = $selectedCycle)";
	// 		$classesList = Models\Classe::getList(array('where' => $where));
	// 	} else {
	// 		$classesList = Models\Classe::getList();
	// 	}

	// 	foreach ($classesList as $classe) {
	// 		$classeID = $classe->get('ID');
	// 		$niveau = $classe->get('Niveau');
	// 		$niveauID = $niveau->get('ID');
	// 		$cycle = $niveau->get('Cycle');
	// 		$cycleID = $cycle->get('ID');

	// 		$inscriptions[$classeID] = Models\Inscription::getList(array('where' => array('Classe' => $classeID)));
	// 		$selectedClasses[$niveauID][] = $classe;

	// 		if (!isset($selectedNiveaux[$cycleID]) || !in_array($niveau, $selectedNiveaux[$cycleID]))
	// 			$selectedNiveaux[$cycleID][] = $niveau;

	// 		if (!in_array($cycle, $selectedCycles))
	// 			$selectedCycles[] = $cycle;
	// 	}

	// 	$selectedServices = $services;
	// 	$selectedMonths = $months;

	// 	$promotions = Models\Promotion::getList();

	// 	if (!$selectedPromotion) {
	// 		$selectedPromotion = Models\Promotion::actuelle()->getKey();
	// 	}

	// 	$countInscription = Models\Inscription::getCount(
	// 		array(
	// 			'where' => array(
	// 				'Promotion' => $selectedPromotion
	// 			),
	// 		)
	// 	);

	// 	if (Request::has('export_excel')) {

	// 		ini_set('memory_limit', '-1');
	// 		set_time_limit(1800);

	// 		loadLib('phpexcelsheet');
	// 		if (!PHPExcel_Settings::setLocale('fr_FR'))
	// 			echo 'Locale not set ' . PHP_EOL;

	// 		try {

	// 			$objPHPExcel = new PHPExcel();
	// 			$sheet = $objPHPExcel->getActiveSheet();

	// 			// $logo = Config::get('logo') ? _basepath . 'assets/images/schools/' . Config::get('logo') : _basepath . 'assets/images/logo.png';
	// 			// $sheet->mergeCells('A1:B9');
	// 			// $objDrawing = new PHPExcel_Worksheet_Drawing();
	// 			// $objDrawing->setName(Config::get('nom_ecole'));
	// 			// $objDrawing->setDescription(Config::get('nom_ecole'));
	// 			// $objDrawing->setPath($logo);
	// 			// $objDrawing->setCoordinates('A1');
	// 			// //setOffsetX works properly
	// 			// $objDrawing->setOffsetX(5);
	// 			// $objDrawing->setOffsetY(5);
	// 			// //set width, height
	// 			// $objDrawing->setWidth(80);
	// 			// $objDrawing->setHeight(80);
	// 			// $objDrawing->setWorksheet($sheet);


	// 			$sheet->getCellByColumnAndRow(0, 3)->setValue('Etat global de paiement')
	// 				->getStyle()
	// 				->getFont()
	// 				->setSize(20);


	// 			$ca_montant_total = 0;
	// 			$ca_nombre_total = 0;

	// 			$ec_montant_total = 0;
	// 			$ec_nombre_total = 0;

	// 			$row = 11;
	// 			foreach ($selectedCycles as $cycle) {


	// 				$cycleID = $cycle->get('ID');
	// 				$col = 0;
	// 				$sheet->getCellByColumnAndRow($col++, $row)->setValue($cycle->Label)
	// 					->getStyle()
	// 					->getFill()
	// 					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 					->getStartColor()
	// 					->setRGB('FFA500');

	// 				$ca_montant_total +=  $ca_montant = Models\FIN\EncaissementRubriqueInscription::getCAMontant(array(
	// 					'Cycle' =>  $cycleID,
	// 					'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
	// 					'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
	// 					'Promotion' => $selectedPromotion,
	// 				));
	// 				$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant)
	// 					->getStyle()
	// 					->getFill()
	// 					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 					->getStartColor()
	// 					->setRGB('FFA500');


	// 				$ca_nombre_total += $ca_nombre = Models\FIN\EncaissementRubriqueInscription::getCANombre(array(
	// 					'Cycle' =>  $cycleID,
	// 					'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
	// 					'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
	// 					'Promotion' => $selectedPromotion,

	// 				));
	// 				$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre)
	// 					->getStyle()
	// 					->getFill()
	// 					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 					->getStartColor()
	// 					->setRGB('FFA500');


	// 				$ec_montant_total += $ec_montant = Models\FIN\EncaissementLigne::getECMontant(array(
	// 					'Cycle' =>  $cycleID,
	// 					'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
	// 					'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
	// 					'Promotion' => $selectedPromotion,

	// 				));
	// 				$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_montant)
	// 					->getStyle()
	// 					->getFill()
	// 					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 					->getStartColor()
	// 					->setRGB('FFA500');



	// 				$ec_nombre_total += $ec_nombre = Models\FIN\EncaissementLigne::getECNombre(array(
	// 					'Cycle' =>  $cycleID,
	// 					'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
	// 					'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
	// 					'Promotion' => $selectedPromotion,


	// 				));
	// 				$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_nombre)
	// 					->getStyle()
	// 					->getFill()
	// 					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 					->getStartColor()
	// 					->setRGB('FFA500');


	// 				$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant - $ec_montant)
	// 					->getStyle()
	// 					->getFill()
	// 					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 					->getStartColor()
	// 					->setRGB('FFA500');
	// 				$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre - $ec_nombre)
	// 					->getStyle()
	// 					->getFill()
	// 					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 					->getStartColor()
	// 					->setRGB('FFA500');


	// 				$row += 2;

	// 				foreach ($selectedNiveaux[$cycleID] as $niveau) {

	// 					$niveauID = $niveau->get('ID');
	// 					$col = 0;
	// 					$sheet->getCellByColumnAndRow($col++, $row)->setValue("  " . $niveau->Label)
	// 						->getStyle()
	// 						->getFill()
	// 						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 						->getStartColor()
	// 						->setRGB('FFA500');

	// 					$ca_montant = Models\FIN\EncaissementRubriqueInscription::getCAMontant(array(
	// 						'Cycle' =>  $cycleID,
	// 						'Niveau' =>  $niveauID,
	// 						'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
	// 						'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
	// 						'Promotion' => $selectedPromotion,
	// 					));
	// 					$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant)
	// 						->getStyle()
	// 						->getFill()
	// 						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 						->getStartColor()
	// 						->setRGB('FFA500');

	// 					$ca_nombre = Models\FIN\EncaissementRubriqueInscription::getCANombre(array(
	// 						'Cycle' =>  $cycleID,
	// 						'Niveau' =>  $niveauID,
	// 						'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
	// 						'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
	// 						'Promotion' => $selectedPromotion,

	// 					));
	// 					$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre)
	// 						->getStyle()
	// 						->getFill()
	// 						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 						->getStartColor()
	// 						->setRGB('FFA500');


	// 					$ec_montant = Models\FIN\EncaissementLigne::getECMontant(array(
	// 						'Cycle' =>  $cycleID,
	// 						'Niveau' =>  $niveauID,
	// 						'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
	// 						'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
	// 						'Promotion' => $selectedPromotion,

	// 					));
	// 					$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_montant)
	// 						->getStyle()
	// 						->getFill()
	// 						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 						->getStartColor()
	// 						->setRGB('FFA500');



	// 					$ec_nombre = Models\FIN\EncaissementLigne::getECNombre(array(
	// 						'Cycle' =>  $cycleID,
	// 						'Niveau' =>  $niveauID,
	// 						'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
	// 						'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
	// 						'Promotion' => $selectedPromotion,


	// 					));
	// 					$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_nombre)
	// 						->getStyle()
	// 						->getFill()
	// 						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 						->getStartColor()
	// 						->setRGB('FFA500');


	// 					$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant - $ec_montant)
	// 						->getStyle()
	// 						->getFill()
	// 						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 						->getStartColor()
	// 						->setRGB('FFA500');

	// 					$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre - $ec_nombre)
	// 						->getStyle()
	// 						->getFill()
	// 						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 						->getStartColor()
	// 						->setRGB('FFA500');


	// 					$row += 2;

	// 					foreach ($selectedClasses[$niveauID] as $classe) {
	// 						$classeID = $classe->get('ID');

	// 						$col = 0;
	// 						$sheet->getCellByColumnAndRow($col++, $row)->setValue("    " . $classe->Label)
	// 							->getStyle()
	// 							->getFill()
	// 							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 							->getStartColor()
	// 							->setRGB('FFA500');

	// 						$ca_montant = Models\FIN\EncaissementRubriqueInscription::getCAMontant(array(
	// 							'Cycle' =>  $cycleID,
	// 							'Niveau' =>  $niveauID,
	// 							'Classe' => $classeID,
	// 							'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
	// 							'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
	// 							'Promotion' => $selectedPromotion,
	// 						));
	// 						$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant)
	// 							->getStyle()
	// 							->getFill()
	// 							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 							->getStartColor()
	// 							->setRGB('FFA500');


	// 						$ca_nombre = Models\FIN\EncaissementRubriqueInscription::getCANombre(array(
	// 							'Cycle' =>  $cycleID,
	// 							'Niveau' =>  $niveauID,
	// 							'Classe' => $classeID,
	// 							'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
	// 							'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
	// 							'Promotion' => $selectedPromotion,

	// 						));

	// 						$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre)

	// 							->getStyle()
	// 							->getFill()
	// 							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 							->getStartColor()
	// 							->setRGB('FFA500');


	// 						$ec_montant = Models\FIN\EncaissementLigne::getECMontant(array(
	// 							'Cycle' =>  $cycleID,
	// 							'Niveau' =>  $niveauID,
	// 							'Classe' => $classeID,
	// 							'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
	// 							'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
	// 							'Promotion' => $selectedPromotion,

	// 						));

	// 						$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_montant)
	// 							->getStyle()
	// 							->getFill()
	// 							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 							->getStartColor()
	// 							->setRGB('FFA500');



	// 						$ec_nombre = Models\FIN\EncaissementLigne::getECNombre(array(
	// 							'Cycle' =>  $cycleID,
	// 							'Niveau' =>  $niveauID,
	// 							'Classe' => $classeID,
	// 							'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
	// 							'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
	// 							'Promotion' => $selectedPromotion,


	// 						));
	// 						$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_nombre)
	// 							->getStyle()
	// 							->getFill()
	// 							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 							->getStartColor()
	// 							->setRGB('FFA500');



	// 						$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant - $ec_montant)
	// 							->getStyle()
	// 							->getFill()
	// 							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 							->getStartColor()
	// 							->setRGB('FFA500');

	// 						$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre - $ec_nombre)
	// 							->getStyle()
	// 							->getFill()
	// 							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 							->getStartColor()
	// 							->setRGB('FFA500');

	// 						$row += 2;

	// 						foreach ($inscriptions[$classeID] as $inscription) {

	// 							$col = 0;
	// 							$sheet->getCellByColumnAndRow($col++, $row)->setValue("      " . $inscription->get('Eleve')->get('User')->getNomComplet());
	// 							$ca_montant = Models\FIN\EncaissementRubriqueInscription::getCAMontant(array(
	// 								'Cycle' =>  $cycleID,
	// 								'Niveau' =>  $niveauID,
	// 								'Inscription' => $inscription->ID,
	// 								'Classe' => $classeID,
	// 								'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
	// 								'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
	// 								'Promotion' => $selectedPromotion,
	// 							));
	// 							$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant);


	// 							$ca_nombre = Models\FIN\EncaissementRubriqueInscription::getCANombre(array(
	// 								'Cycle' =>  $cycleID,
	// 								'Niveau' =>  $niveauID,
	// 								'Inscription' => $inscription->ID,
	// 								'Classe' => $classeID,
	// 								'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
	// 								'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
	// 								'Promotion' => $selectedPromotion,

	// 							));
	// 							$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre);


	// 							$ec_montant = Models\FIN\EncaissementLigne::getECMontant(array(
	// 								'Cycle' =>  $cycleID,
	// 								'Niveau' =>  $niveauID,
	// 								'Classe' => $classeID,
	// 								'Inscription' => $inscription->ID,
	// 								'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
	// 								'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
	// 								'Promotion' => $selectedPromotion,

	// 							));
	// 							$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_montant);



	// 							$ec_nombre = Models\FIN\EncaissementLigne::getECNombre(array(
	// 								'Cycle' =>  $cycleID,
	// 								'Niveau' =>  $niveauID,
	// 								'Inscription' => $inscription->ID,
	// 								'Classe' => $classeID,
	// 								'EncaissementRubrique' => ($selectedService != "all"  ? $selectedService : null),
	// 								'Mois' => ($selectedMonth != "all" ? $selectedMonth : null),
	// 								'Promotion' => $selectedPromotion,


	// 							));
	// 							$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_nombre);


	// 							$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant - $ec_montant);
	// 							$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre - $ec_nombre);
	// 							$row++;
	// 						}

	// 						$row++;
	// 					}

	// 					$row++;
	// 				}

	// 				$row++;
	// 			}

	// 			$col = 0;
	// 			$sheet->getCellByColumnAndRow($col++, $row)->setValue('Total')->getStyle()
	// 				->getFill()
	// 				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 				->getStartColor()
	// 				->setRGB('FFA500');
	// 			$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant_total)
	// 				->getStyle()
	// 				->getFill()
	// 				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 				->getStartColor()
	// 				->setRGB('FFA500');

	// 			$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre_total)
	// 				->getStyle()
	// 				->getFill()
	// 				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 				->getStartColor()
	// 				->setRGB('FFA500');

	// 			$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_montant_total)
	// 				->getStyle()
	// 				->getFill()
	// 				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 				->getStartColor()
	// 				->setRGB('FFA500');
	// 			$sheet->getCellByColumnAndRow($col++, $row)->setValue($ec_nombre_total)

	// 				->getStyle()
	// 				->getFill()
	// 				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 				->getStartColor()
	// 				->setRGB('FFA500');
	// 			$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_montant_total - $ec_montant_total)
	// 				->getStyle()
	// 				->getFill()
	// 				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 				->getStartColor()
	// 				->setRGB('FFA500');

	// 			$sheet->getCellByColumnAndRow($col++, $row)->setValue($ca_nombre_total - $ec_nombre_total)
	// 				->getStyle()
	// 				->getFill()
	// 				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	// 				->getStartColor()
	// 				->setRGB('FFA500');

	// 			$row++;

	// 			foreach (range('A', 'A') as $columnID) {
	// 				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
	// 					->setAutoSize(true);
	// 			}

	// 			// redirect output to client browser
	// 			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	// 			header('Content-Disposition: attachment;filename="etat global de paiement' . date('Y_m_d_h_i_s') . '.xlsx"');
	// 			header('Cache-Control: max-age=0');


	// 			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	// 			//dd($objWriter);
	// 			$objWriter->save('php://output');
	// 			exit;
	// 		} catch (Exception $e) {
	// 			echo $e->getMessage();
	// 			exit;
	// 		}
	// 	}

	// 	$data = array();
	// 	$data['cycles'] = $cycles;
	// 	$data['niveaux'] = $niveaux;
	// 	$data['classes'] = $classes;
	// 	$data['inscriptions'] = $inscriptions;
	// 	$data['services'] = $services;
	// 	$data['months'] = $months;
	// 	$data['promotions'] = $promotions;
	// 	$data['countInscription'] = $countInscription;
	// 	$data['selectedCycle'] = $selectedCycle;
	// 	$data['selectedNiveau'] = $selectedNiveau;
	// 	$data['selectedClasse'] = $selectedClasse;
	// 	$data['selectedService'] = $selectedService;
	// 	$data['selectedMonth'] = $selectedMonth;
	// 	$data['selectedServices'] = $selectedServices;
	// 	$data['selectedMonths'] = $selectedMonths;
	// 	$data['selectedCycles'] = $selectedCycles;
	// 	$data['selectedNiveaux'] = $selectedNiveaux;
	// 	$data['selectedClasses'] = $selectedClasses;
	// 	$data['selectedPromotion'] = $selectedPromotion;

	// 	loadView('encaissements/etat-paiements', $data);
	// }

	function etat_impayes()
	{

		set_time_limit(0);
		$promotions = Models\Promotion::getList();
		$promotion = Models\Promotion::promotion_actuelle();
		$months_list = _zero_months_list;
		$month   = !isset($_GET['month']) ? date('n') : null;
		$niveau = null;
		$service =  null;
		$select_pre_month = null;

		$services = (object)array(
			'annules' => Models\FIN\EncaissementRubrique::annulesService(),
			'mensuels' => Models\FIN\EncaissementRubrique::mensuelServices(),
		);


		$niveaux = Models\Niveau::getList();

		if (!isset($_GET['niveau'])) {
			$niveau = $niveaux[0];
		}

		if (isset($_GET['service']) && $_GET['service'] != 'all') {
			$service =  new  Models\FIN\EncaissementRubrique($_GET['service']);
			if (!$service->Optionnel) {
				$month = 0;
			}
		}

		if (isset($_GET['niveau']) && $_GET['niveau'] != 'all') {
			$niveau =  new  Models\Niveau($_GET['niveau']);
		}

		if (isset($_GET['promotion'])) {
			$promotion = new Models\Promotion($_GET['promotion']);
		}

		if (isset($_GET['month']) && $_GET['month'] != 'all') {
			$month = $_GET['month'];
		}

		if (isset($_GET['select_pre_month']) && $_GET['select_pre_month']) {
			$select_pre_month = true;
		}

		$inscriptions = Models\FIN\EncaissementRubrique::unpaidInscriptions($promotion, $service, $month,   $niveau, null, $select_pre_month);
		$inscriptions = $inscriptions->get();



		if (isset($_GET['export_pdf'])) {

			$title = 'Etat des impayés ';

			if ($service && $month)
				$title .=  '( Service' . $service->get('Label') . 'pour le mois' . $months_list[$month] . ' )';
			else if ($month && !$service)
				$title .= '( Mois ' .  $months_list[$month] .  'pour tous les services )';
			else if (!$month && $service)
				$title .=  '( Service ' .  $service->get('Label') . 'pour tous les mois )';
			else
				$title .=  '( Tous les services et mois )';

			$data['title'] = $title;

			set_time_limit(-1);
			loadLib('tcpdf');
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$fontArabicBold = TCPDF_FONTS::addTTFfont(_basepath . 'assets/fonts/ADOBEARABIC-BOLD.OTF', 'TrueTypeUnicode', '', 96);
			$pdf->SetFont($fontArabicBold, '', 14, '', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('');
			$pdf->SetTitle($title);
			$pdf->SetSubject('');
			$pdf->SetKeywords('');
			// remove default header/footer
			$pdf->setPrintHeader(false);
			// $pdf->setPrintFooter(false);
			// set header and footer fonts
			$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			// set margins
			$pdf->SetMargins(5, 3, 5);
			// set image scale factor
			//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			$pdf->AddPage('P', 'A4');
			$html = getView('encaissements/etat-impayes-pdf',  array(
				'promotion' => $promotion,
				'promotions' => $promotions,
				'months_list' => $months_list,
				'month' => $month,
				'services' => $services,
				'service' => $service,
				'niveaux' => $niveaux,
				'niveau' => $niveau,
				'title' => $title,
				'inscriptions' => $inscriptions,
				'select_pre_month' => $select_pre_month
			), 'null_layout');

			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->lastPage();
			ob_end_clean();
			// ---------------------------------------------------------

			//Close and output PDF document
			$fileTitle = strtolower(str_replace(' ', '_', $title)) . ' ' . date('Ymd-His') . '.pdf';
			$pdf->Output($fileTitle, 'I');
		}

		loadView('encaissements/etat-impayes', array(
			'promotion' => $promotion,
			'promotions' => $promotions,

			'months_list' => $months_list,
			'month' => $month,

			'services' => $services,
			'service' => $service,

			'niveaux' => $niveaux,
			'niveau' => $niveau,

			'inscriptions' => $inscriptions,
			'select_pre_month' => $select_pre_month
		));
	}


	function etat_journalier()
	{

		$niveaux = Models\Niveau::getList();
		$classes = Models\Classe::getList();
		$promotions = Models\Promotion::getList();
		$promotion = Models\Promotion::promotion_actuelle();
		$classe = null;
		$niveau = null;
		$mode = null;
		$user = roleIs('agent_plus', 'resp_financier') ? Session::user() : null;
		$compte_bancaire = null;

		$zone_recherche = null;
		$periode = null;
		$filtre_periode = null;
		$filter = array(
			'classe' => null,
			'niveau' => null,
			'zone_recherche' => null,
			'periode' => null,
			'promotion' => null,
			'user' => null,
			'compte_bancaire' => null,
		);



		$encaissements = array();
		if (isset($_SESSION['filtreEncaissementDataHolder'])) {

			$filtreEncaissementDataHolder = unserialize($_SESSION['filtreEncaissementDataHolder']);

			$filtre_periode = $filtreEncaissementDataHolder['filtre_periode'];

			if ($filtreEncaissementDataHolder['classe']) {
				$classe = new Models\Classe($filtreEncaissementDataHolder['classe']);
				$filter['classe'] = $classe;
			}

			if ($filtreEncaissementDataHolder['niveau']) {
				$niveau = new Models\Niveau($filtreEncaissementDataHolder['niveau']);
				$filter['niveau'] = $niveau;
			}

			if ($filtreEncaissementDataHolder['mode']) {
				$mode = Models\FIN\PaiementMode::getByAlias($filtreEncaissementDataHolder['mode']);
				$filter['mode'] = $mode;
			}

			if ($filtreEncaissementDataHolder['promotion']) {
				$promotion = new Models\Promotion($filtreEncaissementDataHolder['promotion']);
				$filter['promotion'] = $promotion;
			}


			if ($filtreEncaissementDataHolder['user']) {
				$user = new Models\User($filtreEncaissementDataHolder['user']);
				$filter['user'] = $user;
			}


			if ($filtreEncaissementDataHolder['compte_bancaire']) {
				$compte_bancaire = new Models\FIN\CompteBancaire($filtreEncaissementDataHolder['compte_bancaire']);
				$filter['compte_bancaire'] = $compte_bancaire;
			}

			if (isset($_SESSION['numRecuAdded'])) {
				$zone_recherche = $_SESSION['numRecuAdded'];
				$filtreEncaissementDataHolder['zone_recherche'] = $_SESSION['numRecuAdded'];
				$filter['zone_recherche'] = $zone_recherche;
				unset($_SESSION['numRecuAdded']);
			} elseif ($filtreEncaissementDataHolder['zone_recherche']) {
				$zone_recherche = $filtreEncaissementDataHolder['zone_recherche'];
				$filter['zone_recherche'] = $zone_recherche;
			}

			if ($filtre_periode) {
				$periode = $filtreEncaissementDataHolder['periode'];
				$filter['periode'] = $periode;
			}

			$_SESSION['filtreEncaissementDataHolder'] = serialize($filtreEncaissementDataHolder);

			$encaissements = Models\FIN\Encaissement::getEncaissements($filter);
		}



		if (isset($_GET['print_pdf'])) {

			loadLib('tcpdf');
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('Jamal');
			$pdf->SetTitle('Etat Encaissements');

			// remove default header/footer
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);

			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			$pdf->AddPage('P', 'A4');

			$html = getView('encaissements/encaissements-list-pdf', array(
				'encaissements' => $encaissements,
				'niveaux' => $niveaux,
				'niveau' => $niveau,
				'classe' => $classe,
				'promotions' => $promotions,
				'promotion' => $promotion,
				'mode' => $mode,
				'classes' => $classes,
				'user' => $user,
				'compte_bancaire' => $compte_bancaire,
				'zone_recherche' => $zone_recherche,
				'periode' => $periode,
				'filtre_periode' => $filtre_periode,
				'navKey' => 'encaissements',
			), 'null_layout');

			// echo $html;


			// output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');

			$pdf->lastPage();
			ob_end_clean();
			$pdf->Output('dddd.pdf', 'I');
			exit;
		}


		if (isset($_GET['export_excel'])) {


			set_time_limit(-1);
			header('Content-Type: text/plain; charset=utf8');
			loadLib('phpexcel');
			if (!PHPExcel_Settings::setLocale('fr_FR'))
				echo 'Locale not set ' . PHP_EOL;

			$objPHPExcel = new PHPExcel();
			$activeSheet = $objPHPExcel->getActiveSheet();
			$activeSheet->setCellValue('A1', "Etat des encaissements");
			$activeSheet->mergeCells('A1:F2');


			$activeSheet->setCellValue('A3', "M");
			$activeSheet->setCellValue('B3', "Nom et prénom ");
			$activeSheet->setCellValue('C3', "Num reçu");
			$activeSheet->setCellValue('D3', "Montant");
			$activeSheet->setCellValue('E3', "M.reg");
			$activeSheet->setCellValue('F3', "Date paiement");
			$activeSheet->setCellValue('G3', "Année scolaire");
			$activeSheet->setCellValue('H3', "Détails");


			$row = 5;
			$mode_reg = [
				'total' => [
					'label' => 'Total',
					'regle' => 0,
					'annule' => 0,
				],
			];
			foreach ($encaissements as $key => $item) {

				if (!isset($mode_reg[$item->PaiementMode])) {
					$mode_reg[$item->PaiementMode] = [
						'label' => $item->getPaiementMode()->get('Label'),
						'regle' => 0,
						'annule' => 0,

					];
				}


				$mode_reg[$item->PaiementMode]['regle'] += $item->Montant;
				$mode_reg['total']['regle'] += $item->Montant;

				if ($item->Canceled) {
					$mode_reg[$item->PaiementMode]['annule'] += $item->Montant;
					$mode_reg['total']['annule'] += $item->Montant;

					$activeSheet->getStyle("A$row:G$row")->applyFromArray(
						array(
							'fill' => array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb' => 'FF0000')
							)
						)
					);
				}


				$lignesShowed = array();
				$eleves  = [];
				$lignes = $item->encaissementLignes();

				foreach ($lignes as $ligne) {

					$service =  $ligne->get('EncaissementRubrique');
					$eleves[$ligne->get('Inscription')->ID] = $ligne->get('Inscription')->get('Eleve')->get('User')->getNomComplet() . ' (' . ($ligne->get('Inscription')->get('Classe') ? $ligne->get('Inscription')->get('Classe')->Label : ' --- ') . ' ) ';
					$key =  $service->get('ID');

					if (!isset($lignesShowed[$key])) {
						$lignesShowed[$key] = (object)array(
							'service' =>  $service->get('Label'),
							'montant' => $ligne->get('Montant'),
							'months' =>  array()
						);
					} else {
						$lignesShowed[$key]->montant += $ligne->get('Montant');
					}

					$lignesShowed[$key]->months[] =  ((!$service->get('Mensuel')  && $ligne->get('Mois') == 0) || !$ligne->get('Mois')) ? 'FA' : $ligne->get('Mois');
				}
				$details = '';
				foreach ($lignesShowed as $s) {
					$details .= 	$s->service . ' ' . Tools::numberFormat($s->montant) . ' [ ' . implode(',', $s->months) . ' ] ' . ', ';
				}

				$activeSheet->setCellValue("A$row", $item->get('ID'));
				$activeSheet->setCellValue("B$row", implode(',', $eleves));
				$activeSheet->setCellValue("C$row", $item->get('NumRecu'));
				$activeSheet->setCellValue("D$row", ($item->Canceled ? '-' : '') . $item->get('Montant'));
				$activeSheet->setCellValue("E$row", Tools::dateFormat($item->get('DatePaiement')));
				$activeSheet->setCellValue("F$row", $item->getPaiementMode() ? $item->getPaiementMode()->get('Label') : '----');
				$activeSheet->setCellValue("G$row", $item->Promotion->get('Label'));
				$activeSheet->setCellValue("H$row", $details);

				$row += 1;
			}
			$row += 3;



			$activeSheet->setCellValue("A$row", 'Mode de règlement');
			$activeSheet->setCellValue("B$row", 'Réglé');
			$activeSheet->setCellValue("C$row", 'Annulé');
			$activeSheet->setCellValue("D$row", 'Total');

			$row++;

			foreach (array_reverse($mode_reg) as $mode) {
				$activeSheet->setCellValue("A$row", $mode['label']);
				$activeSheet->setCellValue("B$row", \Tools::numberFormat($mode['regle'], 2));
				$activeSheet->setCellValue("C$row", \Tools::numberFormat(-$mode['annule'], 2));
				$activeSheet->setCellValue("D$row",  \Tools::numberFormat($mode['regle'] - $mode['annule'], 2));
				$row++;
			}

			excel_styling($activeSheet, array(
				'header' => array('A1:G3'),
				'body' => array("A4:G$row"),
			));

			foreach (range('A', 'U') as $col)
				$activeSheet->getColumnDimension($col)->setAutoSize(true);


			// redirect output to client browser
			$title = 'Etat des encaissements';
			$fileTitle = alais_string($title) . '_' . date('Ymd-His') . '.xlsx';
			// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); didn't work on windows
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="' . $fileTitle . '"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			ob_end_clean();
			$objWriter->save('php://output');
			// print_r($objPHPExcel);

			exit;
		}

		loadView('encaissements/etat-journalier', array(
			'encaissements' => $encaissements,
			'niveaux' => $niveaux,
			'niveau' => $niveau,
			'classe' => $classe,
			'promotions' => $promotions,
			'promotion' => $promotion,
			'mode' => $mode,
			'user' => $user,
			'classes' => $classes,
			'compte_bancaire' => $compte_bancaire,
			'zone_recherche' => $zone_recherche,
			'periode' => $periode,
			'filtre_periode' => $filtre_periode,
			'navKey' => 'encaissements',
		));
	}


	function etat_paiements_mois()
	{

		set_time_limit(0);
		$promotions = Models\Promotion::getList();
		$niveaux = Models\Niveau::getList();
		$services = (object)array(
			'annules' => Models\FIN\EncaissementRubrique::annulesService(),
			'mensuels' => Models\FIN\EncaissementRubrique::mensuelServices(),
		);

		$months_list = _zero_months_list;
		$classes = array();
		$inscriptions = array();
		$classe   =  null;
		$niveau = null;
		$promotion = null;
		$by_service = isset($_GET['by_service']);

		if (!isset($_GET['promotion'])) {
			$promotion = Models\Promotion::actuelle();
		}

		if (!isset($_GET['niveau'])) {
			$niveau = $niveaux[0];
			$classes = Models\Classe::getList(array('where' => array('Niveau' => $niveau->get('ID'))));
		}

		if (isset($_GET['classe']) && $_GET['classe'] != 'all') {
			$classe =  new  Models\Classe($_GET['classe']);
		}

		if (isset($_GET['niveau']) && $_GET['niveau'] != 'all') {
			$niveau =  new  Models\Niveau($_GET['niveau']);
			$classes = Models\Classe::getList(array('where' => array('Niveau' => $niveau->get('ID'))));
		}

		if (isset($_GET['promotion'])) {
			$promotion = new Models\Promotion($_GET['promotion']);
		}


		if ($classe) {
			$inscriptions = $classe->getInscriptions();
		} else {
			$inscriptions = $niveau->getInscriptions($promotion);
		}

		if (isset($_GET['export'])) {

			set_time_limit(0);
			ini_set('memory_limit', '-1');

			header('Content-Type: text/plain; charset=utf8');
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="Encaissements-'  . date('Y-m-d h:i:s') . '.xlsx"');
			header('Cache-Control: max-age=0');


			loadLib('phpexcel');
			$objPHPExcel = new PHPExcel();
			// $objPHPExcel->getSheet(); // at index 0
			$sheet = $objPHPExcel->getActiveSheet(); // Same same, by default active sheet is at 0

			foreach (range('A', 'G') as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
					->setAutoSize(true);
			}

			if ($by_service) {
				// First
				$col = 0;
				$startRow = 1;

				$sheet->getCellByColumnAndRow($col++, $startRow)->setValueExplicit('Nom Complet')->getStyle()->getFont()->setBold(true);;
				$sheet->getCellByColumnAndRow($col++, $startRow)->setValueExplicit('Classe')->getStyle()->getFont()->setBold(true);

				foreach ($months_list as $key => $month) {
					$sheet->getCellByColumnAndRow($col, $startRow)->setValueExplicit($month)->getStyle()->getFont()->setBold(true);;
					$col += 3;
				}


				// Second
				$col = 2;
				$startRow++;
				foreach ($months_list as $key => $month) {

					if ($month  == 0) {
						foreach ($services->annules as $service) {
							$sheet->getCellByColumnAndRow($col, $startRow)->setValueExplicit($service->Label)->getStyle()->getFont()->setBold(true);
							$col += 3;
						}
					} else {
						foreach ($services->mensuels as $service) {
							$sheet->getCellByColumnAndRow($col++, $startRow)->setValueExplicit($service->Label)->getStyle()->getFont()->setBold(true);
							$col += 3;
						}
					}
				}


				// Second
				$col = 2;
				$startRow++;
				foreach ($months_list as $key => $month) {
					if ($month  == 0) {
						foreach ($services->annules as $service) {
							$sheet->getCellByColumnAndRow($col++, $startRow)->setValueExplicit('Total')->getStyle()->getFont()->setBold(true);
							$sheet->getCellByColumnAndRow($col++, $startRow)->setValueExplicit('Encaisse')->getStyle()->getFont()->setBold(true);
							$sheet->getCellByColumnAndRow($col++, $startRow)->setValueExplicit('Reste')->getStyle()->getFont()->setBold(true);
						}
					} else {
						foreach ($services->mensuels as $service) {
							$sheet->getCellByColumnAndRow($col++, $startRow)->setValueExplicit('Total')->getStyle()->getFont()->setBold(true);
							$sheet->getCellByColumnAndRow($col++, $startRow)->setValueExplicit('Encaisse')->getStyle()->getFont()->setBold(true);
							$sheet->getCellByColumnAndRow($col++, $startRow)->setValueExplicit('Reste')->getStyle()->getFont()->setBold(true);
						}
					}
				}


				$startRow++;
				$i = 0;
				foreach ($inscriptions as $i => $ins) {

					$currentRow = $startRow + ($i++);
					$col = 0;

					$_classe =  $ins->get('Classe') ? $ins->get('Classe')->get('Label') : '---';
					$sheet->getCellByColumnAndRow($col++, $currentRow)->setValueExplicit($ins->Eleve->User->getNomComplet())->getStyle()->getFont()->setBold(true);
					$sheet->getCellByColumnAndRow($col++, $currentRow)->setValueExplicit($_classe)->getStyle()->getFont()->setBold(true);;


					foreach ($months_list as  $month_key => $month) {

						if ($month  == 0) {

							foreach ($services->annules as $service) {

								$total = Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($ins, $month_key, $service);
								$en = Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($ins, $month_key, $service);

								$sheet->getCellByColumnAndRow($col++, $currentRow)->setValueExplicit($total);
								$sheet->getCellByColumnAndRow($col++, $currentRow)->setValueExplicit($en);
								$sheet->getCellByColumnAndRow($col++, $currentRow)->setValueExplicit($total - $en);
							}
						} else {

							foreach ($services->mensuels as $service) {

								$total = Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($ins, $month_key, $service);
								$en = Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($ins, $month_key, $service);

								$sheet->getCellByColumnAndRow($col++, $currentRow)->setValueExplicit($total);
								$sheet->getCellByColumnAndRow($col++, $currentRow)->setValueExplicit($en);
								$sheet->getCellByColumnAndRow($col++, $currentRow)->setValueExplicit($total - $en);
							}
						}
					}
				}
			} else {
				// First
				$col = 0;
				$startRow = 1;

				$sheet->getCellByColumnAndRow($col++, $startRow)->setValueExplicit('Nom Complet')->getStyle()->getFont()->setBold(true);;
				$sheet->getCellByColumnAndRow($col++, $startRow)->setValueExplicit('Classe')->getStyle()->getFont()->setBold(true);

				foreach ($months_list as $key => $month) {
					$sheet->getCellByColumnAndRow($col, $startRow)->setValueExplicit($month)->getStyle()->getFont()->setBold(true);;
					$col += 3;
				}

				// Second
				$col = 2;
				$startRow++;
				foreach ($months_list as $key => $month) {
					$sheet->getCellByColumnAndRow($col++, $startRow)->setValueExplicit('Total')->getStyle()->getFont()->setBold(true);
					$sheet->getCellByColumnAndRow($col++, $startRow)->setValueExplicit('Encaisse')->getStyle()->getFont()->setBold(true);
					$sheet->getCellByColumnAndRow($col++, $startRow)->setValueExplicit('Reste')->getStyle()->getFont()->setBold(true);;
				}

				$startRow++;
				$i = 0;
				foreach ($inscriptions as  $ins) {
					$currentRow = $startRow + ($i++);
					$col = 0;
					$_classe =  $ins->get('Classe') ? $ins->get('Classe')->get('Label') : '---';

					$sheet->getCellByColumnAndRow($col++, $currentRow)->setValueExplicit($ins->Eleve->User->getNomComplet())->getStyle()->getFont()->setBold(true);
					$sheet->getCellByColumnAndRow($col++, $currentRow)->setValueExplicit($_classe)->getStyle()->getFont()->setBold(true);;

					foreach ($months_list as  $month_key => $month) {
						$total = Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($ins, $month_key);
						$en = Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($ins, $month_key);

						$sheet->getCellByColumnAndRow($col++, $currentRow)->setValueExplicit($total);
						$sheet->getCellByColumnAndRow($col++, $currentRow)->setValueExplicit($en);
						$sheet->getCellByColumnAndRow($col++, $currentRow)->setValueExplicit($total - $en);
					}
				}
			}

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			exit(0);
		}



		loadView('encaissements/etat-paiements-mois', array(
			'promotion' => $promotion,
			'promotions' => $promotions,
			'months_list' => $months_list,
			'classes' => $classes,
			'classe' => $classe,
			'niveaux' => $niveaux,
			'niveau' => $niveau,
			'by_service' => $by_service,
			'services' => $services,
			'inscriptions' => $inscriptions,
		));
	}

	function etat_impayes_parents()
	{

		set_time_limit(0);
		$promotions = Models\Promotion::getList();
		$promotion = Models\Promotion::actuelle();
		$months_list = _zero_months_list;
		$month   = !isset($_GET['month']) ? date('n') : null;
		$niveau = null;
		$service =  null;
		$select_pre_month = null;
		$services = (object)array(
			'annules' => Models\FIN\EncaissementRubrique::annulesService(),
			'mensuels' => Models\FIN\EncaissementRubrique::mensuelServices(),
		);


		$niveaux = Models\Niveau::getList();

		// if (!isset($_GET['niveau'])) {
		// 	$niveau = $niveaux[0];
		// }

		if (isset($_GET['service']) && $_GET['service'] != 'all') {
			$service =  new  Models\FIN\EncaissementRubrique($_GET['service']);
		}

		if (isset($_GET['niveau']) && $_GET['niveau'] != 'all') {
			$niveau =  new  Models\Niveau($_GET['niveau']);
		}

		if (isset($_GET['promotion'])) {
			$promotion = new Models\Promotion($_GET['promotion']);
		}

		if (isset($_GET['month']) && $_GET['month'] != 'all') {
			$month = $_GET['month'];
		}

		if (isset($_GET['select_pre_month']) && $_GET['select_pre_month']) {
			$select_pre_month = true;
		}

		$encaissments_parents  = array();
		$inscriptions = Models\FIN\EncaissementRubrique::unpaidInscriptions($promotion, $service, $month, $niveau, $select_pre_month)->get();

		foreach ($inscriptions as $inscription) {

			try {
				$_parents = $inscription->get('Eleve')->parents();
				$parent =  array_shift($_parents);
				if ($parent) {
					if (!isset($encaissments_parents[$parent->ID])) {
						$encaissments_parents[$parent->ID] = array(
							'parent' => $parent,
							'montant' => 0,
						);
					}

					$total_a_payer = 0;

					if ($month && $service) {

						if ($select_pre_month && $service->get('Mensuel')) {

							$monthService = $inscription->monthsOfService($service);

							foreach ($monthService as $month_key) {
								$a_p = Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($inscription, $month_key, $service);
								$paye = Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($inscription, $month_key, $service);
								if (($a_p > $paye)) {
									$total_a_payer += ($a_p - $paye);
								}
								if ($month == $month_key)
									break;
							}
						} else {
							$a_p = Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($inscription, $month, $service);
							$paye =  Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($inscription, $month, $service);
							$total_a_payer += ($a_p - $paye);
						}
					} else if ($month && !$service) {

						if ($select_pre_month) {

							foreach ($months_list as $month_key => $month_label) {
								$a_p = Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($inscription, $month_key, $service);
								$paye =  Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($inscription, $month_key);
								if (($a_p > $paye)) {
									$total_a_payer += ($a_p - $paye);;
								}

								if ($month == $month_key) break;
							}
						} else {

							$a_p =  Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($inscription, $month, $service);
							$paye =  Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($inscription, $month, $service);
							$total_a_payer += ($a_p - $paye);
						}
					} else if (!$month && $service) {

						if (!$service->get('Mensuel')) {

							$a_p = Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($inscription, null, $service);
							$paye =  Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($inscription, null, $service);
							$total_a_payer += ($a_p - $paye);
						} else {
							$monthService = $inscription->monthsOfService($service);
							foreach ($monthService as $month_key) {

								$a_p =  Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($inscription, $month_key);
								$paye =  Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($inscription, $month_key);
								if (($a_p > $paye)) {
									continue;
								}
								$total_a_payer += ($a_p - $paye);;
							}
						}
					} else {

						foreach (Models\FIN\EncaissementRubriqueInscription::etatEncaisementServices($inscription) as $month_key => $m) {

							$paye =  Models\FIN\EncaissementRubriqueInscription::sumAmountOfRubriques($inscription, $month_key);
							$a_p  = Models\FIN\EncaissementRubriqueInscription::defaultAmountOfRubriques($inscription, $month_key);

							if (!($a_p > $paye)) {
								continue;
							}

							if ($a_p > 0) {
								$percentage  = (int) ($paye * 100 / $a_p);
							} else {
								$percentage  = 0;
							}

							if ($percentage >= 100) {
								continue;
							}
							$total_a_payer += ($a_p - $paye);
						}
					}

					if ($total_a_payer) {
						$encaissments_parents[$parent->ID]['montant'] += $total_a_payer;
					} else {
						unset($encaissments_parents[$parent->ID]);
					}
				}
			} catch (Exception $e) {
				continue;
			}
		}

		if (isset($_GET['export'])) {

			ini_set('memory_limit', '-1');
			set_time_limit(20);
			loadLib('tcpdf');
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('BELHARRADI JAMAL');
			$pdf->SetTitle('Etat impayer');
			$pdf->SetSubject('Etat impayer');
			$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

			// remove default header/footer
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);

			// set default monospaced font
			// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// set margins
			// $pdf->SetMargins(19, 70, 19, 0);

			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			// ---------------------------------------------------------
			$pdf->AddPage('P', 'A4');

			$html = getView('encaissements/etat-impayes-famille-print', array(
				'promotion' => $promotion,
				'promotions' => $promotions,

				'months_list' => $months_list,
				'month' => $month,

				'services' => $services,
				'service' => $service,

				'niveaux' => $niveaux,
				'niveau' => $niveau,
				'encaissments_parents' => $encaissments_parents,
				'select_pre_month' => $select_pre_month
			), 'null_layout');


			ob_start();
			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->lastPage();
			ob_clean();

			$pdf->Output('etat_impayer.pdf', 'I');
		}

		loadView('encaissements/etat-impayes-famille', array(
			'promotion' => $promotion,
			'promotions' => $promotions,

			'months_list' => $months_list,
			'month' => $month,

			'services' => $services,
			'service' => $service,

			'niveaux' => $niveaux,
			'niveau' => $niveau,
			'encaissments_parents' => $encaissments_parents,
			'select_pre_month' => $select_pre_month
		));
	}

	function recu($encaissement)
	{

		try {
			$encaissement = new Models\FIN\Encaissement($encaissement);
		} catch (Exception $th) {
			exit('Element introvable');
		}
		ini_set('memory_limit', '-1');
		set_time_limit(20);
		loadLib('tcpdf');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


		if (_school_alias == 'lesentier') {
			$fontArabicBold = TCPDF_FONTS::addTTFfont(_basepath . 'assets/fonts/ADOBEARABIC-BOLD.OTF', '', '', 96);
			$pdf->SetFont($fontArabicBold, '', 12, '', false);
		}


		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Nicola Asuni');
		$pdf->SetTitle('Recu');
		$pdf->SetSubject('TCPDF Tutorial');
		$pdf->setFont('dejavusans');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		// $pdf->SetMargins(19, 70, 19, 0);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// ---------------------------------------------------------
		$pdf->AddPage('P', 'A4');
		$months_list = _zero_months_list;
		$lignesShowed = array();
		$lignes = $encaissement->encaissementLignes();

		$etablissement  =  Models\Partenaire::query()->first();

		if (in_array($encaissement->PaiementMode, ['cheque', 'virement'])) {
			if ($encaissement->CompteBancaire && is_object($encaissement->CompteBancaire)) {
				$etablissement = $encaissement->CompteBancaire->Etablissement;
			}
		}

		foreach ($lignes as $ligne) {
			$service =  $ligne->get('EncaissementRubrique');
			$inscription =  $ligne->get('Inscription');
			$key =  $ligne->get('Mois');

			if (!isset($lignesShowed[$key])) {
				$lignesShowed[$key] = array(
					'mois_key' => $ligne->get('Mois'),
					'mois' => $months_list[$ligne->get('Mois')],
					'inscriptions' => array(),
				);
			}

			if (!isset($lignesShowed[$key]['inscriptions'][$inscription->ID])) {
				$lignesShowed[$key]['inscriptions'][$inscription->ID] = array(
					'inscription' => $inscription,
					'services' => array(),
				);
			}

			$lignesShowed[$key]['inscriptions'][$inscription->ID]['services'][] = array(
				'service' => $service,
				'montant' => $ligne->get('Montant')
			);
		}

		$temp_path = _basepath . Config::get('admin') . '/pages/encaissements/impression/schools/' . Config::getSchool() . '/encaissements-recu-famille.php';

		if (file_exists($temp_path)) {
			$view = 'encaissements/impression/schools/' . Config::getSchool() . '/encaissements-recu-famille';
		} else {
			$view = 'encaissements/encaissements-famille-recu';
		}

		$html = getView($view, array(
			'encaissement' => $encaissement,
			'etablissement' => $etablissement,
			'lignesShowed' =>	$lignesShowed,
			'lignes' => $lignes,
		), 'null_layout');

		ob_start();
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();
		ob_clean();
		$pdf->Output($encaissement->get('NumRecu') . '.pdf', 'I');
	}


	function legacy()
	{
		loadView('encaissements/encaissements-legacy', array(
			'navKey' => 'encaissements',
		), '_layout');
	}
}


/* Router options */
$action = Request::getArgs(0) ? Request::getArgs(0) : 'index';
$args = array_slice(Request::getArgs(), 1);
#call the proper action
try {
	if (!method_exists('ContentController', $action))
		$action = 'index';
	$controller = new ContentController;
	$controller->{$action}(...$args);
} catch (Exception $e) {
	echo $e->getMessage();
	exit;
}
