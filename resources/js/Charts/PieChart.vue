<script>
import { Pie, mixins } from "vue-chartjs";
// const { reactiveProp } = mixins

export default {
  extends: Pie,
  mixins: [mixins.reactiveProp],
  props: ["chartData", "options"],
  watch: {
    chartData() {
      this.renderChart(this.chartData, this.options);
    },
  },
  mounted() {
    this.renderChart(this.chartData, this.options);
  },
};
</script>

<style>
</style>
