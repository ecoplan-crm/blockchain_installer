<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Config;

class StartNetwork extends EcoplanComponent
{
    protected $listeners = ['finished'];
    public $starting = false;
    public $logContent = "";

    public function render()
    {
        return view('livewire.start-network');
    }

    public function mount()
    {
        $this->logContent = session('networkLogContent', $this->logContent);
    }

    public function setLogContent($logContent) {
        $this->logContent = $logContent;
        session(['networkLogContent' => $logContent]);
    }

    public function start()
    {
        $this->starting = true;
        $this->clearLog();
    }

    public function clearLog()
    {
        $this->logContent = "";
        session()->forget('networkLogContent');
    }

    public function finished($content)
    {
        $this->starting = false;
        $this->setLogContent($content);
    }

    public function next()
    {
        return redirect()->to('/deployChaincode');
    }

    public function back()
    {
        return redirect()->to('/createConfiguration');
    }
}