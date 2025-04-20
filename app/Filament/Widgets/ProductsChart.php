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
