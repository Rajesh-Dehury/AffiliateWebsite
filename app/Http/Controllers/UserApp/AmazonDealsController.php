<?php

namespace App\Http\Controllers\UserApp;

use App\Http\Controllers\Controller;
use App\Models\AmazonDeals;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AmazonDealsController extends Controller
{
    public function index(Request $request)
    {
        // Initialize query
        $query = AmazonDeals::query();

        // Filter by search
        if ($request->has('search') && !empty($request->search)) {
            $query->where('product_title', 'like', '%' . $request->search . '%');
        }

        // Filter by saving_percent (disc)
        if ($request->has('disc') && $request->disc > 0) {
            $query->where(function ($query) use ($request) {
                $query->where('saving_percent', '>=', $request->disc)
                    ->orWhereNull('saving_percent');
            });
        }

        // Filter by date range
        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('updated_at', [
                Carbon::parse($request->date_from),
                Carbon::parse($request->date_to)
            ]);
        } elseif ($request->has('date_from')) {
            $query->where('updated_at', '>=', Carbon::parse($request->date_from));
        } elseif ($request->has('date_to')) {
            $query->where('updated_at', '<=', Carbon::parse($request->date_to));
        }

        // Apply sorting
        $sortField = $request->get('sortField', 'id'); // default sorting field
        $sortDirection = $request->get('sortDirection', 'asc'); // default sorting direction

        $query->orderBy($sortField, $sortDirection);

        // Return paginated results
        $totalRecords = $request->get('totalRecords', $request->limit); // default pagination

        return $query->paginate($totalRecords);
    }

    function show($id)
    {
        $deal = AmazonDeals::find($id);
        return $deal;
    }
}
