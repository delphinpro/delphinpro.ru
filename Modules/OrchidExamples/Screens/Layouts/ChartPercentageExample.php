<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

declare(strict_types=1);

namespace Modules\OrchidExamples\Screens\Layouts;

use Orchid\Screen\Layouts\Chart;

class ChartPercentageExample extends Chart
{
    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = self::TYPE_PERCENTAGE;

    /**
     * @var int
     */
    protected $height = 160;
}
