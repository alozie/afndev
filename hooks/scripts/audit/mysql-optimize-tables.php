<?php

$table_data = db_query("SHOW TABLE STATUS");
foreach ($table_data as $table) {
  drush_print("Optimizing $table->Name");
  db_query("OPTIMIZE TABLE $table->Name");
}
