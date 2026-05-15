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
LAST_WT_HASH=""   # ← add this line
LAST_LOG_SIZE=0
[ -f "$LOG_FILE" ] && LAST_LOG_SIZE=$(wc -c < "$LOG_FILE")

# -------------------------------------------------------
# HELPERS
# -------------------------------------------------------
get_module() {
    case "$1" in
        *Resident*|*resident*)     echo "Residents" ;;
        *Household*|*household*)   echo "Households" ;;
        *Document*|*document*)     echo "Documents" ;;
        *Blotter*|*blotter*)       echo "Blotter" ;;
        *Business*|*business*)     echo "Businesses" ;;
        *Official*|*official*)     echo "Officials" ;;
        *Committee*|*committee*)   echo "Committees" ;;
        *Report*|*report*)         echo "Reports" ;;
        *User*|*user*)             echo "Users" ;;
        *Dashboard*|*dashboard*)   echo "Dashboard" ;;
        *Purok*|*purok*)           echo "Puroks" ;;
        *Activity*|*activity*)     echo "Activity Log" ;;
        *[Mm]igration*|*[Ss]eeder*|*[Ff]actory*) echo "Database" ;;
        *[Mm]odel*)                echo "Models" ;;
        *layout*|*sidebar*|*topbar*|*app.blade*) echo "Layout" ;;
        *routes*|*web.php*)        echo "Routes" ;;
        *verify*)                  echo "Verification" ;;
        *)                         echo "General" ;;
    esac
}

get_module_slug() {
    case "$1" in
        Residents)    echo "residents" ;;
        Households)   echo "households" ;;
        Documents)    echo "documents" ;;
        Blotter)      echo "blotter" ;;
        Businesses)   echo "businesses" ;;
        Officials)    echo "officials" ;;
        Committees)   echo "committees" ;;
        Reports)      echo "reports" ;;
        Users)        echo "users" ;;
        Dashboard)    echo "dashboard" ;;
        Puroks)       echo "puroks" ;;
        *)            echo "" ;;
    esac
}

get_layer() {
    case "$1" in
        *Controller*)              echo "backend" ;;
        *.blade.php*)              echo "frontend" ;;
        *[Mm]igration*|*[Ss]eeder*|*[Ff]actory*|*[Mm]odel*) echo "database" ;;
        *routes*|*web.php*|*Middleware*) echo "backend" ;;
        *.css*|*.js*)              echo "frontend" ;;
        *Trait*)                   echo "backend" ;;
        *)                         echo "backend" ;;
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

# -------------------------------------------------------
# SMART TITLE GENERATOR
# Analyzes changed files and produces a specific title
# -------------------------------------------------------

get_file_basename() {
    echo "$1" | grep -oE '[A-Za-z0-9_-]+\.(php|js|css|sh|json)' | tail -1
}

get_file_role() {
    local f="$1"
    if echo "$f" | grep -qi "Controller"; then
        local basename=$(get_file_basename "$f")
        echo "updated $basename"
    elif echo "$f" | grep -qi "blade"; then
        local basename=$(get_file_basename "$f")
        if echo "$f" | grep -qi "\-index"; then
            echo "updated $basename (index/list page)"
        elif echo "$f" | grep -qi "\-create"; then
            echo "updated $basename (create form)"
        elif echo "$f" | grep -qi "\-edit"; then
            echo "updated $basename (edit form)"
        elif echo "$f" | grep -qi "\-show"; then
            echo "updated $basename (detail page)"
        else
            echo "updated $basename"
        fi
    elif echo "$f" | grep -qi "[0-9]_create\|[0-9]_add\|[0-9]_update\|migration"; then
        local basename=$(get_file_basename "$f")
        echo "migration: $basename"
    elif echo "$f" | grep -qi "Model\|/Models/"; then
        local basename=$(get_file_basename "$f")
        echo "updated model $basename"
    elif echo "$f" | grep -qi "Seeder"; then
        local basename=$(get_file_basename "$f")
        echo "updated seeder $basename"
    elif echo "$f" | grep -qi "Factory"; then
        local basename=$(get_file_basename "$f")
        echo "updated factory $basename"
    elif echo "$f" | grep -qi "routes\|web\.php"; then
        echo "updated web.php (route definitions)"
    elif echo "$f" | grep -qi "Trait\|Middleware"; then
        local basename=$(get_file_basename "$f")
        echo "updated $basename"
    elif echo "$f" | grep -qi "\.sh$"; then
        local basename=$(get_file_basename "$f")
        echo "updated script $basename"
    else
        local basename=$(get_file_basename "$f")
        echo "updated $basename"
    fi
}

