$('#button-login').on('click', function() {
    let jsonData = {
        'username': $('#username').val(),
        'cabang'  : $('#cabang').val(),
        'password': $('#password').val(),
    }

    axios.post('/login', jsonData)
    .then((result) => {
        if (result.status == 200) {
            window.location.href = '/';
        }
    }).catch((err) => {
        let errors = err.response.data;
        swal(`${err.response.status}`, `${errors.message}`, 'error')
    });
})