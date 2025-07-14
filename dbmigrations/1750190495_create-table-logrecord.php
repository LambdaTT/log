<?php

namespace Log\Migrations;

use SplitPHP\DbManager\Migration;
use SplitPHP\Database\DbVocab;

class CreateTableLogrecord extends Migration
{
  public function apply()
  {
    $this->Table('LOG_RECORD', 'Log Record')
      ->id('id_log_record')
      ->string('ds_key', 17)
      ->datetime('dt_log')->setDefaultValue(DbVocab::SQL_CURTIMESTAMP())
      ->string('ds_context', 100)
      ->text('tx_message')
      ->Index('KEY', DbVocab::IDX_UNIQUE)->onColumn('ds_key');
  }
}
