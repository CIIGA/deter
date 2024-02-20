// Agrega un evento click al botón de imprimir
$(".btnImprimir").click(function (e) {
  e.preventDefault(); // Evita el comportamiento predeterminado del enlace
  var idFormato = $(this).data("id_formato");
  console.log(idFormato);
  // Muestra una alerta de SweetAlert con dos botones
  Swal.fire({
    title: "¿Qué acción deseas realizar para imprimir el formato?",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Masivo",
    cancelButtonText: "Individual",
  }).then((result) => {
    if (result.isConfirmed) {
      // Si elige "Masivo", redirige a una vista con parámetros PHP
      window.location.href =
        'cargaPeticion.php?f=' +idFormato; // Actualiza los parámetros según tus variables PHP
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      // Si elige "Individual", pide un número de identificación
      Swal.fire({
        title: "Ingrese la cuenta a buscar:",
        input: "text",
        inputPlaceholder: "Cuenta catastral",
        showCancelButton: true,
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
        inputValidator: (value) => {
          if (!value) {
            return "Debes ingresar una cuenta";
          }
        },
      }).then((result) => {
        if (result.isConfirmed) {
          // Si ingresa un número de identificación y presiona "Aceptar", redirige a otra vista con ese número por método GET
          const numeroIdentificacion = result.value;
          window.location.href = `individual.php?cuenta=${numeroIdentificacion}`;
        }
      });
    }
  });
});
$(document).ready(function () {
  // Selecciona todos los interruptores de clase .custom-control-input
  $(".custom-control-input").change(function () {
    // Encuentra el formulario más cercano al interruptor cambiado y lo envía
    $(this).closest("form").submit();
  });
});
$(".btnArchivo").click(function () {
  // Obtener el ID y el porcentaje de CSS del botón
  var id = $(this).data("id");
  var archivo = $(this).data("archivo");
  var tipo = $(this).data("tipo");
  $(".id_formato").val(id);
  $(".tipo").val(tipo);
  $(".btnDescarga").attr("href", archivo);
  $(".tipoArchivo").text(tipo);
  if (tipo === "header" || tipo === "footer") {
    $("#modalArchivoFondo").modal("show");
  } else {
    $("#modalArchivo").modal("show");
  }
});
$(".btnConfigurar").click(function () {
  
  // Obtener el ID y el porcentaje de CSS del botón
  var id = $(this).data("id");
  var tamanio = $(this).data("tamanio");
  var top = $(this).data("top");
  var bottom = $(this).data("bottom");
  var left = $(this).data("left");
  var right = $(this).data("right");

  $("#c_id_formato").val(id);
  $("#mtop").val(top);
  $("#mbottom").val(bottom);
  $("#mleft").val(left);
  $("#mright").val(right);
  $("#tamanio").empty();
  if (tamanio === 'carta') {
    $('#tamanio').append($('<option>', {
        value: '0', // El valor de la opción
        text: 'carta' // El texto que se mostrará en la opción
    }));
    $('#tamanio').append($('<option>', {
        value: 'oficio', // El valor de la opción
        text: 'oficio' // El texto que se mostrará en la opción
    }));
  } else {
    $('#tamanio').append($('<option>', {
        value: 'oficio', // El valor de la opción
        text: 'oficio' // El texto que se mostrará en la opción
    }));
    $('#tamanio').append($('<option>', {
        value: 'carta', // El valor de la opción
        text: 'carta' // El texto que se mostrará en la opción
    }));
  }

    $("#modalConfigurar").modal("show");
  
});
document
  .getElementById("html")
  .addEventListener("change", validateFileExtension);
document
  .getElementById("encabezado")
  .addEventListener("change", validateFileExtensionJpg);
document
  .getElementById("pie")
  .addEventListener("change", validateFileExtensionJpg);
document
  .getElementById("css")
  .addEventListener("change", validateFileExtension);
document
  .getElementById("archivo")
  .addEventListener("change", validateFileExtension);
document
  .getElementById("archivoFondo")
  .addEventListener("change", validateFileExtensionJpg);

function validateFileExtension(event) {
  const allowedExtensions = ["txt"];
  const input = event.target;
  const fileName = input.files[0].name;
  const fileExtension = fileName.split(".").pop().toLowerCase();
  console.log(fileExtension);

  if (!allowedExtensions.includes(fileExtension)) {
    Swal.fire({
      icon: "error",
      title: "Fatal error!",
      text: "El archivo debe de ser con extensión .txt",
      timer: 3000, // Duración en milisegundos (3 segundos en este caso)
      showConfirmButton: false, // Ocultar el botón de confirmación
    });
    input.value = ""; // Clear the input
  }
}

function validateFileExtensionJpg(event) {
  const allowedExtensions = ["jpg"];
  const input = event.target;
  const fileName = input.files[0].name;
  const fileExtension = fileName.split(".").pop().toLowerCase();
  console.log(fileExtension);

  if (!allowedExtensions.includes(fileExtension)) {
    Swal.fire({
      icon: "error",
      title: "Fatal error!",
      text: "El archivo debe de ser una imagen con extensión .jpg o .png",
      timer: 3000, // Duración en milisegundos (3 segundos en este caso)
      showConfirmButton: false, // Ocultar el botón de confirmación
    });
    input.value = ""; // Clear the input
  }
}
