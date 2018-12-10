DELIMITER $$

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
