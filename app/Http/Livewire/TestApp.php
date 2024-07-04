<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class TestApp extends EcoplanComponent
{
    public $data = [];

    public $edit = false;

    public $deleteModal = false;

    public $isNewAsset = false;

    public $isSaving = false;

    public $errorMessage;

    public $startTime = 0;

    public $executionTime = 0;

    public $cookieSavedMessage = '';

    public $assetID;

    public $color;

    public $size;

    public $owner;

    public $appraisedValue;

    public $ending = false;

    public $emtyModalData = [
        'assetID' => '',
        'color' => '',
        'size' => '',
        'owner' => '',
        'appraisedValue' => '',
    ];

    protected $listeners = ['setData', 'deleteEntry'];

    public function render()
    {
        return view('livewire.test-app', [
            'data' => $this->data,
        ]);
    }

    public function rules()
    {
        return [
            'assetID' => ['required'],
            'color' => ['required'],
            'size' => ['required'],
            'owner' => ['required'],
            'appraisedValue' => ['required'],
        ];
    }

    public function saveToCookie()
    {
        $sessionData = [
            'peerCount' => Session::get('peerCount'),
            'channelName' => Session::get('channelName'),
            'chaincodeLanguage' => Session::get('chaincodeLanguage'),
            'chaincodeName' => Session::get('chaincodeName'),
            'chaincodeVersion' => Session::get('chaincodeVersion'),
            'chaincodeSequenz' => Session::get('chaincodeSequenz'),
            'chaincodeDirectory' => Session::get('chaincodeDirectory'),
            'peerPackages' => Session::get('peerPackages'),
            'serverIP' => Session::get('serverIP'),
            'newNetwork' => Session::get('newNetwork'),
            'peerNumber' => Session::get('peerNumber'),
        ];
        $serializedSessionData = serialize($sessionData);

        // Cookie für eine bestimmte Zeit setzen (10 Tage)
        Cookie::queue('session_data', $serializedSessionData, 14400);

        // Erfolgsmeldung anzeigen oder weitere Aktionen ausführen
        $this->cookieSavedMessage = 'Cookie erfolgreich gespeichert.';
    }

    public function init()
    {
        $this->setStartTime();
        $this->sendCommand('InitLedger', null);
        $this->refresh();
        $this->calculateExecutionTime();
    }

    public function mount()
    {
        $this->checkSessionCookie();
        //$this->refresh();
    }

    public function newEntry()
    {
        $this->setData($this->emtyModalData);
        $this->toggleEditor(true);
    }

    public function toggleEditor($state)
    {
        $this->edit = $state;
    }

    public function assetExists($assetID)
    {

        foreach ($this->data as $asset) {
            if ($asset['assetID'] == $assetID) {
                return true;
            }
        }

        return false;
    }

    public function cancel()
    {
        $this->toggleEditor(false);
        $this->deleteModal = false;
        $this->errorMessage = null;
    }

    public function setStartTime()
    {
        $this->startTime = round(microtime(true) * 1000);
    }

    public function calculateExecutionTime()
    {
        $this->executionTime = (round(microtime(true) * 1000) - $this->startTime) / 1000;
    }

    public function save()
    {
        $this->validate();
        $this->setStartTime();
        $response = $this->sendCommand(($this->isNewAsset) ? 'CreateAsset' : 'UpdateAsset', [$this->assetID, $this->color, $this->size, $this->owner, $this->appraisedValue]);
        if (is_array($response)) {
            $this->refresh();
            $this->toggleEditor(false);
            $this->errorMessage = null;
        } else {
            $this->errorMessage = strval($response);
        }
        $this->calculateExecutionTime();
    }

    public function refreshWithTiming()
    {
        $this->setStartTime();
        $this->refresh();
        $this->calculateExecutionTime();
    }

    private function refresh()
    {
        $response = $this->sendCommand('GetAllAssets', null);
        if (is_array($response)) {
            $this->data = $response;
        } else {
            $this->data = [];
        }
    }

    public function sendCommand($function, $parameter)
    {

        $response = Http::get('http://localhost:'.Config::get('ecoplan.nodejs_port').'/ledger', [
            'command' => $function,
            'parameter' => $parameter,
            'channel' => session('channelName'),
            'isNewNetwork' => session('newNetwork') ?? 0,
            'peerNumber' => session('peerNumber'),
            'ccn' => session('chaincodeName'),
        ]);

        if ($response->json() == null) {
            return $response;
        }

        return $response->json();
    }

    public function next()
    {
        return redirect()->to('/?ignoreSessionCookie');
    }

    public function back()
    {
        if (session('newNetwork')) {
            return redirect()->to('/deliverPeers');
        } else {
            return redirect()->to('/deployChaincode');
        }

    }

    public function setData($data)
    {
        foreach ($data as $parameter => $value) {
            $this->$parameter = $value;
        }
        $this->isNewAsset = ! $this->assetExists($this->assetID);
    }

    public function deleteEntry()
    {
        $this->setStartTime();

        $response = $this->sendCommand('DeleteAsset', [$this->assetID]);

        if ($response == 'Asset removed successful!') {
            $this->refresh();
            $this->deleteModal = false;
            $this->errorMessage = null;
        } else {
            $this->errorMessage = strval($response);
        }

        $this->calculateExecutionTime();
    }
}
