<template>
  <div class="content-wrapper">
    <PageHeader nom="Produits" type="Gestion" />
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-11" style="margin: auto">
            <div class="filtre">
              <div class="row mb-4">
                <div class="col-md-4">
                  <label>Sort Order</label>
                  <select
                    class="form-control"
                    v-model="order"
                    @change="changeOrder()"
                  >
                    <option value="ASC">ASC</option>
                    <option value="DESC">DESC</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label>Order By</label>
                  <select
                    class="form-control"
                    v-model="orderTarget"
                    @click="changeOrderTarget()"
                  >
                    <option value="id">Id</option>
                    <option value="nom">Nom</option>
                    <option value="categorie">Categorie</option>
                    <option value="prix">Prix</option>
                    <option value="stock">Stock</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="form-group">
                  <div class="input-group input-group-lg">
                    <input
                      type="search"
                      class="form-control form-control-lg"
                      placeholder="Tapez le nom de produit"
                      v-model="keyword"
                    />
                    <div class="input-group-append">
                      <button
                        type="button"
                        class="btn btn-lg btn-default"
                        @click="getResult()"
                      >
                        <i class="fa fa-search"></i>
                      </button>
                      <button
                        type="button"
                        class="btn btn-lg btn-default"
                        @click="
                          keyword = '';
                          getProduits();
                        "
                      >
                        <i class="fa fa-close"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header">
                <div class="card-tools">
                  <button
                    type="button"
                    class="btn btn-tool"
                    data-card-widget="collapse"
                    title="Collapse"
                  >
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                <table class="table table-striped projects">
                  <thead>
                    <tr>
                      <td>Id</td>
                      <td>Nom</td>
                      <td v-if="width > 560">Categorie</td>
                      <td>Prix</td>
                      <td>Stock</td>
                      <td v-if="width > 560">Outils</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(produit, ind) in produits" :key="ind">
                      <td>{{ produit.id }}</td>
                      <td>{{ produit.nom }}</td>
                      <td v-if="width > 560">{{ produit.categorie }}</td>
                      <td>{{ produit.prix }}</td>
                      <td>{{ produit.stock }}</td>
                      <td v-if="width > 560" class="text-left">
                        <button
                          type="button"
                          class="btn btn-info btn-sm"
                          data-toggle="modal"
                          data-target="#modal-default"
                          @click="openForm('modifier', produit)"
                        >
                          <i class="fas fa-pencil-alt"> </i>
                          Modifier
                        </button>
                        <button
                          type="button"
                          class="btn btn-danger btn-sm"
                          @click="executeAction('supprimer', produit.id)"
                        >
                          <i class="fas fa-trash"> </i>
                          Supprimer
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <ul
                  class="pagination"
                  style="
                    justify-content: right;
                    margin-bottom: 0;
                    padding-top: 15px;
                  "
                >
                  <li :class="prevClass" @click="getProduit(links[0].url)">
                    <a class="page-link">Previous</a>
                  </li>
                  <li
                    v-for="(link, ind) in links.slice(1, links.length - 1)"
                    :key="ind"
                    @click="getProduit(link.url)"
                    :class="link.active ? 'page-item active' : 'page-item'"
                  >
                    <a class="page-link">{{ link.label }}</a>
                  </li>
                  <li
                    :class="nextClass"
                    @click="getProduit(links[links.length - 1].url)"
                  >
                    <a class="page-link">Next</a>
                  </li>
                </ul>
              </div>
            </div>
            <div style="width: 25%; margin: auto">
              <button
                type="button"
                class="btn btn-block btn-primary mb-3"
                data-toggle="modal"
                data-target="#modal-default"
                @click="openForm('ajouter')"
              >
                Ajouter
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="modal-default" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Produit</h4>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form>
            <div class="modal-body">
              <div class="form-group">
                <label for="nom">Nom</label>
                <input
                  type="text"
                  class="form-control"
                  id="nom"
                  v-model="form.nom"
                />
                <span
                  class="error invalid-feedback"
                  v-for="(err, ind) in errors.nom"
                  :key="ind"
                  >{{ err }}</span
                >
              </div>
              <div class="form-group">
                <label for="categorie">Categorie</label>
                <select id="categorie" v-model="form.categorie_id">
                  <option
                    v-for="(categorie, ind) in categories"
                    :key="ind"
                    :value="categorie.id"
                    style="text-transform: capitalize"
                  >
                    {{ categorie.nom }}
                  </option>
                </select>
              </div>
              <div class="form-group">
                <label for="prix">Prix</label>
                <input
                  type="text"
                  class="form-control"
                  id="prix"
                  v-model="form.prix"
                />
                <span
                  class="error invalid-feedback"
                  v-for="(err, ind) in errors.prix"
                  :key="ind"
                  >{{ err }}</span
                >
              </div>
              <div class="form-group">
                <label for="stock">Stock</label>
                <input
                  type="text"
                  class="form-control"
                  id="stock"
                  v-model="form.stock"
                />
                <span
                  class="error invalid-feedback"
                  v-for="(err, ind) in errors.stock"
                  :key="ind"
                  >{{ err }}</span
                >
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button
                id="close"
                type="button"
                class="btn btn-default"
                data-dismiss="modal"
                @click="clearForm()"
              >
                Annuler
              </button>
              <button
                type="button"
                class="btn btn-primary"
                @click.prevent="executeAction(action)"
              >
                {{ action == "ajouter" ? "Ajouter" : "Modifier" }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <vue-confirm-dialog></vue-confirm-dialog>
  </div>
</template>
<script>
export default {
  name: "Produits",
  data() {
    return {
      form: {},
      categories: [],
      action: "ajouter",
      produits: [],
      links: [],
      prevClass: "",
      nextClass: "",
      orderTarget: "id",
      order: "ASC",
      keyword: "",
      errors: "",
      width: 0,
    };
  },
  methods: {
    handlePagination() {
      this.prevClass = "page-item ";
      this.prevClass += this.links[0].url == null ? "disabled" : "";
      this.nextClass = "page-item ";
      this.nextClass +=
        this.links[this.links.length - 1].url == null ? "disabled" : "";
    },
    getProduits(
      url = "/api/produits?page=1",
      orderTarget = "id",
      order = "ASC",
      keyword = ""
    ) {
      axios
        .get(
          `${url}&order=${order}&orderTarget=${orderTarget}${
            keyword != "" ? "&keyword=" + keyword : ""
          }`
        )
        .then((res) => {
          this.produits = res.data.data;
          this.links = res.data.links;
          this.handlePagination();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getCategories() {
      axios
        .get("/api/categories?orderTarget=id&order=ASC")
        .then((res) => {
          this.categories = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    openForm(action, produit = undefined) {
      this.action = action;
      this.errors = {};
      if (action == "ajouter") {
        this.form = { nom: "", categorie_id: 1, prix: "", stock: "" };
      }
      if (action == "modifier") {
        const { id, nom, categorie_id, prix, stock } = produit;
        this.form = { id, nom, categorie_id, prix, stock };
      }
    },
    executeAction(action, id = undefined) {
      switch (action) {
        case "ajouter":
          axios
            .post("/api/produits", this.form)
            .then((res) => {
              this.getProduits();
              document.getElementById("close").click();
              toastr.success("Un produit a été ajouté");
            })
            .catch((err) => {
              this.errors = err.response.data.errors;
            });
          break;
        case "modifier":
          axios
            .put("/api/produits/" + this.form.id, this.form)
            .then((res) => {
              this.getProduits();
              document.getElementById("close").click();
              toastr.success("Les informations du produit ont été modifiées");
            })
            .catch((err) => {
              this.errors = err.response.data.errors;
            });
          break;
        case "supprimer":
          this.$confirm({
            message: "Etes-vous sûr",
            button: {
              no: "Non",
              yes: "Oui",
            },
            callback: (confirm) => {
              if (confirm) {
                axios
                  .delete("/api/produits/" + id)
                  .then((res) => {
                    this.getProduits();
                  })
                  .catch((err) => {
                    console.log(err);
                  });
              }
            },
          });
          break;
        default:
          break;
      }
    },
    clearForm() {
      this.form = {};
    },
    changeOrder() {
      this.getProduits(
        "/api/produits?page=1",
        this.orderTarget,
        this.order,
        this.keyword
      );
    },
    changeOrderTarget() {
      this.getProduits(
        "/api/produits?page=1",
        this.orderTarget,
        this.order,
        this.keyword
      );
    },
    getResult() {
      this.getProduits(
        "/api/produits?page=1",
        this.orderTarget,
        this.order,
        this.keyword
      );
    },
  },
  mounted() {
    this.getProduits();
    this.getCategories();

    this.width = window.innerWidth;
    window.addEventListener("resize", () => {
      this.width = window.innerWidth;
    });
  },
};
</script>
<style lang="scss">
.filtre > .row {
  justify-content: center;
  margin-left: 0;
  margin-right: 0;
  .form-group {
    width: 100%;
  }
}
.card {
  width: 100%;
}
</style>
