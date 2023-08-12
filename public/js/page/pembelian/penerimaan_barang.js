let unitRow = 1

$(document).ready(function () {
    getIndex()
});

function getIndex() {
    Service.showLoading()
    
    axios.get('/penerimaan-barang')
    .then((result) => {
        const response = result.data.data
        drawTable(response)
    }).catch((err) => handelErrorFetch(err))
    .finally(() => Service.hideLoading())
}


function drawTable(data) {
    $("#table-penerimaan-barang").DataTable({
        autoWidth: false,
        paging: true,
        data: data,
        columns: [
            {
                data: "DT_RowIndex",
            },
            {
                data: "nomor_bapb",
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
                data: "tanggal_terima",
                render: (data) => moment(data).format('YYYY-MM-DD')
            },
            {
                data: "tanggal_tempo",
                render: (data) => moment(data).format('YYYY-MM-DD')
            },
            {
                data: "total_harga",
                render: (data) => parseFloat(data).toLocaleString('id-ID'),
            },
            {
                data: "id",
                render: function (data) {
                    return /*html*/ `
                        <div style="display: flex; justify-content: center; gap: 10px;">
                            <button type="submit" class="btn btn-primary btn-sm btn-action mr-1"data-toggle="tooltip" title="Edit" onclick="showModal(${data})"><i class="fas fa-pencil-alt"></i></button>
                            <button type="submit" class="btn btn-danger btn-sm btn-action mr-1" data-toggle="tooltip" title="Checkout" onclick="deleteBarang(${data})"><i class="fas fa-trash-alt"></i></button>
                        </div>`
                },
            },
        ],
    })
}

function showModal(id = null) {
    $("#modal-penerimaan-barang").modal({
        backdrop: "static",
        keyboard: false,
    })
    
    formReset()

    Service.initSelect2({
        id: 'supplier_id',
        uri: 'supplier-search',
        item: function (item) {
            return {
                id: item.id,
                text: item.nama
            }
        }
    })
    

    if (id == null) {
        $("#modal-title").text("Tambah BAPB");
        $("#btn-form-penerimaan-barang").attr("onclick", `store()`);
        $("#btn-form-penerimaan-barang").text("Simpan");
    } else {
        $("#modal-title").text("Ubah BAPB");
        $("#btn-form-penerimaan-barang").attr("onclick", `update(${id})`);
        $("#btn-form-penerimaan-barang").text("Ubah");
        showPenerimaanBarang(id);
    }
}

function formReset() {
    Service.resetForm('modal-penerimaan-barang', 'table-barang')
    $('#tanggal_po').val('')
    $('#sub_total').val('')
    $('#diskon').val('')
    $('#ppn').val('')
    $('#total_harga').val('')
}

function getPurchaseOrder(id) {
    Service.initSelect2({
        id: 'purchase_order_id',
        uri: `purchase-order/get/${id}`,
        item: function (item) {
            return {
                id: item.id,
                text: `${item.nomor_purchase_order} | ${moment(item.tanggal).format('YYYY-MM-DD')}`
            }
        }
    })
}

$('#purchase_order_id').on('change', function() {
    showPurchaseOrder($(this).val())

    unitRow = 1
    $('#table-barang tbody tr').each((key, row) => $(row).remove())
})

