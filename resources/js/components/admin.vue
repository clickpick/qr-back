<template>
  <el-container>
    <el-main class="wrapper">
      <el-table :data="projects" :row-class-name="markStatus" @row-click="edit" v-loading="loading">
        <el-table-column prop="name" label="Название" />
        <el-table-column prop="percent_funds" label="Собрано" />
        <!-- <el-table-column prop="winners_count" label="Победителей" /> -->
        <el-table-column prop="status" label="Статус" :formatter="mapStatus" />
        <el-table-column prop="updated_at" label="Обновлен" />
      </el-table>
    </el-main>
    <keep-alive>
      <template v-if="current">
        <el-dialog title="Редактирование" :visible.sync="modal">
          <el-form :model="current" :rules="rules" status-icon>
            <el-form-item label="Название" prop="name">
              <el-input v-model="current.name" autocomplete="off" />
            </el-form-item>
            <el-form-item label="Короткое описание" prop="description">
              <el-input v-model="current.description" type="textarea" autocomplete="off" />
            </el-form-item>
            <el-form-item label="Полное описание" prop="bid_description">
              <el-input v-model="current.big_description" type="textarea" autocomplete="off" />
            </el-form-item>
            <el-form-item label="Количество призовых мест">
              <el-input
                v-model.number="current.winners_count"
                type="number"
                autocomplete="off"
                min="1"
              >
                <template slot="append">шт.</template>
              </el-input>
            </el-form-item>
            <el-form-item label="Сумма сбора" prop="goal_funds">
              <el-input
                v-model.number="current.goal_funds"
                type="number"
                autocomplete="off"
                min="0"
              >
                <template slot="append">₽</template>
              </el-input>
            </el-form-item>
            <el-form-item label="Уже собрано" prop="raised_funds">
              <el-input
                v-model.number="current.raised_funds"
                type="number"
                autocomplete="off"
                min="0"
              >
                <template slot="append">₽</template>
              </el-input>
            </el-form-item>
            <el-form-item label="Ссылка" prop="link">
              <el-input v-model="current.link" type="url" autocomplete="off" />
            </el-form-item>
            <el-form-item label="Контакты" prop="contact">
              <el-input v-model="current.contact" type="textarea" autocomplete="off" />
            </el-form-item>
            <el-form-item label="Иконка" prop="link">
              <v-upload v-model="current.poster" />
            </el-form-item>
            <el-form-item label="Фон" prop="link">
              <v-upload v-model="current.banner" />
            </el-form-item>
            <el-form-item label="Статус">
              <el-select v-model="current.status" placeholder="Выберите">
                <el-option
                  v-for="item in statuses"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                ></el-option>
              </el-select>
            </el-form-item>
          </el-form>
          <span slot="footer">
            <el-button @click="reset">Отмена</el-button>
            <el-button @click="activate">Активировать</el-button>
            <el-button type="primary" @click="save">Сохранить</el-button>
          </span>
        </el-dialog>
      </template>
    </keep-alive>
  </el-container>
</template>

<script>
import createFormData from "object-to-formdata";
import { format, parseISO } from "date-fns";
import { ru } from "date-fns/locale"
import { createComponent, ref, onBeforeMount } from "@vue/composition-api";

export default createComponent({
  name: "v-admin",
  setup(props, ctx) {
    const projects = ref(null);
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
      projects.value.splice(index, 1, current.value);
    };

    const save = () => {
      dispatch();

      const model = Object.assign({}, current.value);
      model.banner = model.banner.raw || null;
      model.poster = model.poster.raw || null;

      const data = createFormData(Object.freeze(model));
      data.append("_method", "PUT");

      ctx.root.$axios.post(`/admin/api/projects/${model.id}`, data).then(() => {
        ctx.root.$notify({
          title: "Успешно",
          message: `Проект "${model.name}" сохранен`,
          type: "success"
        });
      }).catch((e) => {
        ctx.root.$notify({
          title: "Ошибка",
          message: `${e}`,
          type: "error"
        });
      });

      modal.value = false;
    };

    const reset = () => {
      current.value = backup;
      modal.value = false;
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
            type: "success"
          });
        }).catch((e) => {
          ctx.root.$notify({
            title: "Ошибка",
            message: `${e}`,
            type: "error"
          });
        });
      });
    };

    const rules = {};

    onBeforeMount(() => {
      load();
    });

    const statuses = ref(Object.freeze([{
      value: 0,
      label: "На рассмотрении"
    }, {
      value: 1,
      label: "Одобрен"
    }, {
      value: 2,
      label: "Отклонён"
    }]));

    const mapStatus = (project) => {
      return statuses.value.find((status) => {
        return project.status === status.value;
      }).label;
    };

    return {
      current,
      loading,
      modal,
      edit,
      save,
      reset,
      activate,
      rules,
      projects,
      markStatus,
      mapStatus,
      statuses
    }
  }
});
</script>

<style lang="scss">
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
</style>
