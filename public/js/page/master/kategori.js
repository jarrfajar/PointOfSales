$(document).ready(function () {
    drawTable()
});

function drawTable() {
    $("#table-kategori").DataTable({
        autoWidth: false,
        paging: true,
        ajax: {
            url: "/kategori",
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
                data: "id",
                render: function (data) {
                    return /*html*/ `
                        <div style="display: flex; justify-content: center; gap: 10px;">
                            <button type="submit" class="btn btn-primary btn-sm btn-action mr-1"data-toggle="tooltip" title="Edit" onclick="showModal(${data})"><i class="fas fa-pencil-alt"></i></button>
                            <button type="submit" class="btn btn-danger btn-sm btn-action mr-1" data-toggle="tooltip" title="Checkout" onclick="deleteKategori(${data})"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    `;
                },
            },
        ],
    });
}

function showModal(id = null) {
    $("#form-kategori").trigger("reset")
    $('.is-invalid').removeClass('is-invalid')

    $("#modal-kategori").modal({
        backdrop: "static",
        keyboard: false,
    });

    if (id == null) {
        $("#modal-title").text("Tambah Kategori");
        $("#btn-form-kategori").attr("onclick", `storeKategori()`);
        $("#btn-form-kategori").text("Simpan");
    } else {
        $("#modal-title").text("Ubah Kategori");
        $("#btn-form-kategori").attr("onclick", `updateKategori(${id})`);
        $("#btn-form-kategori").text("Ubah");
        showKategori(id);
    }
}

const initializeDatatable = () => {
    $("#table-kategori").DataTable().destroy();
    drawTable();
};

function storeKategori() {
    let jsonData = {
        'nama': $('#nama').val()
    }
    
    axios.post('/kategori', jsonData)
    .then((result) => {
        if (result.status === 200 || result.status === 201) {
            $('#modal-kategori').modal('hide')
            swal('Berhasil!', 'kategori berhasil ditambah!', 'success')
            
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

function showKategori(id) {
    axios.get(`/kategori/${id}`)
    .then((result) => {
        let response = result.data.data;

        $('#nama').val(response.nama)
    })
    .catch((err) => {
        swal(`${err.response.status}`, `${err.response.statusText}`, "error");
    });
}

function updateKategori(id) {
    let jsonData = {
        'nama': $('#nama').val()
    }
    
    axios.put(`/kategori/${id}`, jsonData)
    .then((result) => {
        if (result.status === 200 || result.status === 201) {
            $('#modal-kategori').modal('hide')
            swal('Berhasil!', 'kategori berhasil diubah!', 'success')
            
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

function deleteKategori(id) {
    swal({
        title: 'Are you sure?',
        text: 'Once deleted, you will not be able to recover this imaginary file!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            axios.delete(`/kategori/${id}`)
            .then(function(response) {
                if (response.status == 200) {
                    $('#modal-kategori').modal('hide')
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