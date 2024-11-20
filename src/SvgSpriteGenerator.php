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
    private bool $removeFill;

    /**
     * Constructor to initialize the class with user preferences.
     *
     * @param string $inputDirectory Directory containing SVG files.
     * @param string $outputFile Output file path for the SVG sprite.
     * @param bool $removeFill Whether to remove the 'fill' attribute from SVG symbols.
     */
    public function __construct(
        string $inputDirectory,
        string $outputFile,
        bool $removeFill = false
    ) {
        $this->inputDirectory = rtrim($inputDirectory, '/');
        $this->outputFile = $outputFile;
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

        // Remove <g> tags
        $content = preg_replace('/<g[^>]*>|<\/g>/', '', $content);

        // Extract the content inside the <svg> tag
        preg_match('/<svg[^>]*>(.*?)<\/svg>/s', $content, $matches);
        if (!isset($matches[1])) {
            throw new Exception("Invalid SVG format in file: $filePath");
        }
        $contentInsideSvg = $matches[1];

        // Clean the attributes from the <svg> tag
        $attributes = $this->extractAttributes($content);
        $attributes = $this->cleanAttributes($attributes);

        $symbolId = pathinfo($filePath, PATHINFO_FILENAME);

        return '<symbol id="' . $symbolId . '" ' . $attributes . '>' . $contentInsideSvg . '</symbol>';
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
        // Remove everything except viewBox and fill
        $allowedAttributes = ['viewBox'];
        if (!$this->removeFill) {
            $allowedAttributes[] = 'fill';
        }

        // Filter attributes
        $attributesArray = [];
        preg_match_all('/(\w+)=(".*?"|\'.*?\')/s', $attributes, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            if (in_array($match[1], $allowedAttributes)) {
                $attributesArray[] = $match[0];
            }
        }

        return implode(' ', $attributesArray);
    }
}