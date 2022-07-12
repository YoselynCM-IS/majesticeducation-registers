<template>
    <div>
        <b-tabs pills card vertical end class="tab-ctgs"
            nav-wrapper-class="w-20">
            <b-tab v-for="(categorie, i) in categories" v-bind:key="i"
                :title="categorie.categorie" @click="show_registers(categorie.id)">
                <b-row>
                    <b-col>
                        <pagination size="default" :limit="1" :data="registersData" 
                            @pagination-change-page="getResults">
                            <span slot="prev-nav">
                                <b-icon-chevron-left></b-icon-chevron-left>
                            </span>
                            <span slot="next-nav">
                                <b-icon-chevron-right></b-icon-chevron-right>
                            </span>
                        </pagination>
                        <b-button v-if="registersData.data" variant="dark" pill
                            :href="`/revisions/download_categorie/${categorie.id}`">
                            <b-icon-download></b-icon-download> Descargar
                        </b-button>
                    </b-col>
                    <b-col sm="4" class="text-right">
                        <b-form-group label="Buscar alumno:">
                            <b-form-input v-model="sStudent" :disabled="load || !registersData.data" 
                                @keyup="showStudents(categorie.id)"
                                style="text-transform:uppercase;">
                            </b-form-input>
                            <div class="list-group" v-if="students.length" id="listR">
                                <a 
                                    href="#" v-bind:key="i" 
                                    class="list-group-item list-group-item-action" 
                                    v-for="(student, i) in students" 
                                    @click="selectStudent(student)">
                                    {{ student.name }}
                                </a>
                            </div>
                        </b-form-group>
                    </b-col>
                    <b-col sm="3" class="text-right">
                        <b-button variant="warning" pill size="sm" :disabled="categorie_id == null"
                            @click="editCorte(categorie, i)">
                            <i class="fa fa-pencil"></i>
                        </b-button>
                        <b-button v-if="role == 'manager'"
                            :disabled="categorie_id == null || registersData.total > 0"
                            variant="danger" pill size="sm" @click="deleteCorte(categorie)">
                            <i class="fa fa-close"></i>
                        </b-button>
                        <b-button v-if="role == 'manager'" :disabled="categorie_id == null"
                            variant="dark" pill size="sm" @click="archivarCorte(categorie)">
                            <i class="fa fa-archive"></i>
                        </b-button>
                    </b-col>
                </b-row>
                <div v-if="!load">
                    <b-table v-if="registersData.data"
                        :items="registersData.data" :fields="fields" 
                        responsive>
                        <template v-slot:cell(index)="data">
                            {{ data.index + 1 }}
                        </template>
                        <template v-slot:cell(created_at)="data">
                            {{ data.item.created_at | moment("YYYY-MM-DD hh:mm:ss") }}
                        </template>
                        <template v-slot:cell(information)="data">
                            <b-button pill id="btnPre" @click="showInfo(data.item)">
                                <b-icon-info-circle></b-icon-info-circle>
                            </b-button>
                        </template>
                    </b-table>
                    <b-card-text v-else class="text-center">
                        Presiona sobre la categoría para ver los registros.
                    </b-card-text>
                </div>
                <div v-else class="text-center text-danger my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Cargando...</strong>
                </div>
            </b-tab>
        </b-tabs>

        <!-- MODALS -->
        <!-- INFORMACION EL ESTUDIANTE -->
        <b-modal v-model="modalInfo" hide-footer :title="student.name" size="xl">
            <information-student :student="student"></information-student>
        </b-modal>
        <!-- EDITAR CORTE -->
        <b-modal v-model="modalNEC" title="Editar categoria" hide-footer>
            <ne-categorie-component :form="form" :edit="true"
                @save_categorie="save_categorie"></ne-categorie-component>
        </b-modal>
    </div>
</template>

