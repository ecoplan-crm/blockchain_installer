<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class Start extends EcoplanComponent
{

    public function render()
    {
        return view('livewire.start');
    }

    public function mount()
    {
        if (!request()->has('ignoreSessionCookie')) {
            $this->checkSessionCookie();
        }
    }

    public function joinNetwork()
    {
        session(['newNetwork' => false]);
        return redirect()->to('/createConfiguration');
    }

    public function installNetwork()
    {
        session(['newNetwork' => true]);
        session(['peerNumber' => 0]);
        return redirect()->to('/enterParameters');
    }

    public function resetNetwork()
    {
        $networkDirectory = Config::get('ecoplan.network_directory');
        $assetDirectory = $this->getAbsolutePath("$networkDirectory/../asset-my");
        $walletDirectory = Config::get('ecoplan.wallet_directory');
        $digiDirectory = $this->getAbsolutePath("$walletDirectory/../../../");

        $networkFolderExists = File::exists($networkDirectory);

        if ($networkFolderExists) {
            EcoplanComponent::executeScript($networkDirectory . "/network.sh down");
            EcoplanComponent::executeScript("rm -R " . $networkDirectory);
        }

        EcoplanComponent::executeScript("cp -R " . realpath("$digiDirectory/Skript/my-network") . " $networkDirectory");
        EcoplanComponent::chmodNetworkDirectory();



        EcoplanComponent::executeScript("rm -R $assetDirectory");
        EcoplanComponent::executeScript("cp -R " . realpath("$digiDirectory/Skript/asset-my") . " " . $assetDirectory);
        EcoplanComponent::executeScript("sudo chmod -R 777 $assetDirectory");

        EcoplanComponent::executeScript("rm $walletDirectory/*");

        if (!$networkFolderExists) {
            EcoplanComponent::executeScript("$networkDirectory/network.sh down");
        }

        Cookie::queue(Cookie::forget('session_data'));

        return redirect()->to('/sessionClear');
    }

    public function getAbsolutePath($relativePath)
    {
        return $this->canonicalize($relativePath);
    }

    function canonicalize($path) {
        $parts = array_filter(explode('/', $path), 'strlen');
        $absolutes = [];
        foreach ($parts as $part) {
            if ('.' == $part) continue;
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        return '/' . implode('/', $absolutes);
    }
}
