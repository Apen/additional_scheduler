<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 CERDAN Yohann (cerdanyohann@yahoo.fr)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * tx_additionalscheduler_utils
 * Class with some utils functions
 *
 * @author     Yohann CERDAN <cerdanyohann@yahoo.fr>
 * @package    TYPO3
 * @subpackage additional_scheduler
 */
class tx_additionalscheduler_utils
{
	/**
	 * Define all the reports
	 *
	 * @return array
	 */

	public function getTasksList() {
		$tasks = array('savewebsite', 'exec', 'execquery', 'clearcache', 'cleart3temp');

		if (self::intFromVer(TYPO3_version) < 6000000) {
			$tasks[] = 'translationupdate';
		}

		return $tasks;
	}

	/**
	 * Send a email using t3lib_htmlmail or the new swift mailer
	 * It depends on the TYPO3 version
	 */

	public function sendEmail($to, $subject, $message, $type = 'plain', $fromEmail = '', $fromName = '', $charset = 'utf-8', $files = array()) {
		$useSwiftMailer = t3lib_div::compat_version('4.5');
		if ($useSwiftMailer) {
			// new TYPO3 swiftmailer code
			$mail = t3lib_div::makeInstance('t3lib_mail_Message');
			$mail->setTo(explode(',', $to));
			$mail->setSubject($subject);
			$mail->setCharset($charset);
			$mail->setFrom(array($fromEmail => $fromName));
			$mail->setReplyTo(array($fromEmail => $fromName));

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
		} else {
			// send mail
			$mail = t3lib_div::makeInstance('t3lib_htmlmail');
			$mail->start();
			$mail->useBase64();
			$mail->charset = $charset;
			$mail->subject = $subject;

			// from
			$mail->from_email = $fromEmail;
			$mail->from_name = $fromName;

			// replyTo
			$mail->replyto_email = $fromEmail;
			$mail->replyto_name = $fromName;

			// recipients
			$mail->setRecipient($to);

			// add Plain
			if ($type == 'plain') {
				$mail->addPlain($message);
			}

			// add HTML
			if ($type == 'html') {
				$mail->theParts['html']['content'] = $message;
				$mail->theParts['html']['path'] = '';
				$mail->extractMediaLinks();
				$mail->extractHyperLinks();
				$mail->fetchHTMLMedia();
				$mail->substMediaNamesInHTML(0); // 0 = relative
				$mail->substHREFsInHTML();
				$mail->setHtml($mail->encodeMsg($mail->theParts['html']['content']));
			}

			// add Files
			if (!empty($files)) {
				foreach ($files as $file) {
					$mail->addAttachment($file);
				}
			}

			// send
			$mail->setHeaders();
			$mail->setContent();

			return $mail->sendtheMail();
		}
	}

	/**
	 * Returns an integer from a three part version number, eg '4.12.3' -> 4012003
	 *
	 * @param    string $verNumberStr  number on format x.x.x
	 * @return   integer   Integer version of version number (where each part can count to 999)
	 */
	public function intFromVer($verNumberStr) {
		$verParts = explode('.', $verNumberStr);
		return intval(
			(int)$verParts[0] . str_pad((int)$verParts[1], 3, '0', STR_PAD_LEFT) . str_pad(
				(int)$verParts[2], 3, '0', STR_PAD_LEFT
			)
		);
	}
}

?>