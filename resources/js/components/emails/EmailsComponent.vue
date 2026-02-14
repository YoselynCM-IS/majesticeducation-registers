<template>
    <div>
        <b-row>
            <b-col sm="3">
                <b-list-group>
                    <b-list-group-item href="#" @click="getEmails('sent')">
                        <i class="fa fa-send"></i> Enviados
                    </b-list-group-item>
                    <b-list-group-item href="#" @click="getEmails('failed')">
                        <i class="fa fa-close"></i> Fallidos
                    </b-list-group-item>
                    <b-list-group-item href="#" variant="warning" @click="getRegistros()">
                        <i class="fa fa-exclamation-circle"></i> Por enviar
                    </b-list-group-item>
                </b-list-group>
            </b-col>
            <b-col>
                <div v-if="!load">
                    <div v-if="viewEmails">
                        <b-row class="mb-2">
                            <b-col>
                                <b-form-input v-model="querySearch" :disabled="load || status == null" @keyup="http_searchEmail()" placeholder="Buscar correo..."></b-form-input>
                            </b-col>
                            <b-col sm="3">
                                <!-- PAGINACIÓN -->
                                <pagination size="small" :limit="1" :data="emails"
                                    @pagination-change-page="getResults">
                                    <span slot="prev-nav"><i class="fa fa-angle-left"></i></span>
                                    <span slot="next-nav"><i class="fa fa-angle-right"></i></span>
                                </pagination>
                            </b-col>
                        </b-row>
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
                    <div v-else>
                        <b-row>
                            <b-col>
                                <b-form-input v-model="school" @keyup="showSchoolsActive()" :disabled="load" placeholder="Buscar por escuela..."></b-form-input>
                                <div class="list-group" v-if="schools.length" id="listR">
                                    <a href="#" v-bind:key="i" class="list-group-item list-group-item-action" 
                                        v-for="(school, i) in schools" 
                                        @click="selectSchool(school)">
                                        {{ school.name }}
                                    </a>
                                </div>
                            </b-col>
                            <b-col sm="3">
                                <!-- PAGINACIÓN -->
                                <pagination size="small" :limit="1" :data="registros"
                                    @pagination-change-page="http_registros">
                                    <span slot="prev-nav"><i class="fa fa-angle-left"></i></span>
                                    <span slot="next-nav"><i class="fa fa-angle-right"></i></span>
                                </pagination>
                            </b-col>
                        </b-row>
                        <b-row class="mt-2 mb-2">
                            <b-col sm="3" class="text-right">
                                <b-button @click="selectAllRows" pill size="sm" block :variant="selectAll ? 'success':'secondary'"
                                    :disabled="registros.data.length == 0">
                                    Seleccionar {{ selected.length }}
                                </b-button>
                            </b-col>
                            <b-col sm="3" class="text-right">
                                <b-button pill size="sm" variant="dark" block @click="sendEmails()"
                                    :disabled="registros.data.length == 0 || selected.length == 0">
                                    <i class="fa fa-send"></i> Enviar correos
                                </b-button>
                            </b-col>
                        </b-row>
                        <b-table v-if="registros.data.length > 0" :items="registros.data" :fields="fields" responsive
                            ref="selectableTable" selectable :select-mode="selectMode" @row-selected="onRowSelected">
                            <template v-slot:cell(index)="data">
                                {{ data.index + 1 }}
                            </template>
                            <template v-slot:cell(name)="data">
                                {{ data.item.name }} 
                                <b-badge :variant="data.item.validate == 'NO ENVIADO' ? 'warning':'secondary'">{{ data.item.validate }}</b-badge>
                            </template>
                            <template v-slot:cell(selected)="{ rowSelected }">
                                <template v-if="rowSelected">
                                    <b-icon-check-square-fill></b-icon-check-square-fill>
                                </template>
                                <template v-else>
                                    <b-icon-square></b-icon-square>
                                </template>
                            </template>
                        </b-table>
                        <b-alert v-else show variant="light">No se han encontrado correos pendientes.</b-alert>
                    </div>
                    
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
    import searchAllSchoolMixin from '../../mixins/searchAllSchoolMixin';
    export default {
        props: ['sistemname'],
        components: {DetailsEmailComponent, LoadComponent},
        mixins: [searchAllSchoolMixin],
        data(){
            return {
                load: false,
                emails: { data:[] },
                email: null,
                querySearch: null,
                status: 'sent',
                general: true,
                registros: { data:[] },
                viewEmails: true,
                fields: [
                    { key: 'index', label: 'N.' },
                    { key: 'school.name', label: 'Escuela' },
                    { key: 'name', label: 'Alumno' },
                    { key: 'book', label: 'Libro' }
                ],
                selectMode: 'multi',
                selected: [],
                school_id: 0,
                selectAll: false
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
                this.viewEmails = true;
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
            },
            // OBTENER REGISTROS QUE NO SE HAN ENVIADO CORREO
            getRegistros(){
                this.set_variables(0, null);
                this.http_registros();
            },
            // OBTENER REGISTROS QUE ESTAN PENDIENTES DE ENVIAR CORREO
            http_registros(page = 1){
                this.load = true;
                this.selected = [];
                this.selectAll = false;
                axios.get(`/student/by_school_check?page=${page}`, {params: {school_id: this.school_id}}).then(response => {
                    this.registros = response.data;
                    this.load = false;
                }).catch(error => {
                    this.load = false;
                });
            },
            // SELECCIONAR ESCUELA
            selectSchool(school){
                this.set_variables(school.id, school.name);
                this.http_registros();
            },
            // ASIGNAR VARIABLES
            set_variables(school_id, school_name){
                this.school_id = school_id;
                this.school = school_name;
                this.schools = [];
                this.viewEmails = false;
                this.emails = { data:[] };
            },
            onRowSelected(items) {
                this.selected = items
            },
            selectAllRows(){
                this.selectAll = !this.selectAll;
                if(this.selectAll) this.$refs.selectableTable.selectAllRows();
                else this.$refs.selectableTable.clearSelected()
            },
            sendEmails(){
                this.load = true;
                let form = { selected: this.selected }
                axios.put('/student/send_emails', form).then(response => {
                    this.selected = [];
                    this.selectAll = false;
                    this.http_registros();
                }).catch(error => {
                    // PENDIENTE
                    this.load = false;
                });
            }
        }
    }
</script>

<style scoped>
    #listR{
        position: absolute;
        z-index: 100;
    }
</style>

