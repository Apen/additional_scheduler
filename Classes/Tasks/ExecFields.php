<?php

namespace Sng\Additionalscheduler\Tasks;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Sng\Additionalscheduler\BaseAdditionalFieldProvider;
use Sng\Additionalscheduler\Utils;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExecFields extends BaseAdditionalFieldProvider
{
    public function validateAdditionalFields(array &$submittedData, SchedulerModuleController $parentObject)
    {
        $result = true;
        $pathFieldName = $this->getFieldName('execdir');
        if (empty($submittedData[$pathFieldName])) {
            $this->addMessage('execdirerror', FlashMessage::ERROR, $parentObject);
            $result = false;
        }
        // check script is executable
        $script = GeneralUtility::trimExplode(' ', $submittedData[$pathFieldName]);
        if (substr($script[0], 0, 1) != '/') {
            $script[0] = Utils::getPathSite() . $script[0];
        }
        if (!empty($script[0]) && !is_executable($script[0])) {
            $this->addMessage(sprintf($GLOBALS['LANG']->sL($this->locallangPath.':mustbeexecutable'),
                $submittedData['additionalscheduler_exec_path']), FlashMessage::ERROR);
            $result = false;
        }
        return $result;
    }

    /**
     * Task namespace, mainly to compute formfield names
     * @return string
     * @see BaseAdditionalFieldProvider::getFieldName()
     */
    protected function getTaskNs()
    {
        return 'exec';
    }

    /**
     * Fields structure
     * keys are field's names, values are formfield data
     * eg
     * [
     *   'foo' => 'input',
     *   'bar' => ['code' => 'input', 'extraAttributes' => 'class="baz"', 'default' => 'biz'],
     * ]
     * By implementing this method, fields will be auto-added to the form
     * @return array
     */
    protected function getFields()
    {
        return [
            'execdir' => 'input',
            'subject' => 'input',
            'email' => 'input',
        ];
    }
}
