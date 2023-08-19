let unitRow = 1
$(document).ready(function () {
    $('#checkbox-point').prop("checked", false);
    $('#nama-member').val('-')
    $('#points').val(0)
    $('#jumlah_point').val(0)
    $('#total_harga').val(0)
    $('#total_harga_text').text(0)

    Service.initSelect2({
        id: 'barang',
        uri: "stok-barang/search",
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
                text: `${item.barang.nama_barang} | ${Service.parseCurrency(item.barang.harga_jual)}`,
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
        $('#points').val(parts[2])
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
                <button type="button" class="btn btn-danger btn-sm btn-action mr-1 btn-deleteRow" id="delete_row-${unitRow}"  onClick="deleteRow(this)">
                    <i class="fas fa-trash-alt"></i>
                </button>
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
                <input type="text" id="diskon-${unitRow}" class="form-control" value="0">
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

$('#checkbox-point').on('change', function() {
    let totalHarga = Service.parseCurrencyToFloat($("#total_harga").val())
    let point      = parseFloat($('#points').val())
    
    if ($(this).is(":checked")) {
        $('#jumlah_points').val(point)

        $("#total_harga").val(Service.parseCurrency(totalHarga - point))
        $("#total_harga_text").text(Service.parseCurrency(totalHarga - point))
        console.log(totalHarga);
        console.log(point);
    } else {
        $('#jumlah_points').val(0)

        $("#total_harga").val(Service.parseCurrency(totalHarga + point))
        $("#total_harga_text").text(Service.parseCurrency(totalHarga + point))
    }
})

function formReset() {
    unitRow = 1
    $("#table-barang tbody").empty()
    $("#jumlah_point").val(0)
    $("#total_harga").val(0)
    $("#member").empty()
    $("#nama-member").val('-')
    $("#total_harga_text").text(0)
    $("#jumlah-point").text('')
    $('#checkbox-point').prop("checked", false);
}


function jsonData() {
    let data = [];
    $("#table-barang tbody tr").each(function () {
        console.log($(this).find('input').first());
        let elementId = $(this).find('input').first().attr("id").split("-")[1]

        let obj = {
            barang_id: $(`#barang_id-${elementId}`).val(),
            qty      : $(`#qty-${elementId}`).val(),
            satuan_id: $(`#satuan_id-${elementId}`).val(),
            harga    : Service.parseCurrencyToFloat($(`#harga-${elementId}`).val()),
            diskon   : $(`#diskon-${elementId}`).val(),
            total    : Service.parseCurrencyToFloat($(`#sub_total-${elementId}`).val()),
        };

        data.push(obj);
    });

    let usePoint = $('#checkbox-point').is(":checked") ? 1 : 0
    let form     = new FormData(document.getElementById("form-kasir"));
    let jsonData = {
        usePoint    : usePoint,
        member_id   : $('#member').val() || 0,
        jumlah_point: parseFloat($('#jumlah_points').val() || 0),
        total_harga : Service.parseCurrencyToFloat($('#total_harga').val()),
        barang: data,
    };

    form.forEach((value, key) => (jsonData[key] = value));

    return jsonData;
}

function bayar() {
    console.log( jsonData());
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this imaginary file!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            Service.showLoading();
            axios.post('/kasir', jsonData())
            .then((result) => {
                if (result.status === 200 || result.status === 201) {
                    formReset()
                    swal("Berhasil!", "Transaksi berhasil dilakukan!", "success")
                }
            })
            .catch((err) => Service.handelErrorFetch(err, "table-barang"))
            .finally(() => Service.hideLoading());
        } else {
            swal("Your imaginary file is safe!");
        }
    })
}