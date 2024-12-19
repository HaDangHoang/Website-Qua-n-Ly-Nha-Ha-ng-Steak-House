<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRquest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_name' => 'required',
            'user_phone' => ['required', 'regex:/^0[0-9]{9}$/'],
            'guest_count' => 'required|integer|min:1',
        ];
    }
    public function messages(): array
    {
        return [
            'user_name.required' => 'Tên khách hàng không được để trống!',
            'user_phone.required' => 'Số điện thoại không được để trống!',
            'user_phone.regex' => 'Số điện thoại phải là số và có 10 chữ số, bắt đầu bằng số 0!',
            'guest_count.required' => 'Số lượng khách không được để trống!',
            'guest_count.integer' => 'Số lượng khách phải là số nguyên!',
            'guest_count.min' => 'Số lượng khách phải lớn hơn hoặc bằng 1!',
        ];
    }
}
