<?php

namespace App\Http\Requests;

use App\Models\Barang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BarangUpdateRequest extends FormRequest
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

        $barang = Barang::find(request()->id);
        return [
            'kode_barang'        => ['required','string','min:3','max:250', Rule::unique('barang')->where(fn ($query) => $query->where('gudang_id', request()->gudang_id))->ignore(request()->route('id'))],
            'nama_barang'        => ['required','string','min:3','max:250'],
            'gudang_id'          => ['required','integer'],
            'kategori_id'        => ['required','integer'],
            'satuan_id'          => ['required','integer'],
            'tanggal_kadaluarsa' => ['required','date'],
            'harga_beli'         => ['required','numeric','max:9999999999999999.99'],
            'harga_jual'         => ['required','numeric','max:9999999999999999.99'],
        ];
    }

    public function attributes()
    {
        return [
            'kode_barang' => 'kode barang',
            'nama_barang' => 'nama barang',
            'kategori_id' => 'kategori',
            'jumlah'      => 'jumlah',
            'harga_beli'  => 'harga beli',
            'harga_jual'  => 'harga jual',
        ];
    }
}
