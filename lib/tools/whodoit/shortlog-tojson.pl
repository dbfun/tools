#!/usr/bin/perl
use utf8;
use JSON;
@ret = ();
while (defined($line = <STDIN>)) {
  chomp($line);
  if ($line =~ m/([0-9]+)\s+(.*)/) {
    push(@ret, {count => $1, name => $2});
    }
  }
print encode_json \@ret;