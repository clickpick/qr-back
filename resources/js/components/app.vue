<template>
  <div class="balmui-container balmui-container--flex">
    <ui-permanent-drawer menu-selector="#menu">
      <ui-drawer-header>
        <ui-drawer-title>{{ title }}</ui-drawer-title>
        <ui-drawer-subtitle>{{ subtitle }}</ui-drawer-subtitle>
      </ui-drawer-header>
      <ui-drawer-content>
        <ui-list-nav></ui-list-nav>
      </ui-drawer-content>
    </ui-permanent-drawer>

    <main :class="[$tt('body1'), 'main']">
      <ui-top-app-bar content-selector=".main" nav-id="menu" id="menu" fixed>
        <template #nav-icon>
          <ui-icon @click="$router.back()">arrow_back</ui-icon>
        </template>
        Temporary Drawer
      </ui-top-app-bar>
      <div :class="$tt('body2')">
        <h3 :class="$tt('headline6')">Текущий проект</h3>
        <ui-table :data="[all.data[0]]" :thead="all.thead" :tbody="all.tbody" fullwidth></ui-table>
        <h3 :class="$tt('headline6')">Все проекты</h3>
        <ui-table :data="all.data" :thead="all.thead" :tbody="all.tbody" fullwidth></ui-table>
      </div>
    </main>
  </div>
</template>

<script>
export default {
  name: "v-app",
  data() {
    return {
      title: "Mail",
      subtitle: "ui@balmjs.com",
      all: {
        data: [
          {
            id: 1,
            dessert: "Frozen yogurt",
            calories: 159,
            fat: 6,
            carbs: 24,
            protein: 4
          },
          {
            id: 2,
            dessert: "Ice cream sandwich",
            calories: 237,
            fat: 9,
            carbs: 37,
            protein: 4.3
          },
          {
            id: 3,
            dessert: "Eclair",
            calories: 262,
            fat: 16,
            carbs: 24,
            protein: 6
          }
        ],
        thead: [
          "ID",
          "Dessert (100g serving)",
          "Calories",
          "Fat (g)",
          "Carbs (g)",
          "Protein (g)"
        ],
        tbody: ["id", "dessert", "calories", "fat", "carbs", "protein"]
      },
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
</style>
