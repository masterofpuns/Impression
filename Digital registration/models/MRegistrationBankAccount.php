<?php

namespace app\hig;

/**
 * MRegistrationBankAccount
 *
 * @property $table string
 * @property $registrationId int
 * @property $iban string
 * @property $ascription string
 * @property $dateTimeCreated string
 */

class MRegistrationBankAccount extends \app\CDBRecord {
	
	protected $table = 'registration_bank_account';
	protected $registrationId;
	protected $iban;
	protected $ascription;
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
		'iban' => [
			'description' => 'IBAN',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'IBAN',
					'placeholder' => 'IBAN'
				],
				'order' => 2
			],
			'backend' => [
				'type' => 'text',
				'typeParams' => []
			]
		],
		'ascription' => [
			'description' => 'ASCRIPTION',
			'frontend' => [
				'type' => 'text-single_line',
				'typeParams' => [
					'label' =>  'ASCRIPTION',
					'placeholder' => 'ASCRIPTION'
				],
				'order' => 3
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