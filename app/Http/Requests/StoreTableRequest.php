<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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

            // Số bàn (giới hạn giá trị từ 1 đến 100 và phải là duy nhất trong khu vực)
            'table_number' => 'required|integer|min:1|max:100|unique:tables,table_number,NULL,id,area,' . $this->input('area'),

            // Trạng thái bàn (chỉ chấp nhận các trạng thái hợp lệ)
            'status' => 'required|in:Available,Reserved,Occupied',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Thông báo lỗi cho trường 'area'
            'area.required' => 'Khu vực là bắt buộc.',
            'area.string' => 'Khu vực phải là chuỗi ký tự.',
            'area.max' => 'Khu vực không được vượt quá 255 ký tự.',

            // Thông báo lỗi cho trường 'table_number'
            'table_number.required' => 'Số bàn là bắt buộc.',
            'table_number.integer' => 'Số bàn phải là một số nguyên.',
            'table_number.min' => 'Số bàn phải lớn hơn hoặc bằng 1.',
            'table_number.max' => 'Số bàn không được lớn hơn 100.',
            'table_number.unique' => 'Số bàn đã tồn tại trong khu vực này. Vui lòng chọn số khác.',

            // Thông báo lỗi cho trường 'status'
            'status.required' => 'Trạng thái bàn là bắt buộc.',
            'status.in' => 'Trạng thái bàn không hợp lệ. Chỉ chấp nhận các giá trị: Có sẵn, Đã đặt trước, Đang sử dụng.',
        ];
    }
}
