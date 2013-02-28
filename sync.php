<?php
    include "connection.php";

    require_once("util.php");

    $rid = "";
    $sid = "";
    $code = "";
 
    if(isset($_GET["sid"]) && isset($_GET["usercode"]))
    {
        $sid = $_GET["sid"];
        $usercode = $_GET["usercode"];
        
        //echo $usercode;

        $email = "";
        $uid = -1;
        $sql = "SELECT idUser, email FROM Users WHERE confirmationCode = '$usercode'";
        $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

        //if(mysql_num_rows($exe)==1)
        if($row = mysql_fetch_array($exe))
        {
            $uid = $row['idUser'];
            $email = $row['email'];
        }

        //echo "USER=$uid EMAIL=$email";

        if($uid == -1)
            die("NO USER"); // no user

        // ================================================
        // Checking authentication into section or research
        // ================================================

        $sql = "SELECT S.`idSection` as sid, S.`title` as sname FROM Sections as S, SectionMembers as SM, Researches as R, ResearchMembers as RM WHERE 
                   S.idSection=$sid AND ((SM.idUser=$uid AND S.idSection=SM.idSection) OR (RM.idUser=$uid AND R.idResearch=RM.idResearch AND S.idResearch=R.idResearch))";
        $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

        $sid_auth = -1;
        $sname = "";
        if($row = mysql_fetch_array($exe))
        {
            $sid_auth = $row['sid']; 
            $sname = $row['sname']; 
        }

        if($sid_auth == -1)
            die("NO AUTH"); // no user


        // ================================================
        // OK! Displaying section data
        // ================================================

        echo "$sid\n";
        echo "$sname\n";

        $rid = getResearchIdBySectionId($sid);
        $aid = getAreaIdByResearchId($rid);

        $sql = "SELECT filename FROM Files WHERE idSection='$sid'";
        $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
        $nfiles = mysql_num_rows($exe);

        echo "$nfiles\n";

        while($row = mysql_fetch_array($exe))
        {
            $filename = $row['filename']; 
            echo "$myrm_domain_name/myrm/files/a$aid/r$rid/s$sid/$filename\n";
        }
    }
    else if(isset($_GET["rid"]) && isset($_GET["usercode"]))
    {
        $rid = $_GET["rid"];
        $usercode = $_GET["usercode"];
        
        //echo $usercode;

        $email = "";
        $uid = -1;
        $sql = "SELECT idUser, email FROM Users WHERE confirmationCode = '$usercode'";
        $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

        //if(mysql_num_rows($exe)==1)
        if($row = mysql_fetch_array($exe))
        {
            $uid = $row['idUser'];
            $email = $row['email'];
        }

        //echo "USER=$uid EMAIL=$email";

        if($uid == -1)
            die("NO USER"); // no user

        // ================================================
        // Checking authentication into section or research
        // ================================================

        $sql = "SELECT R.`idResearch` as rid, R.`title` as rname FROM Researches as R, ResearchMembers as RM WHERE 
                   R.idResearch=$rid AND RM.idUser=$uid AND R.idResearch=RM.idResearch";
        $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

        $rid_auth = -1;
        $rname = "";
        if($row = mysql_fetch_array($exe))
        {
            $rid_auth = $row['rid']; 
            $rname = $row['rname']; 
        }

        if($rid_auth == -1)
            die("NO AUTH"); // no user


        // ================================================
        // OK! Displaying research data
        // ================================================

        $aid = getAreaIdByResearchId($rid);
        $aname = getAreaNameByAreaId($aid);

        echo "$rid\n";
        echo "$aname-$rname\n";


        $sql = "SELECT S.`idSection` as sid FROM Sections as S WHERE S.idResearch=$rid";
        $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

        $nsections = mysql_num_rows($exe);

        echo "$nsections\n";

        while($row = mysql_fetch_array($exe))
        {
            $sid = $row['sid']; 
            echo "$sid\n";
        }
    }
    else if(isset($_GET["usercode"])) // GET ALL RESEARCHES
    {
        $usercode = $_GET["usercode"];

        $email = "";
        $uid = -1;
        $sql = "SELECT idUser, email FROM Users WHERE confirmationCode = '$usercode'";
        $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

        //if(mysql_num_rows($exe)==1)
        if($row = mysql_fetch_array($exe))
        {
            $uid = $row['idUser'];
            $email = $row['email'];
        }

        //echo "USER=$uid EMAIL=$email";

        if($uid == -1)
            die("NO USER"); // no user

        // ================================================
        // Checking authentication into section or research
        // ================================================

        $sql = "SELECT R.`idResearch` as rid FROM Researches as R, ResearchMembers as RM WHERE RM.idUser=$uid AND R.idResearch=RM.idResearch";
        $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
        $nresearches = mysql_num_rows($exe);

        echo "$nresearches\n";

        while($row = mysql_fetch_array($exe))
        {
            $rid = $row['rid']; 
            echo "$rid\n";
        }
    }
    else
        die("NO DATA");
?>
