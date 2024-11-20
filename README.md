# SVG Sprite Generator

A PHP library to optimize SVG files and generate an SVG sprite.

---

## Installation

This library can be installed via [Composer](https://getcomposer.org/). Ensure Composer is installed on your system.

### Step 1: Install the package
Run the following command in your terminal to add this package to your project:

```bash
composer require vestaware/svg-sprite-generator
```

### Step 2: Require Composer autoload
In your PHP file, include Composer's autoload file:

```php
require 'vendor/autoload.php';
```

---

## Usage

The library processes multiple SVG files, optimizes them, and generates a single SVG sprite containing `<symbol>` elements.

### Example:
```php
use Vestaware\SvgSpriteGenerator\SvgSpriteGenerator;

// Define the input directory containing SVG files
$inputDir = __DIR__ . '/svgs';

// Define the output file for the SVG sprite
$outputFile = __DIR__ . '/sprite.svg';

// Create an instance of the generator
$generator = new SvgSpriteGenerator(
    $inputDir,
    $outputFile,
    removeComments: true,
    removeMetadata: true,
    removeFill: true // Set to false if you want to keep the 'fill' attribute
);

// Generate the SVG sprite
$generator->generateSprite();

echo "SVG sprite generated at: $outputFile";
```

---

## Options

- **`removeComments`**: Removes comments from SVG files (default: `true`).
- **`removeMetadata`**: Removes metadata such as XML declaration and DOCTYPE (default: `true`).
- **`removeFill`**: Removes the `fill` attribute from SVG files (default: `false`).

---

## Output Example

The generated SVG sprite will look like this:

```html
<svg xmlns="http://www.w3.org/2000/svg" style="display:none;" aria-hidden="true">
  <symbol id="icon-add" viewBox="0 0 24 24">
    <path d="M12 5v14m7-7H5"></path>
  </symbol>
  <symbol id="icon-remove" viewBox="0 0 24 24">
    <path d="M5 12h14"></path>
  </symbol>
</svg>
```

---

## Advanced Usage

### Specify a Version
If you need to install a specific version of the package, use:

```bash
composer require vestaware/svg-sprite-generator:^1.0
```

### Update the Package
To update the package to the latest version:

```bash
composer update vestaware/svg-sprite-generator
```

---

## License

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
