<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0" id="test">{{ nom }}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><router-link to="/">Home</router-link></li>
            <li class="breadcrumb-item" v-if="type != undefined">
              <a href="/clients"></a>
              <router-link to="/clients">{{ type }}</router-link>
            </li>
            <li class="breadcrumb-item active">{{ nom }}</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "PageHeader",
  props: {
    nom: String,
    type: String | undefined,
  },
};
</script>
