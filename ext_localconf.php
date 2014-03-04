<?php
if(!isset($_EXTKEY)) {
	$_EXTKEY = 'lowlevel_scheduler_task';
	$extkeyWasMocked = TRUE;
} else {
	$extkeyWasMocked = FALSE;
}

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
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_lowlevelschedulertask_task_cachecleaner'] = array(
		'extension'        => $_EXTKEY,
		'title'            => 'LLL:EXT:' . $_EXTKEY . '/locallang_db.xml:lowlevelconnect_task_cachecleaner.name',
		'description'      => 'LLL:EXT:' . $_EXTKEY . '/locallang_db.xml:lowlevelconnect_task_cachecleaner.description',
		'additionalFields' => 'tx_lowlevelschedulertask_task_cachecleaner_AdditionalFieldProvider'
	);

	if (t3lib_extMgm::isLoaded('aoe_tools')) {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_lowlevelschedulertask_task_systemcleaner'] = array(
			'extension'        => $_EXTKEY,
			'title'            => 'LLL:EXT:' . $_EXTKEY . '/locallang_db.xml:lowlevelschedulertask_task_systemcleaner.name',
			'description'      => 'LLL:EXT:' . $_EXTKEY . '/locallang_db.xml:lowlevelschedulertask_task_systemcleaner.description',
			'additionalFields' => 'tx_lowlevelschedulertask_task_systemcleaner_AdditionalFieldProvider'
		);
	}
}

if (t3lib_extMgm::isLoaded('realurl')) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_lowlevelschedulertask_task_realurl'] = array(
		'extension'        => $_EXTKEY,
		'title'            => 'LLL:EXT:' . $_EXTKEY . '/locallang_db.xml:lowlevelconnect_task_realurl.name',
		'description'      => 'LLL:EXT:' . $_EXTKEY . '/locallang_db.xml:lowlevelconnect_task_realurl.description',
		'additionalFields' => 'tx_lowlevelschedulertask_task_realurl_AdditionalFieldProvider'
	);
}

if($extkeyWasMocked) {
	unset($_EXTKEY);
}

