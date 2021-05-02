CREATE TABLE IF NOT EXISTS `#__agadvents_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',

  `fulltext` mediumtext NOT NULL,
  `fulltext_no` mediumtext NOT NULL,

  `cords` mediumtext NOT NULL,
  `cordsimagemap` mediumtext NOT NULL,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `#__agadvents_details` ADD COLUMN  `access` int(10) unsigned NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__agadvents_details` ADD KEY `idx_access` (`access`);

ALTER TABLE `#__agadvents_details` ADD COLUMN  `catid` int(11) NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__agadvents_details` ADD COLUMN  `state` tinyint(3) NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__agadvents_details` ADD KEY `idx_catid` (`catid`);

ALTER TABLE `#__agadvents_details` ADD COLUMN  `published` tinyint(1) NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__agadvents_details` ADD COLUMN  `publish_up` datetime AFTER `alias`;

ALTER TABLE `#__agadvents_details` ADD COLUMN  `publish_down` datetime AFTER `alias`;

ALTER TABLE `#__agadvents_details` ADD KEY `idx_state` (`published`);

ALTER TABLE `#__agadvents_details` ADD COLUMN  `language` char(7) NOT NULL DEFAULT '*' AFTER `alias`;

ALTER TABLE `#__agadvents_details` ADD KEY `idx_language` (`language`);

ALTER TABLE `#__agadvents_details` ADD COLUMN  `ordering` int(11) NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__agadvents_details` ADD COLUMN  `params` text NOT NULL AFTER `alias`;

ALTER TABLE `#__agadvents_details` ADD COLUMN `checked_out` int(10) unsigned NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__agadvents_details` ADD COLUMN `checked_out_time` datetime AFTER `alias`;

ALTER TABLE `#__agadvents_details` ADD KEY `idx_checkout` (`checked_out`);

ALTER TABLE `#__agadvents_details` ADD COLUMN  `featured` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT 'Set if agadvent is featured.';

ALTER TABLE `#__agadvents_details` ADD KEY `idx_featured_catid` (`featured`,`catid`);
