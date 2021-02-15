<template>
  <notifications
    position="top right"
    :duration="10000"
    class="ml-5"
    style="margin-top: 15vh;"
  >
    <template slot="body" slot-scope="props">
      <v-alert
        :type="props.item.type"
        border="left"
        elevation="2"
        color="#2A3B4D"
        dark
        dense
      >
        <v-row no-gutters>
          <v-col cols="10">
            <div
              v-html="props.item.text"
              style="font-size: 14px; text-align: center;"
            ></div>
          </v-col>
          <v-col cols="2">
            <v-btn @click="props.close" icon>
              <v-icon>{{ mdiCloseCircleOutline }}</v-icon>
            </v-btn>
          </v-col>
        </v-row>
      </v-alert>
    </template>
  </notifications>
</template>

<script>
import { mdiCloseCircleOutline } from "@mdi/js";

export default {
  data() {
    return {
      mdiCloseCircleOutline
    };
  }
};
</script>

<style scoped>
.notify {
  width: 100%;
  border-radius: 2px;
  background-color: #383838;
  box-shadow: 5px 10px 18px #888888;
  color: white;
}
</style>
