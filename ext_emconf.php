<?php

$EM_CONF[$_EXTKEY] = array(
    'title'            => 'Useful tasks in scheduler module',
    'description'      => 'Useful tasks in the scheduler module : full backup, send query result in mail, exec SH script with reports...',
    'category'         => 'misc',
    'version'          => '1.2.3',
    'state'            => 'stable',
    'uploadfolder'     => false,
    'createDirs'       => '',
    'clearcacheonload' => true,
    'author'           => 'CERDAN Yohann',
    'author_email'     => 'cerdanyohann@yahoo.fr',
    'author_company'   => '',
    'constraints'      => array(
        'depends'   => array(
            'typo3' => '6.2.0-8.7.99',
        ),
        'conflicts' => array(),
        'suggests'  => array(),
    ),
);
