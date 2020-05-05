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

class SavewebsiteFields extends BaseAdditionalFieldProvider
{

    public function validateAdditionalFields(array &$submittedData, SchedulerModuleController $parentObject)
    {
        $result = true;
        // check dir is writable
        $pathFieldName = $this->getFieldName('savedir');
        if ((empty($submittedData[$pathFieldName])) || (!is_writable($pathFieldName))) {
            $this->addMessage('savedirerror', FlashMessage::ERROR, $parentObject);
            $result = false;
        }
        // check save script is executable
        $saveScript = Utils::getPathSite() . 'typo3conf/ext/additional_scheduler/Resources/Shell/save_typo3_website.sh';
        if (!is_executable($saveScript)) {
            $parentObject->addMessage(sprintf($GLOBALS['LANG']->sL($this->locallangPath.':mustbeexecutable'), $saveScript), FlashMessage::ERROR);
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
        return 'savewebsite';
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
            'savedir' => 'input',
            'subject' => 'input',
            'email' => 'input',
        ];
    }
}
