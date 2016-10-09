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
VALUES (NULL, '0', 'build_number', '(Build 09-10-2016)', '', '2016-10-09', '1');

--CREATE TABLE `rank` (
--  `rid` int(11) NOT NULL,
--  `tid` int(11) NOT NULL
--) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--ALTER TABLE `rank` ADD PRIMARY KEY (`rid`);
--ALTER TABLE `rank` MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT;

--INSERT INTO rank (tid) SELECT cid FROM content order by cid;