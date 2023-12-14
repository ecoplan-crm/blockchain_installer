
# Hyperledger Fabric Blockchain Installer

Mit dem vorliegenden Implementierungstool soll der Installationsprozess der Hyperledger Fabric Blockchain vereinfacht und beschleunigt werden. Es soll eine gezielte Wissensvermittlung zu den im Hintergrund ablaufenden Prozessen und der Technologie erfolgen. Das Tool führt die wesentlichen Schritte der Hyperledger Fabric Blockchain-Installation unter einer Oberfläche zusammen. Der User wird dabei Schritt für Schritt durch die Anwendung geführt, um die komplexen Prozesse der Installation zu verstehen. Kontextabhängige Hilfen und Grafiken unterstützen den User bei jedem einzelnen Schritt. Am Ende werden fertige Pakete mit gepackten Dateien (Docker-Container) über einen Download bereitstehen, die dann auf die einbezogenen Rechner verteilt und ausgeführt werden können. Zielanwender sind IT-System-Administratoren, sowie IT-Fachpersonal kleiner und mittlerer Unternehmen und anderer Organisationen. Nach dem Aufsetzen der Blockchain kann der Anwender über eine Testoberfläche Werte auf die Blockchain schreiben und lesen. So können erste Erfahrungen mit der Blockchain gesammelt werden.

## Installation

Voraussetzung für die Installation ist ein Ubuntu 22.X System.
Achtung: Wenn die Anwendung später zum Test eines verteilten Peer-Netzwerkes genutzt werden soll, dann funktioniert dies nur, wenn die Hauptmaschine ein echter oder virtueller Linux-Server ist. Ein Betrieb in WSL2 unter Windows ist nicht möglich.

![App Screenshot](/public/images/Hauptrechner_ZusatzPeers.jpg)

Diese Installationsanleitung geht von einem komplett neu aufgesetztem Ubuntu-System aus. Sollte das Ubuntu-System bereits in Nutzung gewesen sein, können bestimmte Punkte ggf. übersprungen werden.

Alternativ zum manuellen Einrichten liegt in diesem Verzeichnis ein Installationsskript ab. Dieses erledigt alle unten aufgeführten Schritte auf der Ubuntu-Maschine. Dafür mit folgendem Befehl in die Root-Shell wechsel:

```bash
sudo -i
```

Anschließend im Verzeichnis mit folgendem Befehl das Install-Skript anlegen:

```bash
vi install.sh
```

Nun hier aus dem git-Repository den Inhalt aus install.sh in die Zwischenablage kopieren und mit Rechtsklick in das Konsolenfenster einfügen.
Mit :wq die Datei abspeichern. Nun kann das Skript mit fachfolgendem Befehl gestartet werden:

```bash
bash install.sh
```

Achtung: Das Skript übernimmt auch das Klonen dieses Repositorys.

**0. WLS2 aufsetzen**

Wenn die Anwendung nicht für ein verteiltes Netz vorgesehen ist (spricht: nur der Hauptrechner soll in Betrieb genommen werden), oder es nur um einen weiteren Peer für ein schon bestehendes Netzwerk geht, dann kann die Anwendung auch unter WSL2 verwendet werden. Nachfolgend die notwendigen Schritte, um Ubuntu 22.x auf Windows mit WSL2 zu installieren:

* Es müssen die Windows-Features (Systemsteuerung -> Programme -> Programme und Features -> Windows-Features aktivieren oder deaktivieren) Windows-Subsystem für Linux und VM-Plattform aktiviert werden
* Über den Microsoft-Store muss das Linux-Subsystem für Windows installiert werden
* Über den Microsoft-Store muss anschließend Ubuntu 22.x (z.B. 22.04.2 LTS) installiert werden.
* Für eine einfache Handhabung wird auch die Verwendung des Windows-Terminals empfohlen (ebenfalls über den Microsoft Store herunterladen). Nach der Installation der Ubuntu-Version erscheint diese im Dropdown des Windows-Terminals und so können übersichtlich mehrere Ubuntu-Konsolen gestartet werden.

**1. Prüfen, ob ein SSH-Key existiert**

```bash
ls ~/.ssh/
```
Wenn in diesem Ordner bereits die Dateien id_rsa und id_rsa.pub abliegen und die zugehörige Passphrase bekannt ist, dann Schritt 2 überspringen.

