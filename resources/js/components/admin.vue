<template>
  <el-tabs tab-position="left" @tab-click="middleware" value="stats">
    <el-tab-pane name="stats" label="Статистика">
      <el-container>
        <el-main class="wrapper">state</el-main>
      </el-container>
    </el-tab-pane>
    <el-tab-pane name="projects" label="Проекты" :lazy="true">
      <el-container>
        <el-main class="wrapper">
          <el-table
            :data="projects"
            :row-class-name="markStatus"
            @row-click="edit"
            v-loading="loading"
          >
            <el-table-column prop="name" label="Название" />
            <el-table-column prop="percent_funds" label="Собрано" />
            <el-table-column prop="status" label="Статус" :formatter="mapStatus" />
            <el-table-column prop="updated_at" label="Обновлен" />
          </el-table>
        </el-main>
        <el-dialog title="Редактирование" :visible.sync="modal">
          <v-form v-model="current" />
          <span slot="footer">
            <el-button @click="reset">Отмена</el-button>
            <el-button type="danger" @click="remove">Удалить</el-button>
            <el-button type="success" @click="activate">Активировать</el-button>
            <el-button type="primary" @click="save">Сохранить</el-button>
          </span>
        </el-dialog>
      </el-container>
    </el-tab-pane>
    <el-tab-pane name="new" label="Новый проект" :lazy="true">
      <el-container>
        <el-main class="wrapper">
          <v-form v-model="current" />
          <div class="item-right">
            <el-button @click="clear">Очистить</el-button>
            <el-button type="primary" @click="save">Создать</el-button>
          </div>
        </el-main>
      </el-container>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
import createFormData from "object-to-formdata";
import { format, parseISO } from "date-fns";
import { ru } from "date-fns/locale"
import { createComponent, ref, onBeforeMount } from "@vue/composition-api";
import { default as createEmptyProject } from "../model/project";
import { default as createStatusEnum } from "../model/status";
import { parse as parseError } from "../model/error";

