<template>
    <div>
        <b-form @submit.prevent="!edit ? onSubmit():onUpdate()">
            <b-form-group label="Nombre de la escuela:">
                <b-form-input v-model="school.name" required :disabled="load"
                    style="text-transform:uppercase;">
                </b-form-input>
                <div v-if="errors && errors.name" class="text-danger">La escuela ya se encuentra registrada.</div>
            </b-form-group>
            <div class="text-right">
                <b-button pill :disabled="load" id="btnPre" type="submit">
                    <b-icon-plus-circle></b-icon-plus-circle> Guardar
                </b-button>
            </div>
        </b-form>
        
        <!-- REFERENCIAS -->
        <div v-if="school.id !== null">
            <hr>
            <b>REFERENCIAS</b>
            <b-alert v-model="message.status" variant="success" dismissible>
                {{ message.message }}
            </b-alert>
            <b-table :items="school.referencias" :fields="fields" responsive>
                <template #thead-top="row">
                    <tr>
                        <th>
                            <b-button pill variant="secondary" size="sm" :disabled="load"
                                @click="cleanReferencia()">
                                <b-icon-x></b-icon-x>
                            </b-button>
                        </th>
                        <th>
                            <b-form-input v-model="referencia.referencia" :disabled="load || state" required
                                style="text-transform:uppercase;">
                            </b-form-input>
                        </th>
                        <th>
                            <b-form-select v-model="referencia.tipo" :disabled="load" :options="tipos" required @change="set_referencia()"></b-form-select>
                        </th>
                        <th>
                            <b-button pill id="btnPre" size="sm" :disabled="load"
                                @click="saveReferencia()">
                                <b-icon-check></b-icon-check>
                            </b-button>
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        <th><div v-if="errors && errors.referencia" class="text-danger">Mínimo 5 caracteres.</div></th>
                        <th><div v-if="errors && errors.tipo" class="text-danger">*Obligatorio</div></th>
                        <th></th>
                    </tr>
                </template>
                <template v-slot:cell(index)="data">
                    {{ data.index + 1 }}
                </template>
                <template v-slot:cell(actions)="data">
                    <b-button pill variant="warning" class="text-white" size="sm"
                        @click="editReferencia(data.index, data.item)" :disabled="load || position == data.index">
                        <b-icon-pencil-square></b-icon-pencil-square>
                    </b-button>
                    <b-button pill variant="danger" class="text-white" size="sm"
                        @click="deleteReferencia(data.index, data.item)" :disabled="load">
                        <b-icon-x></b-icon-x>
                    </b-button>
                </template>
            </b-table>
        </div> 
    </div>
</template>

<script>
export default {
    props: ['school', 'edit'],
    data(){
        return {
            load: false,
            errors: {},
            fields: [
                {key:'index', label:'N.'},
                {key:'referencia', label:'Referencia'},
                {key:'tipo', label:'Tipo'},
                {key:'actions', label:''}
            ],
            referencia: {
                id: null,
                school_id: null,
                referencia: null,
                tipo: null
            },
            position: null,
            tipos: [
                { value: null, text: 'Selecciona una opción', disabled: true },
                { value: 'CIE', text: 'CIE' },
                { value: 'cuenta', text: 'CUENTA' }
            ],
            message: {
                status: false,
                message: null
            },
            state: false
        }
    },
    methods: {
        onSubmit(){
            this.load = true;
            axios.post('/schools/new_school', this.school).then(response => {
                this.load = false;
                this.errors = {};
                this.$emit('updateSchools', response.data);
            })
            .catch(error => {
                this.load = false;
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                } else {
                    swal("Ocurrió un problema", "Ocurrió un problema al guardar la escuela, por favor verifica tu conexión a internet e intenta de nuevo. Si el error persiste refresca la pagina y vuelve acceder al sistema.", "warning");
                }
            });
        },
        onUpdate(){
            this.load = true;
            axios.put('/schools/update_school', this.school).then(response => {
                this.load = false;
                this.errors = {};
                this.$emit('updateSchools', response.data);
            })
            .catch(error => {
                this.load = false;
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                } else {
                    swal("Ocurrió un problema", "Ocurrió un problema al actualizar la escuela, por favor verifica tu conexión a internet e intenta de nuevo. Si el error persiste refresca la pagina y vuelve acceder al sistema.", "warning");
                }
            });
        },
        // REFERENCIAS
        // EDITAR REFERENCIA
        editReferencia(i, referencia){
            this.referencia.id = referencia.id;
            this.referencia.school_id = referencia.school_id;
            this.referencia.referencia = referencia.referencia;
            this.referencia.tipo = referencia.tipo;
            this.position = i;
            this.set_referencia();
        },
        // ELIMINAR REFERENCIA
        deleteReferencia(i, referencia){
            this.load = true;
            axios.delete('/schools/delete_referencia', {params: {referencia_id: referencia.id}}).then(response => {
                this.school.referencias.splice(i, 1);
                this.message.message = 'La referencia se elimino';
                this.message.status = true;
                this.load = false;
            }).catch(error => {
                this.load = false;
            });
        },
        // GUARDAR REFERENCIA
        saveReferencia(){
            this.load = true;
            if(this.position == null){  
                this.referencia.school_id = this.school.id;
                axios.post('/schools/save_referencia', this.referencia).then(response => {
                    this.load = false;
                    this.errors = {};
                    this.school.referencias.push(response.data);
                    this.message.message = 'La referencia se guardo';
                    this.message.status = true;
                    this.cleanReferencia();
                }).catch(error => {
                    this.result_catch(error);
                });
            } else {
                axios.put('/schools/update_referencia', this.referencia).then(response => {
                    this.load = false;
                    this.errors = {};
                    this.school.referencias[this.position].referencia = response.data.referencia;
                    this.school.referencias[this.position].tipo = response.data.tipo;
                    this.message.message = 'La referencia se actualizo';
                    this.message.status = true;
                    this.cleanReferencia();
                }).catch(error => {
                    this.result_catch(error);
                });
            }
            
        },
        // PARA OCUPAR EN AGREGAR/EDITAR REFERECNIA
        result_catch(error){
            this.load = false;
            if (error.response.status === 422) {
                this.errors = error.response.data.errors || {};
            } else {
                swal("Ocurrió un problema", "Ocurrió un problema al guardar la escuela, por favor verifica tu conexión a internet e intenta de nuevo. Si el error persiste refresca la pagina y vuelve acceder al sistema.", "warning");
            }
        },
        // LIMPIAR CAMPOS
        cleanReferencia(){
            this.referencia.id = null;
            this.referencia.school_id = null;
            this.referencia.referencia = null;
            this.referencia.tipo = null;
            this.position = null;
            this.state = false;
        },
        // ASIGNAR REFERENCIA POR TIPO CUENTA
        set_referencia(){
            this.state = false;
            if(this.referencia.tipo == 'cuenta'){
                this.referencia.referencia = 'CUENTA';
                this.state = true;
            }
        }
    }
}
</script>