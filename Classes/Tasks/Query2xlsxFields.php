<?php

declare(strict_types=1);

namespace Sng\Additionalscheduler\Tasks;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Sng\Additionalscheduler\BaseAdditionalFieldProvider;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;

class Query2xlsxFields extends BaseAdditionalFieldProvider
{
    /**
     * @return bool
     */
    public function validateAdditionalFields(array &$submittedData, SchedulerModuleController $parentObject): bool
    {
        $result = true;
        if (empty($submittedData[$this->getFieldName('query')])) {
            $this->addErrorMessage('query.error.required');
            $result = false;
        }

        if (empty($submittedData[$this->getFieldName('filename')])) {
            $this->addErrorMessage('filename.error.required'); // Assuming a generic error message key
            $result = false;
        } elseif (!str_ends_with(strtolower($submittedData[$this->getFieldName('filename')]), '.xlsx')) {
            $submittedData[$this->getFieldName('filename')] .= '.xlsx';
        }


        // Basic check for PhpSpreadsheet library presence.
        // This message will appear in the scheduler module if the class is not found.
        if (!class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
            $this->addFlashMessage(
                'PhpSpreadsheet library not found. Please install it using Composer: "composer require phpoffice/phpspreadsheet". XLSX export will not work.',
                'XLSX Library Missing',
                \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING // Or ::ERROR depending on desired severity
            );
            // Optionally, prevent saving the task or return false if it's critical
            // For now, just a warning. The task execution will fail more clearly.
        }


        return $result;
    }

    /**
     * Field structure
     * keys are field's names, values form field data
     *
     * @return array
     */
    protected function getFields(): array
    {
        return [
            'filename' => ['code' => 'input', 'default' => 'data.xlsx'],
            'noDatetimeFlag' => ['code' => 'checkbox', 'default' => '0'],
            'noHeader' => ['code' => 'checkbox', 'default' => '0'],
            'query' => 'textarea',
            'email' => 'input',
            'subject' => 'input',
            'body' => 'textarea',
        ];
    }

    /**
     * @return string
     */
    protected function getTaskNs(): string
    {
        return 'query2xlsx';
    }
}
