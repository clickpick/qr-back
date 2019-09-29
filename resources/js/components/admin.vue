<template>
  <div class="balmui-container balmui-container--flex">
    <main :class="[$tt('body1'), 'main']">
      <ui-top-app-bar content-selector=".main" nav-id="menu" id="menu" fixed>
        <template #nav-icon>
          <ui-icon @click="$router.back()">arrow_back</ui-icon>
        </template>
        QR
      </ui-top-app-bar>
      <div :class="$tt('body2')">
        <h3 :class="$tt('headline6')">Текущий проект</h3>
        <ui-table :data="active" :thead="projects.head" :tbody="projects.schema" fullwidth></ui-table>
        <h3 :class="$tt('headline6')">Все проекты</h3>
        <ui-table :data="projects.data" :thead="projects.head" :tbody="projects.schema" fullwidth></ui-table>
      </div>
    </main>
    <ui-dialog :open="open" @confirm="onUpdateProject">
      <ui-dialog-title>Изменение заявки</ui-dialog-title>
      <ui-dialog-content>
        <v-fields :project="project" ref="fields" :additional="true" />
        <ui-text-divider>Постер (иконка)</ui-text-divider>
        <ui-file accept="image/*" preview @change="$_setPoster" text="Загрузить" />
        <transition-group class="preview-list" name="list" tag="ul">
          <li class="item" v-if="project.poster" :key="project.poster.uuid">
            <div class="inner">
              <img class="preview" :src="project.poster.previewSrc" />
              <span class="name">{{ project.poster.name }}</span>
            </div>
          </li>
        </transition-group>
        <ui-text-divider>Баннер (подложка)</ui-text-divider>
        <ui-file accept="image/*" preview @change="$_setBanner" text="Загрузить" />
        <transition-group class="preview-list" name="list" tag="ul">
          <li class="item" v-if="project.banner" :key="project.banner.uuid">
            <div class="inner">
              <img class="preview" :src="project.banner.previewSrc" />
              <span class="name">{{ project.banner.name }}</span>
            </div>
          </li>
        </transition-group>
      </ui-dialog-content>
      <ui-dialog-actions />
    </ui-dialog>
  </div>
</template>

<script>
import { reactive as project } from "../model/project";
import { default as EventBus } from "vue";

export default {
  name: "v-admin",
  data() {
    return {
      open: null,
      active: [project],
      project,
      projects: {
        data: [project],
        head: [
          "ID",
          "Название",
          "Описание",
          "Сумма сбора",
          "Приз",
          "Ссылка на фонд",
          "Обратная связь"
        ],
        schema: ["id", "name", "description", "donate", "prize", "link", "contact"]
      }
    };
  },
  mounted() {
    this.$_registerClick();
  },
  updated() {
    this.$_registerClick();
  },
  created() {
    this.$axios.get("/admin/api/projects").then((response) => {
      [this.active, this.projects] = response.reduce((result, item) => {
        if (item.isActive) {
          result[0].push(item);
        } else {
          result[1].push(item);
        }

        return result;
      }, [[], []]);
    });

    this.bus = new EventBus();

    this.bus.$on("row.click", (id) => {
      if (id >= 0 && id < this.projects.data.length) {
        this.project = this.projects.data[id];
        this.open = true;
      }
    });
  },
  methods: {
    $_registerClick() {
      Array.from(this.$el.getElementsByClassName("mdc-data-table__row")).forEach((el, id) => {
        el.onclick = () => this.bus.$emit("row.click", id);
      });
    },
    $_setPoster(files) {
      if (files && files.length && files[0]) {
        this.project.poster = files[0];
      }
    },
    $_setBanner(files) {
      if (files && files.length && files[0]) {
        this.project.banner = files[0];
      }
    },
    onUpdateProject(result) {
      this.open = false;

      if (result) {
        this.$axios.post("/admin/update", this.$refs.fields.get());
      }
    }
  }
}
</script>

<style>
.main {
  position: relative;
  flex-grow: 1;
}

.main .mdc-top-app-bar--fixed {
  top: 0;
}

.main .mdc-typography--body2 {
  padding: 16px;
}

.balmui-container--flex {
  display: flex;
  height: 100vh;
}

.mdc-data-table__cell:nth-child(1) {
  text-align: right;
}
.mdc-data-table__cell:nth-child(2) {
  width: 8vw;
}
.mdc-data-table__cell:nth-child(3) {
  width: 20vw;
}
.mdc-data-table__cell:nth-child(4) {
  width: 6vw;
  text-align: right;
}
.mdc-data-table__cell:nth-child(5) {
  width: 12vw;
}
.mdc-data-table__cell:nth-child(6) {
  width: 22vw;
}
</style>
