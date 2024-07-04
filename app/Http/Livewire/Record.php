<?php

namespace App\Http\Livewire;

class Record extends EcoplanComponent
{
    public $entry;

    public function setData()
    {
        $data = [
            'assetID' => $this->entry['assetID'],
            'color' => $this->entry['color'],
            'size' => $this->entry['size'],
            'owner' => $this->entry['owner'],
            'appraisedValue' => $this->entry['appraisedValue'],
        ];

        $this->emitUp('setData', $data);
    }
}
