<script>
import { cloneDeep, isEqual } from "lodash";

export default {
  methods: {
    compareItems(oldItems, newItems) {
      let result = [];

      //search for added/updated/not-changed
      for (let i = 0; i < newItems.length; i++) {
        let newItem = newItems[i];

        let index = oldItems.findIndex(e => e.id === newItem.id);
        if (index < 0) {
          let addItem = cloneDeep(newItem);
          addItem["status"] = "Add";
          result.push(addItem);
        } else {
          let oldItem = oldItems[index];
          if (isEqual(oldItem, newItem) == true) {
            let addItem = cloneDeep(newItem);
            addItem["status"] = "None";
            result.push(addItem);
          } else {
            let addItem = cloneDeep(newItem);
            addItem["status"] = "Update";
            result.push(addItem);
          }
        }
      }

      //search for deleted
      for (let i = 0; i < oldItems.length; i++) {
        let oldItem = oldItems[i];

        let index = newItems.findIndex(e => e.id === oldItem.id);
        if (index < 0) {
          let addItem = cloneDeep(oldItem);
          addItem["status"] = "Delete";
          result.push(addItem);
        }
      }
      return result;
    }
  }
};
</script>
