<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

abstract class EcoplanComponent extends Component
{
    /**
     * The components heading
     */
    public static string $heading = '';

    /**
     * returns the Heading for the view
     */
    protected function getHeading(): string
    {
        return static::$heading;
    }

    public function redirectTo($redirectTo)
    {
        return redirect()->to($redirectTo);
    }

    //Sendet an die Node.js-App ein Skript zum Ausführen ohne Live-Ausgabe der Logs
    public static function executeScript($script)
    {

        $response = Http::post(Config::get('app.url').':'.Config::get('ecoplan.nodejs_port').'/livewire-execute-script', [
            'script' => $script,
        ]);

        return $response;
    }

    public static function chmodNetworkDirectory()
    {
        EcoplanComponent::executeScript('sudo chmod -R 777 '.Config::get('ecoplan.network_directory'));
    }

    public function checkSessionCookie()
    {

        if ($this->isSessionCookieSet()) {

            $serializedSessionData = request()->cookie('session_data');
            $sessionData = unserialize($serializedSessionData);
            foreach ($sessionData as $key => $value) {
                session([$key => $value]);
            }

            if (! request()->is('testApp')) {
                return redirect('/testApp');
            }

        }
    }

    public function isSessionCookieSet()
    {
        return request()->hasCookie('session_data');
    }
}
