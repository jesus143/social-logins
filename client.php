<?php 
session_start();
if (isset($_GET["id"]) && $_GET["id"] > 0) {
        require_once "sharelock.class.php";
        $sharelock = new sharelock();
        $share_id = $_GET["id"];
        $ip = 1;
        $client_id = $_GET["cid"];
        $total_visits = $sharelock->visitor($share_id, $ip, $client_id);
        header("Refresh:0; url=client.php");
    }

    ?>