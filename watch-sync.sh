#!/bin/bash
# =============================================================
#  BMS Auto Git Sync + GitHub Issues
#  Usage: bash watch-sync.sh
#  Run in a SEPARATE Cmder tab — leave it running
# =============================================================

PROJECT_DIR="D:/laragon/www/anakco_bms"
REPO="dvphnc/anakco-bms-newera"
BRANCH="main"
CHECK_INTERVAL=5
LOG_FILE="$PROJECT_DIR/storage/logs/laravel.log"

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
CYAN='\033[0;36m'
BLUE='\033[0;34m'
NC='\033[0m'

echo ""
echo "=================================================="
echo "   BMS Auto Git Sync + GitHub Issues"
echo "   Watching: $PROJECT_DIR"
echo "   Repo:     $REPO"
echo "   Branch:   $BRANCH"
echo "   Press Ctrl+C to stop"
echo "=================================================="
echo ""

cd "$PROJECT_DIR" || { echo -e "${RED}ERROR: Project folder not found!${NC}"; exit 1; }

LAST_HASH=$(git diff HEAD | md5sum)
LAST_LOG_SIZE=0
[ -f "$LOG_FILE" ] && LAST_LOG_SIZE=$(wc -c < "$LOG_FILE")

# -------------------------------------------------------
# HELPERS — Detect module, layer, prefix, milestone
# -------------------------------------------------------
get_module() {
    case "$1" in
        *Resident*|*resident*)     echo "residents" ;;
        *Household*|*household*)   echo "households" ;;
        *Document*|*document*)     echo "documents" ;;
        *Blotter*|*blotter*)       echo "blotter" ;;
        *Business*|*business*)     echo "businesses" ;;
        *Official*|*official*)     echo "officials" ;;
        *Committee*|*committee*)   echo "committees" ;;
        *Report*|*report*)         echo "reports" ;;
        *User*|*user*)             echo "users" ;;
        *Dashboard*|*dashboard*)   echo "dashboard" ;;
        *[Mm]igration*|*[Ss]eeder*|*[Ff]actory*|*[Mm]odel*) echo "database" ;;
        *layout*|*sidebar*|*topbar*|*app.blade*) echo "layout" ;;
        *routes*|*web.php*)        echo "routes" ;;
        *)                         echo "general" ;;
    esac
}

get_layer() {
    case "$1" in
        *Controller*)              echo "backend" ;;
        *.blade.php*)              echo "frontend" ;;
        *[Mm]igration*|*[Ss]eeder*|*[Ff]actory*|*[Mm]odel*) echo "database" ;;
        *routes*|*web.php*)        echo "backend" ;;
        *.css*|*.js*)              echo "frontend" ;;
        *)                         echo "backend" ;;
    esac
}

get_prefix() {
    case "$1" in
        backend)  echo "BACKEND" ;;
        frontend) echo "FRONTEND" ;;
        database) echo "DATABASE" ;;
        *)        echo "UPDATE" ;;
    esac
}

get_layer_label() {
    case "$1" in
        backend)  echo "layer: backend" ;;
        frontend) echo "layer: frontend" ;;
        database) echo "layer: database" ;;
        *)        echo "type: enhancement" ;;
    esac
}

get_module_label() {
    case "$1" in
        residents|households|documents|blotter|businesses|officials|committees|reports|users|dashboard)
            echo "module: $1" ;;
        *) echo "" ;;
    esac
}

get_milestone() {
    case "$1" in
        database) echo "Sprint 1: Foundation & Setup" ;;
        routes)   echo "Sprint 1: Foundation & Setup" ;;
        backend)  echo "Sprint 2: Core Backend" ;;
        frontend) echo "Sprint 3: UI & Frontend" ;;
        *)        echo "Sprint 4: Bug Fixes & Polish" ;;
    esac
}

get_file_description() {
    case "$1" in
        *Controller*)   echo "Controller updated — CRUD methods, queries, or business logic changed" ;;
        *.blade.php*)   echo "Blade view updated — UI, layout, or template changes" ;;
        *[Mm]igration*) echo "Migration updated — database schema changes" ;;
        *[Mm]odel*)     echo "Model updated — relationships, scopes, or fillable fields changed" ;;
        *[Ss]eeder*)    echo "Seeder updated — database seed data changed" ;;
        *[Ff]actory*)   echo "Factory updated — fake data generation changed" ;;
        *routes*)       echo "Routes updated — URL definitions or middleware changed" ;;
        *.css*)         echo "Stylesheet updated — design or layout styles changed" ;;
        *.js*)          echo "JavaScript updated — frontend behavior changed" ;;
        *)              echo "File updated" ;;
    esac
}

