<?php
print " <br> current url $current_url";
define("MY_CONSTANT", 1);
print "<pre>";
print "<br>constant";
print_r(get_defined_constants(true)['user']);
print "<hr><br>session";
print_r($_SESSION);
//print_r($_SESSION['twitter']);
print "<hr><br>cookie";
print_r($_COOKIE);
print "</pre>";

