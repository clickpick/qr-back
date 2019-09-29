<template>
  <fieldset>
    <v-provider name="name" rules="required|alpha_spaces" v-slot="{ errors }" class="field">
      <ui-textfield
        outlined
        id="name"
        v-model="$_project.name"
        :required="true"
        helptextId="name-helper"
      >Название проекта</ui-textfield>
      <ui-textfield-helptext
        v-if="errors && errors.length"
        id="name-helper"
        :visible="true"
      >{{ errors[0] }}</ui-textfield-helptext>
    </v-provider>

    <v-provider name="description" rules="required" v-slot="{ errors }" class="field">
      <ui-textfield
        outlined
        type="textarea"
        id="description"
        v-model="$_project.description"
        :required="true"
        helptextId="description-helper"
      >Описание проекта</ui-textfield>
      <ui-textfield-helptext
        v-if="errors && errors.length"
        id="description-helper"
        :visible="true"
      >{{ errors[0] }}</ui-textfield-helptext>
    </v-provider>

    <template v-if="additional">
      <v-provider
        name="donate"
        rules="required|numeric|min_value:0"
        v-slot="{ errors }"
        class="field"
      >
        <ui-textfield
          outlined
          id="donate"
          v-model="$_project.donate"
          :required="false"
          helptextId="donate-helper"
        >Сколько нужно собрать (в рублях)</ui-textfield>
        <ui-textfield-helptext
          v-if="errors && errors.length"
          id="donate-helper"
          :visible="true"
        >{{ errors[0] }}</ui-textfield-helptext>
      </v-provider>
    </template>

    <v-provider name="prize" rules="required" v-slot="{ errors }" class="field">
      <ui-textfield
        outlined
        id="prize"
        v-model="$_project.prize"
        :required="true"
        helptextId="prize-helper"
      >Приз за победу в проекте</ui-textfield>
      <ui-textfield-helptext
        v-if="errors && errors.length"
        id="prize-helper"
        :visible="true"
      >{{ errors[0] }}</ui-textfield-helptext>
    </v-provider>

    <template v-if="additional">
      <v-provider name="link" rules="required" v-slot="{ errors }" class="field">
        <ui-textfield
          outlined
          id="link"
          v-model="$_project.link"
          :required="false"
          helptextId="link-helper"
        >Ссылка на проект</ui-textfield>
        <ui-textfield-helptext
          v-if="errors && errors.length"
          id="link-helper"
          :visible="true"
        >{{ errors[0] }}</ui-textfield-helptext>
      </v-provider>
    </template>

    <v-provider name="contact" rules="required" v-slot="{ errors }" class="field">
      <ui-textfield
        outlined
        type="textarea"
        id="contact"
        v-model="$_project.contact"
        :required="true"
        helptextId="contact-helper"
      >Контакты для связи</ui-textfield>
      <ui-textfield-helptext
        v-if="errors && errors.length"
        id="contact-helper"
        :visible="true"
      >{{ errors[0] }}</ui-textfield-helptext>
    </v-provider>
  </fieldset>
</template>

<script>
import Vue from "vue";

export default {
  name: "v-fields",
  props: {
    additional: {
      type: Boolean,
      default: false
    },
    project: {
      required: true
    }
  },
  created() {
    this.$_project = this.project;
  },
  watch: {
    project: {
      deep: true,
      immediate: true,
      handler(val) {
        Vue.set(this, "$_project", { ...this.$_project, ...val });
      }
    }
  },
  methods: {
    get() {
      return this.$_project;
    }
  }
}
</script>

<style>
fieldset {
  padding: 0;
  margin: 0;
  border: 0;
}

.field {
  display: block;
  margin: 8px 0;
}

.mdc-text-field {
  width: 100%;
}

.mdc-text-field--textarea textarea.mdc-text-field__input {
  min-height: 44px;
  margin-top: 16px;
  padding-bottom: 0px;
}

.mdc-text-field--textarea .mdc-floating-label {
  margin-top: 5px;
}

.mdc-text-field--textarea .mdc-floating-label--float-above {
  margin-top: 0;
}
</style>
