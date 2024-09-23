<?php

namespace App\Services\SpecialServices;

use Illuminate\Http\Request;

class SortingService
{
    public function apply($query, Request $request, $allowedFields = [], string $defaultSortBy = 'id', $defaultSortDirection = 'asc'){
        $sortBy = $request->get('SortBy', $defaultSortBy);
        if (!in_array($sortBy, $allowedFields)) {
            $sortBy = $defaultSortBy;
        }
        $sortDirection = in_array($request->get('SortDirection', $defaultSortDirection), ['asc', 'desc']) ? $request->get('SortDirection', $defaultSortDirection) : $defaultSortDirection;
        return $query->orderBy($sortBy, $sortDirection);
    }
}