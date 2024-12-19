<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cho phép tất cả người dùng gửi yêu cầu này
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Khu vực bàn
            'area' => 'required|string|max:255',

            // Số bàn phải tồn tại trong bảng `tables` và phải kiểm tra sự duy nhất trong khu vực
            // Ngoại trừ bàn hiện tại đang được chỉnh sửa
            'table_number' => [
                'required',
                'integer',
                'min:1',
                'max:100',
                function ($attribute, $value, $fail) {
                    $existingTable = \App\Models\Table::where('area', $this->input('area'))
                        ->where('table_number', $value)
                        ->where('id', '!=', $this->route('id'))  // Loại trừ bàn đang được chỉnh sửa
                        ->exists();

                    if ($existingTable) {
                        $fail('Số bàn này đã tồn tại trong khu vực. Vui lòng chọn số khác.');
                    }
                }
            ],

            // Trạng thái bàn (chỉ chấp nhận các trạng thái hợp lệ)
            'status' => 'required|in:Available,Reserved,Occupied',
        ];
    }
}
