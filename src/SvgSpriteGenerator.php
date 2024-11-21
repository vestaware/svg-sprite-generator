<?php

namespace Vestaware\SvgSpriteGenerator;

use Exception;

/**
 * Class SvgSpriteGenerator
 * A PHP library to optimize SVG files and generate SVG sprites with versioning.
 */
class SvgSpriteGenerator
{
    private string $inputDirectory;
    private string $outputDirectory;
    private string $baseFileName;
    private string $manifestPath;
    private bool $removeFill;

    /**
     * Constructor to initialize the class with user preferences.
     *
     * @param string $inputDirectory Directory containing SVG files.
     * @param string $outputDirectory Directory to store the SVG sprite.
     * @param string $baseFileName Base name for the output SVG sprite (without extension or hash).
     * @param string $manifestPath Path to the manifest file.
     * @param bool $removeFill Whether to remove the 'fill' attribute from SVG symbols.
     */
    public function __construct(
        string $inputDirectory,
        string $outputDirectory,
        string $baseFileName,
        string $manifestPath,
        bool $removeFill = false
    ) {
        $this->inputDirectory = rtrim($inputDirectory, '/');
        $this->outputDirectory = rtrim($outputDirectory, '/');
        $this->baseFileName = $baseFileName;
        $this->manifestPath = $manifestPath;
        $this->removeFill = $removeFill;
    }

    /**
     * Generate the SVG sprite with versioning.
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

        // Calculate hash of the sprite content
        $hash = substr(md5($spriteContent), 0, 8); // Use 8 characters for the hash
        $hashedFileName = "{$this->baseFileName}.{$hash}.min.svg";

        // Full path for the output file
        $outputPath = "{$this->outputDirectory}/$hashedFileName";

        // Load existing manifest
        $manifest = $this->loadManifest();

        // Remove previous versions if any
        $this->cleanupOldFiles($this->outputDirectory, $this->baseFileName, $hashedFileName);

        // Save the sprite
        file_put_contents($outputPath, $spriteContent);

        // Update the manifest
        $manifest[$this->baseFileName] = $hashedFileName;
        $this->saveManifest($manifest);
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

        // Ensure xmlns is removed
        $attributesArray = array_filter($attributesArray, function ($attr) {
            return stripos($attr, 'xmlns') === false;
        });

        return implode(' ', $attributesArray);
    }

    /**
     * Cleanup old files with the same base name.
     *
     * @param string $directory Directory containing the sprite files.
     * @param string $baseFileName Base file name for the sprite (without extension or hash).
     * @param string $currentFileName Current file name with hash.
     */
    private function cleanupOldFiles(string $directory, string $baseFileName, string $currentFileName): void
    {
        $files = glob("$directory/{$baseFileName}.*.min.svg");
        foreach ($files as $file) {
            if (basename($file) !== $currentFileName) {
                unlink($file);
            }
        }
    }

    /**
     * Load the manifest file.
     *
     * @return array The current manifest data.
     */
    private function loadManifest(): array
    {
        if (file_exists($this->manifestPath)) {
            return json_decode(file_get_contents($this->manifestPath), true) ?? [];
        }
        return [];
    }

    /**
     * Save the manifest file.
     *
     * @param array $manifest The updated manifest data.
     */
    private function saveManifest(array $manifest): void
    {
        file_put_contents($this->manifestPath, json_encode($manifest, JSON_PRETTY_PRINT));
    }
}
