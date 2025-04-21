<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Customer;

class PieChart extends ChartWidget
{
    protected static ?string $heading = 'Kundenstatus';
    protected static ?int $sort = 6;
    protected static string $chartType = 'pie';

    protected function getData(): array
    {
        $statuses = Customer::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Kundenstatus',
                    'data' => array_values($statuses),
                    'backgroundColor' => [
                        '#6366F1',
                        '#10B981',
                        '#F59E0B',
                        '#EF4444',
                        '#3B82F6',
                    ],
                ],
            ],
            'labels' => array_keys($statuses),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getContentHeight(): string
    {
        return 'h-10'; // oder h-80, h-96 usw.
    }
}
