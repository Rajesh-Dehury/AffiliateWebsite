<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    use HasFactory;

    protected $fillable = ['amazon_deal_id', 'price'];

    public function deal()
    {
        return $this->belongsTo(AmazonDeals::class, 'amazon_deal_id');
    }
}
