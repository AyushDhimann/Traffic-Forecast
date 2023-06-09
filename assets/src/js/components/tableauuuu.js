const tableauViz = document.getElementById('tableau-viz');
tableauViz.setAttribute('src', 'https://10ax.online.tableau.com/#/site/trailblazers/views/data/Sheet1/db72342f-61a2-4007-a09b-1acf98198e4c/hhhh?:iid=1');
tableauViz.setAttribute('width', '800');
tableauViz.setAttribute('height', '500');
tableauViz.setAttribute('hide-tabs', '');
tableauViz.setAttribute('toolbar', 'bottom');

const ctx = document.getElementById('chart').getContext('2d');
// Chart.js code to create bar chart...

const chartContainer = document.getElementById('chart-container');
chartContainer.style.display = 'flex';
chartContainer.style.flexDirection = 'column';
chartContainer.style.alignItems = 'center';

const chart = document.getElementById('chart');
chart.style.width = '800px';
chart.style.height = '500px';
