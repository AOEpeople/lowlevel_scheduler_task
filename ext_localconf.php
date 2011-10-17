<?php
global $_EXTKEY;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_lowlevelschedulertask_task_updateRefindex'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:' . $_EXTKEY . '/locallang_db.xml:lowlevelconnect_task_updateRefindex.name',
	'description'      => 'LLL:EXT:' . $_EXTKEY . '/locallang_db.xml:lowlevelconnect_task_updateRefindex.description',
	'additionalFields' => 'tx_lowlevelschedulertask_task_updateRefindex_AdditionalFieldProvider'
);
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_lowlevelschedulertask_task_cleanup'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:' . $_EXTKEY . '/locallang_db.xml:lowlevelconnect_task_cleanup.name',
	'description'      => 'LLL:EXT:' . $_EXTKEY . '/locallang_db.xml:lowlevelconnect_task_cleanup.description',
	'additionalFields' => 'tx_lowlevelschedulertask_task_cleanup_AdditionalFieldProvider'
);
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_lowlevelschedulertask_task_syslog'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:' . $_EXTKEY . '/locallang_db.xml:lowlevelconnect_task_syslog.name',
	'description'      => 'LLL:EXT:' . $_EXTKEY . '/locallang_db.xml:lowlevelconnect_task_syslog.description',
	'additionalFields' => 'tx_lowlevelschedulertask_task_syslog_AdditionalFieldProvider'
);


if (t3lib_extMgm::isLoaded('cachecleaner')) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_lowlevelschedulertask_task_cachecleaner] = array(
		'extension'        => $_EXTKEY,
		'title'            => 'LLL:EXT:' . $_EXTKEY . '/locallang_db.xml:lowlevelconnect_task_cachecleaner.name',
		'description'      => 'LLL:EXT:' . $_EXTKEY . '/locallang_db.xml:lowlevelconnect_task_cachecleaner.description',
		'additionalFields' => 'tx_lowlevelschedulertask_task_cachecleaner_AdditionalFieldProvider'
	);
}