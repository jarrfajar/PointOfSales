<?php

namespace App\Http\Requests;

use App\Models\Branch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchUpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $branch = Branch::find(request()->id);
        return [
            'nama'          => ['required', 'string', 'min:3', 'max:250', Rule::unique('cabang', 'nama')->ignore($branch->id)],
            'alamat'        => ['required', 'string', 'min:3', 'max:250'],
            'kepala_cabang' => ['required', 'string', 'min:3', 'max:250'],
        ];
    }
}
