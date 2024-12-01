<?php

namespace App\Services;

use App\Repositories\DictionaryRepository;

class DictionaryService
{
    protected $repository;

    public function __construct(DictionaryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getWordInfoAndRegisterHistory(string $word)
    {
        return $this->repository->getWordInfoAndRegisterHistory($word);
    }

    public function registerHistory(string $word)
    {
        return $this->repository->registerHistory($word);
    }
}
