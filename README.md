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

###Mainfile.php Edit

Before the closing ?> add

<pre><code>function chop_word($val, $cut_len) {
    $tot_len = strlen($val);
    $cut_str = substr($val, 0, $cut_len);
    $len = strlen($cut_str);
    for($i=0;$i < $len;$i++) {
        for($i=0;$i < $len;$i++) {
            if(ord($val[$i]) > 127) $hanlen++;
            else $englen++;
        }
        $cut_gap = $hanlen % 2;
        if($cut_gap == 1){
            $hanlen--;
        }
        $length=$hanlen + $englen;

        if($tot_len > $length){
            return substr($val, 0, $length)."...";
        } else {
            return substr($val, 0, $length);
        }
    }
}</code></pre>

