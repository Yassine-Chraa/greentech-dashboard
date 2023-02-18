<template>
  <div class="content-wrapper">
    <PageHeader nom="Clients" type="Gestion" />
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-11" style="margin: auto; max-width: 740px">
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
                    <option value="dette">Dette</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="form-group">
                  <div class="input-group input-group-lg">
                    <input
                      type="search"
                      class="form-control form-control-lg"
                      placeholder="Tapez le nom du client"
                      v-model="keyword"
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
                <h3 class="card-title">Clients</h3>
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
                      <th v-if="width > 560">Numéro</th>
                      <th>Dette</th>
                      <th>Outils</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(client, ind) in clients" :key="ind">
                      <td>
                        {{ client.id }}
                      </td>
                      <td>
                        {{ client.nom }}
                      </td>
                      <td v-if="width > 560">
                        {{ client.numero }}
                      </td>
                      <td>
                        {{ client.dette }}
                      </td>
                      <td class="text-left">
                        <button
                          type="button"
                          class="btn btn-primary btn-sm"
                          data-toggle="modal"
                          data-target="#invoice"
                          @click="
                            executeAction('voirFacture', client.id, client.nom)
                          "
                        >
                          <i class="fas fa-eye"> </i>
                          Voir la facture
                        </button>
                        <button
                          v-if="width > 560"
                          type="button"
                          class="btn btn-info btn-sm"
                          data-toggle="modal"
                          data-target="#modal-default"
                          @click="openForm('modifier', client)"
                        >
                          <i class="fas fa-pencil-alt"> </i>
                          Modifier
                        </button>
                        <button
                          v-if="width > 560"
                          type="button"
                          class="btn btn-danger btn-sm"
                          @click="executeAction('supprimer', client.id)"
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
                  <li :class="prevClass" @click="getClients(links[0].url)">
                    <a class="page-link">Previous</a>
                  </li>
                  <li
                    v-for="(link, ind) in links.slice(1, links.length - 1)"
                    :key="ind"
                    @click="getClients(link.url)"
                    :class="link.active ? 'page-item active' : 'page-item'"
                  >
                    <a class="page-link">{{ link.label }}</a>
                  </li>
                  <li
                    :class="nextClass"
                    @click="getClients(links[links.length - 1].url)"
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
            <h4 class="modal-title">
              {{
                action.replace(action[0], action[0].toUpperCase()) + " Client"
              }}
            </h4>
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
                <label for="nom">Nom complet</label>
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
                <label for="numero">Numéro de téléphone</label>
                <input
                  type="tel"
                  maxlength="10"
                  class="form-control"
                  id="numero"
                  v-model="form.numero"
                />
                <span
                  class="error invalid-feedback"
                  v-for="(err, ind) in errors.numero"
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
    <div class="modal fade" id="invoice" aria-hidden="true">
      <div class="modal-dialog" style="max-width: 640px">
        <div class="modal-content">
          <div class="modal-header" style="padding: 6px">
            <button
              type="button"
              class="modal-title btn btn-info"
              @click="download()"
            >
              Telecharger la facture
            </button>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body" style="padding: 0">
            <invoice
              :produits="commandeProduits"
              :id="commandeId"
              :client="client"
              :total="total"
              :fournisseur="fournisseur"
              :date="date"
            />
          </div>
        </div>
      </div>
    </div>
    <vue-html2pdf
      :show-layout="false"
      :float-layout="true"
      :enable-download="true"
      :preview-modal="false"
      :paginate-elements-by-height="1100"
      :filename="'client_N' + commandeId + '/' + date.substr(0, 4)"
      :pdf-quality="2"
      :manual-pagination="false"
      pdf-format="a4"
      pdf-orientation="landscape"
      pdf-content-width="600px"
      ref="html2Pdf"
    >
      <section slot="pdf-content">
        <invoice
          :produits="commandeProduits"
          :id="commandeId"
          :client="client"
          :total="total"
          :fournisseur="fournisseur"
          :date="date"
        />
      </section>
    </vue-html2pdf>
  </div>
</template>

<script>
import invoice from "../components/Invoice";
export default {
  name: "Clients",
  data() {
    return {
      form: {},
      action: "ajouter",
      clients: [],
      links: [],
      prevClass: "",
      nextClass: "",
      commandeProduits: [],
      total: 0,
      commandeId: "",
      client: "",
      fournisseur: "",
      date: "",
      orderTarget: "id",
      order: "ASC",
      keyword: "",
      errors: {},
      width: 0,
    };
  },
  components: {
    invoice,
  },
  methods: {
    handlePagination() {
      this.prevClass = "page-item ";
      this.prevClass += this.links[0].url == null ? "disabled" : "";
      this.nextClass = "page-item ";
      this.nextClass +=
        this.links[this.links.length - 1].url == null ? "disabled" : "";
    },
    getClients(
      url = "/api/clients?page=1",
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
          this.clients = res.data.data;
          this.links = res.data.links;
          this.handlePagination();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    calcTotal(produits) {
      this.total = 0;
      produits.forEach((ele) => {
        this.total += ele.prix * ele.quantite;
      });
    },
    openForm(action, client = undefined) {
      this.action = action;
      this.errors = {};
      if (action == "ajouter") {
        this.form = { nom: "", numero: "" };
      }
      if (action == "modifier") {
        const { id, nom, numero } = client;
        this.form = { id, nom, numero };
      }
    },
    executeAction(action, id = undefined, nom = undefined) {
      switch (action) {
        case "voirFacture":
          axios
            .get("/api/clients/produits/" + id)
            .then((res) => {
              this.commandeProduits = res.data;
              this.commandeId = id;
              this.client = nom;
              this.fournisseur = nom == "GREENTECH" ? "" : "GREENTECH";
              if (this.commandeProduits.length != 0)
                this.date = this.commandeProduits[0].date;
              this.calcTotal(res.data);
            })
            .catch((err) => {
              console.log(err);
            });
          break;
        case "ajouter":
          axios
            .post("/api/clients", this.form)
            .then((res) => {
              this.getClients();
              document.getElementById("close").click();
              toastr.success("Un client a été ajouté");
            })
            .catch((err) => {
              this.errors = err.response.data.errors;
            });
          break;
        case "modifier":
          axios
            .put(`/api/clients/${this.form.id}`, this.form)
            .then((res) => {
              this.getClients();
              document.getElementById("close").click();
              toastr.success("Les informations du client ont été modifiées");
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
                  .delete("/api/clients/" + id)
                  .then((res) => {
                    this.getClients();
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
      this.getClients(
        "/api/clients?page=1",
        this.orderTarget,
        this.order,
        this.keyword
      );
    },
    download() {
      this.$refs.html2Pdf.generatePdf();
    },
  },
  mounted() {
    this.width = window.innerWidth;
    window.addEventListener("resize", () => {
      this.width = window.innerWidth;
    });
    this.getClients();
  },
};
</script>
<style lang="scss">
.card {
  width: 100%;
}
</style>
