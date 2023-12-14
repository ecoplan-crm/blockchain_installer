#!/bin/bash
#
# Copyright IBM Corp All Rights Reserved
#
# SPDX-License-Identifier: Apache-2.0
#

# This is a collection of bash functions used by different scripts

echo Beginn envVar
ORG_COUNT=$1
echo ${ORG_COUNT}
PEER_COUNT=$2
echo $PEER_COUNT

# imports
. scripts/utils.sh

export CORE_PEER_TLS_ENABLED=true
export ORDERER_CA=${PWD}/organizations/ordererOrganizations/example.com/tlsca/tlsca.example.com-cert.pem
export PEER0_ORG1_CA=${PWD}/organizations/peerOrganizations/org1.example.com/tlsca/tlsca.org1.example.com-cert.pem
export ORDERER_ADMIN_TLS_SIGN_CERT=${PWD}/organizations/ordererOrganizations/example.com/orderers/orderer.example.com/tls/server.crt
export ORDERER_ADMIN_TLS_PRIVATE_KEY=${PWD}/organizations/ordererOrganizations/example.com/orderers/orderer.example.com/tls/server.key

# Set environment variables for the peer org
setGlobals() {
  local USING_ORG=""
  local USING_PEER="0"

  if [ -z "$OVERRIDE_ORG" ]; then
    USING_ORG=$1
  else
    USING_ORG="${OVERRIDE_ORG}"
  fi

  #Wenn Variable $2 nicht leer, dann setzen
  if [ -n "$2" ]; then
    USING_PEER=$2
    echo USING_PEER wurde auf $USING_PEER gesetzt
  fi

  USING_PORT=$[$USING_PEER * 1000 + 7051]
  echo Using Port ${USING_PORT}

  infoln "Using organization ${USING_ORG}"
  if [ $USING_ORG -eq 1 ]; then
    export CORE_PEER_LOCALMSPID="Org1MSP"
    #export CORE_PEER_TLS_ROOTCERT_FILE=${PWD}/organizations/peerOrganizations/org1.example.com/peers/peer${USING_PEER}.org1.example.com/tls/ca.crt
    export CORE_PEER_TLS_ROOTCERT_FILE=$PEER0_ORG1_CA
    export CORE_PEER_MSPCONFIGPATH=${PWD}/organizations/peerOrganizations/org1.example.com/users/Admin@org1.example.com/msp
    export CORE_PEER_ADDRESS=peer${USING_PEER}.org1.example.com:${USING_PORT}
    #export CORE_PEER_ADDRESS=localhost:7051
  else
    errorln "ORG Unknown"
  fi

  if [ "$VERBOSE" == "true" ]; then
    env | grep CORE
  fi
}

# Set environment variables for use in the CLI container
setGlobalsCLI() {
  setGlobals $1

  local USING_ORG=""
  if [ -z "$OVERRIDE_ORG" ]; then
    USING_ORG=$1
  else
    USING_ORG="${OVERRIDE_ORG}"
  fi
  if [ $USING_ORG -eq 1 ]; then
    export CORE_PEER_ADDRESS=peer0.org1.example.com:7051
  else
    errorln "ORG Unknown"
  fi
}

# parsePeerConnectionParameters $@
# Helper function that sets the peer connection parameters for a chaincode
# operation
parsePeerConnectionParameters() {

  ORG_COUNT=$1
  PEER_COUNT=$2

  PEER_CONN_PARMS=()
  PEERS=""

  for ((o=1 ; o <= ${ORG_COUNT}; o++)); do
    for ((p=0 ; p < ${PEER_COUNT}; p++)); do

      setGlobals $o $p
      PEER="peer$p.org$o"
      ## Set peer addresses
      if [ -z "$PEERS" ]
      then
        PEERS="$PEER"
      else
        PEERS="$PEERS $PEER"
      fi
      PEER_CONN_PARMS=("${PEER_CONN_PARMS[@]}" --peerAddresses $CORE_PEER_ADDRESS)
      ## Set path to TLS certificate
      #CA=PEER${p}_ORG${o}_CA
      #In envvar wird aktuell nur diese eine variable gesetzt; wenn das so funktioniert, dann muss hier keine individuelle anpassung pro peer erfolgen
      CA=PEER0_ORG1_CA
      TLSINFO=(--tlsRootCertFiles "${!CA}")
      PEER_CONN_PARMS=("${PEER_CONN_PARMS[@]}" "${TLSINFO[@]}")

    done
  done
}

verifyResult() {
  if [ $1 -ne 0 ]; then
    fatalln "$2"
  fi
}
