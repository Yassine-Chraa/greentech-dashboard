<template>
  <div class="content-wrapper">
    <PageHeader nom="Categories" type="Gestion" />
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-9" style="margin: auto; max-width: 550px">
            <div class="filtre">
              <div class="row mb-4">
                <div class="col-md-4">
                  <label>Sort Order</label>
                  <select
                    class="form-control"
                    v-model="order"
                    @change="updateTable()"
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
                    @click="updateTable()"
                  >
                    <option value="id">Id</option>
                    <option value="nom">Nom</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="form-group">
                  <div class="input-group input-group-lg">
                    <input
                      type="search"
                      class="form-control form-control-lg"
                      placeholder="Tapez le nom de la categorie"
                      v-model="keyword"
                      style="font-size: 18px"
                    />
                    <div class="input-group-append">
                      <button
                        type="button"
                        class="btn btn-lg btn-default"
                        @click="updateTable()"
                      >
                        <i class="fa fa-search"></i>
                      </button>
                      <button
                        type="button"
                        class="btn btn-lg btn-default"
                        @click="
                          keyword = '';
                          updateTable();
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
                <h3 class="card-title">categories</h3>
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
                      <th>id</th>
                      <th>Nom</th>
                      <th>produits</th>
                      <th v-if="width > 560">Outils</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(categorie, ind) in categories" :key="ind">
                      <td>
                        {{ categorie.id }}
                      </td>
                      <td>
                        {{ categorie.nom }}
                      </td>
                      <td style="text-align: center">
                        {{ categorie.nombre_produits }}
                      </td>
                      <td v-if="width > 560" class="text-left">
                        <button
                          type="button"
                          class="btn btn-info btn-sm"
                          data-toggle="modal"
                          data-target="#modal-default"
                          @click="openForm('modifier', categorie)"
                        >
                          <i class="fas fa-pencil-alt"> </i>
                          Modifier
                        </button>
                        <button
                          type="button"
                          class="btn btn-danger btn-sm"
                          v-if="categorie.nombre_produits == 0"
                          @click="executeAction('supprimer', categorie.id)"
                        >
                          <i class="fas fa-trash"> </i>
                          Supprimer
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
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
            <h4 class="modal-title">Categorie</h4>
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
  name: "Categories",
  data() {
    return {
      form: {},
      action: "ajouter",
      categories: [],
      produits: [],
      orderTarget: "id",
      order: "ASC",
      keyword: "",
      errors: {},
      width: 0,
    };
  },
  methods: {
    getCategories(orderTarget = "id", order = "ASC", keyword = "") {
      axios
        .get(
          `/api/categories?order=${order}&orderTarget=${orderTarget}${
            keyword != "" ? "&keyword=" + keyword : ""
          }`
        )
        .then((res) => {
          this.categories = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getProduits(categorie) {
      axios
        .get(`/api/produits?categorie_id=${categorie_id}`)
        .then((res) => {
          this.produits = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    openForm(action, categorie = undefined) {
      this.action = action;
      this.errors = {};
      if (action == "ajouter") {
        this.form = { nom: "", numero: "" };
      }
      if (action == "modifier") {
        const { id, nom, numero } = categorie;
        this.form = { id, nom, numero };
      }
    },
    executeAction(action, id = undefined, nom = undefined) {
      switch (action) {
        case "ajouter":
          axios
            .post("/api/categories", this.form)
            .then((res) => {
              this.getCategories();
              document.getElementById("close").click();
              toastr.success("Une categorie a été ajouté");
            })
            .catch((err) => {
              this.errors = err.response.data.errors;
            });
          break;
        case "modifier":
          axios
            .put("/api/categories/" + this.form.id, this.form)
            .then((res) => {
              this.getCategories();
              document.getElementById("close").click();
              toastr.success(
                "Les informations de la categorie ont été modifiées"
              );
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
                  .delete("/api/categories/" + id)
                  .then((res) => {
                    this.getCategories();
                    document.getElementById("close").click();
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
    updateTable() {
      this.getCategories(this.orderTarget, this.order, this.keyword);
    },
  },
  mounted() {
    this.getCategories();

    this.width = window.innerWidth;
    window.addEventListener("resize", () => {
      this.width = window.innerWidth;
    });
  },
};
</script>
<style lang="scss">
.card {
  width: 100%;
}
.pagination {
  > li:first-child,
  > li:last-child {
    font-weight: 600;
  }
  .page-item.disabled .page-link {
    color: #8d9399;
  }
}
</style>
