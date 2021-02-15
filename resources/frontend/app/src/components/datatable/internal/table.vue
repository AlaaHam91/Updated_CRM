<template>
  <table>
    <thead>
      <tr>
        <th style="min-width: 30px; max-width: 30px;">&nbsp;</th>
        <th v-if="!readonly" style="min-width: 30px; max-width: 30px;">
          &nbsp;
        </th>
        <th
          v-for="(header, x) in headers"
          :key="x"
          v-show="header.visible"
          class="text-body-2"
          v-text="header.text"
          :style="colStyle(header.width)"
        >
          <div class="verticalGrip">&nbsp;</div>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(item, y) in items" :key="item.id">
        <td class="text-center" v-text="y + 1"></td>
        <td v-if="!readonly" class="text-center">
          <input type="checkbox" :value="item.id" v-model="selectedRows" />
        </td>
        <td
          v-for="(header, j) in headers"
          :key="j"
          v-show="header.visible"
          :style="colStyle(header.width)"
        >
          <div
            :class="
              errors[y][header.value] && errors[y][header.value].hasError
                ? 'error-c'
                : ''
            "
            class="d-flex align-center cell-content"
          >
            <cell
              :headerId="header.id"
              :itemId="item.id"
              :type="header.type"
              :min="header.min ? header.min : undefined"
              :step="header.step"
              :store-module="storeModule"
              :readonly="header.readonly"
              :rules="header.rules"
              :dedicatedOptionsName="
                header.dedicatedOptions ? header.dedicatedOptions : undefined
              "
              :linkLabel="header.linkLabel"
            />
            <v-tooltip v-if="errors[y][header.value].hasError" bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  small
                  color="error"
                  class="mx-2"
                  v-bind="attrs"
                  v-on="on"
                >
                  mdi-information-outline
                </v-icon>
              </template>
              <span v-text="errors[y][header.value].msg"></span>
            </v-tooltip>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</template>
<script>
import cell from "./cell";

export default {
  name: "items-table",
  props: {
    storeModule: String
  },

  components: {
    cell
  },

  data() {
    return {};
  },

  computed: {
    headers() {
      return this.$store.getters[`${this.storeModule}/headers`];
    },
    items() {
      let items = this.$store.getters[`${this.storeModule}/items`];
      return items.filter(e => e.status !== "Delete");
    },
    selectedRows: {
      get() {
        return this.$store.getters[`${this.storeModule}/selectedRows`];
      },
      set(ids) {
        this.$store.commit(`${this.storeModule}/selectedRows`, { ids: ids });
      }
    },
    readonly() {
      return this.$store.getters[`${this.storeModule}/readonly`];
    },
    errors() {
      return this.$store.getters[`${this.storeModule}/errors`];
    }
  },

  mounted() {
    this.$nextTick(() => {
      let pageX, curCol, nxtCol, curColWidth, nxtColWidth;

      Array.prototype.forEach.call(
        document.querySelectorAll(`#${this.storeModule} .verticalGrip`),
        function(grip) {
          document.dir === "rtl"
            ? (grip.style.left = 0)
            : (grip.style.right = 0);

          grip.addEventListener("mousedown", function(e) {
            curCol = e.target.parentElement;
            nxtCol = curCol.nextElementSibling;
            pageX = e.pageX;
            curColWidth = curCol.offsetWidth;
            if (nxtCol) nxtColWidth = nxtCol.offsetWidth;
          });
        }
      );

      document.addEventListener("mousemove", function(e) {
        if (curCol) {
          let diffX = e.pageX - pageX;

          if (nxtCol)
            document.dir === "rtl"
              ? (nxtCol.style.width = nxtColWidth + diffX + "px")
              : (nxtCol.style.width = nxtColWidth - diffX + "px");

          document.dir === "rtl"
            ? (curCol.style.width = curColWidth - diffX + "px")
            : (curCol.style.width = curColWidth + diffX + "px");
        }
      });

      document.addEventListener("mouseup", function() {
        curCol = undefined;
        nxtCol = undefined;
        pageX = undefined;
        nxtColWidth = undefined;
        curColWidth = undefined;
      });
    });
  },

  methods: {
    colStyle(width) {
      return `width: ${width}; max-width: ${width}; min-width: ${width};`;
    }
  }
};
</script>

<style scoped>
table {
  border-collapse: collapse;
  border-spacing: 0;
  width: auto;
  border: 1px solid #ddd;
}

tr:nth-child(even) {
  background-color: #f2f2f2;
}

th {
  position: sticky;
  z-index: 1;
  text-align: center;
  background: #fff;
}

thead th {
  top: 0;
}

td,
th {
  padding: 0;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  border: 1px solid lightgray;
  height: 1px;
}

.verticalGrip {
  position: absolute;
  top: 0;
  bottom: 0;
  width: 5px;
  cursor: col-resize;
  z-index: 20;
}

.cell-content {
  min-height: 36px;
  /* max-height: 36px; */
}

.error-c {
  border: 1px solid red !important;
  width: 100%;
  height: 100%;
}
</style>
