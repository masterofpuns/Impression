<?php

namespace app\hig;

/**
 * MRegistrationContactPersonAddress
 *
 * @property $table string
 * @property $registrationId int
 * @property $registrationContactPersonId int
 * @property $street string
 * @property $number string
 * @property $numberSuffix string
 * @property $postalCode string
 * @property $city string
 * @property $country string
 * @property $dateTimeCreated string
 */

class MRegistrationContactPersonAddress extends \app\CDBRecord {
	
	protected $table = 'registration_contact_person_address';
	protected $registrationId;
	protected $registrationContactPersonId;
	protected $street;
	protected $number;
	protected $numberSuffix;
	protected $postalCode;
	protected $city;
	protected $country;
	protected $dateTimeCreated;
	
	protected $fields = [
		'registrationId' => [
			'description' => 'REGISTRATION_ID',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'REGISTRATION_ID',
					'placeholder' => 'REGISTRATION_ID'
				],
				'order' => 1
			],
			'backend' => [
				'type' => 'int',
				'typeParams' => []
			]
		],
		'registrationContactPersonId' => [
			'description' => 'REGISTRATION_CONTACT_PERSON_ID',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'REGISTRATION_CONTACT_PERSON_ID',
					'placeholder' => 'REGISTRATION_CONTACT_PERSON_ID'
				],
				'order' => 2
			],
			'backend' => [
				'type' => 'int',
				'typeParams' => []
			]
		],
		'street' => [
			'description' => 'STREET',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'STREET',
					'placeholder' => 'STREET'
				],
				'order' => 3
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
				'order' => 4
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'numberSuffix' => [
			'description' => 'NUMBER_SUFFIX',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'NUMBER_SUFFIX',
					'placeholder' => 'NUMBER_SUFFIX'
				],
				'order' => 5
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'postalCode' => [
			'description' => 'POSTAL_CODE',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'POSTAL_CODE',
					'placeholder' => 'POSTAL_CODE'
				],
				'order' => 6
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'city' => [
			'description' => 'CITY',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'CITY',
					'placeholder' => 'CITY'
				],
				'order' => 7
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'country' => [
			'description' => 'COUNTRY',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'COUNTRY',
					'placeholder' => 'COUNTRY'
				],
				'order' => 8
			],
			'backend' => [
				'type' => 'text',
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
				'order' => 9
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		]
	];
	
	public function addRelated() {}
}