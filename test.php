<?php

$local = '/tmp';
$mounted = '/mounted';

function test_run($iterations, $fn, $file) {
  $start = microtime(TRUE);
  for($i = 0; $i < $iterations; $i++) {
    $fn($file);
  }
  return microtime(TRUE) - $start;
}

function print_comparison($name, $local, $mounted) {
  $localMs = round($local * 1000, 2);
  $mountedMs = round($mounted * 1000, 2);
  $pct = ($mountedMs - $localMs) / $localMs * 100;
  echo sprintf('Finished %s.  Local: %dms, Mounted: %dms (%d%% difference)%s', $name, $localMs, $mountedMs, $pct, PHP_EOL);
}

function doStatTest($file) {
  stat($file);
  clearstatcache(FALSE, $file);
}

function doOpenTest($file) {
  $handle = fopen($file, 'r');
  fclose($handle);
}

function doWriteTest($handle) {
  fwrite($handle, mt_rand(0, 9) . PHP_EOL);
}

$localFile = $local.'/test.txt';
$mountedFile = $mounted.'/test.txt';
touch($localFile);
touch($mountedFile);
$stat['local'] = test_run(1000, 'doStatTest', $localFile);
$stat['mounted'] = test_run(1000, 'doStatTest', $mountedFile);
print_comparison('stat x 1000', $stat['local'], $stat['mounted']);

$open['local'] = test_run(1000, 'doOpenTest', $localFile);
$open['mounted'] = test_run(1000, 'doOpenTest', $mountedFile);
print_comparison('open x 1000', $open['local'], $open['mounted']);

$localHandle = fopen($localFile, 'w+');
$mountedHandle = fopen($mountedFile, 'w+');
$write['local'] = test_run(1000, 'doWriteTest', $localHandle);
$write['mounted'] = test_run(1000, 'doWriteTest', $mountedHandle);
print_comparison('write x 1000', $write['local'], $write['mounted']);

fclose($localHandle);
fclose($mountedHandle);
unlink($localFile);
unlink($mountedFile);
