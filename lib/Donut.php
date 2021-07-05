<?php

/**
 * tiny-phpeanuts - donut charts, easy phpeezy
 *
 * @link https://github.com/Fundevogel/tiny-phpeanuts
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace Fundevogel;

use Fundevogel\Helpers\Butler;

use SVG\SVG;
use SVG\Nodes\Shapes\SVGCircle;

/**
 * Class Donut
 *
 * Creates a donut chart
 *
 * @package tiny-phpeanuts
 */
class Donut
{
    /**
     * Current version number of tiny-phpeanuts
     */
    const VERSION = '1.0.1';


    /**
     * Data points being visualized
     * Each entry consists of
     * - a color string
     * - a value representing the share (between 0 and 1)
     *
     * @var array
     */
    private $entries;


    /**
     * Thickness of the chart
     *
     * @var float
     */
    private $thickness;


    /**
     * Spacing between chart segments
     *
     * @var float
     */
    private $spacing;


    /**
     * SVG root element dimensions
     *
     * @var int
     */
    private $size = 100;


    /**
     * Enables viewBox instead of width & height
     *
     * @var bool
     */
    private $preferViewbox = true;


    /**
     * SVG root element background fill
     *
     * @var string
     */
    private $backgroundColor = 'transparent';


    /**
     * SVG root element classes
     *
     * @var string
     */
    private $classes = '';


    /**
     * SVG root element role attribute
     *
     * @var string
     */
    private $role = 'img';


    /**
     * Enables pie chart functionality
     *
     * @var bool
     */
    private $isPieChart = false;


    public function __construct(
        array $entries,
        float $thickness = 3,
        float $spacing = 0.005
    ) {
        if (array_sum(Butler::pluck($entries, 'value')) > 1) {
            throw new \Exception('The sum of entries can not be greater than 1');
        }

        $this->thickness = $thickness;
        $this->entries = $entries;
        $this->spacing = $spacing;
    }


    /**
     * Setters & getters
     */

    public function setSize(int $size)
    {
        $this->size = $size;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setPreferViewbox(bool $preferViewbox)
    {
        $this->preferViewbox = $preferViewbox;
    }

    public function getPreferViewbox()
    {
        return $this->preferViewbox;
    }

    public function setBackgroundColor(string $backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
    }

    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    public function setClasses(string $classes)
    {
        $this->classes = $classes;
    }

    public function getClasses()
    {
        return $this->classes;
    }

    public function setRole(string $role)
    {
        $this->role = $role;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setPieChart(bool $isPieChart)
    {
        $this->isPieChart = $isPieChart;
    }

    public function getPieChart()
    {
        return $this->isPieChart;
    }


    /**
     * Functionality
     */

    public function render(): string
    {
        $svg = new SVG($this->size, $this->size);
        $doc = $svg->getDocument();

        # If enabled, remove replace width & height with viewBox
        if ($this->preferViewbox) {
            # (1) Remove width & height attribute
            $doc->removeAttribute('width');
            $doc->removeAttribute('height');

            # (2) Add viewBox
            $doc->setAttribute('viewBox', implode(' ', [
                '0 0',
                $this->size,
                $this->size,
            ]));
        }

        # If defined, set role attribute
        if ($this->role !== '') {
            $doc->setAttribute('role', $this->role);
        }

        # Build segments (= SVG circle elements)
        $segments = $this->constructSegments();

        foreach ($segments as $segment) {
            $doc->addChild($segment);
        }

        # If defined, add class(es)
        if ($this->classes !== '') {
            $doc->setAttribute('class', $this->classes);
        }

        return $svg->toXMLString(false);
    }

    private function constructSegments(): array
    {
        $thickness = $this->isPieChart ? $this->size / 2 : $this->thickness;
        $spacing = $this->isPieChart ? 0 : $this->spacing;

        $segmentsWithSpacing = $this->correctSegmentsForSpacing($this->entries, $spacing);

        $start = 0;
        $segments = [];

        foreach ($segmentsWithSpacing as $entry) {
            $segments[] = $this->constructSegment(
                $entry['color'],
                $entry['value'],
                $thickness,
                $start
            );

            $start += $entry['value'] + $spacing;
        }

        return $segments;
    }

    private function correctSegmentsForSpacing(array $segments, float $spacing): array
    {
        $totalLengthWithoutSpacing = 1 - $spacing * count($segments);

        $results = [];

        foreach ($segments as $entry) {
            $results[] = [
                'color' => $entry['color'],
                'value' => number_format($entry['value'] * $totalLengthWithoutSpacing, 4),
            ];
        }

        return $results;
    }

    private function constructSegment(
        string $color,
        float $length,
        float $thickness,
        float $start
    ): \SVG\Nodes\Shapes\SVGCircle {
        $radius = ($this->size / 2) - ($thickness / 2);
        $circumference = $radius * 2 * M_PI;
        $base = $circumference / 100;
        $offset = $circumference - ($base * ($start * 100)) + ($circumference / 4);
        $lengthOnCircle = $base * ($length * 100);

        $circle = (new SVGCircle(
            $this->size / 2,
            $this->size / 2,
            $radius
        ))->setAttribute('fill', $this->backgroundColor);

        if ($this->backgroundColor === 'transparent') {
            $circle
                ->setAttribute('fill-opacity', 0)
                ->setAttribute('stroke', $color)
                ->setAttribute('stroke-width', (string) $thickness)
                ->setAttribute('stroke-dashoffset', (string) $offset)
                ->setAttribute('stroke-dasharray', Butler::join([
                    (string) $lengthOnCircle,
                    (string) $circumference - $lengthOnCircle
                ], ' '))
            ;
        }

        return $circle;
    }
}
