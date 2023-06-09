Traffic Forecast
===========
![PHP status](https://github.com/Trailblazers/Traffic-Forecast/workflows/PHP/badge.svg)
![ESLint status](https://github.com/Trailblazers/Traffic-Forecast/workflows/JS/badge.svg)
![Active installs](https://img.shields.io/wordpress/plugin/installs/Traffic-Forecast.svg)
![Downloads](https://img.shields.io/wordpress/plugin/dt/Traffic-Forecast.svg)
[![Rating](https://img.shields.io/wordpress/plugin/r/Traffic-Forecast.svg)](https://wordpress.org/support/plugin/Traffic-Forecast/reviews/)
[![License: GPLv3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

[Traffic Forecast](https://www.TrafficForecast.com/) is an open-source and privacy-friendly Forecast plugin for WordPress. 

![Screenshot of the Traffic Forecast dashboard](https://github.com/Trailblazers/Traffic-Forecast/raw/master/assets/src/img/screenshot-1.png?v=1)

## Features

- No external services. Data is yours and yours alone.
- No personal information or anything visitor specific is tracked.
- Fast! Handles thousands of daily visitors or sudden bursts of traffic without breaking a sweat.
- Lightweight. Adds only 985 bytes of data to your pages.
- Plug and play. Just install and activate the plugin and stats will be recorded right away.
- Open-source (GPLv3 licensed).

### How to install

To run the latest development version of the plugin, take the following steps.

First, clone the repository using Git in your `/wp-content/plugins/` directory
```
git clone git@github.com:Trailblazers/Traffic-Forecast.git
```

Create the autoloader using Composer.
```
composer install
```

Install client-side dependencies using NPM
```
npm install
```

Build the plugin assets by issuing the following command:
``` 
npm run build
```

### Usage

Stats will be collected right away after you install and activate the plugin. You can view your stats on the **Dashboard > Forecast** page.

### Contributing

You can contribute to Traffic Forecast in many different ways. For example:

- Write about the plugin on your blog or share it on social media.
- [Vote on features in the GitHub issue list](https://github.com/Trailblazers/Traffic-Forecast/issues?q=is%3Aopen+is%3Aissue+label%3A%22feature+suggestion%22).
- [Translate the plugin into your language](https://translate.wordpress.org/projects/wp-plugins/Traffic-Forecast/stable/) using your WordPress.org account.
- Help fund development and support costs through [Traffic Forecast on OpenCollective](https://opencollective.com/Traffic-Forecast).

### License

GNU General Public License v3.0
