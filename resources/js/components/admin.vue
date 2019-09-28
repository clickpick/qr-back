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
        <ui-table :data="current" :thead="projects.head" :tbody="projects.schema" fullwidth></ui-table>
        <h3 :class="$tt('headline6')">Все проекты</h3>
        <ui-table :data="projects.data" :thead="projects.head" :tbody="projects.schema" fullwidth></ui-table>
      </div>
    </main>
    <ui-dialog :open="open" @confirm="onUpdateProject">
      <ui-dialog-title>Изменение заявки</ui-dialog-title>
      <ui-dialog-content>
        <v-fields :project="edit" />
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
      edit: project,
      current: {
        id: 1,
        name: "WWF",
        desc: "Амурский тигр",
        donate: 2387000,
        prize: "Стикеры VK fest",
        link: "https://wwf.ru/help/projects/fight-for-the-amur-tiger",
        contact: "https://vk.com/chingis_balbarov"
      },
      projects: {
        data: [
          {
            id: 1,
            name: "WWF",
            desc: "Амурский тигр",
            donate: 2387000,
            prize: "Стикеры VK fest",
            link: "https://wwf.ru/help/projects/fight-for-the-amur-tiger",
            contact: "https://vk.com/chingis_balbarov"
          },
          {
            id: 2,
            name: "Another",
            desc: "Победа в VK Hackathon",
            donate: 0,
            prize: "Поднятие самооценки",
            link: "https://vk.com/hackathon",
            contact: "click"
          },
          {
            id: 3,
            name: "Next",
            desc: "Победа в VK Cup",
            donate: 0,
            prize: "Поднятие самооценки",
            link: "https://vk.com/cup",
            contact: "click"
          }
        ],
        head: [
          "ID",
          "Название",
          "Описание",
          "Сумма сбора",
          "Приз",
          "Ссылка на фонд",
          "Обратная связь"
        ],
        schema: ["id", "name", "desc", "donate", "prize", "link", "contact"]
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
    this.bus = new EventBus();

    this.bus.$on("row.click", (id) => {
      if (id >= 0 && id < this.projects.data.length) {
        this.edit = this.projects.data[id];
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
    onUpdateProject(result) {
      this.open = false;

      if (result) {
        this.$axios.post("test", this.edit);
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
