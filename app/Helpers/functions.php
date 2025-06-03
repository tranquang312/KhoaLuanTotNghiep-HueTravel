<?php

if (!function_exists('translateStatus')) {
    function translateStatus($status)
    {
        return match ($status) {
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'cancelled' => 'Đã hủy',
            'completed' => 'Đã hoàn thành',
            default => 'Không xác định'
        };
    }
}
