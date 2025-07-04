<template>
    <div>
        <b-row class="mb-2">
            <b-col>
                <b-pagination v-model="currentPage" pills 
                    :per-page="perPage" :total-rows="schools.length">
                </b-pagination>
            </b-col>
            <b-col sm="3" class="text-right">
                <b-button variant="dark" pill href="/schools/download_relation">
                    <b-icon-download></b-icon-download> Relación Libros
                </b-button>
            </b-col>
            <b-col sm="3" class="text-right">
                <b-button pill id="btnPre" @click="newSchool()">
                    <b-icon-plus-circle></b-icon-plus-circle> Agregar escuela
                </b-button>
            </b-col>
        </b-row>
        <b-table :items="schools" :fields="fields"
            :per-page="perPage" :current-page="currentPage">
            <template v-slot:cell(index)="data">
                {{ data.index + 1 }}
            </template>
            <template v-slot:cell(actions)="data">
                <b-button pill variant="warning" class="text-white"
                    @click="editSchool(data.index, data.item)">
                    <b-icon-pencil-square></b-icon-pencil-square>
                </b-button>
                <!-- :disabled="data.item.students_count > 0 || data.item.books_count > 0" -->
                <b-button pill variant="danger" class="text-white"
                    @click="deleteSchool(data.index, data.item)">
                    <b-icon-x></b-icon-x>
                </b-button>
            </template>
            <template v-slot:cell(show)="data">
                <b-button pill id="btnPre" @click="showBooks(data.item)">
                    <b-icon-info-circle-fill></b-icon-info-circle-fill>
                </b-button>
            </template>
        </b-table>

        <!-- MODAL -->
        <b-modal ref="my-modal" hide-footer title="">
            <new-edit-school :school="school" :edit="edit" @updateSchools="updateSchools">
            </new-edit-school>
        </b-modal>
        <b-modal ref="modal-show" hide-footer :title="school_name">
            <b-alert
                :show="dismissCountDown" dismissible variant="success"
                @dismissed="dismissCountDown=0"
                @dismiss-count-down="countDownChanged">
                <b-icon-check></b-icon-check> El libro ha sido eliminado
            </b-alert>
            <b-table v-if="books.length > 0" :items="books" :fields="fieldsB">
                <template v-slot:cell(index)="data">
                    {{ data.index + 1 }}
                </template>
                <template v-slot:cell(price)="data">
                    ${{ data.item.pivot.price | numeral('0,0') }}
                </template>
                <template v-slot:cell(remove)="data">
                    <b-button variant="danger" pill size="sm" :disabled="load"
                        @click="removeBook(data.item)">
                        <b-icon-x></b-icon-x>
                    </b-button>
                </template>
            </b-table>
            <b-alert v-else show variant="dark" class="text-center">
                <b-icon-info-circle></b-icon-info-circle> No se han asignado libros
            </b-alert>
        </b-modal>
    </div>
</template>

<script>
// SWEETALERT
import swal from 'sweetalert';
export default {
    props: ['registers'],
    data(){
        return {
            currentPage: 1,
            perPage: 25,
            schools: this.registers,
            fields: [
                {key:'index', label:'N.'},
                {key:'name', label:'Escuela'},
                {key:'referencia', label:'Referencia'},
                {key:'show', label:'Libros'},
                {key:'actions', label:'Editar / Eliminar'}
            ],
            school: { id: null, name: '', referencia: null },
            position: null,
            edit: false,
            books: [],
            fieldsB: [
                {key:'index', label:'N.'},
                {key:'name', label:'Libro'},
                {key:'price', label:'Precio'},
                {key:'remove', label:'Quitar'},
            ],
            b_school: null,
            load: false,
            dismissCountDown: 0,
            school_name: ''
        }
    },
    methods: {
        newSchool(){
            this.school = { id: null, name: '', referencia: null };
            this.edit = false;
            this.$refs['my-modal'].show();
        },
        updateSchools(school){
            if(!this.edit){
                this.schools.unshift(school);
                swal("Guardado", "La escuela se guardo correctamente.", "success");
            } else {
                this.schools[this.position].name = school.name;
                this.schools[this.position].referencia = school.referencia;
                swal("Actualizado", "La escuela se actualizo correctamente.", "success");
            }
            this.$refs['my-modal'].hide();
        },
        editSchool(position, school){
            this.school = { id: school.id, name: school.name, referencia: school.referencia };
            this.position = position;
            this.edit = true;
            this.$refs['my-modal'].show();
        },
        deleteSchool(position, school){
            this.load = true;
            axios.delete('/schools/delete', {params: {school_id: school.id}}).then(response => {
                this.schools.splice(position, 1);
                this.load = false;
                swal("Eliminada", "La escuela se elimino correctamente.", "success");
            }).catch(error => {
                // PENDIENTE
                this.load = false;
                swal("Ocurrió un problema", "Ocurrió un problema al eliminar la escuela, por favor verifica tu conexión a internet e intenta de nuevo. Si el error persiste refresca la pagina y vuelve acceder al sistema.", "warning");
            });
        },
        showBooks(school){
            this.books = [];
            axios.get('/schools/get_books', {params: {school_id: school.id}}).then(response => {
                this.books = response.data;
                this.school_name = school.name;
                this.b_school = school.id;
                this.$refs['modal-show'].show();
            }).catch(error => {
                // PENDIENTE
            });
        },
        countDownChanged(dismissCountDown) {
            this.dismissCountDown = dismissCountDown
        },
        removeBook(book){
            this.load = true;
            axios.delete('/schools/remove_book', {params: {book_id: book.id, school_id: this.b_school}}).then(response => {
                this.books = response.data;
                this.dismissCountDown = 5;
                this.load = false;
            }).catch(error => {
                // PENDIENTE
                this.load = false;
                swal("Ocurrió un problema", "Ocurrió un problema al eliminar el libro, por favor verifica tu conexión a internet e intenta de nuevo. Si el error persiste refresca la pagina y vuelve acceder al sistema.", "warning");
            });
        }
    }
}
</script>