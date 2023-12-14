<?php

return [
    'attributes' => [
        'peerCount' => 'Anzahl Peers',
        'channelName' => 'Channel-Name',
        'chaincodeLanguage' => 'Sprache',
        'chaincodeName' => 'Name',
        'chaincodeVersion' => 'Version',
        'chaincodeSequenz' => 'Sequenz',
        'chaincodeDirectory' => 'Speicherort',
        'assetID' => 'ID',
        'color' => 'Farbe',
        'size' => 'Größe',
        'owner' => 'Eigentümer',
        'appraisedValue' => 'Wert',
        'dockerComposeTestNet' => 'docker-compose-test-net.yaml',
        'composeTestNet' => 'compose-test-net.yaml',
        'composeCa' => 'compose-ca.yaml',
        'configtx' => 'configtx.yaml',
    ],
    'min' => [
        'array' => 'The :attribute must have at least :min items.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'numeric' => 'Der Mindestwert ist :min.',
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'max' => [
        'numeric' => 'Der Maxwert ist :max.',
        'string' => ':attribute darf maximal :max Zeichen lang sein.'
    ],
    'required' => 'Das Feld :attribute ist verpflichtend.',
    'regex' => 'Die Eingabe entspricht nicht dem benötigten Format.',
];
