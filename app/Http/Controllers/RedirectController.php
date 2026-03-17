<?php

namespace App\Http\Controllers;

use App\Models\AmazonDeals;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function redirectToAnotherUrl($product_asin)
    {
        // Retrieve the item based on the product_asin
        $item = AmazonDeals::where('product_asin', $product_asin)->first();

        // Check if the item exists
        if ($item) {
            // Increment clicks count
            $item->increment('clicks_count');
            
            // Get the URL from the item
            $url = $item->detail_page_url;

            // Sleep for 2 seconds
            // sleep(2); // Reduced or removed sleep for better UX unless specifically needed for some reason

            // Redirect to the URL
            return redirect()->away($url);
        } else {
            // Handle the case where the item is not found
            return redirect()->back()->with('error', 'Item not found');
        }
    }
}
