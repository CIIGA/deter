function mostrarSweetAlert(icono, titulo, texto) {
    Swal.fire({
        icon: icono,
        title: titulo,
        text: texto,
        showConfirmButton: true,
        timer: 2000
    });
}