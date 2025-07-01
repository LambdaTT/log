<?php

namespace Log\EventListeners;

use SplitPHP\Database\Dao;
use SplitPHP\EventListener;
use SplitPHP\Database\Dbmetadata;
use Exception;

class Listeners extends EventListener
{
  private array $evtIds = [];

  public function init()
  {
    require_once CORE_PATH . '/database/' . DBTYPE . '/class.dbmetadata.php';

    $this->evtIds['log.any'] = $this->addEventListener('log.any', function ($event) {
      if (!Dbmetadata::tableExists('LOG_RECORD')) {
        // If the table does not exist, we cannot log
        $this->removeEventListener($this->evtIds['log.any']);
        $this->removeEventListener($this->evtIds['log.error']);
        throw new Exception("The LOG_RECORD table does not exist. Please run Log module's migration.");
      }

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

    $this->evtIds['log.error'] = $this->addEventListener('log.error', function ($event) {
      if (!Dbmetadata::tableExists('LOG_RECORD')) {
        // If the table does not exist, we cannot log
        $this->removeEventListener($this->evtIds['log.error']);
        $this->removeEventListener($this->evtIds['log.any']);
        throw new Exception("The LOG_RECORD table does not exist. Please run Log module's migration.");
      }

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
  }
}
