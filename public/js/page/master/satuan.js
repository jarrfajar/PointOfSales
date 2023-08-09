$(document).ready(function () {
    drawTable()
});

function drawTable() {
    $("#table-satuan").DataTable({
        autoWidth: false,
        paging: true,
        ajax: {
            url: "/satuan",
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
                data: "deskripsi",
            },
            {
                data: "id",
                render: function (data) {
                    return /*html*/ `
                        <div style="display: flex; justify-content: center; gap: 10px;">
                            <button type="submit" class="btn btn-primary btn-sm btn-action mr-1"data-toggle="tooltip" title="Edit" onclick="showModal(${data})"><i class="fas fa-pencil-alt"></i></button>
                            <button type="submit" class="btn btn-danger btn-sm btn-action mr-1" data-toggle="tooltip" title="Checkout" onclick="deleteSatuan(${data})"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    `;
                },
            },
        ],
    });
}

function showModal(id = null) {
    $("#form-satuan").trigger("reset")
    $('.is-invalid').removeClass('is-invalid')

    $("#modal-satuan").modal({
        backdrop: "static",
        keyboard: false,
    });

    if (id == null) {
        $("#modal-title").text("Tambah Satuan");
        $("#btn-form-satuan").attr("onclick", `storeSatuan()`);
        $("#btn-form-satuan").text("Simpan");
    } else {
        $("#modal-title").text("Ubah Gudang");
        $("#btn-form-satuan").attr("onclick", `updateSatuan(${id})`);
        $("#btn-form-satuan").text("Ubah");
        showSatuan(id);
    }
}

const initializeDatatable = () => {
    $("#table-satuan").DataTable().destroy();
    drawTable();
};

function storeSatuan() {
    let jsonData = {
        'nama': $('#nama').val(),
        'deskripsi': $('#deskripsi').val(),
    }
    
    axios.post('/satuan', jsonData)
    .then((result) => {
        if (result.status === 200 || result.status === 201) {
            $('#modal-satuan').modal('hide')
            swal('Berhasil!', 'satuan berhasil ditambah!', 'success')
            
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

function showSatuan(id) {
    axios.get(`/satuan/${id}`)
    .then((result) => {
        let response = result.data.data;

        $('#nama').val(response.nama)
        $('#deskripsi').val(response.deskripsi)
    })
    .catch((err) => {
        swal(`${err.response.status}`, `${err.response.statusText}`, "error");
    });
}

function updateSatuan(id) {
    let jsonData = {
        'nama': $('#nama').val(),
        'deskripsi': $('#deskripsi').val(),
    }
    
    axios.put(`/satuan/${id}`, jsonData)
    .then((result) => {
        if (result.status === 200 || result.status === 201) {
            $('#modal-satuan').modal('hide')
            swal('Berhasil!', 'satuan berhasil diubah!', 'success')
            
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

function deleteSatuan(id) {
    swal({
        title: 'Are you sure?',
        text: 'Once deleted, you will not be able to recover this imaginary file!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            axios.delete(`/satuan/${id}`)
            .then(function(response) {
                if (response.status == 200) {
                    $('#modal-satuan').modal('hide')
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
    $("#form-satuan").trigger("reset");
    $(".is-invalid").removeClass("is-invalid");
}