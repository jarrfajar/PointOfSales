<?php

namespace App\Http\Requests;

use App\Rules\UniqueKodeBarangSatuan;
use Illuminate\Foundation\Http\FormRequest;

class PenerimaanBarangRequest extends FormRequest
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
            'purchase_order_id'      => ['required','integer'],
            'nomor_resi'             => ['required','string'],
            'gudang_id'              => ['required','integer'],
            'supplier_id'            => ['required','integer'],
            'tanggal_po'             => ['required','string'],
            'tanggal_terima'         => ['required','string'],
            'tanggal_tempo'          => ['required','string'],
            'deskripsi'              => ['required','string'],
            'total_harga'            => ['required','numeric'],
            'sub_total'              => ['required','numeric'],
            'diskon'                 => ['required','numeric'],
            'ppn'                    => ['required','numeric'],
            // 'barang'                 => ['required','array','min:1', new UniqueKodeBarangSatuan('barang_id','satuan_id')],
            'barang'                 => ['required','array','min:1'],
            'barang.*.barang_id'     => ['required','integer','distinct'],
            'barang.*.jumlah'        => ['required','integer','min:1'],
            'barang.*.satuan_id'     => ['required','integer'],
            'barang.*.harga'         => ['required','integer'],
            'barang.*.diskon_persen' => ['required','integer'],
            'barang.*.diskon_rp'     => ['required','numeric'],
            'barang.*.ppn'           => ['required','integer'],
            'barang.*.total_harga'   => ['required','integer'],
        ];
    }
}
