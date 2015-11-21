DROP PROCEDURE IF EXISTS `WDI_STATINSERT`;
DELIMITER $$
CREATE PROCEDURE `WDI_STATINSERT`(name VARCHAR(64), stat MEDIUMTEXT)
BEGIN
      SET @name = name;
      SET @stat = stat;
      
      
      -- Get project ID
      INSERT IGNORE INTO `wdi_p` (`name`) VALUES (@name);
      PREPARE stmt_pid FROM 'SET @pid = (SELECT `project_id` FROM `wdi_p` WHERE `name` = ? LIMIT 1)';
      EXECUTE stmt_pid USING @name;
      DEALLOCATE PREPARE stmt_pid;
      
      PREPARE stmt_stat FROM 'UPDATE `wdi_p` SET `statistic` = ? WHERE `project_id` = ? LIMIT 1';
      EXECUTE stmt_stat USING @stat, @pid;
      DEALLOCATE PREPARE stmt_stat;
    END $$
DELIMITER ;