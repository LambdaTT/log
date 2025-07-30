<?php

namespace Log\Services;

use SplitPHP\Service;
use SplitPHP\Utils;

class LogService extends Service
{
  public function list(array $params = [])
  {
    $params['$sort_by'] = 3; // dt_log
    $params['$sort_direction'] = 'DESC';

    $begginingOfDay = date('Y-m-d') . ' 00:00:00';
    $endOfDay = date('Y-m-d') . ' 23:59:59';

    $params['dt_log'] = $params['dt_log'] ?? '$btwn|' . "{$begginingOfDay}|{$endOfDay}";

    $result = [];
    if (!isset($params['ds_context'])) {
      unset($params['ds_context']);
      $result = [
        'server' => $this->serverErrorLog(),
      ];
    }

    $this->getDao('LOG_RECORD')
      ->bindParams($params)
      ->fetch(function (&$record) use (&$result) {
        $record->tx_message = json_decode($record->tx_message) ?? $record->tx_message;
        if (!array_key_exists($record->ds_context, $result)) {
          $result[$record->ds_context] = [];
        }

        $result[$record->ds_context][]  = $record;
      });

    return $result;
  }

  public function serverErrorLog($reverse = true)
  {
    $path = ROOT_PATH . '/log/server.log';
    $data = [];

    if (file_exists($path)) {
      $pattern = '/^\[\d{1,2}-[A-Za-z]{3}-\d{4} \d{2}:\d{2}:\d{2} UTC\].*?(?=^\[\d{1,2}-[A-Za-z]{3}-\d{4} \d{2}:\d{2}:\d{2} UTC\]|\z)/ms';
      preg_match_all($pattern, file_get_contents($path), $matches);
      $rawData = $matches[0] ?? [];
      foreach ($rawData as $entry) {
        $dates = [];
        preg_match('/\[(.*)\]/', $entry, $dates);

        if (!empty($dates[0])) {
          $entry = preg_replace('/\[(.*)\]/', '', $entry);
          $entry = trim($entry);
          $data[] = [
            "datetime" => date('Y-m-d H:i:s', strtotime($dates[1])),
            "message" => $entry
          ];
        }
      }
    }

    if ($reverse) $data = array_reverse($data);

    return array_values(array_filter($data));
  }

  public function checkAuthHeader()
  {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
    if (!$authHeader) {
      return false;
    }

    // Validate the token (this is just a placeholder, implement your own logic)
    $token = str_replace('Bearer ', '', $authHeader);
    $decrypted = Utils::dataDecrypt($token, PRIVATE_KEY);
    if ($decrypted != hash('sha256', PUBLIC_KEY))
      return false;

    return true;
  }

  public function clear()
  {
    $this->getDao('LOG_RECORD')
      ->delete();
  }
}
