#!/bin/bash

source scripts/utils.sh

ORG_COUNT=${1}
PEER_COUNT=${2}
CHANNEL_NAME=${3:-"mychannel"}
CC_NAME=${4}
CC_SRC_PATH=${5}
CC_SRC_LANGUAGE=${6}
CC_VERSION=${7:-"1.0"}
CC_SEQUENCE=${8:-"1"}
CC_INIT_FCN=${9:-"NA"}
CC_END_POLICY=${10:-"NA"}
CC_COLL_CONFIG=${11:-"NA"}
DELAY=${12:-"3"}
MAX_RETRY=${13:-"5"}
VERBOSE=${14:-"false"}


println "executing with the following"
println "- ORG_COUNT: ${C_GREEN}${ORG_COUNT}${C_RESET}"
println "- PEER_COUNT: ${C_GREEN}${PEER_COUNT}${C_RESET}"
println "- CHANNEL_NAME: ${C_GREEN}${CHANNEL_NAME}${C_RESET}"
println "- CC_NAME: ${C_GREEN}${CC_NAME}${C_RESET}"
println "- CC_SRC_PATH: ${C_GREEN}${CC_SRC_PATH}${C_RESET}"
println "- CC_SRC_LANGUAGE: ${C_GREEN}${CC_SRC_LANGUAGE}${C_RESET}"
println "- CC_VERSION: ${C_GREEN}${CC_VERSION}${C_RESET}"
println "- CC_SEQUENCE: ${C_GREEN}${CC_SEQUENCE}${C_RESET}"
println "- CC_END_POLICY: ${C_GREEN}${CC_END_POLICY}${C_RESET}"
println "- CC_COLL_CONFIG: ${C_GREEN}${CC_COLL_CONFIG}${C_RESET}"
println "- CC_INIT_FCN: ${C_GREEN}${CC_INIT_FCN}${C_RESET}"
println "- DELAY: ${C_GREEN}${DELAY}${C_RESET}"
println "- MAX_RETRY: ${C_GREEN}${MAX_RETRY}${C_RESET}"
println "- VERBOSE: ${C_GREEN}${VERBOSE}${C_RESET}"

FABRIC_CFG_PATH=$PWD/../config/

#User has not provided a name
if [ -z "$CC_NAME" ] || [ "$CC_NAME" = "NA" ]; then
  fatalln "No chaincode name was provided. Valid call example: ./network.sh deployCC -ccn basic -ccp ../asset-transfer-basic/chaincode-go -ccl go"

# User has not provided a path
elif [ -z "$CC_SRC_PATH" ] || [ "$CC_SRC_PATH" = "NA" ]; then
  fatalln "No chaincode path was provided. Valid call example: ./network.sh deployCC -ccn basic -ccp ../asset-transfer-basic/chaincode-go -ccl go"

# User has not provided a language
elif [ -z "$CC_SRC_LANGUAGE" ] || [ "$CC_SRC_LANGUAGE" = "NA" ]; then
  fatalln "No chaincode language was provided. Valid call example: ./network.sh deployCC -ccn basic -ccp ../asset-transfer-basic/chaincode-go -ccl go"

## Make sure that the path to the chaincode exists
elif [ ! -d "$CC_SRC_PATH" ] && [ ! -f "$CC_SRC_PATH" ]; then
  fatalln "Path to chaincode does not exist. Please provide different path."
fi

CC_SRC_LANGUAGE=$(echo "$CC_SRC_LANGUAGE" | tr [:upper:] [:lower:])

# do some language specific preparation to the chaincode before packaging
if [ "$CC_SRC_LANGUAGE" = "go" ]; then
  CC_RUNTIME_LANGUAGE=golang

  infoln "Vendoring Go dependencies at $CC_SRC_PATH"
  pushd $CC_SRC_PATH
  GO111MODULE=on go mod vendor
  popd
  successln "Finished vendoring Go dependencies"

elif [ "$CC_SRC_LANGUAGE" = "java" ]; then
  CC_RUNTIME_LANGUAGE=java

  rm -rf $CC_SRC_PATH/build/install/
  infoln "Compiling Java code..."
  pushd $CC_SRC_PATH
  ./gradlew installDist
  popd
  successln "Finished compiling Java code"
  CC_SRC_PATH=$CC_SRC_PATH/build/install/$CC_NAME

elif [ "$CC_SRC_LANGUAGE" = "javascript" ]; then
  CC_RUNTIME_LANGUAGE=node

