$(document).ready(function () {
    getIndex()
})

function getIndex() {
    Service.showLoading()
    
    axios.get('/barang-masuk')
    .then((result) => {
        const response = result.data.data
        drawTable(response)
    }).catch((err) => Service.handelErrorFetch(err))
    .finally(() => Service.hideLoading())
}

function drawTable(data) {
    $("#table-barang-masuk").DataTable({
        autoWidth: false,
        paging: true,
        data: data,
        columns: [
            {
                data: "DT_RowIndex",
            },
            {
                data: "penerimaan_barang",
                render: (data) => data.tanggal_terima
            },
            {
                data: "barang",
                render: (data) => data.nama_barang
            },
            {
                data: "penerimaan_barang",
                render: (data) => data.supplier.nama
            },
            {
                data: "penerimaan_barang",
                render: (data) => data.gudang.nama
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
                data: "jumlah",
            },
        ],
    })
}