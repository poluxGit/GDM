-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema GOM
-- -----------------------------------------------------
-- Generic Object Management
DROP SCHEMA IF EXISTS `GOM` ;

-- -----------------------------------------------------
-- Schema GOM
--
-- Generic Object Management
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `GOM` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ;
USE `GOM` ;

-- ************************************************************************** --
-- ************************************************************************** --
-- ************************************************************************** --
-- ATTENTION TRAITEMENT SPECIFIQUE ---------------------------------------------
-- ************************************************************************** --

-- -----------------------------------------------------
-- Table `A100_UACCT`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `A100_UACCT` ;

CREATE TABLE IF NOT EXISTS `A100_UACCT` (
  `TID` VARCHAR(100) NOT NULL,
  `BID` VARCHAR(200) NOT NULL,
  `ULOGIN` VARCHAR(45) NOT NULL,
  `UHOST` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`TID`))
ENGINE = InnoDB
COMMENT = 'Table des comptes utilisateurs - UACCT.';

DELIMITER $$

USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_UACCT_BEF_INSERT` $$
USE `GOM`$$
CREATE TRIGGER A100_UACCT_BEF_INSERT BEFORE INSERT ON `A100_UACCT` FOR EACH ROW
BEGIN
	IF NEW.TID IS NULL THEN
		SET NEW.TID = CONCAT(NEW.ULOGIN,'@',NEW.UHOST);
	END IF;
END$$

DELIMITER ;
USE `GOM`;
INSERT INTO A100_UACCT (`BID`, `ULOGIN`, `UHOST`) VALUES ('GOM Administrator', 'gomAdmin', '%');
INSERT INTO A100_UACCT (`BID`, `ULOGIN`, `UHOST`) VALUES ('GOM Modeler', 'gomModeler', '%');
INSERT INTO A100_UACCT (`BID`, `ULOGIN`, `UHOST`) VALUES ('GOM Dev User', 'gomDev', '%');
INSERT INTO A100_UACCT (`BID`, `ULOGIN`, `UHOST`) VALUES ('GOM User', 'gomUser', '%');
INSERT INTO A100_UACCT (`BID`, `ULOGIN`, `UHOST`) VALUES ('Compte Utilisateur Polux', 'polux', '%');

-- ************************************************************************** --
-- FIN TRAITEMENT SPECIFIQUE ---------------------------------------------------
-- ************************************************************************** --
-- ************************************************************************** --

-- -----------------------------------------------------
-- Table `A000_MDL`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `A000_MDL` ;

CREATE TABLE IF NOT EXISTS `A000_MDL` (
  `TID` VARCHAR(100) NOT NULL COMMENT 'Identifiant Unique Technique',
  `BID` VARCHAR(200) NOT NULL COMMENT 'Identifiant Unique Fonctionnel',
  `CODE` VARCHAR(2) NOT NULL COMMENT 'Code Interne technique utilisée pour le génération des TID & BID',
  `BIDCODE` VARCHAR(30) ASCII NOT NULL COMMENT 'Code Court intégré au BID final.',
  `VERSION` VARCHAR(5) NOT NULL DEFAULT '0' COMMENT 'Version du modèle',
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
  CONSTRAINT `FK_MDLUACCT_CUSER`
    FOREIGN KEY (`CUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_MDLUACCT_UUSER`
    FOREIGN KEY (`UUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Table des modèles de données - MDL.';

CREATE UNIQUE INDEX `CODE_UNIQUE` ON `A000_MDL` (`CODE` ASC) VISIBLE;

CREATE INDEX `FK_MDLUACCT_CUSER_idx` ON `A000_MDL` (`CUSER` ASC) VISIBLE;

