<template>
  <div>
    <list
      api="people-index/customer-changes.api"
      :breadcrumbs="breadcrumbs"
      :can="$can('Cp_cust_customerChange', 'Update')"
      returnPath="customer-changes-index"
    />
  </div>
</template>

<script>
import list from "./../../users/user-changes/list";

export default {
  name: "customer-changes-index",

  components: {
    list
  },

  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("peopleIndex")
        },
        {
          text: this.$t("customerChanges")
        }
      ]
    };
  }
};
</script>
