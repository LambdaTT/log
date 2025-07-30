<?php

namespace Log\Commands;

use SplitPHP\Cli;
use SplitPHP\Utils;

class Commands extends Cli
{
  public function init(): void
  {
    $this->addCommand('server', function () {
      $data = $this->getService('log/log')->serverErrorLog();
      print_r($data);
    });

    $this->addCommand('records', function ($args) {
      $params = [];
      if (isset($args['--context']) || isset($args[0])) {
        $params['ds_context'] = $args['--context'] ?? $args[0];
      }

      $records = $this->getService('log/log')->list($params);
      print_r($records);
    });

    $this->addCommand('clear', function () {
      Utils::printLn(">> Careful! This command will clear all log records.");
      Utils::printLn(">>> Are you sure you want to proceed? (y/n)");
      $confirmation = trim(fgets(STDIN));
      if (strtolower($confirmation) === 'y') {
        $this->getService('log/log')->clear();
        Utils::printLn(">> Log records cleared successfully.");
      } else {
        Utils::printLn(">> Operation cancelled.");
      }
    });
  }
}
