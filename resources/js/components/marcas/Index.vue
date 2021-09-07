<template>
  <div class="container">
    <card-component>
      <template v-slot:header>
        <div class="row">
          <div class="col-xs-12 col-sm-6 mb-3">Lista de Marcas</div>
          <div class="col-xs-3 col-sm-2 input-group mb-3">
            <select class="custom-select" v-model="per_page">
              <option value="3">3</option>
              <option value="5">5</option>
              <option value="10">10</option>
            </select>
          </div>
          <div class="col-xs-9 col-sm-4 input-group mb-3">
            <input
              type="text"
              v-model="busca"
              class="form-control"
              placeholder="Busca"
            />
          </div>
        </div>
      </template>
      <template v-slot:body>
        <list-records-component
          :listar="filtroMarcas"
          :message="message"
          @excluir-marcas="excluirMarcas"
        >
        </list-records-component>
        <alert-component
          v-if="tipo"
          :tipo="tipo"
          :message="message"
        ></alert-component>
      </template>
      <template v-slot:footer>
        <div class="row">
          <div class="col-xs-12 col-sm-8">
            <paginate-component :paginacao="paginacao" @page_paginate="pagePaginate"></paginate-component>
          </div>
          <div class="col-xs-12 col-sm-4">
            <button
              type="submit"
              class="btn btn-primary float-right"
              data-toggle="modal"
              :data-target="'#' + modalId"
            >
              Adicionar
            </button>
          </div>
        </div>
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
export default {
  data() {
    return {
      modalId: "registra-marca",
      url: "http://localhost:8000/api/v1/marca",
      per_page: 5,
      paginacao: null,
      page: 1,
      marca: null,
      arquivoImagem: [],
      tipo: null,
      message: null,
      marcas: [],
      busca: null,
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
      if (this.busca) {
        let filtered = this.marcas.filter((marca) =>
          marca.marca.includes(this.busca)
        );
        if (filtered !== undefined) filter = filtered;
      }
      if (this.busca) {
        let filtered = this.marcas.find((marca) => marca.id == this.busca);
        if (filtered !== undefined) filter.push(filtered);
      }
      if (!this.busca) {
        return this.marcas;
      } else if (filter.length) {
        return filter;
      } else {
        return [
          {
            id: 0,
            marca: "Não encontrada",
            image: "not found",
          },
        ];
      }
    },
  },
  methods: {
    carregarMarcas() {
      let params = new URLSearchParams({
        per_page: this.per_page,
        page: this.page,
        // ids: this.busca,
        // marca: this.busca,
      });
      const config = {
        headers: {
          Authorization: "bearer " + this.token,
          Accept: "application/json",
        },
        params: params,
      };
      axios
        // .get(`${this.url}/?per_page=${this.per_page}`, config)
        .get(this.url, config)
        .then((response) => {
          this.marcas = response.data.marcas.data;
          this.paginacao = response.data.marcas;
          console.log(this.paginacao);
        })
        .catch((erro) => {
          console.error(erro);
        });
    },
    pagePaginate(page) {
      this.page = page
    },
    excluirMarcas(id) {
      const config = {
        headers: {
          Authorization: "bearer " + this.token,
          Accept: "application/json",
        },
      };
      axios
        .delete(`${this.url}/${id}`, config)
        .then((response) => {
          this.message = `A marca ${response.data.marca.marca} foi excluida com sucesso `;
          this.tipo = "alert-success";
          this.carregarMarcas();
        })
        .catch((errors) => {
          this.message = errors.message + " | " + errors.response.data.message;
          this.tipo = "alert-danger";
          console.error(errors.response);
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
  watch: {
    per_page() {
      this.carregarMarcas();
    },
    page() {
      this.carregarMarcas();
    },
  },
};
</script>
