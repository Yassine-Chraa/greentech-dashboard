<template>
  <li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#" style="font-size: 18px">
      <i class="far fa-bell"></i>
      <span class="badge badge-warning navbar-badge">{{produits.length}}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
      <span class="dropdown-item dropdown-header">{{produits.length}} Notifications</span>
      <div class="dropdown-divider"></div>
      <div v-for="(produit, ind) in produits" :key="ind">
        <a href="#" class="dropdown-item" style="font-size: 14px">
          <i class="fas fa-warning mr-2" style="color: #d2d232"></i>
          {{ produit.nom }} est presque fini
        </a>
      </div>
    </div>
  </li>
</template>
<script>
export default {
  name: "Notifications",
  data() {
    return {
      produits: [],
    };
  },
  methods: {
    checkStock() {
      axios
        .get("/api/produits?page=0")
        .then((res) => {
          res.data.forEach((produit) => {
            if (produit.stock < 5) {
              this.produits.push(produit);
            }
          });
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
  created() {
    this.checkStock();
  },
};
</script>
