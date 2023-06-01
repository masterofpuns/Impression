<?php
namespace app\hig;

use \app\h;
use \app\m;

class CRegistrationService {
	public function getRegistrationRelation($registrationId) {
		$registrationRelation = m::app()->db->querySingle(
			"SELECT * FROM registration_relation WHERE registrationId = :registrationId ORDER BY id ASC LIMIT 1",
			[":registrationId" => $registrationId],
			'\\app\\hig\\MRegistrationRelation'
		);
		
		return $registrationRelation;
	}
	public function getRegistrationContactPersons($registrationId) {
		$registrationContactPersons = m::app()->db->query(
			"SELECT * FROM registration_contact_person WHERE registrationId = :registrationId",
			[":registrationId" => $registrationId],
			'\\app\\hig\\MRegistrationContactPerson'
		);
		
		return $registrationContactPersons;
	}
	public function getRegistrationAddress($registrationId) {
		$registrationAddress = m::app()->db->querySingle(
			"SELECT * FROM registration_address WHERE registrationId = :registrationId ORDER BY id ASC LIMIT 1",
			[":registrationId" => $registrationId],
			'\\app\\hig\\MRegistrationAddress'
		);
		
		return $registrationAddress;
	}
	public function getRegistrationBankAccount($registrationId) {
		$registrationBankAccount = m::app()->db->querySingle(
			"SELECT * FROM registration_bank_account WHERE registrationId = :registrationId ORDER BY id ASC LIMIT 1",
			[":registrationId" => $registrationId],
			'\\app\\hig\\MRegistrationBankAccount'
		);
		
		return $registrationBankAccount;
	}
	public function getRegistrationUbos($registrationId) {
		$registrationUbos = m::app()->db->query(
			"SELECT * FROM registration_ubo WHERE registrationId = :registrationId",
			[":registrationId" => $registrationId],
			'\\app\\hig\\MRegistrationUbo'
		);
		
		return $registrationUbos;
	}
	public function getRegistrationContactPersonPhoneNumbers($registrationContactPersonId) {
		$registrationContactPersonPhoneNumbers = m::app()->db->query(
			"SELECT * FROM registration_contact_person_phone_number WHERE registrationContactPersonId = :registrationContactPersonId",
			[":registrationContactPersonId" => $registrationContactPersonId],
			'\\app\\hig\\MRegistrationContactPersonPhoneNumber'
		);
		
		return $registrationContactPersonPhoneNumbers;
	}
	public function getRegistrationContactPersonAddress($registrationContactPersonId) {
		$registrationContactPersonAddress = m::app()->db->querySingle(
			"SELECT * FROM registration_contact_person_address WHERE registrationContactPersonId = :registrationContactPersonId LIMIT 1",
			[":registrationContactPersonId" => $registrationContactPersonId],
			'\\app\\hig\\MRegistrationContactPersonAddress'
		);
		
		return $registrationContactPersonAddress;
	}
	public function getRegistrationBySignToken($token) {
		$registration = m::app()->db->querySingle(
			"
				SELECT R.*
				FROM registration R
				INNER JOIN registration_contact_person RCP ON RCP.registrationId = R.id
				WHERE RCP.signToken = :signToken
				LIMIT 1
			",
			[":signToken" => $token],
			'\\app\\hig\\MRegistration'
		);
		
		return $registration;
	}
	public function checkRegistrationSignedByToken($registration, $token) {
		$signed = false;
		
		$contactPerson = $this->getContactPersonForRegistrationByToken($registration, $token);
		if (!empty($contactPerson) && !empty($contactPerson->dateTimeSigned)) {
			$signed = true;
		}
		
		return $signed;
	}
	public function getContactPersonForRegistrationByToken($registration, $token) {
		$contactPerson = m::app()->db->querySingle(
			"
				SELECT RCP.*
				FROM registration_contact_person RCP
				WHERE RCP.signToken = :signToken
				AND RCP.registrationId = :registrationId
				LIMIT 1
			",
			[
				":signToken" => $token,
				":registrationId" => $registration->id
			],
			'\\app\\hig\\MRegistrationContactPerson'
		);
		
		return $contactPerson;
	}
	public function getExistingRegistrationForRelation($relation, $fund) {
		$registration = m::app()->db->querySingle(
			"SELECT * FROM registration WHERE relationId = :relationId AND fundId = :fundId ORDER BY id ASC LIMIT 1 ",
			[":relationId" => $relation->id, ":fundId" => $fund->id],
			'\\app\\hig\\MRegistration'
		);
		return $registration;
	}
	public function getRegistrationContactPersonsAddress($registrationContactPersonId, $contactPersonParams, $properties) {
		$additionalSQL = "";
		$replaces = [":registrationContactPersonId" => $registrationContactPersonId];
		foreach ($properties as $property) {
			if (!isset($contactPersonParams[$property])) { continue; }
			$additionalSQL .= " AND $property = :$property";
			$replaces[":$property"] = $contactPersonParams[$property];
		}
		
		$registrationContactPersonAddress = m::app()->db->querySingle(
			"
				SELECT *
				FROM registration_contact_person_address
				WHERE registrationContactPersonId = :registrationContactPersonId
				$additionalSQL
				LIMIT 1
			",
			$replaces,
			'\\app\\hig\\MRegistrationContactPersonAddress'
		);
		
		return $registrationContactPersonAddress;
	}
}