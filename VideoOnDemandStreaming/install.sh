#!/bin/sh
echo "Installing VideoOnDemandStreaming..."
if [ -d /Library/WowzaMediaServer ]
then
	cd /Library/WowzaMediaServer/examples/VideoOnDemandStreaming
else
	cd /usr/local/WowzaMediaServer/examples/VideoOnDemandStreaming
fi

cp -R conf/* ../../conf/
if [ ! -d ../../applications/vod ]
then
	mkdir ../../applications/vod
fi
