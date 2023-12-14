#!/bin/bash

echo "Dieses Skript unterstützt Sie bei der Installation und dem Betrieb des Ecoplan-Blockchain-Konfigurators"

# Bestätigung, dass das Skript mit sudo -i gestartet wurde
        echo "Bitte bestätigen Sie, dass dieses Skript mit 'sudo -i' gestartet wurde."
        read -p "Sind Sie sicher? (Ja/Nein): " confirmation

        if [ "$confirmation" != "Ja" ]; then
            echo "Das Skript wird beendet, da keine Bestätigung erfolgte."
            exit 1
        fi

        if [ "$(id -u)" -ne 0 ]; then
            echo "Das Skript wird nicht als Root-Benutzer ausgeführt. Der Vorgang wird beendet. Bitte starten die das Skript erneut mit dem Root-Benutzer."
            exit 1
        else
            echo "Das Skript wird als Root-Benutzer ausgeführt."
        fi

read -p "Möchten Sie die Anwendung installieren, starten, oder beenden? (install/start/stop): " ACTION

if [ "$ACTION" == "start" ]; then
    # Starte die Anwendung

    if [ -f .server ]; then

        cd /var/www/html/Digi3/digi3_laravel

        if command -v "pm2" &>/dev/null; then
            echo .
        else
            npm install -g pm2
        fi

        npm run build
        pm2 start public/node/server.js
        sudo service apache2 restart

        echo "Die Anwendung läuft jetzt unter der bei der Installation angegebenen URL."

    else

        read -p "Die Anwendung wird nach dem Drücken von [Enter] gestartet und ist dann über http://localhost:8000 erreichbar."

        cd /root/Digi3/digi3_laravel
        php artisan serve &
        npm run dev &
        node public/node/server.js &

    fi

elif [ "$ACTION" == "stop" ]; then
    # Stoppe die Anwendung

    if [ -f .server ]; then

        sudo service apache2 stop
        pm2 stop all

    else
        echo "Anwendung wird gestoppt..."
        pkill php
        pkill node
        echo "Die Anwendung wurde gestoppt."
    fi
