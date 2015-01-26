<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009 AOE media (dev@aoemedia.de)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * {@inheritdoc}
 *
 * class.tx_lowlevelschedulertask_task_cachecleaner_AdditionalFieldProvider.php
 *
 * @author Max Beer <max.beer@aoemedia.de>
 * @copyright Copyright (c) 2009, AOE media GmbH <dev@aoemedia.de>
 * @version $Id$
 * @date $Date$
 * @since 08.11.2012 - 10:15:09
 * @package TYPO3
 * @subpackage tx_lowlevelschedulertask
 * @access public
 */
class tx_lowlevelschedulertask_task_realurl_AdditionalFieldProvider implements tx_scheduler_AdditionalFieldProvider {

	/**
	 * This method is used to define new fields for adding or editing a task
	 * In this case, it adds an email field
	 *
	 * @param array $taskInfo: reference to the array containing the info used in the add/edit form
	 * @param \TYPO3\CMS\Scheduler\Task\AbstractTask $task: when editing, reference to the current task object. Null when adding.
	 * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject: reference to the calling object (Scheduler's BE module)
	 * @return array Array containg all the information pertaining to the additional fields
	 *				 The array is multidimensional, keyed to the task class name and each field's id
	 *				 For each field it provides an associative sub-array with the following:
	 *				 ['code']		=> The HTML code for the field
	 *				 ['label']		=> The label of the field (possibly localized)
	 *				 ['cshKey']		=> The CSH key for the field
	 *				 ['cshLabel']	=> The code of the CSH label
	 */
	public function getAdditionalFields(array &$taskInfo, $task, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule) {

		// Initialize extra field value
		if (empty($taskInfo['urlEncodeCacheExpirationDays'])) {
			if ($schedulerModule->CMD == 'add') {
				// Default value is two days
				$taskInfo['urlEncodeCacheExpirationDays'] = 2;
			} elseif ($schedulerModule->CMD == 'edit') {
				// In case of edit, and editing a test task, set to internal value if not data was submitted already
				$taskInfo['urlEncodeCacheExpirationDays'] = $task->urlEncodeCacheExpirationDays;
			} else {
				// Otherwise set an empty value, as it will not be used anyway
				$taskInfo['urlEncodeCacheExpirationDays'] = '';
			}
		}

		// Write the code for the field
		$fieldID = 'task_urlEncodeCacheExpirationDays';
		$fieldCode = '<input type="text" name="tx_scheduler[urlEncodeCacheExpirationDays]" id="' . $fieldID . '" value="' . htmlentities($taskInfo['urlEncodeCacheExpirationDays']) . '" size="30" />';
		$additionalFields = array();
		$additionalFields[$fieldID] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:lowlevel_scheduler_task/task/locallang.xml:realurl.urlEncodeCacheExpirationDays'
		);

		return $additionalFields;
	}

	/**
	 * Validates the additional fields' values
	 *
	 * @param array An array containing the data submitted by the add/edit task form
	 * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController Reference to the scheduler backend module
	 * @return boolean True if validation was ok (or selected class is not relevant), false otherwise
	 */
	public function validateAdditionalFields(array &$submittedData, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule) {
		$result = false;

		if (t3lib_div::intval_positive($submittedData['urlEncodeCacheExpirationDays']) === 0) {
			$schedulerModule->addMessage($GLOBALS['LANG']->sL('LLL:EXT:lowlevel_scheduler_task/task/locallang.xml:msg.invalidUrlEncodeCacheExpirationDays'), t3lib_FlashMessage::ERROR);
			$result = false;
		} else {
			$result = true;
		}

		return $result;
	}

	/**
	 * Takes care of saving the additional fields' values in the task's object
	 *
	 * @param array An array containing the data submitted by the add/edit task form
	 * @param \TYPO3\CMS\Scheduler\Task\AbstractTask Reference to the scheduler backend module
	 * @return void
	 */
	public function saveAdditionalFields(array $submittedData, \TYPO3\CMS\Scheduler\Task\AbstractTask $task) {
		$task->urlEncodeCacheExpirationDays = $submittedData['urlEncodeCacheExpirationDays'];
	}
}