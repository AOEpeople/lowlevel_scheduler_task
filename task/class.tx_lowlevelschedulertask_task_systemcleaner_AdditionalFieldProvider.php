<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 AOE media (dev@aoemedia.de)
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
 * This class provides the additional fields for system cleaner scheduler task.
 *
 * @access public
 * @package TYPO3
 * @subpackage tx_lowlevelschedulertask
 *
 * @author Chetan Thapliyal <chetan.thapliyal@aoemedia.de>
 * $Id$
 */
class tx_lowlevelschedulertask_task_systemcleaner_AdditionalFieldProvider extends Tx_AoeTools_AbstractSchedulerAdditionalFields {

	/**
	 * Description of additional fields.
	 *
	 * @var array
	 */
	protected $fields;

	/**
	 * Prefix for language label keys. This is prepended to the additional field name to get the complete label key.
	 *
	 * @var string
	 */
	protected $lllPrefix = 'LLL:EXT:lowlevel_scheduler_task/task/locallang.xml:scheduler.task.systemcleaner.additionalfields.';

	/**
	 * Constructor
	 */
	public function __construct() {
		$toolKeys = array_keys($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['lowlevel']['cleanerModules']);
		$this->fields = array(
				// key corresponding to the tool/script to execute
			'toolKey' => array(
				'type' => 'select',
				'options' => array_combine($toolKeys, $toolKeys)
			),
			'toolOptions' => array('type' => 'input'),
				// Next task to execute on successful execution of current task
			'successor' => array('type' => 'select_task')
		);
	}
}
