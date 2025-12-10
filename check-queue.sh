#!/bin/bash
echo "=== Queue Status ==="
echo "Time: $(date)"
echo ""

cd /home/$(whoami)/ci4-app/rnd

echo "1. Failed Jobs:"
php spark queue:failed --count

echo ""
echo "2. Pending Jobs:"
php spark queue:work --stop-when-empty --once 2>/dev/null | grep -i "processing\|processed"

echo ""
echo "3. Log Files:"
ls -la /home/$(whoami)/ci4-app/rnd/logs/*.log 2>/dev/null | head -5