<script>
export default {
    props: ['role'],
    data(){
        return {
            categories: [],
            registersData: {},
            fields: [
                { label: 'N.', key: 'index' },
                { label: 'Escuela', key: 'school.name' },
                { label: 'Alumno', key: 'name' },
                { label: 'Libro', key: 'book' },
                { label: 'Fecha de registro', key: 'created_at' },
                { label: 'Información', key: 'information' },
            ],
            student: {},
            modalInfo: false,
            categorie_id: null,
            sStudent: null,
            students: [],
            modalNEC: false,
            form: {
                id: null,
                categorie: null
            },
            position: null
        }
    },
    created: function (){
        this.show_categories();
        if(this.categorie_id !== null){
            this.getResults();
        }
    },
    methods: {
        // MOSTRAR PAGINADO
        getResults(page = 1){
            this.http_registers(page);
        },
        // MOSTRAR LAS CAEGORIAS
        show_categories(){
            this.load = true;
            axios.get('/revisions/show_categories')
                .then(response => {
                    this.categories = response.data;
                    this.load = false;   
                });
        },
        // MOSTRAR REGISTROS
        show_registers(categorie_id){
            this.registersData = {};
            this.categorie_id = categorie_id;
            this.sStudent = null;
            this.http_registers();
        },
        // HTTP DE REGISTERS
        http_registers(page = 1){
            this.load = true;
            axios.get(`/revisions/by_categorie?page=${page}`, {params: {categorie_id: this.categorie_id}})
                .then(response => {
                    this.registersData = response.data;
                    this.load = false;   
                });
        },
        // MOSTRAR INFORMACIÓN DEL ALUMNO
        showInfo(student){
            axios.get('/student/show_registers', {params: {student_id: student.id}}).then(response => {
                this.student = response.data;
                this.modalInfo = !this.modalInfo;
            }).catch(error => {
                // PENDIENTE
            });
        },
        // MOSTRAR ESTUDIANTES POR CATEGORIA
        showStudents(categorie_id){
            axios.get('/revisions/by_student', {params: {categorie_id: categorie_id, student: this.sStudent}}).then(response => {
                this.students = response.data;
            }).catch(error => {
                // PENDIENTE
            });
        },
        // ELEGIR ESTUDIANTE
        selectStudent(student){
            this.load = true;
            axios.get('/revisions/show_student', {params: {student_id: student.id}}).then(response => {
                this.students = [];
                this.registersData = response.data
                this.sStudent = student.name;
                this.load = false;
            }).catch(error => {
                // PENDIENTE
                this.load = false;
            });
        },
        // EDITAR CORTE
        editCorte(categorie, i){
            this.form.id = categorie.id;
            this.form.categorie = categorie.categorie;
            this.position = i;
            this.modalNEC = true;
        },
        // CATEGORIA MODIFICADA
        save_categorie(){
            this.categories[this.position].categorie = this.form.categorie;
            this.modalNEC = false;
            swal("Guardado", "La categoría se actualizo correctamente", "success");
        },
        // ELIMINAR CORTE
        deleteCorte(categorie){
            this.load = true;
            axios.delete('/revisions/delete_categorie', {params: {categorie_id: categorie.id}}).then(response => {
                swal("Eliminado", "El corte se elimino correctamente.", "success")
                .then((value) => {
                    location.reload();
                });
                this.load = false;
            }).catch(error => {
                this.load = false;
                swal("Ocurrió un problema", "Ocurrió un problema al eliminar el libro, por favor verifica tu conexión a internet e intenta de nuevo. Si el error persiste refresca la pagina y vuelve acceder al sistema.", "warning");
            });
        },
        // ARCHIVAR CORTE
        archivarCorte(categorie){
            this.load = true;
            let form = { categorie_id: categorie.id };
            axios.put('/revisions/archive_categorie', form).then(response => {
                swal("OK", "El corte se archivo correctamente.", "success")
                .then((value) => {
                    location.reload();
                });
                this.load = false;
            }).catch(error => {
                // PENDIENTE
                this.load = false;
                swal("Ocurrió un problema", "Ocurrió un problema al asignar el libro, por favor verifica tu conexión a internet e intenta de nuevo. Si el error persiste refresca la pagina y vuelve acceder al sistema.", "warning");
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