<?php
require __DIR__ . '/db.php';
json_out(200, ['ok' => true, 'ts' => time()]);
