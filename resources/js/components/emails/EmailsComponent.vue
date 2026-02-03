<template>
    <div>
        <b-row class="mb-2">
            <b-col sm="3"></b-col>
            <b-col>
                <b-form-input v-model="querySearch" :disabled="load || status == null" @keyup="searchEmail()" placeholder="Buscar correo..."></b-form-input>
            </b-col>
        </b-row>
        <b-row>
            <b-col sm="3">
                <b-list-group>
                    <b-list-group-item href="#" @click="getEmails('sent')">
                        <i class="fa fa-send"></i> Enviados
                    </b-list-group-item>
                    <b-list-group-item href="#" @click="getEmails('failed')">
                        <i class="fa fa-close"></i> Fallidos
                    </b-list-group-item>
                </b-list-group>
            </b-col>
            <b-col>
                <div v-if="emails.length > 0">
                    <b-list-group v-for="(email, i) in emails" v-bind:key="i">
                        <b-list-group-item href="#" @click="showEmail(email)">
                            <b-row>
                                <b-col>Para: {{ email.email }}</b-col>
                                <b-col>{{ email.subject }}</b-col>
                                <b-col sm="3">{{ email.sent_at | moment("YYYY-MM-DD hh:mm:ss") }}</b-col>
                            </b-row>
                        </b-list-group-item>
                    </b-list-group>
                </div>
                <b-alert v-else show variant="light">No se ha enviado ningún correo.</b-alert>
            </b-col>
        </b-row>

        <!-- MODALS -->
         <b-modal ref="modal-email" hide-footer size="lg">
            <template #modal-title><b>{{ email ? email.subject:null }}</b></template>
            <details-email-component :email="email" :from="sistemname"></details-email-component>
        </b-modal>
    </div>
</template>

<script>
    import DetailsEmailComponent from './partials/DetailsEmailComponent.vue';
    export default {
        props: ['sistemname'],
        components: {DetailsEmailComponent},
        data(){
            return {
                load: false,
                emails: [],
                email: null,
                querySearch: null,
                status: null,
            }
        },
        methods: {
            // OBTENER EMAILS ENVIADOS
            getEmails(status){
                this.load = true;
                this.status = status;
                this.querySearch = null;
                axios.get('/emails/get_status', {params: {status: status}}).then(response => {
                    this.emails = response.data;
                    this.load = false;
                }).catch(error => {
                    this.load = false;
                });
            },
            // OBTENER CORREO ENVIADO
            showEmail(email){
                this.load = true;
                this.email = null;
                axios.get('/emails/show', {params: {email_id: email.id}}).then(response => {
                    this.$refs['modal-email'].show();
                    this.email = response.data;
                    this.load = false;
                }).catch(error => {
                    this.load = false;
                });
            },
            // BUSCAR CORREO
            searchEmail(){
                if(this.querySearch.length > 3){
                    axios.get('/emails/search', {params: {querySearch: this.querySearch, status: this.status}}).then(response => {
                        this.emails = response.data;
                    }).catch(error => { });
                }
            }
        }
    }
</script>