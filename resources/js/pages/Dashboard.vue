<template>
  <div class="content-wrapper">
    <PageHeader nom="Dashbaord" />
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <InfoBox
            nom="Nouvelles ventes"
            :data="daySales.toFixed(2)"
            bg="bg-info"
            icon="fa-chart-line"
          />
          <InfoBox
            nom="Ventes par mois"
            :data="monthSales.toFixed(2)"
            bg="bg-success"
            icon="fa-chart-simple"
          />
          <div class="clearfix hidden-md-up"></div>
          <InfoBox
            nom="Revenu total"
            :data="ventes.toFixed(2)"
            bg="bg-success"
            icon="fa-basket-shopping"
          />
          <InfoBox
            nom="Achats"
            :data="achats.toFixed(2)"
            bg="bg-danger "
            icon="fa-store"
          />
        </div>
        <div class="row" style="padding-top: 32px">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">Dernières ventes</h3>
                <div class="card-tools">
                  <button
                    type="button"
                    class="btn btn-tool"
                    data-card-widget="collapse"
                  >
                    <i class="fas fa-minus"></i>
                  </button>
                  <button
                    type="button"
                    class="btn btn-tool"
                    data-card-widget="remove"
                  >
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Outils</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(commande, ind) in commandes.ventes"
                        :key="ind"
                      >
                        <td>
                          {{ commande.id }}
                        </td>
                        <td>{{ commande.nom }}</td>
                        <td>{{ commande.date }}</td>
                        <td>
                          <button
                            type="button"
                            class="btn btn-sm btn-success"
                            style="margin-top: -5px"
                            data-toggle="modal"
                            data-target="#invoice"
                            @click="
                              executeAction('voir', commande.id, commande.nom,commande.fournisseur)
                            "
                          >
                            <i class="fa-solid fa-eye"></i>
                            Voir
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="card-footer clearfix" v-if="$attrs.isadmin">
                <router-link
                  to="/commandes"
                  class="btn btn-sm btn-secondary float-right"
                  >voir toutes</router-link
                >
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">Dernières Achats</h3>
                <div class="card-tools">
                  <button
                    type="button"
                    class="btn btn-tool"
                    data-card-widget="collapse"
                  >
                    <i class="fas fa-minus"></i>
                  </button>
                  <button
                    type="button"
                    class="btn btn-tool"
                    data-card-widget="remove"
                  >
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>

              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Outils</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(commande, ind) in commandes.achats"
                        :key="ind"
                      >
                        <td>
                          {{ commande.id }}
                        </td>
                        <td>{{ commande.nom }}</td>
                        <td>{{ commande.date }}</td>
                        <td>
                          <button
                            type="button"
                            class="btn btn-sm btn-success"
                            style="margin-top: -5px"
                            data-toggle="modal"
                            data-target="#invoice"
                            @click="
                              executeAction('voir', commande.id, commande.nom,commande.fournisseur)
                            "
                          >
                            <i class="fa-solid fa-eye"></i>
                            Voir
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="card-footer clearfix" v-if="$attrs.isadmin">
                <router-link
                  to="/commandes"
                  class="btn btn-sm btn-secondary float-right"
                  >voir toutes</router-link
                >
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <Chart type="ventes" ind="1" bg="#7F5283" />
          <Chart type="achats" ind="2" bg="#EF5B0C" />
        </div>
        <div class="row">
          <Pie ind="1" type="ventes" />
          <Pie ind="2" type="stock" />
        </div>
      </div>
    </div>
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
              :client="clientName"
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
      :filename="'facture_N'+commandeId+'/'+date.substr(0,4)"
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
          :client="clientName"
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
import Chart from "../components/Chart";
import Pie from "../components/Pie";
export default {
  name: "Dashboard",
  data() {
    return {
      daySales: 0,
      monthSales: 0,
      ventes: 0,
      achats: 0,
      commandes: {
        ventes: [],
        achats: [],
      },
      commandeProduits: [],
      total: 0,
      commandeId: "",
      clientName: "",
      fournisseur: "",
      date: "",
      now: new Date(),
    };
  },
  components: {
    invoice,
    Chart,
    Pie,
  },
  methods: {
    getCommandes() {
      axios
        .get("/api/commandes?page=0&type=vente")
        .then((res) => {
          this.commandes.ventes = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
      axios
        .get("/api/commandes?page=0&type=achat")
        .then((res) => {
          this.commandes.achats = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getDaySales() {
      axios
        .get(
          `/api/commandes/daySales/${this.now.toISOString().split("T")[0]}`
        )
        .then((res) => {
          if (res.data[0].ventes != null) this.daySales = res.data[0].ventes;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getMonthSales() {
      let month = this.now.getMonth() + 1;
      axios
        .get(`/api/commandes/monthSales/${this.now.getFullYear() + "-" + month}`)
        .then((res) => {
          if (res.data[0].ventes != null) this.monthSales = res.data[0].ventes;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getVentes() {
      axios
        .get(`/api/commandes/total/vente?annee=${this.now.getFullYear()}`)
        .then((res) => {
          if (res.data[0].total != null) this.ventes = res.data[0].total;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getAchats() {
      axios
        .get(`/api/commandes/total/achat?annee=${this.now.getFullYear()}`)
        .then((res) => {
          this.achats = res.data[0].total;
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
    executeAction(action, id = undefined, name = undefined, fournisseur=undefined) {
      switch (action) {
        case "voir":
          axios
            .get("/api/commandes/produits/" + id)
            .then((res) => {
              this.commandeProduits = res.data;
              this.commandeId = id;
              this.clientName = name;
              this.fournisseur = fournisseur;
              if(this.commandeProduits.length != 0)this.date = this.commandeProduits[0].date;
              this.calcTotal(res.data);
            })
            .catch((err) => {
              console.log(err);
            });
          break;
        default:
          break;
      }
    },
    download() {
      this.$refs.html2Pdf.generatePdf();
    },
  },
  created() {
    this.getDaySales();
    this.getMonthSales();
    this.getVentes();
    this.getAchats();
    this.getCommandes();
  },
};
</script>
<style lang="scss">
.content {
  .row {
    > .card {
      height: 100%;
      margin-left: 16px;
      margin-right: 16px;
    }
    .monster-card {
      color: #2c2b2e;
      .card-body {
        padding: 1.25rem;
        .card-title {
          float: unset;
          position: relative;
          font-weight: 400;
          margin-bottom: 0.75rem;
          line-height: 22px;
          font-size: 18px;
        }
        .text-end {
          text-align: left;
          .font-light {
            line-height: 1.2;
            font-size: 22px;
          }
        }
        span {
          font-size: 16px;
          font-weight: 300;
        }
      }
      .progress {
        background-color: unset;
      }
    }
  }
}
</style>
