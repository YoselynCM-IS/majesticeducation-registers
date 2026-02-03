<template>
    <div>
        <b-row class="mb-2">
            <b-col sm="3">
                <!-- PAGINACIÓN -->
                <pagination size="small" :limit="1" :data="emails"
                    @pagination-change-page="getResults">
                    <span slot="prev-nav"><i class="fa fa-angle-left"></i></span>
                    <span slot="next-nav"><i class="fa fa-angle-right"></i></span>
                </pagination>
            </b-col>
            <b-col>
                <b-form-input v-model="querySearch" :disabled="load || status == null" @keyup="http_searchEmail()" placeholder="Buscar correo..."></b-form-input>
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
                <div v-if="!load">
                    <div v-if="emails.data.length > 0">
                        <b-list-group v-for="(email, i) in emails.data" v-bind:key="i">
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
                </div>
                <load-component v-else></load-component>
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
    import LoadComponent from '../funciones/LoadComponent.vue';
    export default {
        props: ['sistemname'],
        components: {DetailsEmailComponent, LoadComponent},
        data(){
            return {
                load: false,
                emails: { data:[] },
                email: null,
                querySearch: null,
                status: 'sent',
                general: true
            }
        },
        mounted: function(){
            this.getResults();
        },
        methods: {
            // OBTENER RESULTADOS POR TIPO DE BUSQUEDA
            getResults(page = 1){
                if(this.general) this.http_byStatus(page);
                else this.http_searchEmail(page);
            },
            // OBTENER EMAILS ENVIADOS
            getEmails(status){
                this.status = status;
                this.querySearch = null;
                this.general = true;
                this.http_byStatus();
            },
            // HTTP DE EMAILS POR STATUS
            http_byStatus(page = 1){
                this.load = true;
                axios.get(`/emails/get_status?page=${page}`, {params: {status: this.status}}).then(response => {
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
            http_searchEmail(page = 1){
                if(this.querySearch.length > 3){
                    this.general = false;
                    axios.get(`/emails/search?page=${page}`, {params: {querySearch: this.querySearch, status: this.status}}).then(response => {
                        this.emails = response.data;
                    }).catch(error => { });
                }
            }
        }
    }
</script>