**2. SSH-Key generieren**

Nachfolgenden Befehl in der Konsole ausführen (deine E-Mail-Adresse einsetzen) und den Anweisungen folgen. Es kann das Standardverzeichnis zur Ablage des SSH-Keys verwendet werden.

```bash
ssh-keygen -t rsa -b 4096 -C "deine.email@beispiel.de"
```

**3. SSH-Key in GitHub hinterlegen**

Zum Klonen des Git-Projektes muss der eigene SSH-Key auf GitHub hinterlegt werden. Wenn dies bereits geschehen ist, dann kann auch dieser Schritt übersprungen werden. Ansonsten folgende Schritte ausführen:

* Auf https://github.com/settings/keys gehen
* Im Terminal in den Ordner ~/.ssh/ wechseln
* Den Inhalt von id_rsa.pub kopieren und auf der geöffneten GitHub-Seite als neuen SSH-Key einfügen

**4. Repository klonen**

Auf dem Server muss ein Verzeichnis erstellt werden, in das alle benötigten Dateien gekloned werden sollen. Für den Betrieb auf einem Ubuntu-Server, der später Peers für weitere Geräte zur Verfügung stellen soll, wird das Verzeichnis /var/www/html empfohlen. Für die weiteren Peers bzw. für eine Testinstallation unter WSL2 wird das home-Verzeichnis empfohlen. Im ausgewählten Verzeichnis einen Unterordner blockchain erstellen und im Terminal in diesen navigieren. Anschließend nachfolgendes Kommando ausführen:

```bash
git clone git@github.com:ecoplan-crm/digi3_laravel.git
```

**5. Docker installieren**

Im nächsten Schritt muss Docker installiert werden. Bei der vorgeschlagenen Installationsweise wird nicht die neuste Docker-Version installiert. Die vorgeschlagene Installation ist jedoch ausreichend. Wenn man die aktuellste Version installieren möchte, dann stattdessen die Anleitung unter https://docs.docker.com/engine/install/ubuntu/ befolgen.
Beim letzten Befehl darauf achten, "username" durch den eigenen Benutzernamen zu ersetzen.

```bash
sudo apt update
sudo apt install docker.io
sudo apt install docker-compose
sudo usermod -aG docker username
```

Nach Ausführung der Befehle muss das Terminal einmal neugestartet werden, damit die usermod-Änderung aktiv wird.

**6. Fabric-samples klonen**

Die benötigten Dateien für das Hyperledger Fabric Network werden über nachfolgenden Befehl heruntergeladen. Bei Ausführung des Befehls muss man sich in dem in Schritt 4 angelegten Ordner befinden.

```bash
curl -sSL https://bit.ly/2ysbOFE | bash -s -- 2.4.9 1.5.2
```

**7. Docker-Images pullen**

In der Regel werden bereits mit vorherigem Befehl die benötigten Docker-Images heruntergeladen. Zur Sicherheit bzw. falls beim Download etwas schiefgelaufen ist, können mit den nachfolgenden Kommandos die Docker-Images nochmal separat heruntergeladen werden. Wenn die Images bereits vorhanden sind, dann werden diese automatisch übersprungen.

```bash
docker pull hyperledger/fabric-ca:1.5.2
docker pull hyperledger/fabric-tools:2.4.9
docker pull hyperledger/fabric-orderer:2.4.9
docker pull hyperledger/fabric-peer:2.4.9
docker pull hyperledger/fabric-javaenv:2.4.1
```

**8. Weitere Dienste installieren**

Mit den nachfolgenden Kommandos werden alle sonstigen benötigten Packages heruntergeladen und installiert.

```bash
sudo apt update
sudo apt install php8.1
sudo apt install nodejs npm
sudo apt install jq
sudo apt install php8.1-xml
sudo apt install php8.1-curl
sudo apt install openjdk-8-jdk
echo "export JAVA_HOME=/usr/lib/jvm/java-8-openjdk-amd64" >> ~/.bashrc
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.4/install.sh | bash 
```

Das Terminal muss neugestartet werden und anschließend noch folgende Kommandos ausgeführt werden:

```bash
nvm install 18
nvm use 18
```

**9. Composer installieren**