CREATE INDEX `FK_MDLUACCT_UUSER_idx` ON `A000_MDL` (`UUSER` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `A000_OBD`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `A000_OBD` ;

CREATE TABLE IF NOT EXISTS `A000_OBD` (
  `TID` VARCHAR(100) NOT NULL COMMENT 'Identifiant Unique Technique',
  `MDL_TID` VARCHAR(100) NOT NULL COMMENT 'TID du modèle de la définition d\'objet',
  `BID` VARCHAR(200) NOT NULL COMMENT 'Identifiant Unique Fonctionnel',
  `STITLE` VARCHAR(50) NOT NULL COMMENT 'Titre court',
  `LTITLE` VARCHAR(200) NULL DEFAULT NULL COMMENT 'Titre Long',
  `COMMENT` TEXT NULL COMMENT 'Commentaire sur le modèle',
  `OBI_DB_TABLENAME` VARCHAR(200) NOT NULL COMMENT 'Nom de la table de stockage des OBI de l\'OBD.',
  `OBD_TYPE` ENUM('Simple', 'Complex', 'Specific') NOT NULL DEFAULT 'Simple',
  `OBD_TID_NUMLEN` INT NOT NULL DEFAULT 5,
  `OBD_TID_SPREFIX` VARCHAR(5) NOT NULL DEFAULT 'NODEF',
  `OBI_TID_PATTERN` VARCHAR(400) NULL DEFAULT NULL,
  `OBD_BID_PREFIX` VARCHAR(15) NOT NULL DEFAULT 'NOTDEF',
  `OBI_BID_PATTERN` VARCHAR(400) NULL DEFAULT NULL,
  `CUSER` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Compte Utilisateur',
  `CDATE` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UUSER` VARCHAR(100) NULL DEFAULT NULL,
  `UDATE` TIMESTAMP NULL DEFAULT NULL,
  `IS_DELETED` TINYINT NOT NULL DEFAULT 0,
  `IS_SYSTEM` TINYINT NOT NULL DEFAULT 0,
  `IS_COUNTED` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`TID`),
  CONSTRAINT `FK_OBDMDL_TID`
    FOREIGN KEY (`MDL_TID`)
    REFERENCES `A000_MDL` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_OBDUACCT_CUSER`
    FOREIGN KEY (`CUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_OBDUACCT_UUSER`
    FOREIGN KEY (`UUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Table des définitions d\'Objet - OBD.';

CREATE INDEX `FK_ODBMDL_TID_idx` ON `A000_OBD` (`MDL_TID` ASC) VISIBLE;

CREATE INDEX `FK_OBDUACCT_CUSER_idx` ON `A000_OBD` (`CUSER` ASC) VISIBLE;

CREATE INDEX `FK_OBDUACCT_UUSER_idx` ON `A000_OBD` (`UUSER` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `A000_OBMD`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `A000_OBMD` ;

CREATE TABLE IF NOT EXISTS `A000_OBMD` (
  `TID` VARCHAR(100) NOT NULL COMMENT 'Identifiant technique Unique',
  `OBD_TID` VARCHAR(100) NOT NULL,
  `BID` VARCHAR(200) NOT NULL COMMENT 'Identifiant Unique Fonctionnel',
  `STITLE` VARCHAR(50) NOT NULL COMMENT 'Titre court',
  `LTITLE` VARCHAR(200) NULL DEFAULT NULL COMMENT 'Titre Long',
  `COMMENT` TEXT NULL COMMENT 'Commentaire sur le modèle',
  `OBMD_DATA_TYPE` ENUM('String','Date','Datetime','Integer','Real') NOT NULL DEFAULT 'String',
  `OBMD_DATA_PATTERN` VARCHAR(100) NULL DEFAULT NULL,
  `OBMI_TID_PATTERN` VARCHAR(400) NOT NULL,
  `OBMI_BID_PATTERN` VARCHAR(400) NULL DEFAULT NULL,
  `JSON_DATA` JSON NULL,
  `CUSER` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Compte Utilisateur',
  `CDATE` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UUSER` VARCHAR(100) NULL DEFAULT NULL,
  `UDATE` TIMESTAMP NULL DEFAULT NULL,
  `IS_DELETED` TINYINT NOT NULL DEFAULT 0,
  `IS_SYSTEM` TINYINT NOT NULL DEFAULT 0,
  `IS_MULTIPLE` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`TID`),
  CONSTRAINT `FK_OBMDOBD_TID`
    FOREIGN KEY (`OBD_TID`)
    REFERENCES `A000_OBD` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_OBMDUACCT_CUSER`
    FOREIGN KEY (`CUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_OBMDUACCT_UUSER`
    FOREIGN KEY (`UUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Table de définition des attributs sur ODB - OBMD.';

CREATE INDEX `FK_OBMDOBD_TID_idx` ON `A000_OBMD` (`OBD_TID` ASC) VISIBLE;

CREATE INDEX `FK_OBMDUACCT_CUSER_idx` ON `A000_OBMD` (`CUSER` ASC) VISIBLE;

CREATE INDEX `FK_OBMDUACCT_UUSER_idx` ON `A000_OBMD` (`UUSER` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `Z000_LOGS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Z000_LOGS` ;

CREATE TABLE IF NOT EXISTS `Z000_LOGS` (
  `TID` VARCHAR(100) NOT NULL,
  `LOG_TYPE` ENUM('DataEvent','Information','SQL Query') NOT NULL,
  `OB_TID` VARCHAR(100) NULL DEFAULT NULL,
  `OB_BID` VARCHAR(200) NULL DEFAULT NULL,
  `MESSAGE` VARCHAR(4000) NULL,
  `CUSER` VARCHAR(100) NOT NULL,
  `CDATE` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`TID`),
  CONSTRAINT `FK_LOGUACCT_CUSER`
    FOREIGN KEY (`CUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Table des logs.';

CREATE INDEX `FK_LOGUACCT_CUSER_idx` ON `Z000_LOGS` (`CUSER` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `A000_LNKD`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `A000_LNKD` ;

CREATE TABLE IF NOT EXISTS `A000_LNKD` (
  `TID` VARCHAR(100) NOT NULL COMMENT 'Identifiant Unique Technique',
  `MDL_TID` VARCHAR(100) NOT NULL COMMENT 'TID du modèle de la définition de lien.',
  `BID` VARCHAR(200) NOT NULL COMMENT 'Identifiant Unique Fonctionnel',
  `STITLE` VARCHAR(50) NOT NULL COMMENT 'Titre court',
  `LTITLE` VARCHAR(200) NULL DEFAULT NULL COMMENT 'Titre Long',
  `COMMENT` TEXT NULL DEFAULT NULL COMMENT 'Commentaire sur le modèle',
  `LNKD_TYPE` ENUM('OneToOne','OneToAny','AnyToAny') NOT NULL,
  `OBD_TID_SOURCE` VARCHAR(100) NOT NULL,
  `OBD_TID_TARGET` VARCHAR(100) NOT NULL,
  `JSON_DATA` JSON NULL COMMENT 'Nom de la table de stockage des OBI de l\'OBD.',
  `CUSER` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Compte Utilisateur',
  `CDATE` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UUSER` VARCHAR(100) NULL DEFAULT NULL,
  `UDATE` TIMESTAMP NULL DEFAULT NULL,
  `IS_DELETED` TINYINT NOT NULL DEFAULT 0,
  `IS_PROPAGATED` TINYINT NULL DEFAULT NULL,
  PRIMARY KEY (`TID`),
  CONSTRAINT `FK_LNKDMDL_TID`
    FOREIGN KEY (`MDL_TID`)
    REFERENCES `A000_MDL` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_LNKDOBD_SRC`
    FOREIGN KEY (`OBD_TID_SOURCE`)
    REFERENCES `A000_OBD` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_LNKDOBD_DST`
    FOREIGN KEY (`OBD_TID_TARGET`)
    REFERENCES `A000_OBD` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_LNKDUACCT_CUSER`
    FOREIGN KEY (`CUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_LNKDUACCT_UUSER`
    FOREIGN KEY (`UUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Table des définitions des liens entre objets - LNKD.';

CREATE INDEX `FK_ODBMDL_TID_idx` ON `A000_LNKD` (`MDL_TID` ASC) VISIBLE;

CREATE INDEX `FK_LNKDOBD_SRC_idx` ON `A000_LNKD` (`OBD_TID_SOURCE` ASC) VISIBLE;

CREATE INDEX `FK_LNKDOBD_DST_idx` ON `A000_LNKD` (`OBD_TID_TARGET` ASC) VISIBLE;

CREATE INDEX `FK_LNKDUACCT_CUSER_idx` ON `A000_LNKD` (`CUSER` ASC) VISIBLE;

CREATE INDEX `FK_LNKDUACCT_CUSER_idx1` ON `A000_LNKD` (`UUSER` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `A000_LNKMD`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `A000_LNKMD` ;

CREATE TABLE IF NOT EXISTS `A000_LNKMD` (
  `TID` VARCHAR(100) NOT NULL COMMENT 'Identifiant Unique Technique',
  `LNKD_TID` VARCHAR(100) NOT NULL COMMENT 'TID du modèle de la définition de lien.',
  `BID` VARCHAR(200) NOT NULL COMMENT 'Identifiant Unique Fonctionnel',
  `STITLE` VARCHAR(50) NOT NULL COMMENT 'Titre court',
  `LTITLE` VARCHAR(200) NULL DEFAULT NULL COMMENT 'Titre Long',
  `LNKMD_DATA_TYPE` ENUM('String','Date','Datetime','Integer','Real') NOT NULL DEFAULT 'String',
  `LNKMD_DATA_PATTERN` VARCHAR(100) NULL DEFAULT NULL,
  `LNKMI_TID_PATTERN` VARCHAR(400) NOT NULL,
  `LNKMI_BID_PATTERN` VARCHAR(400) NULL DEFAULT NULL,
  `COMMENT` TEXT NULL DEFAULT NULL COMMENT 'Commentaire sur le modèle',
  `JSON_DATA` JSON NULL COMMENT 'Nom de la table de stockage des OBI de l\'OBD.',
  `CUSER` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Compte Utilisateur',
  `CDATE` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UUSER` VARCHAR(100) NULL DEFAULT NULL,
  `UDATE` TIMESTAMP NULL DEFAULT NULL,
  `IS_DELETED` TINYINT NOT NULL DEFAULT 0,
  `IS_PROPAGATED` TINYINT NULL DEFAULT NULL,
  PRIMARY KEY (`TID`),
  CONSTRAINT `FK_LNKMDLNKD_TID`
    FOREIGN KEY (`LNKD_TID`)
    REFERENCES `A000_LNKD` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_LNKMDUACCT_CUSER`
    FOREIGN KEY (`CUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_LNKMDUACCT_UUSER`
    FOREIGN KEY (`UUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Table des définitions des meta liens entre objets - LNKMD.';

CREATE INDEX `FK_LNKDMLNKD_TID_idx` ON `A000_LNKMD` (`LNKD_TID` ASC) VISIBLE;

CREATE INDEX `FK_LNKMDUACCT_CUSER_idx` ON `A000_LNKMD` (`CUSER` ASC) VISIBLE;

CREATE INDEX `FK_LNKMDUACCT_UUSER_idx` ON `A000_LNKMD` (`UUSER` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `A100_OBI`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `A100_OBI` ;

CREATE TABLE IF NOT EXISTS `A100_OBI` (
  `TID` VARCHAR(100) NOT NULL COMMENT 'Identifiant Unique Technique',
  `OBD_TID` VARCHAR(100) NOT NULL COMMENT 'TID du OBD',
  `BID` VARCHAR(200) NOT NULL COMMENT 'Identifiant Unique Fonctionnel',
  `CUSER` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Compte Utilisateur',
  `CDATE` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UUSER` VARCHAR(100) NULL DEFAULT NULL,
  `UDATE` TIMESTAMP NULL DEFAULT NULL,
  `IS_DELETED` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`TID`),
  CONSTRAINT `FK_OBIOBD_TID`
    FOREIGN KEY (`OBD_TID`)
    REFERENCES `A000_OBD` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_OBIUACCT_CUSER`
    FOREIGN KEY (`CUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_OBIUACCT_UUSER`
    FOREIGN KEY (`UUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Table des Objets instanciés - OBI.';

CREATE INDEX `FK_OBIOBD_TID_idx` ON `A100_OBI` (`OBD_TID` ASC) VISIBLE;

CREATE INDEX `FK_OBIUACCT_CUSER_idx` ON `A100_OBI` (`CUSER` ASC) VISIBLE;

CREATE INDEX `FK_OBIUACCT_UUSER_idx` ON `A100_OBI` (`UUSER` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `A100_OBMI`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `A100_OBMI` ;

CREATE TABLE IF NOT EXISTS `A100_OBMI` (
  `TID` VARCHAR(100) NOT NULL COMMENT 'Identifiant Unique Technique',
  `OBI_TID` VARCHAR(100) NOT NULL COMMENT 'TID OBI.',
  `OBMD_TID` VARCHAR(100) NOT NULL,
  `BID` VARCHAR(200) NOT NULL COMMENT 'Identifiant Unique Fonctionnel',
  `STITLE` VARCHAR(50) NOT NULL COMMENT 'Titre court',
  `LTITLE` VARCHAR(200) NULL DEFAULT NULL COMMENT 'Titre Long',
  `OBMI_DATA_TYPE` ENUM('String','Date','Datetime','Integer','Real') NOT NULL DEFAULT 'String',
  `OBMI_DATA_PATTERN` VARCHAR(100) NULL DEFAULT NULL,
  `OBMI_VALUE` TEXT NULL DEFAULT NULL,
  `COMMENT` TEXT NULL DEFAULT NULL COMMENT 'Commentaire sur le modèle',
  `JSON_DATA` JSON NULL COMMENT 'Nom de la table de stockage des OBI de l\'OBD.',
  `CUSER` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Compte Utilisateur',
  `CDATE` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UUSER` VARCHAR(100) NULL DEFAULT NULL,
  `UDATE` TIMESTAMP NULL DEFAULT NULL,
  `IS_DELETED` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`TID`),
  CONSTRAINT `FK_OBDMIOBI_TID`
    FOREIGN KEY (`OBI_TID`)
    REFERENCES `A100_OBI` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_OBMIOBMD_TID`
    FOREIGN KEY (`OBMD_TID`)
    REFERENCES `A000_OBMD` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_OBMIUACCT_CUSER`
    FOREIGN KEY (`CUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_OBMIUACCT_UUSER`
    FOREIGN KEY (`UUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Table des instances de meta-données sur OBI - OBMI.';

CREATE INDEX `FK_OBDMIOBI_TID_idx` ON `A100_OBMI` (`OBI_TID` ASC) VISIBLE;

CREATE INDEX `FK_OBMIOBMD_TID_idx` ON `A100_OBMI` (`OBMD_TID` ASC) VISIBLE;

CREATE INDEX `FK_OBMIUACCT_CUSER_idx` ON `A100_OBMI` (`CUSER` ASC) VISIBLE;

CREATE INDEX `FK_OBMIUACCT_UUSER_idx` ON `A100_OBMI` (`UUSER` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `A100_LNKI`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `A100_LNKI` ;

CREATE TABLE IF NOT EXISTS `A100_LNKI` (
  `TID` VARCHAR(100) NOT NULL COMMENT 'Identifiant Unique Technique',
  `LNKD_TID` VARCHAR(100) NOT NULL COMMENT 'TID du OBD',
  `BID` VARCHAR(200) NOT NULL COMMENT 'Identifiant Unique Fonctionnel',
  `LNKI_NUM` INT NOT NULL DEFAULT 1,
  `OBI_TID_SOURCE` VARCHAR(100) NOT NULL,
  `OBI_TID_TARGER` VARCHAR(100) NOT NULL,
  `CUSER` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Compte Utilisateur',
  `CDATE` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UUSER` VARCHAR(100) NULL DEFAULT NULL,
  `UDATE` TIMESTAMP NULL DEFAULT NULL,
  `IS_DELETED` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`TID`),
  CONSTRAINT `FK_LNKILNKD_TID`
    FOREIGN KEY (`LNKD_TID`)
    REFERENCES `A000_LNKD` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_LNKIOBI_SOURCE`
    FOREIGN KEY (`OBI_TID_SOURCE`)
    REFERENCES `A100_OBI` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_LNKIOBI_TARGET`
    FOREIGN KEY (`OBI_TID_TARGER`)
    REFERENCES `A100_OBI` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_LNKIUACCT_CUSER`
    FOREIGN KEY (`CUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_LNKIUACCT_UUSER`
    FOREIGN KEY (`UUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Table des liens entre Objets instanciés - LNKI.';

CREATE INDEX `FK_LNKILNKD_TID_idx` ON `A100_LNKI` (`LNKD_TID` ASC) VISIBLE;

CREATE INDEX `FK_LNKIOBI_SOURCE_idx` ON `A100_LNKI` (`OBI_TID_SOURCE` ASC) VISIBLE;

CREATE INDEX `FK_LNKIOBI_TARGET_idx` ON `A100_LNKI` (`OBI_TID_TARGER` ASC) VISIBLE;

CREATE INDEX `FK_LNKIUACCT_CUSER_idx` ON `A100_LNKI` (`CUSER` ASC) VISIBLE;

CREATE INDEX `FK_LNKIUACCT_UUSER_idx` ON `A100_LNKI` (`UUSER` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `A100_LNKMI`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `A100_LNKMI` ;

CREATE TABLE IF NOT EXISTS `A100_LNKMI` (
  `TID` VARCHAR(100) NOT NULL COMMENT 'Identifiant Unique Technique',
  `LNKI_TID` VARCHAR(100) NOT NULL COMMENT 'TID du modèle de la définition de lien.',
  `LNKMD_TID` VARCHAR(100) NOT NULL,
  `BID` VARCHAR(200) NOT NULL COMMENT 'Identifiant Unique Fonctionnel',
  `STITLE` VARCHAR(50) NOT NULL COMMENT 'Titre court',
  `LTITLE` VARCHAR(200) NULL DEFAULT NULL COMMENT 'Titre Long',
  `LNKMI_DATA_TYPE` ENUM('String','Date','Datetime','Integer','Real') NOT NULL DEFAULT 'String',
  `LNKMI_DATA_PATTERN` VARCHAR(100) NULL DEFAULT NULL,
  `LNKMI_VALUE` TEXT NULL DEFAULT NULL,
  `COMMENT` TEXT NULL DEFAULT NULL COMMENT 'Commentaire sur le modèle',
  `JSON_DATA` JSON NULL DEFAULT NULL COMMENT 'Nom de la table de stockage des OBI de l\'OBD.',
  `CUSER` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Compte Utilisateur',
  `CDATE` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UUSER` VARCHAR(100) NULL DEFAULT NULL,
  `UDATE` TIMESTAMP NULL DEFAULT NULL,
  `IS_DELETED` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`TID`),
  CONSTRAINT `FK_LNKMILNKI_TID`
    FOREIGN KEY (`LNKI_TID`)
    REFERENCES `A100_LNKI` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_LNKMILNKMD_TID`
    FOREIGN KEY (`LNKMD_TID`)
    REFERENCES `A000_LNKMD` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_LNKMIUACCT_CUSER`
    FOREIGN KEY (`CUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_LNKMIUACCT_UUSER`
    FOREIGN KEY (`UUSER`)
    REFERENCES `A100_UACCT` (`TID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Table des meta liens entre objets - LNKMI.';

CREATE INDEX `FK_LNKMILNKI_TID_idx` ON `A100_LNKMI` (`LNKI_TID` ASC) VISIBLE;

CREATE INDEX `FK_LNKMILNKMD_TID_idx` ON `A100_LNKMI` (`LNKMD_TID` ASC) VISIBLE;

CREATE INDEX `FK_LNKMIUACCT_CUSER_idx` ON `A100_LNKMI` (`CUSER` ASC) VISIBLE;

CREATE INDEX `FK_LNKMIUACCT_UUSER_idx` ON `A100_LNKMI` (`UUSER` ASC) VISIBLE;

USE `GOM` ;

-- -----------------------------------------------------
-- function DMA_generateTIDForOBD
-- -----------------------------------------------------

USE `GOM`;
DROP function IF EXISTS `DMA_generateTIDForOBD`;

DELIMITER $$
USE `GOM`$$
-- ************************************************************************** --
-- CREATION OBJET DU MODELE 																								  --
-- ************************************************************************** --

-- -------------------------------------------------------------------------- --
--  Création d'un nouveau modèle
-- -------------------------------------------------------------------------- --
-- Exemple : MDL-E1_0010
-- -------------------------------------------------------------------------- --
-- CREATE PROCEDURE DMA_createNewModel( IN pStrNewModel_Code VARCHAR(2),
-- 																		 IN pStrNewModel_BIDCode VARCHAR(30),
-- 																		 IN pStrNewModel_Version VARCHAR(5),
-- 																		 IN pStrNewModel_ShortTitle VARCHAR(50),
-- 																		 IN pStrNewModel_LongTitle VARCHAR(200),
-- 																		 IN pStrNewModel_Comment TEXT,
-- 																		 IN pStrNewModel_JSONData JSON,
-- 																	   OUT pStrNewModelTID VARCHAR(100))
-- BEGIN
--
-- 	DECLARE lStrTID VARCHAR(100);
-- 	DECLARE lStrBID VARCHAR(200);
-- 	DECLARE liNbModel INT;
--
-- 	-- Nombre de modèle déjà défini
-- 	SELECT count(TID) INTO liNbModel FROM A000_MDL;
--
-- 	-- Définition du TID => MDL-E1_0010
-- 	SET lStrTID = CONCAT('MDL-',pStrNewModel_Code,'_',LPAD(CONVERT(liNbModel+1,CHAR),4,'0'));
-- 	SET lStrBID = CONCAT('MDL.',pStrNewModel_BIDCode,'-',pStrNewModel_Version,'_',LPAD(CONVERT(liNbModel+1,CHAR),4,'0'));
--
-- 	INSERT INTO A000_MDL(	`TID`,	`BID`,	`CODE`,	`BIDCODE`,	`VERSION`,	`STITLE`,	`LTITLE`,	`COMMENT`,	`JSON_DATA`	)
-- 	VALUES (
-- 		lStrTID,
-- 		lStrBID,
-- 		pStrNewModel_Code,
-- 		pStrNewModel_BIDCode,
-- 		pStrNewModel_Version,
-- 		pStrNewModel_ShortTitle,
-- 		pStrNewModel_LongTitle,
-- 		pStrNewModel_Comment,
-- 		pStrNewModel_JSONData
-- 	);
--
-- 	SET pStrNewModelTID = lStrTID;
--
-- END$$
--
-- -- -------------------------------------------------------------------------- --
-- --  Création d'une nouvelle definition d'objet.
-- -- -------------------------------------------------------------------------- --
-- -- Exemple : E1.OBD-CAT_0002
-- -- -------------------------------------------------------------------------- --
-- CREATE PROCEDURE DMA_createNewObjectDefinition( IN pStrModelTID VARCHAR(100),
-- 																							  IN pStrNewOBD_BID VARCHAR(200),
-- 																							  IN pStrNewOBD_ShortTitle VARCHAR(50),
-- 																							  IN pStrNewOBD_LongTitle VARCHAR(200),
-- 																							  IN pStrNewOBD_Comment TEXT,
-- 																							  IN pStrNewOBD_Type ENUM('Simple', 'Complex', 'Specific'),
-- 																							  IN pStrNewOBD_TIDPattern VARCHAR(400),
-- 																							  IN pStrNewOBD_BIDPattern VARCHAR(400),
-- 																							  IN pStrNewOBD_Tablename VARCHAR(400),
-- 																							  OUT pStrTIDNewOBD VARCHAR(100)
-- 																							)
-- BEGIN
--
-- 	DECLARE lStrTID VARCHAR(100);
-- 	DECLARE lStrBID VARCHAR(200);
-- 	DECLARE lStrModelCode VARCHAR(100);
-- 	DECLARE liNbOBDForModel INT;
--
-- 	-- Nombre d'objet déjà défini sur la même occurence du model (TODO Faire fonction)
-- 	SELECT count(TID) INTO liNbOBDForModel FROM A000_MDL;
--
--   -- Code du Model (TODO Faire fonction)
--   SELECT CODE INTO lStrModelCode FROM A000_MDL WHERE TID = pStrModelTID;
--
-- 	-- Définition du TID => MDL-E1_0010
-- 	SET lStrTID = CONCAT(lStrModelCode,'.OBD',pStrNewODBShortPrefix,'_',LPAD(CONVERT(liNbOBDForModel+1,CHAR),4,'0'));
--
-- 	INSERT INTO `A000_OBD` (
-- 		`TID`,
-- 		`MDL_TID`,
-- 		`BID`,
-- 		`STITLE`,
-- 		`LTITLE`,
-- 		`COMMENT`,
-- 		`ODB_TYPE`,
-- 		`OBI_TID_PATTERN`,
-- 		`OBI_BID_PATTERN`,
-- 		`OBI_DB_TABLENAME`)
-- 	VALUES (
-- 		lStrTID,
-- 		pStrModelTID,
-- 		pStrNewOBD_BID,
-- 		pStrNewOBD_ShortTitle,
-- 		pStrNewOBD_LongTitle,
-- 		pStrNewOBD_Comment,
-- 		pStrNewOBD_Type,
-- 		pStrNewOBD_TIDPattern,
-- 		pStrNewOBD_BIDPattern,
-- 		 pStrNewOBD_Tablename
-- 	 );
-- 	 SET pStrTIDNewOBD = lStrTID;
-- END$$

-- ************************************************************************** --
-- FONCTIONS OUTILS          																								  --
-- ************************************************************************** --

-- -------------------------------------------------------------------------- --
--  Génération d'un TID depuis son model.
-- -------------------------------------------------------------------------- --
-- Exemple : E1.OBD-CAT_0002
-- -------------------------------------------------------------------------- --
CREATE FUNCTION DMA_generateTIDForOBD(pStrModelTID VARCHAR(100), pStrNewOBDShortPrefix VARCHAR(10)) RETURNS VARCHAR(100) DETERMINISTIC
BEGIN
	DECLARE lResult VARCHAR(100);
	DECLARE lStrTMP VARCHAR(100);
  DECLARE lStrModelCode VARCHAR(100);
	DECLARE liNbOBDForModel INT;

	-- Nombre d'objet déjà défini sur la même occurence du model (TODO Faire fonction)
	SELECT count(TID) INTO liNbOBDForModel FROM A000_OBD WHERE MDL_ID = pStrModelTID;

  -- Code du Model (TODO Faire fonction)
  SELECT CODE INTO lStrModelCode FROM A000_MDL WHERE TID = pStrModelTID;

	SET lStrTMP = CONCAT(lStrModelCode,'.OBD',pStrNewOBDShortPrefix,'_',LPAD(CONVERT(liNbOBDForModel+1,CHAR),4,'0'));
	RETURN lResult;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure LOG_logItem
-- -----------------------------------------------------

USE `GOM`;
DROP procedure IF EXISTS `LOG_logItem`;

DELIMITER $$
USE `GOM`$$
-- ************************************************************************** --
-- Routines relatives aux logs applicatifs (prefix LOG_)
-- ************************************************************************** --

-- -------------------------------------------------------------------------- --
-- Procédure de log générique
-- -------------------------------------------------------------------------- --
CREATE PROCEDURE LOG_logItem( p_sTypeLog ENUM('DataEvent','Information','SQL Query'),
											 			  p_sMessage VARCHAR(4000),
										   			  p_sOBDTid VARCHAR(100),
											 				p_sOBDBid VARCHAR(200))
BEGIN
		DECLARE lIntNbLogs INT;
    DECLARE lStrTIDLog VARCHAR(100);
    DECLARE lStrPrefixType VARCHAR(5);

    SET lStrPrefixType = LOG_getTIDPrefixFromLogType(p_sTypeLog);

		SELECT TOOLS_getRowsCountForTable('Z000_LOGS') INTO lIntNbLogs;

    SET lStrTIDLog = CONCAT('LOG.',lStrPrefixType,'-',LPAD(CONVERT(lIntNbLogs+1,CHAR),20,'0'));

		INSERT INTO Z000_LOGS (
			`TID`,
      `OB_TID`,
      `OB_BID`,
      `MESSAGE`,
      `LOG_TYPE`) VALUES (lStrTIDLog, p_sOBDTid, p_sOBDBid, p_sMessage, p_sTypeLog);

END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure LOG_logSQLQuery
-- -----------------------------------------------------

USE `GOM`;
DROP procedure IF EXISTS `LOG_logSQLQuery`;

DELIMITER $$
USE `GOM`$$
CREATE PROCEDURE LOG_logSQLQuery (pStrSQLQuery VARCHAR(4000))
BEGIN
		DECLARE l_sFinalMessage VARCHAR(4000);

		SET l_sFinalMessage = CONCAT('SQL Query Executed : ',pStrSQLQuery);
		CALL LOG_logItem('SQL Query',l_sFinalMessage,NULL,NULL);
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure LOG_logDBDataEvent
-- -----------------------------------------------------

USE `GOM`;
DROP procedure IF EXISTS `LOG_logDBDataEvent`;

DELIMITER $$
USE `GOM`$$
CREATE PROCEDURE LOG_logDBDataEvent( p_sTypeDataEvent ENUM('INSERT','UPDATE','DELETE'),
																		 p_sOBDTid VARCHAR(100),
																	   p_sOBDBid VARCHAR(200))
BEGIN
		DECLARE l_sFinalMessage VARCHAR(4000);

		SET l_sFinalMessage = CONCAT('DataEvent "',p_sTypeDataEvent,'" on object with TID "',p_sOBDTid,'"|BID "',p_sOBDBid,'".');
		CALL LOG_logItem('DataEvent',l_sFinalMessage,p_sOBDTid,p_sOBDBid);
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure LOG_logMessage
-- -----------------------------------------------------

USE `GOM`;
DROP procedure IF EXISTS `LOG_logMessage`;

DELIMITER $$
USE `GOM`$$
CREATE PROCEDURE LOG_logMessage(p_sMessage VARCHAR(4000),
																p_sOBDTid VARCHAR(100),
																p_sOBDBid VARCHAR(200))
BEGIN
	CALL LOG_logItem('Information',p_sMessage,p_sOBDTid,p_sOBDBid);
END$$

DELIMITER ;

-- -----------------------------------------------------
-- function TOOLS_getRowsCountForTable
-- -----------------------------------------------------

USE `GOM`;
DROP function IF EXISTS `TOOLS_getRowsCountForTable`;

DELIMITER $$
USE `GOM`$$
-- ************************************************************************** --
-- Routines 'Outils'
-- ************************************************************************** --

-- -------------------------------------------------------------------------- --
--  Renvoi le nombre de lignes de la table
-- -------------------------------------------------------------------------- --
CREATE FUNCTION TOOLS_getRowsCountForTable(p_sTablename VARCHAR(200)) RETURNS INT DETERMINISTIC
BEGIN
	  DECLARE l_iNbRows INT;

		select TABLE_ROWS INTO l_iNbRows from information_schema.TABLES
		where TABLE_SCHEMA = schema() AND UPPER(table_name)=UPPER(p_sTablename);

		RETURN l_iNbRows;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- function DMA_generateTIDFromOBD
-- -----------------------------------------------------

USE `GOM`;
DROP function IF EXISTS `DMA_generateTIDFromOBD`;

DELIMITER $$
USE `GOM`$$
CREATE FUNCTION DMA_generateTIDFromOBD(p_sOBDTid VARCHAR(100)) RETURNS VARCHAR(100) DETERMINISTIC
BEGIN
	DECLARE l_sResult 					VARCHAR(100) DEFAULT NULL;
	DECLARE l_iNbObj 				  	INT;
	DECLARE l_sOBDModelTID    	VARCHAR(100) DEFAULT NULL;
	DECLARE l_sModelCode      	VARCHAR(2) DEFAULT NULL;
	DECLARE l_sOBDDBTablename 	VARCHAR(200) DEFAULT NULL;
	DECLARE l_sOBDType 					ENUM('Simple','Complex','Specific');
	DECLARE l_sOBDTIDPefix 		  VARCHAR(5) DEFAULT NULL;
	DECLARE l_sOBDTIDPattern 	  VARCHAR(400) DEFAULT NULL;
	DECLARE l_sOBDTIDNumLength 	INT DEFAULT NULL;

	-- Récupération des informations de la définition d'objet OBD
	SELECT MDL_TID,OBI_DB_TABLENAME, OBD_TYPE, OBD_TID_SPREFIX, OBI_TID_PATTERN, OBD_TID_NUMLEN
	INTO l_sOBDModelTID,l_sOBDDBTablename,l_sOBDType,l_sOBDTIDPefix,l_sOBDTIDPattern,l_sOBDTIDNumLength
	FROM A000_OBD WHERE TID = p_sOBDTid;

	-- Nb elements existants
	SELECT TOOLS_getRowsCountForTable(l_sOBDDBTablename) INTO l_iNbObj;
  -- Code du Model
  SELECT DMA_getMDLCode(l_sOBDModelTID) INTO l_sModelCode;

	-- TODO : Faire moteur afin d'exploiter le TID Pattern
	-- Exemple : E1.DOC-C-0000000...0023
	SET l_sResult = CONCAT(l_sModelCode,'.',l_sOBDTIDPefix,'-',DMA_getTIDPrefixFromOBDType(l_sOBDType),'-',LPAD(CONVERT(l_iNbObj+1,CHAR),l_sOBDTIDNumLength,'0'));
	RETURN l_sResult;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- function DMA_getMDLCode
-- -----------------------------------------------------

USE `GOM`;
DROP function IF EXISTS `DMA_getMDLCode`;

DELIMITER $$
USE `GOM`$$
CREATE FUNCTION DMA_getMDLCode(p_sMDLTid VARCHAR(100)) RETURNS VARCHAR(2) DETERMINISTIC
BEGIN
	DECLARE l_sResult 				VARCHAR(2) DEFAULT NULL;
	-- Code du Model (TODO Faire fonction)
  SELECT CODE INTO l_sResult FROM A000_MDL WHERE TID = p_sMDLTid;
	RETURN l_sResult;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- function DMA_getTIDPrefixFromOBDType
-- -----------------------------------------------------

USE `GOM`;
DROP function IF EXISTS `DMA_getTIDPrefixFromOBDType`;

DELIMITER $$
USE `GOM`$$
CREATE FUNCTION DMA_getTIDPrefixFromOBDType( p_sTypeOBD ENUM('Simple','Complex','Specific')) RETURNS VARCHAR(3) NO SQL
BEGIN
	DECLARE l_sResult VARCHAR(3) DEFAULT 'SPE';

	IF  p_sTypeOBD = 'Simple' THEN
		SET l_sResult = 'S';
	END IF;

	IF  p_sTypeOBD = 'Complex' THEN
		SET l_sResult = 'C';
	END IF;

	IF  p_sTypeOBD = 'Specific' THEN
		SET l_sResult = 'SPE';
	END IF;

	RETURN l_sResult;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- function DMA_generateTIDFromOBDTablename
-- -----------------------------------------------------

USE `GOM`;
DROP function IF EXISTS `DMA_generateTIDFromOBDTablename`;

DELIMITER $$
USE `GOM`$$
CREATE FUNCTION DMA_generateTIDFromOBDTablename(p_sOBDTablename VARCHAR(200)) RETURNS VARCHAR(100) DETERMINISTIC
BEGIN
	DECLARE l_sResult 		VARCHAR(100) DEFAULT NULL;
	DECLARE l_sOBDTID    	VARCHAR(100) DEFAULT NULL;

	-- Récupération des informations de la définition d'objet OBD
	SELECT TID INTO l_sOBDTID FROM A000_OBD WHERE UCASE(OBI_DB_TABLENAME) = UCASE(p_sOBDTablename);

	IF NOT l_sOBDTID IS NULL THEN
		SET l_sResult =  DMA_generateTIDFromOBD(l_sOBDTID);
	END IF;
	RETURN l_sResult;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- function LOG_getTIDPrefixFromLogType
-- -----------------------------------------------------

USE `GOM`;
DROP function IF EXISTS `LOG_getTIDPrefixFromLogType`;

DELIMITER $$
USE `GOM`$$
CREATE FUNCTION LOG_getTIDPrefixFromLogType( p_sTypeLog ENUM('DataEvent','Information','SQL Query')) RETURNS VARCHAR(5) NO SQL
BEGIN
	DECLARE l_sResult VARCHAR(5);

	CASE p_sTypeLog
		WHEN 'DataEvent' THEN
			SET l_sResult = 'D_EVT';
		WHEN 'Information' THEN
			SET l_sResult = 'INFO';
        WHEN 'SQL Query' THEN
			SET l_sResult = 'SQL';
    END CASE;

	RETURN l_sResult;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- function DMA_createNewModel
-- -----------------------------------------------------

USE `GOM`;
DROP function IF EXISTS `DMA_createNewModel`;

DELIMITER $$
USE `GOM`$$
-- ************************************************************************** --
-- CREATION OBJET DU MODELE 																								  --
-- ************************************************************************** --

-- -------------------------------------------------------------------------- --
--  Création d'un nouveau modèle
-- -------------------------------------------------------------------------- --
-- Exemple : MDL-E1_0010
-- -------------------------------------------------------------------------- --
CREATE FUNCTION DMA_createNewModel(  pStrNewModel_Code VARCHAR(2),
																		 pStrNewModel_BIDCode VARCHAR(30),
																		 pStrNewModel_Version VARCHAR(5),
																		 pStrNewModel_ShortTitle VARCHAR(50),
																		 pStrNewModel_LongTitle VARCHAR(200),
																		 pStrNewModel_Comment TEXT,
																		 pStrNewModel_JSONData JSON) RETURNS VARCHAR(100) DETERMINISTIC
BEGIN

	DECLARE lStrTID VARCHAR(100);
	DECLARE lStrBID VARCHAR(200);
	DECLARE lStrBIDPrefix VARCHAR(15);

	SELECT OBD_BID_PREFIX INTO lStrBIDPrefix FROM A000_OBD WHERE OBI_DB_TABLENAME = 'A000_MDL';

	-- Définition du BID => MDL-ECM-01
	SET lStrBID = CONCAT(lStrBIDPrefix,'-',pStrNewModel_BIDCode,'-',pStrNewModel_Version);

	INSERT INTO A000_MDL(`BID`,	`CODE`,	`BIDCODE`,	`VERSION`,	`STITLE`,	`LTITLE`,	`COMMENT`,	`JSON_DATA`	)
	VALUES (
		lStrBID,
		pStrNewModel_Code,
		pStrNewModel_BIDCode,
		pStrNewModel_Version,
		pStrNewModel_ShortTitle,
		pStrNewModel_LongTitle,
		pStrNewModel_Comment,
		pStrNewModel_JSONData
	);

	SELECT TID INTO lStrTID FROM A000_MDL WHERE CDATE = (SELECT MAX(CDATE) FROM A000_MDL) ;

	RETURN lStrTID;

END$$

DELIMITER ;

-- -----------------------------------------------------
-- function DMA_createNewObjectDefinition
-- -----------------------------------------------------

USE `GOM`;
DROP function IF EXISTS `DMA_createNewObjectDefinition`;

DELIMITER $$
USE `GOM`$$
CREATE FUNCTION DMA_createNewObjectDefinition( p_sModelTID VARCHAR(100),
																							 p_sNewOBD_BID VARCHAR(200),
																							 p_sNewOBD_ShortTitle VARCHAR(50),
																							 p_sNewOBD_LongTitle VARCHAR(200),
																						 	 p_sNewOBD_Comment TEXT,
																							 p_sNewOBD_Type ENUM('Simple', 'Complex', 'Specific'),
																							 p_sNewOBD_TIDShortPrefix VARCHAR(5),
																							 p_iNewOBD_TIDNumLenght INT,
																							 p_sNewOBD_TIDPattern VARCHAR(400),
																							 p_sNewOBD_BIDPrefix VARCHAR(15),
																							 p_sNewOBD_BIDPattern VARCHAR(400),
																							 p_sNewOBD_Tablename VARCHAR(400)
																							) RETURNS VARCHAR(100) DETERMINISTIC
BEGIN

	DECLARE lStrTID VARCHAR(100);

	INSERT INTO A000_OBD
	(`MDL_TID`,	`BID`,	`STITLE`,	`LTITLE`,	`COMMENT`,	`OBI_DB_TABLENAME`,	`OBD_TYPE`,
		`OBD_TID_NUMLEN`,	`OBD_TID_SPREFIX`,`OBI_TID_PATTERN`,	`OBD_BID_PREFIX`,	`OBI_BID_PATTERN`)
	VALUES
	(p_sModelTID,p_sNewOBD_BID,p_sNewOBD_ShortTitle,p_sNewOBD_LongTitle,p_sNewOBD_Comment,p_sNewOBD_Tablename,p_sNewOBD_Type,
	 p_iNewOBD_TIDNumLenght,p_sNewOBD_TIDShortPrefix,p_sNewOBD_TIDPattern,p_sNewOBD_BIDPrefix,p_sNewOBD_BIDPattern);

	 SET lStrTID = DMA_generateTIDFromOBDTablename('A000_OBD') ;

	 RETURN lStrTID;

END$$

DELIMITER ;
USE `GOM`;

DELIMITER $$

USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_UACCT_BEF_INSERT` $$
USE `GOM`$$
CREATE TRIGGER A100_UACCT_BEF_INSERT BEFORE INSERT ON `A100_UACCT` FOR EACH ROW
BEGIN
	IF NEW.TID IS NULL THEN
		SET NEW.TID = CONCAT(NEW.ULOGIN,'@',NEW.UHOST);
	END IF;
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_MDL_BEFINS` $$
USE `GOM`$$
CREATE TRIGGER A000_MDL_BEFINS BEFORE INSERT ON `A000_MDL` FOR EACH ROW
BEGIN
	SET NEW.CUSER = current_user();
    IF NEW.TID IS NULL THEN
		SET NEW.TID = DMA_generateTIDFromOBDTablename('A000_MDL');
    END IF;
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_MDL_AFTINS` $$
USE `GOM`$$
CREATE TRIGGER A000_MDL_AFTINS AFTER INSERT ON `A000_MDL` FOR EACH ROW
BEGIN
	CALL LOG_logDBDataEvent('INSERT',NEW.TID,NEW.BID);
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_MDL_BEFUPD` $$
USE `GOM`$$
CREATE TRIGGER A000_MDL_BEFUPD BEFORE UPDATE ON `A000_MDL` FOR EACH ROW
BEGIN
	SET NEW.UUSER = current_user();
    SET NEW.UDATE = current_timestamp();
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_MDL_AFTUPD` $$
USE `GOM`$$
CREATE TRIGGER A000_MDL_AFTUPD AFTER UPDATE ON `A000_MDL` FOR EACH ROW
BEGIN
	CALL LOG_logDBDataEvent('UPDATE',NEW.TID,NEW.BID);
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_OBD_BEFINS` $$
USE `GOM`$$
CREATE TRIGGER A000_OBD_BEFINS BEFORE INSERT ON `A000_OBD` FOR EACH ROW
BEGIN
	SET NEW.CUSER = current_user();
    IF NEW.IS_SYSTEM=0 OR (NEW.IS_SYSTEM=1 AND NEW.TID IS NULL) THEN
		SET NEW.TID = DMA_generateTIDFromOBDTablename('A000_OBD');
    END IF;
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_OBD_AFTINS` $$
USE `GOM`$$
CREATE TRIGGER A000_OBD_AFTINS AFTER INSERT ON `A000_OBD` FOR EACH ROW
BEGIN
	CALL LOG_logDBDataEvent('INSERT',NEW.TID,NEW.BID);
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_OBD_BEFUPD` $$
USE `GOM`$$
CREATE TRIGGER A000_OBD_BEFUPD BEFORE UPDATE ON `A000_OBD` FOR EACH ROW
BEGIN
	SET NEW.UUSER = current_user();
    SET NEW.UDATE = current_timestamp();
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_OBD_AFTUPD` $$
USE `GOM`$$
CREATE TRIGGER A000_OBD_AFTUPD AFTER UPDATE ON `A000_OBD` FOR EACH ROW
BEGIN
	CALL LOG_logDBDataEvent('UPDATE',NEW.TID,NEW.BID);
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_OBMD_BEFINS` $$
USE `GOM`$$
CREATE TRIGGER A000_OBMD_BEFINS BEFORE INSERT ON `A000_OBMD` FOR EACH ROW
BEGIN
	SET NEW.CUSER = current_user();
    SET NEW.TID = DMA_generateTIDFromOBDTablename('A000_OBMD');
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_OBMD_AFTINS` $$
USE `GOM`$$
CREATE TRIGGER A000_OBMD_AFTINS AFTER INSERT ON `A000_OBMD` FOR EACH ROW
BEGIN
CALL LOG_logDBDataEvent('INSERT',NEW.TID,NEW.BID);
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_OBMD_BEFUPD` $$
USE `GOM`$$
CREATE TRIGGER A000_OBMD_BEFUPD BEFORE UPDATE ON `A000_OBMD` FOR EACH ROW
BEGIN
	SET NEW.UUSER = current_user();
    SET NEW.UDATE = current_timestamp();
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_OBMD_AFTUPD` $$
USE `GOM`$$
CREATE TRIGGER A000_OBMD_AFTUPD AFTER UPDATE ON `A000_OBMD` FOR EACH ROW
BEGIN
	CALL LOG_logDBDataEvent('UPDATE',NEW.TID,NEW.BID);
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `Z000_LOGS_BEFINS` $$
USE `GOM`$$
CREATE TRIGGER Z000_LOGS_BEFINS BEFORE INSERT ON `Z000_LOGS` FOR EACH ROW
BEGIN
	SET NEW.CUSER = current_user();
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_LNKD_BEFINS` $$
USE `GOM`$$
CREATE  TRIGGER A000_LNKD_BEFINS BEFORE INSERT ON `A000_LNKD` FOR EACH ROW
BEGIN
	SET NEW.CUSER = current_user();
    SET NEW.TID = DMA_generateTIDFromOBDTablename('A000_LNKD');
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_LNKD_AFTINS` $$
USE `GOM`$$
CREATE  TRIGGER A000_LNKD_AFTINS AFTER INSERT ON `A000_LNKD` FOR EACH ROW
BEGIN
CALL LOG_logDBDataEvent('INSERT',NEW.TID,NEW.BID);
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_LNKD_BEFUPD` $$
USE `GOM`$$
CREATE TRIGGER A000_LNKD_BEFUPD BEFORE UPDATE ON `A000_LNKD` FOR EACH ROW
BEGIN
	SET NEW.UUSER = current_user();
    SET NEW.UDATE = current_timestamp();
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_LNKD_AFTUPD` $$
USE `GOM`$$
CREATE TRIGGER A000_LNKD_AFTUPD AFTER UPDATE ON `A000_LNKD` FOR EACH ROW
BEGIN
	CALL LOG_logDBDataEvent('UPDATE',NEW.TID,NEW.BID);
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_LNKMD_BEFINS` $$
USE `GOM`$$
CREATE TRIGGER A000_LNKMD_BEFINS BEFORE INSERT ON `A000_LNKMD` FOR EACH ROW
BEGIN
	SET NEW.CUSER = current_user();
    SET NEW.TID = DMA_generateTIDFromOBDTablename('A000_LNKMD');
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_LNKMD_AFTINS` $$
USE `GOM`$$
CREATE TRIGGER A000_LNKMD_AFTINS AFTER INSERT ON `A000_LNKMD` FOR EACH ROW
BEGIN
CALL LOG_logDBDataEvent('INSERT',NEW.TID,NEW.BID);
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_LNKMD_BEFUPD` $$
USE `GOM`$$
CREATE TRIGGER A000_LNKMD_BEFUPD BEFORE UPDATE ON `A000_LNKMD` FOR EACH ROW
BEGIN
	SET NEW.UUSER = current_user();
    SET NEW.UDATE = current_timestamp();
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A000_LNKMD_AFTUPD` $$
USE `GOM`$$
CREATE TRIGGER A000_LNKMD_AFTUPD AFTER UPDATE ON `A000_LNKMD` FOR EACH ROW
BEGIN
	CALL LOG_logDBDataEvent('UPDATE',NEW.TID,NEW.BID);
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_OBI_BEFINS` $$
USE `GOM`$$
CREATE TRIGGER A100_OBI_BEFINS BEFORE INSERT ON `A100_OBI` FOR EACH ROW
BEGIN
	SET NEW.CUSER = current_user();
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_OBI_AFTINS` $$
USE `GOM`$$
CREATE TRIGGER A100_OBI_AFTINS AFTER INSERT ON `A100_OBI` FOR EACH ROW
BEGIN
CALL LOG_logDBDataEvent('INSERT',NEW.TID,NEW.BID);
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_OBI_BEFUPD` $$
USE `GOM`$$
CREATE TRIGGER A100_OBI_BEFUPD BEFORE UPDATE ON `A100_OBI` FOR EACH ROW
BEGIN
	SET NEW.UUSER = current_user();
    SET NEW.UDATE = current_timestamp();
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_OBI_AFTUPD` $$
USE `GOM`$$
CREATE TRIGGER A100_OBI_AFTUPD AFTER UPDATE ON `A100_OBI` FOR EACH ROW
BEGIN
	CALL LOG_logDBDataEvent('UPDATE',NEW.TID,NEW.BID);
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_OBMI_BEFINS` $$
USE `GOM`$$
CREATE TRIGGER A100_OBMI_BEFINS BEFORE INSERT ON `A100_OBMI` FOR EACH ROW
BEGIN
	SET NEW.CUSER = current_user();
    SET NEW.TID = DMA_generateTIDFromOBDTablename('A100_OBMI');
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_OBMI_AFTINS` $$
USE `GOM`$$
CREATE TRIGGER A100_OBMI_AFTINS AFTER INSERT ON `A100_OBMI` FOR EACH ROW
BEGIN
CALL LOG_logDBDataEvent('INSERT',NEW.TID,NEW.BID);
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_OBMI_BEFUPD` $$
USE `GOM`$$
CREATE TRIGGER A100_OBMI_BEFUPD BEFORE UPDATE ON `A100_OBMI` FOR EACH ROW
BEGIN
	SET NEW.UUSER = current_user();
    SET NEW.UDATE = current_timestamp();
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_OBMI_AFTUPD` $$
USE `GOM`$$
CREATE TRIGGER A100_OBMI_AFTUPD AFTER UPDATE ON `A100_OBMI` FOR EACH ROW
BEGIN
	CALL LOG_logDBDataEvent('UPDATE',NEW.TID,NEW.BID);
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_LNKI_BEFINS` $$
USE `GOM`$$
CREATE TRIGGER A100_LNKI_BEFINS BEFORE INSERT ON `A100_LNKI` FOR EACH ROW
BEGIN
	SET NEW.CUSER = current_user();
    SET NEW.TID = DMA_generateTIDFromOBDTablename('A100_LNKI');
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_LNKI_AFTINS` $$
USE `GOM`$$
CREATE  TRIGGER A100_LNKI_AFTINS AFTER INSERT ON `A100_LNKI` FOR EACH ROW
BEGIN
CALL LOG_logDBDataEvent('INSERT',NEW.TID,NEW.BID);
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_LNKI_BEFUPD` $$
USE `GOM`$$
CREATE TRIGGER A100_LNKI_BEFUPD BEFORE UPDATE ON `A100_LNKI` FOR EACH ROW
BEGIN
	SET NEW.UUSER = current_user();
    SET NEW.UDATE = current_timestamp();
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_LNKI_AFTUPD` $$
USE `GOM`$$
CREATE TRIGGER A100_LNKI_AFTUPD AFTER UPDATE ON `A100_LNKI` FOR EACH ROW
BEGIN
	CALL LOG_logDBDataEvent('UPDATE',NEW.TID,NEW.BID);
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_LNKMI_BEFINS` $$
USE `GOM`$$
CREATE TRIGGER A100_LNKMI_BEFINS BEFORE INSERT ON `A100_LNKMI` FOR EACH ROW
BEGIN
	SET NEW.CUSER = current_user();
    SET NEW.TID = DMA_generateTIDFromOBDTablename('A100_LNKMI');
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_LNKMI_AFTINS` $$
USE `GOM`$$
CREATE TRIGGER A100_LNKMI_AFTINS AFTER INSERT ON `A100_LNKMI` FOR EACH ROW
BEGIN
CALL LOG_logDBDataEvent('INSERT',NEW.TID,NEW.BID);
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_LNKMI_BEFUPD` $$
USE `GOM`$$
CREATE  TRIGGER A100_LNKMI_BEFUPD BEFORE UPDATE ON `A100_LNKMI` FOR EACH ROW
BEGIN
	SET NEW.UUSER = current_user();
    SET NEW.UDATE = current_timestamp();
END$$


USE `GOM`$$
DROP TRIGGER IF EXISTS `A100_LNKMI_AFTUPD` $$
USE `GOM`$$
CREATE TRIGGER A100_LNKMI_AFTUPD AFTER UPDATE ON `A100_LNKMI` FOR EACH ROW
BEGIN
	CALL LOG_logDBDataEvent('UPDATE',NEW.TID,NEW.BID);
END$$


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
