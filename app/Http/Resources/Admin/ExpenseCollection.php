<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExpenseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'expenses' => ExpenseResource::collection($this->collection),
            'total_count' => $this->collection->count(),
            'total_amount' => $this->collection->sum('amount'),
        ];
    }
}

