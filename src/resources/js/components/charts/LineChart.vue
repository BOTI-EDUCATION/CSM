<script>
import { Line, mixins } from 'chart.js'
const { reactiveProp } = mixins



export default {
  extends: Line,
  mixins: [reactiveProp],
  props: ['chartdata','options'],
  mounted () {
    // this.chartData is created in the mixin.
    // If you want to pass options please create a local options object
    this.renderChart(this.chartData, this.options)
  }
}
</script>