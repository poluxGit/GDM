DELIMITER $$

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

-- -------------------------------------------------------------------------- --
--  Création d'une nouvelle definition d'objet.
-- -------------------------------------------------------------------------- --
-- Exemple : E1.OBD-CAT_0002
-- -------------------------------------------------------------------------- --
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

-- ************************************************************************** --
-- FONCTIONS OUTILS          																								  --
-- ************************************************************************** --

-- -------------------------------------------------------------------------- --
--  Retourne le code du Model
-- -------------------------------------------------------------------------- --
-- Paramètres :
--  p_sMDLTid VARCHAR(100) : TID du Model
-- Retour :
--  Code trouvée VARCHAR(100) | NULL si non trouvé
-- -------------------------------------------------------------------------- --
CREATE FUNCTION DMA_getMDLCode(p_sMDLTid VARCHAR(100)) RETURNS VARCHAR(2) DETERMINISTIC
BEGIN
	DECLARE l_sResult 				VARCHAR(2) DEFAULT NULL;
	-- Code du Model (TODO Faire fonction)
  SELECT CODE INTO l_sResult FROM A000_MDL WHERE TID = p_sMDLTid;
	RETURN l_sResult;
END$$

-- -------------------------------------------------------------------------- --
--  Fonction principale de génération de TID de l'application
-- -------------------------------------------------------------------------- --
-- Paramètres :
--  p_sOBDTid VARCHAR(100) : TID de la définition d'objet concernée
-- Retour :
--  TID généré : VARCHAR(100) | NULL si OBD non trouvée
-- -------------------------------------------------------------------------- --
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

-- -------------------------------------------------------------------------- --
--  Retourne un TID généré depuis le nom de la tbale concernée
-- -------------------------------------------------------------------------- --
-- Paramètres :
--  p_sOBDTablename VARCHAR(200) : Nom de la table concernée
-- Retour :
--  TID généré : VARCHAR(100) | NULL si OBD non trouvée
-- -------------------------------------------------------------------------- --
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


-- -------------------------------------------------------------------------- --
--  Retourne le Prefix du type d'objet
-- -------------------------------------------------------------------------- --
-- Paramètres :
--  p_sTypeOBD ENUM('Simple','Complex','Specific') : Type de l'OBD
-- Retour :
--  Prefix généré : VARCHAR(3) IN S,C,SPE
-- -------------------------------------------------------------------------- --
CREATE FUNCTION DMA_getTIDPrefixFromOBDType(p_sTypeOBD ENUM('Simple','Complex','Specific')) RETURNS VARCHAR(3) NO SQL
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
