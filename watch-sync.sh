#!/bin/bash
# =============================================================
#  BMS Auto Git Sync — watches for file changes and auto pushes
#  Usage: bash watch-sync.sh
#  Run this in a SEPARATE Cmder tab — leave it running
# =============================================================

PROJECT_DIR="D:/laragon/www/anakco_bms"
BRANCH="main"
CHECK_INTERVAL=5  # seconds between checks

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo ""
echo "=========================================="
echo "   BMS Auto Git Sync"
echo "   Watching: $PROJECT_DIR"
echo "   Branch:   $BRANCH"
echo "   Press Ctrl+C to stop"
echo "=========================================="
echo ""

cd "$PROJECT_DIR" || { echo -e "${RED}ERROR: Project folder not found!${NC}"; exit 1; }

# Store initial state
LAST_HASH=$(git diff HEAD | md5sum)

while true; do
    # Check for any changes (tracked + untracked)
    CURRENT_HASH=$(git diff HEAD | md5sum)
    NEW_FILES=$(git ls-files --others --exclude-standard | head -1)

    if [ "$CURRENT_HASH" != "$LAST_HASH" ] || [ -n "$NEW_FILES" ]; then

        echo -e "${YELLOW}[$(date '+%H:%M:%S')]${NC} Changes detected! Syncing..."

        # Stage all changes
        git add -A

        # Get list of changed files for commit message
        CHANGED=$(git diff --cached --name-only | head -5 | tr '\n' ', ' | sed 's/,$//')
        EXTRA=$(git diff --cached --name-only | wc -l)

        if [ "$EXTRA" -gt 5 ]; then
            COMMIT_MSG="auto: update $CHANGED and $((EXTRA - 5)) more files"
        elif [ -n "$CHANGED" ]; then
            COMMIT_MSG="auto: update $CHANGED"
        else
            COMMIT_MSG="auto: update files"
        fi

        # Commit
        git commit -m "$COMMIT_MSG" --quiet

        # Push
        if git push origin "$BRANCH" --quiet 2>/dev/null; then
            echo -e "${GREEN}[$(date '+%H:%M:%S')]${NC} ✅ Pushed: $COMMIT_MSG"
        else
            echo -e "${RED}[$(date '+%H:%M:%S')]${NC} ❌ Push failed! Check your connection."
            # Try again after a moment
            sleep 3
            git push origin "$BRANCH" 2>&1
        fi

        echo ""

        # Update hash
        LAST_HASH=$(git diff HEAD | md5sum)
    fi

    sleep $CHECK_INTERVAL
done
