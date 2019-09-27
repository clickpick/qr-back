<template>
  <div>
    <ui-top-app-bar fixed content-selector=".demo-main" nav-id="demo-menu">Temporary Drawer</ui-top-app-bar>

    <ui-modal-drawer menu-selector="#demo-menu">
      <ui-drawer-header>
        <ui-drawer-title>{{ title }}</ui-drawer-title>
        <ui-drawer-subtitle>{{ subtitle }}</ui-drawer-subtitle>
      </ui-drawer-header>
      <ui-drawer-content>
        <ui-list-nav>
          <ui-item-a first-icon="arrow_back" @click.native="() => { $router.back(); }">Back</ui-item-a>
          <ui-list-divider></ui-list-divider>
          <template v-for="(item, index) in menu">
            <ui-list-divider v-if="item === '-'" :key="`divider-${index}`"></ui-list-divider>
            <template v-else>
              <ui-list-group-subheader
                v-if="item.subheader"
                :key="`subheader-${index}`"
              >{{ item.subheader }}</ui-list-group-subheader>
              <ui-item-a
                v-for="(subItem, subIndex) in item.items"
                :key="`item-${index}-${subIndex}`"
                :first-icon="subItem.icon"
              >{{ subItem.name }}</ui-item-a>
            </template>
          </template>
        </ui-list-nav>
      </ui-drawer-content>
    </ui-modal-drawer>

    <ui-drawer-scrim />

    <main :class="[$tt('body1'), 'demo-main']">
      <div :class="$tt('body2')">
        <h1 :class="$tt('headline4')">Temporary Drawer</h1>
        <p>Click the menu icon above to open.</p>
      </div>
    </main>
  </div>
</template>

<script>
export default {
  name: "app",
  data() {
    return {
      title: "Mail",
      subtitle: "ui@balmjs.com",
      menu: [
        {
          items: [
            {
              icon: "inbox",
              name: "Inbox"
            },
            {
              icon: "star",
              name: "Star"
            },
            {
              icon: "send",
              name: "Sent Mail"
            },
            {
              icon: "drafts",
              name: "Drafts"
            }
          ]
        },
        "-",
        {
          subheader: "Labels",
          items: [
            {
              icon: "email",
              name: "All Mail"
            },
            {
              icon: "delete",
              name: "Trash"
            },
            {
              icon: "report",
              name: "Spam"
            }
          ]
        },
        "-",
        {
          items: [
            {
              icon: "settings",
              name: "Settings"
            },
            {
              icon: "announcement",
              name: "Help & feedback"
            }
          ]
        }
      ]
    };
  }
}
</script>

<style>
</style>
