<template>
  <div class="col-sm-6">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title" style="text-transform: capitalize;">{{type}}</h3>
        <select
          v-model="categorie_id"
          style="
            min-width: 140px;
            margin-left: 12px;
            text-transform: capitalize;
            font-size: 15px;
          "
        >
          <option
            v-for="(categorie, ind) in categories"
            :value="categorie.id"
            :key="ind"
          >
            {{ categorie.nom }}
          </option>
        </select>
        <div class="card-tools">
          <button
            type="button"
            class="btn btn-tool"
            data-card-widget="collapse"
          >
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="chart">
          <div class="chartjs-size-monitor">
            <div class="chartjs-size-monitor-expand">
              <div class=""></div>
            </div>
            <div class="chartjs-size-monitor-shrink">
              <div class=""></div>
            </div>
          </div>
          <canvas
            :id="'pieStock' + ind"
            style="
              min-height: 250px;
              height: 320px;
              max-height: 320px;
              max-width: 100%;
              display: block;
              width: 310px;
            "
            class="chartjs-render-monitor"
            width="310"
            height="320"
          ></canvas>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Pie",
  data() {
    return { now: new Date(), categories: [], categorie_id: 1, myChart: null };
  },
  props: {
    ind: String,
    type: String,
  },
  methods: {
    setChart() {
      let labels = Array();
      let stock = Array();
      let k = 0,
        i = 0;
      let url = "";
      if (this.type == "stock")
        url = `/api/produits?page=0&categorie_id=${this.categorie_id}`;
      if (this.type == "ventes")
        url = `/api/produits/ventes/${this.categorie_id}`;
      axios
        .get(url)
        .then((res) => {
          res.data.forEach((produit) => {
            labels.push(produit.nom);
            if (this.type == "stock") stock.push(produit.stock);
            if (this.type == "ventes") stock.push(produit.quantite);
          });
          var randomColors = Array(res.data.length);//TODO : use set insted of array
          for (let i = 0; i < res.data.length; i++) {
            randomColors[i] =
              "#" + Math.floor(Math.random(0, 7) * 16777215).toString(16);
          }
          const data = {
            labels: labels,
            datasets: [
              {
                label: "Ventes",
                backgroundColor: randomColors,
                borderColor: randomColors,
                data: stock,
              },
            ],
          };

          const config = {
            type: "pie",
            data: data,
            options: {
              options: {
                responsive: true,
              },
            },
          };

          this.myChart = new Chart(
            document.getElementById("pieStock" + this.ind),
            config
          );
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
  },
  watch: {
    categorie_id: function () {
      this.myChart.destroy();
      this.setChart();
    },
  },
  mounted() {
    this.getCategories();
    this.setChart();
  },
};
</script>
