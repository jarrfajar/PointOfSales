<?php

namespace App\Http\Requests;

use App\Rules\UniqueKodeBarangSatuan;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderRequest extends FormRequest
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
        return [
            'gudang_id'            => ['required','integer'],
            'tanggal'              => ['required','date'],
            'supplier_id'          => ['required','integer'],
            'total_harga'          => ['required','numeric','max:9999999999999999.99'],
            'deskripsi'            => ['required','string','max:250'],
            'barang'               => ['required','array', new UniqueKodeBarangSatuan('kode_barang','satuan_id')],
            'barang.*.kode_barang' => ['required','string'],
            'barang.*.jumlah'      => ['required','integer'],
            'barang.*.satuan_id'   => ['required','integer'],
            'barang.*.harga'       => ['required','numeric','max:9999999999999999.99'],
            'barang.*.total_harga' => ['required','numeric','max:9999999999999999.99'],
        ];
    }
}
