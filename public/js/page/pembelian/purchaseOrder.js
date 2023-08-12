let unitRow = 1;

$(document).ready(function () {
    drawTable()
});

function showModal(id = null) {
    $('.is-invalid').removeClass('is-invalid')
    
    $("#modal-purchase-order").modal({
        backdrop: "static",
        keyboard: false,
    });
    resetForm()

    let today = moment().format('YYYY-MM-DD')
    $('#tanggal').val(today)
    $('#total_harga').val(0)

    initSelect2('gudang_id', 'gudang-search')
    initSelect2('supplier_id', 'supplier-search')

    if (id == null) {
        $("#modal-title").text("Tambah Purchase Order");
        $("#btn-form-purchase-order").attr("onclick", `storePurchaseOrder()`);
        $("#btn-form-purchase-order").text("Simpan");
    } else {
        $("#modal-title").text("Ubah Purchase Order");
        $("#btn-form-purchase-order").attr("onclick", `updatePurchaseOrder(${id})`);
        $("#btn-form-purchase-order").text("Ubah");
        showPurchaseOrder(id);
    }
}

function resetForm() {
    $("#deskripsi").val('')
    $("#table-barang tbody").empty();

    $(".is-invalid").removeClass("is-invalid");
    $('.invalid').html('')
    $('select').empty()
}

function changeGudang() {
    $('#total_harga').val(0)
    $('#table-barang tbody tr').each((key, row) => $(row).remove())
}

function addRow(value = null) {
    let gudang = $('#gudang_id').val()
    
    if (gudang == null) {
        swal('Gagal', 'Pilih gudang terlebih dahulu', "error")
        return
    }
    
    $('#table-barang tbody').append(/*html*/`
        <tr>
            <td scope="row">
                <button type="button" class="btn btn-danger btn-sm btn-action mr-1" onClick="deleteRow(this)"><i class="fas fa-trash-alt"></i></button>
            </td>
            <td>
                <select class="form-control" id="kode_barang-${unitRow}" data-name="kode_barang"></select>
                <span id="kode_barang_err" class="invalid-feedback"></span>
            </td>
            <td>
                <div>
                    <input type="text" class="form-control" id="jumlah-${unitRow}" value="${value?.jumlah || 0}" data-name="jumlah" onchange="countTotalPrice(this)">
                    <span id="jumlah_err" class="invalid-feedback"></span>
                </div>
            </td>
            <td>
                <select class="form-control" id="satuan-${unitRow}" data-name="satuan"></select>
                <span id="satuan_err" class="invalid-feedback"></span>
            </td>
            <td>
                <div>
                    <input type="number" class="form-control" id="harga-${unitRow}" value="${value?.harga || 0}" data-name="harga" onchange="countTotalPrice(this)">
                    <span id="harga_err" class="invalid-feedback"></span>
                </div>
            </td>
            <td>
                <div>
                    <input type="text" class="form-control" id="total_harga-${unitRow}" value="${value?.total_harga || 0}" data-name="total_harga" readonly>
                    <span id="total_harga_err" class="invalid-feedback"></span>
                </div>
            </td>
        </tr>
    `)

    

    if (value) {
        select2init(`kode_barang-${unitRow}`, {
            id: value.barang.id,
            text: value.barang.nama_barang,
        });

        select2init(`satuan-${unitRow}`, {
            id: value.satuan.id,
            text: value.satuan.nama,
        });
    } else {
        initSelect2(`satuan-${unitRow}`, 'satuan-search')
        initBarangSelect2(`kode_barang-${unitRow}`)
    }

    unitRow++;
}

function countTotalPrice(element) {
    let elementId   = $(element).attr("id").split("-")[1];
    let jumlah      = $(`#jumlah-${elementId}`).val()
    let harga       = $(`#harga-${elementId}`).val()

    if (jumlah && harga) {
        let totalHarga = 0

        $(`#total_harga-${elementId}`).val(parseInt(jumlah) * parseFloat(harga))
        
        $('#table-barang tbody tr').each(function(key, row) {
            totalHarga += parseFloat($(row).find('[data-name="total_harga"]').val())
        })
        
        $('#total_harga').val(totalHarga)
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
                        }
                    }),
                };
            },
            cache: true,
        },
    })
}

