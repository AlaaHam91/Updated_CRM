<script>
export default {
  methods: {
    $can(section, permission) {
      return this.$store.getters["auth/can"](section, permission);
    },
    $canMsg(section, permission, msg = null) {
      if (!this.$store.getters["auth/can"](section, permission)) {
        let notify = {
          text: msg ? msg : this.$t("permissionDenied"),
          type: "error"
        };
        this.$notify(notify);
        return false;
      } else return true;
    }
  }
};
</script>
