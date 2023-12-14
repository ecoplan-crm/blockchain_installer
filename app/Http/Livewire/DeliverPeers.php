<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Config;

class DeliverPeers extends EcoplanComponent
{

    public $message = "";
    public $peerPackages = 0;
    public $serverIP = "10.11.12.74"; //TODO vorbelegung rausnehmen

    public function mount()
    {
        $this->peerPackages = session('peerPackages', $this->peerPackages);
        $this->serverIP = session('serverIP', $this->serverIP);
    }

    public function isServerIpSet() {
        return filter_var($this->serverIP, FILTER_VALIDATE_IP) !== false;
    }

    public function updated($propertyName)
    {
        session(['peerPackages' => $this->peerPackages]);
        session(['serverIP' => $this->serverIP]);
    }

    public function next()
    {
        $this->updated(null);
        return redirect()->to('/testApp');
    }

    public function back()
    {
        return redirect()->to('/deployChaincode');
    }

    public function downloadPeer($peerNumber)
    {
        $this->chmodNetworkDirectory();
        return redirect()->route('download.zip', ['peerNumber' => $peerNumber, 'serverIP' => $this->serverIP]);
    }
}
