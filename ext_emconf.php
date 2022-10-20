<?php

$EM_CONF['additional_scheduler'] = [
    'title' => 'Useful tasks in scheduler module',
    'description' => 'Useful tasks in the scheduler module : full backup, send query result in mail, exec SH script with reports...',
    'category' => 'misc',
    'version' => '1.5.9',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'CERDAN Yohann',
    'author_email' => 'cerdanyohann@yahoo.fr',
    'author_company' => '',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