elif [ "$ACTION" == "install" ]; then

    MAINPATH=""

    read -p "Handelt es sich bei der aktuellen Ubuntu-Maschine um den Hauptcomputer, der im Netzwerk für weitere Computer erreichbar sein soll? (j/n): " SERVER

    if [ "$SERVER" == "j" ]; then

        read -p "Bitte geben Sie die URL sein, über die der Server erreichbar sein wird (z.B. epcdigi3.ecoplan.de): " SERVER_URL
        echo "$SERVER_URL" > ".server"
        MAINPATH="/var/www/html"
        sudo mkdir -p "$MAINPATH"
    else
        if [ -f .server ]; then
            rm .server
        fi
        MAINPATH="/root"
    fi

    while true; do

        echo "Schritte 1 und 2: Prüfung, ob ein SSH-Key im Default-Speicherort existiert, und ggf. einen neuen generieren"
        if [ ! -f ~/.ssh/id_rsa ]; then
            echo "SSH-Key existiert nicht (am Default-Speichertort). Es wird ein neuer SSH-Key generiert..."
            read -p "Geben Sie Ihre E-Mail-Adresse für den SSH-Key ein: " EMAIL
            read -s -p "Geben Sie eine Passphrase für den SSH-Key ein: " PASSPHRASE
            echo "SSH-Key wird generiert..."
            ssh-keygen -t rsa -b 4096 -C "$EMAIL" -N "$PASSPHRASE" -f ~/.ssh/id_rsa
        else
            echo "SSH-Key existiert bereits."
        fi

        echo "."

        echo "Schritt 3: SSH-Key in Github hinterlegen"
        echo "Falls der SSH-Key noch nicht bei GitHub hinterlegt ist, bitte manuell erledigen. Dafür die Seite https://github.com/settings/keys aufrufen."
        read -p "Drücken Sie [Enter], um den SSH-Key anzuzeigen."
        cat ~/.ssh/id_rsa.pub
        read -p "Drücken Sie [Enter], wenn der SSH-Key erfolgreich auf GitHub hinterlegt wurde."

        echo "."

        echo "Schritt 4: Repository clonen"
        read -p "Drücken Sie [Enter] um fortzufahren"
        cd "$MAINPATH"
        sudo mkdir -p Digi3
        cd Digi3
        sudo git clone git@github.com:ecoplan-crm/blockchain_installer.git

        if [ ! -d "$MAINPATH/Digi3/digi3_laravel" ]; then
            echo "Repository wurde nicht erfolgreich geklont. Es wird zurück zu Schritt 1 gesprungen."
            continue
        else
            echo "Repository wurde erfolgreich geklont."
            break
        fi

    done

    echo "."

    while true; do

        echo "Schritt 5: Docker installieren"
        read -p "Drücken Sie [Enter] um fortzufahren"
        sudo apt update
        sudo apt install -y docker.io docker-compose
        sudo usermod -aG docker $(whoami)

        # Überprüfung, ob Docker erfolgreich installiert wurde
        if ! command -v docker &>/dev/null; then
            echo "Docker wurde nicht erfolgreich installiert. Ein erneuter Installationsvorgang wird gestartet."
            continue
        else
            echo "Docker wurde erfolgreich installiert."
            break
        fi
    done

    echo "."

    while true; do

        echo "Schritt 6: Fabric-Samples clonen"
        read -p "Drücken Sie [Enter] um fortzufahren"
        sudo curl -sSL https://bit.ly/2ysbOFE | bash -s -- 2.4.9 1.5.2

        # Überprüfung, ob das Fabric-Samples-Verzeichnis existiert
        if [ ! -d "$MAINPATH/Digi3/fabric-samples" ]; then
            echo "Fabric-Samples-Verzeichnis wurde nicht gefunden. Ein erneuter Clone-Vorgang wird gestartet."
            continue
        else
            echo "Fabric-Samples-Verzeichnis wurde erfolgreich gecloned."
            break
        fi

    done

    echo "."

    echo "Schritt 7: Docker Images pullen"
    read -p "Drücken Sie [Enter] um fortzufahren"
    sudo docker pull hyperledger/fabric-ca:1.5.2
    sudo docker pull hyperledger/fabric-tools:2.4.9
    sudo docker pull hyperledger/fabric-orderer:2.4.9
    sudo docker pull hyperledger/fabric-peer:2.4.9
    sudo docker pull hyperledger/fabric-javaenv:2.4.1
    echo "Docker Images wurden gekloned"

    echo "."

    echo "Schritt 8: Weitere Dienste installieren"
    read -p "Drücken Sie [Enter] um fortzufahren"
    sudo apt update
    sudo apt install -y php8.1 nodejs jq php8.1-xml php8.1-curl openjdk-8-jdk
    echo "export JAVA_HOME=/usr/lib/jvm/java-8-openjdk-amd64" >>~/.bashrc
    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.4/install.sh | bash
    source ~/.nvm/nvm.sh
    nvm install 18
    nvm use 18
    nvm alias default 18
    echo "Dienste wurden installiert"

    echo "."

    echo "Schritt 9: Composer installieren"
    read -p "Drücken Sie [Enter] um fortzufahren"
    cd "$MAINPATH/Digi3/digi3_laravel"
    sudo apt install -y php-cli php-zip unzip
    sudo php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
    composer install
    echo "Composer wurde installiert"

    echo "."

    echo "Schritte 10: .env einrichten"
    read -p "Drücken Sie [Enter] um fortzufahren"
    #Die folgenden Zeilen sind auskommentiert, da in dieser Installation nur das Standard-Directory unterstützt wird und damit auch die Pfade schon fest hinterlegt werden können
    #read -p "WALLET_DIRECTORY: " WALLET_DIR
    #read -p "NETWORK_DIRECTORY: " NETWORK_DIR
    cp .env.example .env
    php artisan key:generate
    sed -i "s|WALLET_DIRECTORY=.*|WALLET_DIRECTORY='$MAINPATH/Digi3/digi3_laravel/public/node/wallet'|" .env
    sed -i "s|NETWORK_DIRECTORY=.*|NETWORK_DIRECTORY='$MAINPATH/Digi3/fabric-samples/my-network'|" .env

    if [ "$SERVER" == "j" ]; then
        sed -i "s|APP_ENV=.*|APP_ENV=production|" .env
        sed -i "s|APP_URL=.*|APP_URL=http://$SERVER_URL|" .env
        sed -i "s|LARAVEL_PORT=.*|LARAVEL_PORT=80|" .env
    fi

    echo ".env wurde eingerichtet"

    echo "."

    echo "Schritt 11: fabric-network installieren"
    read -p "Drücken Sie [Enter] um fortzufahren"
    npm install fabric-network
    echo "fabric-network wurde installiert"

    echo "."

    echo "Schritt 12: Max filesize in php.ini setzen"
    read -p "Drücken Sie [Enter] um fortzufahren"
    PHP_INI_PATH=$(php -i | grep "Loaded Configuration File" | sed -n 's|.*=> \(.*\)|\1|p')
    sudo sed -i "s/post_max_size = .*/post_max_size = 1G/" $PHP_INI_PATH
    sudo sed -i "s/upload_max_filesize = .*/upload_max_filesize = 1G/" $PHP_INI_PATH
    echo "Max filesize wurde gesetzt"

    echo "."

    echo "Schritt 13: sudoers-Datei anpassen"
    read -p "Drücken Sie [Enter] um fortzufahren"
    echo "$USER ALL=(ALL) NOPASSWD: /bin/chmod -R 777 $MAINPATH/Digi3/fabric-samples/my-network" | sudo tee -a /etc/sudoers
    echo "$USER ALL=(ALL) NOPASSWD: /bin/chmod -R 777 $MAINPATH/Digi3/fabric-samples/asset-my" | sudo tee -a /etc/sudoers
    echo "$USER ALL=(ALL) NOPASSWD: /bin/sed *" | sudo tee -a /etc/sudoers
    echo "$USER ALL=(ALL) NOPASSWD: /bin/tee *" | sudo tee -a /etc/sudoers
    echo "$USER ALL=(ALL) NOPASSWD: /bin/cp *" | sudo tee -a /etc/sudoers
    echo "$USER ALL=(ALL) NOPASSWD: /bin/grep *" | sudo tee -a /etc/sudoers
    echo "$USER ALL=(ALL) NOPASSWD: /bin/rm *" | sudo tee -a /etc/sudoers
    echo "sudoers-Datei wurde angepasst"

    echo "."

    if [ "$SERVER" == "j" ]; then

        echo "Schritt 15: Anwendung von extern erreichbar machen"
        read -p "Drücken Sie [Enter] um fortzufahren"

        sudo chown -R www-data:www-data "$MAINPATH/Digi3"

        cd "$MAINPATH/Digi3/digi3_laravel"

        APACHE_LOG_DIR='${APACHE_LOG_DIR}'

        APACHE_CONFIG_CONTENT="
