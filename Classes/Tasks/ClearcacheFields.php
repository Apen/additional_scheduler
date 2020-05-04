<?php

namespace Sng\Additionalscheduler\Tasks;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Sng\Additionalscheduler\BaseAdditionalFieldProvider;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;

class ClearcacheFields extends BaseAdditionalFieldProvider
{
    public function validateAdditionalFields(array &$submittedData, SchedulerModuleController $parentObject)
    {
        return true;
    }

    /**
     * Task namespace, mainly to compute formfield names
     * @return string
     * @see BaseAdditionalFieldProvider::getFieldName()
     */protected function getTaskNS()
    {
        return 'clearcache';
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
        return [];
    }
}
