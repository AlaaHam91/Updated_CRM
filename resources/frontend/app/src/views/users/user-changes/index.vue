<template>
  <div>
    <list
      api="users/user-changes.api"
      :breadcrumbs="breadcrumbs"
      :hasLanguage="false"
      :can="$can('Cp_userChange', 'Update')"
      returnPath="user-changes-index"
    />
  </div>
</template>

<script>
import list from "./list";

export default {
  name: "user-changes-index",

  components: {
    list
  },

  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("users")
        },
        {
          text: this.$t("userChanges")
        }
      ]
    };
  }
};
</script>
