sitefeatue-module
=================

A simple module to manage the site feature slider from codecanyon.

###Sql Table for Inserting Sliders

<pre><code>
    -- ----------------------------
-- Table structure for `nuke_sf_slider`
-- ----------------------------
DROP TABLE IF EXISTS `nuke_sf_slider`;
CREATE TABLE `nuke_sf_slider` (
  `slid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `longdesc` varchar(255) DEFAULT NULL,
  `shortdesc` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`slid`)
) ENGINE=MyISAM;
</code></pre>

