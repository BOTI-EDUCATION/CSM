<?php

namespace Models\RH;

use \Models\Model;
use Session;

class Collaborateur extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'rh_collaborateur';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Superieur' => array(
			'fk' => 'RH\Collaborateur',
		),
		'User' => array(
			'fk' => 'User',
		),
		'CIN' => array(
			'type' => 'varchar',
		),
		'Superieurs' => array(
			'type' => 'text',
		),
		'Sites' => array(
			'type' => 'text',
		),
		'Chauffeur' => array(
			'type' => 'boolean',
		),
		'AideChauffeur' => array(
			'type' => 'boolean',
		),
		'NumeroPermis' => array(
			'type' => 'varchar',
		),
		'DateValiditePermis' => array(
			'type' => 'date',
		),
		'Fonction' => array(
			'type' => 'varchar',
		),
		'Horaire' => array(
			'type' => 'text',
		),
		'CodePointage' => array(
			'type' => 'varchar',
		),
		'Vacataire' => array(
			'type' => 'bool',
		),
		'DateEmbauche' => array(
			'type' => 'date',
		),
		'SalaireBase' => array(
			'type' => 'double',
		),
		'NumeroCnss' => array(
			'type' => 'text',
		),
		'MatriculeInterne' => array(
			'type' => 'varchar',
		),
		'Bank' => array(
			'type' => 'varchar',
		),
		'NemuroCompteBank' => array(
			'type' => 'varchar',
		),
		'DateDemission' => array(
			'type' => 'varchar',
		),

		'Files' => array(
			'type' => 'text',
		),
		'Salaires' => array(
			'type' => 'text',
		),
		'DeletedAt' => array(
			'type' => 'text',
		),
	);


	public static function getList($args = null, $query = null)
	{

		$authUser = Session::user();
		$authUsercollab = $authUser ? $authUser->getCollaborateur() : null;

		if (!is_array($args))
			$args = array();

		$args['where'][] = '(`User` NOT IN (SELECT `ID` FROM `users` WHERE `DeletedAt` IS NOT NULL))';

		// if (!roleIs('admin', 'assistant_rh', 'resp_pedago')) {
		 	// if (!roleIs('admin', 'assistant_rh')) {

		// 	if ($authUsercollab) {
		// 		$args['where'][] =  '((`Superieurs` LIKE \'%"' . $authUsercollab->getKey() . '"%\'))';
		// 	}
		// }

		return parent::getList($args, $query);
	}


	public static function getByNumeroCnss($value)
	{
		$collaborateur = self::getList(array("where" => array(
			'NumeroCnss' => $value,
		)));
		return count($collaborateur) ? $collaborateur[0] : null;
	}

	public static function getByMatriculeInterne($value)
	{
		$collaborateur = \Models\RH\Collaborateur::getList(array("where" => array(
			'MatriculeInterne' => $value,
		)));
		return count($collaborateur) ? $collaborateur[0] : null;
	}

	public static function getByNumeroCompteBank($value)
	{
		$collaborateur = self::getList(array("where" => array(
			'NemuroCompteBank' => $value,
		)));
		return count($collaborateur) ? $collaborateur[0] : null;
	}


	function files()
	{
		return  $this->Files ? json_decode($this->Files, true) : array();
	}

	function getFiles($full_link = false)
	{
		return array_map(function ($item) use ($full_link) {

			$item['file'] =  \GoogleStorage::getUrl(\Config::get('path-images-rh-files') . $item['file']);
			return $item;
		}, $this->files());
	}

	function addFile($label, $file, $index = null)
	{
		$files = $this->files();
		if (is_null($index)) {
			$files[] = array(
				'label' => $label,
				'file' => $file
			);
		} else {
			$files[$index] = array(
				'label' => $label,
				'file' => $file
			);
		}
		$this->Files = json_encode($files);
	}


	function deleteFile($index)
	{
		$files = $this->files();
		unset($files[$index]);
		$this->Files = json_encode($files);
	}

	public function getImage($full_link = false)
	{

		$icon = $this->get('Image');
		if (!$icon) {
			$icon = 'default1.png';
		}
		return;
	}

	public function getAbsences($dateStart = null, $dateEnd = null)
	{

		$where = [];

		$where['User'] = $this->User->ID;
		$where[] = '(`Retard` IS NULL OR `Retard` = 0)';

		if ($dateStart) {
			$where[] = "`Date` >= '$dateStart'";
		}

		if ($dateEnd) {
			$where[] = "`Date` <= '$dateEnd'";
		}

		$absences = \Models\MGMT\Team\Absence::getList(array(
			'where' => $where
		));


		return $absences;
	}
}
