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

require_once t3lib_extMgm::extPath('lowlevel') . 'class.tx_lowlevel_cleaner_core.php';

/**
 * {@inheritdoc}
 *
 * @author Michael Klapper <klapper@aoemedia.de>
 * @copyright Copyright (c) 2009, AOE media GmbH <dev@aoemedia.de>
 * @version $Id$
 * @date $Date$
 * @since 28.10.2009 - 10:11:44
 * @package TYPO3
 * @subpackage tx_lowlevelschedulertask
 * @access public
 */
class tx_lowlevelschedulertask_task_cleanup extends tx_scheduler_Task {

	/**
	 * This is the main method that is called when a task is executed
	 * It MUST be implemented by all classes inheriting from this one
	 * Note that there is no error handling, errors and failures are expected
	 * to be handled and logged by the client implementations.
	 * Should return true on successful execution, false on error.
	 *
	 * @access public
	 * @return boolean	Returns true on successful execution, false on error
	 *
	 * @author Michael Klapper <michael.klapper@aoemedia.de>
	 */
	public function execute() {
		global $TYPO3_CONF_VARS;
		$cleanerKeys = array (
			'orphan_records'          => array('-r', '--refindex', 'update', '--AUTOFIX', '--YES'),
			'versions'                => array('-r', '--refindex', 'ignore', '--AUTOFIX', '--YES'),
			'tx_templavoila_unusedce' => array('-r', '--refindex', 'update', '--AUTOFIX', '--YES'),
			'tx_templavoila_unusedce' => array('-r', '--refindex', 'update', '--AUTOFIX', '--YES'),
			'missing_files'           => array('-r', '--refindex', 'ignore', '--AUTOFIX', '--YES'),
			'double_files'            => array('-r', '--refindex', 'ignore', '--AUTOFIX', '--YES'),
			'lost_files'              => array('-r', '--refindex', 'ignore', '--AUTOFIX', '--YES')
		);
		$LowlevelCleanerCore = t3lib_div::makeInstance('tx_lowlevel_cleaner_core'); /* @var $LowlevelCleanerCore tx_lowlevel_cleaner_core */

		ob_start();
		foreach ($cleanerKeys as $cleanerKey => $cleanerParams) {

			if (!array_key_exists($cleanerKey, $TYPO3_CONF_VARS['EXTCONF']['lowlevel']['cleanerModules']))
				continue;

			array_unshift($cleanerParams, $_SERVER['argv'][0], $cleanerKey);
			$LowlevelCleanerCore->cli_main($cleanerParams);
		}
		ob_end_clean();

		return true;
	}
}