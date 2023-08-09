$(document).ready(function () {
    drawTable()
});

function drawTable() {
    $("#table-gudang").DataTable({
        autoWidth: false,
        paging: true,
        ajax: {
            url: "/gudang",
            dataSrc: "data",
        },
        columns: [
            {
                data: "DT_RowIndex",
            },
            {
                data: "nama",
            },
            {
                data: "alamat",
            },
            {
                data: "id",
                render: function (data) {
                    return /*html*/ `
                        <div style="display: flex; justify-content: center; gap: 10px;">
                            <button type="submit" class="btn btn-primary btn-sm btn-action mr-1"data-toggle="tooltip" title="Edit" onclick="showModal(${data})"><i class="fas fa-pencil-alt"></i></button>
                            <button type="submit" class="btn btn-danger btn-sm btn-action mr-1" data-toggle="tooltip" title="Checkout" onclick="deleteGudang(${data})"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    `;
                },
            },
        ],
    });
}

function showModal(id = null) {
    $("#form-gudang").trigger("reset")
    $('.is-invalid').removeClass('is-invalid')

    $("#modal-gudang").modal({
        backdrop: "static",
        keyboard: false,
    });


    if (id == null) {
        $("#modal-title").text("Tambah Gudang");
        $("#btn-form-gudang").attr("onclick", `storeGudang()`);
        $("#btn-form-gudang").text("Simpan");
    } else {
        $("#modal-title").text("Ubah Gudang");
        $("#btn-form-gudang").attr("onclick", `updateGudang(${id})`);
        $("#btn-form-gudang").text("Ubah");
        showGudang(id);
    }
}

const initializeDatatable = () => {
    $("#table-gudang").DataTable().destroy();
    drawTable();
};

function storeGudang() {
    let jsonData = {
        'nama': $('#nama').val(),
        'alamat': $('#alamat').val(),
    }
    
    axios.post('/gudang', jsonData)
    .then((result) => {
        if (result.status === 200 || result.status === 201) {
            $('#modal-gudang').modal('hide')
            swal('Berhasil!', 'gudang berhasil ditambah!', 'success')
            
            initializeDatatable()
        }
    }).catch((err) => {
        if (err.response.status === 422) {
            let error = err.response.data;

            $('.is-invalid').removeClass('is-invalid')

            swal(`${err.response.status}`, `${error.message}`, 'error')
            
            for (const key in error.errors) {
                $(`#${key}`).addClass('is-invalid');
                $(`#${key}`).next().html(`${error.errors[key][0]}`);
            }
            return
        }

        swal(`${err.response.status}`, `${err.response.statusText}`, 'error')
    });
}

function showGudang(id) {
    axios.get(`/gudang/${id}`)
    .then((result) => {
        let response = result.data.data;

        $('#nama').val(response.nama)
        $('#alamat').val(response.alamat)
    })
    .catch((err) => {
        swal(`${err.response.status}`, `${err.response.statusText}`, "error");
    });
}

function updateGudang(id) {
    let jsonData = {
        'nama': $('#nama').val(),
        'alamat': $('#alamat').val(),
    }
    
    axios.put(`/gudang/${id}`, jsonData)
    .then((result) => {
        if (result.status === 200 || result.status === 201) {
            $('#modal-gudang').modal('hide')
            swal('Berhasil!', 'gudang berhasil diubah!', 'success')
            
            initializeDatatable()
        }
    }).catch((err) => {
        if (err.response.status === 422) {
            let error = err.response.data;

            $('.is-invalid').removeClass('is-invalid')

            swal(`${err.response.status}`, `${error.message}`, 'error')
            
            for (const key in error.errors) {
                $(`#${key}`).addClass('is-invalid');
                $(`#${key}`).next().html(`${error.errors[key][0]}`);
            }
            return
        }

        swal(`${err.response.status}`, `${err.response.statusText}`, 'error')
    });
}

function deleteGudang(id) {
    swal({
        title: 'Are you sure?',
        text: 'Once deleted, you will not be able to recover this imaginary file!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            axios.delete(`/gudang/${id}`)
            .then(function(response) {
                if (response.status == 200) {
                    $('#modal-gudang').modal('hide')
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
    });
}

function removeErrorClass(element) {
    $(element).removeClass("is-invalid");
}

function resetForm() {
    $("#form-gudang").trigger("reset");
    $(".is-invalid").removeClass("is-invalid");
}