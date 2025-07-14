<?php

namespace Log\Migrations;

use SplitPHP\DbManager\Migration;
use SplitPHP\Database\DbVocab;

class AddColumnFilepathToLog extends Migration
{
  public function apply()
  {
    $this->Table('LOG_RECORD')
      ->string('ds_filepath');
  }
}
