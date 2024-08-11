<?php

namespace App\Livewire;

use App\Models\AmazonDeals;
use Livewire\Component;
use Livewire\WithPagination;

class Home extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 5; // Default per page
    public $sortField = 'updated_at'; // Default sort field
    public $sortDirection = 'desc'; // Default sort direction

    protected $queryString = ['search', 'sortField', 'sortDirection', 'perPage'];

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
        $query = AmazonDeals::where('product_title', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection);

        if ($this->perPage == 'All') {
            return $query->get();
        } else {
            return $query->paginate($this->perPage);
        }
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
