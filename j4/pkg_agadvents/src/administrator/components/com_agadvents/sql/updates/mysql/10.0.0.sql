ALTER TABLE `#__agadvents_details` ADD COLUMN  `access` int(10) unsigned NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__agadvents_details` ADD KEY `idx_access` (`access`);