export default createComponent({
  name: "v-admin",
  setup(props, ctx) {
    const projects = ref([]);
    const loading = ref(false);

    const formatDate = (date) => {
      return format(parseISO(date), "dd MMMM yyyy", { locale: ru });
    };

    const load = () => {
      loading.value = true;

      ctx.root.$axios.get("/admin/api/projects").then(({ data: { data }}) => {
        loading.value = false;

        projects.value = data.map((project) => {
          project.updated_at = formatDate(project.updated_at);
          project.percent_funds = `${Math.floor(100 * (project.raised_funds || 0) / (project.goal_funds || 1))}%`;
          project.poster = {
            url: project.poster_url,
            name: "Иконка"
          };
          project.banner = {
            url: project.banner_url,
            name: "Фон"
          };
          return project;
        }).sort((a, b) => b.id - a.id);
      });
    };

    const markStatus = ({ row }) => {
      if (row.is_active && row.is_finished) {
        return "status-gone";
      }

      if (row.is_active) {
        return "status-active";
      }

      if (row.is_finished) {
        return "status-finished";
      }

      return "";
    };

    const current = ref(null);
    const modal = ref(false);

    let backup = null;

    const edit = (project) => {
      backup = project;
      current.value = Object.assign({}, project);

      ctx.root.$nextTick(() => {
        modal.value = true;
      });
    };

    const dispatch = () => {
      const index = projects.value.findIndex((project) => {
        return project.id === current.value.id;
      });
      if (index === -1) {
        projects.value.unshift(current.value);
      } else {
        projects.value.splice(index, 1, current.value);
      }
    };

    const erase = () => {
      projects.value = projects.value.filter((project) => {
        return project.id !== current.value.id;
      });
    };

    const save = () => {
      dispatch();

      const model = Object.assign({}, current.value);
      model.banner = model.banner && model.banner.raw || null;
      model.poster = model.poster && model.poster.raw || null;

      const data = createFormData(Object.freeze(model));

      let url = "/request-funding";
      if (model.id) {
        data.append("_method", "PUT");
        url = `/admin/api/projects/${model.id}`;
      }

      ctx.root.$axios.post(url, data).then(() => {
        ctx.root.$notify({
          title: "Успешно",
          message: `Проект "${model.name}" сохранен`,
          customClass: "v-notification",
          type: "success"
        });
      }).catch((e) => {
        if (model.id) {
          ctx.root.$confirm("Отменить изменения? Все проекты будут перезагружены.", "Произошла ошибка", {
            confirmButtonText: "Да",
            cancelButtonText: "Нет",
            customClass: "v-notification",
            type: "error"
          }).then(() => {
            load();
          });
        } else {
          ctx.root.$notify({
            title: "Ошибка",
            message: parseError(e),
            customClass: "v-notification",
            type: "error"
          });
        }
      });

      modal.value = false;
    };

    const remove = () => {
      const model = Object.assign({}, current.value);

      ctx.root.$confirm("Вы хотите удалить проект?", "Внимание", {
        confirmButtonText: "Да",
        cancelButtonText: "Отмена",
        confirmButtonClass: "el-button--danger",
        type: "warning"
      }).then(() => {
        erase();

        ctx.root.$axios.delete(`/admin/api/projects/${model.id}`).then(() => {
          ctx.root.$notify({
            title: "Успешно",
            message: `Проект "${model.name}" удален`,
            customClass: "v-notification",
            type: "success"
          });
        }).catch((e) => {
          ctx.root.$notify({
            title: "Ошибка",
            message: parseError(e),
            customClass: "v-notification",
            type: "error"
          });
        });

        modal.value = false;
      });
    };

    const reset = () => {
      current.value = backup;
      modal.value = false;
    };

    const clear = () => {
      current.value = createEmptyProject();
    };

    const activate = () => {
      const model = Object.assign({}, current.value);

      ctx.root.$confirm("Вы хотите активировать проект?", "Внимание", {
        confirmButtonText: "Да",
        cancelButtonText: "Отмена",
        type: "warning"
      }).then(() => {
        projects.value.forEach((project) => {
          project.is_active = false;
        });
        current.value.is_active = true;

        dispatch();

        ctx.root.$axios.post(`/admin/api/projects/${model.id}/activate`).then(() => {
          ctx.root.$notify({
            title: "Успешно",
            message: `Проект "${model.name}" активирован`,
            customClass: "v-notification",
            type: "success"
          });
        }).catch((e) => {
          ctx.root.$notify({
            title: "Ошибка",
            message: parseError(e),
            customClass: "v-notification",
            type: "error"
          });
        });
      });
    };

    onBeforeMount(() => {
      load();
    });

    const statuses = ref(createStatusEnum());

    const mapStatus = (project) => {
      const status = statuses.value.find((status) => {
        return project.status === status.value;
      });
      return (status || statuses.value[0]).label;
    };

    const middleware = ({ name }) => {
      if (name === "new") {
        clear();
      }
    };

    return {
      current,
      loading,
      modal,
      edit,
      save,
      reset,
      clear,
      remove,
      activate,
      projects,
      markStatus,
      mapStatus,
      statuses,
      middleware
    }
  }
});
</script>

<style lang="scss">
.item-right {
  display: flex;
  justify-content: flex-end;
}

.el-tab-pane {
  height: 100%;
  overflow-y: auto;
}

.el-tabs__content {
  height: 100%;
}

.el-tabs.el-tabs--left {
  height: 100vh;
}

.el-tabs__item {
  padding: 0 32px;
}

.el-tabs__nav {
  margin-top: 32px;
}

.wrapper {
  max-width: 900px;
  margin: 0 auto;
}

.el-switch-group {
  display: table;
  clear: both;

  .el-switch {
    display: block;
    margin-top: 12px;
  }
}

.el-table__row {
  &.status-gone {
    background-color: #e3f2fd;
  }

  &.status-finished {
    background-color: #eeeeee;
  }

  &.status-active {
    background-color: #e8f5e9;
  }
}

.el-table--enable-row-hover {
  .el-table__body {
    .el-table__row {
      &.status-gone {
        &,
        &:hover > td {
          background-color: #e3f2fd;
        }
      }

      &.status-finished {
        &,
        &:hover > td {
          background-color: #eeeeee;
        }
      }

      &.status-active {
        &,
        &:hover > td {
          background-color: #e8f5e9;
        }
      }
    }
  }
}

.v-notification {
  .el-notification__content {
    p {
      white-space: pre-line;
    }
  }
}
</style>
