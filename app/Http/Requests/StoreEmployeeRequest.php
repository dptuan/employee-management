<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'employees' => [
               'required',
               'array',
               function ($attribute, $values, $fail) {
                    $values = collect($values);
                    foreach ($values as $value) {
                        if (empty($value['name']) || empty($value['login'])) {
                            $fail($attribute . ' field is invalid.');
                        }

                        if (!empty($value['login']) && $values->where('login', $value['login'])->count() > 1) {
                            $fail($attribute . ' field is invalid.');
                        }

                        if (!empty($value['login']) && Employee::where('login', $value['login'])->exists()) {
                            $fail($attribute . ' is already exists.');
                        }
                    }
               }
           ]
        ];
    }
}
