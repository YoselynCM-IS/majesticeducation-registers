<template>
    <div>
        <b-card>
            <label>para: {{ email.email }}</label><br>
            <label>fecha: {{ email.sent_at | moment("YYYY-MM-DD hh:mm:ss") }}</label>
        </b-card><br>
        <h6><b>Hola {{ name }}</b></h6>
	    <pre-register-email v-if="email.mailable.includes('PreRegister')" :message="message" :email="email" :book="book"></pre-register-email>
        <codes-email v-if="email.mailable.includes('SendCodes')" :message="message" :email="email" :book="book"></codes-email>
        <hr>
		<p>Por favor no respondas este correo, ya que solo es de envió y tus respuestas no serán leídas. Si tienes alguna duda o aclaración, contáctanos al siguiente número.</p>
		<hr>
		<h6><b>{{ from }}</b></h6>
		Dudas o Aclaraciones: <br>
		56 2741 1481 <br>
		56 2741 0930 <br>
		<strong>Horario de atención</strong><br>
		<ul>
			<li>Lunes a Viernes de 10:00 am - 5:00 pm</li>
			<li>Sábado de 10:00 am - 1:00 pm </li>
		</ul>
    </div>
</template>

<script>
    import PreRegisterEmail from './PreRegisterEmail.vue';
    import CodesEmail from './CodesEmail.vue';
    export default {
        props: ['email', 'from'],
        components: {PreRegisterEmail, CodesEmail},
        data(){
            return {
                message: JSON.parse(this.email.message).message,
                name: JSON.parse(this.email.message).name,
                book: JSON.parse(this.email.message).book
            }
        }
    }
</script>