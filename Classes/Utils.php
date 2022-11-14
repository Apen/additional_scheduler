<?php

declare(strict_types=1);

namespace Sng\Additionalscheduler;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MailUtility;

/**
 * Class with some utils functions
 */
class Utils
{
    /**
     * Define all the reports
     *
     * @return array
     */
    public static function getTasksList(): array
    {
        return ['Savewebsite', 'Exec', 'Execquery', 'Clearcache', 'Cleart3temp', 'Query2csv'];
    }

    /**
     * @return string
     */
    public static function getPathSite(): string
    {
        return method_exists(Environment::class, 'getPublicPath')
            ? Environment::getPublicPath() . '/'
            : Environment::getPublicPath() . '/';
    }

    /**
     * Send a email using t3lib_htmlmail or the new swift mailer
     * It depends on the TYPO3 version
     *
     * @param string $to
     * @param string $subject
     * @param string $message
     * @param string $type
     * @param string $charset
     * @param array  $files
     */
    public static function sendEmail(string $to, string $subject, string $message, string $type = 'plain', string $charset = 'utf-8', array $files = []): void
    {
        $from = MailUtility::getSystemFrom();
        if ($from === null) {
            throw new \RuntimeException('System email is not configured', 165282396);
        }

        $fromAdress = key($from);
        if (is_numeric($fromAdress)) {
            $fromAdress = $from[0];
        }

        $mail = GeneralUtility::makeInstance(MailMessage::class);
        
        $parsedRecipients = MailUtility::parseAddresses($to);
        if (!empty($parsedRecipients)) {
            $mail
                ->from(new Address($fromAdress))
                ->to(...$parsedRecipients)
                ->subject($subject);
        }
        if ($type === 'plain') {
            $mail->text($message);
        } else {
            $mail->html($message);
        }

        // add Files
        foreach ($files as $fileName => $path) {
            $altName = is_string($fileName) ? $fileName : null;
            $mail->attachFromPath($path, $altName);
        }

        $mail->send();
    }
}
