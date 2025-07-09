<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DryOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoice_number',
        'order_date',
        'total_amount',
        'status',



        'address',
        'pic_spot',
        'instructions',
        'instructions_text',
        'delivery_speed_type',
        'detergent_type',
        'is_delicate_cycle',
        'is_hang_dry',
        'is_return_hanger',
        'is_additional_request',
        'coverage_type',






    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function dryOrderItems()
    {
        return $this->hasMany(DryOrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id')->where('order_type', 'dry');
    }


}
