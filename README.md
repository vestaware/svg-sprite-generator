# SVG Sprite Generator

A PHP library to optimize SVG files and generate an SVG sprite.

---

## Installation

Install the package using [Composer](https://getcomposer.org/):

```bash
composer require vestaware/svg-sprite-generator
```

---

## Usage

Here is an example of how to use the library:

```php
<?php

require 'vendor/autoload.php';

use YourNamespace\SvgSpriteGenerator\SvgSpriteGenerator;

// Define the input directory and output file
$inputDir = __DIR__ . '/svgs';
$outputFile = __DIR__ . '/sprite.svg';

// Create an instance of the generator
$generator = new SvgSpriteGenerator($inputDir, $outputFile);

// Generate the SVG sprite
$generator->generateSprite();

echo "SVG sprite generated successfully at: $outputFile";
```

- Place your SVG files in the `svgs` directory.
- The output will be saved as `sprite.svg`.

---

## Features

- Optimizes SVG files by removing comments and metadata.
- Combines multiple SVG files into a single SVG sprite.
- Each SVG is converted into a `<symbol>` tag for easy use in HTML.

---

## License

This project is licensed under the MIT License:

```
MIT License

Copyright (c) [2024] [Vestaware]

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```
