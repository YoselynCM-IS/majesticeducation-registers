<template>
    <div>
        <p>Para hacerte entrega de tu libro <b>{{ book }}</b> favor de presentar:</p>
        <ul>
            <li>Este correo impreso.</li>
            <li>El comprobante original de tu pago.</li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['book']
    }
</script>