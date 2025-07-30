<?php

namespace Log\EventListeners;

use SplitPHP\Database\Dao;
use SplitPHP\Database\Database;
use SplitPHP\EventListener;
use SplitPHP\Helpers;
use SplitPHP\System;
use Throwable;

class Listeners extends EventListener
{
  private array $evtIds = [];

  public function init(): void
  {
    require_once CORE_PATH . '/database/' . Database::getRdbmsName() . '/class.dbmetadata.php';

    $this->evtIds['log.common'] = $this->addEventListener('log.common', function ($event) {
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
      } catch (Throwable $exc) {
        if (System::$bootType == 'cli') {
          echo PHP_EOL;
          echo "\033[31mERROR[Event:log.common->Exception]: " . $exc->getMessage() . ". In file '" . $exc->getFile() . "', line " . $exc->getLine() . ".\033[0m";
          echo PHP_EOL;
        } elseif (System::$bootType == 'web') {
          $this->respondException($exc);
        }

        // Remove the event listener if an error occurs
        // This is to prevent the same error from being logged repeatedly
        // and to ensure that the system can continue functioning.
        $this->removeEventListener($this->evtIds['log.common']);

        Helpers::Log()->add('general_error', Helpers::Log()->exceptionBuildLog($exc, [
          'eventName' => $event->getName(),
          'eventInfo' => $event->info()
        ]));
      }
    });

    $this->evtIds['log.error'] = $this->addEventListener('log.error', function ($event) {
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
      } catch (Throwable $exc) {
        if (System::$bootType == 'cli') {
          echo PHP_EOL;
          echo "\033[31mERROR[Event:log.error->Exception]: " . $exc->getMessage() . ". In file '" . $exc->getFile() . "', line " . $exc->getLine() . ".\033[0m";
          echo PHP_EOL;
        } elseif (System::$bootType == 'web') {
          $this->respondException($exc);
        }

        // Remove the event listener if an error occurs
        // This is to prevent the same error from being logged repeatedly
        // and to ensure that the system can continue functioning.
        $this->removeEventListener($this->evtIds['log.error']);

        Helpers::Log()->add('general_error', Helpers::Log()->exceptionBuildLog($exc, [
          'eventName' => $event->getName(),
          'eventInfo' => $event->info()
        ]));
      }
    });
  }

  /**
   * Responds to the exception with a JSON response.
   *
   * This method sets the HTTP response code and returns a JSON response with the exception details.
   * It includes information about the request, method, URL, parameters, and body.
   *
   * @param Throwable $exception The exception to respond to.
   * @return void
   */
  private static function respondException(Throwable $exception): void
  {
    $request = System::$currentRequest;
    $status = 500;
    $responseData = [
      "error" => true,
      "accessible" => false,
      "message" => $exception->getMessage(),
      "file" => $exception->getFile(),
      "line" => $exception->getLine(),
    ];

    if (!empty($request)) {
      $responseData = [
        ...$responseData,
        ...[
          "request" => $request->__toString(),
          "method" => $request->getVerb(),
          "url" => $request->getRoute()->url,
          "params" => $request->getRoute()->params,
          "body" => $request->getBody()
        ]
      ];
    }

    http_response_code($status);

    if (!empty($responseData)) {
      header('Content-Type: application/json');
      echo json_encode($responseData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
  }
}
