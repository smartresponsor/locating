#!/usr/bin/env bash
<<<<<<< HEAD
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
=======
>>>>>>> 94ad96f (first/init commit)
set -euo pipefail

clear
echo "Logs Menu"
echo "---------"
echo "1) Symfony server logs"
echo "2) Docker logs"
echo "Space) Exit"

read -r -n 1 -s -p "Choice: " action
echo

case $action in
  1) exec symfony server:log ;;
  2) exec docker-compose logs -f ;;
  *) echo "Bye"; return 1 ;;
esac
