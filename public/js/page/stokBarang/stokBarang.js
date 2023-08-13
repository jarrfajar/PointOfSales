$(document).ready(function () {
    getIndex()
})

function getIndex() {
    Service.showLoading()
    
    axios.get('/stok-barang')
    .then((result) => {
        const response = result.data.data
        drawTable(response)
    }).catch((err) => Service.handelErrorFetch(err))
    .finally(() => Service.hideLoading())
}

function drawTable(data) {
    $("#table-stok-barang").DataTable({
        autoWidth: false,
        paging: true,
        data: data,
        columns: [
            {
                data: "DT_RowIndex",
            },
            {
                data: "gudang",
                render: (data) => data.nama
            },
            {
                data: "barang",
                render: (data) => data.nama_barang
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
                data: "batas_min",
            },
            {
                data: "batas_max",
            },
            {
                data: "jumlah",
            },
            {
                data: "barang",
                render: (data) => data.tanggal_kadaluarsa
            },
            {
                data: "status_str",
                render: function (data) {
                    switch (data) {
                        case 'Berlebih':
                            return /*html*/`<span class="badge badge-pill badge-primary">${data}</span>`
                            break;
                        case 'Peringatan':
                            return /*html*/`<span class="badge badge-pill badge-warning">${data}</span>`
                            break;
                        case 'Normal':
                            return /*html*/`<span class="badge badge-pill badge-success">${data}</span>`
                            break;
                        default:
                            return /*html*/`<span class="badge badge-pill badge-danger">${data}</span>`
                            break;
                    }
                }
            },
        ],
    })
}