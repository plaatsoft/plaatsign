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

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `token` varchar(32) NOT NULL,
  `value` varchar(128) NOT NULL,
  `options` varchar(255) NOT NULL,
  `last_update` date NOT NULL,
  `readonly` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `config` (`id`, `category`, `token`, `value`, `options`, `last_update`, `readonly`) VALUES
(1, 0, 'database_version', '0.1', '', '2016-09-24', 1),
(2, 0, 'slide_show_delay', '20', '', '2016-09-24', 0);

CREATE TABLE `content` (
  `cid` int(11) NOT NULL,
  `filename` varchar(128) NOT NULL,
  `filesize` int(11) NOT NULL,
  `enabled` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `session` (
  `sid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `session` varchar(50) DEFAULT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `user` (
  `uid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(128) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `language` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `last_activity` datetime DEFAULT NULL,
  `role` int(11) NOT NULL,
  `requests` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


INSERT INTO `user` (`uid`, `username`, `password`, `name`, `email`, `language`, `created`, `last_activity`, `role`, `requests`) VALUES
(1, 'admin', '$2y$12$oOuOKHZBRJaCSXex6A607Olf4qBn3QgyTCjosJ4wmFFWyzmKUtxVS', 'Administrator', 'admin@plaatsoft.nl', 0, '2016-09-24 09:37:53', '2016-09-24 14:33:18', 1, 1);


ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `content`
  ADD PRIMARY KEY (`cid`);

ALTER TABLE `session`
  ADD PRIMARY KEY (`sid`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`);

ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `content`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

ALTER TABLE `session`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;