# -------------------------------------------------------
# FUNCTION 1 — Auto-create changelog issue (like originals)
# -------------------------------------------------------
create_changelog_issue() {
    local changed_files="$1"
    local commit_msg="$2"
    local timestamp=$(date '+%B %d, %Y at %H:%M')

    local first_file=$(echo "$changed_files" | head -1)
    local module=$(get_module "$first_file")
    local layer=$(get_layer "$first_file")
    local prefix=$(get_prefix "$layer")
    local layer_label=$(get_layer_label "$layer")
    local module_label=$(get_module_label "$module")
    local milestone=$(get_milestone "$layer")
    local file_count=$(echo "$changed_files" | grep -c .)

    # Build file details table
    local file_table="| File | Type | Description |\n|---|---|---|\n"
    while IFS= read -r f; do
        [ -z "$f" ] && continue
        local ftype=$(get_layer "$f")
        local fdesc=$(get_file_description "$f")
        file_table="$file_table| \`$f\` | $ftype | $fdesc |\n"
    done <<< "$changed_files"

    # Build labels string
    local labels="type: enhancement,$layer_label,status: in-progress"
    [ -n "$module_label" ] && labels="$labels,$module_label"

    local title="[$prefix] $commit_msg"

    local body="## 📝 Change Log

**Date:** $timestamp
**Branch:** \`$BRANCH\`
**Module:** $module
**Layer:** $layer
**Files Changed:** $file_count

## 📁 Changed Files

$(echo -e "$file_table")

## 🔍 Commit Message
\`\`\`
$commit_msg
\`\`\`

## ✅ Acceptance Criteria
- [ ] Changes tested locally at http://localhost
- [ ] No new errors in \`storage/logs/laravel.log\`
- [ ] UI renders correctly if frontend changes
- [ ] Database migrations run cleanly if schema changes

## 🔗 Related
Check the existing issues for this module: \`module: $module\`

---
*This issue was automatically created by the BMS watch-sync script when files were changed.*"

    local issue_url
    if [ -n "$module_label" ]; then
        issue_url=$(gh issue create \
            --repo "$REPO" \
            --title "$title" \
            --label "$labels" \
            --milestone "$milestone" \
            --body "$body" 2>/dev/null)
    else
        issue_url=$(gh issue create \
            --repo "$REPO" \
            --title "$title" \
            --label "$labels" \
            --body "$body" 2>/dev/null)
    fi

    [ -n "$issue_url" ] && echo -e "${CYAN}[$(date '+%H:%M:%S')]${NC} 📋 Issue created → $issue_url"
}

# -------------------------------------------------------
# FUNCTION 2 — Auto-close issues from commit message
# -------------------------------------------------------
check_and_close_issues() {
    local commit_msg="$1"
    local issue_numbers=$(echo "$commit_msg" | grep -oiE '(fixes|closes|resolves|fix|close|resolve) #[0-9]+' | grep -oE '[0-9]+')

    if [ -n "$issue_numbers" ]; then
        while IFS= read -r issue_num; do
            [ -z "$issue_num" ] && continue
            # Also update the issue label to status: done
            gh issue edit "$issue_num" \
                --repo "$REPO" \
                --remove-label "status: in-progress,status: needs-review,status: blocked" \
                --add-label "status: done" 2>/dev/null

            gh issue close "$issue_num" \
                --repo "$REPO" \
                --comment "✅ **Automatically closed** by commit on \`$BRANCH\`:

> $commit_msg

Issue has been marked as **status: done**." 2>/dev/null && \
            echo -e "${GREEN}[$(date '+%H:%M:%S')]${NC} ✅ Auto-closed issue #$issue_num → status: done"
        done <<< "$issue_numbers"
    fi
}

# -------------------------------------------------------
# FUNCTION 3 — Auto-create bug issue from Laravel log
# -------------------------------------------------------
check_laravel_log() {
    [ ! -f "$LOG_FILE" ] && return

    local current_size=$(wc -c < "$LOG_FILE")
    [ "$current_size" -le "$LAST_LOG_SIZE" ] && return

    local new_content=$(tail -c $((current_size - LAST_LOG_SIZE)) "$LOG_FILE" 2>/dev/null)
    LAST_LOG_SIZE=$current_size

    if ! echo "$new_content" | grep -qiE '\[ERROR\]|\[CRITICAL\]|ErrorException|SQLSTATE'; then
        return
    fi

    local error_line=$(echo "$new_content" | grep -iE '\[ERROR\]|\[CRITICAL\]|ErrorException|SQLSTATE' | head -1)
    local error_short=$(echo "$error_line" | cut -c1-150)
    local error_file=$(echo "$new_content" | grep -oE 'at .*\.php:[0-9]+' | head -1)
    local module=$(get_module "$error_file")
    local timestamp=$(date '+%B %d, %Y at %H:%M')
    local module_label=$(get_module_label "$module")
    local labels="type: bug,priority: high,status: needs-review"
    [ -n "$module_label" ] && labels="$labels,$module_label"

    # Detect error type for title
    local error_type="Runtime Error"
    echo "$error_line" | grep -qi "SQLSTATE"     && error_type="SQL Error"
    echo "$error_line" | grep -qi "ErrorException" && error_type="PHP Exception"
    echo "$error_line" | grep -qi "CRITICAL"      && error_type="Critical Error"

    local title="[BUG] $error_type detected in $module — $(date '+%b %d %H:%M')"

    local body="## 🐛 Bug Report

**Date:** $timestamp
**Module:** $module
**Error Type:** $error_type
**Detected by:** Auto watch-sync (Laravel log monitor)

## ❌ Error Message
\`\`\`
$error_short
\`\`\`

## 📍 Location
\`$error_file\`

## 📋 Log Excerpt
\`\`\`
$(echo "$new_content" | head -30)
\`\`\`

## 🔍 Root Cause
<!-- Fill this in after investigating -->

## ✅ Fix Applied
<!-- Describe the fix here -->

## 🔧 Steps to Reproduce
1. Go to the $module module
2. Check \`storage/logs/laravel.log\` for the full stack trace
3. Reproduce the action that caused the error

## 📁 File to Check
\`$error_file\`

---
**To close this issue**, commit your fix with:
\`\`\`
fixes #ISSUE_NUMBER description of fix
\`\`\`
This will automatically close the issue and mark it as **status: done**.

---
*Auto-created by BMS watch-sync when a Laravel error was detected in the log.*"

    local issue_url
    if [ -n "$module_label" ]; then
        issue_url=$(gh issue create \
            --repo "$REPO" \
            --title "$title" \
            --label "$labels" \
            --milestone "Sprint 4: Bug Fixes & Polish" \
            --body "$body" 2>/dev/null)
    else
        issue_url=$(gh issue create \
            --repo "$REPO" \
            --title "$title" \
            --label "$labels" \
            --body "$body" 2>/dev/null)
    fi

    [ -n "$issue_url" ] && echo -e "${RED}[$(date '+%H:%M:%S')]${NC} 🐛 Bug issue created → $issue_url"
}

# -------------------------------------------------------
# MAIN LOOP
# -------------------------------------------------------
echo -e "${GREEN}✅ Watching for changes...${NC}"
echo ""
echo -e "${BLUE}💡 TIP — To auto-close an issue when fixing:${NC}"
echo -e "   Add 'fixes #12' anywhere in your commit message"
echo -e "   Example: ${YELLOW}BMS_COMMIT='fixes #12 fixed residents search' bash watch-sync.sh${NC}"
echo ""

while true; do

    check_laravel_log

    CURRENT_HASH=$(git diff HEAD | md5sum)
    NEW_FILES=$(git ls-files --others --exclude-standard | head -1)

    if [ "$CURRENT_HASH" != "$LAST_HASH" ] || [ -n "$NEW_FILES" ]; then

        echo -e "${YELLOW}[$(date '+%H:%M:%S')]${NC} Changes detected! Syncing..."

        git add -A

        CHANGED_FILES=$(git diff --cached --name-only)
        CHANGED_SHORT=$(echo "$CHANGED_FILES" | head -5 | tr '\n' ', ' | sed 's/,$//')
        EXTRA=$(echo "$CHANGED_FILES" | grep -c .)

        if [ -n "$BMS_COMMIT" ]; then
            COMMIT_MSG="$BMS_COMMIT"
            unset BMS_COMMIT
        elif [ "$EXTRA" -gt 5 ]; then
            COMMIT_MSG="auto: update $CHANGED_SHORT and $((EXTRA - 5)) more"
        else
            COMMIT_MSG="auto: update $CHANGED_SHORT"
        fi

        git commit -m "$COMMIT_MSG" --quiet

        if git push origin "$BRANCH" --quiet 2>/dev/null; then
            echo -e "${GREEN}[$(date '+%H:%M:%S')]${NC} ✅ Pushed: $COMMIT_MSG"

            check_and_close_issues "$COMMIT_MSG"
            create_changelog_issue "$CHANGED_FILES" "$COMMIT_MSG"

        else
            echo -e "${RED}[$(date '+%H:%M:%S')]${NC} ❌ Push failed! Retrying..."
            sleep 3
            git push origin "$BRANCH" 2>&1
        fi

        echo ""
        LAST_HASH=$(git diff HEAD | md5sum)
    fi

    sleep $CHECK_INTERVAL
done
