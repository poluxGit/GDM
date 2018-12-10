DELIMITER $$

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

CREATE PROCEDURE LOG_logSQLQuery (pStrSQLQuery VARCHAR(4000))
BEGIN
		DECLARE l_sFinalMessage VARCHAR(4000);

		SET l_sFinalMessage = CONCAT('SQL Query Executed : ',pStrSQLQuery);
		CALL LOG_logItem('SQL Query',l_sFinalMessage,NULL,NULL);
END$$

CREATE PROCEDURE LOG_logDBDataEvent( p_sTypeDataEvent ENUM('INSERT','UPDATE','DELETE'),
																		 p_sOBDTid VARCHAR(100),
																	   p_sOBDBid VARCHAR(200))
BEGIN
		DECLARE l_sFinalMessage VARCHAR(4000);

		SET l_sFinalMessage = CONCAT('DataEvent "',p_sTypeDataEvent,'" on object with TID "',p_sOBDTid,'"|BID "',p_sOBDBid,'".');
		CALL LOG_logItem('DataEvent',l_sFinalMessage,p_sOBDTid,p_sOBDBid);
END$$

CREATE PROCEDURE LOG_logMessage(p_sMessage VARCHAR(4000),
																p_sOBDTid VARCHAR(100),
																p_sOBDBid VARCHAR(200))
BEGIN
	CALL LOG_logItem('Information',p_sMessage,p_sOBDTid,p_sOBDBid);
END$$

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
