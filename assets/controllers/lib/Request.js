import $ from "jquery";

export function request(url, data = {}, datatype = 'text', csrf_token_id = 'csrf_token') {
    const csrf_token = $(`#${csrf_token_id}`).attr('data-csrf-token');
    if (csrf_token) {
        data[csrf_token_id] = csrf_token;
        return new Promise((resolve, reject) => {
            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                dataType: datatype,
                success: function (response) {
                    resolve(response);
                },
                error: function (xhr) {
                    reject('Erreur lors de la requête : ' + xhr.responseText);
                }
            });
        });
    } else {
        return Promise.reject('Token CSRF manquant');
    }
}

export function file_request(url, formdata, datatype = 'text', csrf_token_id = 'csrf_token') {
    const csrf_token = $(`#${csrf_token_id}`).attr('data-csrf-token');
    if (csrf_token) {
        formdata.append(csrf_token_id, csrf_token)
        return new Promise((resolve, reject) => {
            $.ajax({
                url: url,
                type: 'POST',
                data: formdata,
                dataType: datatype,
                processData: false,
                contentType: false,
                success: function (response) {
                    resolve(response);
                },
                error: function (xhr) {
                    reject('Erreur lors de la requête : ' + xhr.responseText);
                }
            });
        });
    } else {
        return Promise.reject('Token CSRF manquant');
    }
}