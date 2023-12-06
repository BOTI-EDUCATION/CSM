<?php

use Models\Classe;
use Models\ETD\Edt;
use Models\Inscription;

// ControllerRole('admin', 'agent', 'surveillant', 'agent_plus', 'resp_pedago', 'direction');

loadLib('tcpdf');
class CUSTOMTCPDF extends TCPDF
{
	// Page footer
	public function Footer()
	{
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font

		// Page number
		$this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

Session::getInstance()->requireLogin(true);

// POSTs
if (Request::isPost()) {
	switch ($_POST['op']) {
		case 'filter':
			if ($_POST['promotion'])
				$promotion = new Models\Promotion($_POST['promotion']);
			else
				$promotion = Models\Promotion::promotion_actuelle();

			$site =  null;
			if (isset($_POST['site']) && $_POST['site']) {
				$site  = new Models\Site($_POST['site']);
			}


			$where = array('Promotion' => $promotion->getPk(true));
			$where[] = "Parascolaire IS NOT TRUE";

			if ($site) {
				$where['Site']  = $site->getPk(true);
			}

			$classes = Models\Classe::getList(
				array('where' => $where, 'order' => array('J1Ordre' => true)),
				Models\Classe::sqlQuery() . <<<END
			 JOIN (SELECT `ID` AS `J1ID`, `Ordre` AS `J1Ordre` FROM `gen_niveaux`) AS `j1` ON `ins_classes`.`Niveau` = `j1`.`J1ID`
END
			);


			$promotions 	= Models\Promotion::order(array('Annee' => false))->get();
			$sites 	= Models\Site::getList();
			loadView('classes/classes-list', array(
				'classes' 	=> $classes,
				'promotion' => $promotion,
				'sites' => $sites,
				'site' => $site,
				'promotions' => $promotions,
				'navKey' 	=> 'classes',
				'css' => ['css/pages/classes/classe.css']

			));

		case 'classes':
			$pk = (isset($_POST['id']) && $_POST['id']) ? $_POST['id'] : null;

			try {
				$classe = new Models\Classe($pk);
			} catch (Exception $e) {
				exit('Element introuvable');
			}


			$classe
				->set('Label', $_POST['label'])
				->set('Site', $_POST['site'])
				->set('Niveau', $_POST['niveau'])
				->set('Groupe', $_POST['groupe'])
				->set('FraisInscription', $_POST['fraisinscription'])
				->set('FraisScolarite', $_POST['fraisscolarite'])
				->set('Promotion', $_POST['promotion']);

			$inscriptions = $classe->getInscriptions();
			foreach ($inscriptions  as $item) {
				$item->set('Niveau', $_POST['niveau'])
					->set('Site', $_POST['site']);
				$item->save();
			}

			$classe->save();


			$user = Session::user();
			if ($user->Classes) {
				$user->set('Classes', $user->Classes . "," . $classe->getKey())->save();
				$user->save();
			}

			Models\ActionLog::catchLog(($pk ? 'Modification' : ' Creation') . " de classe =" . $classe->Label);

			URL::redirect(URL::admin('classes/' . $classe->getPK(true)));
		case 'responsable':

			try {
				$classe = new Models\Classe($_POST['classe']);
			} catch (Exception $e) {
				$classe = null;
			}

			try {
				$responsable = new Models\Eleve($_POST['responsable']);
			} catch (Exception $e) {
				$responsable = null;
			}

			if ($responsable) {
				$ResponsableHistory = $classe->get('ResponsableHistory') . ';' . $responsable->getPK(true) . ',' . date('Y-m-d H:i:s') . ',' . Session::getInstance()->getCurUser()->getPK(true);
				$classe
					->set('Responsable', $responsable)
					->set('ResponsableHistory', $ResponsableHistory)
					->save();

				// Générer un POST aux parents de l'élève en Question.
				try {
					$post = new Models\Post();
				} catch (Exception $e) {
					exit('Erreur : Création conteu');
				}

				$post->set('User',  Session::getInstance()->getCurUser());

				$sujet = $responsable->get('User')->getNomComplet() . ' est nommé responsable de classe';
				$contenu = 'Votre enfant ' . $responsable->get('User')->getNomComplet() . ' a été nommé responsable de classe pour cette semaine';

				$post
					->set('PostFormat', 1)
					->set('PostCategorie', 3)
					->set('Label', $sujet)
					->set('Date', date('Y-m-d H:i:s'))
					->set('DatePublication', date('Y-m-d H:i:s'))
					->set('Date', date('Y-m-d H:i:s'))
					->set('Visible', true)
					->set('Content', $contenu)
					->set('Home', false)
					->set('Public', false)
					->set('Parents', false);


				$alias = Tools::getAlias($post->get('Alias'));
				if (!$alias)
					$alias = Tools::getAlias($post->get('Label'));
				$post->set('Alias', $alias);

				$post->save();

				$postEleve = new Models\PostEleve();
				$postEleve
					->set('Post', $post)
					->set('Eleve', $responsable)
					->save();

				$tokensAndroid = array();
				$tokensIos = array();
				$notifLabel = $sujet . ' | envoyé à : ';
				$parrainages = Models\Parrainage::getList(array('where' => array('Eleve' => $responsable->get('ID'))));
				foreach ($parrainages as $item) {

					if (!$item->get('Parent')->get('User')->get('TokenID'))
						continue;

					$notifLabel .= ' - ' . $item->get('Parent')->get('User')->getNomComplet();

					if ($item->get('Parent')->get('User')->get('TokenDevice') == 'ios')
						$tokensIos[] = $item->get('Parent')->get('User')->get('TokenID');
					else
						$tokensAndroid[] = $item->get('Parent')->get('User')->get('TokenID');
				}

				if ($tokensAndroid) {
					$messageAndroid =  array(
						"title" => 'Notification',
						'body' 	=> $contenu,
						// "content-available"=> '0',
						'icon'	=> 'myicon',
						'sound' => 'mySound',
					);
					$message_status = send_notification_firebase($tokensAndroid, $messageAndroid, 'android');
				}

				if ($tokensIos) {
					$messageIos =  array(
						"title" => 'Notification',
						'body' 	=> $contenu,
						'icon'	=> 'myicon',
						'sound' => 'mySound',
					);

					$message_status = send_notification_firebase($tokensIos, $messageIos, 'ios');
				}

				$notif = new Models\Notif();
				$notif
					->set('Label', $notifLabel)
					->set('Message', $contenu)
					->set('Date', date('Y-m-d H:i:s'));

				$notif->save();
			} else {

				$classe
					->set('Responsable', null)
					->save();
			}


			URL::redirect(URL::admin('classes'));

		case 'send-notif':

			try {
				$classe = new Models\Classe($_POST['classe']);
			} catch (Exception $e) {
				$classe = null;
			}

			$tokensAndroid = array();
			$tokensIos = array();
			$parrainages = Models\Parrainage::getList(
				array('where' => array('J2Classe' => $classe->get('ID'))),
				Models\Parrainage::sqlQuery() . <<<END
	JOIN (SELECT `ID` AS `J1ID` FROM `gen_eleves`) AS `j1` ON `parrainages`.`Eleve` = `j1`.`J1ID`
	JOIN (SELECT `ID` AS `J2ID`, `Eleve` AS `J2Eleve`, `Classe` AS `J2Classe` FROM `ins_inscriptions`) AS `j2` ON `j1`.`J1ID` = `j2`.`J2Eleve`
END
			);
			$notifLabel = $_POST['titre'] . ' | envoyé à : ' . $classe->get('Label');
			foreach ($parrainages as $item) {

				if (!$item->get('Parent')->get('User')->get('TokenID'))
					continue;

				if ($item->get('Parent')->get('User')->get('TokenDevice') == 'ios')
					$tokensIos[] = $item->get('Parent')->get('User')->get('TokenID');
				else
					$tokensAndroid[] = $item->get('Parent')->get('User')->get('TokenID');
			}

			if ($tokensAndroid) {
				$messageAndroid =  array(
					"title" => $_POST['titre'],
					'body' 	=> $_POST['message'],
					// "content-available"=> '0',
					'icon'	=> 'myicon',
					'sound' => 'mySound',
				);
				$message_status = send_notification_firebase($tokensAndroid, $messageAndroid, 'android');
			}

			if ($tokensIos) {
				$messageIos =  array(
					"title" => $_POST['titre'],
					'body' 	=> $_POST['message'],
					'icon'	=> 'myicon',
					'sound' => 'mySound',
				);

				$message_status = send_notification_firebase($tokensIos, $messageIos, 'ios');
			}

			$notif = new Models\Notif();
			$notif
				->set('Label', $notifLabel)
				->set('Message', $_POST['message'])
				->set('Date', date('Y-m-d H:i:s'));

			$notif->save();

			URL::redirect(URL::admin('classes/' . $classe->getPK(true)));


		case 'import-eleves':

			// header('Content-Type: text/plain; charset=utf8');
			try {
				$promotion = new Models\Promotion($_POST['promotion']);
			} catch (Exception $e) {
				exit('Promotion introuvable');
			}
			set_time_limit(0);
			loadLib('phpexcelsheet');
			//Check valid eleves has been uploaded

			$withParent = isset($_POST['with-parent']);
			if (isset($_FILES['eleves'])) {
				if ($_FILES['eleves']['tmp_name']) {
					if (!$_FILES['eleves']['error']) {

						$inputFile = $_FILES['eleves']['name'];
						$tmp_inputFile = $_FILES['eleves']['tmp_name'];
						$extension = strtoupper(pathinfo($inputFile, PATHINFO_EXTENSION));

						if ($extension == 'XLSX' || $extension == 'ODS') {

							try {
								$inputFileType = PHPExcel_IOFactory::identify($tmp_inputFile);
								$objReader = PHPExcel_IOFactory::createReader($inputFileType);
								$objPHPExcel = $objReader->load($tmp_inputFile);
							} catch (Exception $e) {
								die($e->getMessage());
							}

							$sheet = $objPHPExcel->getSheet(0);
							$highestRow = $sheet->getHighestRow();
							$highestColumn = $sheet->getHighestColumn();
							$countImported = 0;
							DB::begin();
							for ($row = 1; $row <= $highestRow; $row++) {
								$tel = '';
								if ($row == 1)
									continue;

								if ($withParent && $row == 2)
									continue;

								$rowsData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, TRUE);
								$rowData = $rowsData[0];



								if (!$rowData[0] && !$rowData[1] && !$rowData[2] && !$rowData[3])
									continue;

								$dateNaissance = null;

								if ($rowData[4]) {


									$strdate = $rowData[4];
									if (false) {
										// print_r($strdate);
										$strdate = str_replace('D', '', $strdate);
										// print_r($strdate);
										$strdate = str_replace('/', '-', $strdate);
										// print_r($strdate);
									}

									//code Taha
									// $date = explode('/', $strdate);
									// if (count($date) == 3) {

									// 	$dateNaissance = date('Y-m-d', strtotime($date[2] . '-' . $date[0] . '-' . $date[1]));
									// } else {
									// 	$dateNaissance = null;
									// }

									$dateNaissance = $strdate;
									$dateNaissance = str_replace('D', '', $dateNaissance);
									$dateNaissance = str_replace('/', '-', $dateNaissance);
									$dateNaissance = date('Y-m-d', strtotime($dateNaissance));
								}



								$countImported++;

								/*
								try {
									$niveau = new Models\Niveau($rowData[5]);
								} catch (Exception $e) {
									$niveau = null;
								}
							
								$classe = null;
								if (isset($rowData[17]) && $rowData[17]) {

									$colGroupe = $rowData[17];
									$LevelClasses = Models\Classe::getList((array('where' => array('Groupe' => $colGroupe, 'Niveau' => $niveau->get('ID'), 'Promotion' =>  $promotion->get('ID')))));

									if ($LevelClasses) {
										$classe = $LevelClasses[0];
									}
								}*/

								$niveau = null;
								$classe = null;
								$classes = array();

								if (isset($rowData[5]) && $rowData[5]) {
									$niveau_group = explode(',', $rowData[5]);

									$code_niveau 	=  isset($niveau_group[0]) ? $niveau_group[0] : 'tps';
									$code_group 	=  isset($niveau_group[1]) ? $niveau_group[1] : 1;

									$niveau 	= Models\Niveau::getByCode($code_niveau);
									$group 		= new  Models\Groupe($code_group);

									if ($niveau) {
										$classes =  Models\Classe::all(array('where' =>
										array(
											'Promotion' => $promotion,
											'Niveau' => $niveau->ID,
											'Groupe' => $group->ID
										)));
									}

									if (count($classes)) {
										$classe = $classes[0];
									}
								}

								if (!$niveau) {
									$niveau = Models\Niveau::getByCode('tps');
									$group = new Models\Groupe(1);
								}



								$dateIns = null;
								if ($rowData[18]) {
									$strdate = $rowData[18];
									$strdate = str_replace('D', '', $strdate);
									$strdate = str_replace('/', '-', $strdate);
									$dateIns = date('Y-m-d', strtotime($strdate));
								}

								if ($rowData[19]) {
									try {
										$classe = new Models\Classe($rowData[19]);
									} catch (Exception $e) {
									}
								}


								// dd($classe);
								if ($classe && $classe->Promotion->getPk(true) != $promotion->getPk(true)) {
									echo 'Promotion of the classe not equal to selected promotion';
									exit(0);
								}


								$inscription = new Models\Inscription();
								$inscription
									->set('Eleve', $eleve)
									->set('Promotion', $promotion)
									->set('Niveau', $classe ? $classe->Niveau : $niveau)
									->set('Classe', $classe ?: null)
									->set('InscriptionBy', Session::getInstance()->getCurUser()->getNomComplet())
									->set('DateInscripiton', $dateIns ?: date('Y-m-d h:i:s'))
									->set('Commentaire',  "Import en masse d'élèves")
									->setJson('Validated', [
										'user' => Session::user()->getKey(),
										'date' => date('Y-m-d H:i:s'),
									]);
								$inscription->save();
								$inscription->applyRequiredServices();


								if ($withParent) {

									if ($rowData[6] || $rowData[7] || $rowData[8] || $rowData[9]) {

										if ($rowData[9] || $rowData[8]) {


											$rowData[8] = str_replace("-", "", $rowData[8]);
											$rowData[8] = str_replace(".", "", $rowData[8]);
											$tel = format_phone($rowData[8]);
											$userPapa = new Models\User();
											$emailExists = $userPapa->emailExists($rowData[9]);
											$telExists = $userPapa->telExists($tel);

											if ($emailExists) {
												$userPapa = new Models\User($emailExists);
												$papa = $userPapa->getParent();
												if (!$papa) {
													$papa = new Models\Parentt();
													$papa
														->set('User', $userPapa)
														->save();
												}
											} elseif ($telExists) {
												$userPapa = new Models\User($telExists);
												$papa = $userPapa->getParent();
												if (!$papa) {
													$papa = new Models\Parentt();
													$papa
														->set('User', $userPapa)
														->save();
												}
											} else {

												$papa = new Models\Parentt();
												$userPapa = new Models\User();
												$userPapa
													->set('Key', \Tools::getRandChars(30, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'))
													->set('Role', 2)
													->set('Homme', 1)
													->set('Adresse', $rowData[3])
													->set('Nom', strtoupper($rowData[6]))
													->set('Prenom', ucfirst(strtolower($rowData[7])))
													->set('Tel', $tel)
													->set('Email', $rowData[9])
													->set('Date', date('Y-m-d H:i:s'))
													->set('Enabled', 1)
													->set('CIN', isset($rowData[21]) ? $rowData[21] : null) //add cin for papa 
													->set('Fonction', isset($rowData[22]) ? $rowData[22] : null) //add profession for papa
													->save();
												$papa
													->set('User', $userPapa)
													->save();
											}
										} else {

											$papa = new Models\Parentt();
											$userPapa = new Models\User();
											$userPapa
												->set('Key', \Tools::getRandChars(30, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'))
												->set('Role', 2)
												->set('Homme', 1)
												->set('Adresse', $rowData[3])
												->set('Nom', strtoupper($rowData[6]))
												->set('Prenom', ucfirst(strtolower($rowData[7])))
												->set('Tel', $tel)
												->set('Email', $rowData[9])
												->set('Date', date('Y-m-d H:i:s'))
												->set('Enabled', 1)
												->set('CIN', isset($rowData[21]) ? $rowData[21] : null) //add cin for papa 
												->set('Fonction', isset($rowData[22]) ?  $rowData[22] : null) //add profession for papa
												->save();
											$papa
												->set('User', $userPapa)
												->save();
										}

										$parrainage = new Models\Parrainage();
										$parrainage
											->set('Eleve', $eleve)
											->set('Type', 1)
											->set('Parent', $papa)
											->save();
									}


									$duplicateEmail = false;
									$duplicateTel = false;

									if ($rowData[10] || $rowData[11] || $rowData[12] || $rowData[13]) {

										if ($rowData[13] || $rowData[12]) {

											$rowData[12] = str_replace("-", "", $rowData[12]);
											$rowData[12] = str_replace(".", "", $rowData[12]);
											$tel = format_phone($rowData[12]);
											if (($rowData[9] && $rowData[9] == $rowData[13]))
												$duplicateAccount = true;

											if (($rowData[8] && $rowData[8] == $rowData[12]))
												$duplicateTel = true;


											$userMama = new Models\User();
											$emailExists = $userMama->emailExists($rowData[13]);

											$telExists = $userMama->telExists($tel);

											if ($duplicateTel && $duplicateEmail) {
												// Duplicate Account :)
											} elseif ($emailExists && !$duplicateEmail) {
												$userMama = new Models\User($emailExists);
												$mama = $userMama->getParent();
												if (!$mama) {
													$mama = new Models\Parentt();
													$mama
														->set('User', $userMama)
														->save();
												}
											} elseif ($telExists && !$duplicateTel) {
												$userMama = new Models\User($telExists);
												$mama = $userMama->getParent();
												if (!$mama) {
													$mama = new Models\Parentt();
													$mama
														->set('User', $userMama)
														->save();
												}
											} else {

												$mama = new Models\Parentt();
												$userMama = new Models\User();
												$userMama
													->set('Key', \Tools::getRandChars(30, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'))
													->set('Role', 2)
													->set('Homme', 0)
													->set('Adresse', $rowData[3])
													->set('Nom', strtoupper($rowData[10]))
													->set('Prenom', ucfirst(strtolower($rowData[11])))
													->set('Tel', $tel)
													->set('Email', $rowData[13])
													->set('Date', date('Y-m-d H:i:s'))
													->set('Enabled', 1)
													->set('CIN', isset($rowData[23]) ? $rowData[23] : null) //add cin for maman
													->set('Fonction', isset($rowData[24]) ? $rowData[24] : null) //add profession for maman
													->save();
												$mama
													->set('User', $userMama)
													->save();
											}
										} else {

											$mama = new Models\Parentt();
											$userMama = new Models\User();
											$userMama
												->set('Key', \Tools::getRandChars(30, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'))
												->set('Role', 2)
												->set('Homme', 0)
												->set('Adresse', $rowData[3])
												->set('Nom', strtoupper($rowData[10]))
												->set('Prenom', ucfirst(strtolower($rowData[11])))
												->set('Tel', $tel)
												->set('Email', $rowData[13])
												->set('Date', date('Y-m-d H:i:s'))
												->set('Enabled', 1)
												->set('CIN', isset($rowData[23]) ? $rowData[23] : null) //add cin for maman
												->set('Fonction', isset($rowData[24]) ?  $rowData[24] : null) //add profession for maman
												->save();
											$mama
												->set('User', $userMama)
												->save();
										}

										if (!$duplicateTel && !$duplicateEmail) {

											try {
												$parrainage = new Models\Parrainage();
												$parrainage
													->set('Eleve', $eleve)
													->set('Type', 2)
													->set('Parent', $mama)
													->save();
											} catch (Exception $e) {
											}
										}
									}
								}

								//*/

							}
							DB::commit();
							//exit;

							Models\ActionLog::catchLog("Import de " . $countImported . "Elèves ");
						} else {
							echo "Please upload an XLSX or ODS file";
						}
					} else {
						echo $_FILES['eleves']['error'];
					}
				}
			}

			URL::redirect(URL::admin('classes/generate'));
		case 'import-eleves_v_1':

			// header('Content-Type: text/plain; charset=utf8');
			try {
				$promotion = new Models\Promotion($_POST['promotion']);
			} catch (Exception $e) {
				exit('Promotion introuvable');
			}

			loadLib('phpexcelsheet');
			//Check valid eleves has been uploaded

			$withParent = isset($_POST['with-parent']);
			if (isset($_FILES['eleves'])) {
				if ($_FILES['eleves']['tmp_name']) {
					if (!$_FILES['eleves']['error']) {

						$inputFile = $_FILES['eleves']['name'];
						$tmp_inputFile = $_FILES['eleves']['tmp_name'];
						$extension = strtoupper(pathinfo($inputFile, PATHINFO_EXTENSION));

						if ($extension == 'XLSX' || $extension == 'ODS') {

							try {
								$inputFileType = PHPExcel_IOFactory::identify($tmp_inputFile);
								$objReader = PHPExcel_IOFactory::createReader($inputFileType);
								$objPHPExcel = $objReader->load($tmp_inputFile);
							} catch (Exception $e) {
								die($e->getMessage());
							}

							$sheet = $objPHPExcel->getSheet(0);
							$highestRow = $sheet->getHighestRow();
							$highestColumn = $sheet->getHighestColumn();

							DB::begin();
							for ($row = 1; $row <= $highestRow; $row++) {

								if ($row == 1)
									continue;

								if ($withParent && $row == 2)
									continue;

								$rowsData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
								$rowData = $rowsData[0];

								if (!$rowData[0] && !$rowData[1] && !$rowData[2] && !$rowData[3])
									continue;

								// print_r($rowData);

								//*

								$dateNaissance = null;
								if ($rowData[4]) {
									$strdate = $rowData[4];
									$strdate = str_replace('D', '', $strdate);
									$strdate = str_replace('/', '-', $strdate);
									$dateNaissance = date('Y-m-d', strtotime($strdate));
								}

								$utilisateur = new Models\User();
								$utilisateur
									->set('Key', \Tools::getRandChars(30, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'))
									->set('Role', 3)
									->set('Homme', (isset($rowData[0]) && $rowData[0] == 'M') ? true : false)
									->set('Nom', strtoupper($rowData[1]))
									->set('Prenom', ucfirst(strtolower($rowData[2])))
									->set('DateNaissance', $dateNaissance)
									->set('Adresse', $rowData[3])
									->set('Enabled', true);

								$utilisateur->save();

								$eleve = new Models\Eleve();
								$eleve
									->set('User', $utilisateur)
									->set('PrenomAr', ((isset($rowData[15]) && $rowData[15] !== '')) ? $rowData[15] : null)
									->set('Massar', ((isset($rowData[16]) && $rowData[16] !== '')) ? $rowData[16] : null)
									->set('NomAr', ((isset($rowData[14]) && $rowData[14] !== '')) ? $rowData[14] : null)
									->set('Matricule', ((isset($rowData[17]) && $rowData[17] !== '')) ? $rowData[17] : null);

								$eleve->save();



								/*
								try {
									$niveau = new Models\Niveau($rowData[5]);
								} catch (Exception $e) {
									$niveau = null;
								}

								$classe = null;
								if (isset($rowData[17]) && $rowData[17]) {

									$colGroupe = $rowData[17];
									$LevelClasses = Models\Classe::getList((array('where' => array('Groupe' => $colGroupe, 'Niveau' => $niveau->get('ID'), 'Promotion' =>  $promotion->get('ID')))));

									if ($LevelClasses) {
										$classe = $LevelClasses[0];
									}
								}*/


								$dateIns = null;
								if ($rowData[18]) {
									$strdate = $rowData[18];
									$strdate = str_replace('D', '', $strdate);
									$strdate = str_replace('/', '-', $strdate);
									$dateIns = date('Y-m-d', strtotime($strdate));
								}




								$classe = null;
								if (isset($rowData[5]) && $rowData[5]) {
									$idclasse = $rowData[5];
									$classe = new Models\Classe($idclasse);
								}




								if ($classe) {
									$inscription = new Models\Inscription();
									$inscription
										->set('Eleve', $eleve)
										->set('Promotion', $promotion)
										->set('Niveau', $classe->get('Niveau'))
										->set('Classe', $classe)
										->set('InscriptionBy', Session::getInstance()->getCurUser()->getNomComplet())
										->set('DateInscripiton', $dateIns ?: date('Y-m-d h:i:s'))
										->set('Commentaire',  "Import eleve");
									$inscription->save();
									$inscription->applyRequiredServices();
								}

								if ($withParent) {

									if ($rowData[6] || $rowData[7] || $rowData[8] || $rowData[9]) {

										if ($rowData[9] || $rowData[8]) {


											$rowData[8] = str_replace("-", "", $rowData[8]);
											$rowData[8] = str_replace(".", "", $rowData[8]);

											$userPapa = new Models\User();
											$emailExists = $userPapa->emailExists($rowData[9]);
											$telExists = $userPapa->telExists($rowData[8]);

											if ($emailExists) {
												$userPapa = new Models\User($emailExists);
												$papa = $userPapa->getParent();
												if (!$papa) {
													$papa = new Models\Parentt();
													$papa
														->set('User', $userPapa)
														->save();
												}
											} elseif ($telExists) {
												$userPapa = new Models\User($telExists);
												$papa = $userPapa->getParent();
												if (!$papa) {
													$papa = new Models\Parentt();
													$papa
														->set('User', $userPapa)
														->save();
												}
											} else {

												$papa = new Models\Parentt();
												$userPapa = new Models\User();
												$userPapa
													->set('Key', \Tools::getRandChars(30, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'))
													->set('Role', 2)
													->set('Homme', true)
													->set('Adresse', $rowData[3])
													->set('Nom', strtoupper($rowData[6]))
													->set('Prenom', ucfirst(strtolower($rowData[7])))
													->set('Tel', preg_replace('/\s+/', '', $rowData[8]))
													->set('Email', $rowData[9])
													->set('Enabled', true)
													->save();
												$papa
													->set('User', $userPapa)
													->save();
											}
										} else {

											$papa = new Models\Parentt();
											$userPapa = new Models\User();
											$userPapa
												->set('Key', \Tools::getRandChars(30, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'))
												->set('Role', 2)
												->set('Homme', true)
												->set('Adresse', $rowData[3])
												->set('Nom', strtoupper($rowData[6]))
												->set('Prenom', ucfirst(strtolower($rowData[7])))
												->set('Tel', preg_replace('/\s+/', '', $rowData[8]))
												->set('Email', $rowData[9])
												->set('Enabled', true)
												->save();
											$papa
												->set('User', $userPapa)
												->save();
										}

										$parrainage = new Models\Parrainage();
										$parrainage
											->set('Eleve', $eleve)
											->set('Type', 1)
											->set('Parent', $papa)
											->save();
									}


									$duplicateEmail = false;
									$duplicateTel = false;

									if ($rowData[10] || $rowData[11] || $rowData[12] || $rowData[13]) {

										if ($rowData[13] || $rowData[12]) {

											$rowData[12] = str_replace("-", "", $rowData[12]);
											$rowData[12] = str_replace(".", "", $rowData[12]);

											if (($rowData[9] && $rowData[9] == $rowData[13]))
												$duplicateAccount = true;

											if (($rowData[8] && $rowData[8] == $rowData[12]))
												$duplicateTel = true;


											$userMama = new Models\User();
											$emailExists = $userMama->emailExists($rowData[13]);
											$telExists = $userMama->telExists($rowData[12]);

											if ($duplicateTel && $duplicateEmail) {
												// Duplicate Account :)
											} elseif ($emailExists && !$duplicateEmail) {
												$userMama = new Models\User($emailExists);
												$mama = $userMama->getParent();
												if (!$mama) {
													$mama = new Models\Parentt();
													$mama
														->set('User', $userMama)
														->save();
												}
											} elseif ($telExists && !$duplicateTel) {
												$userMama = new Models\User($telExists);
												$mama = $userMama->getParent();
												if (!$mama) {
													$mama = new Models\Parentt();
													$mama
														->set('User', $userMama)
														->save();
												}
											} else {

												$mama = new Models\Parentt();
												$userMama = new Models\User();
												$userMama
													->set('Key', \Tools::getRandChars(30, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'))
													->set('Role', 2)
													->set('Homme', false)
													->set('Adresse', $rowData[3])
													->set('Nom', strtoupper($rowData[10]))
													->set('Prenom', ucfirst(strtolower($rowData[11])))
													->set('Tel', preg_replace('/\s+/', '', $rowData[12]))
													->set('Email', $rowData[13])
													->set('Enabled', true)
													->save();
												$mama
													->set('User', $userMama)
													->save();
											}
										} else {

											$mama = new Models\Parentt();
											$userMama = new Models\User();
											$userMama
												->set('Key', \Tools::getRandChars(30, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'))
												->set('Role', 2)
												->set('Homme', false)
												->set('Adresse', $rowData[3])
												->set('Nom', strtoupper($rowData[10]))
												->set('Prenom', ucfirst(strtolower($rowData[11])))
												->set('Tel', preg_replace('/\s+/', '', $rowData[12]))
												->set('Email', $rowData[13])
												->set('Enabled', true)
												->save();
											$mama
												->set('User', $userMama)
												->save();
										}

										if (!$duplicateTel && !$duplicateEmail) {

											try {
												$parrainage = new Models\Parrainage();
												$parrainage
													->set('Eleve', $eleve)
													->set('Type', 2)
													->set('Parent', $mama)
													->save();
											} catch (Exception $e) {
											}
										}
									}
								}
								//*/


							}
							DB::commit();
							//exit;
						} else {
							echo "Please upload an XLSX or ODS file";
						}
					} else {
						echo $_FILES['eleves']['error'];
					}
				}
			}

			URL::redirect(URL::admin('classes/generate'));

		case 'affectation':

			$pk = (isset($_POST['id']) && $_POST['id']) ? $_POST['id'] : null;

			// dd($_POST);
			try {
				$classe = new Models\Classe($pk);
			} catch (Exception $e) {
				exit('Element introuvable');
			}

			if (!$classe)
				URL::redirect(URL::admin('classes/'));


			$allinscriptions = $classe->get('Niveau')->getInscriptions();

			$inscriptionsClasse = $classe->getInscriptions();

			// dd($inscriptionsClasse);
			/*
			if($allinscriptions) {
				foreach($allinscriptions as $item) {

					if ($inscriptionsClasse && array_key_exists($item->get('ID'), $inscriptionsClasse) && isset($_POST['inscriptions'])  && $_POST['inscriptions'] && !in_array($item->get('ID'), $_POST['inscriptions'])){

						// LOG désaffectation

						$item->set('Classe', null);

					}elseif(isset($_POST['inscriptions']) && $_POST['inscriptions'] && in_array($item->get('ID'), $_POST['inscriptions'])) {

						if(!$item->get('Classe')){

						// LOG affectation

						}

						$item->set('Classe', $classe);
					}

					$item->save();
				}

				if(!isset($_POST['inscriptions']) && $inscriptionsClasse){

					foreach($inscriptionsClasse as $item) {

						// LOG désaffectation

						$item->set('Classe', null)->save();

					}
				}
			}
			*/

			foreach ($inscriptionsClasse as $item) {
				$item->set('Classe', null);

				$item->save();
			}

			if (isset($_POST['inscriptions'])) {
				$i = 1;
				foreach ($_POST['inscriptions'] as $item) {
					try {
						$inscription = new Models\Inscription($item);
					} catch (Exception $e) {
						$inscription = null;
					}
					$inscription->set('Classe', $classe)->set('Ordre', $i)->save();
					$i++;
				}
			}

			Models\ActionLog::catchLog(
				"Modifier afftectaion des eleves de classe " . $classe->get("Label"),
				$classe
			);

			URL::redirect(URL::admin('classes/' . $classe->getPK(true) . '/affecter'));

		case 'generation-classe':

			$niveaux = Models\Niveau::getList();

			foreach ($niveaux as $item) {
				if (isset($_POST['niveau-' . $item->get('ID')]) && $_POST['niveau-' . $item->get('ID')] > 0) {
					$nombre = $_POST['niveau-' . $item->get('ID')];

					for ($i = 1; $i <= $nombre; $i++) {

						$promotion = Models\Promotion::promotion_actuelle();

						try {
							$groupe = new Models\Groupe($i);
						} catch (Exception $e) {
							$groupe = null;
						}

						$classe = new Models\Classe();
						$classe
							->set('Promotion', $promotion)
							->set('Groupe', $groupe)
							->set('Promotion', $_POST['promotion'])
							->set('Niveau', $item)
							->set('Site', $_POST['site'])
							->set('Label', $item->get('Label') . ' G' . $i)
							->save();;
					}
				}
			}

			URL::redirect(URL::admin('classes'));
		case 'ordre':
			$classe = new Models\Classe($_POST['classe']);
			$inscriptions = $classe->getInscriptions();
			DB::begin();
			foreach ((array)$_POST['ordre'] as $index => $ins_id) {
				$inscriptions[$ins_id]->set('Ordre', $index + 1)->save();
			}
			DB::commit();
			sendResponse(array('msg' => 'l\'ordre est bien enregistré'));
	}
}



// GETs
if (Request::getArgs(0) == 'import-eleves') {
	loadView('classes/classes-import-eleves', array(
		'isUpdate' => null,
		'navKey' => 'classes',
		'classe' => '',
	));
} elseif (Request::getArgs(0) == 'add') {
	loadView('classes/classes-form', array(
		'isUpdate' => null,
		'navKey' => 'classes',
		'classe' => '',
	));
} elseif (Request::getArgs(0) == 'nonaffectes') {

	$promotion =  Models\Promotion::promotion_actuelle();
	$inscriptions = Models\Inscription::query()->where([
		'Classe IS NULL',
		'Depart IS NULL',
		'Promotion' => $promotion->getKey()
	])->get();



	if (isset($_GET['export_excel'])) {

		header('Content-Type: text/plain; charset=utf8');
		loadLib('phpexcel');

		if (!PHPExcel_Settings::setLocale('fr_FR'))
			echo 'Locale not set ' . PHP_EOL;

		$objPHPExcel = new PHPExcel();

		// $objPHPExcel->getSheet(); // at index 0
		$objPHPExcel->getActiveSheet(); // Same same, by default active sheet is at 0

		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Jamal');



		$objPHPExcel->getActiveSheet()
			->setCellValue('A1', 'Matricule')
			->setCellValue('B1', 'Elève')
			->setCellValue('C1', 'Niveau')
			->setCellValue('D1', 'Nom & Prenom Père')
			->setCellValue('E1', 'Téléphone Père')
			->setCellValue('F1', 'Nom & Prénom Mère')
			->setCellValue('G1', 'Téléphone Mère');


		$i = 2;
		while ($i < count($inscriptions)) {
			try {
				$inscription = $inscriptions[$i];

				$eleve = $inscription->get('Eleve');
				$eleveParents = $eleve->parents();
				$eleveParents = $eleve->parents();
				$papa = isset($eleveParents[1]) ? $eleveParents[1] : null;
				$maman =  isset($eleveParents[2]) ? $eleveParents[2] : null;
				$tuteur = isset($eleveParents[3]) ? $eleveParents[3] : null;

				$objPHPExcel->getActiveSheet()
					->setCellValue('A' .  $i, $eleve->get('Matricule') ?: '---')
					->setCellValue('B' .  $i, $eleve->get('User')->getNomComplet())
					->setCellValue('C' .  $i, $inscription->get('Niveau')->get('Label'))
					->setCellValue('D' .  $i, $papa ? $papa->get('User')->getNomComplet() : '---')
					->setCellValue('E' .  $i, $papa ? $papa->get('User')->get('Tel') : '---')
					->setCellValue('F' .  $i, $maman ? $maman->get('User')->getNomComplet() : '---')
					->setCellValue('G' .  $i, $maman ? $maman->get('User')->get('Tel') : '---');


				$i++;
			} catch (\Exception $th) {
				continue;
			}
		}


		// Set the number format mask so that the excel timestamp will be displayed as a human-readable date/time
		$objPHPExcel->getActiveSheet()
			->getStyle('B' . 0 . ':B' . count($eleves))
			->getNumberFormat()
			->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME);

		foreach (range('A', 'U') as $col)
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);

		$fileName = 'insatallation-eleves-';
		// redirect output to client browser
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $fileName . date('Y-m-d H:i:s') . '.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		exit($objWriter->save('php://output'));
	}


	loadView('classes/classes-nonaffectes', array(
		'isUpdate' => null,
		'navKey' => 'classes',
		'inscriptions' => $inscriptions,
	));
} elseif (Request::getArgs(0) == 'generate') {
	$niveaux = Models\Niveau::getList();
	loadView('classes/classes-generation', array(
		'niveaux' => $niveaux,
		'isUpdate' => true,
		'navKey' => 'classes',
	));
} elseif (Request::getArgs(0)) {
	try {
		$classe = new Models\Classe(Request::getArgs(0));
	} catch (Exception $e) {
		exit('Element introuvable');
	}

	if (Request::getArgs(1) == 'edit') {
		loadView('classes/classes-form', array(
			'classe' => $classe,
			'isUpdate' => true,
			'navKey' => 'classes',
		));
	} elseif (Request::getArgs(1) == 'export') {

		// dd("here");
		header('Content-Type: text/plain; charset=utf8');
		loadLib('phpexcelsheet');
		set_time_limit(10);
		if (!PHPExcel_Settings::setLocale('fr_FR'))
			echo 'Locale not set ' . PHP_EOL;

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet();


		$objPHPExcel->getActiveSheet()
			->setCellValue('A1', 'Num')
			->setCellValue('B1', 'ID')
			->setCellValue('C1', 'Nom')
			->setCellValue('D1', 'Prenom')
			->setCellValue('E1', 'Niveau')
			->setCellValue('F1', 'Promotion')
			->setCellValue('G1', 'Groupe')
			->setCellValue('H1', 'Adresse')
			->setCellValue('I1', 'Date de naissance')
			->setCellValue('J1', 'Nom PAPA')
			->setCellValue('K1', 'Tel PAPA')
			->setCellValue('L1', 'Email PAPA')
			->setCellValue('M1', 'Nom MAMAN')
			->setCellValue('N1', 'Tel MAMAN')
			->setCellValue('O1', 'Email MAMAN');

		$startRow = 2;

		$i = 0;
		$inscriptions  = $classe->getInscriptions();
		$eleveParents = array();
		$parrainages = Models\Parrainage::getList(
			array('where' => array(
				'J1Classe' =>  $classe->get('ID'),
			)),
			Models\Parrainage::sqlQuery() . <<<END
	JOIN (SELECT `ID` AS `J1ID`, `Eleve` AS `J1Eleve`, `Promotion` AS `J1Promotion`, `Classe` AS `J1Classe` FROM `ins_inscriptions`) AS `j1` ON `parrainages`.`Eleve` = `j1`.`J1Eleve`
END
		);
		foreach ($parrainages as $item) {
			try {
				$eleveParents[$item->get('Eleve')->get('ID')][$item->get('Type')->get('ID')] = $item->get('Parent');
			} catch (Exception $th) {
				continue;
			}
		}
		foreach ($inscriptions as $item) {

			try {
				$currentRow = $startRow + $i;

				$papa = (isset($eleveParents[$item->get('Eleve')->get('ID')]) && isset($eleveParents[$item->get('Eleve')->get('ID')][1])) ? $eleveParents[$item->get('Eleve')->get('ID')][1] : null;
				$maman = (isset($eleveParents[$item->get('Eleve')->get('ID')]) && isset($eleveParents[$item->get('Eleve')->get('ID')][2])) ? $eleveParents[$item->get('Eleve')->get('ID')][2] : null;
				$objPHPExcel->getActiveSheet()
					->setCellValue('A' . $currentRow, $i + 1)
					->setCellValue('B' . $currentRow, $item->get('Eleve')->get('ID'))
					->setCellValue('C' . $currentRow, $item->get('Eleve')->get('User')->get('Nom'))
					->setCellValue('D' . $currentRow, $item->get('Eleve')->get('User')->get('Prenom'))
					->setCellValue('E' . $currentRow, $item->get('Niveau')->get('Label'))
					->setCellValue('F' . $currentRow, $item->get('Promotion')->get('Label'))
					->setCellValue('G' . $currentRow, $item->get('Classe') ? $item->get('Classe')->get('Label') : '')
					->setCellValue('H' . $currentRow, $item->get('Eleve')->get('User')->get('Adresse'))
					->setCellValue('I' . $currentRow, $item->get('Eleve')->get('User')->get('DateNaissance'))
					->setCellValue('J' . $currentRow, $papa ? $papa->get('User')->getNomComplet() : '')
					->setCellValue('K' . $currentRow, $papa ? $papa->get('User')->get('Tel') : '')
					->setCellValue('L' . $currentRow, $papa ? $papa->get('User')->get('Email') : '')
					->setCellValue('M' . $currentRow, $maman ? $maman->get('User')->getNomComplet() : '')
					->setCellValue('N' . $currentRow, $maman ? $maman->get('User')->get('Tel') : '')
					->setCellValue('O' . $currentRow, $maman ? $maman->get('User')->get('Email') : '');

				$i++;
			} catch (Exception $th) {
				continue;
			}
		}


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
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$fileName = 'base-eleves-' . $classe->get('Label');
		// redirect output to client browser
		//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $fileName . date('Ymd-His') . '.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();

		$objWriter->save('php://output');
		exit();
	} elseif (Request::getArgs(1) == 'export_pdf') {


		$pdf = new CUSTOMTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$fontArabicBold = TCPDF_FONTS::addTTFfont(_basepath . 'assets/fonts/ADOBEARABIC-BOLD.OTF', 'TrueTypeUnicode', '', 96);
		$pdf->SetFont($fontArabicBold, '', 14, '', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('');
		$pdf->SetTitle('LISTES ELEVES');
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

		$pdf->AddPage('P', 'A4');

		$html = getView('classes/classe-item-eleves', array(
			'classe' => $classe,
			'inscriptions' => $classe->getInscriptions()
		), 'null_layout');

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();
		// ---------------------------------------------------------
		ob_end_clean();
		//Close and output PDF document
		$pdf->Output('listes-eleves-.pdf', 'I');
		exit;
	} elseif (Request::getArgs(1) == 'export_absences') {

		$pdf = new CUSTOMTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$fontArabicBold = TCPDF_FONTS::addTTFfont(_basepath . 'assets/fonts/ADOBEARABIC-BOLD.OTF', 'TrueTypeUnicode', '', 96);
		$pdf->SetFont($fontArabicBold, '', 14, '', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('');
		$pdf->SetTitle('Etat absences ' . $classe->Label . ' ' . date('Y-m-d'));
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

		$pdf->AddPage('P', 'A3');

		$dateEnd = new Datetime();
		$dateEnd->modify('monday this week');
		$dateStart = clone $dateEnd;
		$dateEnd->add(new DateInterval('P6D'));
		$periodInterval = new \DateInterval('P1D');
		$days = array(
			0 => 'Lundi',
			1 => 'Mardi',
			2 => 'Mercredi',
			3 => 'Jeudi',
			4 => 'Vendredi',
			5 => 'Samedi',
			6 => 'Dimanche',
		);
		$ab_periodes = array('matin' => 'matin', 'apres-midi' => 'A-midi');

		$html = getView('classes/classe-item-absences-pdf', array(
			'classe' => $classe,
			'dateEnd' => $dateEnd,
			'dateStart' => $dateStart,
			'days' => $days,
			'ab_periodes' =>	$ab_periodes,
			'peroidObj' => new \DatePeriod($dateStart, $periodInterval, $dateEnd),
			'inscriptions' => $classe->getInscriptions()
		), 'null_layout');


		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();

		// ---------------------------------------------------------
		ob_end_clean();
		//Close and output PDF document
		$pdf->Output('etat_absences ' . str_replace(' ', '_', $classe->Label) . ' ' . date('Y_m_d') . '.pdf', 'I');

		exit;
	} elseif (Request::getArgs(1) == 'reset-password') {


		$parrainages = Models\Parrainage::getList(
			array('where' => array(
				'J1Promotion' =>  $_SESSION['promotion_actuelle'],
				'J1Classe' =>  $classe->get('ID'),
			)),
			Models\Parrainage::sqlQuery() . <<<END
	JOIN (SELECT `ID` AS `J1ID`, `Eleve` AS `J1Eleve`, `Promotion` AS `J1Promotion`, `Classe` AS `J1Classe` FROM `ins_inscriptions`) AS `j1` ON `parrainages`.`Eleve` = `j1`.`J1Eleve`
END
		);

		$users = array();


		foreach ($parrainages as $item) {

			if (!$item->get('Parent'))
				continue;
			if (!$item->get('Parent')->get('User'))
				continue;
			if (!$item->get('Parent')->get('User')->get('Email'))
				continue;

			$users[$item->get('Parent')->get('User')->get('Email')] = $item->get('Parent')->get('User');
		}


		foreach ($users as $user) {

			$password = (Config::has('config_password_generator') ? Config::get('config_password_generator') : '') . substr($user->get('Tel'), -2);
			$user->set('Password', \Tools::passwordCrypt($password, $user->get('Key')));
			$user->set('Enabled', true)->save();
			$notifMessage = (Config::has('config_send_sms') ? Config::get('config_send_sms') : '') . ' Login : ' . $user->get('Tel') . ' , Mot de passe : ' . $password;

			smsCasaNet($user->get('Tel'), $notifMessage);
			Models\NotificationSms::create(array(
				'User' =>  Session::getInstance()->getCurUser(),
				'UserTo' => $user,
				'UserToName' => $user->getNomComplet(),
				'GSM' => $user->Tel,
				'Action' => "Send sms Login & password ",
				'Message' => $notifMessage,
				'Date' => date('Y-m-d H:i:s'),
			));
		}

		Models\ActionLog::catchLog('send_login_password_sms__classe(' . $classe->getPK(true) . ')');
		$_SESSION['alert_msg'] = 'SMS a été envoyé avec succés';
		URL::redirect(URL::admin('classes/' . $classe->getPK(true)));

		// print_r($users);exit;

	} elseif (Request::getArgs(1) == 'picto') {
		// $eleves = $classe->getEleves();
		$inscriptions = $classe->getInscriptions();
		loadView('classes/classes-list-trombinoscope', array(
			'classe' => $classe,
			// 'elves' => $eleves,
			'inscriptions' => $inscriptions,
			'isUpdate' => true,
			'navKey' => 'classes',
		), '_layout-empty');
	} elseif (Request::getArgs(1) == 'affecter') {
		$eleves_classe = $classe->getEleves();
		$eleves_niveau = $classe->get('Niveau')->getElevesNonAffectes($classe->get('Promotion'));
		$nombre_classes = $classe->get('Niveau')->getCountClasses($classe->get('Promotion'));
		$classes_ = Classe::getList(['where' => ['Niveau' => $classe->get('Niveau')->ID]]);
		$allinscriptions = $classe->get('Niveau')->getInscriptions($classe->get('Promotion'));
		$inscriptions  =  $classe->getInscriptions();

		loadView('classes/classes-affectation', array(
			'classe' => $classe,
			'eleves_niveau' => $eleves_niveau,
			'classes_' => $classes_,
			'eleves_classe' => $eleves_classe,
			'nombre_classes' => $nombre_classes,
			'allinscriptions' => $allinscriptions,
			'inscriptions' => $inscriptions,
			'isUpdate' => true,
			'navKey' => 'classes',
		));
	} elseif (Request::getArgs(1) == 'delete') {
		$inscrits = Models\Inscription::getList(array('where' => array('Classe' => Request::getArgs(0))));

		if (!count($inscrits)) {
			$enseignantClasses = Models\EnseignantClasse::getList(array('where' => array('Classe' => Request::getArgs(0))));

			foreach ($enseignantClasses as $c) {
				$c->delete();
			}

			$postClasses = Models\PostClasse::getList(array('where' => array('Classe' => Request::getArgs(0))));
			foreach ($postClasses as $c) {
				$c->delete();
			}

			$edts = Edt::getList(array('where' => array('Classe' => Request::getArgs(0))));
			foreach ($edts as $edt) {
				$edt->delete();
			}

			$classe->delete();
			URL::redirect(URL::admin('classes'));
		} else {
			$data['classe'] = Request::getArgs(0);
			loadView('erreur', array(
				'error' => 'Impossible de supprimer ce classe car il y a un ou plusieus produits relatifs à ce classe',
				'classe' => Request::getArgs(0),
			));

			// loadView('erreur', array(
			// 	0 => 'Suppression impossible',
			// 	1 => '404',
			// 	2 => 'Impossible de supprimer ce classe car il y a un ou plusieus produits relatifs à ce classe.',
			// 	3 => URL::admin('classes/' . Request::getArgs(0)),
			// ));
		}
	}
	if (Request::getArgs(1) == "fiche_equipepedagogique") {

		$data = array();
		$unites_ens =  Models\EnseignantUnite::getList(array('where' => array('Enseignant IN(SELECT Enseignant FROM sco_enseignantclasses where Classe = ' . $classe->ID . '  )')));
		$unites = array_reduce($unites_ens, function ($arr, $item) {
			if (!isset($arr[$item->Unite->ID])) {
				$arr[$item->Unite->ID] = array(
					'unite' => $item->Unite,
					'profs' => array(),
				);
			}
			$arr[$item->Unite->ID]['profs'][] = $item->Enseignant;
			return $arr;
		}, array());

		$data['classe'] =  $classe;
		$data['unites'] = $unites;

		set_time_limit(-1);
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$fontArabicBold = TCPDF_FONTS::addTTFfont(_basepath . 'assets/fonts/ADOBEARABIC-BOLD.OTF', 'TrueTypeUnicode', '', 96);
		$pdf->SetFont($fontArabicBold, '', 14, '', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('');
		$pdf->SetTitle('Equipepedagogique_' . $classe->get('Label'));
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


		foreach ($classe->getInscriptions() as $key => $inscription) {
			$data['eleve'] = $inscription->get('Eleve');
			$data['inscription'] = $inscription;;
			$pdf->AddPage('A4');
			$html = getView('eleves/print/equipepedagogique', $data, 'null_layout');
			$pdf->writeHTML($html, true, false, true, false, '');
		}

		$pdf->lastPage();
		ob_end_clean();
		$pdf->Output('fiche-equipepedagogique-' .  $classe->get('Label') . '.pdf', 'I');
	} elseif (Request::getArgs(1) == "fiche_discipline") {
		//exit(0);
		set_time_limit(-1);
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$fontArabicBold = TCPDF_FONTS::addTTFfont(_basepath . 'assets/fonts/ADOBEARABIC-BOLD.OTF', 'TrueTypeUnicode', '', 96);
		$pdf->SetFont($fontArabicBold, '', 14, '', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('');
		$pdf->SetTitle('Discipline' . $classe->get('Label'));
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
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->AddPage('A4');

		$scores = [];
		foreach ($classe->getInscriptions() as $key => $ins) {
			$score_displine = Models\DisciplineAction::eleveScore($ins);
			$score = $score_displine['score'];
			$scores = array_merge($scores, [$ins->Eleve->User->getNomComplet() => $score]);
		}
		arsort($scores);
		$data['scores'] = $scores;
		$html = getView('eleves/print/fichediscipline-1', $data, 'null_layout');
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();

		foreach ($classe->getInscriptions() as  $inscription) {
			$eleve =  $inscription->get('Eleve');
			$disciplines = Models\DisciplineAction::getList(array('where' => array('Inscription' => $inscription->get('ID')), 'order' => array('DateAction' => true)));
			$absences = $inscription ? Models\Absence::getList(array('where' => array('Inscription' => $inscription->get('ID'), 'ValidateAt IS NOT NULL'))) : array();
			$retards_minutes = 0;
			$absences_count = 0;
			foreach ($absences as $item) {
				if (!$item->get('Cours')) {
					continue;
				}
				$countCours = count(explode(',', $item->get('Cours')));
				if ($item->get('Retards'))
					$retards_minutes += $item->get('Retards');
				else
					$absences_count += $countCours;
			}

			$data['eleve'] = $eleve;
			$data['inscription'] = $inscription;
			$data['classe'] =  $classe;
			$data['absences'] =  $absences;
			$data['disciplines'] =  $disciplines;
			$data['abs_dip'] =  (object) array(
				'retards_minutes' => $retards_minutes,
				'absences_count' => $absences_count,
			);

			$pdf->AddPage('A4');
			$html = getView('eleves/print/fichediscipline', $data, 'null_layout');
			$pdf->writeHTML($html, true, false, true, false, '');
		}

		$pdf->lastPage();
		ob_end_clean();
		$pdf->Output('fiche-discipline-' . $classe->get('Label') . '.pdf', 'I');
	} elseif (Request::getArgs(1) == 'fiche_inscription') {
		$compte = Models\Partenaire::getList()[0];

		$data['inscriptions'] =  $classe->getInscriptions();

		set_time_limit(-1);
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$fontArabicBold = TCPDF_FONTS::addTTFfont(_basepath . 'assets/fonts/ADOBEARABIC-BOLD.OTF', 'TrueTypeUnicode', '', 96);
		$pdf->SetFont($fontArabicBold, '', 14, '', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('');
		$pdf->SetTitle('Fiche inscrption' . $classe->get('Label'));
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
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// $fontArabicBold = TCPDF_FONTS::addTTFfont(_basepath . 'assets/fonts/ADOBEARABIC-BOLD.OTF', '', '', 96);
		// $pdf->SetFont($fontArabicBold, '', 12, '', false);
		$pdf->SetFont('dejavusans');


		foreach ($classe->getInscriptions()  as  $inscription) {
			$pdf->AddPage('L', 'A4');
			ob_start();
			$html = getView('eleves/print/fiche-inscription', ['inscription' => $inscription, 'compte' => $compte], 'null_layout');
			$pdf->writeHTML($html, true, false, true, false, '');
			ob_end_clean();
		}
		$pdf->Output('fiche-inscription' . $classe->get('Label') . '.pdf', 'I');
		exit;
	} elseif (Request::getArgs(1) == "print_badges") {
		$data = array();

		$compte = null;
		try {
			$compte = Models\Partenaire::getList()[0];
		} catch (Exception $e) {
		}
		$tel = array();
		if ($compte) {
			if ($compte->get('Fixe')) $tel[] = $compte->get('Fixe');
			if ($compte->get('Tel')) $tel[] = $compte->get('Tel');
		}
		$tel = implode(' / ', $tel);

		$data['inscriptions'] = $classe->getInscriptions();
		$data['school_tel'] = $tel;

		set_time_limit(-1);
		loadLib('tcpdf');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$fontArabicBold = TCPDF_FONTS::addTTFfont(_basepath . 'assets/fonts/ADOBEARABIC-BOLD.OTF', 'TrueTypeUnicode', '', 96);
		$pdf->SetFont($fontArabicBold, '', 14, '', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('');
		$pdf->SetTitle('Badge ' . $classe->Label);
		$pdf->SetSubject('');
		$pdf->SetKeywords('');
		// remove default header/footer
		$pdf->setPrintHeader(false);
		// $pdf->setPrintFooter(false);
		// set header and footer fonts
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		// set margins
		$pdf->SetMargins(0, 0, 0, 0);
		// set image scale factor
		//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->AddPage('L', 'A6');

		$html = getView('classes/classe-badges', $data, 'null_layout');
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();
		ob_end_clean();
		// ---------------------------------------------------------

		//Close and output PDF document
		$pdf->Output('badge-' . $classe->Label . '.pdf', 'I');
	} else {


		$inscriptions = $classe->getInscriptions(
			isAllowed('order_by_massar_notes') ? 'massar' : (isAllowed('custom_order_inscriptions') ? 'custom' : 'alphabet')
		);

		if (isset($_GET['export'])) {

			header('Content-Type: text/plain; charset=utf8');
			loadLib('phpexcelsheet');
			set_time_limit(10);
			if (!PHPExcel_Settings::setLocale('fr_FR'))
				echo 'Locale not set ' . PHP_EOL;

			$objPHPExcel = new PHPExcel();

			// $objPHPExcel->getSheet(); // at index 0
			$objPHPExcel->getActiveSheet(); // Same same, by default active sheet is at 0

			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Abdellah');

			$objPHPExcel->getActiveSheet()
				->setCellValue('A1', 'ID')
				->setCellValue('B1', 'Nom')
				->setCellValue('C1', 'Prenom');

			$startRow = 2;

			$i = 0;
			foreach ($inscriptions as $item) {
				$currentRow = $startRow + $i;

				$objPHPExcel->getActiveSheet()
					->setCellValue('A' . $currentRow, $item->get('Eleve')->get('ID'))
					->setCellValue('B' . $currentRow, $item->get('Eleve')->get('User')->get('Nom'))
					->setCellValue('C' . $currentRow, $item->get('Eleve')->get('User')->get('Prenom'));

				$i++;
			}

			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

			$fileName = $classe->get('Label');
			// redirect output to client browser
			// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $fileName . date('Ymd-His') . '.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			ob_end_clean();
			$objWriter->save('php://output');
			// print_r($objPHPExcel);

			URL::redirect(URL::admin('classes/' . $classe->get('ID')));
		}


		$alert_msg = null;
		if (isset($_SESSION['alert_msg'])) {
			$alert_msg =  $_SESSION['alert_msg'];
			unset($_SESSION['alert_msg']);
		}

		$cours_generes = $classe->cours_generes();
		$absence_saisies = $classe->absence_saisies();
		$retards_saisies = $classe->retards_saisies();
		$pctFillesGarcons = $classe->pctFillesGarcons();

		$eleveParents = array();
		$parrainages = Models\Parrainage::getList(
			array('where' => array(
				'J1Classe' =>  $classe->get('ID'),
			)),
			Models\Parrainage::sqlQuery() . <<<END
	JOIN (SELECT `ID` AS `J1ID`, `Eleve` AS `J1Eleve`, `Promotion` AS `J1Promotion`, `Classe` AS `J1Classe` FROM `ins_inscriptions`) AS `j1` ON `parrainages`.`Eleve` = `j1`.`J1Eleve`
END
		);
		foreach ($parrainages as $item) {
			try {
				$eleveParents[$item->get('Eleve')->get('ID')][$item->get('Type') ? $item->get('Type')->get('ID') : 1] = $item->get('Parent');
			} catch (Exception $th) {
			}
		}

		loadView('classes/classes-item', array(
			'classe' => $classe,
			'eleveParents' => $eleveParents,
			'alert_msg' => $alert_msg,
			'inscriptions' => $inscriptions,
			'cours_generes' => $cours_generes,
			'absence_saisies' => $absence_saisies,
			'retards_saisies' => $retards_saisies,
			'pctFillesGarcons' => $pctFillesGarcons,
			'navKey' => 'classes',
		));
	}
} else {

	$promotion = Models\Promotion::promotion_actuelle();
	// $promotion = new Models\Promotion($_SESSION['promotion_actuelle']);

	$classes = Models\Classe::getList(
		array('where' => array('Parascolaire IS NOT TRUE', 'Promotion' => $promotion->get('ID')), 'order' => array('J1Ordre' => true)),
		Models\Classe::sqlQuery() . <<<END
	 JOIN (SELECT `ID` AS `J1ID`, `Ordre` AS `J1Ordre` FROM `gen_niveaux`) AS `j1` ON `ins_classes`.`Niveau` = `j1`.`J1ID`
END
	);


	$promotions 	= Models\Promotion::order(array('Annee' => true))->get();
	$sites 	= Models\Site::getList();

	loadView('classes/classes-list', array(
		'classes' 	=> $classes,
		'promotion' => $promotion,
		'sites' => $sites,
		'site' => null,
		'promotions' => $promotions,
		'navKey' 	=> 'classes',
		'css' => ['css/pages/classes/classe.css']
	));

}
