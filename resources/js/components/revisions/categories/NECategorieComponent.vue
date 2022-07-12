<template>
    <div>
        <b-form @submit.prevent="onSubmit">
            <b-form-group label="Nombre de la categoria:">
                <b-form-input v-model="form.categorie" required :disabled="load"
                    style="text-transform:uppercase;">
                </b-form-input>
            </b-form-group>
            <div class="text-right">
                <b-button pill :disabled="load" id="btnPre" type="submit">
                    <b-icon-plus-circle></b-icon-plus-circle> Guardar
                </b-button>
            </div>
        </b-form>
    </div>
</template>

<script>
export default {
    props: ['form', 'edit'],
    data(){
        return {
            load: false
        }
    },
    methods: {
        onSubmit(){
            this.load = true;
            if(!this.edit){
                axios.post('/revisions/save_categorie', this.form).then(response => {
                    this.$emit('save_categorie', response.data);
                    this.load = false;
                }).catch(error => {
                    // PENDIENTE
                    this.load = false;
                });
            } else {
                axios.put('/revisions/update_categorie', this.form).then(response => {
                    this.$emit('save_categorie', response.data);
                    this.load = false;
                }).catch(error => {
                    // PENDIENTE
                    this.load = false;
                });
            }
        }
    }
}
</script>

<style>

</style>