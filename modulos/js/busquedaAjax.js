$(obtener_registros());
function obtener_registros(alumnos)
{   
	$.ajax({
		url : '../php/buscaUsr.php',
		type : 'POST',
		dataType : 'html',
		data : { alumnos: alumnos },
		})

	.done(function(resultado){
		$("#tabla_resultado").html(resultado);
	})
}
$(document).on('keyup', '#busqueda', function(){
	var valorBusqueda=$(this).val();
	if (valorBusqueda!="")
	{
		obtener_registros(valorBusqueda);
	}
	else
		{
			obtener_registros();
		}
});