#!/usr/bin/php -c /etc/php.ini

<?php

    array_shift($argv);
    $f_notify_type =array_shift($argv);  /*1*/
    $f_host_name =array_shift($argv);    /*2*/
    $f_host_alias =array_shift($argv);   /*3*/
    $f_host_state =array_shift($argv);    /*4*/
    $f_host_address =array_shift($argv);   /*5*/
    $f_serv_output =array_shift($argv);   /*6*/
    $f_long_date =array_shift($argv);     /*7*/
    $f_serv_desc  =array_shift($argv);    /*8*/
    $f_serv_state  =array_shift($argv);   /*9*/
    $f_to  =array_shift($argv);           /*10*/
    $f_duration = round((array_shift($argv))/60,2);   /*11*/
    $f_exectime =array_shift($argv);       /*12*/
    $f_totwarnings =array_shift($argv);     /*13*/
    $f_totcritical =array_shift($argv);      /*14*/
    $f_totunknowns =array_shift($argv);     /*15*/
    $f_lastserviceok = array_shift($argv);    /*16*/
    $f_lastwarning = array_shift($argv);     /*17*/
    $f_attempts= array_shift($argv);     /*18*/
    $f_ackauthor= array_shift($argv);     /*19*/
    $f_ackcomment= array_shift($argv);     /*20*/
    $userType = array_shift($argv);      //21

    $f_downwarn = $f_duration;
    $f_color="#dddddd";
    if($f_serv_state=="WARNING") {$f_color="#f48400";}
    if($f_serv_state=="CRITICAL") {$f_color="#f40000";}
    if($f_serv_state=="OK") {$f_color="#00b71a";}
    if($f_serv_state=="UNKNOWN") {$f_color="#cc00de";}

    $f_serv_output = str_replace("(","/",$f_serv_output);
    $f_serv_output = str_replace(")","/",$f_serv_output);
    $f_serv_output = str_replace("[","/",$f_serv_output);
    $f_serv_output = str_replace("]","/",$f_serv_output);

    $serviceName = substr($f_serv_output, 0, strpos($f_serv_output, ":"));
    $subject = "[CENTREON] $f_notify_type $f_host_name/$f_serv_desc [$f_serv_state]";

    $url = "MyIp";  
    $userName = "MyUserNameForAutoLogin"; 
    $token = "MytokenForAutoLogin";  

    $from = "centreon@WhatIwant";   
    $body = "<html><body><table border=0 width='98%' cellpadding=0 cellspacing=0><tr><td valign='top'>\n";
    $body .= "<table border=0 cellpadding=0 cellspacing=0 width='98%'>";
    $body .= "<tr bgcolor=$f_color><td width='140'><b><font color=#ffffff>Notification:</font></b></td><td><font ";
    $body .= "color=#ffffff><b>$f_notify_type [$f_serv_state]</b></font></td></tr>\n";
    if($f_ackauthor!="" && $f_ackcomment!=""){
        $body .= "<tr bgcolor=$f_color><td width='140'><b><font color=#ffffff>$f_ackauthor:</font></b></td><td><font color=#ffffff><b>$f_ackcomment</b></font></td></tr>\n";
    }
    $body .= "<tr bgcolor=#eeeeee><td><b>Service:</b></td><td><font color=#0000CC><b><a href='$url/centreon/main.php?p=20201&o=svcd&host_name=$f_host_name&service_description=$f_serv_desc'>$f_serv_desc</a></b></font></td></tr>\n";
    $body .= "<tr bgcolor=#fefefe><td><b>Host:</b></td><td><font color=#0000CC><b><a href='$url/centreon/main.php?p=20202&o=hd&host_name=$f_host_name'>$f_host_alias</a></b></td></tr>\n";
    $body .= "<tr bgcolor=#fefefe><td><b>Address:</b></td><td><b>$f_host_address</b></font></td></tr>\n";
    $body .= "<tr bgcolor=#eeeeee><td><b>Date/Time:</b></td><td>$f_long_date UTC</td></tr>\n";
    $body .= "<tr bgcolor=#fefefe><td><b>Additional Info:</b></td><td><font color=$f_color>$f_serv_output</font></td></tr>\n";
    $body .= "<tr bgcolor=#eeeeee><td><b>Commands:</b></td><td><a target='external' href='$url/centreon/main.php?p=202&o=svc_unhandled&search=$f_serv_desc&host_search=$f_host_name&cmd=70&select[$f_host_name%253B$f_serv_desc]&autologin=1&useralias=$userName&token=$token'><b>Acknowledge</b></a></td></tr>\n";
    if ($userType == "admin"){
        $body .= "</td><td valign='top'></tr></table><table border=0 cellpadding=0 cellspacing=0 width='96%'><tr bgcolor=#000055><td width='140'><b> \n";
        $body .= "<font color=#FFFFFF>Service Summary</font></b></td><td>.</td></tr> \n";
        $body .= "<tr bgcolor=#fefefe><td>Service <b>DOWN</b> For: </td><td> $f_downwarn<i>m</i></td></tr>\n";
        $body .= "<tr bgcolor=#eeeeee><td>Total Warnings: </td><td> $f_totwarnings</td></tr>\n";
        $body .= "<tr bgcolor=#fefefe><td>Total Critical: </td><td> $f_totcritical</td></tr>\n";
        $body .= "<tr bgcolor=#eeeeee><td>Total Unknowns: </td><td> $f_totunknowns</td></tr>\n";
    }    
    $body .= "</body></html> \n";
    
    $headers = 'MIME-Version: 1.0'."\n";
    $headers .= 'Content-Type: text/html; charset=UTF-8'."\n";
    $headers .= "From: $from"."\n";
    /* Send eMail Now... */
    $m_true = mail($f_to, $subject, $body, $headers);
    echo $m_true;
?>
