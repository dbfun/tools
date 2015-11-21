#!/usr/bin/env php
<?php

global $argv;
if(!isset($argv[1], $argv[2])) {
  fwrite(STDERR, "Use spfcheck.php IP SPF\n");
  exit(1);
}

$ip = trim($argv[1]);
$spf = trim($argv[2]);

if(!preg_match('~^([0-9]{1,3}\.){3}[0-9]{1,3}$~', $ip)) {
  fwrite(STDERR, "Wrong IP!\n");
  exit(1);
}

$results = array();
if(preg_match('~ip4:'.preg_quote($ip).'~', $spf)) {
  $results[] = "SPF: can send mail from $ip";
} elseif(preg_match('~\b\+?a\b~', $spf)) {
  $results[] = "SPF: can send mail from IP from A-record";
} elseif(preg_match('~\b\+?mx\b~', $spf)) {
  $results[] = "SPF: can send mail from IP from MX-record";
} elseif(preg_match('~\b\+?include:\b~', $spf)) {
  $results[] = "SPF: can send mail from IP (see include)";
} elseif(preg_match('~\b\+?redirect\b~', $spf)) {
  $results[] = "SPF: can send mail from IP (see redirect)";
} else {
  $results[] = "SPF WARNING: can`t send mail from $ip";
}
echo implode("\n", $results);