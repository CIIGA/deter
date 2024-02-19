var WSMexicali = function () {
    return {
        init: function () {
            $(".call-webservice-mexicali").on('click', () => {
                var url = $(".call-webservice-mexicali").attr('href');
                wsmexicali.showModal(url);
                $(this).hide();
                return false;
            });
        }, 
        showModal: function (url) {
            Swal.fire({
                title: 'WS Mexicali Agua',
                text: "¿Estás seguro de consumir el WebService?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(url, { method: "POST" })
                        .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                        })
                        .catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        )
                    });
                },
                backdrop: () => !Swal.isLoading(),
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                $(".call-webservice-mexicali").show();
                if (result.value.type == 200) {
                    var mensaje = "<span>El proceso termino.</span>";
                    if(result.value.errores.length > 0) {
                        mensaje += "<br /> Se encontraron algunos errores:<br />";
                        mensaje += wsmexicali.getErrores(result.value.errores);
                    }
                    Swal.fire({
                        icon: 'success',
                        title: "WS Mexicali Agua",
                        html: mensaje,
                        confirmButtonText: 'Aceptar',
                    });
                } else {
                    var mensaje = wsmexicali.getErrores(result.value.errores);
                    Swal.fire({
                        icon: 'error',
                        title: "Error al enviar los datos",
                        confirmButtonText: 'Aceptar',
                        html: mensaje
                    });
                }
            });
        }, 
        getErrores: function (errores) {
            var mensaje = "<ul class='text-danger' style='font-size: 14px;list-style-type: none;padding: 0;margin: 0;'>";
            $.each(errores, function (idx, error) {
                mensaje += "<li>" + error + "</li>"
            });
            mensaje += "</ul>";
            return mensaje;
        }
    }
}