<?php

$EM_CONF[$_EXTKEY] = array(
    'title'            => 'Useful tasks in scheduler module',
    'description'      => 'Useful tasks in the scheduler module : full backup, send query result in mail, exec SH script with reports...',
    'category'         => 'misc',
    'version'          => '1.2.2',
    'state'            => 'stable',
    'uploadfolder'     => false,
    'createDirs'       => '',
    'clearcacheonload' => true,
    'author'           => 'CERDAN Yohann',
    'author_email'     => 'cerdanyohann@yahoo.fr',
    'author_company'   => '',
    'constraints'      => array(
        'depends'   => array(
            'php'       => '5.0.0-5.6.99',
            'typo3'     => '6.2.0-7.6.99',
            'scheduler' => ''
        ),
        'conflicts' => array(),
        'suggests'  => array(),
    ),
);

