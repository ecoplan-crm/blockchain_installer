<?php

namespace App\Http\Livewire;

class EnterParameters extends EcoplanComponent
{
    public $peerCount = 2;

    public $channelName = 'channel';

    public $chaincodeLanguage = 'Java';

    public $chaincodeName = 'basic';

    public $chaincodeVersion = '1.0';

    public $chaincodeSequenz = '1';

    public $chaincodeDirectory = '../asset-my/chaincode/';

    public function render()
    {
        return view('livewire.enter-parameters');
    }

    public function mount()
    {
        $this->peerCount = session('peerCount', $this->peerCount);
        $this->channelName = session('channelName', $this->channelName);
        $this->chaincodeLanguage = session('chaincodeLanguage', $this->chaincodeLanguage);
        $this->chaincodeName = session('chaincodeName', $this->chaincodeName);
        $this->chaincodeVersion = session('chaincodeVersion', $this->chaincodeVersion);
        $this->chaincodeSequenz = session('chaincodeSequenz', $this->chaincodeSequenz);
        $this->chaincodeDirectory = session('chaincodeDirectory', $this->chaincodeDirectory);
    }

    public function rules()
    {
        return [
            'peerCount' => ['required', 'numeric', 'min:2'],
            'channelName' => ['required', 'regex:/^[a-z]+$/', 'max:255'],
            'chaincodeLanguage' => ['required', 'string', 'max:255'],
            'chaincodeName' => ['required', 'string', 'max:255'],
            'chaincodeVersion' => ['required', 'string', 'max:255'],
            'chaincodeSequenz' => ['required', 'numeric', 'min:1', 'max:1'],
            'chaincodeDirectory' => ['required', 'string', 'max:255'],
        ];
    }

    public function updated($propertyName)
    {
        session(['peerCount' => $this->peerCount]);
        session(['channelName' => $this->channelName]);
        session(['chaincodeLanguage' => $this->chaincodeLanguage]);
        session(['chaincodeName' => $this->chaincodeName]);
        session(['chaincodeVersion' => $this->chaincodeVersion]);
        session(['chaincodeSequenz' => $this->chaincodeSequenz]);
        session(['chaincodeDirectory' => $this->chaincodeDirectory]);

        session()->forget('dockerComposeTestNet');
        session()->forget('composeTestNet');
        session()->forget('composeCa');
        session()->forget('configtx');
    }

    public function next()
    {
        $this->updated(null);
        $validatedData = $this->validate();

        return redirect()->to('/createConfiguration');
    }

    public function resetForm()
    {
        $this->reset();
        $this->updated(null);
    }
}
