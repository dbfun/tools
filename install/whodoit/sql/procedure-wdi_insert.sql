DROP PROCEDURE IF EXISTS `WDI_INSERT`;
DELIMITER $$
CREATE PROCEDURE `WDI_INSERT`(name VARCHAR(64), branch VARCHAR(32), sha1 CHAR(40))
BEGIN
      SET @name = name;
      SET @branch = branch;
      SET @sha1 = sha1;
      
      -- Get/create project ID
      INSERT IGNORE INTO `wdi_p` (`name`) VALUES (@name);
      PREPARE stmt_pid FROM 'SET @pid = (SELECT `project_id` FROM `wdi_p` WHERE `name` = ? LIMIT 1)';
      EXECUTE stmt_pid USING @name;
      DEALLOCATE PREPARE stmt_pid;
      
      -- Get last SHA and last BRANCH from project
      PREPARE stmt_sha FROM 'SELECT @sha1_last:=`sha1`, @branch_last:=`branch` FROM `wdi` WHERE `project_id` = ? ORDER BY `time` DESC LIMIT 1';
      EXECUTE stmt_sha USING @pid;
      DEALLOCATE PREPARE stmt_sha;
      
      -- Insert into `wdi` new entities if HEAD is changed
      -- Insert into `wdi_push` brief information
      IF (@pid IS NOT NULL) THEN
      
        IF (@sha1_last IS NULL OR @sha1_last <> @sha1) THEN
          PREPARE stmt_insert FROM 'INSERT INTO `wdi` (`project_id`, `branch`, `sha1`) VALUES (?, ?, ?)';
          EXECUTE stmt_insert USING @pid, @branch, @sha1;
          DEALLOCATE PREPARE stmt_insert;
        END IF;
        
        PREPARE stmt_push FROM 'SET @is_insert = (SELECT `project_id` FROM `wdi_push` WHERE `project_id` = ? LIMIT 1)';
        EXECUTE stmt_push USING @pid;
        DEALLOCATE PREPARE stmt_push;
        
        IF (@is_insert IS NULL) THEN
          PREPARE stmt_insert_push FROM 'INSERT INTO `wdi_push` (`project_id`, `new_branch`) VALUES (?, ?)';
          EXECUTE stmt_insert_push USING @pid, @branch;
          DEALLOCATE PREPARE stmt_insert_push;
        ELSEIF (@sha1_last <> @sha1) THEN
          PREPARE stmt_update_push FROM 'UPDATE `wdi_push` SET `old_branch`=?, `new_branch`=? WHERE `project_id`=? LIMIT 1';
          EXECUTE stmt_update_push USING @branch_last, @branch, @pid;
          DEALLOCATE PREPARE stmt_update_push;
        END IF;

      END IF;
      
      
      
      SELECT @pid AS `pid`, @name AS `name`, @sha1 AS `sha1`, @sha1_last AS `sha1_last`, @branch AS `branch`;
    END $$
DELIMITER ;