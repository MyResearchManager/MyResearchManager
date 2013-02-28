#!/bin/bash

usercode=$1
myrmserver=$2

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

nresearches=$(head -n 1 $alldata | tail -1)

echo "Found $nresearches researches."

for ((i=1; i<=$nresearches; i++)); do
  rid=$(head -n $((1+$i)) $alldata | tail -1)
  ./myrm-sync-research.sh $rid $usercode $myrmserver
done

echo "Finished All Sync from MyResearchManager"