Um alle Abhängigkeiten des Laravel-Projektes zu installieren, wird Composer benötigt. Für dessen Installation nachfolgende Kommandos ausführen:

```bash
sudo apt install php-cli php-zip unzip
sudo php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"
sudo chmod +x /usr/local/bin/composer
```

Das Terminal muss an dieser Stelle einmal neugesartet werden und anschließend muss in das Laravel-Verzeichnis (digi3_laravel) gewechselt und folgender Befehl ausgeführt werden:


```bash
composer install
```

**10. Umgebungsvariablen setzen**

Die Umgebungsvariablen für das Laravel-Projekt müssen an die lokale Installation angepasst werden. Dafür zuerst die .env.example kopieren und einen App-Key generieren:

```bash
cp .env.example .env
php artisan key:generate
```

In der .env müssen nun folgende Variablen angepasst werden:

* WALLET_DIRECTORY
* NETWORK_DIRECTORY

WALLET_DIRECTORY ist der absolute Pfad zum wallet des Node.js-Servers. 
NETWORK_DIRECTORY ist der absolute Pfad zu dem geklonten fabric-samles-Verzeichnis (mit angehängtem "/my-nework")

Je nachdem, wo das Hauptverzeichnis angelegt wurde, sind folgende Konfigurationen möglich:

```bash
WALLET_DIRECTORY='/var/www/html/blockchain/digi3_laravel/public/node/wallet'
NETWORK_DIRECTORY='/var/www/html/blockchain/fabric-samples/my-network'
```

```bash
WALLET_DIRECTORY='/home/ubuntu/blockchain/digi3_laravel/public/node/wallet'
NETWORK_DIRECTORY='/home/ubuntu/blockchain/fabric-samples/my-network'
```

**11. npm install**

Die JavaScript-Abhängigkeiten werden mit folgendem Befehl installiert:

```bash
npm install fabric-network
```

**12. PHP-Umgebungsvariablen setzen**

Zwei PHP-Umgebungsvariablen müssen angepasst werden, damit später der Up- und Download der Peer-Packages funktioniert. Dafür zuerst die php.ini Datei suchen:

```bash
php -i | grep php.ini
```

Den Pfad, der hier ermittelt wird, in folgenden Befehl einsetzen:

```bash
sudo nano /etc/php/8.1/cli/php.ini
```

In der Datei folgende Parameter abändern:

* post_max_size = 1G
* upload_max_filesize = 1G

**13. visudo-Datei bearbeiten**

Die Laravel-Anwendung muss später selbstständig bestimmte sudo-Kommandos ausführen können, weswegen sie die dafür nötigen Berechtigungen erhalten muss. Die visudo-Datei öffnen:

```bash
sudo visudo
```

Folgende Zeilen einfügen und dabei username mit dem Benutzernamen ersetzen, über den auch der NodeJs-Server gestartet wird. In der Regel ist dies der eigene Benutzer. Der Pfad "/var/www/html/blockchain/fabric-samples/my-network" muss zudem so angepasst werden, dass dieser auf den eigenen fabric-samples-Ordner zeigt (mit den entsprechenden Unterordnern).

```bash
username ALL=(ALL) NOPASSWD: /bin/chmod -R 777 /var/www/html/blockchain/fabric-samples/my-network
username ALL=(ALL) NOPASSWD: /bin/chmod -R 777 /var/www/html/blockchain/fabric-samples/asset-my
username ALL=(ALL) NOPASSWD: /bin/sed *
username ALL=(ALL) NOPASSWD: /bin/tee *
username ALL=(ALL) NOPASSWD: /bin/cp *
username ALL=(ALL) NOPASSWD: /bin/rm *
username ALL=(ALL) NOPASSWD: /bin/grep *
```

**14. Anwendung starten**

Möchte man die Anwendung nur lokal auf der Hauptmaschine starten (und später keine weiteren Peers in das Netzwerk einbinden) oder sich nur als weiterer Peer in ein Netzwerk eingliedern, dann ist dies bereits der letzte Schritt. Man muss in den digi3_laravel-Ordner wechseln und dort die folgenden drei Kommandos ausführen (entweder in drei Konsolen oder jeweils mit &, sodass man weitere Befehle eingeben kann)

```bash
php artisan serve
npm run dev
node public/node/server.js
```

