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

UPDATE config SET value="0.5" WHERE token = "database_version";

INSERT INTO `config` (`id`, `category`, `token`, `value`, `options`, `last_update`, `readonly`) 
VALUES (NULL, '0', 'build_number', '(Build 22-10-2016)', '', sysdate(), '1');