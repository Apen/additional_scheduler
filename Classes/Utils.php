<?php

namespace Sng\Additionalscheduler;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * tx_additionalscheduler_utils
 * Class with some utils functions
 *
 * @author     Yohann CERDAN <cerdanyohann@yahoo.fr>
 * @package    TYPO3
 * @subpackage additional_scheduler
 */
class Utils
{
    /**
     * Define all the reports
     *
     * @return array
     */
    public static function getTasksList()
    {
        return array('savewebsite', 'exec', 'execquery', 'clearcache', 'cleart3temp');
    }

    /**
     * Send a email using t3lib_htmlmail or the new swift mailer
     * It depends on the TYPO3 version
     */
    public static function sendEmail($to, $subject, $message, $type = 'plain', $charset = 'utf-8', $files = array())
    {
        $mail = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');
        $mail->setTo(explode(',', $to));
        $mail->setSubject($subject);
        $mail->setCharset($charset);
        $from = \TYPO3\CMS\Core\Utility\MailUtility::getSystemFrom();
        $mail->setFrom($from);
        $mail->setReplyTo($from);
        // add Files
        if (!empty($files)) {
            foreach ($files as $file) {
                $mail->attach(Swift_Attachment::fromPath($file));
            }
        }
        // add Plain
        if ($type == 'plain') {
            $mail->addPart($message, 'text/plain');
        }
        // add HTML
        if ($type == 'html') {
            $mail->setBody($message, 'text/html');
        }
        // send
        $mail->send();
    }

    /**
     * Returns an integer from a three part version number, eg '4.12.3' -> 4012003
     *
     * @param string $verNumberStr number on format x.x.x
     * @return   integer   Integer version of version number (where each part can count to 999)
     */
    public static function intFromVer($verNumberStr)
    {
        $verParts = explode('.', $verNumberStr);
        return intval(
            (int)$verParts[0] . str_pad((int)$verParts[1], 3, '0', STR_PAD_LEFT) . str_pad(
                (int)$verParts[2], 3, '0', STR_PAD_LEFT
            )
        );
    }
}

?>