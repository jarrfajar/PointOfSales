$(document).ready(function () {
    $('#start').val(moment().format('YYYY-MM-DD'))
    $('#end').val(moment().format('YYYY-MM-DD'))

    getIndex()
})

function getIndex() {
    const jsonData = {
        start : $('#start').val(),
        end : $('#end').val()
    }

    Service.showLoading()
    axios.get('/barang-keluar', {
        params: jsonData
      })
    .then((result) => {
        const response = result.data.data
        drawTable(response)
    }).catch((err) => Service.handelErrorFetch(err))
    .finally(() => Service.hideLoading())
}

function drawTable(data) {
    $("#table-barang-keluar").DataTable({
        autoWidth: false,
        paging: true,
        data: data,
        columns: [
            {
                data: "DT_RowIndex",
            },
            {
                data: "sale",
                render: (data) => data.tanggal
            },
            {
                data: "barang",
                render: (data) => data.nama_barang
            },
            {
                data: "sale",
                render: (data) => data?.gudang?.nama || ''
            },
            {
                data: "barang",
                render: (data) => data.kategori.nama
            },
            {
                data: "barang",
                render: (data) => data.satuan.nama
            },
            {
                data: "qty",
            },
        ],
    })
}

function filter() {
    $("#modal-barang-keluar").modal({
        backdrop: "static",
        keyboard: false,
    })

    $("#modal-title").text("Filter Data")
    $("#btn-form-barang-keluar").attr("onclick", `filterData()`)
}


const initializeDatatable = () => {
    $("#table-barang-keluar").DataTable().destroy()
    getIndex()
}

function filterData() {
    $('#modal-barang-keluar').modal('hide')
    initializeDatatable()
}

function formReset() {
    $('#start').val(moment().format('YYYY-MM-DD'))
    $('#end').val(moment().format('YYYY-MM-DD'))
}