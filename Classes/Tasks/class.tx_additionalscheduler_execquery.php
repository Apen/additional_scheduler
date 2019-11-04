<?php

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class tx_additionalscheduler_execquery extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{

    public function execute()
    {

        $this->query = preg_replace('/\r\n/', ' ', $this->query);

        // templating
        $template = new \Sng\Additionalscheduler\Templating();
        if (!empty($this->emailtemplate)) {
            $template->initTemplate($this->emailtemplate);
        } else {
            $template->initTemplate('typo3conf/ext/additional_scheduler/Resources/Private/Templates/execquery.html');
        }
        $markersArray = array();

        // exec query
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('additional_scheduler');
        $res = $queryBuilder->getConnection()->executeQuery($this->query);
        $return = '';

        if (preg_match('/SELECT.*?FROM/i', $this->query, $matches)) {
            $i = 0;
            $return .= '<table>';
            while ($item = $res->fetch()) {
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