<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>Majestic Education</title>
	</head>
	<body style="background-color: white; color: black;">
        <h2>Hola {{ $student->name }}</h2>
	    <p>{{ $message }}</p>
		@if($student->check == 'accepted')
			@if(str_contains($student->book,'PACK') || $student->school_id == 39 || $student->school_id == 54)
				@include('partials.messages.libro-fisico', ['libro' => $student->book])
			@else
				@if(str_contains($student->book,'DIGITAL'))
					<p>Aproximadamente en 48 horas hábiles recibirás por correo tu código.</p>
				@else
					@if($student->school_id == 13)
						<p>Para recoger tu libro {{ $student->book }}, acudir al INSTITUTO TECNOLOGICO DE MINATITLAN el día jueves 26 de mayo.</p>
					@else
						@include('partials.messages.libro-fisico', ['libro' => $student->book])
					@endif
				@endif
			@endif
		@endif
        <hr>
		<p>Por favor no respondas este correo, ya que solo es de envió y tus respuestas no serán leídas. Si tienes alguna duda o aclaración, contáctanos al siguiente número.</p>
		<hr>
		<h2><b>Majestic Education</b></h2>
		Dudas o Aclaraciones: <br>
		56 2741 1481 <br>
		<strong>Horario de atención</strong><br>
		<ul>
			<li>Lunes a Viernes de 10:00 am - 5:00 pm</li>
			<li>Sábado de 10:00 am - 1:00 pm </li>
		</ul>
	</body>
</html>