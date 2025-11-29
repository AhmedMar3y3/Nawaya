<?php

namespace App\Http\Requests\API\Workshop;

use App\Http\Requests\BaseRequest;
use App\Enums\Workshop\WorkshopType;

class WorkshopFilterRequest extends BaseRequest
{
    public function rules(): array
    {
        $workshopTypes = array_map(fn($case) => $case->value, WorkshopType::cases());
        
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'in:' . implode(',', $workshopTypes)],
        ];
    }
}


