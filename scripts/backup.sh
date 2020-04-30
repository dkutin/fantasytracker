#!/bin/bash
# Creates a backup of the tmp/ directory

if [ -z "$1" ]; then
  echo "Help : To compress file use argument with directory"
  exit 0
fi

filename="backups/$1_$(date '+%F').tar.gz"

if [ -e "$filename" ]; then
  echo "WARNING: file exists: $filename" >&2
else
  tar -czvf "$filename" "$@"
fi
