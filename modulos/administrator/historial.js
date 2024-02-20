$(document).ready(function () {
  function cargarContenidoTimeline(fechaI, fechaF) {
    $.ajax({
      url: "test.php", // Ruta a tu script PHP que se encarga de obtener los datos de la base de datos
      method: "POST",
      data: {
        fechaI: fechaI,
        fechaF: fechaF,
      },
      success: function (response) {
        $("#contenido-timeline").html(response);
      },
      error: function (xhr, status, error) {
        console.error(error);
      },
    });
  }
  var fechaIDefault = $("#fechaI").val();
    var fechaFDefault = $("#fechaF").val();
  // Cargar contenido del timeline al cargar la página con la fecha de hoy por default
  cargarContenidoTimeline(fechaIDefault, fechaFDefault);

  // Evento para cambiar la fecha seleccionada y cargar el contenido del timeline
 $("#buscar").on("click", function () {
    var fechaI = $("#fechaI").val();
    var fechaF = $("#fechaF").val();
    
    // Verificar si las fechas están vacías
    if (fechaI === '' || fechaF === '') {
        // Mostrar un mensaje de error
        Swal.fire({
            icon: 'error',
            title: 'Datos incompletos',
            text: 'Para filtrar entre fechas debe de definir las dos fechas.',
            showConfirmButton: true,
        });
        // Detener la ejecución del código
        return;
    }
  
    // Continuar con la lógica si las fechas no están vacías
    cargarContenidoTimeline(fechaI, fechaF);
});

});
