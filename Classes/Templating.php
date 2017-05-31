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

use TYPO3\CMS\Core\Service\MarkerBasedTemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This class provides methods to generate the templates reports
 *
 * @author         CERDAN Yohann <cerdanyohann@yahoo.fr>
 * @package        TYPO3
 */
class Templating
{
    /**
     * Template object for frontend functions
     */
    public $templateContent = null;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Loads a template file
     *
     * @param string  $templateFile
     * @param boolean $debug
     * @return boolean
     */
    public function initTemplate($templateFile, $debug = false)
    {
        $templateAbsPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($templateFile);
        if ($templateAbsPath !== null) {
            $this->templateContent = \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($templateAbsPath);
            if ($debug === true) {
                if ($this->templateContent === null) {
                    \TYPO3\CMS\Core\Utility\DebugUtility::debug('Check the path template or the rights', 'Error');
                }
                \TYPO3\CMS\Core\Utility\DebugUtility::debug($this->templateContent, 'Content of ' . $templateFile);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Template rendering for subdatas and principal datas
     *
     * @param array   $templateMarkers
     * @param string  $templateSection
     * @param boolean $debug
     * @return string HTML code
     */
    public function renderAllTemplate($templateMarkers, $templateSection, $debug = false)
    {
        // Check if the template is loaded
        if (!$this->templateContent) {
            return false;
        }

        // Check argument
        if (!is_array($templateMarkers)) {
            return false;
        }

        if ($debug === true) {
            \TYPO3\CMS\Core\Utility\DebugUtility::debug($templateMarkers, 'Markers for ' . $templateSection);
        }

        $content = '';

        if (is_array($templateMarkers[0])) {
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
    public function renderSingle($templateMarkers, $templateSection)
    {
        $subParts = $this->getSubpart($this->templateContent, $templateSection);

        foreach ($templateMarkers as $subPart => $subContent) {
            if (preg_match_all('/(<!--).*?' . $subPart . '.*?(-->)/', $subParts, $matches) >= 2) {
                $subParts = $this->substituteSubpart($subParts, $subPart, $subContent);
            }
        }

        $content = $this->substituteMarkerArray($subParts, $templateMarkers);

        return $content;
    }

    /**
     * Substitutes markers in a template. Usually, this is just a wrapper method
     * around the \TYPO3\CMS\Core\Html\HtmlParser::substituteMarkerArray method. However, this
     * method is only available from TYPO3 4.2.
     *
     * @param  string $template The template
     * @param  array  $marker   The markers that are to be replaced
     * @return string           The template with replaced markers
     */
    protected function substituteMarkerArray($template, $marker)
    {
        $templateService = GeneralUtility::makeInstance(MarkerBasedTemplateService::class);
        return $templateService->substituteMarkerArray($template, $marker, '', false, false);
    }

    /**
     * Replaces a subpart in a template with content. This is just a wrapper method
     * around the substituteSubpart method of the \TYPO3\CMS\Core\Html\HtmlParser class.
     *
     * @param  string $template The tempalte
     * @param  string $subpart  The subpart name
     * @param  string $replace  The subpart content
     * @return string           The template with replaced subpart.
     */
    protected function substituteSubpart($template, $subpart, $replace)
    {
        $templateService = GeneralUtility::makeInstance(MarkerBasedTemplateService::class);
        return $templateService->substituteSubpart($template, $subpart, $replace, true, false);
    }

    /**
     * Gets a subpart from a template. This is just a wrapper around the getSubpart
     * method of the \TYPO3\CMS\Core\Html\HtmlParser class.
     *
     * @param  string $template The template
     * @param  string $subpart  The subpart name
     * @return string           The subpart
     */
    protected function getSubpart($template, $subpart)
    {
        $templateService = GeneralUtility::makeInstance(MarkerBasedTemplateService::class);
        return $templateService->getSubpart($template, $subpart);
    }

    /**
     * Clean a template string (remove blank lines...)
     *
     * @param  string $content
     * @return mixed
     */
    protected function cleanTemplate($content)
    {
        return preg_replace('/^[\t\s\r]*\n+/m', '', $content);
    }
}

?>