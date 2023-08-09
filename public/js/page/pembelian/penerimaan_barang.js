let unitRow = 1

function showModal(id = null) {
    $('.is-invalid').removeClass('is-invalid')
    
    $("#modal-penerimaan-barang").modal({
        backdrop: "static",
        keyboard: false,
    })

    resetForm()

    initSelect2('supplier_id','supplier-search')

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
    // 
}

function getPurchaseOrder(id) {
    initSelect2Po('nomor_po',`purchase-order/get/${id}`)
}

$('#nomor_po').on('change', function() {
    showPurchaseOrder($(this).val())

    unitRow = 1
    $('#table-barang tbody tr').each((key, row) => $(row).remove())
})

function showPurchaseOrder(id) {
    axios.get(`/purchase-order/${id}`)
    .then((result) => {
        const response = result.data.data
        
        select2Selected("gudang_id", {
            id: response.gudang.id,
            text: response.gudang.nama,
        })

        $('#tanggal').val(moment(response.tanggal).format('YYYY-MM-DD'))
        $('#deskripsi').val(response.deskripsi)
        $('#sub_total').val(parseFloat(response.total_harga || 0))
        $('#diskon').val(0)
        $('#diskon_hidden').val(0)
        $('#ppn').val(0)
        $('#total_harga').val(parseFloat(response.total_harga || 0))
        
        response.purchase_orders.forEach((value) => addRow(value))

    }).catch((err) => {
        swal(`${err.response.status}`, `${err.response.statusText}`, 'error')
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

function initSelect2Po(id, uri) {
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
                            text: `${item.nomor_purchase_order} | ${moment(item.tanggal).format('YYYY-MM-DD')}`
                        }
                    }),
                }
            },
            cache: true,
        },
    })
}

function select2Selected(id, data) {
    let select2Element = $(`#${id}`);
    let option = data;

    var newOption = new Option(option.text, option.id);
    select2Element.empty().append(newOption).trigger("change");
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
                    <input type="text" class="form-control" id="jumlah-${unitRow}" value="${parseInt(value?.jumlah || 0)}" data-name="jumlah" onchange="countTotalPrice(this)">
                    <span id="jumlah_err" class="invalid-feedback"></span>
                </div>
            </td>
            <td>
                <select class="form-control" id="satuan-${unitRow}" data-name="satuan"></select>
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
                    <input type="checkbox" name="custom-switch-checkbox" id="tax-${unitRow}" class="custom-switch-input" onchange="countTax(this)">
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
        select2Selected(`kode_barang-${unitRow}`, {
            id: value.barang.id,
            text: value.barang.nama_barang,
        });

        select2Selected(`satuan-${unitRow}`, {
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
        let tax = (total_harga * 11) / 100

        $(`#tax_hidden-${elementId}`).val(tax)
        $('#ppn').val(ppn + tax)
    } else {
        let tax_hidden = parseFloat($(`#tax_hidden-${elementId}`).val())
        $('#ppn').val(ppn - tax_hidden)
        $(`#tax_hidden-${elementId}`).val(0)
    }

    countTotal()
}

function countDiskonWithTax() {
    let diskon    = 0;
    let tax       = 0;

    $('#table-barang tbody tr').each(function () {
        diskon += parseFloat($(this).children().find('[data-name="diskon_rp"]').val())
        tax   += parseFloat($(this).children().find('[data-name="tax_hidden"]').val())
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