function showPurchaseOrder(id) {
    Service.showLoading();
    axios.get(`/purchase-order/${id}`)
    .then((result) => {
        const response = result.data.data
        
        Service.select2Selected({
            id: 'gudang_id',
            dataOption: {
                id: response.gudang.id,
                text: response.gudang.nama,
            }
        })

        $('#tanggal_po').val(moment(response.tanggal).format('YYYY-MM-DD'))
        $('#deskripsi').val(response.deskripsi)
        $('#sub_total').val(parseFloat(response.total_harga || 0))
        $('#diskon').val(0)
        $('#diskon_hidden').val(0)
        $('#ppn').val(0)
        $('#total_harga').val(parseFloat(response.total_harga || 0))
        
        response.purchase_orders.forEach((value) => addRow(value))

    }).catch((err) => Service.handelErrorFetch(err))
    .finally(() => Service.hideLoading())
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
                <button type="button" class="btn btn-danger btn-sm btn-action mr-1" id="delete_row-${unitRow}"  onClick="deleteRow(this)"><i class="fas fa-trash-alt"></i></button>
            </td>
            <td>
                <select class="form-control select" id="barang_id-${unitRow}" data-select="barang_id"></select>
                <span id="barang_id_err" class="invalid-feedback"></span>
            </td>
            <td>
                <div>
                    <input type="text" class="form-control" id="jumlah-${unitRow}" value="${parseInt(value?.jumlah || 0)}" data-name="jumlah" onchange="countTotalPrice(this)">
                    <span id="jumlah_err" class="invalid-feedback"></span>
                </div>
            </td>
            <td>
                <select class="form-control select" id="satuan-${unitRow}" data-select="satuan_id"></select>
                <span id="satuan_err" class="invalid-feedback"></span>
            </td>
            <td>
                <div>
                    <input type="number" class="form-control" id="harga-${unitRow}" value="${parseFloat(value?.harga || 0)}" data-name="harga" onchange="countTotalPrice(this)">
                    <span id="harga_err" class="invalid-feedback"></span>
                </div>
            </td>
            <td>
                <div>
                    <input type="text" class="form-control" id="diskon_persen-${unitRow}" value="${parseInt(value?.diskon_persen || 0)}" data-name="diskon_persen" onchange="countDiskonPersen(this)">
                    <span id="diskon_persen_err" class="invalid-feedback"></span>
                </div>
            </td>
            <td>
                <div>
                    <input type="text" class="form-control" id="diskon_rp-${unitRow}" value="${parseFloat(value?.diskon_rp || 0)}" data-name="diskon_rp" onchange="countDiskonRp(this)">
                    <span id="diskon_rp_err" class="invalid-feedback"></span>
                </div>
            </td>
            <td>
                <label class="custom-switch" style="padding-left: 0">
                    <input type="checkbox" id="tax-${unitRow}" class="custom-switch-input" onchange="countTax(this)" data-name="tax">
                    <input type="hidden" id="tax_hidden-${unitRow}" value="${parseFloat(value?.tax || 0)}" data-name="tax_hidden">
                    <span class="custom-switch-indicator"></span>
                </label>
            </td>
            <td>
                <div>
                    <input type="hidden" class="form-control" id="total_harga_hidden-${unitRow}" value="${parseFloat(value?.total_harga || 0)}">
                    <input type="text" class="form-control" id="total_harga-${unitRow}" value="${parseFloat(value?.total_harga || 0)}" data-name="total_harga" readonly>
                    <span id="total_harga_err" class="invalid-feedback"></span>
                </div>
            </td>
        </tr>
    `)

    if (value) {
        Service.select2Selected({
            id: `barang_id-${unitRow}`,
            dataOption: {
                id: value.barang.id,
                text: value.barang.nama_barang,
            }
        })
        
        Service.select2Selected({
            id: `satuan-${unitRow}`,
            dataOption: {
                id: value.satuan.id,
                text: value.satuan.nama,
            }
        })
    } else {
        Service.initSelect2({
            id:`satuan-${unitRow}`,
            uri: 'satuan-search',
            item: function (item) {
                return {
                    id: item.id,
                    text: item.nama
                }
            }
        })

        let gudang = $('#gudang_id').val()
        Service.initSelect2({
            id:`barang_id-${unitRow}`,
            uri: 'barang-search',
            params: function (data) {
                return {
                    search: data.term,
                    gudang: gudang,
                }
            },
            item: function (item) {
                return {
                    id: item.id,
                    text: item.nama_barang
                }
            },
        })
    }

    unitRow++;
}

function deleteRow(element) {
    let elementId = $(element).attr("id").split("-")[1]
    let harga     = parseFloat($(`#total_harga_hidden-${elementId}`).val())
    let subTotal  = parseFloat($('#sub_total').val())
    
    $('#sub_total').val(subTotal - harga)
    countDiskonWithTax()
    $(element).parent().parent().remove()
}

function countTotalPrice(element) {
    let elementId = $(element).attr("id").split("-")[1]
    let jumlah    = $(`#jumlah-${elementId}`).val()
    let harga     = $(`#harga-${elementId}`).val()

    if (jumlah && harga) {
        let totalHarga = 0

        $(`#total_harga-${elementId}`).val(parseFloat(harga) * parseInt(jumlah))
        $(`#total_harga_hidden-${elementId}`).val(parseFloat(harga) * parseInt(jumlah))
        
        $('#table-barang tbody tr').each(function(key, row) {
            totalHarga += parseFloat($(row).find('[data-name="total_harga"]').val())
        })
        
        $('#sub_total').val(totalHarga)
        countTotal()
    } 
}

function countDiskonPersen(element) {
    let elementId          = $(element).attr("id").split("-")[1];
    let total_harga_hidden = $(`#total_harga_hidden-${elementId}`).val()
    let diskon_persen      = $(`#diskon_persen-${elementId}`).val()
    
    if (diskon_persen > 0) {
        let diskon_harga = total_harga_hidden * (diskon_persen / 100).toFixed(2)

        $(`#diskon_rp-${elementId}`).val(diskon_harga)
        $(`#total_harga-${elementId}`).val(total_harga_hidden - diskon_harga)

        $(`#tax-${elementId}`).trigger('change');
        countDiskonWithTax()
    } else {
        $(`#total_harga-${elementId}`).val(total_harga_hidden)
        $(`#tax-${elementId}`).trigger('change');
        countDiskonWithTax()
    }
}

function countDiskonRp(element) {
    let elementId          = $(element).attr("id").split("-")[1];
    let diskon_rp          = parseFloat($(`#diskon_rp-${elementId}`).val())
    let total_harga_hidden = $(`#total_harga_hidden-${elementId}`).val()

    $(`#diskon_persen-${elementId}`).val(0)

    if (diskon_rp > 0) {
        $(`#total_harga-${elementId}`).val(total_harga_hidden - diskon_rp)

        $(`#tax-${elementId}`).trigger('change');
        countDiskonWithTax()
    } else {
        $(`#total_harga-${elementId}`).val(total_harga_hidden)
        $(`#tax-${elementId}`).trigger('change');
        countDiskonWithTax()
    }
}

