-- -----------------------------------------------------
-- Table `{$tmp_tablename}`
-- -----------------------------------------------------
USE `{$target_schema}`;

DROP TABLE IF EXISTS `{$tmp_tablename}` ;

CREATE TABLE IF NOT EXISTS `{$tmp_tablename}` (
  `TID` VARCHAR(100) NOT NULL COMMENT 'Identifiant technique Unique',
  `BID` VARCHAR(200) NOT NULL COMMENT 'Identifiant Unique Fonctionnel',
  `STITLE` VARCHAR(50) NOT NULL COMMENT 'Titre court',
  `LTITLE` VARCHAR(200) NULL DEFAULT NULL COMMENT 'Titre Long',
  `COMMENT` TEXT NULL COMMENT 'Commentaire sur le modèle',
  `JSON_DATA` JSON NULL,
  `CUSER` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Compte Utilisateur',
  `CDATE` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UUSER` VARCHAR(100) NULL DEFAULT NULL,
  `UDATE` TIMESTAMP NULL DEFAULT NULL,
  `IS_DELETED` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`TID`),
  CONSTRAINT `FK_{$tmp_OBJCODE}UACCT_CUSER`
    FOREIGN KEY (`CUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_{$tmp_OBJCODE}UACCT_UUSER`
    FOREIGN KEY (`UUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB,
STATS_AUTO_RECALC=1
COMMENT = 'Table des .';

DELIMITER $$

USE `{$target_schema}`$$
DROP TRIGGER IF EXISTS `{$tmp_tablename}_BEFINS` $$
USE `{$target_schema}`$$
CREATE TRIGGER {$tmp_tablename}_BEFINS BEFORE INSERT ON `{$tmp_tablename}` FOR EACH ROW
BEGIN
	SET NEW.CUSER = current_user();
  SET NEW.TID = DMA_generateTIDFromOBDTablename('{$tmp_tablename}');
END$$


USE `{$target_schema}`$$
DROP TRIGGER IF EXISTS `{$tmp_tablename}_AFTINS` $$
USE `{$target_schema}`$$
CREATE TRIGGER {$tmp_tablename}_AFTINS AFTER INSERT ON `{$tmp_tablename}` FOR EACH ROW
BEGIN
  DECLARE l_sTIDOBD VARCHAR(100);
  CALL LOG_logDBDataEvent('INSERT',NEW.TID,NEW.BID);

  SELECT CAST( TID AS CHAR CHARACTER SET utf8) INTO l_sTIDOBD FROM A000_OBD WHERE OBI_DB_TABLENAME = '{$tmp_tablename}';
  INSERT INTO A100_OBI(TID,OBD_TID,BID) VALUES (NEW.TID,l_sTIDOBD,NEW.BID);
END$$


USE `{$target_schema}`$$
DROP TRIGGER IF EXISTS `{$tmp_tablename}_BEFUPD` $$
USE `{$target_schema}`$$
CREATE TRIGGER {$tmp_tablename}_BEFUPD BEFORE UPDATE ON `{$tmp_tablename}` FOR EACH ROW
BEGIN
	SET NEW.UUSER = current_user();
    SET NEW.UDATE = current_timestamp();
END$$


USE `{$target_schema}`$$
DROP TRIGGER IF EXISTS `{$tmp_tablename}_AFTUPD` $$
USE `{$target_schema}`$$
CREATE TRIGGER {$tmp_tablename}_AFTUPD AFTER UPDATE ON `{$tmp_tablename}` FOR EACH ROW
BEGIN
	CALL LOG_logDBDataEvent('UPDATE',NEW.TID,NEW.BID);
END$$
