<template>
<div class="container">
    <card-component titulo="Busca de Marcas">
        <template v-slot:body>
            <search-component tipo-id="marca" tipo="Marca">
            </search-component>
        </template>
        <template v-slot:footer>
            <button type="submit" class="btn btn-primary float-right">Buscar</button>
        </template>
    </card-component>
    <card-component titulo="Lista de registros">
        <template v-slot:body>
            <list-records-component>
            </list-records-component>
        </template>
        <template v-slot:footer>
            <button type="submit" class="btn btn-primary float-right" data-toggle="modal" :data-target="'#'+modalId">Adicionar</button>
        </template>
    </card-component>
    <modal-component :modal-id="modalId" titulo="Adicionar Marca">
        <template v-slot:body>
            <div class="form-group">
                <div class="form-group mb-3">
                    <label for="marca">Marca</label>
                    <input type="text" class="form-control" id="marca" name="marca" placeholder="Informa a Marca">
                </div>
                <div class="form-group mb-3">
                    <label for="imagem">Imagem</label>
                    <input type="file" class="form-control-file" id="imagem" name="imagem">
                </div>
            </div>
        </template>
        <template v-slot:footer>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Descartar</button>
            <button type="button" class="btn btn-primary">Criar Marca</button>
        </template>
    </modal-component>
</div>
</template>
<script>
export default {
    data() {
        return {
            modalId: 'registra-marca'
        }
    },
}
</script>
