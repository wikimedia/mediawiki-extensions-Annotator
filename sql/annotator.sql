--
-- Table structure for table `annotator`
--

CREATE TABLE IF NOT EXISTS /*_*/annotator (
  annotation_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  annotation_json text NOT NULL,
  annotation_rev_id int(10) unsigned NOT NULL,
  annotation_user_id int(10) unsigned NOT NULL
) /*$wgDBTableOptions*/;

