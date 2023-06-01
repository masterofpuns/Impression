<?php

namespace app\hig;

/**
 * MRegistrationContactPersonPhoneNumber
 *
 * @property $table string
 * @property $registrationContactPersonId int
 * @property $type string
 * @property $number int
 * @property $dateTimeCreated string
 */

class MRegistrationContactPersonPhoneNumber extends \app\CDBRecord {
	
	protected $table = 'registration_contact_person_phone_number';
	protected $registrationContactPersonId;
	protected $type;
	protected $number;
	protected $dateTimeCreated;
	
	protected $fields = [
		'registrationContactPersonId' => [
			'description' => 'REGISTRATION_CONTACT_PERSON_ID',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'REGISTRATION_CONTACT_PERSON_ID',
					'placeholder' => 'REGISTRATION_CONTACT_PERSON_ID'
				],
				'order' => 1
			],
			'backend' => [
				'type' => 'int',
				'typeParams' => []
			]
		],
		'type' => [
			'description' => 'TYPE',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'TYPE',
					'placeholder' => 'TYPE'
				],
				'order' => 2
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'number' => [
			'description' => 'NUMBER',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'NUMBER',
					'placeholder' => 'NUMBER'
				],
				'order' => 3
			],
			'backend' => [
				'type' => 'int',
				'typeParams' => []
			]
		],
		'dateTimeCreated' => [
			'description' => 'DATE_TIME_CREATED',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'DATE_TIME_CREATED',
					'placeholder' => 'DATE_TIME_CREATED'
				],
				'order' => 4
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		]
	];
	
	public function addRelated() {}
}