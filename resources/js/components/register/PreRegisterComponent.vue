<template>
    <div>
        <h4 class="text-center"><b>Pre-registro</b></h4>
        <!-- TUTORIAL -->
        <b-card class="mb-3 mt-2">
            <h6>
                <b>
                    Si necesitas ayuda para realizar tu pre-registro, puedes descargar este 
                    <a href="/student/download_tutorial">tutorial</a> para poder guiarte.
                </b>
            </h6>
            <hr>
            <p>Acceder con alguno de los siguientes exploradores:</p>
            <ul>
                <li><b>Firefox</b></li>
                <li><b>Chrome</b></li>
            </ul>
            <hr>
            <p>Al comprar tu libro con nosotros te ofrecemos la garantía y seguridad de que te será entregado. </p>
            <p>
                Te invitamos a <b>NO</b> realizar la compra de tu libro en línea (Amazon, Mercado Libre, entre otras).
            </p>
        </b-card>
        <!-- NUMERO DE CUENTA AL CUAL SE DEPOSITO -->
        <b-row>
            <b-col sm="9">
                <b-form-group label="Numero de cuenta al que se realizó el deposito">
                    <b-form-input v-model="cuenta" :disabled="load || statusCuenta"
                        type="number" required
                    ></b-form-input>
                </b-form-group>
            </b-col>
            <b-col sm="3">
                <b-button class="mt-4" @click="checkCuenta()" pill
                    id="btnPre" block :disabled="load || statusCuenta">
                    <b-icon-arrow-right-circle-fill></b-icon-arrow-right-circle-fill> Continuar
                </b-button>
            </b-col>
        </b-row>
        <!-- FORM -->
        <form @submit="onSubmit" enctype="multipart/form-data" method="POST">
            <!-- <input type="hidden" name="_token" :value="csrf"> -->
            <!-- Datos del alumno -->
            <div>
                <h5><b>Datos del alumno</b></h5>
                <b-row>
                    <b-col>
                        <b-form-group label="Nombre (Completo)">
                            <b-form-input v-model="form.name" :disabled="load || !statusCuenta"
                                style="text-transform:uppercase;" required
                            ></b-form-input>
                            <div v-if="errors && errors.name" class="text-danger">
                                El campo Nombre es requerido y tiene que ser igual o mayor a 3 caracteres.
                            </div>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Apellidos (Ambos y completos)">
                            <b-form-input v-model="form.lastname" :disabled="load || !statusCuenta"
                                style="text-transform:uppercase;" required
                            ></b-form-input>
                            <div v-if="errors && errors.lastname" class="text-danger">
                                El campo Apellidos es requerido y tiene que ser igual o mayor a 5 caracteres.
                            </div>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col>
                        <b-form-group label="Correo electrónico:">
                            <b-form-input v-model="form.email"
                                type="email" required :disabled="load || !statusCuenta"
                            ></b-form-input>
                            <div v-if="errors && errors.email" class="text-danger">
                                <b>El campo Correo electrónico es requerido.</b>
                            </div>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Numero de teléfono">
                            <b-form-input v-model="form.telephone" :disabled="load || !statusCuenta" required
                                minlength="10" maxlength="10"
                            ></b-form-input>
                            <div v-if="errors && errors.telephone" class="text-danger">
                                El campo Numero de teléfono es requerido y tiene que ser igual a 10 digitos.
                            </div>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col>
                        <b-form-group label="Plantel:">
                            <b-form-select v-model="form.school"
                                :options="schools" @change="selectPlantel()"
                                required :disabled="load || !statusCuenta || !selBook"
                            ></b-form-select>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-alert v-if="form.email.length > 0" show variant="info">
                    <b-icon-info-circle></b-icon-info-circle> <b>Importante</b> <br>
                    <p>
                        Ingresa un correo electrónico que utilices habitualmente, en él te haremos llegar un correo donde te informaremos si tu pre-registro ha sido aceptado o rechazado. Y por favor verifica que este bien escrito.
                    </p>
                </b-alert>
                <hr>
            </div>
            <!-- Datos del libro -->
            <div>
                <h5><b>Datos del libro</b></h5>
                <b-row>
                    <b-col>
                        <b-form-group label="Libro:">
                            <b-form-select v-model="valueBook" @change="selectBook()"
                                :options="books" required :disabled="load || selSchool"
                            ></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col sm="2">
                        <b-form-group label="Numero de piezas">
                            <b-form-input v-model="form.quantity" @change="setQuantity()"
                                required type="number" :disabled="load || selBook"
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col sm="2">
                        <b-form-group label="Precio del libro">
                            ${{ form.price | numeral('0,0') }}
                        </b-form-group>
                    </b-col>
                    <b-col sm="2">
                        <b-form-group label="Total">
                            ${{ form.a_depositar | numeral('0,0') }}
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-alert v-if="valueBook !== null" show variant="info">
                    <b-icon-info-circle></b-icon-info-circle> <b>Importante</b> 
                    <label>
                        Verifica que el libro que seleccionaste es el correcto, una vez que tu pre-registro sea guardado no se podrá modificar y no habrá cambio de libro.
                    </label><br>
                    <label v-if="valueBook.name.includes('DIGITAL')">
                        Verifica que tu correo electrónico este bien escrito ya que si tu pre-registro es aceptado, en el te haremos llegar el código para acceder a tu libro. 
                    </label>
                    <label v-if="form.school == 13">
                        Si requieres el libro físico comunícate al <b>56 2741 1481</b> o al <b>56 2741 0930</b>.
                    </label>
                </b-alert>
                <hr>
            </div>
            <!-- Datos del libro solo para HORIZONTES DE CECYT -->
            <div v-if="form.school == 29 && valueBook !== null && valueBook.name.includes('HORIZONTES')">
                <b-row>
                    <b-col>
                        <b-form-group label="Nombre del maestro">
                            <b-form-input v-model="form.teacher" :disabled="load || selSchool"
                                style="text-transform:uppercase;" required
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Grupo">
                            <b-form-input v-model="form.group" :disabled="load || selSchool"
                                style="text-transform:uppercase;" required
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                </b-row>
                <hr>
            </div>
            <!-- Datos del pago -->
            <div v-if="statusCuenta && cuenta !== bancoppel1 && cuenta !== bancoppel2">
                <b-row>
                    <b-col><h5><b>Datos del pago</b></h5></b-col>
                    <b-col class="text-right">
                        <b-button variant="light" :disabled="load || selBook" @click="addComprobante()" pill>
                            <b-icon-plus-circle-fill></b-icon-plus-circle-fill> Agregar otro pago
                        </b-button>
                    </b-col>
                </b-row>
                <b-alert v-if="!selBook" variant="primary" show>
                    Los datos que ingresaras a continuación debes obtenerlos de tu comprobante de pago.
                </b-alert>
                <div v-for="(comprobante, i) in form.comprobantes" v-bind:key="i">
                    <div class="text-right" v-if="i > 0">
                        <b-button variant="secondary" pill @click="deleteComprobante(i)">
                            <b-icon-dash-circle-fill></b-icon-dash-circle-fill> Eliminar
                        </b-button>
                    </div>
                    <b-row>
                        <!-- TIPO DE PAGO -->
                        <b-col>
                            <div v-if="cuenta === bancomer1 || cuenta === bancomer2">
                                <b-form-group v-if="form.school != 52"
                                    label="Tipo de pago (BBVA BANCOMER):">
                                    <b-form-select v-model="comprobante.type" :disabled="load || selBook"
                                        :options="types" required>
                                    </b-form-select>
                                </b-form-group>
                                <b-form-group v-else label="Tipo de pago:">
                                    <b-form-select v-model="comprobante.type" :disabled="load || selBook"
                                        :options="typesTamazunchale" required>
                                    </b-form-select>
                                </b-form-group>
                            </div>
                            <b-form-group v-if="cuenta === banamex1 || cuenta === banamex2"
                                label="Tipo de pago (OXXO):">
                                <b-form-select v-model="comprobante.type" :disabled="load || selBook"
                                    :options="types_externa" required>
                                </b-form-select>
                            </b-form-group>
                            <b-form-group v-if="cuenta === banco_azteca1 || cuenta === banco_azteca2"
                                label="Tipo de pago (BANCO AZTECA):">
                                <b-form-select v-model="comprobante.type" :disabled="load || selBook"
                                    :options="typesChontalpa" required>
                                </b-form-select>
                            </b-form-group>
                        </b-col>
                        <!-- SELECCIONAR BANCO -->
                        <b-col v-if="comprobante.type == 'transferencia'">
                            <b-form-group label="Banco:" v-b-tooltip.hover title="Desde el cual se realizó el pago">
                                <b-form-select v-model="comprobante.bank" :disabled="load || selBook"
                                    :options="banks" required
                                ></b-form-select>
                            </b-form-group>
                            <b-input v-if="comprobante.bank === 'OTRO'" v-model="specifyBank" 
                                v-b-tooltip.hover title="Por favor escribe el nombre del banco"
                                placeholder="Nombre del banco" required style="text-transform:uppercase;"
                                @keyup="posicion = i">
                            </b-input>
                        </b-col>
                        <!-- FOLIO, REFERENCIA -->
                        <b-col>
                            <div v-if="comprobante.type !== 'transferencia'">
                                <b-form-group v-if="comprobante.type == 'oxxo'" label="Folio de venta">
                                    <b-form-input v-model="comprobante.folio" minlength="4"
                                        :disabled="load || selBook" required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group v-if="comprobante.type == 'practicaja'" label="Folio" 
                                    v-b-tooltip.hover title="Ingresar los cuatro números que aparecen en FOLIO en tu comprobante">
                                    <b-form-input v-model="comprobante.folio" minlength="4" maxlength="4"
                                        :disabled="load || selBook" required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group v-if="comprobante.type == 'ventanilla'" label="Movimiento" 
                                    v-b-tooltip.hover title="Ingresar lo que aparece en MOVIMIENTO en tu comprobante de pago">
                                    <b-form-input v-model="comprobante.folio" minlength="5"
                                        :disabled="load || selBook" required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group v-if="comprobante.type == 'BANCO AZTECA'" label="Número de autorización" 
                                    v-b-tooltip.hover title="Ingresar lo que aparece en Número de autorización en tu comprobante de pago">
                                    <b-form-input v-model="comprobante.folio" minlength="5"
                                        :disabled="load || selBook" required
                                    ></b-form-input>
                                </b-form-group>
                            </div>
                            <div v-if="comprobante.type === 'transferencia' && comprobante.bank !== null">
                                <b-form-group v-if="comprobante.bank === 'BANCOMER'" label="Folio">
                                    <b-form-input v-model="comprobante.folio" type="text" minlength="8" maxlength="10"
                                        :disabled="load || selBook" required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group v-if="comprobante.bank === 'BANCOPPEL'" label="Folio de operación"
                                    v-b-tooltip.hover title="En tu comprobante puede aparecer como Folio de operación o Clave de rastreo">
                                    <b-form-input v-model="comprobante.folio" type="text" minlength="24" maxlength="24"
                                        :disabled="load || selBook" required style="text-transform:uppercase;"
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group v-if="comprobante.bank !== 'BANCOPPEL' && comprobante.bank !== 'BANCOMER'" 
                                    label="Referencia" v-b-tooltip.hover title="En tu comprobante puede aparecer como Referencia, Referencia numérica o Numero de referencia">
                                    <b-form-input v-model="comprobante.folio" minlength="4"
                                        :disabled="load || selBook" required
                                    ></b-form-input>
                                </b-form-group>
                            </div>
                        </b-col>
                        <!-- CONCEPTO, AUTORIZACIÓN -->
                        <b-col>
                            <b-form-group v-if="comprobante.type == 'practicaja' || comprobante.type == 'oxxo'" label="Autorización">
                                <b-form-input v-model="comprobante.auto" :disabled="load || selBook"
                                    style="text-transform:uppercase;" required
                                ></b-form-input>
                            </b-form-group>
                            <div v-if="comprobante.type === 'transferencia' && comprobante.bank !== null">
                                <b-form-group v-if="comprobante.bank === 'BANCOMER'" label="Motivo de pago">
                                    <b-form-input v-model="comprobante.auto" type="text" minlength="3"
                                        :disabled="load || selBook" required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group v-if="comprobante.bank !== 'BANCOMER'" label="Concepto" 
                                    v-b-tooltip.hover title="En tu comprobante puede aparecer como Concepto, Concepto de pago o Concepto de tranferencia. Te solicitamos escribirlo tal y como aparece (ya sean letras mayúsculas, minúsculas y/o números)">
                                    <b-form-input v-model="comprobante.auto" type="text" minlength="3"
                                        :disabled="load || selBook" required
                                    ></b-form-input>
                                </b-form-group>
                            </div>
                            <b-form-group v-if="comprobante.type == 'BANCO AZTECA'" label="No. Operación" 
                                v-b-tooltip.hover title="Ingresar lo que aparece en No. Operación en tu comprobante de pago">
                                <b-form-input v-model="comprobante.auto" minlength="5"
                                    :disabled="load || selBook" required
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <!-- CLAVE DE RASTREO -->
                        <!-- <b-col>
                            <b-form-group v-if="comprobante.type === 'transferencia' && comprobante.bank !== null && comprobante.bank !== 'BANCOMER'"
                                label="Clave de rastreo">
                                <b-form-input v-model="comprobante.clave" type="text" minlength="10" maxlength="30"
                                    :disabled="load || selBook" required
                                ></b-form-input>
                            </b-form-group>
                        </b-col> -->
                    </b-row>
                    <b-row>
                        <b-col>
                            <b-form-group v-if="comprobante.type !== 'BANCO AZTECA' && comprobante.type !== 'transferencia' && comprobante.type !== null"
                                label="Plaza o sucursal donde se realizo el pago:">
                                <b-form-input v-model="comprobante.plaza" :disabled="load || selBook"
                                    style="text-transform:uppercase;" required
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col>
                            <b-form-group v-if="comprobante.type == 'practicaja' || comprobante.type == 'ventanilla'" 
                                :label="`Número de ${comprobante.type == 'practicaja' ? 'cajero':'sucursal'}`" >
                                <b-form-input v-model="comprobante.cajero" minlength="4" maxlength="4"
                                    :disabled="load || selBook" required
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col>
                            <b-form-group label="Importe"
                                v-b-tooltip.hover title="En tu comprobante puede aparecer como Importe, Monto o Cantidad">
                                <b-form-input v-model="comprobante.total" :disabled="load || selBook"
                                    required type="number" step="0.01" min="1" 
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col>
                            <b-form-group label="Fecha de pago"
                                title="En tu comprobante puede aparecer como Fecha de pago, Fecha y Hora, Fecha de aplicación o Fecha de operación">
                                <b-form-datepicker required :disabled="load || selBook" v-model="comprobante.date"></b-form-datepicker>
                            </b-form-group>
                        </b-col>
                    </b-row>
                </div>
                <hr>
            </div>
            <!-- Comprobante(s) -->
            <div>
                <h5><b>Comprobante(s)</b></h5>
                <p>Si realizaste mas de un pago, sube la foto donde aparezcan los comprobantes.</p>
                <b-form-group label="">
                    <input 
                        :disabled="load || selBook" type="file" id="archivoType" 
                        v-on:change="fileChange" required>
                        <!-- multiple="multiple"  -->
                    <label for="archivoType"><b-icon-upload></b-icon-upload> Seleccionar archivo</label>
                    <ol>
                        <li>Solo formato imagen: <b>.jpg</b> / <b>.png</b> / <b>.jpeg</b> / <b>.pdf</b></li>
                        <li>Tamaño máximo: <b>3 MB</b></li>
                    </ol>
                    <p v-if="form.file">
                        Comprobante: <b>{{ form.file.name }}</b>
                    </p>
                    <div v-if="errors && errors.file" class="text-danger">
                        El comprobante es obligatorio con un tamaño máximo de 3MB y solo formato jpg, png, jpeg ó pdf
                    </div>
                </b-form-group>
                <hr>
            </div>
            <!-- GUARDAR -->
            <div>
                <b-alert class="mt-2" v-if="load" show variant="warning">
                    <b-spinner small type="grow"></b-spinner> Guardando...<br>
                    Estamos guardando tus datos, por favor no cierres esta pagina hasta que terminemos.
                </b-alert>
                <b-row class="mt-3">
                    <b-col>
                        <b-alert show variant="info">
                            <b-icon-info-circle></b-icon-info-circle> <b>Importante</b> <br>
                            <p>
                                Antes de guardar tu pre-registro, verifica que tus datos sean correctos, ya que de lo contrario será rechazado.
                            </p>
                        </b-alert>
                    </b-col>
                    <b-col class="text-right" sm="3">
                        <b-button pill block :disabled="load || selBook" type="submit" id="btnPre">
                            <i class="fa fa-check-circle"></i> Guardar
                        </b-button>
                    </b-col>
                </b-row>
            </div>
        </form>
        <!-- CONTACTO -->
        <b-card class="mb-5">
            <strong>Horario de atención</strong><br>
            <ul>
                <li>Lunes a Viernes de 10:00 am - 5:00 pm</li>
                <li>Sábado de 10:00 am - 1:00 pm </li>
            </ul>
            <h6><strong>Contacto</strong></h6>
            <ul>
                <li>
                    <b-icon-telephone></b-icon-telephone> 56 2741 1481 <br>
                    <a target="_blank" href="https://wa.me/525627411481">
                        <b-icon-chat></b-icon-chat> Ir a WhatsApp
                    </a>
                </li>
                <li>
                    <b-icon-telephone></b-icon-telephone> 56 2741 0930 <br>
                    <a target="_blank" href="https://wa.me/525627410930">
                        <b-icon-chat></b-icon-chat> Ir a WhatsApp
                    </a>
                </li>
            </ul>
        </b-card>
    </div>
