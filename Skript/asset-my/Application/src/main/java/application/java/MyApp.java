
/*
 * Copyright IBM Corp. All Rights Reserved.
 *
 * SPDX-License-Identifier: Apache-2.0
 */

// Running TestApp: 
// gradle runApp 

package application.java;

import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.Scanner;
import java.util.concurrent.TimeoutException;

import org.hyperledger.fabric.gateway.Contract;
import org.hyperledger.fabric.gateway.ContractException;
import org.hyperledger.fabric.gateway.Gateway;
import org.hyperledger.fabric.gateway.Network;
import org.hyperledger.fabric.gateway.Wallet;
import org.hyperledger.fabric.gateway.Wallets;

public class MyApp {
	final private static int INIT = 0;
	final private static int GETALL = 1;
	final private static int READ = 2;
	final private static int CREATE = 3;
	final private static int UPDATE = 4;
	final private static int DELET = 5;
	final private static int TRANSFER = 6;
	final private static int EXIST = 7;
	final private static int QUIT = 8;
	final private static int TEST = 9;
	final private static String ORGNO = "1";
	final private static String CHANNEL ="mychannel";

	static {
		System.setProperty("org.hyperledger.fabric.sdk.service_discovery.as_localhost", "true");
	}

	// helper function for getting connected to the gateway
	public static Gateway connect() throws Exception {
		// Load a file system based wallet for managing identities.
		Path walletPath = Paths.get("wallet");
		Wallet wallet = Wallets.newFileSystemWallet(walletPath);
		// load a CCP
		// Path networkConfigPath = Paths.get("..", "..", "test-network-Kopie",
		// "organizations", "peerOrganizations", "org1.example.com",
		// "connection-org1.yaml");
		Path networkConfigPath = Paths.get("..", "..", "my-network_Skript", "organizations", "peerOrganizations",
				"org"+ORGNO+".example.com", "connection-org"+ORGNO+".yaml");

		// Path networkConfigPath = Paths.get("..", "..", "my-network", "organizations",
		// "peerOrganizations",
		// "org2.example.com", "connection-org2.yaml");

		Gateway.Builder builder = Gateway.createBuilder();
		builder.identity(wallet, "appUser_2").networkConfig(networkConfigPath).discovery(true);
		return builder.connect();
	}

	public static void main(String[] args) throws Exception {
		// enrolls the admin and registers the user
		try {
			EnrollAdmin.main(null);
			RegisterUser.main(null);
		} catch (Exception e) {
			System.err.println(e);
		}

		// connect to the network and invoke the smart contract

		try (Gateway gateway = connect()) {

			// get the network and contract
			Network network = gateway.getNetwork(CHANNEL);
			Contract contract = network.getContract("basic");
			;
			boolean input = true;
			int number=0;

			while (input) {
				printMenu();
				Scanner input_number = new Scanner(System.in);
				if(input_number.hasNextInt()){
					number = input_number.nextInt();
				} else{
					System.out.println("First Input need to be an number!");
					continue;
				}
				String[] data = new String[5];
				int max = 0;
				if (number == CREATE || number == UPDATE) {
					max = 5;
				} else if (number == READ || number == EXIST || number == DELET) {
					max = 1;
				} else if (number == TRANSFER || number == TEST) {
					max = 2;
				} else if (number == QUIT) {
					System.out.println("Programm wird beendet!");
					input_number.close();
					input = false;
					break;
				}

				int i = 0;
				while (i < max && input_number.hasNext()) {
					data[i] = input_number.next();
					// System.out.println(data[i]);
					i++;
				}
				System.out.println(data[0] + data[1] + data[2] + data[3] + data[4]);

				menu(number, data, network, contract);
			}

		} catch (Exception e) {
			System.err.println(e);
		}

	}

	public static void printMenu() {
		System.out.println("\n\n");
		System.out.println("<0> Init Ledger");
		System.out.println("<1> Get All Assets");
		System.out.println("<2> Read Asset [asset]");
		System.out.println("<3> Create Asset [assedID Color size owner Value]");
		System.out.println("<4> Update Asset [asset] [data]");
		System.out.println("<5> Delet Asset [asset]");
		System.out.println("<6> Transfer  Asset [asset] [new_owner]");
		System.out.println("<7> Asset Exist [asset]");
		System.out.println("<8> Beenden");
		System.out.println("<9> Test");
	}

	public static void menu(int num, String[] data, Network network, Contract contract) throws ContractException {
		byte[] result;
		switch (num) {
			case INIT:

				System.out.println("Init Ledger:");
				try {
					contract.submitTransaction("InitLedger");
					System.out.println("Init Ledger SUCCESS");

				} catch (TimeoutException | InterruptedException | ContractException e1) {
					// e1.printStackTrace();
					System.out.println("Init Ledger FAILED");

				}
				break;

			case GETALL:
				result = contract.evaluateTransaction("GetAllAssets");
				System.out.println("Evaluate Transaction: GetAllAssets, result: " + new String(result));
				break;

			case READ:
				result = contract.evaluateTransaction("AssetExists", data[0]);
				if (new String(result).equals("true")) {
					result = contract.evaluateTransaction("ReadAsset", data[0]);
					System.out.println("Evaluate Transaction: ReadAsset, result: " + new String(result));
				} else {
					System.out.println("Asset Existiert nicht");
				}
				break;

			case CREATE:
				try {
					result = contract.submitTransaction("CreateAsset", data[0], data[1], data[2], data[3], data[4]);
					System.out.println("Evaluate Transaction: CreateAsset, result: " + new String(result));
				} catch (TimeoutException | InterruptedException | ContractException e) {
					// Auto-generated catch block
					// e.printStackTrace();
					System.out.println("Fehler beim erstellen des Assets");
				}
				break;

			case UPDATE:
				try {
					contract.submitTransaction("UpdateAsset", data[0], data[1], data[2], data[3], data[4]);
				} catch (TimeoutException | InterruptedException e) {
					// Auto-generated catch block
					System.out.println("Fehler beim updaten des Assets");
					// e.printStackTrace();
				}
				break;

			case DELET:
				result = contract.evaluateTransaction("AssetExists", data[0]);
				if (new String(result).equals("true")) {
					try {
						result = contract.submitTransaction("DeleteAsset", data[0]);
					} catch (TimeoutException | InterruptedException e) {
						// Auto-generated catch block
						// e.printStackTrace();
						System.out.println("Fehler beim löschen!");
					}
					System.out.println("Asset erfolgreich gelöscht");
				} else {
					System.out.println("Asset Existiert nicht");
				}
				break;

			case TRANSFER:
				try {
					contract.submitTransaction("TransferAsset", data[0], data[1]);
				} catch (TimeoutException | InterruptedException e) {
					// Auto-generated catch block
					// e.printStackTrace();
					System.out.println("Fehler beim Transfer des Assets");
				}
				break;

			case EXIST:
				result = contract.evaluateTransaction("AssetExists", data[0]);
				System.out.println("result: " + new String(result));
				break;

			case TEST:
				try {
					contract.submitTransaction("UpdateSizeAsset", data[0], data[1]);
				} catch (TimeoutException | InterruptedException e) {
					// Auto-generated catch block
					// e.printStackTrace();
					System.out.println("Fehler beim UpdateSize  des Assets");
				}
				break;

			default:
				break;
		}
	}
}