function countTax(element) {
    let elementId   = $(element).attr("id").split("-")[1];
    let total_harga = parseFloat($(`#total_harga-${elementId}`).val())
    let ppn         = parseFloat($('#ppn').val())

    if ($(element).is(':checked')) {
        $(element).val(1)
        let tax = (total_harga * 11) / 100

        $(`#tax_hidden-${elementId}`).val(tax)
        $('#ppn').val(ppn + tax)
    } else {
        $(element).val(0)
        let tax_hidden = parseFloat($(`#tax_hidden-${elementId}`).val())
        $('#ppn').val(ppn - tax_hidden)
        $(`#tax_hidden-${elementId}`).val(0)
    }

    countTotal()
}

function countDiskonWithTax() {
    let diskon = 0;
    let tax    = 0;

    $('#table-barang tbody tr').each(function () {
        diskon += parseFloat($(this).children().find('[data-name="diskon_rp"]').val())
        tax    += parseFloat($(this).children().find('[data-name="tax_hidden"]').val())
    })
    
    $('#diskon').val(diskon)
    $('#ppn').val(tax)
    countTotal()
}

function countTotal() {
    let sub_total = parseFloat($('#sub_total').val())
    let diskon    = parseFloat($('#diskon').val())
    let ppn       = parseFloat($('#ppn').val())

    $('#total_harga').val(sub_total - diskon - ppn);
}

function jsonData() {
    let data = [];
    $('#table-barang tbody tr').each(function () {
        let elementId = $(this).find("select").attr("id").split("-")[1];

        if ($(this).find(`#tax-${elementId}`).val() == 'on') {
            $(this).find(`#tax-${elementId}`).val(0)
        }

        var obj = {
            barang_id    : $(this).find("select").val(),
            jumlah       : $(this).find(`#jumlah-${elementId}`).val(),
            satuan_id    : $(this).find(`#satuan-${elementId}`).val(),
            harga        : $(this).find(`#harga-${elementId}`).val(),
            diskon_persen: $(this).find(`#diskon_persen-${elementId}`).val(),
            diskon_rp    : $(this).find(`#diskon_rp-${elementId}`).val(),
            ppn          : $(this).find(`#tax-${elementId}`).val(),
            total_harga  : $(this).find(`#total_harga-${elementId}`).val(),
        }

        data.push(obj)
    })

    let form = new FormData(document.getElementById("form-penerimaan-barang"))
    let jsonData = {
        barang: data,
    };

    form.forEach((value, key) => (jsonData[key] = value))

    return jsonData
}

const initializeDatatable = () => {
    $("#table-penerimaan-barang").DataTable().destroy();
    getIndex();
};

function store() {
    Service.showLoading()
    $('.is-invalid').removeClass('is-invalid')
    $('.invalid').html('')

    axios.post('/penerimaan-barang', jsonData())
    .then((result) => {
        if (result.status === 200 || result.status === 201) {
            $('#modal-penerimaan-barang').modal('hide')
            swal('Berhasil!', 'BAPB berhasil ditambah!', 'success')
            
            initializeDatatable()
        }
    })
    .catch((err) => Service.handelErrorFetch(err, 'table-barang'))
    .finally(() => Service.hideLoading())
}

function showPenerimaanBarang(id) {
    Service.showLoading()
    axios.get(`/penerimaan-barang/${id}`)
    .then((result) => {
        const response = result.data.data

        Service.select2Selected({
            id: 'supplier_id',
            dataOption: {
                id: response.supplier.id,
                text: response.supplier.nama,
            }
        })

        $('#nomor_bapb').val(response.nomor_bapb)
        $('#nomor_faktur').val(response.nomor_faktur)
        $('#tanggal_terima').val(response.tanggal_terima)
        $('#tanggal_tempo').val(response.tanggal_tempo)
        $('#nomor_resi').val(response.nomor_resi)

        Service.select2Selected({
            id: 'purchase_order_id',
            dataOption: {
                id: response.purchase_order.id,
                text: `${response.purchase_order.nomor_purchase_order} | ${moment(response.purchase_order.tanggal).format('YYYY-MM-DD')}`
            }
        })
    })
    .catch((err) => Service.handelErrorFetch(err, ['purchase_order_id','kategori_id','satuan_id','gudang_id','supplier_id']))
    .finally(() => Service.hideLoading())
}

function update(id) {
    Service.showLoading()
    axios.put(`/penerimaan-barang/${id}`, jsonData())
    .then((result) => {
        if (result.status === 200 || result.status === 201) {
            $('#modal-penerimaan-barang').modal('hide')
            swal('Berhasil!', 'BAPB berhasil ditambah!', 'success')
            
            initializeDatatable()
        }
    })
    .catch((err) => Service.handelErrorFetch(err, ['purchase_order_id','kategori_id','satuan_id','gudang_id','supplier_id']))
    .finally(() => Service.hideLoading())
}