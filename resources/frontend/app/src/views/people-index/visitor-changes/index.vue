<template>
  <div>
    <list
      api="people-index/visitor-changes.api"
      :breadcrumbs="breadcrumbs"
      :can="$can('Cp_visit_visitorChange', 'Update')"
      returnPath="visitor-changes-index"
    />
  </div>
</template>

<script>
import list from "./../../users/user-changes/list";

export default {
  name: "visitor-changes-index",

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
          text: this.$t("visitorChanges")
        }
      ]
    };
  }
};
</script>
