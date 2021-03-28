<?php


class CONSTANTS
{
public static $month_array = array(
1 => 'فروردین',
2 => 'اردیبهشت',
3 => 'هرذلذ',
4 => 'تیر',
5 => 'مرداد',
6 => 'شهریور',
7 => 'مهر',
8 => 'آبان',
9 => 'آذر',
10 => 'دی',
11 => 'بهمن',
12 => 'اسفند');


public static function getDays(){

    $dozenArr = array("یازده", "دوازده", "سیزده", "چهارده", "پانزده", "شانزده",
        "هفده", "هجده", "نوزده");

    return [
        "اول", "دوم", "سوم", "چهارم", "پنجم", "ششم", "هفتم", "هشتم", "نهم", "دهم",
        "یازدهم", "دوازدهم", "سیزدهم", "چهاردهم", "پانزدهم", "شانزدهم",
        "هفدهم", "هجدهم", "نوزدهم", "بیستم", "بیست و یکم", "بیست و دوم", "بیست و سوم", "بیست و چهارم",
        "بیست و پنجم", "بیست و ششم", "بیست و هفتم", "بیست و هشتم", "بیست و نهم", "سیم", "سی و یکم",
        "سی و دوم", "سی و سوم", "سی و چهارم", "سی و پنجم", "سی و ششم", "سی و هفتم", "سی و هشتم",
        "سی و نهم", "چهلم", "چهل و یکم", "چهل و دوم", "چهل و سوم", " چهل و چهارم", "چهل و پنجم", "چهل و ششم"
    ];
}

public static function getDay($d){
    if ($d <= 40) {
        return self::getDays()[$d];
    } else  {
        $dayArr = array("یکم", "دوم", "سوم", "چهارم", "پنجم", "ششم", "هفتم", "هشتم", "نهم");
        $tensArr = array("ده", "بیست", "سی", "چهل", "پنجاه", "شصت", "هفتاد", "هشتاد", "نود");
        $sadganArr = array("صد", "دویست", "سیصد", "چهارصد", "پانصد","ششصد", "هفتصد", "هشتصد", "نهصد");

        if ($d+1 < 100) {
            $dahgan = $tensArr[$d/10 - 1];
            $yekan = $dayArr[$d % 10 -1];
            $isYekanZero = (($d+1)%10 == 0);
            return $isYekanZero? ($dahgan): ($dahgan . " و" . $yekan);
        } elseif ($d < 1000) {
            $sadgan = $d / 100;
            $dahganVaYekan = ($d% 100);
            $dahgan = $dahganVaYekan/ 10;
            $yekan = $dahganVaYekan% 10;
            $sadganVaDahgan =  $sadganArr[$sadgan - 1] . " و " . $tensArr[$dahgan - 1];
            return $yekan == 0? $sadganVaDahgan: $sadganVaDahgan . " و " . $dayArr[$yekan - 1];
        }
    }
}

    /**
     * Gets the number of month based on 1st half or 2nd half of the year, 31 or 30
     * @param $month Persian Months
     * @return array contains numbers from 1 to 30/31
     */
public static function days_array($month) {
    $key = array_search($month, self::$month_array);
    $days = [];
    $length = 31;
    if ($key > 6) $length = 30;
    for($i = 1; $i <= $length; $i++) {
        $days[] = $i;
    }

    return $days;
}

public static function year() {
    return ['1399', '1400'];
}
}