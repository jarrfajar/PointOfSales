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

const formReset = () => Service.resetForm('form-barang')

function showAddModal(id = null) {
    formReset()

    $("#modal-barang").modal({
        backdrop: "static",
        keyboard: false,
    })

    Service.initSelect2({
        id: 'gudang_id',
        uri: 'gudang-search',
        item: function (item) {
            return {
                id: item.id,
                text: item.nama
            }
        }
    })

    Service.initSelect2({
        id: 'satuan_id',
        uri: 'satuan-search',
        item: function (item) {
            return {
                id: item.id,
                text: item.nama
            }
        }
    })

    Service.initSelect2({
        id: 'kategori_id',
        uri: 'kategori-search',
        item: function (item) {
            return {
                id: item.id,
                text: item.nama
            }
        }
    })

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

function showBarang(id) {
    formReset()

    $("#modal-show-barang").modal({
        backdrop: "static",
        keyboard: false,
    });

    axios.get(`/barang/${id}`)
    .then((result) => {
        const response = result.data.data
        
        $.each(response, function(key, value) {
            if (key === 'kategori') {
                Service.select2Selected({
                    id: 'kategori_id',
                    dataOption: {
                        id: value.id,
                        text: value.nama
                    }
                })
            }

            if (key === 'satuan') {
                Service.select2Selected({
                    id: 'satuan_id',
                    dataOption: {
                        id: value.id,
                        text: value.nama
                    }
                })
            }

            if (key === 'gudang') {
                Service.select2Selected({
                    id: 'gudang_id',
                    dataOption: {
                        id: value.id,
                        text: value.nama
                    }
                })
            }

            $(`#${key}`).val(value)
        })

    })
    .catch((err) => {
        swal(`${err.response.status}`, `${err.response.statusText}`, "error");
        Service.handelErrorFetch(err)
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
        Service.handelErrorFetch(err, ['kategori_id','satuan_id','gudang_id'])
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