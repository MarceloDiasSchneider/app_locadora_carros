<template>
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Marca</th>
        <th scope="col">Imagem</th>
        <th scope="col">Ação</th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="item in listar" :key="item.id">
        <th scope="row">{{ item.id }}</th>
        <th>{{ item.marca }}</th>
        <th>
          <img
            :src="'/storage/' + item.imagem"
            :alt="item.marca"
            width="30"
            heigth="30"
          />
        </th>
        <th>
          <button class="btn-sm btn-danger" @click="$emit('excluir-marcas', item.id)">Deletar</button>
        </th>
      </tr>
    </tbody>
  </table>
  
</template>

<script>
export default {
  props: {
    listar: {
      type: Array,
    },
    message: {
      type: String,
    },
  },
};
</script>
