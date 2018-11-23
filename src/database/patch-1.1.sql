--
--  ===========
--  PlaatSign
--  ===========
--
--  Created by wplaat
--
--  For more information visit the following website.
--  Website : www.plaatsoft.nl 
--
--  Or send an email to the following address.
--  Email   : info@plaatsoft.nl
--
--  All copyrights reserved (c) 1996-2018 PlaatSoft
--

UPDATE config SET value="1.1" WHERE token = "database_version";
UPDATE config SET value="(Build 22-11-2018)" WHERE token = "build_number";

ALTER TABLE `content` ADD `parameters` VARCHAR(255) NOT NULL AFTER `tid`;