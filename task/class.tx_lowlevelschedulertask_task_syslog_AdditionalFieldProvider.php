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
 * class.tx_lowlevelschedulertask_task_syslog_AdditionalFieldProvider.php
 *
 * @author Michael Klapper <klapper@aoemedia.de>
 * @copyright Copyright (c) 2009, AOE media GmbH <dev@aoemedia.de>
 * @version $Id$
 * @date $Date$
 * @since 28.10.2009 - 10:15:09
 * @package TYPO3
 * @subpackage tx_lowlevelschedulertask
 * @access public
 */
class tx_lowlevelschedulertask_task_syslog_AdditionalFieldProvider implements tx_scheduler_AdditionalFieldProvider {

	/**
	 * This method is used to define new fields for adding or editing a task
	 * In this case, it adds an email field
	 *
	 * @param	array					$taskInfo: reference to the array containing the info used in the add/edit form
	 * @param	object					$task: when editing, reference to the current task object. Null when adding.
	 * @param	tx_scheduler_Module		$parentObject: reference to the calling object (Scheduler's BE module)
	 * @return	array					Array containg all the information pertaining to the additional fields
	 *									The array is multidimensional, keyed to the task class name and each field's id
	 *									For each field it provides an associative sub-array with the following:
	 *										['code']		=> The HTML code for the field
	 *										['label']		=> The label of the field (possibly localized)
	 *										['cshKey']		=> The CSH key for the field
	 *										['cshLabel']	=> The code of the CSH label
	 */
	public function getAdditionalFields(array &$taskInfo, $task, tx_scheduler_Module $schedulerModule) {

			// Initialize extra field value
		if (empty($taskInfo['email'])) {
			if ($schedulerModule->CMD == 'add') {
					// In case of new task and if field is empty, set default email address
				$taskInfo['email'] = $GLOBALS['BE_USER']->user['email'];

			} elseif ($schedulerModule->CMD == 'edit') {
					// In case of edit, and editing a test task, set to internal value if not data was submitted already
				$taskInfo['email'] = $task->email;
			} else {
					// Otherwise set an empty value, as it will not be used anyway
				$taskInfo['email'] = '';
			}
		}
			// Initialize extra field value
		if (empty($taskInfo['logLevel'])) {
			if ($schedulerModule->CMD == 'add') {
				$taskInfo['logLevel'] = $GLOBALS['BE_USER']->user['logLevel'];

			} elseif ($schedulerModule->CMD == 'edit') {
				$taskInfo['logLevel'] = $task->logLevel;
			} else {
				$taskInfo['logLevel'] = '';
			}
		}

			// Initialize extra field value
		if (empty($taskInfo['hours'])) {
			if ($schedulerModule->CMD == 'add') {
				$taskInfo['hours'] = $GLOBALS['BE_USER']->user['hours'];

			} elseif ($schedulerModule->CMD == 'edit') {
				$taskInfo['hours'] = $task->hours;
			} else {
				$taskInfo['hours'] = '';
			}
		}

			// Write the code for the field
		$fieldID = 'task_email';
		$fieldCode = '<input type="text" name="tx_scheduler[email]" id="' . $fieldID . '" value="' . htmlentities($taskInfo['email']) . '" size="30" />';
		$additionalFields = array();
		$additionalFields[$fieldID] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:lowlevel_scheduler_task/task/locallang.xml:syslog.email'
		);

			// Write the code for the field
		$fieldID = 'task_hours';
		$fieldCode = '<input type="text" name="tx_scheduler[hours]" id="' . $fieldID . '" value="' . htmlentities($taskInfo['hours']) . '" size="10" />';
		$additionalFields[$fieldID] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:lowlevel_scheduler_task/task/locallang.xml:syslog.hours'
		);


		$fieldID = 'task_logLevel';
		$fieldCode = '<select name="tx_scheduler[logLevel]" id="' . $fieldID . '" value="' . htmlentities($taskInfo['logLevel']) . '">'
		           . '<option value="1">Error</option>'
		           . '<option value="0">All</option>'
		           . '</select>';

		$additionalFields[$fieldID] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:lowlevel_scheduler_task/task/locallang.xml:syslog.logLevel'
		);
		return $additionalFields;
	}

	/**
	 * Validates the additional fields' values
	 *
	 * @param	array					An array containing the data submitted by the add/edit task form
	 * @param	tx_scheduler_Module		Reference to the scheduler backend module
	 * @return	boolean					True if validation was ok (or selected class is not relevant), false otherwise
	 */
	public function validateAdditionalFields(array &$submittedData, tx_scheduler_Module $schedulerModule) {
		$submittedData['email'] = trim($submittedData['email']);
		$result = false;

		if (! t3lib_div::validEmail($submittedData['email'])) {
			$schedulerModule->addMessage($GLOBALS['LANG']->sL('LLL:EXT:lowlevel_scheduler_task/task/locallang.xml:msg.noEmail'), t3lib_FlashMessage::ERROR);
		} else {
			$result = true;
		}

		if (t3lib_div::intval_positive($submittedData['hours']) === 0) {
			$schedulerModule->addMessage($GLOBALS['LANG']->sL('LLL:EXT:lowlevel_scheduler_task/task/locallang.xml:msg.noHours'), t3lib_FlashMessage::ERROR);
			$result = false;
		} else {
			$result = ($result === false) ? false : true;
		}

		return $result;
	}

	/**
	 * Takes care of saving the additional fields' values in the task's object
	 *
	 * @param	array					An array containing the data submitted by the add/edit task form
	 * @param	tx_scheduler_Module		Reference to the scheduler backend module
	 * @return	void
	 */
	public function saveAdditionalFields(array $submittedData, tx_scheduler_Task $task) {
		$task->email    = $submittedData['email'];
		$task->logLevel = $submittedData['logLevel'];
		$task->hours    = $submittedData['hours'];
	}
}