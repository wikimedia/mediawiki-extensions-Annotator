-- Rename current table to temporary name
ALTER TABLE /*_*/annotator RENAME TO /*_*/temp_annotator_rename_user_id;

CREATE TABLE IF NOT EXISTS /*_*/annotator (
  annotation_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  annotation_json text NOT NULL,
  rev_id int(10) unsigned NOT NULL,
  annotation_user_id int(10) unsigned NOT NULL
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/annotator
	( annotation_id, annotation_json, rev_id, annotation_user_id )
SELECT
	annotation_id, annotation_json, rev_id, user_id
FROM
	/*_*/temp_annotator_rename_user_id;

--Drop the original table--
DROP TABLE /*_*/temp_annotator_rename_user_id;

--recreate indexes--
CREATE INDEX /*i*/annotator_rev_id ON /*_*/annotator (rev_id);