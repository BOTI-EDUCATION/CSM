<?php

namespace Models\RH;

use \Models\Model;
use URL;

class Candidat extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'rh_candidats';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Offre' => array(
			'fk' => 'RH\\JobOffer',
		),
		'Nom' => array(
			'type' => 'varchar',
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
		'Adresse' => array(
			'type' => 'varchar',
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
		'Ville' => array(
			'type' => 'varchar',
		),
		'Pays' => array(
			'type' => 'varchar',
		),
		'Specialite' => array(
			'type' => 'varchar',
		),
		'Experience' => array(
			'type' => 'varchar',
		),
		'Commentaire' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'date',
		),
		'Validation' => array(
			'type' => 'text',
		),
		'ValidationUser' => array(
			'fk' => 'User',
		),

	);
	public function getCv()
	{
		return URL::base() . 'assets/schools/' . _school_alias . '/offers/' . $this->get('CV');
	}
}
