<template>
  <div class="v-form">
    <keep-alive>
      <template v-if="model">
        <el-form :model="model" :rules="rules" status-icon>
          <el-form-item label="Название" prop="name">
            <el-input v-model="model.name" autocomplete="off" />
          </el-form-item>
          <el-form-item label="Короткое описание" prop="description">
            <el-input v-model="model.description" type="textarea" autocomplete="off" />
          </el-form-item>
          <el-form-item label="Полное описание" prop="bid_description">
            <el-input v-model="model.big_description" type="textarea" autocomplete="off" />
          </el-form-item>
          <el-form-item label="Призы" prop="prize">
            <el-input v-model="model.prize" type="textarea" autocomplete="off" />
          </el-form-item>
          <el-form-item label="Количество призовых мест">
            <el-input v-model.number="model.winners_count" type="number" autocomplete="off" min="1">
              <template slot="append">шт.</template>
            </el-input>
          </el-form-item>
          <el-form-item label="Сумма сбора" prop="goal_funds">
            <el-input v-model.number="model.goal_funds" type="number" autocomplete="off" min="0">
              <template slot="append">₽</template>
            </el-input>
          </el-form-item>
          <el-form-item label="Уже собрано" prop="raised_funds">
            <el-input v-model.number="model.raised_funds" type="number" autocomplete="off" min="0">
              <template slot="append">₽</template>
            </el-input>
          </el-form-item>
          <el-form-item label="Ссылка" prop="link">
            <el-input v-model="model.link" type="url" autocomplete="off" />
          </el-form-item>
          <el-form-item label="Контакты" prop="contact">
            <el-input v-model="model.contact" type="textarea" autocomplete="off" />
          </el-form-item>
          <el-form-item label="Иконка" prop="link">
            <v-upload v-model="model.poster" />
          </el-form-item>
          <el-form-item label="Фон" prop="link">
            <v-upload v-model="model.banner" />
          </el-form-item>
          <el-form-item label="Статус">
            <el-select v-model="model.status" placeholder="Выберите">
              <el-option
                v-for="item in statuses"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              ></el-option>
            </el-select>
          </el-form-item>
        </el-form>
      </template>
    </keep-alive>
  </div>
</template>

<script>
import { createComponent, ref, watch } from "@vue/composition-api";
import { default as createStatusEnum } from "../model/status";

export default createComponent({
  name: "v-form",
  props: {
    value: {
      type: Object
    }
  },
  model: {
    prop: "value",
    event: "change"
  },
  setup(props, ctx) {
    const rules = {};
    const model = ref(props.value);
    const statuses = ref(createStatusEnum());

    watch(model, (model) => {
      ctx.emit("change", model)
    });

    return {
      model,
      rules,
      statuses
    };
  }
});
</script>
