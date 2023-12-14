"use strict";

const dotenv = require('dotenv');
const path = require('path');
const envPath = path.resolve(__dirname, '../../.env'); // Passe den Pfad entsprechend an
dotenv.config({ path: envPath });

const port = process.env.NODEJS_PORT || 3000;
const laravelPort = process.env.LARAVEL_PORT || 8000 // Der Port der Laravel-Anwendung
const laravelURL = process.env.APP_URL || localhost;

const { exec } = require('child_process');
const { Gateway, Wallets } = require("fabric-network");
const FabricCAServices = require("fabric-ca-client");

const {
    buildCAClient,
    registerAndEnrollUser,
    enrollAdmin,
} = require("./utils/CAUtil.js");
const { buildCCPOrg1, buildWallet } = require("./utils/AppUtil.js");

const { spawn } = require("child_process");

const cors = require("cors");

var chaincodeName = "basic";
const walletPath = path.join(__dirname, "wallet");
let org1UserId = "appUser_0";

var express = require("express");
const { syncBuiltinESMExports } = require("module");
const { log } = require("console");
var app = express();

app.use(express.json());

const allowedOrigins = [laravelURL, laravelURL + ":" + laravelPort];

app.use(
    cors({
        origin: allowedOrigins,
        optionsSuccessStatus: 200, // Einige ältere Browser erfordern eine spezifische Statusmeldung
    })
);

app.get("/ledger", async function (req, res) {

    console.log("Command: " + req.query.command);

    var parameter = req.query.parameter || "unkown";
    console.log("Parameter: " + parameter);

    let result = await ledger(
        req.query.command,
        parameter,
        req.query.channel,
        req.query.isNewNetwork,
        req.query.peerNumber,
        req.query.ccn,
    );

    res.send(result);
});

app.post('/livewire-execute-script', (req, res) => {

    const script = req.body.script; // Das Skript aus dem Request-Body
  
    // Skript in der Shell ausführen
    exec(script, (error, stdout, stderr) => {
      if (error) {
        console.error(`Fehler beim Ausführen des Skripts: ${error.message}`);
        return res.status(500).send('Ein Fehler ist aufgetreten.');
      }
      if (stderr) {
        console.error(`Fehlermeldung der Shell: ${stderr}`);
      }
      console.log(`Skriptausgabe: ${stdout}`);
      res.send('Skript erfolgreich ausgeführt.');
    });
  });

app.post("/execute-script", (req, res) => {

    console.log("execute-script");
    const { script } = req.body;
    console.log("Übergebenes Skript: " + script);
    const scriptExecution = spawn(script, { shell: true });

    scriptExecution.stdout.on("data", (data) => {
        const output = data.toString();
        res.write(output); // Sende Ausgabedaten an die Laravel-Anwendung
    });

    scriptExecution.stderr.on("data", (data) => {
        const error = data.toString();
        res.write(error); // Sende Fehlerdaten an die Laravel-Anwendung
    });

    scriptExecution.on("close", (code) => {
        res.end(code.toString()); // Beende die Antwort an die Laravel-Anwendung
    });
});

app.listen(port, () => {
    console.log("Node.js Server läuft auf " + laravelURL + ":" + port);
    console.log("Laravel-Applikation wird auf Port " + laravelPort + " erwartet. Bitte prüfen!")
});

async function ledger(command, parameter, channel, isNewNetwork, peerNumber, ccn) {
    
    chaincodeName = ccn;
    const mspOrg1 = "Org1MSP";
    const myorg_ca = "ca.org1.example.com";
    let orgPath=(process.env.NETWORK_DIRECTORY || '') + "/organizations/peerOrganizations/org1.example.com/connection-org1.json";

    console.log("isNewNetwork: " + isNewNetwork);
    console.log("peerNumber: " + peerNumber);

    if (isNewNetwork == 0) {
        org1UserId = "appUser_" + peerNumber;
        orgPath=(process.env.NETWORK_DIRECTORY || '') + "/organizations/peerOrganizations/org1.example.com/connection-org1-peerX.json";
    }

    console.log("Path: " + orgPath);

    try {
        const ccp = buildCCPOrg1(orgPath);
        const caClient = buildCAClient(FabricCAServices, ccp, myorg_ca);
        const wallet = await buildWallet(Wallets, walletPath);
        await enrollAdmin(caClient, wallet, mspOrg1);
        await registerAndEnrollUser(caClient, wallet, mspOrg1, org1UserId);
        const gateway = new Gateway();

        try {

            await gateway.connect(ccp, {
                wallet,
                identity: org1UserId,
                discovery: { enabled: true, asLocalhost: isNewNetwork },
            });

            const network = await gateway.getNetwork(channel);

            const contract = network.getContract(chaincodeName);

            console.log("Command: " + command);
            console.log("Parameter: " + parameter);

            let result;
            let array = parameter;
            console.log("array.length: " + array.length);

            for (var i = 0; i < 5; i++) {
                console.log("index " + i + ": " + array[i]);
            }

            switch (command) {
                case "InitLedger":
                    result = await contract.submitTransaction(command);
                    break;
                case "CreateAsset":
                case "UpdateAsset":
                    result = await contract.submitTransaction(
                        command,
                        array[0],
                        array[1],
                        array[2],
                        array[3],
                        array[4]
                    );
                    break;
                case "DeleteAsset":
                    var check = await contract.evaluateTransaction(
                        "AssetExists",
                        array[0]
                    );
                    console.log("Check: " + check);
                    if (check == "true") {
                        result = await contract.submitTransaction(
                            command,
                            array[0]
                        );
                        result = "Asset removed successful!";
                    } else {
                        result = "Asset not found!";
                    }
                    break;
                case "ReadAsset":
                    result = await contract.evaluateTransaction(
                        command,
                        array[0]
                    );
                    break;
                case "GetAllAssets":
                    result = await contract.evaluateTransaction(command);
                    break;
                case "AssetExists":
                    result = await contract.evaluateTransaction(
                        command,
                        array[0]
                    );
                    break;

                default:
                    result = "Command not found!";
                    break;
            }

            console.log("Result: " + result.toString());
            return result.toString();
        } finally {
            gateway.disconnect();
        }
    } catch (error) {
        console.error(`******** FAILED to run the application: ${error}`);
        return `${error}`;
    }
}
