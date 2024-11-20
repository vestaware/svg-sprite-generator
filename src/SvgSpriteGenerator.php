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
    private bool $removeFill;

    /**
     * Constructor to initialize the class with user preferences.
     *
     * @param string $inputDirectory Directory containing SVG files.
     * @param string $outputFile Output file path for the SVG sprite.
     * @param bool $removeComments Whether to remove comments from SVG files.
     * @param bool $removeMetadata Whether to remove metadata from SVG files.
     * @param bool $removeFill Whether to remove 'fill' attribute from SVG symbols.
     */
    public function __construct(
        string $inputDirectory,
        string $outputFile,
        bool $removeComments = true,
        bool $removeMetadata = true,
        bool $removeFill = false
    ) {
        $this->inputDirectory = rtrim($inputDirectory, '/');
        $this->outputFile = $outputFile;
        $this->removeComments = $removeComments;
        $this->removeMetadata = $removeMetadata;
        $this->removeFill = $removeFill;
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

        $spriteContent = '<svg xmlns="http://www.w3.org/2000/svg" style="display:none;" aria-hidden="true">';

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

        // Extract attributes, clean unnecessary ones, and return the symbol
        $attributes = $this->extractAttributes($content);
        $attributes = $this->cleanAttributes($attributes);

        return '<symbol id="' . $symbolId . '" ' . $attributes . '>' . $matches[1] . '</symbol>';
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

    /**
     * Clean unnecessary attributes from the extracted attributes.
     *
     * @param string $attributes Extracted attributes string.
     * @return string Cleaned attributes string.
     */
    private function cleanAttributes(string $attributes): string
    {
        // Remove width and height
        $attributes = preg_replace('/\s(width|height)="[^"]*"/i', '', $attributes);

        // Remove xmlns (only needed on the root <svg>)
        $attributes = preg_replace('/\sxmlns="[^"]*"/i', '', $attributes);

        // Optionally remove fill attribute
        if ($this->removeFill) {
            $attributes = preg_replace('/\sfill="[^"]*"/i', '', $attributes);
        }

        return trim($attributes);
    }
}
