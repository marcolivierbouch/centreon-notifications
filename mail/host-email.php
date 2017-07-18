#!/usr/bin/php -c /etc/php.ini

<?php

    array_shift($argv);
    $f_notify_type = array_shift($argv);
    $f_host_name = array_shift($argv);
    $f_host_alias = array_shift($argv);
    $f_host_state = array_shift($argv);
    $f_host_address = array_shift($argv);
    $f_host_output = array_shift($argv);
    $f_long_date = array_shift($argv);
    $f_serv_desc = array_shift($argv);
    $f_serv_state = array_shift($argv);
    $f_to = array_shift($argv);
    $f_totalup = array_shift($argv);
    $f_totaldown = array_shift($argv);
    $f_ackauthor = array_shift($argv);
    $f_ackcomment = array_shift($argv);
    $userAlias = array_shift($argv);
    $userType = array_shift($argv);

    if($f_host_state == "RECOVERY") {$f_color = "#f48400";}
    if($f_host_state == "DOWN") {$f_color = "#f40000";}
    if($f_host_state == "UP") {$f_color = "#00b71a";}

    $serverNameOfDB = "127.0.0.1";
    $userNameForDB = "MyUser";
    $passwordForDB = "MyPass";
    $dbName = "centreon";
    $url = "MyIp";
    $from = "centreon@WhatIwant";

    $connection = new mysqli($serverNameOfDB, $userNameForDB, $passwordForDB, $dbName);
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    $sql = "SELECT contact_autologin_key FROM contact WHERE contact_alias = '$userAlias';";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();
    $token = $row["contact_autologin_key"];

    $subject = "[CENTREON] $f_notify_type Host:$f_host_name";

    $body = "<html><body><table border=0 width='98%' cellpadding=0 cellspacing=0><tr><td valign='top'>\n";
    $body .= "<table border=0 cellpadding=0 cellspacing=0 width='98%'>\n";
    $body .= "<tr bgcolor=$f_color><td width='140'><b><font color=#ffffff>Host: </font></b></td><td><font color=#ffffff><b> $f_notify_type [$f_host_state]</b></font></td></tr> \n";
    if($f_ackauthor!="" && $f_ackcomment!=""){
        $body .= "<tr bgcolor=$f_color><td width='140'><b><font color=#ffffff>$f_ackauthor:</font></b></td><td><font color=#ffffff><b>$f_ackcomment</b></font></td></tr>\n";
    }
    $body .= "<tr bgcolor=#eeeeee><td><b>Hostname: </b></td><td><b><a href='$url/centreon/main.php?p=20202&o=hd&host_name=$f_host_name&autologin=1&useralias=$userAlias&token=$token'>$f_host_alias</a></b></td></tr>\n";
    $body .= "<tr bgcolor=#fefefe><td><b>Address: </b></td><td><b>$f_host_address</b></td></tr>\n";
    $body .= "<tr bgcolor=#eeeeee><td><b>Date/Time: </b></td><td>$f_long_date</td></tr>\n";
    $body .= "<tr bgcolor=#fefefe><td><b>Info: </b></td><td><font color=$f_color>$f_host_output</font></td></tr>\n";
    if ($userType == "admin"){
        $body .= "<tr bgcolor=#eeeeee><td><b>Total hosts Up: </b></td><td>$f_totalup</td></tr>\n";
        $body .= "<tr bgcolor=#fefefe><td><b>Total hosts Down: </b></td><td>$f_totaldown</td></tr>\n";
    }
    $body .= "<tr bgcolor=#eeeeee><td><b>Actions: </b></td><td><a href='$url/centreon/main.php?p=20202&o=hd&host_name=$f_host_name&cmd=72&select[$f_host_name]=1&autologin=1&useralias=$userAlias&token=$token'><b>Acknowledge</b></a></td></tr>\n";
    $body .= "</table></body></html> \n";

    $headers = 'MIME-Version: 1.0'."\n";
    $headers .= 'Content-Type: text/html; charset=UTF-8'."\n";
    $headers .= "From: $from"."\n";
    
    /* Send eMail Now... */
   $m_true =  mail($f_to, $subject, $body, $headers);
   echo $m_true;
?>




