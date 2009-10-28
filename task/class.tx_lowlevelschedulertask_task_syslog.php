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
 * @author Michael Klapper <klapper@aoemedia.de>
 * @copyright Copyright (c) 2009, AOE media GmbH <dev@aoemedia.de>
 * @version $Id$
 * @date $Date$
 * @since 28.10.2009 - 10:11:44
 * @package TYPO3
 * @subpackage tx_lowlevelschedulertask
 * @access public
 */
class tx_lowlevelschedulertask_task_syslog extends tx_scheduler_Task {

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
		$sucess = false;
		if (!empty($this->email)) {
			$subject   = 'SCHEDULER LOWLEVEL CLEANER SYSLOG TASK';
//			$logData   = $this->getLogEntries();
			$logString = 'Collect data ... ';

			$mailBody =
				$subject . chr(10)
				. '- - - - - - - - - - - - - - - -' . chr(10)
				. 'Sitename: ' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] . chr(10)
				. 'tstamp: ' . date('Y-m-d H:i:s') . ' [' . time() . ']' . chr(10)
				. 'LOG DATA' . chr(10)
				. '- - - - - - - - - - - - - - - -' . chr(10)
				. $logString
				. '- - - - - - - - - - - - - - - -' . chr(10)
				. 'End of message.' . chr(10);

				// Prepare mailer and send the mail
			$mailer = t3lib_div::makeInstance('t3lib_htmlmail');
			$mailer->from_email = $this->email;
			$mailer->from_name = $subject;
			$mailer->replyto_email = $this->email;
			$mailer->replyto_name = $subject;
			$mailer->subject = $subject;
			$mailer->setPlain($mailer->encodeMsg($mailBody));
			$mailer->setRecipient($this->email);
			$mailer->setHeaders();
			$mailer->setContent();
			$success = $mailer->sendtheMail();
		} else {
				// No email defined, just log the task
			t3lib_div::devLog('[tx_scheduler_TestTask]: No email address given', 'scheduler', 2);
		}
		return $sucess;
	}

	/**
	 * Get all log entries from syslog from the last 25h.
	 *
	 * @access protected
	 * @return array
	 *
	 * @author Michael Klapper <michael.klapper@aoemedia.de>
	 */
	protected function getLogEntries() {
		global $TYPO3_DB; /* @var $TYPO3_DB t3lib_db */

			// Initialize result array:
		$resultArray = array(
			'message' => $this->cli_help['name'].chr(10).chr(10).$this->cli_help['description'],
			'headers' => array(
				'listing' => array('','',1),
				'allDetails' => array('','',0),
			),
			'listing' => array(),
			'allDetails' => array()
		);

		$rows = $TYPO3_DB->exec_SELECTgetRows(
			'*',
			'sys_log',
			'tstamp>' . ($GLOBALS['EXEC_TIME'] - 25 * 3600) . ' AND error = 1'
		);

		foreach($rows as $r)	{
			$l = unserialize($r['log_data']);
			$explained = '#'.$r['uid'].' '.t3lib_BEfunc::datetime($r['tstamp']).' USER['.$r['userid'].']: '.sprintf($r['details'],$l[0],$l[1],$l[2],$l[3],$l[4],$l[5]);
			$resultArray['listing'][$r['uid']] = $explained;
			$resultArray['allDetails'][$r['uid']] = array($explained,t3lib_div::arrayToLogString($r,'uid,userid,action,recuid,tablename,recpid,error,tstamp,type,details_nr,IP,event_pid,NEWid,workspace'));
		}

		return $resultArray;
	}
}