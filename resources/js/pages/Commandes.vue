<template>
  <div class="content-wrapper">
    <PageHeader nom="Commandes" type="Gestion" />
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div
            class="col-md-12"
            style="
              margin: auto;
              max-width: 870px;
              padding-left: 4px;
              padding-right: 4px;
            "
          >
            <div class="filtre">
              <div class="row mb-4">
                <div class="col-md-6">
                  <label>La nature de la commande</label>
                  <select
                    class="form-control"
                    v-model="commandeType"
                    @click="getCommandes('/api/commandes?page=1')"
                  >
                    <option value="vente">Vente</option>
                    <option value="achat">Achat</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label>La date de la commande</label>
                  <div class="row">
                    <input
                      type="date"
                      class="form-control col"
                      v-model="commandeDate"
                      @change="getCommandes('/api/commandes?page=1')"
                    />
                      <button
                        type="button"
                        class="btn btn-default"
                        @click="
                          commandeDate = '';
                          getCommandes();
                        "
                      >
                        <i class="fa fa-close"></i>
                      </button>
                  </div>
                </div>
                <div class="col-md-6">
                  <label>Sort Order</label>
                  <select
                    class="form-control"
                    v-model="order"
                    @change="getCommandes('/api/commandes?page=1')"
                  >
                    <option value="ASC">ASC</option>
                    <option value="DESC">DESC</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label>Order By</label>
                  <select
                    class="form-control"
                    v-model="orderTarget"
                    @click="getCommandes('/api/commandes?page=1')"
                  >
                    <option value="commandes.id">Id</option>
                    <option value="date">Date</option>
                    <option value="nom">Client</option>
                    <option value="fournisseur">Fournisseur</option>
                    <option value="avance">Avance</option>
                    <option value="total">Total</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="form-group">
                  <div class="input-group input-group-lg">
                    <input
                      type="search"
                      class="form-control form-control-lg"
                      :placeholder="`Tapez le nom de ${commandeType == 'achat'?'Fournisseur':'Client'}`"
                      v-model="keyword"
                    />
                    <div class="input-group-append">
                      <button
                        type="button"
                        class="btn btn-lg btn-default"
                        @click="getCommandes()"
                      >
                        <i class="fa fa-search"></i>
                      </button>
                      <button
                        type="button"
                        class="btn btn-lg btn-default"
                        @click="
                          keyword = '';
                          getCommandes();
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
                <h3 class="card-title">Commandes</h3>
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
                <table
                  class="table table-striped projects"
                  style="font-size: 15px"
                >
                  <thead>
                    <tr>
                      <th>id</th>
                      <th v-if="commandeType == 'vente'">Client</th>
                      <th v-if="commandeType == 'achat'">Fournisseur</th>
                      <th v-if="width > 560">Date</th>
                      <th v-if="width > 560">Avance</th>
                      <th v-if="width > 560">Total</th>
                      <th>Etat</th>
                      <th>Outils</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(commande, ind) in commandes" :key="ind">
                      <td>
                        {{ commande.id }}
                      </td>
                      <td
                        style="cursor: pointer"
                        v-if="commandeType == 'vente'"
                      >
                        {{ commande.nom }}
                      </td>
                      <td v-if="commandeType == 'achat'">
                        {{ commande.fournisseur }}
                      </td>
                      <td v-if="width > 560">
                        {{ commande.date }}
                      </td>
                      <td v-if="width > 560">
                        {{ commande.avance.toFixed(2) }}
                      </td>
                      <td v-if="width > 560">
                        {{ commande.total.toFixed(2) }}
                      </td>
                      <td>
                        <span
                          class="badge bg-danger"
                          style="font-size: 13px; padding: 4px 8px"
                          >{{
                            commande.total == commande.avance
                              ? "Payée"
                              : "Non payée"
                          }}</span
                        >
                      </td>
                      <td class="text-left">
                        <button
                          type="button"
                          class="btn btn-primary btn-sm mt-1"
                          style="font-size: 13px"
                          data-toggle="modal"
                          data-target="#invoice"
                          @click="
                            executeAction(
                              'voir',
                              commande.id,
                              commande.nom,
                              commande.fournisseur,
                              commande.total
                            )
                          "
                        >
                          <i class="fas fa-eye"> </i>
                          Voir
                        </button>
                        <button
                          v-if="width > 560"
                          type="button"
                          class="btn btn-info btn-sm mt-1"
                          style="font-size: 13px"
                          data-toggle="modal"
                          data-target="#modal-default"
                          @click="openForm('modifier', commande)"
                        >
                          <i class="fas fa-pencil-alt"> </i>
                          Modifier
                        </button>
                        <button
                          v-if="width > 560"
                          type="button"
                          class="btn btn-danger btn-sm mt-1"
                          style="font-size: 13px"
                          @click="executeAction('supprimer', commande.id)"
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
                    cursor: pointer;
                  "
                >
                  <li :class="prevClass" @click="getCommandes(links[0].url)">
                    <a class="page-link">Previous</a>
                  </li>
                  <li
                    v-for="(link, ind) in links.slice(1, links.length - 1)"
                    :key="ind"
                    @click="getCommandes(link.url)"
                    :class="link.active ? 'page-item active' : 'page-item'"
                  >
                    <a class="page-link">{{ link.label }}</a>
                  </li>
                  <li
                    :class="nextClass"
                    @click="getCommandes(links[links.length - 1].url)"
                  >
                    <a class="page-link">Next</a>
                  </li>
                </ul>
              </div>
            </div>
            <div style="display: flex; width: 40%; margin: auto">
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
      <div class="modal-dialog" style="max-width: 680px">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Commande</h4>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              @click="overflow = 'auto' /*Fix scroll problem*/"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form>
            <div class="modal-body">
              <div class="form-group" v-if="commandeType == 'vente'">
                <label for="nom">Client</label>
                <input
                  type="text"
                  class="form-control"
                  id="nom"
                  v-model="form.nom"
                />
                <div>
                  <input type="checkbox" id="isNow" v-model="form.isNew" />
                  <label for="isNow">Nouveau client</label>
                </div>
                <span
                  class="error invalid-feedback"
                  v-for="(err, ind) in errors.nom"
                  :key="ind"
                  >{{ err }}</span
                >
              </div>

              <div class="form-group" v-if="commandeType == 'achat'">
                <label for="nom">Fournisseur</label>
                <input
                  type="text"
                  class="form-control"
                  id="nom"
                  v-model="form.fournisseur"
                />
                <span
                  class="error invalid-feedback"
                  v-for="(err, ind) in errors.fournisseur"
                  :key="ind"
                  >{{ err }}</span
                >
              </div>
              <div class="form-group">
                <label for="date">Date</label>
                <input
                  type="date"
                  class="form-control"
                  id="date"
                  v-model="form.date"
                />
                <span
                  class="error invalid-feedback"
                  v-for="(err, ind) in errors.date"
                  :key="ind"
                  >{{ err }}</span
                >
              </div>
              <div class="form-group" v-if="showTable">
                <label for="avance">avance</label>
                <input
                  type="text"
                  class="form-control"
                  id="avance"
                  v-model="form.avance"
                />
                <span
                  class="error invalid-feedback"
                  v-for="(err, ind) in errors.avance"
                  :key="ind"
                  >{{ err }}</span
                >
              </div>
              <table class="table table-striped projects" v-if="showTable">
                <thead class="thead-dark">
                  <tr>
                    <th>Nom</th>
                    <th>Prix Unitair</th>
                    <th>Quantite</th>
                    <th>Montant</th>
                    <th v-if="width > 560">Outils</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(commandeProduit, ind) in commandeProduits"
                    :key="ind"
                  >
                    <td>{{ commandeProduit.nom }}</td>
                    <td>{{ commandeProduit.prix.toFixed(2) }}</td>
                    <td>{{ commandeProduit.quantite }}</td>
                    <td>
                      {{
                        (
                          commandeProduit.prix * commandeProduit.quantite
                        ).toFixed(2)
                      }}
                    </td>
                    <td v-if="width > 560" class="text-left mt-1">
                      <button
                        type="button"
                        class="btn btn-info btn-sm"
                        data-toggle="modal"
                        data-target="#invoice-products"
                        @click="
                          openForm(
                            'modifierProduit',
                            undefined,
                            commandeProduit
                          )
                        "
                      >
                        <i class="fas fa-pencil-alt"> </i>
                        Modifier
                      </button>
                      <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        @click="
                          executeAction('supprimerProduit', commandeProduit.id)
                        "
                      >
                        <i class="fas fa-trash"> </i>
                        Supprimer
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div v-if="showTable">
                <p class="total">{{ total.toFixed(2) + " DH" }}</p>
              </div>
              <div style="width: 25%; margin: auto" v-if="showTable">
                <button
                  type="button"
                  class="btn btn-block btn-primary"
                  data-toggle="modal"
                  data-target="#invoice-products"
                  @click="openForm('ajouterProduit')"
                >
                  Ajouter
                </button>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button
                id="close"
                type="button"
                class="btn btn-default"
                data-dismiss="modal"
                @click="
                  clearForm();
                  overflow = 'auto'; /*Fix scroll problem*/
                "
              >
                Annuler
              </button>
              <button
                type="button"
                class="btn btn-primary"
                @click.prevent="
                  executeAction(action);
                  overflow = 'hidden'; /*Fix scroll problem*/
                "
              >
                {{ action == "ajouter" ? "Ajouter" : "Modifier" }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="modal fade" id="invoice" aria-hidden="true">
      <div class="modal-dialog" style="max-width: 700px">
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
    <div class="modal fade" id="invoice-products" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">
              {{ action == "ajouterProduit" ? "Ajouter" : "Modifier" }} Produit
            </h4>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              @click="overflow = 'hidden' /*Fix scroll problem*/"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form>
            <div class="modal-body">
              <div class="form-group" v-if="this.action == 'ajouterProduit'">
                <label for="nom">Nom</label>
                <input
                  type="text"
                  class="form-control"
                  id="nom"
                  placeholder="Entrer le nom de produit"
                  v-model="commandeProduitForm.nom"
                />
                <span
                  class="error invalid-feedback"
                  v-for="(err, ind) in produitErrors.nom"
                  :key="ind"
                  >{{ err }}</span
                >
              </div>
              <div class="form-group">
                <label for="prix">Prix</label>
                <input
                  type="text"
                  class="form-control"
                  id="prix"
                  v-model="commandeProduitForm.prix"
                />
                <span
                  class="error invalid-feedback"
                  v-for="(err, ind) in produitErrors.prix"
                  :key="ind"
                  >{{ err }}</span
                >
              </div>
              <div class="form-group">
                <label for="quantite">Quantite</label>
                <input
                  type="text"
                  class="form-control"
                  id="quantite"
                  v-model="commandeProduitForm.quantite"
                />
                <span
                  class="error invalid-feedback"
                  v-for="(err, ind) in produitErrors.quantite"
                  :key="ind"
                  >{{ err }}</span
                >
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button
                id="close2"
                type="button"
                class="btn btn-default"
                data-dismiss="modal"
                @click="
                  commandeProduitForm = {};
                  action = 'modifier';
                  overflow = 'hidden'; /*Fix scroll problem*/
                "
              >
                Annuler
              </button>
              <button
                type="button"
                class="btn btn-primary"
                @click.prevent="
                  executeAction(action);
                  overflow = 'hidden'; /*Fix scroll problem*/
                "
              >
                {{ action == "ajouterProduit" ? "Ajouter" : "Modifier" }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <vue-confirm-dialog></vue-confirm-dialog>
    <vue-html2pdf
      :show-layout="false"
      :float-layout="true"
      :enable-download="true"
      :preview-modal="false"
      :paginate-elements-by-height="1100"
      :filename="'facture_N' + commandeId + '/' + date.substr(0, 4)"
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
import VueHtml2pdf from "vue-html2pdf";
export default {
  name: "Commandes",
  components: {
    invoice,
    VueHtml2pdf,
  },
  data() {
    return {
      form: {},
      commandeProduitForm: {},
      action: "ajouter",
      commandes: [],
      commandeProduits: [],
      total: 0,
      produits: [],
      commandeId: "",
      client: "",
      fournisseur: "",
      date: "",
      links: [],
      prevClass: "",
      nextClass: "",
      showFournisseur: false,
      showTable: false,
      order: "DESC",
      orderTarget: "date",
      keyword: "",
      commandeType: "vente",
      commandeDate: "",
      errors: {},
      produitErrors: {},
      overflow: "auto",
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
    getCommandes(url = "/api/commandes?page=1") {
      axios
        .get(
          `${url}&type=${this.commandeType}&date=${
            this.commandeDate
          }&orderTarget=${this.orderTarget}&order=${this.order}${
            this.keyword != "" ? "&keyword=" + this.keyword : ""
          }`
        )
        .then((res) => {
          this.commandes = res.data.data;
          if (this.commandeType == "achat") this.showFournisseur = true;
          else this.showFournisseur = false;
          this.links = res.data.links;
          this.handlePagination();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getCommandeProduits(id) {
      axios
        .get("/api/commandes/produits/" + id)
        .then((res) => {
          this.commandeProduits = res.data;
          this.calcTotal(this.commandeProduits);
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
    openForm(action, commande = undefined, produit = undefined) {
      this.action = action;
      if (action == "ajouter") {
        this.showTable = false;
        this.errors = {};
        if (this.commandeType == "vente") {
          this.form = {
            type: "vente",
            isNew: false,
            nom: "",
            fournisseur: "GREENTECH",
            date: "",
            avance: 0,
          };
        }
        if (this.commandeType == "achat") {
          this.form = {
            type: "achat",
            isNew: false,
            nom: "GREENTECH",
            fournisseur: "",
            date: "",
            avance: 0,
          };
        }
        this.showFournisseur = false;
      }
      if (action == "modifier") {
        this.errors = {};
        const { id, nom, fournisseur, date, avance } = commande;
        if (this.commandeType == "vente") {
          this.form = { id, nom, fournisseur, date, avance };
        }
        if (this.commandeType == "achat") {
          this.form = { id, isNew: false, nom, fournisseur, date, avance };
        }
        this.getCommandeProduits(id);
        this.showTable = true;
        this.showFournisseur = true;
      }
      if (action == "ajouterProduit") {
        this.produitErrors = {};
        this.commandeProduitForm = {
          commande_id: this.form.id,
          nom: "",
          prix: "",
          quantite: "",
        };
      }
      if (action == "modifierProduit") {
        this.produitErrors = {};
        const { id, nom, prix, quantite } = produit;
        this.commandeProduitForm = { id, nom, prix, quantite };
      }
    },
    executeAction(action, id = null, nom = null, fournisseur = null) {
      switch (action) {
        case "voir":
          axios
            .get("/api/commandes/produits/" + id)
            .then((res) => {
              this.commandeProduits = res.data;
              this.commandeId = id;
              this.client = nom;
              this.fournisseur = fournisseur;
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
            .post("/api/commandes", this.form)
            .then((res) => {
              this.getCommandes();
              document.getElementById("close").click();
              toastr.success("Un commande a été ajouté");
            })
            .catch((err) => {
              if (err.response.data.errors != undefined)
                this.errors = err.response.data.errors;
              if (err.response.data.error != undefined) {
                toastr.error(err.response.data.error);
              }
            });
          break;
        case "modifier":
          axios
            .put("/api/commandes/" + this.form.id, this.form)
            .then((res) => {
              this.getCommandes();
              document.getElementById("close").click();
              toastr.success(
                "Les informations de la commande ont été modifiées"
              );
            })
            .catch((err) => {
              if (err.response.data.errors != undefined)
                this.errors = err.response.data.errors;
              if (err.response.data.error != undefined) {
                toastr.error(err.response.data.error);
              }
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
                  .delete("/api/commandes/" + id)
                  .then(() => {
                    this.getCommandes();
                  })
                  .catch((err) => {
                    console.log(err);
                  });
              }
            },
          });
          break;
        case "ajouterProduit":
          this.commandeProduitForm.commande_id = this.form.id;
          axios
            .post("/api/commandes/produits", this.commandeProduitForm)
            .then((res) => {
              this.getCommandeProduits(this.form.id);
              this.getCommandes();

              document.getElementById("close2").click();
              this.action = "modifier";
              toastr.success("Un produit a été ajouté");
            })
            .catch((err) => {
              if (err.response.data.errors != undefined)
                this.produitErrors = err.response.data.errors;
              if (err.response.data.error != undefined)
                toastr.error(err.response.data.error);
            });
          break;
        case "modifierProduit":
          axios
            .put(
              "/api/commandes/produits/" + this.commandeProduitForm.id,
              this.commandeProduitForm
            )
            .then((res) => {
              this.getCommandeProduits(this.form.id);
              this.getCommandes();

              document.getElementById("close2").click();
              this.action = "modifier";
              toastr.success("Les informations du produit ont été modifiées");
            })
            .catch((err) => {
              if (err.response.data.errors != undefined)
                this.produitErrors = err.response.data.errors;
              if (err.response.data.error != undefined)
                toastr.error(err.response.data.error);
            });
          break;
        case "supprimerProduit":
          this.$confirm({
            message: "Etes-vous sûr",
            button: {
              no: "Non",
              yes: "Oui",
            },
            callback: (confirm) => {
              if (confirm) {
                axios
                  .delete("/api/commandes/produits/" + id)
                  .then((res) => {
                    this.getCommandeProduits(this.form.id);
                    this.getCommandes();
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
    download() {
      this.$refs.html2Pdf.generatePdf();
    },
  },
  watch: {
    overflow: function () {
      document.getElementsByTagName("html")[0].style =
        "overflow: " + this.overflow;
    },
  },
  mounted() {
    this.width = window.innerWidth;
    window.addEventListener("resize", () => {
      this.width = window.innerWidth;
    });
    this.getCommandes();
  },
};
</script>
<style lang="scss" scoped>
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
.pagination {
  > li:first-child,
  > li:last-child {
    font-weight: 600;
  }
  .page-item.disabled .page-link {
    color: #8d9399;
  }
}
#invoice {
  .modal-body {
    padding: 0 !important;
  }
}
</style>
