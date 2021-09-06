<template>
<table class="table table-striped  table-hover">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Marca</th>
            <th scope="col">Imagem</th>
        </tr>
    </thead>

    <tbody>
        <tr v-for="item in listar" :key="item.id">
            <th scope="row">{{ item.id }}</th>
            <th>{{ item.marca }}</th>
            <th><img :src="'/storage/'+item.imagem" :alt="item.marca" width="30" heigth="30"></th>
            <!-- <th><img :src="'/storage/images/marcas/39QHMdntshV9OkdZYanP1J6P12MQKne0hpSPn6Ng.png'" :alt="item.marca" width="30" heigth="30"></th> -->
        </tr>
    </tbody>
</table>
</template>

<script>
export default {
    props: {
        listar: {
            type: Array
        }
    }
}
</script>
