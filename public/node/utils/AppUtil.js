/*
 * Copyright IBM Corp. All Rights Reserved.
 *
 * SPDX-License-Identifier: Apache-2.0
 */

'use strict';

const fs = require('fs');
const path = require('path');

//const orgPath = path.resolve(__dirname,  '..', '..','..', '..','..', '..','Ordner', 'fabric-samples','my-network_Skript', 'organizations', 'peerOrganizations', 'orgASDF.example.com', 'connection-orgASDF.json');

exports.buildCCPOrg1 = (orgPath) => {
	// load the common connection configuration file
	//console.log(__dirname);
	console.log("OrgPath: " + orgPath);
	const ccpPath = orgPath;//path.resolve(__dirname,  '..', '..', '..','..', '..','Ordner', 'fabric-samples','test-network-Kopie', 'organizations', 'peerOrganizations', 'org1.example.com', 'connection-org1.json');
	const fileExists = fs.existsSync(ccpPath);
	if (!fileExists) {
		throw new Error(`no such file or directory: ${ccpPath}`);
	}
	const contents = fs.readFileSync(ccpPath, 'utf8');

	// build a JSON object from the file contents
	const ccp = JSON.parse(contents);

	console.log(`Loaded the network configuration located at ${ccpPath}`);
	console.log(ccp);
	return ccp;
};

exports.buildCCPOrg2 = () => {
	// load the common connection configuration file
	const ccpPath = path.resolve(__dirname, '..', '..', 'test-network',
		'organizations', 'peerOrganizations', 'org2.example.com', 'connection-org2.json');
	const fileExists = fs.existsSync(ccpPath);
	if (!fileExists) {
		throw new Error(`no such file or directory: ${ccpPath}`);
	}
	const contents = fs.readFileSync(ccpPath, 'utf8');

	// build a JSON object from the file contents
	const ccp = JSON.parse(contents);

	console.log(`Loaded the network configuration located at ${ccpPath}`);
	return ccp;
};

exports.buildWallet = async (Wallets, walletPath) => {
	// Create a new  wallet : Note that wallet is for managing identities.
	let wallet;
	if (walletPath) {
		wallet = await Wallets.newFileSystemWallet(walletPath);
		console.log(`Built a file system wallet at ${walletPath}`);
	} else {
		wallet = await Wallets.newInMemoryWallet();
		console.log('Built an in memory wallet');
	}

	return wallet;
};

exports.prettyJSONString = (inputString) => {
	if (inputString) {
		 return JSON.stringify(JSON.parse(inputString), null, 2);
	}
	else {
		 return inputString;
	}
}