**15. Anwendung von extern erreichbar machen**

Wenn diese Ubuntu-Installation als Hauptserver dienen soll, und später weitere Peers in das von diesem Server erstellte Netzwerk beitreten sollen, dann müssen noch weitere Anpassungen vorgenommen werden:

* Umgebungsvariablen in .env anpassen:
  * APP_URL auf Server-Domain ändern
  * APP_ENV auf production ändern
* Apache-Server konfigurieren, der auf das public-Verzeichnis des Laravel-Projektes verweist (z.B. "/var/www/html/blockchain/digi3_laravel/public") und starten
* "npm run build" ausführen
* Node.js-Server über das Tool pm2 starten (ggf. vorher noch installieren)




## Screenshots
![App Screenshot](/public/images/Startseite.jpeg)

## Demo

https://app.screencast.com/EOAp84xf8Iu3M


## Badges

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)


## Dokumentation

Die Dokumentation ist in die Laravel-App integriert.

## Troubleshooting

Beim Starten des Installationsskriptes wird erscheint folgende (oder ähnliche) Fehlermeldung:

```bash
install.sh: line 2: $'\r': command not found
Dieses Skript unterstützt Sie bei der Installation und dem Betrieb des Ecoplan-Blockchain-Konfigurators
': not a valid identifier `ACTION
install.sh: line 5: $'\r': command not found
install.sh: line 13: syntax error near unexpected token `elif'
'nstall.sh: line 13: `elif [ "$ACTION" == "stop" ]; then
```

Problem:
Das Installationsskript wurde über Windows auf die Ubuntu-Maschine kopiert und die Windows-Zeilenumbrüche machen Probleme.

Lösung:
Entweder die Datei zu Unix-Zeilenumbrüchen konvertieren (dos2unix install.sh) oder die Datei neu erstellen und den Inhalt aus GitHub kopieren und direkt in Ubuntu in das Installskript einfügen.

## FAQ

#### Was ist der Unterschied zwischen Hauptserver und weiteren Peers?

Diese Anwendung ermöglicht das Aufsetzen eines Hyperledger fabric Netzwerks. Dieses kann entweder ausschließlich auf einem einzelnen PC erfolgen oder verteilt über mehrere PCs. Wenn nur ein einzelner PC dieses Netzwerk betreiben soll, dann kann die Installation des Netzwerks auf einer beliebigen Ubuntu 22.x Maschine erfolgen. Soll jedoch ein verteiltes Netzwerk aufgebaut werden, dann ist es zwingend erforderlich, dass die Ubuntu-Maschine von anderen PCs über eine IP-Adresse erreichbar ist. Deswegen ist es in diesem Szenario nicht möglich, den Hauptserver auf einem Windows WSL2 Ubuntu zu betreiben. Der Hauptserver beschreibt in diesem Kontext die Maschine, die als erstes aufgesetzt wird, welche dann die weiteren Peers auf die anderen PCs verteilt. Die weiteren PCs können wiederum auch in diesem Szenario auf Windows WSL2 Ubuntu-Maschinen betrieben werden.

#### Kann ich ein Ubuntu auf Basis von WSL2 verwenden?

Siehe Antwort zur Frage "Was ist der Unterschied zwischen Hauptserver und weiteren Peers?"


## Features

- Einfache und geführte Installation des Hyperledger Fabric Netzwerk
- Konfigurierbarkeit der Peers des Startnetzwerkes
- Automatische Erzeugung der Konfigurationsdateien für das Hyperledger Fabric Netzwerk
- Automatisiertes Starten des Netzwerkes
- Automatisiertes Deployen des Chaincodes
- Simple Bereitstellung von weiteren Peer-Paketen zum Betrieb auf weiteren PCs
- Einfache und geführte Eingliederung der weiteren Peers in das aufgesetzte Netzwerk
- Moderne Oberfläche zur Visualisierung des Ledgers und der Docker-Container
- Zeitmessung der Ledger-Aktionen


## License

[MIT](https://choosealicense.com/licenses/mit/)


## Authors

- [Ecoplan GmbH](https://www.ecoplan-crm.de)
- [@mgerk-ep](https://github.com/mgerk-ep)

![Logo](/public/images/Ecoplan-Logo.png)