function initBarangSelect2(id) {
    let gudang = $('#gudang_id').val()

    $(`#${id}`).select2({
        placeholder: "--Pilih--",
        allowClear: true,
        ajax: {
            url: '/barang-search',
            dataType: "json",
            delay: 750,
            data: function (params) {
                return {
                    search: params.term,
                    gudang: gudang,
                };
            },
            processResults: function (data) {
                return {
                    results: data.data.map(function (item) {
                        return {
                            id: item.id,
                            text: item.nama_barang
                        }
                    }),
                };
            },
            cache: true,
        },
    })
}

function removeErrorClass(element) {
    $(element).removeClass("is-invalid");
}

/**
 * Delete row and calculate total price
 */
function deleteRow(element) {
    let subTotal   = parseFloat($(element).parent().parent().find('[data-name="total_harga"]').val())
    let totalHarga = parseFloat($('#total_harga').val())
    
    if (!isNaN(subTotal)) {
       totalHarga -= subTotal
       $('#total_harga').val(totalHarga)
    }

    $(element).parent().parent().remove()
};

const initializeDatatable = () => {
    $("#table-purchase-order").DataTable().destroy();
    drawTable();
}

function drawTable() {
    $("#table-purchase-order").DataTable({
        autoWidth: false,
        paging: true,
        ajax: {
            url: "/purchase-order",
            dataSrc: "data",
        },
        columns: [
            {
                data: "DT_RowIndex",
            },
            {
                data: "nomor_purchase_order",
            },
            {
                data: "tanggal",
                render: (data) => moment(data).format('YYYY-MM-DD')
            },
            {
                data: "gudang",
                render: (data) => data.nama
            },
            {
                data: "supplier",
                render: (data) => data.nama
            },
            {
                data: "deskripsi"
            },
            {
                data: "total_harga",
                render: (data) => parseFloat(data).toLocaleString('id-ID'),
            },
            {
                data: "status",
                render: function(status, type, data) {
                    if (data.bapb === 1) {
                        return /*html*/`<span class="badge badge-primary">selesai</span>`
                    } else {
                        if (status === 1) {
                            return /*html*/`<span class="badge badge-success">Approved</span>`
                        }
    
                        if (status === 2) {
                            return /*html*/`<span class="badge badge-danger">Rejected</span>`
                        } else {
                            return /*html*/`<span class="badge badge-warning">Waiting</span>`
                        }
                    }
                }
            },
            {
                data: "id",
                render: function (id, type, data) {
                    if (data.status !== 0) {
                        return /*html*/ `<button class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle" type="button" disabled>Aksi</button>`;
                    } else {
                        return /*html*/ `
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aksi</button>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right px-2" style="justify-content: center; gap: 10px;">
                                <a class="dropdown-item" href="#" onclick="approved(${id})">Approved</a>
                                <a class="dropdown-item" href="#" onclick="rejected(${id})">Rejected</a>
                                <a class="dropdown-item" href="#" onclick="showModal(${id})">Edit</a>
                                <a class="dropdown-item" href="#" onclick="deletePurchaseOrder(${id})">Hapus</a>
                            </div>
                        </div>`
                    }
                    
                },
            },
        ],
    })
}

