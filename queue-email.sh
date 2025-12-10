#!/bin/bash

# ============================================
# Script untuk menjalankan Queue CI4 via Cron
# ============================================

# Path aplikasi
APP_PATH="/home/$(whoami)/ci4-app/rnd"

# Log file
LOG_FILE="/home/$(whoami)/ci4-app/rnd/logs/queue-cron-$(date +\%Y\%m\%d).log"

# Timestamp untuk logging
TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S')

# Pindah ke direktori aplikasi
cd "$APP_PATH"

echo "[$TIMESTAMP] Starting queue processing..." >> "$LOG_FILE"

# Jalankan queue worker dengan timeout 55 detik
# (Cron job berjalan maksimal 1 menit, jadi kita set 55 detik)
/usr/bin/php spark queue:email \
  --queue=default \
  --sleep=5 \
  --tries=3 \
  --memory=128 \
  --timeout=55 \
  --stop-when-empty >> "$LOG_FILE" 2>&1

EXIT_CODE=$?
TIMESTAMP_END=$(date '+%Y-%m-%d %H:%M:%S')

echo "[$TIMESTAMP_END] Queue processing finished with exit code: $EXIT_CODE" >> "$LOG_FILE"
