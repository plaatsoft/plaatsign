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
--  All copyrights reserved (c) 2008-2016 PlaatSoft
--

UPDATE config SET value="1.0" WHERE token = "database_version";

UPDATE config SET value="(Build 09-11-2018)" WHERE token = "build_number";

INSERT INTO `config` ( `category`, `token`, `value`, `options`, `last_update`, `readonly`) VALUES
(0, 'plaatenergy_dbhost', '127.0.0.1', '', SYSDATE(), 0);

INSERT INTO `config` ( `category`, `token`, `value`, `options`, `last_update`, `readonly`) VALUES
(0, 'plaatenergy_dbname', 'plaatenergy', '', SYSDATE(), 0);

INSERT INTO `config` ( `category`, `token`, `value`, `options`, `last_update`, `readonly`) VALUES
(0, 'plaatenergy_dbuser', 'plaatenergy', '', SYSDATE(), 0);

INSERT INTO `config` ( `category`, `token`, `value`, `options`, `last_update`, `readonly`) VALUES
(0, 'plaatenergy_dbpass', 'plaatenergy', '', SYSDATE(), 0);


