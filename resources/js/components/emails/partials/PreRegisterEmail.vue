<template>
    <div>
        <p>{{ message}}</p>
        <div v-if="email.student.check == 'accepted'">
            <div v-if="book.includes('PACK') || email.student.school_id == 39 || email.student.school_id == 54">
                <book-fisico :book="book"></book-fisico>
            </div>
            <div v-else>
                <p v-if="book.includes('DIGITAL')">
                    Aproximadamente de 24 a 72 horas hábiles recibirás por correo tu código del libro <b>{{ book }}</b>.
                </p>
                <div v-else>
                    <p v-if="email.student.school_id == 13">
                        Para recoger tu libro <b>{{ book }}</b>, acudir al <b>INSTITUTO TECNOLOGICO DE MINATITLAN</b>.
                    </p>
                    <div v-else>
                        <book-fisico :book="book"></book-fisico>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BookFisico from './BookFisico.vue';
    export default {
        props: ['message', 'email', 'book'],
        components: {BookFisico},
    }
</script>