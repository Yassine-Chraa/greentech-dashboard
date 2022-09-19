<template>
  <div class="main" style="margin: auto">
    <div class="col-md-12">
      <div class="row">
        <div class="col-4">
          <img
            class="img-responsive"
            alt="Invoce Template"
            src="/img/logo.png"
            style="width: 70%; height: 85%"
            v-if="fournisseur == 'GREENTECH'"
          />
          <h4 style="font-size: 18px; margin-top: 8px" v-else>
            <strong>{{ fournisseur }}</strong>
          </h4>
        </div>
        <div class="col-8 text-right">
          <h4 style="font-size: 18px; margin-top: 8px">
            <strong>{{ client }}</strong>
          </h4>
          <p>Immouzer Kandar</p>
        </div>
      </div>
      <br />
      <div class="row">
        <div class="col-md-12 text-center">
          <h2 style="font-size: 24px">
            Facture N° {{ id > 9 ? id : "0" + id }}/{{
              date != "" ? date.substr(0, 4) : "20xx"
            }}
          </h2>
        </div>
      </div>
      <br />
      <div>
        <table class="table table-striped projects">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Nom</th>
              <th scope="col">Prix Unitair</th>
              <th scope="col">Quantite</th>
              <th scope="col">Montant</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(produit, ind) in produits" :key="ind">
              <td>{{ produit.nom }}</td>
              <td>{{ produit.prix.toFixed(2) }}</td>
              <td>{{ produit.quantite }}</td>
              <td>{{ (produit.prix * produit.quantite).toFixed(2) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div>
        <div class="col-md-12">
          <div class="row" style="align-items: center">
            <div class="col-md-6">
              <p><b>Date :</b> {{ date }}</p>
            </div>
            <div class="col-md-6">
              <p class="total">{{ total.toFixed(2) + " DH" }}</p>
            </div>
          </div>
          <img
            v-if="fournisseur == 'GREENTECH'"
            class="img-responsive"
            src="/img/signature.jpeg"
            alt=""
            style="width: 30%; margin-top: -32px"
          />
        </div>
        <div class="col-12">
          <img
            src="/img/addrs.png"
            alt=""
            class="img-fluid"
            style="opacity: 0.8; margin-top: 16px"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Invoice",
  props: {
    produits: Array,
    id: String | Number,
    client: String,
    fournisseur: String,
    total: Number,
    date: String,
  },
};
</script>
<style lang="scss">
.main {
  background: #ffffff;
  border-bottom: 15px solid #f81d2d;
  border-top: 15px solid #1e1f23;
  margin-top: 30px;
  margin-bottom: 30px;
  padding: 16px 12px !important;
  position: relative;
  box-shadow: 0 1px 21px #808080;
  font-size: 14px;
}

.main thead {
  background: #1e1f23;
  color: #fff;
}
.total {
  width: max-content;
  margin-left: auto;
  background-color: #212529;
  color: #fff;
  font-weight: 600;
  padding: 6px 12px;
}
</style>
