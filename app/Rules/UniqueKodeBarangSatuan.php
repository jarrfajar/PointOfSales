<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueKodeBarangSatuan implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Inisialisasi array untuk menyimpan kombinasi kode_barang dan satuan_id yang telah ditemukan
        $foundCombinations = [];

        // Loop setiap elemen dalam array "barang" untuk memeriksa kombinasi kode_barang dan satuan_id
        foreach ($value as $barang) {
            $combination = $barang['kode_barang'] . '_' . $barang['satuan_id'];

            // Jika kombinasi kode_barang dan satuan_id sudah ada sebelumnya, validasi gagal
            if (in_array($combination, $foundCombinations)) {
                return false;
            }

            // Tambahkan kombinasi kode_barang dan satuan_id ke dalam array foundCombinations
            $foundCombinations[] = $combination;
        }

        // Validasi berhasil jika tidak ada duplikasi kombinasi kode_barang dan satuan_id
        return true;
    }

    public function message()
    {
        return 'nama barang dan satuan tidak boleh sama.';
    }
}
