<?php

namespace Bprep;

use DateTime;
use DateTimeZone;
use DateInterval;
use DatePeriod;
use InvalidArgumentException;
use Bprep\Holiday\Holidays;

class BpDate
{
    const STRING_DATE_FORMAT = 'Y-m-d';
    const STRING_TIME_FORMAT = 'H:i:s';
    const STRING_FORMAT = 'Y-m-d H:i:s';

    const SUNDAY = 0;
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;

    /**
     * 曜日
     *
     * @var array
     */
    protected static $days = [
        self::SUNDAY => 'Sunday',
        self::MONDAY => 'Monday',
        self::TUESDAY => 'Tuesday',
        self::WEDNESDAY => 'Wednesday',
        self::THURSDAY => 'Thursday',
        self::FRIDAY => 'Friday',
        self::SATURDAY => 'Saturday'
    ];

    /**
     * 曜日
     *
     * @var array
     */
    protected static $daysJa = [
        self::SUNDAY => '日',
        self::MONDAY => '月',
        self::TUESDAY => '火',
        self::WEDNESDAY => '水',
        self::THURSDAY => '木',
        self::FRIDAY => '金',
        self::SATURDAY => '土'
    ];

    public static function today()
    {
        return new DateTime();
    }

    /**
     * 祝日リスト
     * @param DateTime $start
     * @param DateTime $last
     * @return array
     */
    public static function geHolidayBetween(DateTime $start, DateTime $last)
    {
        $holidayList = array();
        $start = self::formatYmd($start);
        $last = self::formatYmd($last);
        foreach (Holidays::$holidays as $key => $value) {
            $d = new DateTime($key);
            if ($start <= $d && $d <= $last) {
                $value['date'] = $d;
                $holidayList[] = $value;
            }
        }
        return $holidayList;
    }

    /**
     * 祝日判定
     * @param DateTime $date
     * @return bool
     */
    public static function isHoliday(DateTime $date)
    {
        return array_key_exists($date->format('Y-m-d'), Holidays::$holidays);
    }


    /**
     * 曜日リスト
     * @return array
     */
    public static function getWeekNameList()
    {
        return static::$daysJa;
    }

    /**
     * 曜日（日本語）
     *
     * @param $date
     * @return mixed
     */
    public static function getWeekJ(DateTime $dt) {
        return static::$daysJa[(int)$dt->format('w')];
    }

    /**
     * 曜日
     *
     * @param $date
     * @return mixed
     */
    public static function getWeek(DateTime $dt) {
        return static::$days[(int)$dt->format('w')];
    }

    /**
     * 指定期間内日付けリスト
     * @param $date_start
     * @param $date_end
     * @return array
     */
    public static function getDataList(DateTime $start, DateTime $end)
    {
        $dayList = array();
        $start = new DateTime(static::toDateString($start) . ' ' . '00:00:00');
        $end = new DateTime(static::toDateString($end) . ' ' . '23:59:59');
        $addInterval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($start, $addInterval, $end);
        foreach ($period as $d)
        {
            $dayList[] = [
                'date' => $d,
                'dateYmd' => static::toDateString($d),
                'week_ja' => static::getWeekJ($d),
                'week' => $d->format('w')
            ];
        }
        return $dayList;
    }

    /**
     * 明日
     * @param null $tz
     * @return DateTime
     */
    public static function tomorrow()
    {
        return static::addDay(static::today());
    }

    /**
     * 指定日に1日プラス
     * @param DateTime $dt
     * @return DateTime
     */
    public static function addDay(DateTime $dt)
    {
        return static::addDays($dt, 1);
    }

    /**
     * 昨日
     * @param null $tz
     * @return DateTime
     */
    public static function yesterday($tz = null)
    {
        return static::subDay(static::today());
    }

    /**
     * 指定日に1日マイナス
     * @param DateTime $dt
     * @return DateTime
     */
    public static function subDay(DateTime $dt)
    {
        return static::addDays($dt, 1);
    }

    /**
     * 指定日時に指定日分プラス・マイナス
     * @param DateTime $dt
     * @param $value
     * @return DateTime
     */
    public static function addDays(DateTime $dt, $value)
    {
        return $dt->modify((int) $value . ' day');
    }

    /**
     * 年プラス
     * @param DateTime $dt
     * @param $value
     * @return DateTime
     */
    public static function addYears(DateTime $dt, $value)
    {
        return $dt->modify((int) $value . ' year');
    }

    /**
     * 年プラス 1年
     * @param DateTime $dt
     * @return DateTime
     */
    public static function addYear(DateTime $dt)
    {
        return static::addYears($dt, 1);
    }

