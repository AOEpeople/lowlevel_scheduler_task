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
 * This class defines the TYPO3 scheduler task to trigger the low-level cleanup scripts.
 *
 * @access public
 * @package TYPO3
 * @subpackage tx_lowlevelschedulertask
 *
 * @author Chetan Thapliyal <chetan.thapliyal@aoemedia.de>
 * $Id$
 */
class tx_lowlevelschedulertask_task_systemcleaner extends Tx_AoeTools_AbstractExtbaseSchedulerTask {

	/**
	 * Extension key
	 *
	 * @var string
	 */
	private $extKey = 'lowlevel_scheduler_task';

	/**
	 * Task namespace
	 *
	 * @var string
	 */
	private $namespace = 'LowlevelSchedulerTask.Task.SystemCleaner';

	/**
	 * Executes the task.
	 *
	 * @return boolean
	 */
	public function _execute() {
		$this->initCliArguments();

		try {
			/** @var $lowLevelCleaner tx_lowlevel_cleaner_core */
			$lowLevelCleaner = $this->getExtbaseObjectManager()->create('tx_lowlevel_cleaner_core');
			ob_start();
			$lowLevelCleaner->cli_main(array());
			$output = ob_get_contents();
			ob_end_clean();
		} catch(Exception $e) {
			$this->log(get_class($e).': '.$e->getMessage(), t3lib_div::SYSLOG_SEVERITY_FATAL);
			return FALSE;
		}

		$this->log($output, t3lib_div::SYSLOG_SEVERITY_INFO);
		return TRUE;
	}

	/**
	 * Initializes command-line arguments expected by low-level cleaner scripts.
	 *
	 * @return void
	 */
	protected function initCliArguments() {
		$params = array(
			PATH_typo3 . 'cli_dispatch.phpsh',
			$this->toolKey,
		);

		if ($this->toolOptions) {
			$params = array_merge($params, t3lib_div::trimExplode(' ', $this->toolOptions, TRUE));
		}

		$_SERVER['argv'] = $params;
		$_SERVER['argc'] = count($params);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return string Information to display
	 */
	public function getAdditionalInformation() {
		$additionalInfo = '';
		if (property_exists($this, 'toolKey')) {
			$additionalInfo = $this->toolKey;
		}
		if (property_exists($this, 'toolOptions') && $this->toolOptions) {
			$additionalInfo .= ' ' . $this->toolOptions;
		}
		return $additionalInfo;
	}

	/**
	 * Logs a message to the TYPO3 sys_log table.
	 *
	 * @param  string   $message
	 * @param  integer  $severity
	 * @return void
	 */
	private function log($message, $severity) {
		if (is_object($GLOBALS['BE_USER'])) {
			$GLOBALS['BE_USER']->simplelog(sprintf('[%s] %s', $this->namespace, $message), $this->extKey, $severity);
		}
	}
}
