<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Customer;

class ProductsChart extends ChartWidget
{
    protected static ?string $heading = 'Customers Created als Line Chart';

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
                    'fill' => true,
                ],
                [
                    'label' => 'Customers created last year',
                    'data' =>[15, 10, 10, 4, 5, 0, 0, 0, 0, 0, 0, 0 ],
                    'borderColor' => 'rgba(76, 175, 80, 1)',         // Linienfarbe
                    'backgroundColor' => 'rgba(76, 175, 80, 0.4)',   // Fläche unter der Linie
                    'fill' => true,                                  // Fläche unter Linie anzeigen
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
