<?php

namespace Vestaware\SvgSpriteGenerator\Tests;

use PHPUnit\Framework\TestCase;
use Vestaware\SvgSpriteGenerator\SvgSpriteGenerator;

class SvgSpriteGeneratorTest extends TestCase
{
    public function testSpriteGeneration(): void
    {
        $inputDir = __DIR__ . '/fixtures';
        $outputFile = __DIR__ . '/sprite.svg';

        $generator = new SvgSpriteGenerator($inputDir, $outputFile);
        $generator->generateSprite();

        $this->assertFileExists($outputFile);
        $this->assertStringContainsString('<symbol', file_get_contents($outputFile));
    }
}
