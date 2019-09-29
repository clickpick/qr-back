<template>
  <main class="main">
    <ui-card>
      <form @submit.prevent="submit">
        <h1 :class="$tt('headline5')">Подать заявку</h1>

        <v-fields :project="project" ref="fields" />

        <ui-button type="submit" raised>Отправить</ui-button>
      </form>
    </ui-card>
  </main>
</template>

<script>
import { reactive as project } from "../model/project";

export default {
  name: "v-app",
  data() {
    return {
      project
    };
  },
  methods: {
    submit() {
      this.$axios.post("test", this.$refs.fields.get());
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
}

.mdc-card {
  padding: 16px;
  width: 60vw;
  max-width: 800px;
}
</style>
