# additional_scheduler

[![Latest Stable Version](https://img.shields.io/packagist/v/apen/additional_scheduler?label=version)](https://packagist.org/packages/apen/additional_scheduler)
[![Total Downloads](https://img.shields.io/packagist/dt/apen/additional_scheduler)](https://packagist.org/packages/apen/additional_scheduler)
[![TYPO3](https://img.shields.io/badge/TYPO3-10.4-orange.svg?style=flat-square)](https://typo3.org/)
[![TYPO3](https://img.shields.io/badge/TYPO3-11.5-orange.svg?style=flat-square)](https://typo3.org/)

>  Useful tasks in the scheduler module : full backup, send query result in mail as HTML or CSV, exec SH script with reports...

## What does it do?

This extension add new process in your scheduler module, for example you can :
* backup your entire TYPO3 website (with mail report)
* exec a SH script (with mail report)
* exec a SQL query with data put into a HTML table in an email
* exec a SQL query with CSV as mail attachment (with options for separator, escaping, etc.) 
* clear caches
* clear files in typo3temp older than x days

Do not hesitate to contact me if you have any good ideas.

This extension work with TYPO3 10.4.x-11.5.x.

## Screenshots

### List of all the tasks

![](https://raw.githubusercontent.com/Apen/additional_scheduler/master/Resources/Public/Images/list.png)

### backup your entire TYPO3 website (with mail report)

#### Configuration

![](https://raw.githubusercontent.com/Apen/additional_scheduler/master/Resources/Public/Images/backup.png)

#### Mail

![](https://raw.githubusercontent.com/Apen/additional_scheduler/master/Resources/Public/Images/backup-email.png)

### exec a SH script (with mail report)

#### Configuration

![](https://raw.githubusercontent.com/Apen/additional_scheduler/master/Resources/Public/Images/exec.png)

#### Mail

![](https://raw.githubusercontent.com/Apen/additional_scheduler/master/Resources/Public/Images/exec-email.png)

### exec a SQL query (with mail report and old templating)

#### Configuration

![](https://raw.githubusercontent.com/Apen/additional_scheduler/master/Resources/Public/Images/query.png)

#### Mail

![](https://raw.githubusercontent.com/Apen/additional_scheduler/master/Resources/Public/Images/query-email.png)

### clear files in typo3temp older than x days

![](https://raw.githubusercontent.com/Apen/additional_scheduler/master/Resources/Public/Images/typo3temp.png)

## Installation

Download and install as TYPO3 extension.

* Composer : composer require apen/additional_scheduler
* TER url : https://extensions.typo3.org/extension/additional_scheduler/
* Releases : https://github.com/Apen/additional_scheduler/releases

Go to the scheduler module and enjoy it.

## Changelog

[See CHANGELOG.md](https://github.com/Apen/additional_scheduler/blob/master/CHANGELOG.md)

## FAQ

I was in an interior version of 1.4 and all my tasks are broken, how can i do?

> Run the dedicated migration command : typo3/sysext/core/bin/typo3 additionalscheduler:fixupdateto14



