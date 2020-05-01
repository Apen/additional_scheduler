<?php

namespace Sng\Additionalscheduler;

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

use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

abstract class BaseAdditionalFieldProvider implements \TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface
{
    /**
     * plugin namespace, mainly to compute formfield names
     * @see BaseAdditionalFieldProvider::getFieldName()
     * @var string
     */
    protected $pluginNS = 'additionalscheduler';

    /**
     * Task namespace, mainly to compute formfield names
     * @see BaseAdditionalFieldProvider::getFieldName()
     * @return string
     */
    abstract protected function getTaskNS();

    /**
     * Code template repository
     * override this function to add your own templates
     *
     * @return array
     */
    protected function getCodeTemplates() {
        return [
            'input' => [
                'code'=> '<input type="text" name="tx_scheduler[%name%]" id="%id%" value="%value%" %extraAttributes% />',
                'extraAttributes' => 'size="50"',
            ],
            'textarea' => [
                'code'=> '<textarea name="tx_scheduler[%name%]" id="%id%" %extraAttributes%/>%value%</textarea>',
                'extraAttributes' => 'cols="50" rows="10"',
            ],
            'checkbox' => [
                'code'=> '<input type="checkbox" name="tx_scheduler[%name%]" id="%id%" value="1" %extraAttributes% %checked%/>',
                'extraAttributes' => '',
            ],
        ];
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
    abstract protected function getFields();

    /**
     * Compute the fieldname, based on plugin and task namespaces
     * @param $field
     * @return string
     */
    protected function getFieldName($field)
    {
        return implode('_', [$this->pluginNS, $this->getTaskNS(), $field]);
    }

    /**
     * Auto-add additionnal fields, based on the getFields() implementation
     * @param array $taskInfo
     * @param AbstractTask $task
     * @param SchedulerModuleController $parentObject
     * @return array
     */
    public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $parentObject)
    {

        $this->initFields($taskInfo, $task, $parentObject);
        $additionalFields = [];
        $codeTemplates = $this->getCodeTemplates();
        foreach ($this->getFields() as $field => $data) {
            $templateName = isset($data['code']) ? $data['code'] : $data;
            $extraAttributes = isset($data['extraAttributes'])
                ? $data['extraAttributes']
                : $codeTemplates[$templateName]['extraAttributes'];
            $fieldID = 'task_'.$field;
            $value = $taskInfo[$this->getFieldName($field)];
            $tr = [
                '%name%' => $this->getFieldName($field),
                '%id%' => $fieldID,
                '%value%' => $value,
                '%checked%' => $value == 1 ? 'checked' : '',
            ];
            // escape data in tag attributes (in case value contains quotes), then add extra attributes
            $tr = array_map('htmlspecialchars', $tr) + ['%extraAttributes%' => $extraAttributes];
            $additionalFields[$fieldID] = array(
                'code'     => strtr($codeTemplates[$templateName]['code'], $tr),
                'label'    => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xml:'.$field,
                'cshKey'   => 'additional_scheduler',
                'cshLabel' => $fieldID
            );
        }
        return $additionalFields;
    }

    protected function initFields(&$taskInfo, $task, SchedulerModuleController $parentObject)
    {
        foreach ($this->getFields() as $field => $data) {
            $name = $this->getFieldName($field);
            if (empty($taskInfo[$name])) {
                $default = isset($data['default']) ? $data['default'] : '';
                $taskInfo[$name] = $task->$field ?: $default;
            }
        }
    }

    public function saveAdditionalFields(array $submittedData, AbstractTask $task)
    {
        foreach (array_keys($this->getFields()) as $field) {
            $task->$field = isset($submittedData[$this->getFieldName($field)])
                ? $submittedData[$this->getFieldName($field)]
                : '';
        }
    }
}