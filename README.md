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

### Example:
```php
use Vestaware\SvgSpriteGenerator\SvgSpriteGenerator;

// Define the input directory containing SVG files
$inputDir = __DIR__ . '/svgs';

// Define the output file for the SVG sprite
$outputFile = __DIR__ . '/sprite.svg';

// Create an instance of the generator
$generator = new SvgSpriteGenerator($inputDir, $outputFile, removeFill: true);

// Generate the SVG sprite
$generator->generateSprite();

echo "SVG sprite generated at: $outputFile";
```

---

## Features

- Removes unnecessary attributes like `id`, `xmlns`, and others.
- Retains only `viewBox` and optionally `fill` attributes.
- Removes `<g>` tags completely.

---

## Output Example

The generated SVG sprite will look like this:

```html
<svg xmlns="http://www.w3.org/2000/svg" style="display:none;">
  <symbol id="icon1" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="10" />
  </symbol>
  <symbol id="icon2" viewBox="0 0 24 24">
    <rect x="4" y="4" width="16" height="16" />
  </symbol>
</svg>
```

---

## License

MIT License

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
