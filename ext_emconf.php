<?php

$EM_CONF['additional_scheduler'] = [
    'title' => 'Useful tasks in scheduler module',
    'description' => 'Useful tasks in the scheduler module : full backup, send query result in mail, exec SH script with reports...',
    'category' => 'misc',
    'version' => '1.5.5',
    'state' => 'stable',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearcacheonload' => true,
    'author' => 'CERDAN Yohann',
    'author_email' => 'cerdanyohann@yahoo.fr',
    'author_company' => '',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-11.2.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
