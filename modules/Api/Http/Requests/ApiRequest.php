<?php

namespace Modules\Api\Http\Requests;

use App\AppHelpers\Helper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ApiRequest extends FormRequest{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        Helper::apiResponseByLanguage($this);
        return true;
    }

    /**
     * @param Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator){
        $translated_errors = [];
        $errors            = $validator->errors()->toArray();
        foreach($errors as $attr => $error){
            $translated_errors[$attr] = reset($error);
        }

        $response = new Response(['status' => 422, 'error' => 'Failed validation.', 'validate' => $translated_errors]);

        throw new ValidationException($validator, $response);
    }

}
