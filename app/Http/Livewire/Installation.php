<?php

namespace App\Http\Livewire;

class Installation extends EcoplanComponent
{
    public function render()
    {
        session(['newNetwork' => true]);
        return view('livewire.installation');
    }
}
