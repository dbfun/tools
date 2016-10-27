#!/bin/bash

# Скрипт для сравнения скорости загрузки двух страниц
# Оконность - через tmux

FIRST="$1"
SECOND="$2"

SESSION=$USER

tmux -2 new-session -d -s $SESSION
tmux split-window -h

tmux select-pane -t 0
tmux send-keys "speedme.sh \"$FIRST\"" C-m

tmux select-pane -t 1
tmux send-keys "speedme.sh \"$SECOND\"" C-m

# Attach to session
tmux -2 attach-session -t $SESSION