<template>
  <div id="main" style="width: 600px; height: 600px"></div>
</template>
<script>
export default {
  name: "Graphe",
  methods: {
    setChart() {
      var chartDom = document.getElementById("main");
      var myChart = echarts.init(chartDom);
      var option;

      option = {
        tooltip: {},
        animationDurationUpdate: 1500,
        animationEasingUpdate: "quinticInOut",
        series: [
          {
            type: "graph",
            layout: "none",
            symbolSize: 50,
            roam: true,
            label: {
              show: true,
            },
            edgeSymbol: ["circle", "arrow"],
            edgeSymbolSize: [4, 10],
            edgeLabel: {
              fontSize: 20,
            },
            data: [
              {
                name: "Node 1",
                x: 300,
                y: 300,
              },
              {
                name: "Node 2",
                x: 800,
                y: 300,
              },
            ],

            // links: [],
            links: [
              {
                source: 0,
                target: 1,
                symbolSize: [5, 20],
                label: {
                  show: false,
                },
                lineStyle: {
                  width: 5,
                  curveness: 0.2,
                },
              },
            ],
            lineStyle: {
              opacity: 0.9,
              width: 2,
              curveness: 0,
            },
          },
        ],
      };

      option && myChart.setOption(option);
    },
  },
  mounted() {
    this.setChart();
  },
};
</script>
