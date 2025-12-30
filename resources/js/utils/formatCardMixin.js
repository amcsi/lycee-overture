import { mapGetters } from "vuex";
import formatCardText, { formatBrands } from "./formatCard";

export default {
  computed: {
    ...mapGetters("sets", ["brandsMarkupRegexp"]),
  },
  methods: {
    formatCardText(cardText) {
      return formatBrands(formatCardText(cardText), this.brandsMarkupRegexp);
    },
    formatBrands(text) {
      return formatBrands(text, this.brandsMarkupRegexp);
    },
  },
};
