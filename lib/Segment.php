<?php

namespace Fundevogel;

use Fundevogel\Helpers\Butler;

use SVG\Nodes\Shapes\SVGCircle;

/**
 * Class Segment
 *
 * Creates a donut chart segment
 *
 * @package tiny-phpeanuts
 */
class Segment
{
    public $color;
    public $length;
    public $size;
    public $start;
    public $thickness;
    # TODO: Enable access to background color
    public $backgroundColor = 'transparent';

    public function __construct($color, $length, $size, $start, $thickness)
    {
        if ($length > 1 || $length <= 0) {
            throw new \Exception('Please choose a value between 0 and 1');
        }

        $this->color = $color;
        $this->length = $length;
        $this->size = $size;
        $this->thickness = $thickness;
        $this->start = $start;
    }

    public function getSVGElement(): \SVG\Nodes\Shapes\SVGCircle
    {
        $radius = ($this->size / 2) - ($this->thickness / 2);
        $circumference = $radius * 2 * M_PI;
        $base = $circumference / 100;
        $offset = $circumference - ($base * ($this->start * 100)) + ($circumference / 4);
        $lengthOnCircle = $base * ($this->length * 100);

        $circle = (new SVGCircle(
            $this->size / 2,
            $this->size / 2,
            $radius
        ))->setAttribute('fill', $this->backgroundColor);

        if ($this->backgroundColor === 'transparent') {
            $circle
                ->setAttribute('fill-opacity', 0)
                ->setAttribute('stroke', $this->color)
                ->setAttribute('stroke-width', (string) $this->thickness)
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
