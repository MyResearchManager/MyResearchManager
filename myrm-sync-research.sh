#!/bin/bash

rid=$1
usercode=$2
myrmserver=$3

if [ "$rid" == "" ]
then
   echo "Missing research id! Aborting..."
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

researchdata=research-data.txt

# get research data from server

wget -O $researchdata "$myrmserver/sync.php?usercode=$usercode&rid=$rid"

researchid=$(head -n 1 $researchdata | tail -1)
rname=$(head -n 2 $researchdata | tail -1)
mkdir "$rname"
nsections=$(head -n 3 $researchdata | tail -1)

echo "Research id: $rid with name: \"$rname\" and $nsections sections."

for ((i=1; i<=$nsections; i++)); do
  sid=$(head -n $((3+$i)) $researchdata | tail -1)
  echo "Entering directory \"$rname\""
  cd "$rname"
  ../myrm-sync-section.sh $sid $usercode $myrmserver
  cd ..
done

echo "Finished Research"