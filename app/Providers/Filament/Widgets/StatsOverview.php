<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Log;

class StatsOverview extends BaseWidget
{
    use HasWidgetShield;

    protected ?string $heading = 'Analisis Omset';

    // protected ?string $description = 'An overview of some analytics.';
    protected static bool $isLazy = false;
    protected static ?int $sort = 3;



    protected function getStats(): array
    {
        return [
            $this->createCard(
                'Omset Harian',
                $this->getDailyRevenue(),
                $this->getDailyChange(),
                $this->getDailyChart(),
            ),
            $this->createCard(
                'Omset Mingguan',
                $this->getWeeklyRevenue(),
                $this->getWeeklyChange(),
                $this->getWeeklyChart(),
            ),
            $this->createCard(
                'Omset Bulanan',
                $this->getMonthlyRevenue(),
                $this->getMonthlyChange(),
                $this->getMonthlyChart(),
            ),
            $this->createCard(
                'Omset Tahunan',
                $this->getYearlyRevenue(),
                $this->getYearlyChange(),
                $this->getYearlyChart(),
            ),
        ];
    }

    protected function createCard(string $title, int $value, array $change, array $chart): Stat
    {
        return Stat::make($title, 'Rp ' . number_format($value))
            ->description($change['text'])
            ->descriptionIcon($change['icon'])
            ->chart($chart)
            ->color($change['color']);
    }

    protected function getDailyRevenue()
    {
        $query = Transaction::whereDate('created_at', today())
            ->whereIn('booking_status', ['pending', 'paid', 'rented', 'finished']);

        $rawQuery = $query->toSql();
        $bindings = $query->getBindings();
        $data = $query->get(); // Mengambil semua data mentah

        $result = $query->sum('grand_total');

        return round($result / 100, 2);
    }

    protected function getDailyChange()
    {
        $today = round($this->getDailyRevenue() / 100, 2);
        $yesterday = round(Transaction::whereDate('created_at', today()->subDay())
            ->whereIn('booking_status', ['pending', 'paid', 'rented', 'finished'])
            ->sum('grand_total') / 100, 2);

        return $this->calculateChange($today, $yesterday);
    }

    protected function getDailyChart()
    {
        return $this->generateChartData('daily');
    }

    protected function getWeeklyRevenue()
    {
        return round(Transaction::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereIn('booking_status', ['pending', 'paid', 'rented', 'finished'])
            ->sum('grand_total') / 100, 2);
    }

    protected function getWeeklyChange()
    {
        $thisWeek = $this->getWeeklyRevenue();
        $lastWeek = round(Transaction::whereBetween('created_at', [
            now()->startOfWeek()->subWeek(),
            now()->endOfWeek()->subWeek(),
        ])->whereIn('booking_status', ['pending', 'paid', 'rented', 'finished'])
            ->sum('grand_total') / 100, 2);

        return $this->calculateChange($thisWeek, $lastWeek);
    }

    protected function getWeeklyChart()
    {
        return $this->generateChartData('weekly');
    }

    protected function getMonthlyRevenue()
    {
        return round(Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereIn('booking_status', ['pending', 'paid', 'rented', 'finished'])
            ->sum('grand_total') / 100, 2);
    }

    protected function getMonthlyChange()
    {
        $thisMonth = $this->getMonthlyRevenue();
        $lastMonth = round(Transaction::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->whereIn('booking_status', ['pending', 'paid', 'rented', 'finished'])
            ->sum('grand_total') / 100, 2);

        return $this->calculateChange($thisMonth, $lastMonth);
    }

    protected function getMonthlyChart()
    {
        return $this->generateChartData('monthly');
    }

    protected function getYearlyRevenue()
    {
        return round(Transaction::whereYear('created_at', now()->year)
            ->whereIn('booking_status', ['pending', 'paid', 'rented', 'finished'])
            ->sum('grand_total') / 100, 2);
    }

    protected function getYearlyChange()
    {
        $thisYear = $this->getYearlyRevenue();
        $lastYear = round(Transaction::whereYear('created_at', now()->subYear()->year)
            ->whereIn('booking_status', ['pending', 'paid', 'rented', 'finished'])
            ->sum('grand_total') / 100, 2);

        return $this->calculateChange($thisYear, $lastYear);
    }

    protected function getYearlyChart()
    {
        return $this->generateChartData('yearly');
    }

    protected function calculateChange(int $current, int $previous): array
    {
        if ($previous == 0) {
            return [
                'text' => 'No data',
                'icon' => 'heroicon-o-minus',
                'color' => 'secondary',
            ];
        }

        $change = $current - $previous;
        $percentage = abs($change) / $previous * 100;

        if ($change > 0) {
            return [
                'text' => number_format($change, 0, ',', '.') . ' increase (' . round($percentage) . '%)',
                'icon' => 'heroicon-m-arrow-trending-up',
                'color' => 'success',
            ];
        }

        return [
            'text' => number_format(abs($change), 0, ',', '.') . ' decrease (' . round($percentage) . '%)',
            'icon' => 'heroicon-m-arrow-trending-down',
            'color' => 'danger',
        ];
    }

    protected function generateChartData(string $type): array
    {
        $query = Transaction::query()
            ->selectRaw('SUM(grand_total) as total, created_at')
            ->whereIn('booking_status', ['pending', 'paid', 'rented', 'finished']);

        // Filter data berdasarkan tipe (daily, weekly, monthly, yearly)
        $data = match ($type) {
            'daily' => $query
                ->whereDate('created_at', today()) // Data harian
                ->groupByRaw('HOUR(created_at)') // Kelompokkan berdasarkan jam
                ->orderByRaw('HOUR(created_at)')
                ->pluck('total'), // Hanya ambil nilai total
            'weekly' => $query
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]) // Data mingguan
                ->groupByRaw('DAYOFWEEK(created_at)') // Kelompokkan berdasarkan hari dalam minggu
                ->orderByRaw('DAYOFWEEK(created_at)')
                ->pluck('total'),
            'monthly' => $query
                ->whereMonth('created_at', now()->month) // Data bulanan
                ->groupByRaw('DAY(created_at)') // Kelompokkan berdasarkan hari dalam bulan
                ->orderByRaw('DAY(created_at)')
                ->pluck('total'),
            'yearly' => $query
                ->whereYear('created_at', now()->year) // Data tahunan
                ->groupByRaw('MONTH(created_at)') // Kelompokkan berdasarkan bulan
                ->orderByRaw('MONTH(created_at)')
                ->pluck('total'),
            default => [],
        };

        // Pastikan hasil selalu berupa array
        return $data->toArray();
    }
}
