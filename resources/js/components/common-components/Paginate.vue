<template>
  <nav aria-label="Page navigation example">
    <ul class="pagination" v-if="paginacao">
      <li 
        v-for="paginas, key in paginacao.links" 
        :key="key" 
        class="page-item" 
        :class="(paginas.active ? 'active': '' ) + (paginas.url == null ? 'disabled' : '' )" 
      >
        <a 
          class="page-link" 
          href="#" 
          v-html="paginas.label" 
          @click="$emit('page_paginate', paginas.label)"
        />
      </li>
      <!-- <li class="page-item"><a class="page-link" href="#">Previous</a></li>
      <li class="page-item"><a class="page-link" href="#">1</a></li>
      <li class="page-item"><a class="page-link" href="#">2</a></li>
      <li class="page-item"><a class="page-link" href="#">3</a></li>
      <li class="page-item"><a class="page-link" href="#">Next</a></li> -->
    </ul>
  </nav>
</template>
<script>
export default {
  props: {
    paginacao: {
      type: Object,
    }
  }
}
</script>