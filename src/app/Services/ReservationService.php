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

        // その日のすべての確定予約を取得（店舗全体）
        $allReservations = Reservation::where('store_id', $store->id)
            ->where('reservation_date', $date)
            ->whereIn('status', [Reservation::STATUS_CONFIRMED])
            ->where('delete_flg', 0)
            ->get();

        // スタッフ指定がある場合は、そのスタッフの予約のみ
        $staffReservations = $staffId 
            ? $allReservations->where('staff_id', $staffId)
            : collect();

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
            $isAvailable = true;

            // 1. スタッフ指定がある場合：そのスタッフが空いているかチェック
            if ($staffId) {
                if (!$this->isSlotAvailable($slotStart, $slotEnd, $staffReservations, $blocks)) {
                    $isAvailable = false;
                }
            }

            // 2. 店舗の同時対応可能人数チェック（曜日ごと）
            if ($isAvailable) {
                $concurrentCount = $this->getConcurrentReservationCount($slotStart, $slotEnd, $allReservations);
                $slotDayOfWeek = $slotStart->dayOfWeek;
                $schedule = $store->schedules()
                    ->where('day_of_week', $slotDayOfWeek)
                    ->where('is_open', 1)
                    ->where('delete_flg', 0)
                    ->first();
                $maxConcurrent = $schedule->max_concurrent_reservations ?? 1;
                
                if ($concurrentCount >= $maxConcurrent) {
                    $isAvailable = false;
                }
            }

            // 3. ブロック時間チェック（スタッフ指定なしの場合）
            if ($isAvailable && !$staffId) {
                foreach ($blocks as $block) {
                    if ($block->staff_id === null) { // 店舗全体のブロック
                        $blockStart = Carbon::parse($block->block_date . ' ' . $block->start_time);
                        $blockEnd = Carbon::parse($block->block_date . ' ' . $block->end_time);
                        
                        if ($this->isOverlapping($slotStart, $slotEnd, $blockStart, $blockEnd)) {
                            $isAvailable = false;
                            break;
                        }
                    }
                }
            }

            if ($isAvailable) {
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
     * 指定時間帯の同時予約数を取得
     */
    private function getConcurrentReservationCount(Carbon $slotStart, Carbon $slotEnd, $reservations): int
    {
        $count = 0;
        
        foreach ($reservations as $reservation) {
            $date = substr($reservation->reservation_date, 0, 10); // YYYY-MM-DD
            $startTime = substr($reservation->start_time, 11, 8); // HH:MM:SS (datetime形式の場合)
            $endTime = substr($reservation->end_time, 11, 8);
            
            // time形式の場合は11文字目が存在しないので、そのまま使う
            if (strlen($reservation->start_time) <= 8) {
                $startTime = $reservation->start_time;
                $endTime = $reservation->end_time;
            }
            
            $resStart = Carbon::parse($date . ' ' . $startTime);
            $resEnd = Carbon::parse($date . ' ' . $endTime);

            if ($this->isOverlapping($slotStart, $slotEnd, $resStart, $resEnd)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * 指定時間枠が予約可能かチェック
     */
    private function isSlotAvailable(Carbon $slotStart, Carbon $slotEnd, $reservations, $blocks): bool
    {
        // 既存予約との重複チェック
        foreach ($reservations as $reservation) {
            $date = substr($reservation->reservation_date, 0, 10); // YYYY-MM-DD
            $startTime = substr($reservation->start_time, 11, 8); // HH:MM:SS
            $endTime = substr($reservation->end_time, 11, 8);
            
            // time形式の場合は11文字目が存在しないので、そのまま使う
            if (strlen($reservation->start_time) <= 8) {
                $startTime = $reservation->start_time;
                $endTime = $reservation->end_time;
            }
            
            $resStart = Carbon::parse($date . ' ' . $startTime);
            $resEnd = Carbon::parse($date . ' ' . $endTime);

            if ($this->isOverlapping($slotStart, $slotEnd, $resStart, $resEnd)) {
                return false;
            }
        }

        // ブロックとの重複チェック
        foreach ($blocks as $block) {
            $date = substr($block->block_date, 0, 10);
            $startTime = strlen($block->start_time) > 8 ? substr($block->start_time, 11, 8) : $block->start_time;
            $endTime = strlen($block->end_time) > 8 ? substr($block->end_time, 11, 8) : $block->end_time;
            
            $blockStart = Carbon::parse($date . ' ' . $startTime);
            $blockEnd = Carbon::parse($date . ' ' . $endTime);

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

        // reservation_dateとstart_timeから正しくDateTimeを構築
        $date = substr($reservation->reservation_date, 0, 10); // YYYY-MM-DD
        $time = strlen($reservation->start_time) > 8 
            ? substr($reservation->start_time, 11, 8) // datetime形式の場合
            : $reservation->start_time; // time形式の場合
        
        $reservationDateTime = Carbon::parse($date . ' ' . $time);
        $deadline = $reservationDateTime->copy()->subHours($store->cancel_deadline_hours);

        return now()->lt($deadline);
    }
}

