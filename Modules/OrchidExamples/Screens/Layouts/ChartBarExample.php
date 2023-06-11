<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

declare(strict_types=1);

namespace Modules\OrchidExamples\Screens\Layouts;

use Orchid\Screen\Layouts\Chart;

class ChartBarExample extends Chart
{
    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = self::TYPE_BAR;

    /**
     * Height of the chart.
     *
     * @var int
     */
    protected $height = 300;
}
