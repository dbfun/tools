CREATE TABLE `wdi` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `branch` varchar(32) NOT NULL,
  `sha1` char(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `wdi_p` (
  `project_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `statistic` MEDIUMTEXT NOT NULL,
  PRIMARY KEY (`project_id`),
  UNIQUE KEY `idx_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `wdi_push` (
  `project_id` INT(11) UNSIGNED NOT NULL,
  `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `old_branch` VARCHAR(32) DEFAULT NULL,
  `new_branch` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`project_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE VIEW `wdi_view` AS select wdi_p.name AS `name`, wdi.* FROM (`wdi` JOIN `wdi_p` USING (`project_id`));