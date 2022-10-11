<?php

declare(strict_types=1);

namespace Sng\Additionalscheduler\Tasks;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Sng\Additionalscheduler\BaseEmailTask;
use Sng\Additionalscheduler\Templating;
use Sng\Additionalscheduler\Utils;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExecqueryTask extends BaseEmailTask
{
    /**
     * @var string
     */
    public $emailtemplate;

    /**
     * @var string
     */
    public $query;

    /**
     * @return bool
     */
    public function execute(): bool
    {
        $this->query = preg_replace('#\r\n#', ' ', $this->query);

        // templating
        $template = new Templating();
        if (!empty($this->emailtemplate)) {
            $template->initTemplate($this->emailtemplate);
        } else {
            $template->initTemplate('typo3conf/ext/additional_scheduler/Resources/Private/Templates/execquery.html');
        }

        $markersArray = [];

        // exec query
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('additional_scheduler');
        $res = $queryBuilder->getConnection()->executeQuery($this->query);
        $return = '';

        if (preg_match('#SELECT.*?FROM#i', $this->query, $matches)) {
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
                foreach ($item as $itemValue) {
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
        preg_match('#<title\>(.*?)<\/title>#', $mailcontent, $matches);

        // mail
        $mailTo = $this->email;

        // we make sure  $matches has a value for index 1
        $matches += [1 => false];
        $mailSubject = $this->subject ?: $matches[1] ?: $this->getDefaultSubject('execquery');

        if (!empty($this->email)) {
            Utils::sendEmail($mailTo, $mailSubject, $mailcontent, 'html', 'utf-8');
        }

        return true;
    }

    /**
     * This method is designed to return some additional information about the task,
     * that may help to set it apart from other tasks from the same class
     * This additional information is used - for example - in the Scheduler's BE module
     * This method should be implemented in most task classes
     *
     * @return string
     */
    public function getAdditionalInformation(): string
    {
        return substr($this->query, 0, 30);
    }
}
