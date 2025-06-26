<?php

namespace Log\EventListeners;

use SplitPHP\Database\Dao;
use SplitPHP\EventListener;

class Listeners extends EventListener
{
  public function init()
  {
    $this->addEventListener('log.any', function ($event) {
      // Handle the log event
      $this->getDao('LOG_RECORD')
        ->insert([
          'ds_key' => 'log-' . uniqid(),
          'dt_log' => $event->getDatetime(),
          'ds_context' => $event->getLogName(),
          'tx_message' => json_encode($event->getLogMsg()) ?? $event->getLogMsg(),
          'ds_filepath' => $event->getLogFilePath()
        ]);

      Dao::flush();
    });

    $this->addEventListener('log.error', function ($event) {
      // Handle the log event
      $this->getDao('LOG_RECORD')
        ->insert([
          'ds_key' => 'log-' . uniqid(),
          'dt_log' => $event->getDatetime(),
          'ds_context' => $event->getLogName(),
          'ds_filepath' => $event->getLogFilePath(),
          'tx_message' => json_encode($event->getLogMsg()) ?? $event->getLogMsg(),
        ]);

      Dao::flush();
    });
  }
}
