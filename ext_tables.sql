#
# Table structure for table 'tx_realurl_urlencodecache'
# Adding index for tstamp to improve performance of realurl scheduler
#
CREATE TABLE tx_realurl_urlencodecache (
	tstamp int(11) DEFAULT '0' NOT NULL,
	KEY tstamp (tstamp),
);