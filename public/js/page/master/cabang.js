$(document).ready(function () {
    drawTable()
});

function drawTable() {
    $("#table-cabang").DataTable({
        autoWidth: false,
        paging: true,
        ajax: {
            url: "/cabang",
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
                data: "kepala_cabang",
            },
            {
                data: "id",
                render: function (data) {
                    return /*html*/ `
                        <div style="display: flex; justify-content: center; gap: 10px;">
                            <button type="submit" class="btn btn-primary btn-sm btn-action mr-1"data-toggle="tooltip" title="Edit" onclick="showModal(${data})"><i class="fas fa-pencil-alt"></i></button>
                            <button type="submit" class="btn btn-danger btn-sm btn-action mr-1" data-toggle="tooltip" title="Checkout" onclick="deleteCabang(${data})"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    `;
                },
            },
        ],
    });
}

function showModal(id = null) {
    $("#form-cabang").trigger("reset")
    $('.is-invalid').removeClass('is-invalid')

    $("#modal-cabang").modal({
        backdrop: "static",
        keyboard: false,
    });

    if (id == null) {
        $("#modal-title").text("Tambah Cabang");
        $("#btn-form-cabang").attr("onclick", `storeCabang()`);
        $("#btn-form-cabang").text("Simpan");
    } else {
        $("#modal-title").text("Ubah cabang");
        $("#btn-form-cabang").attr("onclick", `updateCabang(${id})`);
        $("#btn-form-cabang").text("Ubah");
        showCabang(id);
    }
}

const initializeDatatable = () => {
    $("#table-cabang").DataTable().destroy();
    drawTable();
};

function storeCabang() {
    let jsonData = {
        'nama': $('#nama').val(),
        'alamat': $('#alamat').val(),
        'kepala_cabang': $('#kepala_cabang').val(),
    }
    
    axios.post('/cabang', jsonData)
    .then((result) => {
        if (result.status === 200 || result.status === 201) {
            $('#modal-cabang').modal('hide')
            swal('Berhasil!', 'cabang berhasil ditambah!', 'success')
            
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

function showCabang(id) {
    axios.get(`/cabang/${id}`)
    .then((result) => {
        let response = result.data.data;

        $('#nama').val(response.nama)
        $('#alamat').val(response.alamat)
        $('#kepala_cabang').val(response.kepala_cabang)
    })
    .catch((err) => {
        swal(`${err.response.status}`, `${err.response.statusText}`, "error");
    });
}

function updateCabang(id) {
    let jsonData = {
        'nama': $('#nama').val(),
        'alamat': $('#alamat').val(),
        'kepala_cabang': $('#kepala_cabang').val(),
    }
    
    axios.put(`/cabang/${id}`, jsonData)
    .then((result) => {
        if (result.status === 200 || result.status === 201) {
            $('#modal-cabang').modal('hide')
            swal('Berhasil!', 'cabang berhasil diubah!', 'success')
            
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

function deleteCabang(id) {
    swal({
        title: 'Are you sure?',
        text: 'Once deleted, you will not be able to recover this imaginary file!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            axios.delete(`/cabang/${id}`)
            .then(function(response) {
                if (response.status == 200) {
                    $('#modal-cabang').modal('hide')
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
    $("#form-cabang").trigger("reset");
    $(".is-invalid").removeClass("is-invalid");
}