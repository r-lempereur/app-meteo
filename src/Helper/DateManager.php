<?php


namespace App\Helper;


class DateManager
{
    const FR_DAYS = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi", "Vendredi","Samedi");
    const FR_MOUNTH = array(1 => "Janvier","Février","Mars","Avril","Mai","Juin", "Juillet","Août","Septembre","Octobre","Novembre","Décembre");

    public function getDateFormatFr($date){
        return self::FR_DAYS[$date['wday']]." ".$date['mday']. " ".self::FR_MOUNTH[$date['mon']];
    }

    public function getDateFormatSearch($date){
        $day = strlen($date['mday']) > 1 ? $date['mday'] : "0".$date['mday'];
        $month = strlen($date['mon']) > 1 ? $date['mon'] : "0".$date['mon'];
        return $date['year']."-".$month."-".$day;
    }
}