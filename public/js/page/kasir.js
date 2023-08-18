let unitRow = 1
$(document).ready(function () {
    $('#checkbox-point').prop("checked", false);
    $('#nama-member').val('-')
    $('#total_harga').val(0)
    $('#total_harga_text').text(0)

    Service.initSelect2({
        id: 'barang',
        uri: "barang-search",
        placeholder: "--Pilih Barang--",
        params: function (data) {
            return {
                search: data.term,
                gudang: 3,
            }
        },
        item: function (item) {
            return {
                id: item.id,
                text: `${item.nama_barang} | ${Service.parseCurrency(item.harga_jual)}`,
            };
        },
    })

    Service.initSelect2({
        id: 'member',
        uri: "member-search",
        placeholder: "--Cari Member--",
        params: function (data) {
            return {
                search: data.term,
                gudang: 3,
            }
        },
        item: function (item) {
            return {
                id: item.id,
                text: `${item.nama} | ${item.nomor_telpon} | ${item.point}`,
            };
        },
    })
})

$('#member').on('change', function() {
    const selectedOption = $(this).select2('data')
    
    if (selectedOption.length > 0) {
        const selectedText = selectedOption[0].text
        const parts        = selectedText.split(" | ")

        $('#nama-member').val(parts[0])
        $('#jumlah-point').text(parts[2])
    } else {
        console.log("Tidak ada pilihan yang dipilih.")
    }
})

let isBarangChange = false

$('#barang').on('change', async function() {
    if (!isBarangChange) {
        try {
            const response = await getBarang($(this).val())
            if (response) {
                isBarangChange = true
                $("#barang").val(null).change()
                isBarangChange = false
            }
        } catch (error) {
            console.error("Terjadi kesalahan:", error)
        }
    }
})

async function getBarang(id) {
    try {
        Service.showLoading()
        const result = await axios.get(`/barang/${id}`)
       
        const response = result.data.data

        let isExist = false
        $("#table-barang tbody tr").each(function() {
            const barang = parseInt($(this).children().find('[data-name="barang"]').val())

            if (barang == id) {
                isExist = true
                swal("Gagal", `${response.nama_barang} sudah ada dalam list`, "error")
                return false
            }
        })

        if (!isExist) {
            addRow(response)
        }

        return response
    } catch (error) {
        Service.handleErrorFetch(error)
        throw error
    } finally {
        Service.hideLoading()
    }
}

function addRow(data) {
    $("#table-barang tbody").append(/*html*/`
        <tr>
            <td>
            <button type="button" class="btn btn-danger btn-sm btn-action mr-1 btn-deleteRow" id="delete_row-${unitRow}"  onClick="deleteRow(this)"><i class="fas fa-trash-alt"></i></button>
            </td>
            <td>
                <input type="hidden" id="barang_id-${unitRow}" class="form-control" value="${data.id}" data-name="barang">
                <input type="text" id="nama_barang-${unitRow}" class="form-control" value="${data.nama_barang}" readonly>
            </td>
            <td>
                <input type="text" id="qty-${unitRow}" class="form-control" value="1" onchange="countQty(this)">
            </td>
            <td>
                <input type="hidden" id="satuan_id-${unitRow}" class="form-control" value="${data?.satuan?.id}">
                <input type="text" id="satuan-${unitRow}" class="form-control" value="${data?.satuan?.nama}" readonly>
            </td>
            <td>
                <input type="text" id="harga-${unitRow}" class="form-control" value="${Service.parseCurrency(data.harga_jual)}" readonly>
            </td>
            <td>
                <input type="text" id="diskon_persen-${unitRow}" class="form-control" value="0">
            </td>
            <td>
                <input type="text" id="sub_total-${unitRow}" class="form-control" value="${Service.parseCurrency(data.harga_jual)}" data-name="total_harga" readonly>
            </td>
        </tr>
    `)

    unitRow++

    let total_harga = Service.parseCurrencyToFloat($('#total_harga').val())
    $('#total_harga').val(total_harga + parseFloat(data?.harga_jual))
    $('#total_harga_text').text(Service.parseCurrency(total_harga + parseFloat(data?.harga_jual)))
}

function countQty(element) {
    let elementId = $(element).attr("id").split("-")[1]
    let qty       = parseInt($(`#qty-${elementId}`).val())
    let sub_total = Service.parseCurrencyToFloat($(`#harga-${elementId}`).val())

    $(`#sub_total-${elementId}`).val(Service.parseCurrency(sub_total * qty))

    countTotalHarga()
}

function countTotalHarga() {
    let total_harga = 0
    
    $("#table-barang tbody tr").each(function () {
        total_harga += Service.parseCurrencyToFloat($(this).children().find('[data-name="total_harga"]').val())
    })
    
    $('#total_harga_text').text(Service.parseCurrency(total_harga))
    $('#total_harga').val(Service.parseCurrency(total_harga))
}

function deleteRow(element) {
    let elementId   = $(element).attr("id").split("-")[1]
    let harga       = Service.parseCurrencyToFloat($(`#sub_total-${elementId}`).val())
    let total_harga = Service.parseCurrencyToFloat($("#total_harga").val())

    $("#total_harga").val(Service.parseCurrency(total_harga - harga))
    $("#total_harga_text").text(Service.parseCurrency(total_harga - harga))

    $(element).parent().parent().remove()
}