<?php

namespace Models\RH;

use DateTime;
use DB;
use \Models\Model;


class CV extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'rh_cvs';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Nom' => array(
			'type' => 'varchar',
		),

		'Candidat' => array(
			'fk' => 'RH\Candidat',
		),
		'Prenom' => array(
			'type' => 'varchar',
		),
		'CIN' => array(
			'type' => 'varchar',
		),
		'Homme' => array(
			'type' => 'bool',
		),
		'DateNaissance' => array(
			'type' => 'date',
		),
		'GSM' => array(
			'type' => 'varchar',
		),
		'Email' => array(
			'type' => 'varchar',
		),
		'CV' => array(
			'type' => 'varchar',
		),
		'Adresse' => array(
			'type' => 'varchar',
		),
		'Ville' => array(
			'type' => 'varchar',
		),
		'Pays' => array(
			'type' => 'varchar',
		),
		'Secteur' => array(
			'type' => 'varchar',
		),
		'Poste' => array(
			'type' => 'varchar',
		),
		'Specialite' => array(
			'type' => 'varchar',
		),
		'Enseignant' => array(
			'type' => 'bool',
		),
		'MatiereEnseigne' => array(
			'type' => 'varchar',
		),
		'Cycles' => array(
			'type' => 'varchar',
		),
		'Experience' => array(
			'type' => 'varchar',
		),
		'Diplome' => array(
			'type' => 'varchar',
		),
		'DiplomeEtablissement' => array(
			'type' => 'varchar',
		),
		'DernierExperience' => array(
			'type' => 'varchar',
		),
		'Remarques' => array(
			'type' => 'text',
		),
		'Appreciation' => array(
			'type' => 'varchar',
		),
		'Tags' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'date',
		),
		'User' => array(
			'fk' => 'User',
		),

	);


	public function getAge()
	{
		$date = new DateTime($this->DateNaissance);
		$now = new DateTime();
		$interval = $now->diff($date);
		return $interval->y;
	}



	static function getSecteurs()
	{
		$query = 'Select DISTINCT(rh_cvs.Secteur) from rh_cvs';
		$secteurs = DB::reader($query);
		return $secteurs;
	}


	static function getTags()
	{
		$tags_ = CV::getList();;
		$tags = [];
		foreach ($tags_ as $tag__) {
			if ($tag__->getJsonProperty('Tags')) {

				foreach (explode(',', $tag__->getJsonProperty('Tags')[0]) as $t) {
					array_push($tags,  $t);
				}
			}
		}
		return array_unique($tags);
	}


	static function appreciations()
	{
		return [
			'Candidat Sous qualifié',
			'Candidat moyennement qualifié',
			'Très haut potentiel',
			'Candidat à suivre de près',
		];
	}
}
