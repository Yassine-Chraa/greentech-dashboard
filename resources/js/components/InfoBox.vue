<template>
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span :class="'info-box-icon ' + bg + ' elevation-1'"
        ><i :class="'fa-solid ' + icon"></i
      ></span>
      <div class="info-box-content">
        <span class="info-box-text">{{ nom }}</span>
        <span class="info-box-number"> {{ data }} </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "InfoBox",
  props: {
    nom: String,
    data: String | Number,
    bg: String,
    icon: String,
  },
};
</script>
<style lang="scss">
.content {
  .row {
    .info-box {
      height: 90% !important;
      .info-box-content {
        justify-content: space-between !important;
        .info-box-text {
          font-size: 15px !important;
        }
      }
    }
  }
}
</style>
