import Chart from './components/chart'
import { render, h } from 'preact'
const el = document.getElementById('Traffic-Forecast-dashboard-widget-mount')
const now = new Date()
const startDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 14, 0, 0, 0)
const endDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59)

function maybeRender() {
  if (el.clientWidth) {
    render(<Chart startDate={startDate} endDate={endDate} height={200} width={el.clientWidth} />, el)
  }
}

window.jQuery(document).on('postbox-toggled', maybeRender)
maybeRender();
