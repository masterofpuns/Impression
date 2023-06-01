<?php

namespace app\hig;

/**
 * MRegistrationAddress
 *
 * @property $table string
 * @property $registrationId int
 * @property $street string
 * @property $number string
 * @property $numberSuffix string
 * @property $postalCode string
 * @property $city string
 * @property $countryId string
 * @property $dateTimeCreated string
 */

class MRegistrationAddress extends \app\CDBRecord {
	
	protected $table = 'registration_address';
	protected $registrationId;
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
		'street' => [
			'description' => 'STREET',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'STREET',
					'placeholder' => 'STREET'
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
				'order' => 4
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
				'order' => 5
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
				'order' => 6
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'countryId' => [
			'description' => 'COUNTRY_ID',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'COUNTRY_ID',
					'placeholder' => 'COUNTRY_ID'
				],
				'order' => 7
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
				'order' => 8
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		]
	];
	
	public function addRelated() {}
}