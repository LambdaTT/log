<?php

namespace application\services;

use \engine\Service;
use \engine\Utils;

class Log_service extends Service
{
  public function dbErrorLog($reverse = true)
  {
    $data = [];

    if (file_exists(ROOT_PATH . '/application/log/db_error.log')) {
      $rawLogData = explode(str_repeat(PHP_EOL, 2), file_get_contents(ROOT_PATH . '/application/log/db_error.log'));
      foreach ($rawLogData as $logEntry) {
        $logEntry = preg_replace('/\[.*\] - /', '', $logEntry);
        $data[] = json_decode($logEntry);
      }
    }

    if ($reverse) $data = array_reverse($data);

    return array_values(array_filter($data));
  }

  public function customErrorLog($logName, $reverse = true)
  {
    $data = [];

    if (file_exists(ROOT_PATH . "/application/log/{$logName}.log")) {
      $rawLogData = explode(str_repeat(PHP_EOL, 2), file_get_contents(ROOT_PATH . "/application/log/{$logName}.log"));
      foreach ($rawLogData as $logEntry) {
        $logEntry = preg_replace('/\[.*\] - /', '', $logEntry);
        if (is_object(json_decode($logEntry)))
          $data[] = json_decode($logEntry);
        else $data[] = $logEntry;
      }
    }

    if ($reverse) $data = array_reverse($data);

    return array_values(array_filter($data));
  }

  public function applicationErrorLog($reverse = true)
  {
    $data = [];

    if (file_exists(ROOT_PATH . '/application/log/application_error.log')) {
      $rawLogData = explode(str_repeat(PHP_EOL, 2), file_get_contents(ROOT_PATH . '/application/log/application_error.log'));
      foreach ($rawLogData as $logEntry) {
        $logEntry = preg_replace('/\[.*\] - /', '', $logEntry);
        $data[] = json_decode($logEntry);
      }
    }

    if ($reverse) $data = array_reverse($data);

    return array_values(array_filter($data));
  }

  public function securityLog($reverse = true)
  {
    $data = [];

    if (file_exists(ROOT_PATH . '/application/log/security.log')) {
      $rawLogData = explode(str_repeat(PHP_EOL, 2), file_get_contents(ROOT_PATH . '/application/log/security.log'));
      foreach ($rawLogData as $logEntry) {
        $logEntry = preg_replace('/\[.*\] - /', '', $logEntry);
        $data[] = json_decode($logEntry);
      }
    }

    if ($reverse) $data = array_reverse($data);

    return array_values(array_filter($data));
  }

  public function systemErrorLog($reverse = true)
  {
    $data = [];

    if (file_exists(ROOT_PATH . '/application/log/sys_error.log')) {
      $rawLogData = explode(str_repeat(PHP_EOL, 2), file_get_contents(ROOT_PATH . '/application/log/sys_error.log'));
      foreach ($rawLogData as $logEntry) {
        $logEntry = preg_replace('/\[.*\] - /', '', $logEntry);
        $data[] = json_decode($logEntry);
      }
    }

    if ($reverse) $data = array_reverse($data);

    return array_values(array_filter($data));
  }

  public function curlLog($reverse = true)
  {
    $data = [];

    if (file_exists(ROOT_PATH . '/application/log/curl.log')) {
      $rawLogData = explode(str_repeat(PHP_EOL, 2), file_get_contents(ROOT_PATH . '/application/log/curl.log'));
      foreach ($rawLogData as $logEntry) {
        $logEntry = preg_replace('/\[.*\] - /', '', $logEntry);
        $data[] = json_decode($logEntry);
      }
    }

    if ($reverse) $data = array_reverse($data);

    return array_values(array_filter($data));
  }

  public function eventErrorLog($reverse = true)
  {
    $data = [];
    $path = ROOT_PATH . '/application/log/event_error.log';

    if (file_exists($path)) {
      $rawLogData = explode(str_repeat(PHP_EOL, 2), file_get_contents($path));
      foreach ($rawLogData as $logEntry) {
        $logEntry = preg_replace('/\[.*\] - /', '', $logEntry);
        $data[] = json_decode($logEntry);
      }
    }

    if ($reverse) $data = array_reverse($data);

    return array_values(array_filter($data));
  }

  public function serverErrorLog($reverse = true)
  {
    $path = ROOT_PATH . '/application/log/server.log';
    $data = [];

    if (file_exists($path)) {
      $rawData = explode(PHP_EOL, file_get_contents($path));
      foreach ($rawData as $entry) {
        $parsingData = [];
        preg_match('/\[(.*)\] (.*)/', $entry, $parsingData);

        if (!empty($parsingData))
          $data[] = [
            "datetime" => date('Y-m-d H:i:s', strtotime($parsingData[1])),
            "message" => $parsingData[2]
          ];
      }
    }

    if ($reverse) $data = array_reverse($data);

    return array_values(array_filter($data));
  }
}
