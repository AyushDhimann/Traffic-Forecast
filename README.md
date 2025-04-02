# Traffic Forecast

[![License: GPLv3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

![Screenshot of the Traffic Forecast dashboard](https://github.com/AyushDhimann/Traffic-Forecast/blob/master/src/original.png)

## Working Demo on Youtube

[![Watch the video](https://img.youtube.com/vi/7Y-3RdS5jDA/hqdefault.jpg)](https://www.youtube.com/watch?v=7Y-3RdS5jDA)

## Features

- Linked with your Tableau Cloud Workbook.
- No external services. Data is yours and yours alone.
- No personal information or anything visitor specific is tracked.
- Fast! Handles thousands of daily visitors or sudden bursts of traffic without breaking a sweat.
- Lightweight. Adds only 985 bytes of data to your pages.
- Open-source (GPLv3 licensed).

## Pending Features

- To send Wordpress Data to Tableau.

### How to install

To install the Traffic Forecast plugin,

1. Download the Traffic Forecast plugin from the [Release](https://github.com/AyushDhimann/Traffic-Forecast/releases/tag/plugin) page.
2. Head over to your Wordpress Dashboard and click on **Plugins**,
3. Click on **Add New**.
4. Click on **Upload Plugin** and upload the Traffic Forecast plugin.
5. Click on **Activate Plugin**.
6. Click on **Dashboard** and then on **Forecast** right below the Dashboard in the side menu.
7. Click on **Sign in to Tableau Cloud**.
8. Done.


### How to build

To run the latest development version of the plugin, take the following steps.

First, clone the repository using Git in your `/wp-content/plugins/` directory
```
git clone git@github.com:AyushDhimann/Traffic-Forecast.git
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


## Credits

Thanks to [dannyvankooten's](https://github.com/dannyvankooten) organization, [ibericodes](https://github.com/ibericode) repository [koko-analytics](https://github.com/ibericode/koko-analytics) for making the best open-source wordpress analytics [plugin](https://www.kokoanalytics.com/).

### License

GNU General Public License v3.0

