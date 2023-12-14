<?php

namespace App\Http\Livewire;

class Docker extends EcoplanComponent
{
    public $containers = array();
    protected $listeners = ['refreshComponent'];


    public function refresh()
    {
        $command = "curl -X GET --unix-socket /var/run/docker.sock http:/v1.40/containers/json";
        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            return response()->json(['error' => 'Fehler beim Abrufen der Docker-Container']);
        }

        $this->containers = json_decode(implode("\n", $output), true);
        $this->containers = collect($this->containers)->sortBy('Names')->values()->all();
    }

    public function mount()
    {
        $this->refresh();
    }

    public function updated()
    {
        $this->refresh();
    }

    public function refreshComponent()
    {
        $this->refresh();
    }

    public function render()
    {
        return view('livewire.docker', [
            'containers' => $this->containers,
        ]);
    }
}
