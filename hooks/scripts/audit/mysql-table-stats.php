<?php

$rows = array();
$rows[] = array(
  'Name',
  'Rows',
  'Average row length',
  'Data length',
  'Data free',
);

$table_data = db_query("SHOW TABLE STATUS");
foreach ($table_data as $table) {
  $rows[] = array(
    $table->Name,
    $table->Rows,
    bytes_to_mb($table->Avg_row_length),
    bytes_to_mb($table->Data_length),
    bytes_to_mb($table->Data_free),
  );
}

drush_print_table($rows, TRUE);


function bytes_to_mb($number) {
  if (is_numeric($number)) {
    return round(($number / 1024), 2) .' MB';
  }
}
