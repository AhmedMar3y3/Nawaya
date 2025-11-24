<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\BaseRequest;

class BulkActionRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'action'     => ['required', 'in:delete,activate,deactivate'],
            'user_ids'   => ['required', 'array', 'min:1'],
            'user_ids.*' => ['required', 'integer', 'exists:users,id'],
        ];
    }

}
