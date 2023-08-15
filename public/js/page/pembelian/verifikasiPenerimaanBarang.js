let unitROw = 1
$(document).ready(function () {
    getIndex()
})

function getIndex() {
    Service.showLoading()
    
    axios.get('/verifikasi-penerimaan-barang')
    .then((result) => {
        const response = result.data.data
        drawTable(response)
    }).catch((err) => Service.handelErrorFetch(err))
    .finally(() => Service.hideLoading())
}

function drawTable(data) {
    $("#table-verifikasi-bapb").DataTable({
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
                            <button type="submit" class="btn btn-danger btn-sm btn-action mr-1" data-toggle="tooltip" title="Checkout" onclick="deleteBapb(${data})"><i class="fas fa-trash-alt"></i></button>
                        </div>`
                },
            },
        ],
    })
}

const initializeDatatable = () => {
    $("#table-verifikasi-bapb").DataTable().destroy();
    drawTable();
}

function showModal(id) {
    unitROw = 1
    $("#modal-verifikasi-bapb").modal({
        backdrop: "static",
        keyboard: false,
    })

    $("#modal-title").text("Validasi BAPB");
    $("#btn-form-verifikasi-bapb").attr("onclick", 'validasi()');

    showPenerimaanBarang(id)

    formReset()
}

function formReset() {
    Service.resetForm('form-verifikasi-bapb')
    $('#tanggal_po').val('')
    $('#sub_total').val('')
    $('#diskon').val('')
    $('#ppn').val('')
    $('#total_harga').val('')
}

function showPenerimaanBarang(id) {
    Service.showLoading()
    axios.get(`/penerimaan-barang/${id}`)
    .then((result) => {
        const response = result.data.data

        $('#supplier_id').val(response.supplier.nama)
        $('#nomor_bapb').val(response.nomor_bapb)
        $('#gudang_id').val(response.gudang.nama)
        $('#nomor_faktur').val(response.nomor_faktur)

        $('#table-barang tbody').empty()

        response.barangs.forEach(data => {
            let pajak

            if (data.ppn == 1) {
                pajak = `<span class="badge badge-pill badge-success">PPN 11%</span>`;
            } else {
                pajak = `<span class="badge badge-pill badge-primary">Tanpa PPN</span>`;
            }

            $('#table-barang tbody').append(/*html*/`
                <tr>
                    <td>${data.barang.nama_barang}</td>
                    <td>${data.jumlah}</td>
                    <td>${data.satuan.nama}</td>
                    <td>${parseFloat(data.harga)}</td>
                    <td>${data.diskon_persen}</td>
                    <td>${parseFloat(data.diskon_rp)}</td>
                    <span class="badge badge-pill badge-primary">${data}</span>
                    <td>${pajak}</td>
                    <td>${parseFloat(data.total_harga)}</td>
                    <td>
                        <div style="display: flex; justify-content: center; gap: 10px;">
                            <input type="hidden" id="barang-${unitROw}" value="${data.barang.nama_barang}">
                            <input type="hidden" id="barang_id-${unitROw}" value="${data.barang_id}">
                            <input type="hidden" id="jumlah-${unitROw}" value="${data.jumlah}">
                            <input type="hidden" id="status-${unitROw}">
                            <button type="button" class="btn btn-primary btn-sm btn-action mr-1" data-toggle="tooltip" title="Valid" id="valid-${unitROw}" onclick="valid(${unitROw})"><i class="fa-regular fa-circle-check fa-xl"></i></button>
                            <button type="button" class="btn btn-danger btn-sm btn-action mr-1" data-toggle="tooltip" title="Retur"  id="retur-${unitROw}" onclick="retur(${unitROw})"><i class="fa-regular fa-circle-xmark fa-xl"></i></i></button>
                        </div>
                    </td>
                </tr>
            `)

            unitROw++
        });
    })
    .catch((err) => Service.handelErrorFetch(err, 'table-barang'))
    .finally(() => Service.hideLoading())
}

function validAll() {
    $('#table-barang tbody tr').each(function(index) {
        valid(index + 1)
    })
}

function returAll() {
    $('#table-barang tbody tr').each(function(index) {
        retur(index + 1)
    })
}

function valid(id) {
    $(`#valid-${id}`).parents('tr').css({"background-color": "#b3b3ff", "color": "white", "font-weight": "bold"})
    $(`#status-${id}`).val(1)
}

function retur(id) {
    $(`#retur-${id}`).parents('tr').css({"background-color": "#ffb3b3", "color": "white", "font-weight": "bold"})
    $(`#status-${id}`).val(0)
}


function jsonData() {
    let data = [];
    $('#table-barang tbody tr').each(function (index) {
        var obj = {
            jumlah   : $(this).find(`#jumlah-${index + 1}`).val(),
            barang_id: $(this).find(`#barang_id-${index + 1}`).val(),
            status   : $(this).find(`#status-${index + 1}`).val(),
        }

        data.push(obj)
    })

    let form = new FormData(document.getElementById("form-verifikasi-bapb"))
    let jsonData = {
        barangs: data,
    };

    form.forEach((value, key) => (jsonData[key] = value))

    return jsonData
}

function validasi() {
    let checkAllValid = false
    let nomor_bapb    = $('#nomor_bapb').val()
    
    $('#table-barang tbody tr').each(function(index) {
        let barang = $(`#barang-${index + 1}`).val()

        if ($(`#status-${index + 1}`).val() == '') {
            swal('Gagal', `${barang} belum divalidasi`, 'error')
            checkAllValid = false
            return false
        }
        
        checkAllValid = true
    })

    if (checkAllValid) {
        swal({
            title: 'Are you sure?',
            text: 'Once deleted, you will not be able to recover this imaginary file!',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                Service.showLoading()
                axios.put(`/verifikasi-penerimaan-barang/${nomor_bapb}`, jsonData())
                .then((result) => {
                    if (result.status === 200 || result.status === 201) {
                        $('#modal-verifikasi-bapb').modal('hide')
                        swal('Berhasil!', 'Verifikasi BAPB berhasil ditambah!', 'success')
                        
                        initializeDatatable()
                    }
                })
                .catch((err) => Service.handelErrorFetch(err))
                .finally(() => Service.hideLoading())
                
            } else {
                swal('Your imaginary file is safe!');
            }
        })
    }
}