<?php

namespace App\Livewire;

use App\Models\AmazonDeals;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class Home extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 30; // Default per page
    public $sortField = 'updated_at'; // Default sort field
    public $sortDirection = 'desc'; // Default sort direction
    public $totalRecords = 0;
    public $disc = 0;  // saving_percent filter
    public $date_from = null; // updated_at filter (from)
    public $date_to = null; // updated_at filter (to)

    protected $queryString = ['search', 'sortField', 'sortDirection', 'perPage'];

    public function mount()
    {
        $this->totalRecords = $this->perPage;
        $this->date_to = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function getRecordsProperty()
    {
        $query = AmazonDeals::query();

        // Filter by search
        if ($this->search) {
            $query->where('product_title', 'like', '%' . $this->search . '%');
        }

        // Filter by saving_percent (disc)
        if ($this->disc > 0) {
            $query->where('saving_percent', '>=', $this->disc);
        }

        // Filter by date range
        if ($this->date_from && $this->date_to) {
            $query->whereBetween('updated_at', [Carbon::parse($this->date_from), Carbon::parse($this->date_to)]);
        } elseif ($this->date_from) {
            $query->where('updated_at', '>=', Carbon::parse($this->date_from));
        } elseif ($this->date_to) {
            $query->where('updated_at', '<=', Carbon::parse($this->date_to));
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        // Return paginated results
        return $query->paginate($this->totalRecords);
    }

    public function loadMore()
    {
        $this->totalRecords += $this->perPage;
    }

    public function render()
    {
        return view(
            'livewire.home',
            [
                'records' => $this->Records,
            ]
        )->layout('components.home-layout');
    }
}
