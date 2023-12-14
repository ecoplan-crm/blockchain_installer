<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Livewire\EcoplanComponent;
use DirectoryIterator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ZipArchive;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;



class ZipController extends Controller
{

    private $parameterPath = 'parameters.json';

    public function uploadAndExtract(Request $request)
    {
        $zipFile = $request->file('zip_file');

        // Pfad zum Speichern der hochgeladenen Datei
        $fullpath = Config::get('ecoplan.network_directory');
        $path = dirname($fullpath);
        $filename = Str::random(15) . '.zip';
        $zipFile->move($path, $filename);

        // Zip-Datei extrahieren
        $zip = new ZipArchive();
        if ($zip->open($path . '/' . $filename) === TRUE) {
            $zip->extractTo($path);
            $zip->close();

            if (!File::exists($fullpath . "/" . $this->parameterPath)) {
                session(['message' => 'Das Verzeichnis enthält nicht die erwarteten Daten.']);
                return redirect()->back();
            }

            $jsonString = File::get($fullpath . "/" . $this->parameterPath);
            $jsonData = json_decode($jsonString, true);
            session(['channelName' => $jsonData["channelName"]]);
            session(['peerNumber' => $jsonData["peerNumber"]]);
            session(['chaincodeName' => $jsonData["chaincodeName"]]);

            session(['message' => 'Verzeichnis wurde hochgeladen.']);
        } else {
            session(['message' => 'Beim Extrahieren ist ein Fehler aufgetreten.']);
        }

        unlink($path . '/' . $filename);

        EcoplanComponent::chmodNetworkDirectory();

        return redirect()->back();
    }
    public function zipAndDownloadDirectory(Request $request, $serverIP, $peerNumber)
    {
        $directoryToZip = Config::get('ecoplan.network_directory');
        $zipFileName = 'peer' . $peerNumber . '.zip';

        $peerPort = 7051 + ($peerNumber * 1000);

        //Extrahiere Zertifikat aus Connection-Profil
        $jsonData = file_get_contents($directoryToZip . '/organizations/peerOrganizations/org1.example.com/connection-org1.json');
        $arrayData = json_decode($jsonData, true);

        //Passe Skripte zum Start des zusätzliche Peers an
        $this->replaceInFile(base_path("Skript/additionalPeers/startPeerAndJoinChannel.sh"), $directoryToZip . "/startPeerAndJoinChannel.sh", [
            ["##{channelName}##", session("channelName")], 
            ["##{peerPort}##", $peerPort],
            ["##{peerNumber}##", $peerNumber],
            ["##{ip}##", $serverIP]
        ]);
        $this->replaceInFile(base_path("Skript/additionalPeers/registerPeerXorg1.sh"), $directoryToZip . "/organizations/fabric-ca/registerPeerXorg1.sh", [
            ["##{peerNumber}##", $peerNumber]
        ]);
        $this->replaceInFile(base_path("Skript/additionalPeers/compose-peerXorg1.yaml"), $directoryToZip . "/compose/compose-peerXorg1.yaml", [
            ["##{peerNumber}##", $peerNumber],
            ["##{peerPort}##", $peerPort],
            ["##{peerPort2}##", $peerPort + 1],
            ["##{peerPort446}##", $peerPort - 51 + 446],
            ["##{ip}##", $serverIP],
        ]);
        $this->replaceInFile(base_path("Skript/additionalPeers/installChaincode.sh"), $directoryToZip . "/installChaincode.sh", [
            ["##{chaincodeName}##", session("chaincodeName")],
            ["##{peerPort}##", $peerPort]
        ]);
        $this->replaceInFile(base_path("Skript/additionalPeers/connection-org1-peerX.json"), $directoryToZip . "/organizations/peerOrganizations/org1.example.com/connection-org1-peerX.json", [
            ["##{peerNumber}##", $peerNumber],
            ["##{peerPort}##", $peerPort],
            ["##{cert}##", str_replace("\n", '\n', $arrayData["peers"]["peer0.org1.example.com"]["tlsCACerts"]["pem"])]
        ]);



        $zip = new ZipArchive();
        if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === true) {

            $this->addDirectoryToZip($zip, $directoryToZip, basename($directoryToZip), []);

            //Erstellt Config-File
            $data = [
                'channelName' => session("channelName"),
                'peerNumber' => $peerNumber,
                'chaincodeName' => session("chaincodeName"),
            ];
            $jsonString = json_encode($data, JSON_PRETTY_PRINT);
            File::put(base_path($this->parameterPath), $jsonString);


            // Füge zusätzliche Dateien hinzu (überschreibe bestehende Dateien)
            $additionalFiles = [
                [$this->parameterPath, $this->parameterPath],
                ['Skript/additionalPeers/envVar.sh', 'scripts/envVar.sh'],
                ['Skript/additionalPeers/fabric-ca-client-config.yaml', 'organizations/peerOrganizations/org1.example.com/fabric-ca-client-config.yaml']
            ];

            foreach ($additionalFiles as $file) {
                $zip->addFile(base_path($file[0]), basename($directoryToZip) . '/' . $file[1]);
            }

            $zip->close();
            unlink(base_path($this->parameterPath));

            return response()->download(public_path($zipFileName))->deleteFileAfterSend(true);
        } else {
            return response()->json(['message' => 'Fehler beim Erstellen der Zip-Datei'], 500);
        }
    }

    private function addDirectoryToZip($zip, $directory, $zipPath, $excludeDirectories)
    {
        $iterator = new DirectoryIterator($directory);
        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isDot()) {
                $filePath = $fileInfo->getPathname();
                $fileZipPath = $zipPath . '/' . $fileInfo->getBasename();

                $shouldExclude = false;
                foreach ($excludeDirectories as $excludeDir) {
                    if ($fileInfo->isDir() && $fileInfo->getBasename() == $excludeDir) {
                        $shouldExclude = true;
                        break;
                    }
                }

                if ($shouldExclude) {
                    continue; // Überspringe den ausgeschlossenen Unterordner
                }

                if ($fileInfo->isDir()) {
                    $zip->addEmptyDir($fileZipPath);
                    $this->addDirectoryToZip($zip, $filePath, $fileZipPath, $excludeDirectories);
                } else {
                    $zip->addFile($filePath, $fileZipPath);
                }
            }
        }
    }


    public function replaceInFile($sourcePath, $destinationPath, $replacements)
    {
        if (File::exists($sourcePath)) {
            $content = File::get($sourcePath);

            foreach ($replacements as $replacement) {
                $content = str_replace($replacement[0], $replacement[1], $content);
            }
            
            File::put($destinationPath, $content);
            return "String erfolgreich ersetzt.";
        } else {
            return "Datei nicht gefunden.";
        }
    }


}