<?php

namespace Log\Commands;

use SplitPHP\Cli;

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
  }
}
