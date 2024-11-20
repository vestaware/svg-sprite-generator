<?php

namespace Vestaware\SvgSpriteGenerator;

use Exception;

/**
 * Class SvgSpriteGenerator
 * A PHP library to optimize SVG files and generate SVG sprites.
 */
class SvgSpriteGenerator
{
    private string $inputDirectory;
    private string $outputFile;
    private bool $removeComments;
    private bool $removeMetadata;

    /**
     * Constructor to initialize the class with user preferences.
     *
     * @param string $inputDirectory Directory containing SVG files.
     * @param string $outputFile Output file path for the SVG sprite.
     * @param bool $removeComments Whether to remove comments from SVG files.
     * @param bool $removeMetadata Whether to remove metadata from SVG files.
     */
    public function __construct(string $inputDirectory, string $outputFile, bool $removeComments = true, bool $removeMetadata = true)
    {
        $this->inputDirectory = rtrim($inputDirectory, '/');
        $this->outputFile = $outputFile;
        $this->removeComments = $removeComments;
        $this->removeMetadata = $removeMetadata;
    }

    /**
     * Generate the SVG sprite by processing files in the input directory.
     *
     * @throws Exception
     */
    public function generateSprite(): void
    {
        $svgFiles = glob($this->inputDirectory . '/*.svg');

        if (empty($svgFiles)) {
            throw new Exception("No SVG files found in the input directory.");
        }

        $spriteContent = '<svg xmlns="http://www.w3.org/2000/svg" style="display:none;">';

        foreach ($svgFiles as $file) {
            $spriteContent .= $this->processSvgFile($file);
        }

        $spriteContent .= '</svg>';

        file_put_contents($this->outputFile, $spriteContent);
    }

    /**
     * Process an SVG file for optimization and inclusion in the sprite.
     *
     * @param string $filePath Path to the SVG file.
     * @return string Optimized SVG content for sprite.
     * @throws Exception
     */
    private function processSvgFile(string $filePath): string
    {
        $content = file_get_contents($filePath);

        if ($this->removeComments) {
            $content = preg_replace('/<!--(.*?)-->/', '', $content);
        }

        if ($this->removeMetadata) {
            $content = preg_replace('/<\?xml(.*?)\?>/', '', $content);
            $content = preg_replace('/<!DOCTYPE(.*?)>/', '', $content);
        }

        preg_match('/<svg[^>]*>(.*?)<\/svg>/s', $content, $matches);

        if (!isset($matches[1])) {
            throw new Exception("Invalid SVG format in file: $filePath");
        }

        $symbolId = pathinfo($filePath, PATHINFO_FILENAME);

        return '<symbol id="' . $symbolId . '" ' . $this->extractAttributes($content) . '>' . $matches[1] . '</symbol>';
    }

    /**
     * Extract attributes from the <svg> tag.
     *
     * @param string $svgContent Full SVG content.
     * @return string Attributes as a string.
     */
    private function extractAttributes(string $svgContent): string
    {
        preg_match('/<svg(.*?)>/s', $svgContent, $matches);

        return isset($matches[1]) ? trim($matches[1]) : '';
    }
}
