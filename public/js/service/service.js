class Service {
    /**
     * Inisialisasi Select2.
     * @param {string} options.id - ID elemen select yang akan diinisialisasi.
     * @param {string} options.uri - URI atau alamat URL untuk permintaan Ajax.
     * @param {function} options.item - Fungsi untuk memetakan item dari respons JSON ke format Select2.
     * @param {function|null} options.params - Fungsi opsional untuk mengkonfigurasi data yang akan dikirim dalam permintaan Ajax.
     */
    static initSelect2(options) {
        const { id, uri, item, params = null } = options;

        const param = function (data) {
            return {
                search: data.term,
            }
        }
        
        $(`#${id}`).select2({
            placeholder: "--Pilih--",
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

    static handelErrorFetch(err, idSelect2 = null) {
        if (err.response.status === 422) {
            let error = err.response.data;

            $('.is-invalid').removeClass('is-invalid')

            swal(`${err.response.status}`, `${error.message}`, 'error')
            
            for (const key in error.errors) {
                if (idSelect2 != null) {
                    if (idSelect2.includes(key)) {
                        idSelect2.forEach(element => {
                            if (key === element) {
                                $(`#${key}`).addClass('is-invalid')
                                $(`#${key}_err`).html(`${error.errors[key][0]}`)
                            }
                        })
                    } else {
                        $(`#${key}`).addClass('is-invalid');
                        $(`#${key}`).next().html(`${error.errors[key][0]}`)
                    }
                } else {
                    $(`#${key}`).addClass('is-invalid');
                    $(`#${key}`).next().html(`${error.errors[key][0]}`)
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
        
        if (emptySelect2) {
            $('select').empty()
        }

        if (resetTable) {
            $(`#${resetTable} tbody`).empty();
        }
    }
}