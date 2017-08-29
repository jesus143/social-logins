<?php

/**
 * Created by Sonu Kumar (sonu29392@gmail.com).
 * User: RAPS-Developer
 * Date: 7/28/2017
 * Time: 12:46 PM
 */
if (isset($_REQUEST['id'])) {
    error_reporting(E_ALL);
    $id = $_REQUEST['id'];
    $cid = $_REQUEST['cid'];

    //$file_visitor = glob('../visitor_'.$cid.'_'.$id . '.txt');
    $file_visitor = glob('../visitor_*_' . $id . '.txt');
    $file_ip = glob('../*_' . $id . '.txt');
    $file_json = glob('../*_' . $id . '.json');

    if (!empty($file_visitor)) {
        unlink($file_visitor['0']);
    }
    if (!empty($file_ip)) {
        unlink($file_ip['0']);
    }
    if (!empty($file_json)) {
        unlink($file_json['0']);
    }

    //header("url=http://suite.social/coder/share/connect/admin-share/index.php?cid=".$cid);
        echo "<!DOCTYPE html>";
    echo "<head>";
    echo "<title>Form submitted</title>";
    echo "<script type='text/javascript'>window.parent.location.reload()</script>";
    echo "</head>";
    echo "<body></body></html>";

    exit;
}
