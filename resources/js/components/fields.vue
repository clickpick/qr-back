<template>
  <fieldset>
    <v-provider name="name" rules="required|alpha_spaces" v-slot="{ errors }" class="field">
      <ui-textfield
        outlined
        id="name"
        v-model="project.name"
        :required="true"
        helptextId="name-helper"
      >Название проекта</ui-textfield>
      <ui-textfield-helptext
        v-if="errors && errors.length"
        id="name-helper"
        :visible="true"
      >{{ errors[0] }}</ui-textfield-helptext>
    </v-provider>

    <v-provider name="desc" rules="required" v-slot="{ errors }" class="field">
      <ui-textfield
        outlined
        type="textarea"
        id="desc"
        v-model="project.desc"
        :required="true"
        helptextId="desc-helper"
      >Описание проекта</ui-textfield>
      <ui-textfield-helptext
        v-if="errors && errors.length"
        id="desc-helper"
        :visible="true"
      >{{ errors[0] }}</ui-textfield-helptext>
    </v-provider>

    <v-provider
      name="donate"
      rules="required|numeric|min_value:0"
      v-slot="{ errors }"
      class="field"
    >
      <ui-textfield
        outlined
        id="donate"
        v-model="project.donate"
        :required="true"
        helptextId="donate-helper"
      >Сколько нужно собрать (в рублях)</ui-textfield>
      <ui-textfield-helptext
        v-if="errors && errors.length"
        id="donate-helper"
        :visible="true"
      >{{ errors[0] }}</ui-textfield-helptext>
    </v-provider>

    <v-provider name="prize" rules="required" v-slot="{ errors }" class="field">
      <ui-textfield
        outlined
        id="prize"
        v-model="project.prize"
        :required="true"
        helptextId="prize-helper"
      >Приз за победу в проекте</ui-textfield>
      <ui-textfield-helptext
        v-if="errors && errors.length"
        id="prize-helper"
        :visible="true"
      >{{ errors[0] }}</ui-textfield-helptext>
    </v-provider>

    <v-provider name="link" rules="required" v-slot="{ errors }" class="field">
      <ui-textfield
        outlined
        id="link"
        v-model="project.link"
        :required="true"
        helptextId="link-helper"
      >Ссылка на проект</ui-textfield>
      <ui-textfield-helptext
        v-if="errors && errors.length"
        id="link-helper"
        :visible="true"
      >{{ errors[0] }}</ui-textfield-helptext>
    </v-provider>

    <v-provider name="contact" rules="required" v-slot="{ errors }" class="field">
      <ui-textfield
        outlined
        type="textarea"
        id="contact"
        v-model="project.contact"
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
export default {
  name: "v-fields",
  props: {
    project: {
      required: true
    }
  }
}
</script>

<style>
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
