<?php
$installer = $this;
$installer->startSetup();
$installer->run("
DROP PROCEDURE IF EXISTS CzRegions;
DELIMITER //
CREATE PROCEDURE CzRegions()
BEGIN

    DECLARE country_code VARCHAR(2) DEFAULT '';
    DECLARE locale VARCHAR(5) DEFAULT '';
    DECLARE region_codes VARCHAR(255) DEFAULT '';
    DECLARE region_names VARCHAR(255) DEFAULT '';
    DECLARE region_code VARCHAR(255) DEFAULT '';
    DECLARE region_name VARCHAR(255) DEFAULT '';
    DECLARE region_id INT DEFAULT 0;

    SET @country_code = 'CZ';
    SET @locale = 'cs_CZ';
    SET @region_codes = 'PHA,STC,JHC,PLK,KVK,ULK,LBK,HKK,PAK,VYS,JHM,OLK,ZLK,MSK,';
    SET @region_names = 'Hlavní město Praha,Středočeský kraj,Jihočeský kraj,Plzeňský kraj,Karlovarský kraj,Ústecký kraj,Liberecký kraj,Královéhradecký kraj,Pardubický kraj,Kraj Vysočina,Jihomoravský kraj,Olomoucký kraj,Zlínský kraj,Moravskoslezský kraj,';

        countryregions: REPEAT
			
			SET @region_code = SUBSTRING(@region_codes, 1, LOCATE(',',@region_codes) - 1);
			SET @region_name = SUBSTRING(@region_names, 1, LOCATE(',',@region_names) - 1);
        		SET @region_codes = SUBSTRING(@region_codes, LOCATE(',', @region_codes) + 1);
			SET @region_names = SUBSTRING(@region_names, LOCATE(',', @region_names) + 1);
				
			INSERT INTO {$this->getTable('directory_country_region')} (`region_id`,`country_id`,`code`,`default_name`) VALUES (NULL,@country_code,@region_code,@region_name);
		
			SET @region_id = LAST_INSERT_ID();
			INSERT INTO {$this->getTable('directory_country_region_name')} (`locale`,`region_id`,`name`) VALUES (@locale,@region_id,@region_name);
			
        UNTIL (LOCATE(',', @region_codes) <= 0)
        END REPEAT countryregions;

END;
//

CALL CzRegions()//

DROP PROCEDURE CzRegions;

");
$installer->endSetup();
?>