    /**
     * 年マイナス　1年
     * @param DateTime $dt
     * @return DateTime
     */
    public static function subYear(DateTime $dt)
    {
        return static::addYears($dt, -1);
    }

    /**
     * 年マイナス
     * @param DateTime $dt
     * @param $value
     * @return DateTime
     */
    public static function subYears(DateTime $dt, $value)
    {
        return static::addYears($dt, (-1 * $value));
    }

    /**
     * 月プラス
     * @param DateTime $dt
     * @param $value
     * @return DateTime
     */
    public static function addMonths(DateTime $dt, $value)
    {
        return $dt->modify((int) $value . ' month');
    }

    /**
     * 月プラス 1ヶ月
     * @param DateTime $dt
     * @return DateTime
     */
    public static function addMonth(DateTime $dt)
    {
        return static::addMonths($dt, 1);
    }

    /**
     * 月マイナス 1ヶ月
     * @param DateTime $dt
     * @return DateTime
     */
    public static function subMonth(DateTime $dt)
    {
        return static::addMonths($dt, -1);
    }

    /**
     * 月マイナス
     * @param DateTime $dt
     * @param $value
     * @return DateTime
     */
    public static function subMonths(DateTime $dt, $value)
    {
        return static::addMonths($dt, (-1 * $value));
    }

    /**
     * 週プラス
     * @param DateTime $dt
     * @param $value
     * @return DateTime
     */
    public static function addWeeks(DateTime $dt, $value)
    {
        return $dt->modify((int) $value . ' weeks');
    }

    /**
     * 1週間後
     * @param DateTime $dt
     * @return DateTime
     */
    public static function addWeek(DateTime $dt)
    {
        return static::addWeeks($dt, 1);
    }

    /**
     * 1週間前
     * @param DateTime $dt
     * @return DateTime
     */
    public static function subWeek(DateTime $dt)
    {
        return static::addWeeks($dt, -1);
    }

    /**
     * 週マイナス
     * @param DateTime $dt
     * @param $value
     * @return DateTime
     */
    public static function subWeeks(DateTime $dt, $value)
    {
        return static::addWeeks($dt, (-1 * $value));
    }

    /**
     * 文字列にフォーマット 年月日
     *
     * @param DateTime $dt
     * @return string
     */
    public static function toDateString(DateTime $dt)
    {
        return $dt->format(static::STRING_DATE_FORMAT);
    }

    /**
     * 文字列にフォーマット 年月日時分秒
     * @param DateTime $dt
     * @return string
     */
    public static function toDateTimeString(DateTime $dt)
    {
        return $dt->format(static::STRING_FORMAT);
    }

    /**
     * 文字列にフォーマット 時分秒
     * @param DateTime $dt
     * @return string
     */
    public static function toTimeString(DateTime $dt)
    {
        return $dt->format(static::STRING_TIME_FORMAT);
    }

    /**
     * 日付け生成
     * @param null $format
     * @param null $year
     * @param null $month
     * @param null $day
     * @param null $hour
     * @param null $minute
     * @param null $second
     * @return DateTime
     */
    public static function createDate($year = null, $month = null, $day = null, $hour = null, $minute = null, $second = null)
    {
        $year = ($year === null) ? date('Y') : $year;
        $month = ($month === null) ? date('m') : $month;
        $day = ($day === null) ? date('d') : $day;

        $hour = ($hour === null) ? date('H') : $hour;
        $minute = ($minute === null) ? date('i') : $minute;
        $second = ($second === null) ? date('s') : $second;

        return static::createFormatDate(static::STRING_FORMAT, sprintf('%s-%s-%s %s:%02s:%02s', $year, $month, $day, $hour, $minute, $second));
    }

    /**
     * 日付け生成
     * @param null $format
     * @param null $year
     * @param null $month
     * @param null $day
     * @return DateTime
     */
    public static function createYmdDate($year = null, $month = null, $day = null)
    {
        return static::createDate($year, $month, $day, null, null, null);
    }

    /**
     * 指定したTimeStampの日付け
     *
     * @param $timestamp
     * @return DateTime
     */
    public static function createTimestamp($timestamp)
    {
        return static::today()->setTimestamp($timestamp);
    }




    // private -------------------------------------------
    private static function createFormatDate($format, $time)
    {
        return DateTime::createFromFormat($format, $time);
    }
    private static function formatYmd(DateTime $date)
    {
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');
        return new DateTime($year . '-' . $month . '-' . $day);
    }
}