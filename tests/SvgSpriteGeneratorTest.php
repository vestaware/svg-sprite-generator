<?php

namespace Vestaware\SvgSpriteGenerator\Tests;

use PHPUnit\Framework\TestCase;
use Vestaware\SvgSpriteGenerator\SvgSpriteGenerator;

class SvgSpriteGeneratorTest extends TestCase
{
    public function testGenerateSprite(): void
    {
        // Arrange
        $inputDir = __DIR__ . '/fixtures';
        $outputFile = __DIR__ . '/output/sprite.svg';

        // Create test directories and sample SVG files
        if (!is_dir($inputDir)) {
            mkdir($inputDir, 0777, true);
        }
        if (!is_dir(dirname($outputFile))) {
            mkdir(dirname($outputFile), 0777, true);
        }

        file_put_contents($inputDir . '/icon1.svg', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" /></svg>');
        file_put_contents($inputDir . '/icon2.svg', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" /></svg>');

        // Act
        $generator = new SvgSpriteGenerator($inputDir, $outputFile, true, true, true);
        $generator->generateSprite();

        // Assert
        $this->assertFileExists($outputFile);

        $outputContent = file_get_contents($outputFile);

        $this->assertStringContainsString('<symbol id="icon1"', $outputContent);
        $this->assertStringContainsString('<symbol id="icon2"', $outputContent);
        $this->assertStringNotContainsString('xmlns=', $outputContent); // xmlns should be removed
        $this->assertStringNotContainsString('width=', $outputContent); // width should be removed
        $this->assertStringNotContainsString('height=', $outputContent); // height should be removed
        $this->assertStringNotContainsString('fill=', $outputContent); // fill should be removed if removeFill is true

        // Cleanup
        unlink($inputDir . '/icon1.svg');
        unlink($inputDir . '/icon2.svg');
        rmdir($inputDir);
        unlink($outputFile);
        rmdir(dirname($outputFile));
    }
}
