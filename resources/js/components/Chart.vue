<template>
  <div class="col-md-6">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
          <span style="text-transform: capitalize">{{ type }}</span> de mois
        </h3>
        <select
          v-model="month"
          style="margin-left: 12px; text-transform: capitalize"
        >
          <option v-for="(month, ind) in months" :value="ind + 1" :key="ind">
            {{ month }}
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
            :id="'myChart' + ind"
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
  name: "Chart",
  data() {
    return {
      now: new Date(),
      months: [
        "janvier",
        "février",
        "mars",
        "avril",
        "mai",
        "juin",
        "juillet",
        "août",
        "septembre",
        "octobre",
        "novembre",
        "décembre",
      ],
      month: new Date().getMonth() + 1,
      myChart: null,
    };
  },
  props: {
    type: String,
    ind: String,
    bg: String,
  },
  methods: {
    setChart() {
      let labels = Array();
      let date = new Date(this.now.getFullYear(), this.now.getMonth(), 1);
      let operations = Array();
      let k = 0,
        i = 0;
      axios
        .get(
          `/api/commandes/chartSales/${date.getFullYear() + "-" + this.month}`
        )
        .then((res) => {
          while (date.getMonth() == this.now.getMonth()) {
            labels.push(date.getDate());
            if (
              k < res.data[this.type].length &&
              res.data[this.type][k].day == i + 1
            )
              operations[i] = res.data[this.type][k++].total;
            else operations[i] = 0;
            date.setDate(date.getDate() + 1);
            i++;
          }

          const data = {
            labels: labels,
            datasets: [
              {
                label: this.type,
                backgroundColor: this.bg,
                borderColor: this.bg,
                data: operations,
              },
            ],
          };

          const config = {
            type: "line",
            data: data,
            options: {
              options: {
                responsive: true,
              },
            },
          };
          this.myChart = new Chart(
            document.getElementById("myChart" + this.ind),
            config
          );
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
  watch: {
    month: function () {
      this.myChart.destroy();
      this.setChart();
    },
  },
  mounted() {
    this.setChart();
  },
};
</script>
