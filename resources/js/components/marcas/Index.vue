<template>
  <div class="container">
    <card-component titulo="Busca de Marcas">
      <template v-slot:body>
        <div class="form-group row">
          <div class="col-xs-12 col-sm-6 input-group mb-3">
            <span class="input-group-text" id="'id-marca">ID</span>
            <input
              type="number"
              v-model="filterId"
              class="form-control"
              placeholder="Id do registro"
              aria-label="Id do registro"
            />
          </div>
          <div class="col-xs-12 col-sm-6 input-group mb-3">
            <span class="input-group-text" id="marca">Marca</span>
            <input
              type="text"
              v-model="filterMarca"
              class="form-control"
              :placeholder="tipo"
            />
          </div>
        </div>
      </template>
      <template v-slot:footer>
        <button type="submit" class="btn btn-primary float-right">
          Buscar
        </button>
      </template>
    </card-component>
    <card-component titulo="Lista de registros">
      <template v-slot:body>
        <list-records-component :listar="filtroMarcas">
        </list-records-component>
      </template>
      <template v-slot:footer>
        <button
          type="submit"
          class="btn btn-primary float-right"
          data-toggle="modal"
          :data-target="'#' + modalId"
        >
          Adicionar
        </button>
      </template>
    </card-component>
    <modal-component :modal-id="modalId" titulo="Adicionar Marca">
      <template v-slot:alert>
        <alert-component
          v-if="tipo"
          :tipo="tipo"
          :message="message"
        ></alert-component>
      </template>
      <template v-slot:body>
        <div class="form-group">
          <div class="form-group mb-3">
            <label for="marca">Marca</label>
            <input
              type="text"
              class="form-control"
              id="marca"
              name="marca"
              v-model="marca"
              required
              placeholder="Informa a Marca"
            />
          </div>
          <div class="form-group mb-3">
            <label for="imagem">Imagem</label>
            <input
              type="file"
              class="form-control-file"
              id="imagem"
              name="imagem"
              required
              @change="carregarImagem($event)"
            />
          </div>
        </div>
      </template>
      <template v-slot:footer>
        <button
          type="button"
          class="btn btn-secondary"
          data-dismiss="modal"
          @click="descartar"
        >
          Descartar
        </button>
        <button type="button" class="btn btn-primary" @click="criarMarca">
          Criar Marca
        </button>
      </template>
    </modal-component>
  </div>
</template>
<script>
import Alert from "../common-components/Alert.vue";
export default {
  components: { Alert },
  data() {
    return {
      modalId: "registra-marca",
      url: "http://localhost:8000/api/v1/marca",
      marca: null,
      arquivoImagem: [],
      tipo: null,
      message: null,
      marcas: [],
      filterId: null,
      filterMarca: null,
    };
  },
  computed: {
    token() {
      return document.cookie
        .split("; ")
        .find((row) => row.startsWith("token="))
        .split("=")[1];
    },
    filtroMarcas() {
      let filter = [];
      if (this.filterId) {
        let filtered = this.marcas.find((marca) => marca.id == this.filterId);
        if (filtered !== undefined) filter.push(filtered);
      }
      if (this.filterMarca) {
        let filtered = this.marcas.find(
          (marca) => marca.marca == this.filterMarca
        );
        if (filtered !== undefined) filter.push(filtered);
      }
      if (!this.filterMarca && !this.filterId) {
        return this.marcas;
      } else if (filter.length) {
        return filter;
      } else {
        return filter.push({
          id: 0,
          marca: "Não encontrada",
          image: "not found",
        });
      }
    },
  },
  methods: {
    carregarMarcas() {
      const config = {
        headers: {
          Authorization: "bearer " + this.token,
          Accept: "application/json",
        },
      };
      axios
        .get(this.url, config)
        .then((response) => {
          this.marcas = response.data.marcas;
        })
        .catch((erro) => {
          console.log(erro);
        });
    },
    carregarImagem(e) {
      this.arquivoImagem = e.target.files;
    },
    criarMarca() {
      let formData = new FormData();
      formData.append("marca", this.marca);
      formData.append("imagem", this.arquivoImagem[0]);
      const config = {
        headers: {
          Authorization: "bearer " + this.token,
          "Content-Type": "multipart/form-data",
          Accept: "application/json",
        },
      };
      axios
        .post(this.url, formData, config)
        .then((response) => {
          this.message = `A marca ${response.data.marca.marca} foi criada com sucesso `;
          this.tipo = "alert-success";
          this.carregarMarcas();
        })
        .catch((errors) => {
          this.message = errors.message + " | " + errors.response.data.message;
          this.tipo = "alert-danger";
          console.error(errors.response);
        });
    },
    descartar() {
      this.marca = null;
      this.arquivoImagem = [];
      this.tipo = null;
      this.message = null;
    },
  },
  mounted() {
    this.carregarMarcas();
  },
};
</script>
