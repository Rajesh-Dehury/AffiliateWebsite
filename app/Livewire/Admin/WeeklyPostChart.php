<?php

namespace App\Livewire\Admin;

use App\Models\AmazonDeals;
use Carbon\Carbon;
use Livewire\Component;

class WeeklyPostChart extends Component
{
    public array $dataset = [];
    public array $labels = [];
    public AmazonDeals $amazonDeals;

    public function mount()
    {
        $this->prepareChartData();
    }

    public function prepareChartData()
    {
        // Fetch data from the last 7 days
        $data = AmazonDeals::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('updated_at', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Set the labels for the last 7 days
        $this->labels = collect(range(6, 0))->map(function ($day) {
            return Carbon::now()->subDays($day)->format('Y-m-d');
        })->toArray();

        // Prepare the dataset
        $counts = [];
        foreach ($this->labels as $label) {
            $counts[] = $data->firstWhere('date', $label)->count ?? 0;
        }

        // Apply the custom styles to the dataset
        $this->dataset = [
            [
                'label' => 'Posts',
                'data' => $counts,
                'borderColor' => 'rgb(255, 99, 132)',  // Equivalent to Utils.CHART_COLORS.red
                'backgroundColor' => 'rgba(255, 99, 132, 0.5)',  // Equivalent to transparentized red color
                'pointStyle' => 'circle',
                'pointRadius' => 5,
                'pointHoverRadius' => 15,
                'fill' => false,  // Not filling under the line
                'tension' => 0.1  // Smooth curves
            ]
        ];
    }

    public function render()
    {
        return view('livewire.admin.weekly-post-chart')
            ->layout('components.admin-layout');
    }
}
