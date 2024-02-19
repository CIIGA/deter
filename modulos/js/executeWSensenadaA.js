$(obtener_registros());

function obtener_registros(alumnos, plaza)
{
    var varNum2 = $('#num2').val();
    var mp = $('#mp').val();
    
	$.ajax({
		url : '../php/execWSensenada.php?exec='+varNum2+'&mp='+mp,
		type : 'POST',
		dataType : 'html',
		data : { alumnos: alumnos,num2: varNum2 },
		})

	.done(function(resultado){
		$("#tabla_resultado").html(resultado);
	})
}

$(document).on('keyup', '#busqueda', function() {
	var valorBusqueda=$(this).val();
	if (valorBusqueda!="")
	{
		obtener_registros(valorBusqueda);
	}
	else {
        obtener_registros();
		}
});










