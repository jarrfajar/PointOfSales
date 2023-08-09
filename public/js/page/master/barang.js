$(document).ready(function () {
    drawTable()
});

function drawTable() {
    $("#table-barang").DataTable({
        autoWidth: false,
        paging: true,
        ajax: {
            url: "/barang",
            dataSrc: "data",
        },
        columns: [
            {
                data: "DT_RowIndex",
            },
            {
                data: "gudang",
                render: (data) => data.nama
            },
            {
                data: "kode_barang",
            },
            {
                data: "nama_barang",
            },
            {
                data: "kategori",
                render: (data) => data.nama
            },
            {
                data: "satuan",
                render: (data) => data.nama
            },
            {
                data: "harga_beli",
                render: (data) => parseFloat(data).toLocaleString('id-ID'),
            },
            {
                data: "harga_jual",
                render: (data) => parseFloat(data).toLocaleString('id-ID'),
            },
            {
                data: "tanggal_kadaluarsa",
            },
            {
                data: "id",
                render: function (data) {
                    return /*html*/ `
                        <div style="display: flex; justify-content: center; gap: 10px;">
                            <button type="submit" class="btn btn-primary btn-sm btn-action mr-1"data-toggle="tooltip" title="Edit" onclick="showAddModal(${data})"><i class="fas fa-pencil-alt"></i></button>
                            <button type="submit" class="btn btn-danger btn-sm btn-action mr-1" data-toggle="tooltip" title="Checkout" onclick="deleteBarang(${data})"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    `;
                },
            },
        ],
    });
}

function showAddModal(id = null) {
    $("#form-barang").trigger("reset")
    $('.is-invalid').removeClass('is-invalid')

    $("#modal-barang").modal({
        backdrop: "static",
        keyboard: false,
    });

    initSelect2('gudang_id', 'gudang-search')
    initSelect2('kategori_id', 'kategori-search')
    initSelect2('satuan_id', 'satuan-search')

    if (id == null) {
        $("#modal-title").text("Tambah Barang");
        $("#btn-form-barang").attr("onclick", `storeBarang()`);
        $("#btn-form-barang").text("Simpan");
    } else {
        $("#modal-title").text("Ubah Barang");
        $("#btn-form-barang").attr("onclick", `updateBarang(${id})`);
        $("#btn-form-barang").text("Ubah");
        showBarang(id);
    }
}

function initSelect2(id, uri) {
    $(`#${id}`).select2({
        placeholder: "--Pilih--",
        allowClear: true,
        ajax: {
            url: `/${uri}`,
            dataType: "json",
            delay: 750,
            data: function (params) {
                return {
                    search: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: data.data.map(function (item) {
                        return {
                            id: item.id,
                            text: item.nama
                        };
                    }),
                };
            },
            cache: true,
        },
    });
}

const initializeDatatable = () => {
    $("#table-barang").DataTable().destroy();
    drawTable();
};

const deleteRow = (element) => $(element).parent().parent().remove();

let jsonData = () => {
    let form = new FormData(document.getElementById('form-barang'))
    let jsonData = {}
    
    form.forEach((value, key) => jsonData[key] = value);

    return jsonData
}

function storeBarang() {
    let data = jsonData()
    
    axios.post('/barang', data)
    .then((result) => {
        if (result.status === 200 || result.status === 201) {
            $('#modal-barang').modal('hide')
            swal('Berhasil!', 'barang berhasil ditambah!', 'success')
            
            initializeDatatable()
        }
    }).catch((err) => {
        if (err.response.status === 422) {
            let error = err.response.data;

            $('.is-invalid').removeClass('is-invalid')

            swal(`${err.response.status}`, `${error.message}`, 'error')
            
            for (const key in error.errors) {
                if (key === 'kategori_id' || key === 'satuan_id' || key === 'gudang_id') {
                    $(`#${key}`).addClass('is-invalid')
                    $(`#${key}_err`).html(`${error.errors[key][0]}`)
                } else {
                    $(`#${key}`).addClass('is-invalid');
                    $(`#${key}`).next().html(`${error.errors[key][0]}`);
                }
            }
            return
        }

        swal(`${err.response.status}`, `${err.response.statusText}`, 'error')
    });
}

function selectedSelect2(data, id) {
    var select2Element = $(`#${id}`);
    var optionData = {
        id: data.id,
        text: data.nama
    }

    var newOption = new Option(optionData.text, optionData.id);
    select2Element.empty().append(newOption).trigger('change');
}

function showBarang(id) {
    $("#form-barang").trigger("reset")
    $('.is-invalid').removeClass('is-invalid')

    $("#modal-show-barang").modal({
        backdrop: "static",
        keyboard: false,
    });

    axios.get(`/barang/${id}`)
    .then((result) => {
        const response = result.data.data
        
        $.each(response, function(key, value) {
            if (key === 'kategori') {
                selectedSelect2(value, 'kategori_id')
            }

            if (key === 'satuan') {
                selectedSelect2(value, 'satuan_id')
            }

            $(`#${key}`).val(value)
        })

    })
    .catch((err) => {
        swal(`${err.response.status}`, `${err.response.statusText}`, "error");
    });
}

function updateBarang(id) {
    let data = jsonData()
    
    axios.put(`/barang/${id}`, data)
    .then((result) => {
        if (result.status === 200 || result.status === 201) {
            $('#modal-barang').modal('hide')
            swal('Berhasil!', 'barang berhasil diubah!', 'success')
            
            initializeDatatable()
        }
    }).catch((err) => {
        if (err.response.status === 422) {
            let error = err.response.data;

            $('.is-invalid').removeClass('is-invalid')

            swal(`${err.response.status}`, `${error.message}`, 'error')
            
            for (const key in error.errors) {
                if (key === 'kategori_id' || key === 'satuan_id' || key === 'gudang_id') {
                    $(`#${key}`).addClass('is-invalid')
                    $(`#${key}_err`).html(`${error.errors[key][0]}`)
                } else {
                    $(`#${key}`).addClass('is-invalid');
                    $(`#${key}`).next().html(`${error.errors[key][0]}`);
                }
            }
            return
        }

        swal(`${err.response.status}`, `${err.response.statusText}`, 'error')
    });
}

function deleteBarang(id) {
    swal({
        title: 'Are you sure?',
        text: 'Once deleted, you will not be able to recover this imaginary file!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            axios.delete(`/barang/${id}`)
            .then(function(response) {
                if (response.status == 200) {
                    $('#modal-barang').modal('hide')
                    swal('Poof! Your imaginary file has been deleted!', {icon: 'success'});
                    initializeDatatable()
                }
            })
            .catch(function(error) {
                swal(`${error.response.status}`, `${error.response.statusText}`, 'error')
            })
            
        } else {
            swal('Your imaginary file is safe!');
        }
    });
}

function removeErrorClass(element) {
    $(element).removeClass("is-invalid");
}

function resetForm() {
    $("#table-add-barang tbody").empty();
    $(".is-invalid").removeClass("is-invalid");
}