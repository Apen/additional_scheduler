<?php

declare(strict_types=1);

namespace Sng\Additionalscheduler;

/*
 * This file is part of the "additional_scheduler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Service\MarkerBasedTemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * This class provides methods to generate the templates reports
 */
class Templating
{
    /**
     * Template object for frontend functions
     */
    public $templateContent;

    /**
     * Loads a template file
     *
     * @param string $templateFile
     * @param bool   $debug
     * @return bool
     */
    public function initTemplate(string $templateFile, bool $debug = false): bool
    {
        $templateAbsPath = GeneralUtility::getFileAbsFileName($templateFile);
        if ($templateAbsPath !== '') {
            $this->templateContent = GeneralUtility::getURL($templateAbsPath);
            if ($debug) {
                if ($this->templateContent === null) {
                    DebuggerUtility::var_dump('Check the path template or the rights', 'Error');
                }

                DebuggerUtility::var_dump($this->templateContent, 'Content of ' . $templateFile);
            }

            return true;
        }

        return false;
    }

    /**
     * Template rendering for subdatas and principal datas
     *
     * @param array  $templateMarkers
     * @param string $templateSection
     * @param bool   $debug
     * @return string HTML code
     */
    public function renderAllTemplate(array $templateMarkers, string $templateSection, bool $debug = false): string
    {
        // Check if the template is loaded
        if (!$this->templateContent) {
            return '';
        }

        // Check argument
        if (!is_array($templateMarkers)) {
            return '';
        }

        if ($debug) {
            DebuggerUtility::var_dump($templateMarkers, 'Markers for ' . $templateSection);
        }

        $content = '';

        if (is_array($templateMarkers[0] ?? '')) {
            foreach ($templateMarkers as $markers) {
                $content .= $this->renderAllTemplate($markers, $templateSection, $debug);
            }
        } else {
            $content = $this->renderSingle($templateMarkers, $templateSection);
        }

        return $this->cleanTemplate($content);
    }

    /**
     * Render a single part with array and section
     *
     * @param array  $templateMarkers
     * @param string $templateSection
     * @return string
     */
    public function renderSingle(array $templateMarkers, string $templateSection): string
    {
        $subParts = $this->getSubpart($this->templateContent, $templateSection);

        foreach ($templateMarkers as $subPart => $subContent) {
            if (preg_match_all('/(<!--).*?' . $subPart . '.*?(-->)/', $subParts, $matches) >= 2) {
                $subParts = $this->substituteSubpart($subParts, $subPart, $subContent);
            }
        }

        return $this->substituteMarkerArray($subParts, $templateMarkers);
    }

    /**
     * Substitutes markers in a template. Usually, this is just a wrapper method
     * around the \TYPO3\CMS\Core\Html\HtmlParser::substituteMarkerArray method. However, this
     * method is only available from TYPO3 4.2.
     *
     * @param string $template The template
     * @param array  $marker   The markers that are to be replaced
     * @return string           The template with replaced markers
     */
    protected function substituteMarkerArray(string $template, array $marker): string
    {
        $templateService = GeneralUtility::makeInstance(MarkerBasedTemplateService::class);
        return $templateService->substituteMarkerArray($template, $marker, '', false, false);
    }

    /**
     * Replaces a subpart in a template with content. This is just a wrapper method
     * around the substituteSubpart method of the \TYPO3\CMS\Core\Html\HtmlParser class.
     *
     * @param string $template The tempalte
     * @param string $subpart  The subpart name
     * @param string $replace  The subpart content
     * @return string           The template with replaced subpart.
     */
    protected function substituteSubpart(string $template, string $subpart, string $replace): string
    {
        $templateService = GeneralUtility::makeInstance(MarkerBasedTemplateService::class);
        return $templateService->substituteSubpart($template, $subpart, $replace, true, false);
    }

    /**
     * Gets a subpart from a template. This is just a wrapper around the getSubpart
     * method of the \TYPO3\CMS\Core\Html\HtmlParser class.
     *
     * @param string $template The template
     * @param string $subpart  The subpart name
     * @return string           The subpart
     */
    protected function getSubpart(string $template, string $subpart): string
    {
        $templateService = GeneralUtility::makeInstance(MarkerBasedTemplateService::class);
        return $templateService->getSubpart($template, $subpart);
    }

    /**
     * Clean a template string (remove blank lines...)
     *
     * @param string $content
     * @return mixed
     */
    protected function cleanTemplate(string $content)
    {
        return preg_replace('#^[\t\s\r]*\n+#m', '', $content);
    }
}
