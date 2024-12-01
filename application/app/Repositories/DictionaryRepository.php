<?php

namespace App\Repositories;

use App\Models\WordHistory;
use App\Models\DictionaryEntry;
use Illuminate\Support\Facades\Auth;

class DictionaryRepository
{
    public function getWordInfoAndRegisterHistory(string $word)
    {
        // Encontrar a palavra no dicionário
        $entry = DictionaryEntry::where('word', $word)->first();

        if ($entry) {
            $this->registerHistory($entry);
        }

        return $entry;
    }

    public function registerHistory(string $word)
    {

            // Registrar o histórico de acesso
            WordHistory::create([
                'user_id' => Auth::id(),
                'word' => $word,
                'accessed_at' => now(),
        ]);
        
    }
}