<VirtualHost *:80>
    ServerName $SERVER_URL
    DocumentRoot $MAINPATH/Digi3/digi3_laravel/public

    <Directory $MAINPATH/Digi3/digi3_laravel>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog $APACHE_LOG_DIR/digi3-error.log
    CustomLog $APACHE_LOG_DIR/digi3-access.log combined
</VirtualHost>
        "

        # Erstellt die Apache-Config-Datei
        echo "$APACHE_CONFIG_CONTENT" >"/etc/apache2/sites-available/digi3.conf"

        sudo a2dissite 000-default.conf
        sudo a2ensite digi3.conf

        sudo a2enmod rewrite

        # Überprüfen Sie den Erfolg und zeigen Sie eine Meldung an
        if [ $? -eq 0 ]; then
            echo "Die Datei $FILENAME wurde erfolgreich im Verzeichnis $FILE_PATH erstellt."
        else
            echo "Fehler beim Erstellen der Datei $FILENAME im Verzeichnis $FILE_PATH."
        fi

    fi

    echo "Anwendung erfolgreich installiert. Bitte starten Sie das Terminal neu und führen das Skript erneut aus. Wählen Sie dann die Aktion zum Starten der Anwendung."

else
    echo "Ungültige Eingabe. Das Terminal kann später manuell neu gestartet werden."
fi
