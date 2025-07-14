<?php

namespace Log\EventListeners;

use SplitPHP\Database\Dao;
use SplitPHP\Database\Database;
use SplitPHP\EventListener;
use SplitPHP\Helpers;
use SplitPHP\Database\Dbmetadata;
use Exception;

class Listeners extends EventListener
{
  private array $evtIds = [];

  public function init(): void
  {
    require_once CORE_PATH . '/database/' . Database::getRdbmsName() . '/class.dbmetadata.php';

    $this->evtIds['log.common'] = $this->addEventListener('log.common', function ($event) {
      if (!Dbmetadata::tableExists('LOG_RECORD')) {
        // If the table does not exist, we cannot log
        $this->removeEventListener($this->evtIds['log.common']);

        $exc = new Exception("The LOG_RECORD table does not exist. Please run Log module's migration.");
        echo PHP_EOL;
        echo "\033[31mERROR[Event:log.common->Exception]: " . $exc->getMessage() . ". In file '" . $exc->getFile() . "', line " . $exc->getLine() . ".\033[0m";
        echo PHP_EOL;

        Helpers::Log()->add('general_error', Helpers::Log()->exceptionBuildLog($exc, [
          'eventName' => $event->getName(),
          'eventInfo' => $event->info()
        ]));
        return;
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

        $exc = new Exception("The LOG_RECORD table does not exist. Please run Log module's migration.");
        echo PHP_EOL;
        echo "\033[31mERROR[Event:log.error->Exception]: " . $exc->getMessage() . ". In file '" . $exc->getFile() . "', line " . $exc->getLine() . ".\033[0m";
        echo PHP_EOL;

        Helpers::Log()->add('general_error', Helpers::Log()->exceptionBuildLog($exc, [
          'eventName' => $event->getName(),
          'eventInfo' => $event->info()
        ]));

        return;
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
