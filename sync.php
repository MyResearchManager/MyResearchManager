<?php
    include "connection.php";

    require_once("util.php");

    $version = "0.3";

    $rid = "";
    $shash = "";
    $usercode = "";
 
    if(isset($_GET["shash"]) && isset($_GET["usercode"]))
    {
        $shash = $_GET["shash"];
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
                   S.shash='$shash' AND ((SM.idUser=$uid AND S.idSection=SM.idSection) OR (RM.idUser=$uid AND R.idResearch=RM.idResearch AND S.idResearch=R.idResearch))";
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

        echo "$version\n";
        echo "$sname\n";

        $rid = getResearchIdBySectionId($sid_auth);
        $aid = getAreaIdByResearchId($rid);

        $sql = "SELECT filename, checksum FROM Files WHERE idSection='$sid_auth'";
        $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
        $nfiles = mysql_num_rows($exe);

        echo "$nfiles\n";

        while($row = mysql_fetch_array($exe))
        {
            $filename = $row['filename']; 
            $md5 = $row['checksum']; 
            echo "$myrm_site/files/a$aid/r$rid/s$sid_auth/$filename\n";
            echo "$md5\n";
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

        echo "$version\n";
        echo "$aname-$rname\n";


        $sql = "SELECT S.`shash` as shash FROM Sections as S WHERE S.idResearch=$rid";
        $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

        $nsections = mysql_num_rows($exe);

        echo "$nsections\n";

        while($row = mysql_fetch_array($exe))
        {
            $shash = $row['shash']; 
            echo "$shash\n";
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

        echo "$version\n";
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