generate_smart_title() {
    local changed_files="$1"
    local manual_msg="$2"

    if [ -n "$manual_msg" ]; then
        echo "$manual_msg"
        return
    fi

    local file_count=$(echo "$changed_files" | grep -c .)
    local first_file=$(echo "$changed_files" | head -1)
    local second_file=$(echo "$changed_files" | sed -n "2p")

    local module=$(get_module "$first_file")
    local file_role=$(get_file_role "$first_file")

    # Single file — most specific
    if [ "$file_count" -eq 1 ]; then
        echo "$module — $file_role"
        return
    fi

    # Two files
    if [ "$file_count" -eq 2 ]; then
        local role2=$(get_file_role "$second_file")
        local mod2=$(get_module "$second_file")
        if [ "$module" = "$mod2" ]; then
            echo "$module — $file_role + $role2"
        else
            echo "$module & $mod2 — $file_role + $role2"
        fi
        return
    fi

    # 3+ files — summarize by module and type
    local modules=()
    local has_controller=0; local has_view=0; local has_migration=0
    local has_model=0; local has_routes=0; local has_trait=0

    while IFS= read -r f; do
        [ -z "$f" ] && continue
        local mod=$(get_module "$f")
        local already=0
        for m in "${modules[@]}"; do [ "$m" = "$mod" ] && already=1; done
        [ $already -eq 0 ] && modules+=("$mod")
        echo "$f" | grep -qi "Controller"  && has_controller=1
        echo "$f" | grep -qi "blade"       && has_view=1
        echo "$f" | grep -qi "migration"   && has_migration=1
        echo "$f" | grep -qi "Model"     && has_model=1
        echo "$f" | grep -qi "routes\|web\.php" && has_routes=1
        echo "$f" | grep -qi "Trait\|Middleware" && has_trait=1
    done <<< "$changed_files"

    local mod_str=""
    if [ ${#modules[@]} -eq 1 ]; then
        mod_str="${modules[0]}"
    elif [ ${#modules[@]} -eq 2 ]; then
        mod_str="${modules[0]} & ${modules[1]}"
    else
        mod_str="${modules[0]}, ${modules[1]} +${#modules[@]} modules"
    fi

    local change_desc=""
    if [ $has_migration -eq 1 ] && [ $has_controller -eq 1 ] && [ $has_view -eq 1 ]; then
        change_desc="full-stack update ($file_count files)"
    elif [ $has_migration -eq 1 ] && [ $has_model -eq 1 ]; then
        change_desc="schema & model update"
    elif [ $has_controller -eq 1 ] && [ $has_view -eq 1 ]; then
        change_desc="controller & view update ($file_count files)"
    elif [ $has_controller -eq 1 ]; then
        change_desc="controller updates ($file_count files)"
    elif [ $has_view -eq 1 ]; then
        change_desc="view updates ($file_count files)"
    elif [ $has_migration -eq 1 ]; then
        change_desc="database migrations ($file_count files)"
    elif [ $has_routes -eq 1 ]; then
        change_desc="route & config update"
    elif [ $has_trait -eq 1 ]; then
        change_desc="trait/middleware update"
    else
        change_desc="update ($file_count files)"
    fi

    echo "$mod_str — $change_desc"
}

# -------------------------------------------------------
# FUNCTION 1 — Auto-create changelog issue
# -------------------------------------------------------
create_changelog_issue() {
    local changed_files="$1"
    local commit_msg="$2"
    local timestamp=$(date '+%B %d, %Y at %H:%M')

    local first_file=$(echo "$changed_files" | head -1)
    local module=$(get_module "$first_file")
    local module_slug=$(get_module_slug "$module")
    local layer=$(get_layer "$first_file")
    local milestone=$(get_milestone "$layer")
    local file_count=$(echo "$changed_files" | grep -c .)

    # Smart title
    local smart_title=$(generate_smart_title "$changed_files" "")
    local prefix=""
    [ "$layer" = "backend" ]  && prefix="BACKEND"
    [ "$layer" = "frontend" ] && prefix="FRONTEND"
    [ "$layer" = "database" ] && prefix="DATABASE"

    # Build file table
    local file_table="| File | Layer | Notes |\n|---|---|---|\n"
    while IFS= read -r f; do
        [ -z "$f" ] && continue
        local flay=$(get_layer "$f")
        local fnote=""
        echo "$f" | grep -qi "Controller" && fnote="Business logic / CRUD"
        echo "$f" | grep -qi "blade"      && fnote="UI template"
        echo "$f" | grep -qi "migration"  && fnote="Schema change"
        echo "$f" | grep -qi "Model"      && fnote="Eloquent model"
        echo "$f" | grep -qi "routes"     && fnote="Route definitions"
        echo "$f" | grep -qi "Seeder"     && fnote="Seed data"
        echo "$f" | grep -qi "Trait"      && fnote="Shared trait"
        [ -z "$fnote" ] && fnote="—"
        file_table="$file_table| \`$f\` | $flay | $fnote |\n"
    done <<< "$changed_files"

    # Labels
    local labels="type: enhancement,layer: $layer,status: in-progress"
    [ -n "$module_slug" ] && labels="$labels,module: $module_slug"

    local title="[$prefix] $smart_title"

    local body="## 📋 Change Log

**Date:** $timestamp
**Module:** $module
**Layer:** $layer
**Files Changed:** $file_count

## 📁 Changed Files

$(echo -e "$file_table")

## 💬 Commit Message
\`\`\`
$commit_msg
\`\`\`

## ✅ Acceptance Criteria
- [ ] Changes tested locally at http://anakco_bms.test
- [ ] No new errors in \`storage/logs/laravel.log\`
- [ ] UI renders correctly if frontend changes
- [ ] Database migrations run cleanly if schema changes

## 🔗 Related
Check existing issues: \`module: $module_slug\`

---
*Auto-created by BMS watch-sync when files were changed.*"

    local issue_url
    if [ -n "$module_slug" ]; then
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

    [ -n "$issue_url" ] && echo -e "${CYAN}[$(date '+%H:%M:%S')]${NC} 📌 Issue created → $issue_url"
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
            gh issue edit "$issue_num" \
                --repo "$REPO" \
                --remove-label "status: in-progress,status: needs-review,status: blocked" \
                --add-label "status: done" 2>/dev/null

            gh issue close "$issue_num" \
                --repo "$REPO" \
                --comment "✅ **Automatically closed** by commit on \`$BRANCH\`:

> $commit_msg

Issue marked as **status: done**." 2>/dev/null && \
            echo -e "${GREEN}[$(date '+%H:%M:%S')]${NC} ✅ Auto-closed issue #$issue_num"
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
    local module_slug=$(get_module_slug "$module")
    local timestamp=$(date '+%B %d, %Y at %H:%M')

    local error_type="Runtime Error"
    echo "$error_line" | grep -qi "SQLSTATE"      && error_type="SQL Error"
    echo "$error_line" | grep -qi "ErrorException" && error_type="PHP Exception"
    echo "$error_line" | grep -qi "CRITICAL"       && error_type="Critical Error"
    echo "$error_line" | grep -qi "ViewException"  && error_type="Blade View Error"
    echo "$error_line" | grep -qi "ModelNotFound"  && error_type="Model Not Found"

    local error_location=$(echo "$new_content" | grep -oE '[A-Za-z0-9/_-]+\.php:[0-9]+' | head -1)
    local error_basename=$(echo "$error_location" | grep -oE '[A-Za-z0-9_-]+\.php:[0-9]+' | head -1)
    [ -z "$error_basename" ] && error_basename="unknown location"

    local error_msg=$(echo "$error_line" | sed "s/\[.*\] //g" | sed "s/production\.ERROR://g" | sed "s/local\.ERROR://g" | sed "s/^ *//g" | cut -c1-70)

    local title="[BUG] $error_type at $error_basename — $error_msg"

    local labels="type: bug,priority: high,status: needs-review"
    [ -n "$module_slug" ] && labels="$labels,module: $module_slug"

    local body="## 🐛 Bug Report

**Date:** $timestamp
**Module:** $module
**Error Type:** $error_type
**File:** \`$error_basename\`
**Detected by:** Auto watch-sync (Laravel log monitor)

## ❌ Error Message
\`\`\`
$error_msg
\`\`\`

## 📍 Exact Location
\`\`\`
$error_location
\`\`\`

## 📄 Log Excerpt
\`\`\`
$(echo "$new_content" | head -25)
\`\`\`

## 🔍 Root Cause
<!-- Fill this in after investigating -->

## ✅ Fix Applied
<!-- Describe the fix here -->

## 📋 Steps to Reproduce
1. Go to the **$module** module
2. Check \`storage/logs/laravel.log\` for the full stack trace
3. Reproduce the action that caused the error

---
**To close:** commit your fix with \`fixes #ISSUE_NUMBER\` — this auto-closes the issue.

---
*Auto-created by BMS watch-sync when a Laravel error was detected.*"

    local issue_url
    if [ -n "$module_slug" ]; then
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
echo -e "${BLUE}💡 TIP — Custom commit message:${NC}"
echo -e "   ${YELLOW}BMS_COMMIT='fixes #12 added export feature' bash watch-sync.sh${NC}"
echo ""

LAST_WT_HASH=""

while true; do

    check_laravel_log

    # ── Sync worktree → main ──────────────────────────────────
    WORKTREE="$PROJECT_DIR/.claude/worktrees/festive-lovelace-684745"
    if [ -d "$WORKTREE" ]; then
        cd "$WORKTREE"
        WT_CURRENT=$(git diff HEAD | md5sum)
        WT_NEW=$(git ls-files --others --exclude-standard | head -1)
        if [ "$WT_CURRENT" != "$LAST_WT_HASH" ] || [ -n "$WT_NEW" ]; then
            git add -A
            WT_FILES=$(git diff --cached --name-only)
            if [ -n "$WT_FILES" ]; then
                WT_MSG="auto: $(generate_smart_title "$WT_FILES" "")"
                git commit -m "$WT_MSG" --quiet
                echo -e "${CYAN}[$(date '+%H:%M:%S')]${NC} 🔀 Worktree committed: $WT_MSG"
            fi
            LAST_WT_HASH=$(git diff HEAD | md5sum)
        fi
        cd "$PROJECT_DIR"
        WT_AHEAD=$(git log main..claude/festive-lovelace-684745 --oneline 2>/dev/null | wc -l)
        if [ "$WT_AHEAD" -gt 0 ]; then
            git stash --quiet 2>/dev/null
            git merge claude/festive-lovelace-684745 --no-edit --quiet 2>/dev/null
            git stash pop --quiet 2>/dev/null || true
            echo -e "${GREEN}[$(date '+%H:%M:%S')]${NC} ✅ Worktree merged into main"
        fi
    fi
    # ─────────────────────────────────────────────────────────

    CURRENT_HASH=$(git diff HEAD | md5sum)
    NEW_FILES=$(git ls-files --others --exclude-standard | head -1)

    if [ "$CURRENT_HASH" != "$LAST_HASH" ] || [ -n "$NEW_FILES" ]; then

        echo -e "${YELLOW}[$(date '+%H:%M:%S')]${NC} Changes detected! Syncing..."

        git add -A
        CHANGED_FILES=$(git diff --cached --name-only)

        if [ -n "$BMS_COMMIT" ]; then
            COMMIT_MSG="$BMS_COMMIT"
            unset BMS_COMMIT
        else
            SMART=$(generate_smart_title "$CHANGED_FILES" "")
            COMMIT_MSG="auto: $SMART"
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