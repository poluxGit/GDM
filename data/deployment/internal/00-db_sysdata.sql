-- ************************************************************************** --
-- Script d'intégration des données systèmes indipensable
-- ************************************************************************** --

DELIMITER ;

-- MDL - Modèle Système
-- -------------------------------------------------------------------------- --
INSERT INTO A000_MDL (
	`TID`,
    `BID`,
    `CODE`,
    `BIDCODE`,
    `VERSION`,
    `STITLE`,
    `LTITLE`)
VALUES ('MDL-SI-0001', 'MDL-SYS', 'SI', 'SYS', '01', 'Modèle système', 'Modèle des Objets système');
-- ANALYZE TABLE Z000_LOGS;
-- ANALYZE TABLE A000_MDL;

-- OBD : Définition d'Objets
-- -------------------------------------------------------------------------- --
-- Définition Objet OBD
INSERT INTO A000_OBD (`TID`,`MDL_TID`,`BID`,`STITLE`, `LTITLE`,`OBI_DB_TABLENAME`,`OBD_TYPE`,`OBD_TID_NUMLEN`,`OBD_TID_SPREFIX`,`OBD_BID_PREFIX`,`IS_SYSTEM`)
VALUES ('SI.OBD-SPE-00001','MDL-SI-0001','ODB.SYS-OBD','OBject Definition', 'Objet de définition d\'autre objet','A000_OBD','Specific',5,'OBD','OBD',1);
-- ANALYZE TABLE Z000_LOGS;
-- ANALYZE TABLE A000_OBD;

-- Définition Objet MDL
INSERT INTO A000_OBD (`MDL_TID`,`BID`,`STITLE`, `LTITLE`,`OBI_DB_TABLENAME`,`OBD_TYPE`,`OBD_TID_NUMLEN`,`OBD_TID_SPREFIX`,`OBD_BID_PREFIX`,`IS_SYSTEM`)
VALUES ('MDL-SI-0001','ODB.SYS-MDL','MoDeL', 'Modele de données - MDL','A000_MDL','Specific',4,'MDL','MDL',1);
-- ANALYZE TABLE Z000_LOGS;
-- ANALYZE TABLE A000_OBD;

-- Définition Objet LNKD
INSERT INTO A000_OBD (`MDL_TID`,`BID`,`STITLE`, `LTITLE`,`OBI_DB_TABLENAME`,`OBD_TYPE`,`OBD_TID_NUMLEN`,`OBD_TID_SPREFIX`,`OBD_BID_PREFIX`,`IS_SYSTEM`)
VALUES ('MDL-SI-0001','ODB.SYS-LNKD','LiNK Definition', 'Définition de Lien - LNKD','A000_LNKD','Specific',5,'LNKD','LNKD',1);
-- ANALYZE TABLE Z000_LOGS;
-- ANALYZE TABLE A000_OBD;

-- Définition Objet OBMD
INSERT INTO A000_OBD (`MDL_TID`,`BID`,`STITLE`, `LTITLE`,`OBI_DB_TABLENAME`,`OBD_TYPE`,`OBD_TID_NUMLEN`,`OBD_TID_SPREFIX`,`OBD_BID_PREFIX`,`IS_SYSTEM`)
VALUES ('MDL-SI-0001','ODB.SYS-OBMD','OBject Meta Defintion', 'Objet définissant les MetatDonnées relatives à un OBD.','A000_OBMD','Specific',10,'OBMD','OBMD',1);
-- ANALYZE TABLE Z000_LOGS;
-- ANALYZE TABLE A000_OBD;

-- Définition Objet LNKMD
INSERT INTO A000_OBD (`MDL_TID`,`BID`,`STITLE`, `LTITLE`,`OBI_DB_TABLENAME`,`OBD_TYPE`,`OBD_TID_NUMLEN`,`OBD_TID_SPREFIX`,`OBD_BID_PREFIX`,`IS_SYSTEM`)
VALUES ('MDL-SI-0001','ODB.SYS-LNKMD','LiNK Meta Definition', 'Définition de metadonnées sur Lien - LNKMD','A000_LNKMD','Specific',10,'LNKD','LNKD',1);
-- ANALYZE TABLE Z000_LOGS;
-- ANALYZE TABLE A000_OBD;

-- Définition Objet OBI
INSERT INTO A000_OBD (`MDL_TID`,`BID`,`STITLE`, `LTITLE`,`OBI_DB_TABLENAME`,`OBD_TYPE`,`OBD_TID_NUMLEN`,`OBD_TID_SPREFIX`,`OBD_BID_PREFIX`,`IS_SYSTEM`)
VALUES ('MDL-SI-0001','ODB.SYS-OBI','OBject Instance', 'Instance d\'Objet - OBI','A100_OBI','Specific',20,'OBI','OBI',1);
-- ANALYZE TABLE Z000_LOGS;
-- ANALYZE TABLE A000_OBD;

-- Définition Objet LNKI
INSERT INTO A000_OBD (`MDL_TID`,`BID`,`STITLE`, `LTITLE`,`OBI_DB_TABLENAME`,`OBD_TYPE`,`OBD_TID_NUMLEN`,`OBD_TID_SPREFIX`,`OBD_BID_PREFIX`,`IS_SYSTEM`)
VALUES ('MDL-SI-0001','ODB.SYS-LNKI','LiNK Instance', 'Instance de liens entre Objet - LNKI','A100_LNKI','Specific',20,'LNKI','LNKI',1);
-- ANALYZE TABLE Z000_LOGS;
-- ANALYZE TABLE A000_OBD;

-- Définition Objet OBMI
INSERT INTO A000_OBD (`MDL_TID`,`BID`,`STITLE`, `LTITLE`,`OBI_DB_TABLENAME`,`OBD_TYPE`,`OBD_TID_NUMLEN`,`OBD_TID_SPREFIX`,`OBD_BID_PREFIX`,`IS_SYSTEM`)
VALUES ('MDL-SI-0001','ODB.SYS-OBMI','OBject Meta Instance', 'Instance de metadonnées d\'Objet - OBMI','A100_OBMI','Specific',20,'OBMI','OBMI',1);
-- ANALYZE TABLE Z000_LOGS;
-- ANALYZE TABLE A000_OBD;

-- Définition Objet LNKMI
INSERT INTO A000_OBD (`MDL_TID`,`BID`,`STITLE`, `LTITLE`,`OBI_DB_TABLENAME`,`OBD_TYPE`,`OBD_TID_NUMLEN`,`OBD_TID_SPREFIX`,`OBD_BID_PREFIX`,`IS_SYSTEM`)
VALUES ('MDL-SI-0001','ODB.SYS-LNKMI','LiNK Meta Instance', 'Instance de metadonnées sur Lien - LNKMI','A100_LNKMI','Specific',20,'LNKMI','LNKMI',1);
-- ANALYZE TABLE Z000_LOGS;
-- ANALYZE TABLE A000_OBD;
