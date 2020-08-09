<?php

/**
 * tiny-phpeanuts - donut charts, easy phpeezy
 *
 * @link https://github.com/Fundevogel/tiny-phpeanuts
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace Fundevogel;

use Fundevogel\Segment;
use Fundevogel\Helpers\Butler;

use \SVG\SVG;

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
    const VERSION = '0.3.0';


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
     * SVG root element classes 
     *
     * @var string
     */
    private $classes = '';


    /**
     * Viewport width & height
     *
     * @var int
     */
    private $size = 100;


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

    public function setClasses(string $classes)
    {
        $this->classes = $classes;
    }

    public function getClasses()
    {
        return $this->classes;
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

    public function getSVGElement(): string
    {
        $segments = $this->constructSegments();
        $svg = new SVG($this->size, $this->size);
        $doc = $svg->getDocument();

        foreach ($segments as $segment) {
            $doc->addChild($segment->getSVGElement());
        }

        if ($this->classes !== '') {
            $svg->setAttribute('class', $this->classes);
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
            $segments[] = new Segment(
                $entry['color'],
                $entry['value'],
                $this->size,
                $start,
                $thickness,
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
}
