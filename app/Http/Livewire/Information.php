<?php

namespace App\Http\Livewire;

class Information extends EcoplanComponent
{
    public $blocks = [];

    public function render()
    {
        session(['newNetwork' => true]);

        return view('livewire.information');
    }

    public function mount()
    {
        // Hier werden die HTML-Elemente aus dem Ordner geladen
        $blocks = [];

        // Verwende den Dateisystemzugriff von Laravel, um Dateien im Ordner zu durchsuchen
        $directory = resource_path('views/information-blocks');
        $files = scandir($directory);

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                // Lade den Inhalt der HTML-Datei
                $blockContent = file_get_contents("$directory/$file");

                // Verwende eine einfache Regex, um den Inhalt in "headline" und "content" aufzuteilen
                preg_match('/<headline>(.*?)<\/headline>/s', $blockContent, $headlineMatch);
                preg_match('/<content>(.*?)<\/content>/s', $blockContent, $contentMatch);

                // Füge "headline" und "content" zum $blocks-Array hinzu
                $blocks[] = [
                    'headline' => $headlineMatch[1] ?? '',
                    'content' => $contentMatch[1] ?? '',
                ];
            }
        }

        // Weise das $blocks-Array dem Parameter "blocks" zu
        $this->blocks = $blocks;
    }
}
