#!/bin/bash

sid=$1
usercode=$2
myrmserver=$3

version="0.2"

if [ "$sid" == "" ]
then
   echo "Missing section id! Aborting..."
   exit 1
fi

if [ "$usercode" == "" ]
then
   echo "Missing usercode! Aborting..."
   exit 1
fi

if [ "$myrmserver" == "" ]
then
   echo "Missing server url! Aborting..."
   exit 1
fi

# =========================================

sectiondata=section-data.txt
logfile=myrm-sync.log

# get section data from server
echo ""
echo "============================"
echo "Get section data from server"
date +"%c"
echo "============================"

echo "" >> $logfile
echo "============================" >> $logfile
echo "Get section data from server" >> $logfile
date +"%c" >> $logfile
echo "============================" >> $logfile

wget -O $sectiondata "$myrmserver/sync.php?usercode=$usercode&sid=$sid" 

serverversion=$(head -n 1 $sectiondata | tail -1)
if [ "$serverversion" == "$version" ]
then
   echo "CORRECT VERSION $version";
else
   echo "ERROR! DIFFERENT VERSIONS, PLEASE DOWNLOAD A NEW ONE! (CLIENT=$version; SERVER=$serverversion)";
   exit 1;
fi

sname=$(head -n 2 $sectiondata | tail -1)

if [ "$sname" == "" ]
then
   echo "Empty section name! Aborting..."
   exit 1
fi

mkdir "$sname"
nfiles=$(head -n 3 $sectiondata | tail -1)

echo "Section id: $sid with name: \"$sname\" and $nfiles files."

for ((i=0; i<$nfiles; i++)); do
  serverfile=$(head -n $((4+2*$i)) $sectiondata | tail -1)
  md5serverfile=$(head -n $((4+2*$i+1)) $sectiondata | tail -1)
  localfile=$(basename "$serverfile")

  echo "CHECKING IF FILE $sname/$localfile EXISTS"
  echo "CHECKING IF FILE $sname/$localfile EXISTS" >> $logfile
  if [ -e "$sname/$localfile" ]
  then
     md5=`md5sum "$sname/$localfile" | awk '{ print $1 }'`
     if [ "$md5serverfile" == "$md5" ]
     then
        echo "SAME MD5! NO NEED TO DOWNLOAD FILE $sname/$localfile"
        echo "SAME MD5! NO NEED TO DOWNLOAD FILE $sname/$localfile" >> $logfile
     else
        echo "DIFFERENT MD5! WILL DOWNLOAD FILE $serverfile"
        echo "DIFFERENT MD5! WILL DOWNLOAD FILE $serverfile" >> $logfile
        wget -O "$localfile" "$serverfile"
        echo "MOVING FILE: $localfile => $sname/$localfile" >> $logfile
        mv "$localfile" "$sname/$localfile"
     fi
  else
     echo "GET FILE FROM MY RESEARCH MANAGER: $serverfile => $localfile"
     echo "GET FILE FROM MY RESEARCH MANAGER: $serverfile => $localfile" >> $logfile
     wget -O "$localfile" "$serverfile"
     echo "MOVING FILE: $localfile => $sname/$localfile" >> $logfile
     mv "$localfile" "$sname/$localfile"
  fi
done

echo "================"
echo "Finished Section"
echo "================"

echo "================" >> $logfile
echo "Finished Section" >> $logfile
echo "================" >> $logfile
