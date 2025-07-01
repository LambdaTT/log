<?php

namespace Log\EventListeners;

use SplitPHP\Database\Dao;
use SplitPHP\EventListener;
use SplitPHP\Database\Dbmetadata;
use Exception;

class Listeners extends EventListener
{
  public function init()
  {
    $this->addEventListener('log.any', function ($event) {
      if (!Dbmetadata::tableExists('LOG_RECORD')) {
        // If the table does not exist, we cannot log
        return;
      }

      try {
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
      } catch (Exception $e) {
      }
    });

    $this->addEventListener('log.error', function ($event) {
      if (!Dbmetadata::tableExists('LOG_RECORD')) {
        // If the table does not exist, we cannot log
        return;
      }

      try {
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
      } catch (Exception $e) {
        // Handle any exceptions that may occur during logging
      }
    });
  }
}
