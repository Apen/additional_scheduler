<?php

namespace Sng\Additionalscheduler;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Class BaseAdditionalFieldProvider
 */
abstract class BaseAdditionalFieldProvider implements AdditionalFieldProviderInterface
{
    /**
     * plugin namespace, mainly to compute formfield names
     * @see BaseAdditionalFieldProvider::getFieldName()
     * @var string
     */
    protected $pluginNS = 'additionalscheduler';

    /**
     * The locallang path
     * @see BaseAdditionalFieldProvider::addMessage()
     * @var string
     */
    protected $locallangPath = 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf';

    /**
     * Task namespace, mainly to compute formfield names
     * @see BaseAdditionalFieldProvider::getFieldName()
     * @return string
     */
    abstract protected function getTaskNs();

    /**
     * Code template repository
     * override this function to add your own templates
     *
     * @return array
     */
    protected function getCodeTemplates()
    {
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
        return implode('_', [$this->pluginNS, $this->getTaskNs(), $field]);
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
            $templateName = $data['code'] ?? $data;
            $extraAttributes = $data['extraAttributes'] ?? $codeTemplates[$templateName]['extraAttributes'];
            $fieldID = 'task_' . $field;
            $value = $taskInfo[$this->getFieldName($field)];
            $tr = [
                '%name%' => $this->getFieldName($field),
                '%id%' => $fieldID,
                '%value%' => $value,
                '%checked%' => $value == 1 ? 'checked' : '',
            ];
            // escape data in tag attributes (in case value contains quotes), then add extra attributes
            $tr = array_map('htmlspecialchars', $tr) + ['%extraAttributes%' => $extraAttributes];
            $additionalFields[$fieldID] = [
                'code'     => strtr($codeTemplates[$templateName]['code'], $tr),
                'label'    => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xml:' . $field,
                'cshKey'   => 'additional_scheduler',
                'cshLabel' => $fieldID
            ];
        }
        return $additionalFields;
    }

    /**
     * @param array $taskInfo
     * @param AbstractTask $task
     * @param SchedulerModuleController $parentObject
     */
    protected function initFields(&$taskInfo, $task, SchedulerModuleController $parentObject)
    {
        foreach ($this->getFields() as $field => $data) {
            $name = $this->getFieldName($field);
            if (empty($taskInfo[$name])) {
                $default = $data['default'] ?? '';
                $taskInfo[$name] = $task->$field ?: $default;
            }
        }
    }

    /**
     * @param array $submittedData
     * @param AbstractTask $task
     */
    public function saveAdditionalFields(array $submittedData, AbstractTask $task)
    {
        foreach (array_keys($this->getFields()) as $field) {
            $task->$field = $submittedData[$this->getFieldName($field)] ?? '';
        }
    }

    /**
     * Shortcut to add error message
     * @param $trKey - the translation key in localang
     * @param $alert
     * @param SchedulerModuleController $parentObject
     */
    protected function addMessage(string $trKey, $alert, SchedulerModuleController $parentObject)
    {
        $message  = $GLOBALS['LANG']->sL($this->locallangPath . ':' . $trKey);
        return $parentObject->addMessage($message, $alert);
    }
}
