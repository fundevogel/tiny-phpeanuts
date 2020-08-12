# tiny-phpeanuts
[![Release](https://img.shields.io/github/release/Fundevogel/tiny-phpeanuts.svg)](https://github.com/Fundevogel/tiny-phpeanuts/releases) [![License](https://img.shields.io/github/license/Fundevogel/tiny-phpeanuts.svg)](https://github.com/Fundevogel/tiny-phpeanuts/blob/master/LICENSE) [![Issues](https://img.shields.io/github/issues/Fundevogel/tiny-phpeanuts.svg)](https://github.com/Fundevogel/tiny-phpeanuts/issues) [![Status](https://travis-ci.org/fundevogel/tiny-phpeanuts.svg?branch=master)](https://travis-ci.org/fundevogel/tiny-phpeanuts)

A tiny PHP library for creating SVG donut (and pie) charts.


## What
This library is a PHP port of the TypeScript library [`tiny-donuts`](https://github.com/Verivox/tiny-donuts).


## Why
> We needed Donut charts - however, Chart.js was a much too powerful (and big) for our use case and added >200kb to our browser application.
>
> So we wrote this small library with a minified size of 4 KiB.

While `tiny-donuts` is a great library, we needed something less client-side and more server-side.

So we ported their library to PHP.


## How
Install this package with [Composer](https://getcomposer.org):

```text
composer require fundevogel/tiny-phpeanuts
```


### Configuration
The `Donut` class takes three parameters:

| Parameter     | Type   | Default   | Description                  |
| ------------- | ------ | --------- | ---------------------------- |
| `$entries`    | array  | -         | data to be visualized        |
| `$thickness`  | float  | see below | thickness of the chart       |
| `$spacing`    | float  | see below | spacing between the segments |


### Options
.. and there's even more! Check out these 'global' options:

| Setter               | Type   | Default         | Description                |
| -------------------- | ------ | --------------- | -------------------------- |
| `setSize`            | int    | `100`           | dimensions of chart        |
| `setBackgroundColor` | string | `'transparent'` | background fill color      |
| `setPreferViewbox`   | bool   | `true`          | `viewBox` > width & height |
| `setClasses`         | string | `''`            | classes applied to chart   |
| `setRole`            | string | `'img'`         | role attribute             |
| `setPieChart`        | bool   | `false`         | make it a pie chart        |


### Example
```php
<?php

require_once('vendor/autoload.php');

use Fundevogel\Donut;

$donut = new Donut(
    [
        ['color' => '#4F5D95', 'value' => 0.68], # PHP
        ['color' => '#2b7489', 'value' => 0.25], # TypeScript
        ['color' => '#563d7c', 'value' => 0.04], # CSS
        ['color' => '#3572A5', 'value' => 0.02], # Python
        ['color' => '#89e051', 'value' => 0.01], # Shell
    ]
);

// .. maybe make it a pie chart?
$donut->setPieChart(true);

// Render its markup
$svg = $donut->render();

# Save it to disk ..
# (1) .. using the XML DOM parser
$dom = new DOMDocument;
$dom->preserveWhiteSpace = FALSE;
$dom->loadXML($svg);
$dom->save('dist/chart_xml-dom-parser.svg');

# (2) .. echoing its contents
header('Content-Type: image/svg+xml');
echo $svg;

# (3) .. or simply like this
file_put_contents('dist/chart_file-put-contents.svg', $svg);
```

.. looks like this:

![Donut Chart, powered by tiny-phpeanuts](./chart.svg)

.. and with `setPieChart(true)`:

![Donut Chart, powered by tiny-phpeanuts](./chart2.svg)


## FAQ
> Will there be more charts in the future?

PRs welcome, beyond that .. no.

> Are you sure?

Yes! If you are looking for something more serious, have a look at [easychart](https://github.com/fernandowobeto/easychart).


## Roadmap
- [ ] Add tests
- [x] Table view for options
- [x] Optimizing code


## Credits
This project is based on the TypeScript library `tiny-donuts` by [Kim Almasan](https://github.com/Narquadah) & [Lars Krumbier](https://github.com/LarsKumbier). The helper functions (see `lib/helpers`) were taken from [Kirby](https://getkirby.com) by [Bastian Allgeier](https://github.com/bastianallgeier) (both the project and its founder are just awesome, btw).


**Happy coding!**


:copyright: Fundevogel Kinder- und Jugendbuchhandlung
