<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

class PaginationService
{
    /**
     * Paginação com cursor.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $limit
     * @param string|null $cursor
     * @param string $cursorField
     * @return array
     */
    public function paginateWithCursor($query, int $limit, ?string $cursor = null, string $cursorField = 'id', ?int $totalDocs = null): array
    {
        // Se houver um cursor, aplica o filtro baseado no field.
        if ($cursor) {
            $decodedCursor = json_decode(base64_decode($cursor), true);
            $query->where($cursorField, '>', $decodedCursor[$cursorField]);
        }

        // Executa a consulta e limita a quantidade de registros.
        $entries = $query->orderBy('id')->take($limit + 1)->get();

        // Se houver mais resultados que o limite,  definir se há próxima página.
        $results = $entries->slice(0, $limit);
        $hasNext = $entries->count() > $limit;

        // Cria o próximo cursor, se necessário.
        $nextCursor = $hasNext ? base64_encode(json_encode([$cursorField => $results->last()->$cursorField])) : null;

        return [
            'results' => $results->pluck('word')->toArray(),
            'totalDocs' => $totalDocs,
            'next' => $nextCursor,
            'hasNext' => $hasNext,
            'hasPrev' => $cursor !== null,
        ];
    }
}
