<?php

namespace Modules\Voucher\Http\Requests;

use App\AppHelpers\Helper;
use Illuminate\Foundation\Http\FormRequest;

class ServiceVoucherRequest extends FormRequest{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        $method = Helper::segment(2);
        switch($method){
            default:
                return [
                    "code"       => "required|validate_unique:service_vouchers",
                    "price"      => "required",
                    "service_id" => "required|check_exist:services,id",
                    "start_at"   => "required",
                ];
            case "update":
                return [
                    "code"       => "required|validate_unique:service_vouchers," . $this->id,
                    "price"      => "required",
                    "service_id" => "required|check_exist:services,id",
                    "start_at"   => "required",
                ];
        }
    }

    public function messages(){
        return [
            'required'        => ':attribute' . trans(' can not be empty.'),
            'validate_unique' => ':attribute' . trans(' was exist.'),
            'check_exist'     => ':attribute' . trans(' does not exist.'),
        ];
    }

    public function attributes(){
        return [
            "code"       => trans("Code"),
            "start_at"   => trans("Start day"),
            "price"      => trans("Price"),
            "service_id" => trans("Service"),
        ];
    }
}
