DROP TABLE IF EXISTS `#__advancedordermanager`;

CREATE TABLE `#__advancedordermanager` (
  `id` int(11) NOT NULL auto_increment,
  `searchname` varchar(240) NOT NULL,
  `savequery` text NOT NULL,
  `description` varchar(240) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

