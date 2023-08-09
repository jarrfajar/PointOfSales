"use strict";

$('#button-register').on('click', function() {
    let jsonData = {
        'username': $('#username').val(),
        'cabang'  : $('#cabang').val(),
        'password': $('#password').val(),
    }

    axios.post('/register', jsonData)
    .then((result) => {
        if (result.status == 200 || result.status == 201) {
            window.location.href = '/';
        }
    }).catch((err) => {
        if (err.response.status === 422) {
            let errors = err.response.data;
            swal(`${err.response.status}`, `${errors.message}`, 'error')
            
        }
    });
})