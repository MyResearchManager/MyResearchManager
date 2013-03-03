#!/bin/bash

usercode=$1
myrmserver=$2

version="0.3"

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

alldata=all-data.txt

# get researches from server

wget -O $alldata "$myrmserver/sync.php?usercode=$usercode"

serverversion=$(head -n 1 $alldata | tail -1)
if [ "$serverversion" == "$version" ]
then
   echo "CORRECT VERSION $version";
else
   echo "ERROR! DIFFERENT VERSIONS, PLEASE DOWNLOAD A NEW ONE! (CLIENT='$version'; SERVER='$serverversion')";
   exit 1;
fi

nresearches=$(head -n 2 $alldata | tail -1)

echo "Found $nresearches researches."

for ((i=1; i<=$nresearches; i++)); do
  rid=$(head -n $((2+$i)) $alldata | tail -1)
  ./myrm-sync-research.sh $rid $usercode $myrmserver
done

rm $alldata

echo "Finished All Sync from MyResearchManager"
