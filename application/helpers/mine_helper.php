<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');


function semester($semester = null)
{
    $semesters = [
        'first'=>[8, 9, 10, 11, 12],
        'second'=>[1, 2, 3, 4, 5, 6] 
    ];
 return   in_array(date('m'), $semesters[$semester]);
}


function plotted_time(){
  $times = array(
        "morning"   => array("start" => "07:00", "end" => "22:00"),
        "afternoon" => array("start" => "07:00", "end" => "22:00"),
        "evening"   => array("start" => "07:00", "end" => "22:00")
    );

//    $times = array(
//        "morning"   => array("start" => "07:00", "end" => "12:00"),
//        "afternoon" => array("start" => "12:00", "end" => "18:00"),
//        "evening"   => array("start" => "18:00", "end" => "22:00")
//    );
//
  $schedule = $_SESSION['schedule_time'];

  $start = date('H:i',strtotime($times[$schedule]['start']));
  $end = date('H:i',strtotime($times[$schedule]['end']));

  return array('start'=>$start, 'end'=>$end);

}


//function random_color_part()
//{
//  return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
//}

function convert_time($interval = false){
  // start by converting to seconds
  $seconds = ($dec * 3600);
  // we're given hours, so let's get those the easy way
  $hours = floor($dec);
  // since we've "calculated" hours, let's remove them from the seconds variable
  $seconds -= $hours * 3600;
  // calculate minutes left
  $minutes = floor($seconds / 60);
  // remove those from seconds as well
  $seconds -= $minutes * 60;
  // return the time formatted HH:MM:SS
  return $this->lz($hours) . ":" . $this->lz($minutes) . ":" . $this->lz($seconds);
}

function console($data = array())
{
  $array = array();
  echo "<pre>";
  var_export($data);
  die();
}

function FileSizeConvert($bytes)
{
  $bytes = floatval($bytes);
  $arBytes = array(
    0 => array(
      "UNIT" => "TB",
      "VALUE" => pow(1024, 4)
    ),
    1 => array(
      "UNIT" => "GB",
      "VALUE" => pow(1024, 3)
    ),
    2 => array(
      "UNIT" => "MB",
      "VALUE" => pow(1024, 2)
    ),
    3 => array(
      "UNIT" => "KB",
      "VALUE" => 1024
    ),
    4 => array(
      "UNIT" => "B",
      "VALUE" => 1
    ),
  );

  foreach ($arBytes as $arItem) {
    if ($bytes >= $arItem["VALUE"]) {
      $result = $bytes / $arItem["VALUE"];
      $result = str_replace(",", ".", strval(round($result, 2))) . " " . $arItem["UNIT"];
      break;
    }
  }
  return $result;
}

function humanTiming($time)
{
  $time = time() - $time; // to get the time since that moment
  $time = ($time < 1) ? 1 : $time;
  $tokens = array(
    31536000 => 'year',
    2592000 => 'month',
    604800 => 'week',
    86400 => 'day',
    3600 => 'hour',
    60 => 'minute',
    1 => 'second'
  );
  foreach ($tokens as $unit => $text) {
    if ($time < $unit) continue;
    $numberOfUnits = floor($time / $unit);
    return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
  }
}

function doEncrypt($pass)
{
  $CI =& get_instance();
  return $CI->encrypt->encode($pass);
}

function doDecrypt($pass)
{
  $CI =& get_instance();
  return $CI->encrypt->decode($pass);
}

function escape_str($str = "")
{
  $CI =& get_instance();
  if (!empty($str)) {
    return $CI->db->escape_str($str);
  } else {
    return $str;
  }
}

// SESSION
function set_session($name, $data)
{

  $CI =& get_instance();
  $CI->session->set_userdata($name, $data);
}

function get_session($name)
{
  $CI =& get_instance();
  return $CI->session->userdata($name);
}

function delete_session($name)
{
  $CI =& get_instance();
  $CI->session->unset_userdata($name);
}

function destroy_session()
{
  $CI =& get_instance();
  $CI->session->sess_destroy();
}

function set_acronym($phrase)
{

  $words = explode(" ", trim($phrase, " "));
  $acronym = "";

  foreach ($words as $w) {
    $acronym .= $w[0];
  }
  return strtoupper($acronym);
}

?>