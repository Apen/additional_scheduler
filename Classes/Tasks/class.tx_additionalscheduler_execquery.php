<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

class tx_additionalscheduler_execquery extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{

    public function execute()
    {
        require_once(PATH_site . 'typo3conf/ext/additional_scheduler/Classes/Utils.php');
        require_once(PATH_site . 'typo3conf/ext/additional_scheduler/Classes/Templating.php');

        // templating
        $template = new \Sng\Additionalscheduler\Templating();
        if (!empty($this->emailtemplate)) {
            $template->initTemplate($this->emailtemplate);
        } else {
            $template->initTemplate('typo3conf/ext/additional_scheduler/Resources/Private/Templates/execquery.html');
        }
        $markersArray = array();

        // exec query
        $res = $GLOBALS['TYPO3_DB']->sql_query($this->query);
        $return = '';

        if (preg_match('/SELECT.*?FROM/i', $this->query, $matches)) {
            $i = 0;
            $return .= '<table>';
            while ($item = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                if ($i === 0) {
                    $return .= '<thead>';
                    $return .= '<tr>';
                    foreach ($item as $itemKey => $itemValue) {
                        $return .= '<th>' . $itemKey . '</th>';
                    }
                    $return .= '</tr>';
                    $return .= '</thead>';
                    $return .= '<tbody>';
                }
                $return .= '<tr>';
                foreach ($item as $itemKey => $itemValue) {
                    $return .= '<td>' . $itemValue . '</td>';
                }
                $return .= '</tr>';
                $i++;
            }
            $return .= '</tbody>';
            $return .= '</table>';
            $GLOBALS['TYPO3_DB']->sql_free_result($res);
        } else {
            $return .= 'SQL : ' . htmlspecialchars($this->query);
        }

        $markersArray['###MAIL_CONTENT###'] = $return;
        $mailcontent = $template->renderAllTemplate($markersArray, '###EMAIl_TEMPLATE###');
        preg_match('/<title\>(.*?)<\/title>/', $mailcontent, $matches);

        // mail
        $mailTo = $this->email;
        $mailSubject = '[additional_scheduler] : ' . $GLOBALS['LANG']->sL('LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xml:task.execquery.name');
        if (!empty($matches[1])) {
            $mailSubject = $matches[1];
        }

        if (empty($this->email) !== true) {
            \Sng\Additionalscheduler\Utils::sendEmail($mailTo, $mailSubject, $mailcontent, 'html', 'utf-8');
        }

        return true;
    }

    /**
     * This method is designed to return some additional information about the task,
     * that may help to set it apart from other tasks from the same class
     * This additional information is used - for example - in the Scheduler's BE module
     * This method should be implemented in most task classes
     *
     * @return    string    Information to display
     */

    public function getAdditionalInformation()
    {
        return substr($this->query, 0, 30);
    }

}

?>
