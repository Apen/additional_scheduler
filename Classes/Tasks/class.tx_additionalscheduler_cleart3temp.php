<?php

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

class tx_additionalscheduler_cleart3temp extends \TYPO3\CMS\Scheduler\Task\AbstractTask {
    protected $stats = array();

    /**
     * This is the main method that is called when a task is executed
     * It MUST be implemented by all classes inheriting from this one
     * Note that there is no error handling, errors and failures are expected
     * to be handled and logged by the client implementations.
     * Should return true on successful execution, false on error.
     *
     * @return boolean    Returns true on successful execution, false on error
     */
    public function execute() {
        $this->stats['nbfiles'] = 0;
        $this->stats['nbfilessize'] = 0;
        $this->stats['nbfilesdeleted'] = 0;
        $this->stats['nbfilesdeletedsize'] = 0;
        $this->stats['nbdirectories'] = 0;

        $this->emptyDirectory(PATH_site . 'typo3temp', $this->nbdays);

        if (defined('TYPO3_cliMode') && TYPO3_cliMode) {
            echo 'Nb files: ' . $this->stats['nbfiles'] . ' (' . $this->stats['nbfilessize'] . ' ko)' . LF;
            echo 'Nb files deleted: ' . $this->stats['nbfilesdeleted'] . ' (' . $this->stats['nbfilesdeletedsize'] . ' ko)' . LF;
            echo 'Nb directories: ' . $this->stats['nbdirectories'] . LF;
        }

        return TRUE;
    }

    /**
     * Delete all files of a directory older than x days
     *
     * @param $dirname
     * @param $nbdays
     * @return bool
     */
    public function emptyDirectory($dirname, $nbdays) {
        if ((is_dir($dirname)) && (($dir_handle = opendir($dirname)) !== FALSE)) {
            while ($file = readdir($dir_handle)) {
                if ($file != "." && $file != "..") {
                    $absoluteFileName = $dirname . "/" . $file;
                    if (!is_dir($absoluteFileName)) {
                        $size = round(filesize($absoluteFileName) / 1024);
                        $this->stats['nbfiles']++;
                        $this->stats['nbfilessize'] += $size;
                        if ((time() - filemtime($absoluteFileName)) >= ($nbdays * 86400)) {
                            if (is_writable($absoluteFileName)) {
                                if (empty($this->dirfilter) === TRUE) {
                                    $this->stats['nbfilesdeleted']++;
                                    $this->stats['nbfilesdeletedsize'] += $size;
                                    @unlink($absoluteFileName);
                                } else {
                                    if (preg_match('#' . $this->dirfilter . '#', $absoluteFileName) === 0) {
                                        $this->stats['nbfilesdeleted']++;
                                        $this->stats['nbfilesdeletedsize'] += $size;
                                        @unlink($absoluteFileName);
                                    } else {
                                        // dont delete files with this mask
                                    }
                                }
                            } else {
                                // cannot delete files
                            }
                        }
                    } else {
                        $this->stats['nbdirectories']++;
                        $this->emptyDirectory($absoluteFileName, $nbdays);
                    }
                }
            }
            closedir($dir_handle);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * This method is designed to return some additional information about the task,
     * that may help to set it apart from other tasks from the same class
     * This additional information is used - for example - in the Scheduler's BE module
     * This method should be implemented in most task classes
     *
     * @return    string    Information to display
     */
    public function getAdditionalInformation() {
        return $this->nbdays . ' days';
    }

}

?>
