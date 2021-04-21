ALTER TABLE `#__agadvents_details` ADD COLUMN  `catid` int(11) NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__agadvents_details` ADD KEY `idx_catid` (`catid`);
