<?php

namespace App\Models;

class PetHealthRecord extends BaseModel
{
    protected $table = 'pet_health_record';
    protected $primaryKey = 'health_record_id';

    // Tắt timestamps mặc định vì SQL dùng recorded_at
    public $timestamps = false;

    protected $fillable = ['health_record_id', 'pet_id', 'booking_id', 'recorded_at', 'note', 'status'];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id', 'pet_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }
}
