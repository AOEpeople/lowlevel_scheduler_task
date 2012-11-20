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
 * @author Max Beer <max.beer@aoemedia.de>
 * @copyright Copyright (c) 2009, AOE media GmbH <dev@aoemedia.de>
 * @version $Id$
 * @date $Date$
 * @since 07.11.2012 - 16:11:44
 * @package TYPO3
 * @subpackage tx_lowlevelschedulertask
 * @access public
 */
class tx_lowlevelschedulertask_task_realurl extends tx_scheduler_Task {

	/**
	 * @var string return message
	 */
	protected $returnMessage;

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
	 * @author Max Beer <max.beer@aoemedia.de>
	 */
	public function execute() {

		$this->clearUrlEncodeCache();

		if ($this->returnMessage && TYPO3_MODE === 'BE') {
			$flashMessage = t3lib_div::makeInstance('t3lib_FlashMessage', $this->returnMessage, '', t3lib_FlashMessage::OK);
			t3lib_FlashMessageQueue::addMessage($flashMessage);
		}

		return true;
	}

	/**
	 * Clears expired records in tx_realurl_urlencodecache
	 *
	 * @access protected
	 *
	 * @author Max beer <max.beer@aoemedia.de>
	 */
	protected function clearUrlEncodeCache() {
		global $TYPO3_DB; /* @var $TYPO3_DB t3lib_db */

		if ($this->urlEncodeCacheExpirationDays > 0) {
			$expirationDays = (int)$this->urlEncodeCacheExpirationDays;
		} else {
			$expirationDays = 2;
		}

		$expirationPeriod = $expirationDays * 24 * 3600;
		$expireTime = time() - $expirationPeriod;

		$TYPO3_DB->exec_DELETEquery('tx_realurl_urlencodecache', 'tstamp < ' . $expireTime);

		$this->returnMessage = sprintf('Deleted %s rows from table tx_realurl_urlencodecache', $TYPO3_DB->sql_affected_rows());
	}

	/**
	 * Get return message
	 * This will be read from EXT:scheduler_timeline
	 *
	 * @access public
	 * @return string
	 */
	public function getReturnMessage() {
		return $this->returnMessage;
	}
}
