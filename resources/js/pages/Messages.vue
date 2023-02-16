<template>
  <div class="content-wrapper">
    <PageHeader nom="Messages" type="Gestion" />
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12" style="margin: auto; max-width: 746px">
            <div class="filtre">
              <div class="row mb-4">
                <div class="col-md-4">
                  <label>Sort Order</label>
                  <select
                    class="form-control"
                    v-model="order"
                    @change="getMessages('/api/messages?page=1')"
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
                    @click="getMessages('/api/messages?page=1')"
                  >
                    <option value="id">Id</option>
                    <option value="nom">Nom</option>
                    <option value="date">Date</option>
                  </select>
                </div>
                <div class="col-md-6 mt-3">
                  <label>La date de la commande</label>
                  <input
                    type="date"
                    class="form-control"
                    v-model="date"
                    @change="getMessages('/api/messages?page=1')"
                  />
                </div>
              </div>
              <div class="row">
                <div class="form-group">
                  <div class="input-group input-group-lg">
                    <input
                      type="search"
                      class="form-control form-control-lg"
                      placeholder="Tapez le nom de la message"
                      v-model="keyword"
                      style="font-size: 18px"
                    />
                    <div class="input-group-append">
                      <button
                        type="button"
                        class="btn btn-lg btn-default"
                        @click="getMessages()"
                      >
                        <i class="fa fa-search"></i>
                      </button>
                      <button
                        type="button"
                        class="btn btn-lg btn-default"
                        @click="
                          keyword = '';
                          getMessages();
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
                <h3 class="card-title">Messages</h3>
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
                      <th v-if="width > 560">Sujet</th>
                      <th>Date</th>
                      <th>Outils</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(message, ind) in messages" :key="ind">
                      <td>
                        {{ message.id }}
                      </td>
                      <td>
                        {{ message.nom }}
                      </td>
                      <td v-if="width > 560">
                        {{ message.sujet }}
                      </td>
                      <td>
                        {{ message.date }}
                      </td>
                      <td class="text-left">
                        <button
                          type="button"
                          class="btn btn-info btn-sm"
                          data-toggle="modal"
                          data-target="#modal-default"
                          @click="voirMessage(message)"
                        >
                          <i class="fas fa-pencil-alt"> </i>
                          Voir
                        </button>
                        <button
                          v-if="width > 560"
                          type="button"
                          class="btn btn-danger btn-sm"
                          @click="supprimerMessage(message.id)"
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
                  <li :class="prevClass" @click="getMessages(links[0].url)">
                    <a class="page-link">Previous</a>
                  </li>
                  <li
                    v-for="(link, ind) in links.slice(1, links.length - 1)"
                    :key="ind"
                    @click="getMessages(link.url)"
                    :class="link.active ? 'page-item active' : 'page-item'"
                  >
                    <a class="page-link">{{ link.label }}</a>
                  </li>
                  <li
                    :class="nextClass"
                    @click="getMessages(links[links.length - 1].url)"
                  >
                    <a class="page-link">Next</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div
      class="modal fade"
      id="modal-default"
      aria-hidden="true"
    >
      <div class="modal-dialog" style="max-width: 680px">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">
              {{ message.sujet ? message.sujet + " :" : "" }}
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
          <div class="modal-body">
            <p style="font-size: 15px; opacity: 0.8">{{ message.contenu }}</p>
            <span style="color: #444; font-weight: 600">{{
              message.email
            }}</span>
          </div>
        </div>
      </div>
    </div>
    <vue-confirm-dialog></vue-confirm-dialog>
  </div>
</template>

<script>
export default {
  name: "Messages",
  data() {
    return {
      links: [],
      prevClass: "",
      nextClass: "",
      messages: [],
      message: {},
      orderTarget: "id",
      order: "ASC",
      keyword: "",
      date: "",
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
    getMessages(url = "/api/messages?page=1") {
      axios
        .get(
          `${url}&date=${this.date}&orderTarget=${this.orderTarget}&order=${
            this.order
          }${this.keyword != "" ? "&keyword=" + this.keyword : ""}`
        )
        .then((res) => {
          this.messages = res.data.data;
           this.links = res.data.links;
          this.handlePagination();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    voirMessage(message) {
      this.message = message;
    },
    supprimerMessage(id) {
      this.$confirm({
        message: "Etes-vous sûr",
        button: {
          no: "Non",
          yes: "Oui",
        },
        callback: (confirm) => {
          if (confirm) {
            axios
              .delete(`/api/messages/${id}`)
              .then(() => {
                this.getMessages();
              })
              .catch((err) => {
                console.log(err);
              });
          }
        },
      });
    },
  },
  mounted() {
    this.getMessages();

    this.width = window.innerWidth;
    window.addEventListener("resize", () => {
      this.width = window.innerWidth;
    });
  },
};
</script>
<style lang="scss">
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
</style>
