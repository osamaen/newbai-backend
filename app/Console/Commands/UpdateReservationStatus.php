<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class UpdateReservationStatus extends Command
{
    protected $signature = 'reservations:daily-update';
    protected $description = 'Update reservation statuses at the start of each day';

    
    const STATUS = [
        'PENDING' => 1,
        'CONFIRMED' => 2,
        'CHECKED_IN' => 3,
        'CHECKED_OUT' => 4,
        'CANCELED' => 5,
        'NO_SHOW' => 6,
        'ON_HOLD' => 7,
        'AWAITING_PAYMENT' => 8,
        'OVERDUE' => 9,
        'PARTIALLY_PAID' => 10,
        'REFUNDED' => 11
    ];

    public function handle()
    {
        $now = Carbon::now();
        
        // Update No-Shows
        // If guest hasn't checked in 24 hours after check-in time
        Reservation::whereIn('status_id', [self::STATUS['CONFIRMED'], self::STATUS['AWAITING_PAYMENT'], self::STATUS['PARTIALLY_PAID']])
            ->where('check_in_date', '<', $now->copy()->subHours(24))
            ->where('is_checked_in',0)
            ->update(['status_id' => self::STATUS['NO_SHOW']]);

        // Update Overdue
        // If guest hasn't checked out by check-out time
        Reservation::where('status_id', self::STATUS['CHECKED_IN'])
            ->where('check_out_date', '<', $now)
            ->update(['status_id' => self::STATUS['OVERDUE']]);

        // Cancel unpaid reservations
        // If payment not received within 48 hours of booking
        // Reservation::whereIn('status_id', [self::STATUS['AWAITING_PAYMENT'], self::STATUS['PARTIALLY_PAID']])
        //     ->where('created_at', '<', $now->copy()->subHours(48))
        //     ->update([
        //         'status_id' => self::STATUS['CANCELED'],
        //         'cancellation_reason' => 'Automatic cancellation due to payment timeout'
        //     ]);

        // Auto confirm reservations when full payment is received
        // This assumes you have a payments table and relationship
        // Reservation::where('status_id', self::STATUS['PARTIALLY_PAID'])
        //     ->whereHas('payments', function ($query) {
        //         $query->selectRaw('reservation_id, SUM(amount) as total_paid')
        //             ->groupBy('reservation_id')
        //             ->havingRaw('total_paid >= reservations.total_amount');
        //     })
        //     ->update(['status_id' => self::STATUS['CONFIRMED']]);

        // Handle pending reservations timeout
        // Cancel if pending for more than 24 hours
        // Reservation::where('status_id', self::STATUS['PENDING'])
        //     ->where('created_at', '<', $now->copy()->subHours(24))
        //     ->update([
        //         'status_id' => self::STATUS['CANCELED'],
        //         'cancellation_reason' => 'Automatic cancellation due to pending timeout'
        //     ]);

        $this->info('Reservation statuses updated successfully');
    }
}