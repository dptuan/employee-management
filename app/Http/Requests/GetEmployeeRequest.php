<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetEmployeeRequest extends FormRequest
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
            'limit' => 'nullable|integer',
            'offset' => 'nullable|integer',
            'sort' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (!$this->startsWith($value, '+') &&
                        !$this->startsWith($value, '-')
                    ) {
                        $fail($attribute . ' is invalid format.');
                    }
                    if (!$this->endsWith($value, 'name') &&
                        !$this->endsWith($value, 'login')
                    ) {
                        $fail($attribute . ' is invalid format.');
                    }
                },
            ],
        ];
    }

    private function startsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return substr($haystack, 0, $length) === $needle;
    }

    private function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if (!$length) {
            return true;
        }

        return substr($haystack, -$length) === $needle;
    }
}
