<?php
if (!defined('ADMIN_FILE')) {
    die("Access Denied");
}
require_once('mainfile.php');
$module_name = 'siteFeature';
include('modules/' . $module_name . '/includes/class.upload.php');
global $db, $prefix, $admin_file, $admin, $name, $nukeurl;
DEFINE(IMAGE_PATH, 'modules/' . $module_name . '/images/');
DEFINE(SLIMIT, 35);
DEFINE(LLIMIT, 225);
DEFINE(SHORTTITLELIMIT, 30);
include('header.php');
OpenTable();
echo '<center>';
echo ' [ <a href="' . $admin_file . '.php?op=siteFeature">Site Feature Admin</a> ] ';
echo ' [ <a href="' . $admin_file . '.php?op=AddSlider">Add Slider</a> ] ';
echo ' [ <a href="' . $admin_file . '.php">Nuke Admin</a> ] ';
echo '<center>';
CloseTable();
switch ($op) {
    case 'AddSlider':
        OpenTable();
        echo '<form enctype="multipart/form-data" action="' . $admin_file . '.php?op=SaveSlider" method="POST">';
        echo '<div class="cptitles">Slider Title</div>';
        echo '<input type="text" name="title" size="54" />';
        echo '<div class="cptitles">Short Desc</div>';
        echo '<small>(Trimmed to ' . SLIMIT . ' characters)</small><br />';
        echo '<textarea name="shortdesc" rows="2" cols="50"></textarea>';
        echo '<div class="cptitles">Long Description</div>';
        echo '<small>(Trimmed to ' . LLIMIT . ' characters)</small><br />';
        echo '<textarea name="longdesc" rows="5" cols="50"></textarea>';
        echo '<div class="cptitles">Read More Url</div>';
        echo '<small>(Full url to read more link)</small><br />';
        echo '<input type="text" name="url" size="54" />';
        echo '<div class="cptitles">Slider Image</div>';
        echo '<small>(All Images will be resized to 500px w x 333px h <br />no matter their original size)</small><br />';
        echo '<input type="file" name="image" size="52" /><br />';
        echo '<input type="submit" name="addslider" value="Add Slider" />';
        echo '</form>';
        CloseTable();
        break;
    
    case 'EditSlider':
        OpenTable();
        $slid        = intval($_GET['slid']);
        $slider      = 'SELECT * FROM ' . $prefix . '_sf_slider WHERE slid = \'' . $slid . '\'';
        $queryslider = $db->sql_query($slider);
        $rowslider   = $db->sql_fetchrow($queryslider);
        $title       = stripslashes($rowslider['title']);
        $longdesc    = stripslashes($rowslider['longdesc']);
        $shortdesc   = stripslashes($rowslider['shortdesc']);
        $url         = stripslashes($rowslider['url']);
        $image       = stripslashes($rowslider['image']);
        echo '<form enctype="multipart/form-data" action="' . $admin_file . '.php?op=SaveSlider" method="POST">';
        echo '<div class="cptitles">Slider Title</div>';
        echo '<input type="text" name="title" value="' . $title . '" size="54" />';
        echo '<div class="cptitles">Short Desc</div>';
        echo '<small>(Trimmed to ' . SLIMIT . ' characters)</small><br />';
        echo '<textarea name="shortdesc" rows="2" cols="50">' . $shortdesc . '</textarea>';
        echo '<div class="cptitles">Long Description</div>';
        echo '<small>(Trimmed to ' . LLIMIT . ' characters)</small><br />';
        echo '<textarea name="longdesc" rows="5" cols="50">' . $longdesc . '</textarea>';
        echo '<div class="cptitles">Read More Url</div>';
        echo '<small>(Full url to read more link)</small><br />';
        echo '<input type="text" name="url" value="' . $url . '" size="54" />';
        echo '<div class="cptitles">Slider Image</div>';
        echo '<small>(All Images will be resized to 500px w x 333px h <br />no matter their original size)</small><br />';
        echo '<input type="file" name="image" size="52" /><br />';
        echo '<input type="hidden" name="slid" value="' . $slid . '" />';
        echo '<input type="hidden" name="sliderimage" value="' . $image . '" />';
        echo '<input type="submit" name="editslider" value="Edit Slider" />';
        echo '</form>';
        CloseTable();
        break;
    
    case 'SaveSlider':
        if ($_POST['addslider']) {
            // ---------- IMAGE UPLOAD ----------
            // we create an instance of the class, giving as argument the PHP object
            // corresponding to the file field from the form
            // All the uploads are accessible from the PHP object $_FILES
            $handle = new Upload($_FILES['image']);
            // then we check if the file has been uploaded properly
            // in its *temporary* location in the server (often, it is /tmp)
            if ($handle->uploaded) {
                // we now process the image a second time, with some other settings
                $handle->image_resize = true;
                $handle->image_x      = 500;
                $handle->image_y      = 333;
                $handle->Process(IMAGE_PATH);
                // we check if everything went OK
                if ($handle->processed) {
                    // everything was fine !
                    $title     = addslashes($_POST['title']);
                    $shortdesc = addslashes($_POST['shortdesc']);
                    $longdesc  = addslashes($_POST['longdesc']);
                    $url       = addslashes($_POST['url']);
                    $image     = addslashes($handle->file_dst_name);
                    if ($db->sql_query("INSERT INTO " . $prefix . "_sf_slider (slid, title, shortdesc, longdesc, url, image) VALUES (NULL, '" . $title . "', '" . $shortdesc . "', '" . $longdesc . "', '" . $url . "', '" . $image . "')")) {
                        header('location: ' . $admin_file . '.php?op=siteFeature');
                    } else {
                        echo mysql_error();
                        die();
                    }
                } else {
                    // one error occured
                    echo '<fieldset>';
                    echo '  <legend>file not uploaded to the wanted location</legend>';
                    echo '  Error: ' . $handle->error . '';
                    echo '</fieldset>';
                }
                // we delete the temporary files
                $handle->Clean();
            }
        } elseif ($_POST['editslider'] AND intval($_POST['slid']) > 0) {
            // ---------- IMAGE UPLOAD ----------
            // we create an instance of the class, giving as argument the PHP object
            // corresponding to the file field from the form
            // All the uploads are accessible from the PHP object $_FILES
            $handle = new Upload($_FILES['image']);
            // then we check if the file has been uploaded properly
            // in its *temporary* location in the server (often, it is /tmp)
            if ($handle->uploaded) {
                // we now process the image a second time, with some other settings
                $handle->image_resize = true;
                $handle->image_x      = 500;
                $handle->image_y      = 333;
                $handle->Process(IMAGE_PATH);
                // we check if everything went OK
                if ($handle->processed) {
                    // everything was fine !
                } else {
                    // one error occured
                    echo '<fieldset>';
                    echo '  <legend>file not uploaded to the wanted location</legend>';
                    echo '  Error: ' . $handle->error . '';
                    echo '</fieldset>';
                    die();
                }
            }
            $slid      = intval($_POST['slid']);
            $title     = addslashes($_POST['title']);
            $shortdesc = addslashes($_POST['shortdesc']);
            $longdesc  = addslashes($_POST['longdesc']);
            $url       = addslashes($_POST['url']);
            if ($handle->file_dst_name != '') {
                $image = addslashes($handle->file_dst_name);
            } else {
                $image = addslashes($_POST['sliderimage']);
            }
            if ($db->sql_query("UPDATE " . $prefix . "_sf_slider SET title = '" . $title . "', shortdesc = '" . $shortdesc . "', longdesc = '" . $longdesc . "', url = '" . $url . "', image = '" . $image . "' WHERE slid = '" . $slid . "'")) {
                header('location: ' . $admin_file . '.php?op=siteFeature');
            } else {
                echo mysql_error();
                die();
            }
            // we delete the temporary files
            $handle->Clean();
        } else {
            die('Possible Hack Attempt?');
        }
        break;
    
    case 'DeleteSlider':
        echo '<center>';
        if (intval($_POST['slid']) != 0 AND $_POST['deleteslider']) {
            $slid = intval($_POST['slid']);
            if ($db->sql_query('DELETE FROM ' . $prefix . '_sf_slider WHERE slid = \'' . $slid . '\'')) {
                header('location: ' . $admin_file . '.php?op=siteFeature');
            } else {
                echo mysql_error();
                die();
            }
        } else {
            echo 'Are you sure you want to delete this slider?';
            echo '<form action="' . $admin_file . '.php?op=DeleteSlider" method="POST">';
            echo '<input type="hidden" name="slid" value="' . $slid . '" />';
            echo '<input type="submit" name="deleteslider" value="Delete Slider" />';
            echo '</form>';
        }
        echo '</center>';
        break;
    
    default:
        OpenTable();
        //Do some Jquery
        echo '<script type="text/javascript">';
        echo "jQuery(document).ready(function() {
    jQuery('#preFeature').siteFeature({
    'tabsLocation': 'left',
    'tabBgImg': 'images/arrow-left.png',
    'tabBgImgIE6': 'images/arrow-left.gif',
    'txtBoxAnimateInType': 'slideLeft',
    'txtBoxAnimateOutType': 'slideRight',
    'containerWidth': '725px',
    'containerHeight': '333px',
    'autoPlay': true,
    'activeTabIsLink': true,
    'activeWindowIsLink': false,
    'autoPlayInterval': 5000,
    'pausehover': true
    });
    });";
        echo '</script>';
        echo '<div id="preFeature">';
        $sqlslider   = 'SELECT * FROM ' . $prefix . '_sf_slider ORDER BY slid desc LIMIT 0, 5';
        $queryslider = $db->sql_query($sqlslider);
        while ($rowslider = $db->sql_fetchrow($queryslider)) {
            $slid      = intval($rowslider['slid']);
            $title     = stripslashes($rowslider['title']);
            $longdesc  = chop_word(stripslashes($rowslider['longdesc']), LLIMIT);
            $shortdesc = chop_word(stripslashes($rowslider['shortdesc']), SLIMIT);
            $url       = stripslashes($rowslider['url']);
            $image     = stripslashes($rowslider['image']);
            echo '<div>
        <img src="' . IMAGE_PATH . $image . '" alt="' . $title . '" title="' . $shortdesc . '" />
        <h3>' . chop_word($title, SHORTTITLELIMIT) . '</h3>
        <p>' . $longdesc . '</p>
        <a href="' . $url . '">More</a>';
            if (is_admin($admin)) {
                echo '&nbsp;<a href="' . $admin_file . '.php?op=EditSlider&amp;slid=' . $slid . '">Edit</a>';
                echo '&nbsp;<a href="' . $admin_file . '.php?op=DeleteSlider&amp;slid=' . $slid . '">Delete</a>';
            }
            echo '</div>';
        }
        echo '</div>';
        echo '<div style="clear:both;"></div>';
        echo '<div>Existing Older Entries?</div>';
        echo '<ul style="list-style:none;">';
        $sqlslider2   = 'SELECT * FROM ' . $prefix . '_sf_slider ORDER BY slid desc';
        $queryslider2 = $db->sql_query($sqlslider2);
        echo mysql_error();
        while ($rowslider2 = $db->sql_fetchrow($queryslider2)) {
            $slid2      = intval($rowslider2['slid']);
            $title2     = stripslashes($rowslider2['title']);
            $longdesc2  = chop_word(stripslashes($rowslider2['longdesc']), LLIMIT);
            $shortdesc2 = chop_word(stripslashes($rowslider2['shortdesc']), SLIMIT);
            $url2       = stripslashes($rowslider2['url']);
            $image2     = stripslashes($rowslider2['image']);
            echo '<li><span>Database ID:' . $slid2 . '</span> | <span title="' . $title2 . '">' . $title2 . '</span> | <span><a href="' . $url2 . '" target="_blank">Click for url</a></span> | <span><a href="' . IMAGE_PATH . $image2 . '" target="_blank">Click for Image</a></span> | <span><a href="' . $admin_file . '.php?op=DeleteSlider&amp;slid=' . $slid2 . '">Delete</a></span></li>';
            echo '<div style="clear:both;"></div>';
        }
        echo '</ul>';
        CloseTable();
        break;
}
include('footer.php');
?>