</template>

<script>
// SWEETALERT
import swal from 'sweetalert';
import banksMixin from '../../mixins/banksMixin';
import booksMixin from '../../mixins/booksMixin';
import typesMixin from '../../mixins/typesMixin';
export default {
    props: ['registers'],
    mixins: [banksMixin,booksMixin,typesMixin],
    data(){
        return {
            // csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            load: false,
            types_externa: [
                { value: null, text: 'Selecciona una opción', disabled: true },
                { value: 'oxxo', text: 'OXXO'}
            ],
            specifyBank: null,
            form: {
                name: '', lastname: '', email: '',
                telephone: null, school: null, book: null,
                quantity: 1, price: 0, a_depositar: 0,
                file: null,
                comprobantes: [{
                    type: null, folio: '', auto: '', clave: null, 
                    bank: null, total: null, date: null, plaza: '', cajero: ''
                }],
                teacher: null, group: null
            },
            optSchools: this.registers,
            schools: [],
            errors: {},
            selSchool: true,
            books: [],
            selBook: true,
            valueBook: null,
            position: null,
            a_depositar: null,
            cuenta: null,
            statusCuenta: false,
            errors: {},
            posicion: null,
            bancomer1: '0172427206',
            bancomer2: '012180001724272063',
            banamex1: '5204165073723995',
            banamex2: '002180701156103586',
            banco_azteca1: '09330153687444',
            banco_azteca2: '5343810206998814',
            bancoppel1: '19000207503',
            bancoppel2: '4169160498193532'
        }
    },
    filters: {
        
    },
    created: function(){
        this.schools.push({
            value: null,
            text: 'Selecciona una opción',
            disabled: true
        });
        this.optSchools.forEach(school => {
            this.schools.push({
                value: school.id,
                text: `${school.name}`
            });
        });
    },
    methods: {
        fileChange(e){
            // this.acum_total();
            
            var fileInput = document.getElementById('archivoType');
            var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
            
            if(allowedExtensions.exec(fileInput.value)){
                this.form.file = e.target.files[0];  
            } else {
                swal("Revisar formato de archivo", "Formato de archivo no permitido, solo puede ser en formato imagen (jpg, jpeg, png)", "warning");
            }

            // this.acum_total();
            // if(this.a_depositar >= this.form.a_depositar){
            //     var fileInput = document.getElementById('archivoType');
            //     var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
            //     // |\.pdf
            //     if(allowedExtensions.exec(fileInput.value)){
            //         let files = e.target.files || e.dataTransfer.files;
            //         if (!files.length) return;
                    
            //         if((this.form.files.length + files.length) <= this.form.comprobantes.length){
            //             for (let i = files.length - 1; i >= 0; i--) {
            //                 this.form.files.push(files[i]);
            //             }
                        
            //             document.getElementById("archivoType").value = [];
            //         }
            //         else {
            //             swal("Revisar registro de pagos", `Solo tienes ${this.form.comprobantes.length} pago(s) registrado(s). El número de archivos debe corresponder al número de pagos que registres.`, "warning");
            //         }
            //     } else {
            //         swal("Revisar formato de archivo", "Revisar formato de archivo", "Formato de archivo no permitido, solo puede ser en formato imagen (jpg, jpeg, png)", "warning");
            //     }
            // } else {
            //     swal("Revisar total", "El total de los datos de pago que registraste tiene que ser igual o mayor al total de tu compra. Por favor revisa y corrige para poder continuar.", "warning");
            //     this.load = false;
            // }
        },
        onSubmit(e){
            e.preventDefault();
            this.load = true;
            this.acum_total();
            if(this.cuenta === this.bancomer1 || this.cuenta === this.bancomer2 || 
                this.cuenta === this.banamex1 || this.cuenta === this.banamex2){
                if(this.a_depositar >= this.form.a_depositar && this.form.file){
                    this.save_all();
                } else {
                    swal("Revisar pago(s)", "Por favor revisa que el total de los datos de pago que registraste sea igual o mayor al total de tu compra.", "warning");
                    this.load = false;
                }
            } else {
                if(this.form.file){
                    this.save_all();
                } else {
                    swal("Comprobante", "Por favor sube tu comprobante de pago.", "warning");
                    this.load = false;
                }
            }
        },
        save_all(){
            let fd = this.attributes();
            axios.post('/student/preregister', fd).then(response => {
                if(response.data === 3){
                    swal("Guardado", "Tus datos han sido guardados correctamente. Aproximadamente en un lapso de 48 a 72 horas hábiles te haremos llegar un correo electrónico donde te notificaremos si tu pre-registro ha sido validado. Gracias.", "success")
                        .then((value) => {
                            location.href = '/student/register';
                        });
                }
                if(response.data === 1) {
                    swal("Pre-registro en proceso", "Tienes un pre-registro que continua en proceso para ser validado. Te haremos llegar un correo electrónico donde te notificaremos si tu pre-registro ha sido validado. Gracias.", "info");
                } 
                if(response.data === 2) {
                    swal("Pre-registro aceptado", "Tu pre-registro ya ha sido aceptado, si no te llego un correo electrónico de confirmación, por favor contáctanos al 56 2741 1481 o al 56 2741 0930", "info");
                }
                this.errors = {};
                this.load = false;
            }).catch(error => {
                if(error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                    swal("Revisa tus datos", "Por favor revisa que todos tus datos esten correctamente e intenta guardar de nuevo.", "warning");
                    this.load = false;
                } else {
                    let f = { error: error };
                    axios.put('/information/send_error', f).then(response => {
                        this.load = false;
                        this.function_swal();
                    }).catch(error => {
                        this.load = false;
                        this.function_swal();
                    });
                }
                // if(error.response.status === 419) {
                // swal("Ocurrió un problema", "Estamos teniendo problemas para guardar tu pre-registro, por favor no actualices la página y vuelve a presionar en Guardar. Si ya has intentando más de 3 veces por favor comunícate con nosotros. Gracias.", "warning")
            });
        },
        function_swal(){
            swal("Ocurrio un problema.", "Por favor intenta de nuevo guardar tu pre-registro.", "warning")
            .then((value) => {
                location.href = '/student/register';
            });
        },
        attributes(){
            let formData = new FormData();
            if(this.specifyBank !== null) this.form.comprobantes[this.posicion].bank = this.specifyBank.toUpperCase();
            formData.append('name', this.form.name);
            formData.append('lastname', this.form.lastname);
            formData.append('email', this.form.email);
            formData.append('telephone', this.form.telephone);
            formData.append('school', this.form.school); 
            formData.append('book', this.form.book);
            formData.append('quantity', this.form.quantity);
            formData.append('price', this.form.price);
            formData.append('a_depositar', this.form.a_depositar);
            formData.append('file', this.form.file);
            formData.append('teacher', this.form.teacher);
            formData.append('group', this.form.group);
            formData.append('comprobantes', JSON.stringify(this.form.comprobantes));
            // for (var i = 0; i < this.form.files.length; i++) {
            //     let files = this.form.files[i];
            //     formData.append('files[]', files);
            // }
            
            return formData;
        },
        selectPlantel(){
            this.selSchool = true;
            axios.get('/schools/get_books', {params: {school_id: this.form.school}}).then(response => {
                this.form.book = null;
                this.school_select(response.data);
                this.selSchool = false;
            }).catch(error => {
                this.selSchool = false;
            });
        },
        selectBook(){
            this.form.book = this.valueBook.name;
            this.form.quantity = 1;
            this.form.price = this.valueBook.pivot.price;
            this.form.a_depositar = parseFloat(this.form.price) * parseInt(this.form.quantity);
            this.selBook = false;
        },
        addComprobante(){
            this.form.comprobantes.push({
                type: null, folio: '', auto: '', clave: null,
                bank: null, total: null, date: null, plaza: '', file: null, cajero: ''
            });
        },
        setQuantity(){
            if(parseInt(this.form.quantity) < 1) this.form.quantity = 1;

            this.form.a_depositar = parseFloat(this.form.price) * parseInt(this.form.quantity);
        },
        acum_total(){
            this.a_depositar = 0;
            this.form.comprobantes.forEach(comprobante => {
                this.a_depositar += parseFloat(comprobante.total);
            });
        },
        deleteComprobante(i){
            this.form.comprobantes.splice(i, 1);
        },
        deleteFile(i){
            this.form.files.splice(i, 1);
        },
        checkCuenta(){
            // 0172427206
            if(this.cuenta === this.bancomer1 || this.cuenta === this.bancomer2 || 
                this.cuenta === this.banamex1 || this.cuenta === this.banamex2 ||
                this.cuenta === this.bancoppel1 || this.cuenta === this.banco_azteca1 ||
                this.cuenta === this.bancoppel2 || this.cuenta === this.banco_azteca2) {
                    this.statusCuenta = true;
            } 
            else {
                this.statusCuenta = false;
                swal("Numero de cuenta incorrecto", "El numero de cuenta que ingresaste no corresponde al nuestro.", "error");
            }

            if(this.cuenta === this.bancoppel1 || this.cuenta === this.bancoppel2) this.set_comprobante1('BANCOPPEL');
            // if(this.cuenta === this.banco_azteca1 || this.cuenta === this.banco_azteca2) this.set_comprobante1('BANCOAZTECA');
        },
        set_comprobante1(type_bank){
            this.form.comprobantes[0].type = type_bank;
            this.form.comprobantes[0].bank = type_bank;
            this.form.comprobantes[0].folio = 'REV. MANUAL ME';
            this.form.comprobantes[0].auto = 'REV. MANUAL ME';
            this.form.comprobantes[0].clave = 'REV. MANUAL ME';
            this.form.comprobantes[0].total = 0;
            this.form.comprobantes[0].date = '2021-09-09';
            this.form.comprobantes[0].plaza = 'REV. MANUAL ME';
            this.form.comprobantes[0].file = null;
        },
        validate_cta(){
            if(this.cuenta === this.banamex1 || this.cuenta === this.banamex2) return false
            return true;
        }
    }
}
</script>