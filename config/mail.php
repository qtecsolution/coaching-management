<?php

return [
	'default' => 'log',
	'mailers' => [
		'smtp' => [
			'transport' => 'smtp',
			'host' => 'smtp.gmail.com',
			'port' => '587',
			'encryption' => 'tls',
			'username' => 'hostforshihab@gmail.com',
			'password' => 'klgdffufnkdtxomi',
			'timeout' => NULL,
			'local_domain' => 'localhost',
		],
		'ses' => [
			'transport' => 'ses',
		],
		'postmark' => [
			'transport' => 'postmark',
		],
		'resend' => [
			'transport' => 'resend',
		],
		'sendmail' => [
			'transport' => 'sendmail',
			'path' => '/usr/sbin/sendmail -bs -i',
		],
		'log' => [
			'transport' => 'log',
			'channel' => NULL,
		],
		'array' => [
			'transport' => 'array',
		],
		'failover' => [
			'transport' => 'failover',
			'mailers' => [
				0 => 'smtp',
				1 => 'log',
			],
		],
		'roundrobin' => [
			'transport' => 'roundrobin',
			'mailers' => [
				0 => 'ses',
				1 => 'postmark',
			],
		],
	],
	'from' => [
		'address' => 'hostforshihab@gmail.com',
		'name' => 'Coaching App',
	],
];
