<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Config;

class Navigation extends EcoplanComponent
{
    public $status = [];
    public $count = 0;
    public $nav = [];
    public $showURLAlert = true;

    public function render()
    {
        return view('livewire.navigation');
    }

    public function reloadWithAppURL() {
        return redirect()->to(Config::get('app.url') . ((array_key_exists("port", parse_url(request()->fullUrl())))?':' . parse_url(request()->fullUrl())["port"] : ''));
    }

    public function ignore() {
        $this->showURLAlert = false;
    }

    public function mount()
    {
        $currentRoute = Request::path();

        $this->status = [
            'parameters' => 'upcoming',
            'configurations' => 'upcoming',
            'network' => 'upcoming',
            'chaincode' => 'upcoming',
            'deliver' => 'upcoming',
            'testapp' => 'upcoming',
        ];

        $completed = false;

        if ($currentRoute === 'testApp' || $completed) {

            $this->status['deliver'] = 'completed';

            if (!$completed) {
                $this->status['testapp'] = 'current';
                $completed = true;
            }
        }

        if ($currentRoute === 'deliverPeers' || $completed) {

            $this->status['chaincode'] = 'completed';

            if (!$completed) {
                $this->status['deliver'] = 'current';
                $completed = true;
            }
        }

        if ($currentRoute === 'deployChaincode' || $completed) {

            $this->status['network'] = 'completed';

            if (!$completed) {
                $this->status['chaincode'] = 'current';
                $completed = true;
            }
        }

        if ($currentRoute === 'startNetwork' || $completed) {

            $this->status['configurations'] = 'completed';

            if (!$completed) {
                $this->status['network'] = 'current';
                $completed = true;
            }
        }

        if ($currentRoute === 'createConfiguration' || $completed) {

            $this->status['parameters'] = 'completed';

            if (!$completed) {
                $this->status['configurations'] = 'current';
                $completed = true;
            }
        }

        if ($currentRoute === 'enterParameters' || $completed) {

            if (!$completed) {
                $this->status['parameters'] = 'current';
                $completed = true;
            }
        }

        $this->nav = [
            [
                'condition' => session("newNetwork"),
                'description' => "Parameter",
                'status' => $this->status['parameters'],
                'redirectTo' => "/enterParameters",
                'laststep' => false
            ],
            [
                'condition' => true,
                'description' => "Konfigurationen",
                'status' => $this->status['configurations'],
                'redirectTo' => "/createConfiguration",
                'laststep' => false
            ],
            [
                'condition' => true,
                'description' => "Netzwerk",
                'status' => $this->status['network'],
                'redirectTo' => "/startNetwork",
                'laststep' => false
            ],
            [
                'condition' => true,
                'description' => "Chaincode",
                'status' => $this->status['chaincode'],
                'redirectTo' => "/deployChaincode",
                'laststep' => false
            ],
            [
                'condition' => session("newNetwork"),
                'description' => "Peers",
                'status' => $this->status['deliver'],
                'redirectTo' => "/deliverPeers",
                'laststep' => false
            ],
            [
                'condition' => true,
                'description' => "Test",
                'status' => $this->status['testapp'],
                'redirectTo' => "/testApp",
                'laststep' => true
            ],
        ];
    }
}