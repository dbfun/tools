#!/bin/bash

# Скрипт для проверки скорости загрузки страницы сайта

URI="$1"
TOTAL_TIME=0
MIN_TIME=1000
NUM_REPEATS=20

clear
echo Страница: "$URI"
echo

let i=1
while [[ $i -le $NUM_REPEATS ]]
do
  let i=i+1
  TIME=`curl -s -w %{time_total} -o /dev/null "$URI"| sed 's/,/./g'`
  echo $TIME
  TOTAL_TIME=`echo "$TOTAL_TIME+$TIME"|bc -l`
  if [[ $(echo "$TIME<$MIN_TIME" |bc -l) == "1" ]]; then
    MIN_TIME=$TIME
  fi
done

AVG_TIME=`echo "scale=3;$TOTAL_TIME/$NUM_REPEATS" | bc -l`

echo
echo "Минимальное время:" $MIN_TIME
echo "    Среднее время:" $AVG_TIME
