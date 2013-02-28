#!/bin/bash

sid=$1
usercode=$2
myrmserver=$3

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

# get section data from server

echo "Get section data from server:"
wget -O $sectiondata "$myrmserver/sync.php?usercode=$usercode&sid=$sid" 

sectionid=$(head -n 1 $sectiondata | tail -1)
sname=$(head -n 2 $sectiondata | tail -1)

if [ "$sname" == "" ]
then
   echo "Empty section name! Aborting..."
   exit 1
fi

mkdir "$sname"
nfiles=$(head -n 3 $sectiondata | tail -1)

echo "Section id: $sid with name: \"$sname\" and $nfiles files."

for ((i=1; i<=$nfiles; i++)); do
  serverfile=$(head -n $((3+$i)) $sectiondata | tail -1)
  localfile=$(basename "$serverfile")
  echo "GET FILE FROM MY RESEARCH MANAGER: $serverfile => $localfile"
  wget -O "$localfile" "$serverfile"
  mv "$localfile" "$sname/$localfile"
done

echo "Finished Section"
