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
        $outputDir = __DIR__ . '/output';
        $baseFileName = 'sprite';
        $manifestPath = $outputDir . '/sprite-manifest.json';

        // Create test directories and sample SVG files
        if (!is_dir($inputDir)) {
            mkdir($inputDir, 0777, true);
        }
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        file_put_contents($inputDir . '/icon1.svg', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g><circle cx="12" cy="12" r="10" /></g></svg>');
        file_put_contents($inputDir . '/icon2.svg', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g><rect x="4" y="4" width="16" height="16" /></g></svg>');

        // Act
        $generator = new SvgSpriteGenerator($inputDir, $outputDir, $baseFileName, $manifestPath, true);
        $generator->generateSprite();

        // Assert
        $this->assertFileExists($manifestPath);

        // Load manifest and verify file name
        $manifest = json_decode(file_get_contents($manifestPath), true);
        $this->assertArrayHasKey($baseFileName, $manifest);

        $hashedFileName = $manifest[$baseFileName];
        $outputFile = $outputDir . '/' . $hashedFileName;

        $this->assertFileExists($outputFile);

        $outputContent = file_get_contents($outputFile);

        $this->assertStringContainsString('<symbol id="icon1"', $outputContent);
        $this->assertStringContainsString('<symbol id="icon2"', $outputContent);
        $this->assertStringNotContainsString('<g>', $outputContent);

        // Cleanup
        unlink($inputDir . '/icon1.svg');
        unlink($inputDir . '/icon2.svg');
        rmdir($inputDir);

        unlink($outputFile);
        unlink($manifestPath);
        rmdir($outputDir);
    }
}
