<?php

namespace App\Console\Commands;

use App\Models\DictionaryEntry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportarPalavras extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:importar-palavras';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import words to database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Downloading the JSON file...');

        $url = 'https://raw.githubusercontent.com/dwyl/english-words/master/words_dictionary.json';

        try {
            // Baixar o arquivo JSON
            $response = Http::get($url);

            if ($response->failed()) {
                $this->error('Failed to download the JSON file.');
                return;
            }

            // Converter o JSON em um array
            $words = $response->json();

            $this->info('Inserting words into the database...');

            // Inserir palavras no banco de dados
            $batchSize = 1000; // Inserção em lotes para eficiência
            $batch = [];

            foreach ($words as $word => $value) {
                $batch[] = ['word' => $word];

                if (count($batch) === $batchSize) {
                    DictionaryEntry::insert($batch); // Inserção em lote
                    $batch = [];
                }
            }

            // Inserir o restante das palavras
            if (!empty($batch)) {
                DictionaryEntry::insert($batch);
            }

            $this->info('Words imported successfully!');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