elif [ "$CC_SRC_LANGUAGE" = "typescript" ]; then
  CC_RUNTIME_LANGUAGE=node

  infoln "Compiling TypeScript code into JavaScript..."
  pushd $CC_SRC_PATH
  npm install
  npm run build
  popd
  successln "Finished compiling TypeScript code into JavaScript"

else
  fatalln "The chaincode language ${CC_SRC_LANGUAGE} is not supported by this script. Supported chaincode languages are: go, java, javascript, and typescript"
  exit 1
fi

INIT_REQUIRED="--init-required"
# check if the init fcn should be called
if [ "$CC_INIT_FCN" = "NA" ]; then
  INIT_REQUIRED=""
fi

if [ "$CC_END_POLICY" = "NA" ]; then
  CC_END_POLICY=""
else
  CC_END_POLICY="--signature-policy $CC_END_POLICY"
fi

if [ "$CC_COLL_CONFIG" = "NA" ]; then
  CC_COLL_CONFIG=""
else
  CC_COLL_CONFIG="--collections-config $CC_COLL_CONFIG"
fi

# import utils
. scripts/envVar.sh $ORG_COUNT $PEER_COUNT
. scripts/ccutils.sh $ORG_COUNT $PEER_COUNT

packageChaincode() {
  set -x
  peer lifecycle chaincode package ${CC_NAME}.tar.gz --path ${CC_SRC_PATH} --lang ${CC_RUNTIME_LANGUAGE} --label ${CC_NAME}_${CC_VERSION} >&log.txt
  res=$?
  PACKAGE_ID=$(peer lifecycle chaincode calculatepackageid ${CC_NAME}.tar.gz)
  { set +x; } 2>/dev/null
  cat log.txt
  verifyResult $res "Chaincode packaging has failed"
  successln "Chaincode is packaged"
}

function checkPrereqs() {
  jq --version > /dev/null 2>&1

  if [[ $? -ne 0 ]]; then
    errorln "jq command not found..."
    errorln
    errorln "Follow the instructions in the Fabric docs to install the prereqs"
    errorln "https://hyperledger-fabric.readthedocs.io/en/latest/prereqs.html"
    exit 1
  fi
}

#check for prerequisites
checkPrereqs

## package the chaincode
packageChaincode

## Install chaincode on peers of org1
infoln "Installing chaincode on peers of org1..."

for ((i=0 ; i < ${PEER_COUNT}; i++)); do
  installChaincode 1 $i
done  

## query whether the chaincode is installed
for ((i=0 ; i < ${PEER_COUNT}; i++)); do
  queryInstalled 1 $i
done

echo approveStart

## approve the definition for org1
#for ((i=0 ; i < ${PEER_COUNT}; i++)); do
  approveForMyOrg 1 0
#done

echo approveAbgeschlossen

for ((i=0 ; i < ${PEER_COUNT}; i++)); do
  checkCommitReadiness 1 $i "\"Org1MSP\": true"
  #Beim ausbauf auf x Organisationen hier aufpassen, da diese Stelle etwas komplizierter ist
done

## check whether the chaincode definition is ready to be committed
## expect org1 to have approved and org2 not to
#checkCommitReadiness 1 "\"Org1MSP\": true" "\"Org2MSP\": false"
#checkCommitReadiness 2 "\"Org1MSP\": true" "\"Org2MSP\": false"

## now approve also for org2
#approveForMyOrg 2

## check whether the chaincode definition is ready to be committed
## expect them both to have approved
#checkCommitReadiness 1 "\"Org1MSP\": true" "\"Org2MSP\": true"
#checkCommitReadiness 2 "\"Org1MSP\": true" "\"Org2MSP\": true"

## now that we know for sure both orgs have approved, commit the definition
commitChaincodeDefinition $ORG_COUNT $PEER_COUNT

## query on both orgs to see that the definition committed successfully
for ((i=0 ; i < ${PEER_COUNT}; i++)); do
  queryCommitted 1 $i
done
#queryCommitted 2

## Invoke the chaincode - this does require that the chaincode have the 'initLedger'
## method defined
if [ "$CC_INIT_FCN" = "NA" ]; then
  infoln "Chaincode initialization is not required"
else
  fatalln "Diese Funktion wurde noch nicht für eine variable Anzahl an Peers/Organisationen angepasst"
  #chaincodeInvokeInit 1 2
fi

exit 0
