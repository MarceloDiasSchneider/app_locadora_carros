<template>
    <div class="form-group row">
        <div class="col-xs-12 col-sm-6 input-group mb-3">
            <span class="input-group-text" :id="'id-'+tipoId">ID</span>
            <input type="number" class="form-control" placeholder="Id do registro" aria-label="Id do registro">
        </div>
        <div class="col-xs-12 col-sm-6 input-group mb-3">
            <span class="input-group-text" :id="tipoId">{{ tipo }}</span>
            <input type="text" class="form-control" :placeholder="tipo">
        </div>
    </div>
</template>

<script>
export default {
    props: {
        tipoId: {
            type: String,
            required: true
        },
        tipo: {
            type: String,
            required: true
        }
    }
}
</script>
