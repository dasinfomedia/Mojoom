DROP TABLE IF EXISTS `#__mojoom_config`;
CREATE TABLE IF NOT EXISTS `#__mojoom_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `iphone_template` varchar(100) NOT NULL,
  `iphonetemplatetheme` varchar(100) NOT NULL,
  `iphonelogo` varchar(255) NOT NULL,
  `iphonejoomlasearch` tinyint(1) NOT NULL,
  `socialneticons` tinyint(1) NOT NULL,
  `facebookicon` varchar(100) NOT NULL,
  `facebooklink` varchar(255) NOT NULL,
  `twittericon` varchar(100) NOT NULL,
  `twitterlink` varchar(255) NOT NULL,
  `linkedinicon` varchar(100) NOT NULL,
  `linkedinlink` varchar(255) NOT NULL,
  `iphonehomepage` varchar(255) NOT NULL,
  `iphoneprofilepage` varchar(255) NOT NULL,
  `iphoneaboutuspage` varchar(255) NOT NULL,
  `iphonemorepage` varchar(255) NOT NULL,
  `tmpl_iphone_module1` varchar(100) NOT NULL,
  `tmpl_iphone_module2` varchar(100) NOT NULL,
  `tmpl_iphone_module3` varchar(100) NOT NULL,
  `iphonefooter` varchar(255) NOT NULL,
  `iphonebacktotop` tinyint(1) NOT NULL,
  `date` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

