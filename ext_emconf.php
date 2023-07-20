<?php

$EM_CONF['additional_scheduler'] = [
    'title' => 'Useful tasks in scheduler module',
    'description' => 'Useful tasks in the scheduler module : full backup, send query result in mail, exec SH script with reports...',
    'version' => '1.6.0',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'CERDAN Yohann',
    'author_email' => 'cerdanyohann@yahoo.fr',
    'author_company' => '',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-12.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
