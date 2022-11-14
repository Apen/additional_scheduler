<?php

declare(strict_types=1);

namespace Sng\Additionalscheduler;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\AbstractAdditionalFieldProvider;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

abstract class BaseAdditionalFieldProvider extends AbstractAdditionalFieldProvider
{
    /**
     * plugin namespace, mainly to compute formfield names
     *
     * @see BaseAdditionalFieldProvider::getFieldName()
     * @var string
     */
    protected $pluginNS = 'additionalscheduler';
    /**
     * The locallang path
     *
     * @see BaseAdditionalFieldProvider::addMessage()
     * @var string
     */
    protected $locallangPath = 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xlf';

    /**
     * Task namespace, mainly to compute formfield names
     *
     * @return string
     * @see BaseAdditionalFieldProvider::getFieldName()
     */
    abstract protected function getTaskNs(): string;

    /**
     * Code template repository
     * override this function to add your own templates
     *
     * @return array
     */
    protected function getCodeTemplates(): array
    {
        return [
            'input' => [
                'code' => '<input type="text" name="tx_scheduler[%name%]" id="%id%" value="%value%" %extraAttributes% />',
                'extraAttributes' => 'size="50"',
            ],
            'textarea' => [
                'code' => '<textarea name="tx_scheduler[%name%]" id="%id%" %extraAttributes%/>%value%</textarea>',
                'extraAttributes' => 'cols="50" rows="10"',
            ],
            'checkbox' => [
                'code' => '<input type="checkbox" name="tx_scheduler[%name%]" id="%id%" value="1" %extraAttributes% %checked%/>',
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
     *
     * @return array
     */
    abstract protected function getFields(): array;

    /**
     * Compute the fieldname, based on plugin and task namespaces
     *
     * @param string $field
     * @return string
     */
    protected function getFieldName(string $field): string
    {
        return implode('_', [$this->pluginNS, $this->getTaskNs(), $field]);
    }

    /**
     * Gets additional fields to render in the form to add/edit a task
     *
     * @param array $taskInfo Values of the fields from the add/edit task form
     * @param \TYPO3\CMS\Scheduler\Task\AbstractTask|null $task The task object being edited. Null when adding a task!
     * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule Reference to the scheduler backend module
     * @return array A two dimensional array: array('fieldId' => array('code' => '', 'label' => '', 'cshKey' => '', 'cshLabel' => ''))
     */
    public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $schedulerModule): array
    {
        $this->initFields($taskInfo, $task, $schedulerModule);
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
                '%checked%' => $value === '1' ? 'checked' : '',
            ];
            // escape data in tag attributes (in case value contains quotes), then add extra attributes
            $tr = array_map('htmlspecialchars', $tr) + ['%extraAttributes%' => $extraAttributes];
            $additionalFields[$fieldID] = [
                'code' => strtr($codeTemplates[$templateName]['code'], $tr),
                'label' => 'LLL:EXT:additional_scheduler/Resources/Private/Language/locallang.xml:' . $field,
                'cshKey' => 'additional_scheduler',
                'cshLabel' => $fieldID,
            ];
        }

        return $additionalFields;
    }

    /**
     * @param array        $taskInfo
     * @param AbstractTask|null $task
     */
    protected function initFields(array &$taskInfo, ?AbstractTask $task, SchedulerModuleController $parentObject): void
    {
        foreach ($this->getFields() as $field => $data) {
            $name = $this->getFieldName($field);
            if (empty($taskInfo[$name])) {
                $default = $data['default'] ?? '';
                $taskInfo[$name] = $task->{$field} ?? $default;
            }
        }
    }

    public function saveAdditionalFields(array $submittedData, AbstractTask $task): void
    {
        foreach (array_keys($this->getFields()) as $field) {
            $task->{$field} = $submittedData[$this->getFieldName($field)] ?? '';
        }
    }

    /**
     * Shortcut to add error message
     */
    protected function addMessage(string $message, int $severity = FlashMessage::OK): void
    {
        $message = $GLOBALS['LANG']->sL($this->locallangPath . ':' . $message);
        $flashMessage = GeneralUtility::makeInstance(
            FlashMessage::class,
            $message,
            '',
            $severity,
            true
        );
        $service = GeneralUtility::makeInstance(FlashMessageService::class);
        $flashMessageQueue = $service->getMessageQueueByIdentifier();
        $flashMessageQueue->enqueue($flashMessage);
    }
}
