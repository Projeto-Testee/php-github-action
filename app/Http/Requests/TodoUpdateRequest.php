<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\DTO\TodoData;
use Illuminate\Foundation\Http\FormRequest;


class TodoUpdateRequest extends FormRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'title'       => 'required',
            'description' => 'required',
            'status'      => 'required'
        ];
    }

    /**
     * @return TodoData
     */
    public function getDto(): TodoData
    {
        return  (new TodoData)->set($this->request->all());
    }

}
