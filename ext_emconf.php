<?php

$EM_CONF['additional_scheduler'] = array(
    'title'            => 'Useful tasks in scheduler module',
    'description'      => 'Useful tasks in the scheduler module : full backup, send query result in mail, exec SH script with reports...',
    'category'         => 'misc',
    'version'          => '1.3.0-dev',
    'state'            => 'stable',
    'uploadfolder'     => false,
    'createDirs'       => '',
    'clearcacheonload' => true,
    'author'           => 'CERDAN Yohann',
    'author_email'     => 'cerdanyohann@yahoo.fr',
    'author_company'   => '',
    'constraints'      => array(
        'depends'   => array(
            'typo3' => '8.7.0-9.5.99',
        ),
        'conflicts' => array(),
        'suggests'  => array(),
    ),
);