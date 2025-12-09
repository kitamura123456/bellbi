<?php

namespace App\Services;

use App\Models\Store;
use App\Models\Reservation;
use App\Models\ReservationBlock;
use Carbon\Carbon;

class ReservationService
{
    /**
     * 指定日の空き枠を30分単位で取得
     */
    public function getAvailableSlots(Store $store, string $date, int $durationMinutes, ?int $staffId = null): array
    {
        $targetDate = Carbon::parse($date);
        $dayOfWeek = $targetDate->dayOfWeek;

        // 営業スケジュール取得
        $schedule = $store->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_open', 1)
            ->where('delete_flg', 0)
            ->first();

        if (!$schedule || !$schedule->open_time || !$schedule->close_time) {
            return []; // 定休日または営業時間未設定
        }

        $openTime = Carbon::parse($date . ' ' . $schedule->open_time);
        $closeTime = Carbon::parse($date . ' ' . $schedule->close_time);

        // 既存予約を取得
        $reservations = Reservation::where('store_id', $store->id)
            ->where('reservation_date', $date)
            ->whereIn('status', [Reservation::STATUS_CONFIRMED])
            ->where('delete_flg', 0);

        if ($staffId) {
            $reservations->where('staff_id', $staffId);
        }

        $reservations = $reservations->get();

        // ブロック情報を取得
        $blocks = ReservationBlock::where('store_id', $store->id)
            ->where('block_date', $date)
            ->where('delete_flg', 0);

        if ($staffId) {
            $blocks->where(function($q) use ($staffId) {
                $q->whereNull('staff_id')->orWhere('staff_id', $staffId);
            });
        }

        $blocks = $blocks->get();

        // 30分単位の枠を生成
        $slots = [];
        $currentTime = $openTime->copy();

        while ($currentTime->copy()->addMinutes($durationMinutes) <= $closeTime) {
            $slotStart = $currentTime->copy();
            $slotEnd = $currentTime->copy()->addMinutes($durationMinutes);

            // この枠が予約可能かチェック
            if ($this->isSlotAvailable($slotStart, $slotEnd, $reservations, $blocks)) {
                $slots[] = [
                    'start_time' => $slotStart->format('H:i'),
                    'end_time' => $slotEnd->format('H:i'),
                ];
            }

            $currentTime->addMinutes(30);
        }

        return $slots;
    }

    /**
     * 指定時間枠が予約可能かチェック
     */
    private function isSlotAvailable(Carbon $slotStart, Carbon $slotEnd, $reservations, $blocks): bool
    {
        // 既存予約との重複チェック
        foreach ($reservations as $reservation) {
            $resStart = Carbon::parse($reservation->reservation_date . ' ' . $reservation->start_time);
            $resEnd = Carbon::parse($reservation->reservation_date . ' ' . $reservation->end_time);

            if ($this->isOverlapping($slotStart, $slotEnd, $resStart, $resEnd)) {
                return false;
            }
        }

        // ブロックとの重複チェック
        foreach ($blocks as $block) {
            $blockStart = Carbon::parse($block->block_date . ' ' . $block->start_time);
            $blockEnd = Carbon::parse($block->block_date . ' ' . $block->end_time);

            if ($this->isOverlapping($slotStart, $slotEnd, $blockStart, $blockEnd)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 時間の重複判定
     */
    private function isOverlapping(Carbon $start1, Carbon $end1, Carbon $start2, Carbon $end2): bool
    {
        return $start1->lt($end2) && $end1->gt($start2);
    }

    /**
     * キャンセル可能かチェック
     */
    public function canCancel(Reservation $reservation): bool
    {
        if ($reservation->status !== Reservation::STATUS_CONFIRMED) {
            return false;
        }

        $store = $reservation->store;
        if (!$store->cancel_deadline_hours) {
            return true; // 期限設定なしの場合は常にキャンセル可能
        }

        $reservationDateTime = Carbon::parse($reservation->reservation_date . ' ' . $reservation->start_time);
        $deadline = $reservationDateTime->copy()->subHours($store->cancel_deadline_hours);

        return now()->lt($deadline);
    }
}

