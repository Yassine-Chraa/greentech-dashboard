<template>
  <div class="content-wrapper">
    <PageHeader nom="Utilisateurs" />
    <div class="content" v-if="$attrs.isadmin == 1">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-11" style="margin: auto; max-width: 675px">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Utilisateurs</h3>
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
                      <th>email</th>
                      <th v-if="width > 560">Outils</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(user, ind) in users" :key="ind">
                      <td>
                        {{ user.id }}
                      </td>
                      <td>
                        {{ user.name }}
                      </td>
                      <td>
                        {{ user.email }}
                      </td>
                      <td v-if="width > 560" class="text-left">
                        <button
                          type="button"
                          class="btn btn-info btn-sm"
                          data-toggle="modal"
                          data-target="#modal-default"
                          @click="openForm('modifier', user)"
                        >
                          <i class="fas fa-pencil-alt"> </i>
                          Modifier
                        </button>
                        <button
                          type="button"
                          class="btn btn-danger btn-sm"
                          @click="executeAction('supprimer', user.id)"
                        >
                          <i class="fas fa-trash"> </i>
                          Supprimer
                        </button>
                        <button
                          type="button"
                          class="btn btn-warning btn-sm"
                          data-toggle="modal"
                          data-target="#modal-default"
                          @click="openForm('modifierPassword', user)"
                        >
                          <i class="fas fa-pencil"> </i>
                          Changer password
                        </button>
                        <button
                          type="button"
                          class="btn btn-secondary btn-sm mt-2"
                          @click="
                            executeAction(
                              'changeStatus',
                              user.id,
                              undefined,
                              user.isAdmin
                            )
                          "
                        >
                          <i class="fas fa-check"> </i>
                          {{
                            user.isAdmin
                              ? "Définir comme utilisateur"
                              : "Définir comme administrateur"
                          }}
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
                  <li :class="prevClass" @click="getUsers(links[0].url)">
                    <a class="page-link">Previous</a>
                  </li>
                  <li
                    v-for="(link, ind) in links.slice(1, links.length - 1)"
                    :key="ind"
                    @click="getUsers(link.url)"
                    :class="link.active ? 'page-item active' : 'page-item'"
                  >
                    <a class="page-link">{{ link.label }}</a>
                  </li>
                  <li
                    :class="nextClass"
                    @click="getUsers(links[links.length - 1].url)"
                  >
                    <a class="page-link">Next</a>
                  </li>
                </ul>
              </div>
            </div>
            <div style="width: 25%; margin: auto">
              <button
                type="button"
                class="btn btn-block btn-primary"
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
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form method="POST">
            <div class="modal-body">
              <div class="form-group" v-if="action != 'modifierPassword'">
                <label for="nom">Nom complet</label>
                <input
                  type="text"
                  class="form-control"
                  id="nom"
                  v-model="form.name"
                />
                <span
                  class="error invalid-feedback"
                  v-for="(err, ind) in errors.name"
                  :key="ind"
                  >{{ err }}</span
                >
              </div>
              <div class="form-group" v-if="action != 'modifierPassword'">
                <label for="email">Email</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  v-model="form.email"
                />
                <span
                  class="error invalid-feedback"
                  v-for="(err, ind) in errors.email"
                  :key="ind"
                  >{{ err }}</span
                >
              </div>
              <div
                class="form-group"
                v-if="action == 'modifierPassword' || action == 'ajouter'"
              >
                <label for="password">Password</label>
                <input
                  type="password"
                  class="form-control"
                  id="password"
                  v-model="form.password"
                />
                <span
                  class="error invalid-feedback"
                  v-for="(err, ind) in errors.password"
                  :key="ind"
                  >{{ err }}</span
                >
              </div>
              <div class="form-group" v-if="action == 'modifierPassword'">
                <label for="password">Nouveau password</label>
                <input
                  type="password"
                  class="form-control"
                  id="password"
                  v-model="form.nvPassword"
                />
                <span
                  class="error invalid-feedback"
                  v-for="(err, ind) in errors.nvPassword"
                  :key="ind"
                  >{{ err }}</span
                >
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button
                id="close"
                type="button"
                class="btn btn-default"
                data-dismiss="modal"
                @click="clearForm()"
              >
                Annuler
              </button>
              <button
                type="button"
                class="btn btn-primary"
                @click.prevent="executeAction(action)"
              >
                {{ action == "ajouter" ? "Ajouter" : "Modifier" }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <vue-confirm-dialog></vue-confirm-dialog>
  </div>
</template>

<script>
export default {
  name: "users",
  data() {
    return {
      form: {},
      action: "ajouter",
      users: [],
      links: [],
      prevClass: "",
      nextClass: "",
      errors: "",
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
    getUsers(url = "/api/users?page=1") {
      axios
        .get(url)
        .then((res) => {
          this.users = res.data.data;
          this.links = res.data.links;
          this.handlePagination();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    openForm(action, user = undefined) {
      this.action = action;
      this.errors = {};
      if (action == "ajouter") {
        this.form = { name: "", email: "", password: "" };
      }
      if (action == "modifier") {
        const { id, name, email } = user;
        this.form = { id, name, email };
      }
      if (action == "modifierPassword") {
        this.form = { id: user.id, password: "", nvPassword: "" };
      }
    },
    executeAction(
      action,
      id = undefined,
      nom = undefined,
      isAdmin = undefined
    ) {
      switch (action) {
        case "ajouter":
          axios
            .post("/api/users", this.form)
            .then((res) => {
              this.getUsers();
              document.getElementById("close").click();
              toastr.success("Un utilisateur a été ajouté");
            })
            .catch((err) => {
              this.errors = err.response.data.errors;
              this.errors.password[0] = this.errors.password[0].replace(
                "password",
                "mot de pass"
              );
            });
          break;
        case "modifier":
          axios
            .put(`/api/users/${this.form.id}`, this.form)
            .then((res) => {
              this.getUsers();
              document.getElementById("close").click();
              toastr.success(
                "Les informations de l'utilisateur ont été modifiées"
              );
            })
            .catch((err) => {
              this.errors = err.response.data.errors;
              this.errors.password[0] = this.errors.password[0].replace(
                "password",
                "mot de pass"
              );
              if (err.response.data.error != undefined) {
                toastr.error(err.response.data.error);
              }
            });
          break;
        case "modifierPassword":
          axios
            .put(`/api/users/${this.form.id}`, this.form)
            .then((res) => {
              this.getUsers();
              document.getElementById("close").click();
              toastr.success(
                "Le mot de pass est modifiées"
              );
            })
            .catch((err) => {
              if (err.response.data.error != undefined) {
                toastr.error(err.response.data.error);
              }
            });
          break;
        case "changeStatus":
          this.$confirm({
            message: "Etes-vous sûr",
            button: {
              no: "Non",
              yes: "Oui",
            },
            callback: (confirm) => {
              if (confirm) {
                axios
                  .put(`/api/users/${id}`, { isAdmin })
                  .then((res) => {
                    this.getUsers();
                  })
                  .catch((err) => {
                    console.log(err);
                  });
              }
            },
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
                  .delete("/api/users/" + id)
                  .then((res) => {
                    this.getUsers();
                    document.getElementById("close").click();
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
  },
  mounted() {
    this.getUsers();
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
