<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Customer;

class BarChart extends ChartWidget
{
    protected static ?string $heading = 'Customers Created als Bar Chart';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = array_reverse(Customer::getRecordCountByYearMonthLastSixMonths());
        $labels = array_keys($data);
        $data = array_values($data);
        return [
            'datasets' => [
                [
                    'label' => 'Customers created',
                    'data' => $data,
                ],
                [
                    'label' => 'Customers created last year',
                    'data' =>[15, 10, 10, 4, 5, 0, 0, 0, 0, 0, 0, 0 ],
                    'backgroundColor' => 'rgba(76, 175, 80, 0.4)',  // Farbe für Balken
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
