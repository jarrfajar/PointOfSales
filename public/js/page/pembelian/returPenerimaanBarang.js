let unitRow = 1
$(document).ready(function () {
    $('#reset-form-modal').hide()
    getIndex()
})

function getIndex() {
    Service.showLoading()
    
    axios.get('/retur-penerimaan-barang')
    .then((result) => {
        const response = result.data.data
        drawTable(response)
    }).catch((err) => Service.handelErrorFetch(err))
    .finally(() => Service.hideLoading())
}

function drawTable(data) {
    $("#table-retur-bapb").DataTable({
        autoWidth: false,
        paging: true,
        data: data,
        columns: [
            {
                data: "DT_RowIndex",
            },
            {
                data: "penerimaan_barang",
                render: (data) => data?.nomor_bapb
            },
            {
                data: "penerimaan_barang",
                render: (data) => data?.gudang?.nama
            },
            {
                data: "penerimaan_barang",
                render: (data) => data?.supplier?.nama
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
                        <button type="submit" class="btn btn-primary btn-sm btn-action mr-1"data-toggle="tooltip" title="Edit" onclick="showModal(${data})">
                            <i class="fa-regular fa-eye fa-xl"></i>
                        </button>`
                },
            },
        ],
    })
}

const initializeDatatable = () => {
    $("#table-retur-bapb").DataTable().destroy();
    drawTable();
}

$('#supplier_id').on('change', function() {
    Service.initSelect2({
        id: 'nomor_bapb',
        uri: `penerimaan-barang-search/${$(this).val()}`,
        item: function (item) {
            return {
                id: item.id,
                text: item.nomor_bapb
            }
        }
    })
})

$('#nomor_bapb').on('change', function() {
    axios.get(`/penerimaan-barang/retur/${$(this).val()}`)
    .then((result) => {
        const response = result.data.data

        $('#nomor_faktur').val(response.nomor_faktur)
        $('#tanggal_terima').val(response.tanggal_terima)
        $('#nomor_resi').val(response.nomor_resi)

        Service.select2Selected({
            id: "gudang_id",
            dataOption: {
                id: response.gudang.id,
                text: response.gudang.nama,
            },
        })
        response.barangs.forEach(value => addRow(value))

        $("#sub_total").val(parseFloat(response.total_harga || 0));
        $("#diskon").val(parseFloat(response.diskon));
        $("#diskon_hidden").val(parseFloat(response.diskon));
        $("#total_harga").val(parseFloat(response.total_harga || 0));

    })
    .catch((err) => Service.handelErrorFetch(err, "table-barang"))
    .finally(() => Service.hideLoading());
})

function showModal(id = null) {
    $("#modal-retur-bapb").modal({
        backdrop: "static",
        keyboard: false,
    })

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
}


function addRow(value = null) {
    let gudang = $("#gudang_id").val();

    if (gudang == null) {
        swal("Gagal", "Pilih gudang terlebih dahulu", "error");
        return;
    }

    $("#table-barang tbody").append(/*html*/ `
        <tr>
            <td scope="row">
                <button type="button" class="btn btn-danger btn-sm btn-action mr-1 btn-deleteRow" id="delete_row-${unitRow}"  onClick="deleteRow(this)"><i class="fas fa-trash-alt"></i></button>
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
                    <input type="text" id="tax_hidden-${unitRow}" value="${parseFloat(value?.tax || 0)}" data-name="tax_hidden">
                    <span class="custom-switch-indicator"></span>
                </label>
            </td>
            <td>
                <div>
                    <input type="text" class="form-control" id="total_harga_hidden-${unitRow}" value="${parseFloat(value?.total_harga || 0)}">
                    <input type="text" class="form-control" id="total_harga-${unitRow}" value="${parseFloat(value?.total_harga || 0)}" data-name="total_harga" readonly>
                    <span id="total_harga_err" class="invalid-feedback"></span>
                </div>
            </td>
        </tr>
    `);

    if (value.ppn == 1) {
        $(`#tax-${unitRow}`).prop('checked', true).trigger('change')
    }

    if (value) {
        Service.select2Selected({
            id: `barang_id-${unitRow}`,
            dataOption: {
                id: value.barang.id,
                text: value.barang.nama_barang,
            },
        })

        Service.select2Selected({
            id: `satuan-${unitRow}`,
            dataOption: {
                id: value.satuan.id,
                text: value.satuan.nama,
            },
        })
    } else {
        Service.initSelect2({
            id: `satuan-${unitRow}`,
            uri: "satuan-search",
            item: function (item) {
                return {
                    id: item.id,
                    text: item.nama,
                };
            },
        })

        let gudang = $("#gudang_id").val();
        Service.initSelect2({
            id: `barang_id-${unitRow}`,
            uri: "barang-search",
            params: function (data) {
                return {
                    search: data.term,
                    gudang: gudang,
                };
            },
            item: function (item) {
                return {
                    id: item.id,
                    text: item.nama_barang,
                };
            },
        })
    }

    unitRow++;
}


function deleteRow(element) {
    let elementId = $(element).attr("id").split("-")[1];
    let harga = parseFloat($(`#total_harga_hidden-${elementId}`).val());
    let subTotal = parseFloat($("#sub_total").val());

    $("#sub_total").val(subTotal - harga);
    countDiskonWithTax();
    $(element).parent().parent().remove();
}

function countTotalPrice(element) {
    let elementId = $(element).attr("id").split("-")[1];
    let jumlah = $(`#jumlah-${elementId}`).val();
    let harga = $(`#harga-${elementId}`).val();

    if (jumlah && harga) {
        let totalHarga = 0;

        $(`#total_harga-${elementId}`).val(
            parseFloat(harga) * parseInt(jumlah),
        );
        $(`#total_harga_hidden-${elementId}`).val(
            parseFloat(harga) * parseInt(jumlah),
        );

        $("#table-barang tbody tr").each(function (key, row) {
            totalHarga += parseFloat(
                $(row).find('[data-name="total_harga"]').val(),
            );
        });

        $("#sub_total").val(totalHarga);
        countTotal();
    }
}

function countDiskonPersen(element) {
    let elementId = $(element).attr("id").split("-")[1];
    let total_harga_hidden = $(`#total_harga_hidden-${elementId}`).val();
    let diskon_persen = $(`#diskon_persen-${elementId}`).val();

    if (diskon_persen > 0) {
        let diskon_harga =
            total_harga_hidden * (diskon_persen / 100).toFixed(2);

        $(`#diskon_rp-${elementId}`).val(diskon_harga);
        $(`#total_harga-${elementId}`).val(total_harga_hidden - diskon_harga);

        $(`#tax-${elementId}`).trigger("change");
        countDiskonWithTax();
    } else {
        $(`#total_harga-${elementId}`).val(total_harga_hidden);
        $(`#tax-${elementId}`).trigger("change");
        countDiskonWithTax();
    }
}

function countDiskonRp(element) {
    let elementId = $(element).attr("id").split("-")[1];
    let diskon_rp = parseFloat($(`#diskon_rp-${elementId}`).val());
    let total_harga_hidden = $(`#total_harga_hidden-${elementId}`).val();

    $(`#diskon_persen-${elementId}`).val(0);

    if (diskon_rp > 0) {
        $(`#total_harga-${elementId}`).val(total_harga_hidden - diskon_rp);

        $(`#tax-${elementId}`).trigger("change");
        countDiskonWithTax();
    } else {
        $(`#total_harga-${elementId}`).val(total_harga_hidden);
        $(`#tax-${elementId}`).trigger("change");
        countDiskonWithTax();
    }
}

function countTax(element) {
    let elementId = $(element).attr("id").split("-")[1];
    let total_harga = parseFloat($(`#total_harga-${elementId}`).val());
    let ppn = parseFloat($("#ppn").val());

    if ($(element).is(":checked")) {
        $(element).val(1);
        let tax = (total_harga * 11) / 100;

        $(`#tax_hidden-${elementId}`).val(tax);
        $("#ppn").val(ppn + tax);
    } else {
        $(element).val(0);
        let tax_hidden = parseFloat($(`#tax_hidden-${elementId}`).val());
        $("#ppn").val(ppn - tax_hidden);
        $(`#tax_hidden-${elementId}`).val(0);
    }

    countTotal();
}

function countDiskonWithTax() {
    let diskon = 0;
    let tax = 0;

    $("#table-barang tbody tr").each(function () {
        diskon += parseFloat(
            $(this).children().find('[data-name="diskon_rp"]').val(),
        );
        tax += parseFloat(
            $(this).children().find('[data-name="tax_hidden"]').val(),
        );
    });

    $("#diskon").val(diskon);
    $("#ppn").val(tax);
    countTotal();
}

function countTotal() {
    let sub_total = parseFloat($("#sub_total").val());
    let diskon = parseFloat($("#diskon").val());
    let ppn = parseFloat($("#ppn").val());

    $("#total_harga").val(sub_total - diskon + ppn);
}

function changeNomorBapb() {
    
}
