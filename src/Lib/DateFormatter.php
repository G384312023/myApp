<?php

class DateFormatter
{
    /**
     * DATETIME文字列を相対的な時間表記に変換します。
     *
     * @param string $dateTimeString 'Y-m-d H:i:s' 形式のDATETIME文字列
     * @return string 整形後の時間文字列
     */
    public static function format(string $dateTimeString): string
    {
        date_default_timezone_set('Asia/Tokyo');

        if (empty($dateTimeString) || $dateTimeString === '0000-00-00 00:00:00') {
            return '';
        }

        $targetTimestamp = strtotime($dateTimeString);
        $currentTimestamp = time();
        $diffSeconds = $currentTimestamp - $targetTimestamp;

        if ($diffSeconds < 0) { // 未来の日付
            return date('Y/n/j H:i', $targetTimestamp);
        }

        if ($diffSeconds < 60) {
            return 'たった今';
        }
        
        if ($diffSeconds < 3600) { // 1時間未満
            return floor($diffSeconds / 60) . '分前';
        }
        
        if ($diffSeconds < 86400) { // 1日未満
            return floor($diffSeconds / 3600) . '時間前';
        }

        // 「昨日」の判定
        $targetDateStr = date('Y-m-d', $targetTimestamp);
        $yesterdayStr = date('Y-m-d', strtotime('yesterday'));
        if ($targetDateStr === $yesterdayStr) {
            return '昨日 ' . date('H:i', $targetTimestamp);
        }

        if ($diffSeconds < 604800) { // 7日未満
            return floor($diffSeconds / 86400) . '日前';
        }

        // 同じ年かどうかでフォーマットを切り替え
        if (date('Y', $targetTimestamp) == date('Y', $currentTimestamp)) {
            return date('n月j日', $targetTimestamp);
        } else {
            return date('Y年n月j日', $targetTimestamp);
        }
    }
}
