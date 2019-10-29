<template>
  <main class="main">
    <template v-if="send">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="loader">
        <path
          fill="#6200ee"
          d="M.19 54.444C2.58 81.954 26.934 102.2 54.444 99.81c27.51-2.39 47.852-26.716 45.366-54.254l-8.455.73c2.009 22.808-14.804 42.965-37.612 44.973-22.809 2.009-42.965-14.804-44.974-37.612l-8.58.797z"
        >
          <animateTransform
            attributeName="transform"
            attributeType="XML"
            dur="2s"
            from="0 50 50"
            repeatCount="indefinite"
            to="360 50 50"
            type="rotate"
          />
        </path>
        <path
          fill="#6200ee"
          d="M62.12 54.532c-2.44 6.71-9.972 10.168-16.652 7.589-6.71-2.441-10.168-9.973-7.589-16.653l-6.433-2.382c-3.843 10.243 1.451 21.708 11.64 25.468 10.243 3.843 21.708-1.451 25.468-11.64l-6.433-2.382z"
        >
          <animateTransform
            attributeName="transform"
            attributeType="XML"
            dur="1s"
            from="0 50 50"
            repeatCount="indefinite"
            to="-360 50 50"
            type="rotate"
          />
        </path>
        <path
          fill="#6200ee"
          d="M45.371 15.257C26.157 17.808 12.706 35.414 15.257 54.63S35.414 87.294 54.63 84.743l-1.103-8.253C38.878 78.412 25.43 68.174 23.51 53.526S31.826 25.43 46.474 23.51l-1.103-8.253z"
        >
          <animateTransform
            attributeName="transform"
            attributeType="XML"
            dur="2s"
            from="0 50 50"
            repeatCount="indefinite"
            to="360 50 50"
            type="rotate"
          />
        </path>
      </svg>
    </template>
    <template v-else>
      <ui-card>
        <form @submit.prevent="submit">
          <h1 :class="$tt('headline5')">Подать заявку</h1>
          <v-fields :project="project" ref="fields" />
          <ui-button type="submit" raised>Отправить</ui-button>
        </form>
      </ui-card>
    </template>
  </main>
</template>

<script>
import { reactive as project } from "../model/project";

export default {
  name: "v-app",
  data() {
    return {
      project,
      send: false
    };
  },
  methods: {
    submit() {
      this.send = true;
      this.$axios.post("/request-funding", this.$refs.fields.get()).then(() => {
        this.send = false;
        this.$alert({
          title: "Успех",
          message: "Заявка успешно отправлена",
          buttonText: "Отправить еще одну"
        }).then(() => {
          window.location.reload();
        });
      }).catch(() => {
        this.$alert({
          title: "Ошибка",
          message: "Не удалось отправить заявку",
          buttonText: "Сбросить форму"
        }).then(() => {
          window.location.reload();
        });
      });
    }
  }
}
</script>

<style>
.main {
  display: flex;
  justify-content: center;
  justify-items: center;
  align-content: center;
  align-items: center;

  min-height: 100vh;
  padding: 32px;
  box-sizing: border-box;
}

.mdc-card {
  padding: 16px;
  width: 60vw;
  max-width: 800px;
}

.loader {
  width: 100px;
  height: 100px;

  display: inline-block;
}
</style>
