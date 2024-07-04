<?php

namespace App\Http\Livewire;

class DeployChaincode extends EcoplanComponent
{
    protected $listeners = ['finished'];

    public $deploying = false;

    public $logContent = '';

    public function render()
    {
        return view('livewire.deploy-chaincode');
    }

    public function mount()
    {
        $this->logContent = session('deployLogContent', $this->logContent);
    }

    public function start()
    {
        $this->deploying = true;
        $this->clearLog();
    }

    public function clearLog()
    {
        $this->logContent = '';
        session()->forget('deployLogContent');
    }

    public function finished($content)
    {
        $this->deploying = false;
        session(['deployLogContent' => $content]);
        $this->logContent = $content;
    }

    public function next()
    {
        if (session('newNetwork')) {
            return redirect()->to('/deliverPeers');
        } else {
            return redirect()->to('/testApp');
        }
    }

    public function back()
    {
        return redirect()->to('/startNetwork');
    }
}
