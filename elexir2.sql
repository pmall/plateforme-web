CREATE TABLE IF NOT EXISTS `analyses` (
  `id` varchar(6) NOT NULL,
  `id_project` varchar(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `version` enum('fdb1','fdb2') NOT NULL,
  `type` enum('simple','compose','apriori','jonction') NOT NULL,
  `paired` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_project` (`id_project`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;

CREATE TABLE IF NOT EXISTS `chips` (
  `id_project` varchar(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `condition` varchar(20) NOT NULL,
  `num` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_project`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `groups` (
  `id_analysis` varchar(6) NOT NULL,
  `condition` varchar(20) NOT NULL,
  `letter` enum('A','B','C','D') NOT NULL,
  PRIMARY KEY (`id_analysis`,`condition`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_project` varchar(6) NOT NULL,
  `id_analysis` varchar(6) DEFAULT NULL,
  `type` enum('qc','preprocessing','analysis','excels') NOT NULL,
  `status` enum('starting','waiting','processing','done') NOT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

CREATE TABLE IF NOT EXISTS `projects` (
  `id` varchar(6) NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `dir` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('exon','ggh') NOT NULL,
  `organism` enum('human','mouse') NOT NULL,
  `cell_line` varchar(20) NOT NULL,
  `comment` text NOT NULL,
  `public` tinyint(1) NOT NULL,
  `date` datetime NOT NULL,
  `dirty` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `salt` varchar(23) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;
