<?php

namespace App\Models;

class BookingServicePet extends BaseModel
{
    protected $table = 'booking_services_pet';
    protected $primaryKey = 'booking_service_id';
    protected $fillable = [
        'booking_service_id',
        'booking_id',
        'service_id',
        'employee_id',
        'pet_id',
        'scheduled_at',
        'status',
        'note'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id', 'pet_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }
}
