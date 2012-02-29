<?php

class tx_additionalscheduler_utils
{
	/**
	 * Define all the reports
	 *
	 * @return array
	 */

	public function getTasksList() {
		$tasks = array('savewebsite', 'translationupdate', 'exec', 'clearcache');
		return $tasks;
	}

	/**
	 * Send a email using t3lib_htmlmail
	 */

	public function sendEmail($to, $subject, $message, $type = 'plain', $fromEmail = '', $fromName = '', $charset = 'iso-8859-1', $files = array()) {
		// send mail
		$mail = t3lib_div::makeInstance('t3lib_htmlmail');
		$mail->start();
		$mail->useBase64();
		$mail->charset = 'iso-8859-1';
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

?>