<?php

namespace Models\RH;

use \Models\Model;
use URL;

class JobOffer extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'rh_job_offers';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Title' => array(
			'type' => 'varchar',
		),
		'Alias' => array(
			'type' => 'varchar',
		),
		'Presentation' => array(
			'type' => 'text',
		),
		'Image' => array(
			'type' => 'varchar',
		),
		'Date' => array(
			'type' => 'date',
		),
		'User' => array(
			'fk' => 'User',
		),
		'DatePublication' => array(
			'type' => 'date',
		),
		'DateFin' => array(
			'type' => 'date',
		),
		'Activation' => array(
			'type' => 'text',
		),
	);
	public function getImage()
	{
		// if (!$this->get('Image') || !file_exists(_basepath . \Config::get('path-offers') . $this->get('Image'))) {

		// 	return URL::base() . 'assets/icons/certificate.png';
		// }

		// return \URL::absolute(\URL::base() . \Config::get('path-offers') . $this->get('Image'));
		$url = $this->get('Image') ? \GoogleStorage::getUrl(\Config::get('path-offers') . $this->get('Image')) : null;

		if (!$url) {
			return $this->type->getImage();
		}

		return \GoogleStorage::getUrl(\Config::get('path-offers') . $this->get('Image'));
	}
	public function getCondidatsCount($all=true)
	{

		$where = [
			'Offre' => $this->get('ID')
		];
		
		if(!$all){
			$where[] = 'Validation IS NULL';

		}

		$candidats = Candidat::where($where)->get();
		return count($candidats);
	}
}