function approved(id) {
    swal({
        title: 'Approved?',
        text: 'Once deleted, you will not be able to recover this imaginary file!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            axios.put(`/purchase-order/approve/${id}`)
            .then(function(response) {
                if (response.status == 200) {
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
    })
}

function rejected(id) {
    swal({
        title: 'Rejected?',
        text: 'Once deleted, you will not be able to recover this imaginary file!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            axios.put(`/purchase-order/reject/${id}`)
            .then(function(response) {
                if (response.status == 200) {
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
    })
}

function jsonData() {
    let data = [];
    $('#table-barang tbody tr').each(function () {
        let elementId = $(this).find("select").attr("id").split("-")[1];
        var obj = {
            barang_id: $(this).find("select").val(),
            jumlah: $(this).find(`#jumlah-${elementId}`).val(),
            satuan_id: $(this).find(`#satuan-${elementId}`).val(),
            harga: $(this).find(`#harga-${elementId}`).val(),
            total_harga: $(this).find(`#total_harga-${elementId}`).val(),
        }

        data.push(obj)
    })

    let form = new FormData(document.getElementById("form-purchase-order"))
    let jsonData = {
        barang: data,
    };

    form.forEach((value, key) => (jsonData[key] = value))

    return jsonData
}

function storePurchaseOrder() {
    axios.post('/purchase-order', jsonData())
    .then((result) => {
        if (result.status === 200 || result.status === 201) {
            $('#modal-purchase-order').modal('hide')
            swal('Berhasil!', 'purchase order berhasil ditambah!', 'success')
            
            initializeDatatable()
        }
    }).catch((err) => {
        if (err.response.status === 422) {
            let error = err.response.data;

            $('.is-invalid').removeClass('is-invalid')

            swal(`${err.response.status}`, `${error.message}`, 'error')
            
            for (const key in error.errors) {
                if (key === 'kategori_id' || key === 'satuan_id' || key === 'gudang_id' || key === 'supplier_id') {
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
    })
}

function updatePurchaseOrder(id) {
    axios.put(`/purchase-order/${id}`, jsonData())
    .then((result) => {
        if (result.status === 200 || result.status === 201) {
            $('#modal-purchase-order').modal('hide')
            swal('Berhasil!', 'purchase order berhasil diubah!', 'success')
            
            initializeDatatable()
        }
    }).catch((err) => {
        if (err.response.status === 422) {
            let error = err.response.data;

            $('.is-invalid').removeClass('is-invalid')

            swal(`${err.response.status}`, `${error.message}`, 'error')
            
            for (const key in error.errors) {
                if (key === 'kategori_id' || key === 'satuan_id' || key === 'gudang_id' || key === 'supplier_id') {
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
    })
}

function select2init(id, data) {
    let select2Element = $(`#${id}`);
    let option = data;

    var newOption = new Option(option.text, option.id);
    select2Element.empty().append(newOption).trigger("change");
}

function showPurchaseOrder(id) {
    axios.get(`/purchase-order/${id}`)
    .then((result) => {
        let response = result.data.data;

        
        $.each(response, function(key, value) {
            if (key === 'gudang_id') {
                select2init("gudang_id", {
                    id: response.gudang.id,
                    text: response.gudang.nama,
                });
            }

            if (key === 'supplier_id') {
                select2init("supplier_id", {
                    id: response.supplier.id,
                    text: response.supplier.nama,
                });
            } 
            
            if (key === 'tanggal') {
                $('tanggal').val(moment(value).format('YYYY-MM-DD'));
            } else {
                $(`#${key}`).val(value)
            }
        })
        
        response.purchase_orders.forEach((value) => addRow(value))
    }).catch((err) => {
        swal(`${err.response.status}`, `${err.response.statusText}`, 'error')
    })
}

function deletePurchaseOrder(id) {
    swal({
        title: 'Delete?',
        text: 'Once deleted, you will not be able to recover this imaginary file!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            axios.delete(`/purchase-order/${id}`)
            .then(function(result) {
                if (result.status === 200 || result.status === 201) {
                    $('#modal-purchase-order').modal('hide')
                    swal('Berhasil!', 'purchase order berhasil diubah!', 'success')
                    
                    initializeDatatable()
                }
            })
            .catch(function(error) {
                swal(`${error.response.status}`, `${error.response.statusText}`, 'error')
            })
            
        } else {
            swal('Your imaginary file is safe!');
        }
    })
}