ALTER TABLE /*_*/annotator ADD COLUMN annotation_user_text varchar(255) binary NOT NULL default '';
UPDATE /*_*/annotator
SET annotation_user_text =
	(
		SELECT user_name FROM /*_*/user
		WHERE annotation_user_id = user_id
	);