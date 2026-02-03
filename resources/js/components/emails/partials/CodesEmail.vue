<template>
    <div>
		<p>Te enviamos el código para acceder al libro <b>{{ book }}</b>:</p>
        <div v-if="message == 'MAJESTIC EDUCATION'">
            <div v-for="(codigo, i) in email.student.codigos" v-bind="i">
                <h6><b>CORREO {{ i + 1 }}: </b></h6>
                <ol>
                    <li>Ingresar a: https://www.blinklearning.com/home</li>
                    <li>Descarga el <a href="https://dl.dropbox.com/s/kbv18zto09ky76w/Manual_alumno_blink_ME.pdf"><b>Manual</b></a> para poder registrarte.</li>
                </ol>
                <b v-if="codigo.code2 == 'BLINK'">TU CÓDIGO ES: {{ codigo.code1 }}</b>
                <div v-else>
                    <div v-if="codigo.code4 == 'NO' && codigo.code5 == 'NO'">
                        <b>TU CÓDIGO DE BAE 2 ES: {{ codigo.code1 }}</b><br>
                        <b>TU CÓDIGO DE BAE 4 ES: {{ codigo.code2 }}</b>
                        <br v-if="code.code3 !== 'NO'"><b>TU CÓDIGO DE MIND TWISTERS (LECTURA) ES: {{ codigo.code3 }}</b>
                    </div>
                    <div v-else>
                        <b>TU CÓDIGO 1 ES: {{ codigo.code1 }}</b><br>
                        <b>TU CÓDIGO 2 ES: {{ codigo.code2 }}</b><br>
                        <b>TU CÓDIGO 3 ES: {{ codigo.code3 }}</b><br>
                        <b>TU CÓDIGO 4 ES: {{ codigo.code4 }}</b><br>
                        <b>TU CÓDIGO 5 ES: {{ codigo.code5 }}</b>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="message == 'EXPRESS PUBLISHING'">
            <p>Ingresa a la URL: <a target="_blank" href="https://www.expressdigibooks.com/site/register">https://www.expressdigibooks.com/site/register</a></p>
			<b>CÓDIGO(S):</b>
			<ol>
				<li>{{ codigo.code1 }}</li>
				<li>{{ codigo.code2 }}</li>
			</ol>
			<p>Descarga la <a href="https://dl.dropbox.com/s/8lwce27hv6bc69s/ep-digibooks.pdf"><b>Guia</b></a> para poder acceder al libro</p>
        </div>
        <div v-if="message == 'CENGAGE'">
            <b>TU CÓDIGO ES: {{ codigo.code1 }}</b>
			<p>Descarga la <a href="https://www.dropbox.com/scl/fi/ll36bj04rl2wairj0fppe/Infograf-a-Spark.pdf?rlkey=7khpzr4v4979ebe9rn8p7dstb&st=7c6dxkcx&dl=1"><b>infografía</b></a> para poder acceder al libro.</p>
        </div>
        <div v-if="message == 'RICHMOND'">
            <p>Revisa con tu profesor para poder acceder a tu libro.</p>
			<b>TU CÓDIGO ES: {{ codigo.code1 }}</b>
			<p>Descarga el <a href="https://dl.dropbox.com/s/r4gj72fr5nmix60/student-book_richmond.pdf"><b>instructivo</b></a> para poder acceder al libro.</p>
        </div>
        <div v-if="message == 'CLE'">
            <p>Ingresar a: https://biblio.manuel-numerique.com/</p>
			<ol>
				<li><b>TU CÓDIGO ES: {{ code.code1 }}</b></li>
				<li>TU USUARIO ES: {{ code.code2 }}</li>
				<li>TU CONTRASEÑA ES: {{ code.code3 }}</li>
			</ol>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['message', 'email', 'book']
    }
</script>