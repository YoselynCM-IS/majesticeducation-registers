export default {
    data(){
        return {
            typesCompleto: [
                { value: null, text: 'Selecciona una opción', disabled: true },
                { value: 'ventanilla', text: 'DEPOSITO EN VENTANILLA'},
                { value: 'practicaja', text: 'DEPOSITO EN PRACTICAJA'},
                { value: 'transferencia', text: 'TRANSFERENCIA'}
            ],
            types: [
                { value: null, text: 'Selecciona una opción', disabled: true },
                { value: 'ventanilla', text: 'DEPOSITO EN VENTANILLA'},
                { value: 'practicaja', text: 'DEPOSITO EN PRACTICAJA'}
            ],
            typesBAzteca: [
                { value: null, text: 'Selecciona una opción', disabled: true },
                { value: 'BANCO AZTECA', text: 'BANCO AZTECA'}
            ],
            typesBancomer: [
                { value: null, text: 'Selecciona una opción', disabled: true },
                { value: 'practicaja', text: 'DEPOSITO EN PRACTICAJA'},
                { value: 'transferencia', text: 'TRANSFERENCIA'}
            ]
        }
    }
}