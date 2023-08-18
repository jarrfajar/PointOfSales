class Service {
    /**
     * Inisialisasi Select2.
     * @param {string} options.id - ID elemen select yang akan diinisialisasi.
     * @param {string} options.uri - URI atau alamat URL untuk permintaan Ajax.
     * @param {function} options.item - Fungsi untuk memetakan item dari respons JSON ke format Select2.
     * @param {function|null} options.params - Fungsi opsional untuk mengkonfigurasi data yang akan dikirim dalam permintaan Ajax.
     */
    static initSelect2(options) {
        const { id, uri, item, placeholder = null, params = null } = options;

        const param = function (data) {
            return {
                search: data.term,
            }
        }
        
        $(`#${id}`).select2({
            placeholder: placeholder || "--Pilih--",
            allowClear: true,
            ajax: {
                url: `/${uri}`,
                dataType: "json",
                delay: 750,
                data: params || param,
                processResults: function (data) {
                    return {
                        results: data.data.map(item),
                    };
                },
                cache: true,
            },
        });
    }

    static select2Selected(options) {
        const { id, dataOption } = options;

        var select2Element = $(`#${id}`);
        var optionData = dataOption
    
        var newOption = new Option(optionData.text, optionData.id);
        select2Element.empty().append(newOption).trigger('change');
    }

    /**
     * id inputan harus sama dengan field table
     * select memakai attr data-select dan harus sama dengan field table
     * @param {*} err 
     * @param {string} idTable 
     * @returns 
     */
    static handelErrorFetch(err, idTable = null) {
        console.log(err);
        if (err?.response?.status === 422) {
            let error = err.response.data;

            $('.is-invalid').removeClass('is-invalid')
            $('.invalid-feedback').html('')

            swal(`${err.response.status}`, `${error.message}`, 'error')

            for (const key in error.errors) {
                $('#form-penerimaan-barang').find('.select').not('table .select').each(function() {
                    $(`#${key}`).addClass('is-invalid');
                    $(`#${key}`).parent().find('.invalid-feedback').html(`${error.errors[key][0]}`)
                })

                // tampilkan error message di table form
                if (idTable !== null) {
                    $(`#${idTable} tbody tr`).each(function(element) {
                        if (key.includes(element)) {
                            let keyName = key.split(`${element}.`)[1];

                            let idElement = $(this).find(`[data-select="${keyName}"]`)
                            idElement.addClass("is-invalid")
                            idElement.parent().find('.invalid-feedback').html(`${error.errors[key][0]}`)
                            
                            $(this).find(`[data-name="${keyName}"]`).addClass("is-invalid");
                            $(this).find(`[data-name="${keyName}"]`).next().html(`${error.errors[key][0]}`);
                        }
                    })
                }
            }
            return
        }

        swal(`${err.response.status}`, `${err.response.statusText}`, 'error')
    }

    static showLoading() {
        const loadingElement = document.getElementById('loading');
        loadingElement.style.display = 'block';
    }

    static hideLoading() {
        const loadingElement = document.getElementById('loading');
        loadingElement.style.display = 'none';
      }

    static resetForm(formId, resetTable, emptySelect2 = true) {
        $(`#${formId}`).trigger("reset")
        $('.is-invalid').removeClass('is-invalid')
        $('.invalid').html('')
        $('textarea').val('')
        
        if (emptySelect2) {
            $('select').empty()
        }

        if (resetTable) {
            $(`#${resetTable} tbody`).empty();
        }
    }

   static parseCurrency(price) {
        return parseFloat(price).toLocaleString('id-ID')
    }

    static parseCurrencyToFloat(currency) {
        const numberString = currency.replace(/\./g, '');
        return parseFloat(numberString.replace(',', '.'));
    }
    
}