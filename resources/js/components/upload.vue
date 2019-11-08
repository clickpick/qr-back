<template>
  <div class="v-upload">
    <el-upload
      ref="upload"
      action="#"
      list-type="picture-card"
      accept="image/*"
      :file-list="list"
      :limit="1"
      :multiple="false"
      :auto-upload="false"
      :before-upload="save"
      :on-change="save"
    >
      <i slot="default" class="el-icon-plus" />
      <div slot="file" slot-scope="{ file: model }">
        <img class="el-upload-list__item-thumbnail" :src="model.url" :alt="model.name" />
        <span class="el-upload-list__item-actions">
          <span class="el-upload-list__item-preview" @click="show">
            <i class="el-icon-zoom-in" />
          </span>
          <span class="el-upload-list__item-delete" @click="remove">
            <i class="el-icon-delete" />
          </span>
        </span>
      </div>
    </el-upload>
    <el-dialog :visible.sync="preview" append-to-body>
      <keep-alive>
        <template v-if="list[0]">
          <img width="100%" :src="list[0].url" :alt="list[0].name" />
        </template>
      </keep-alive>
    </el-dialog>
  </div>
</template>

<script>
import { createComponent, ref } from "@vue/composition-api";

export default createComponent({
  name: "v-upload",
  model: {
    prop: "value",
    event: "change"
  },
  props: {
    value: {
      type: Object
    }
  },
  setup(props, ctx) {
    const upload = ref(null);
    const preview = ref(false)
    const list = ref(props.value ? [props.value] : []);

    const show = () => {
      preview.value = true;
    };

    const remove = () => {
      list.value = [];
      upload.value.clearFiles();
    };

    const save = (model) => {
      ctx.emit("change", list.value[0] = model);
    };

    return {
      list,
      preview,
      show,
      remove,
      save,
      upload
    };
  }
});
</script>

<style lang="scss">
.v-upload {
  display: table;
  clear: both;

  .el-upload-list {
    & + .el-upload {
      display: none;
    }

    &:empty + .el-upload {
      display: inline-block;
    }
  }
}
</style>
