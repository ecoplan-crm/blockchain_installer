#!/bin/bash

ROOTDIR=$(cd "$(dirname "$0")" && pwd)
export PATH=${ROOTDIR}/../bin:${PWD}/../bin:$PATH
export FABRIC_CFG_PATH=${PWD}/configtx
export VERBOSE=false

# push to the required directory & set a trap to go back if needed
pushd ${ROOTDIR} > /dev/null
trap "popd > /dev/null" EXIT

./network.sh editHosts -ip ##{ip}## -myDomain peer##{peerNumber}##.org1.example.com -pc 2

. organizations/fabric-ca/registerPeerXorg1.sh

docker-compose -f compose/compose-peerXorg1.yaml  up -d

export PATH=$PATH:$PWD/../bin/
export FABRIC_CFG_PATH=$PWD/../config/
. scripts/envVar.sh
setGlobals 1

export CORE_PEER_ADDRESS=localhost:##{peerPort}##

peer channel list
peer channel join -b ./channel-artifacts/##{channelName}##.block
