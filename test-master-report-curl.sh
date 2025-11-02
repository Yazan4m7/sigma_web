#!/bin/bash

# Master Report Testing with Curl
# This script uses curl with cookies to maintain session after login

BASE_URL="http://localhost:8000"
COOKIE_FILE="cookies.txt"
TIMESTAMP=$(date +%Y%m%d-%H%M%S)
LOG_FILE="master-report-curl-results-${TIMESTAMP}.md"

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "==========================================="
echo "Master Report Curl Test Execution"
echo "==========================================="
echo

# Function to extract CSRF token
get_csrf_token() {
    curl -s "${BASE_URL}/login" -c "$COOKIE_FILE" | grep -oP 'name="_token"[^>]+value="\K[^"]+' | head -1
}

# Function to login
login() {
    echo "🔐 Logging in to SIGMA..."

    # Get CSRF token
    CSRF=$(get_csrf_token)

    if [ -z "$CSRF" ]; then
        echo "❌ Could not get CSRF token"
        return 1
    fi

    # Login
    curl -s -L "${BASE_URL}/login" \
        -b "$COOKIE_FILE" -c "$COOKIE_FILE" \
        -d "_token=${CSRF}" \
        -d "email=admin@admin.com" \
        -d "password=admin" \
        -o /dev/null

    # Test if logged in by checking dashboard
    RESPONSE=$(curl -s -L "${BASE_URL}/admindash" -b "$COOKIE_FILE")

    if echo "$RESPONSE" | grep -q "admin"; then
        echo "✅ Login successful"
        return 0
    else
        echo "❌ Login failed"
        return 1
    fi
}

# Function to extract case IDs from HTML
extract_case_ids() {
    local html="$1"
    echo "$html" | grep -oP 'data-case-id="\K[0-9]+' | sort -n | uniq
}

# Function to test a URL
test_url() {
    local test_id="$1"
    local test_name="$2"
    local url="$3"
    local expected="$4"

    echo
    echo "=================================================="
    echo "Testing: $test_id - $test_name"
    echo "URL: $url"
    echo "Expected: $expected"
    echo "=================================================="

    # Make request
    RESPONSE=$(curl -s -L "$url" -b "$COOKIE_FILE")

    # Extract case IDs
    CASE_IDS=$(extract_case_ids "$RESPONSE")
    CASE_COUNT=$(echo "$CASE_IDS" | wc -w)

    # Check for "no cases found"
    if echo "$RESPONSE" | grep -qi "no cases found"; then
        CASE_COUNT=0
        CASE_IDS="None"
        echo -e "${YELLOW}📊 Case Count: 0${NC}"
        echo "📋 Case IDs: None (no cases found)"
    elif [ -z "$CASE_IDS" ] || [ "$CASE_COUNT" -eq 0 ]; then
        CASE_COUNT=0
        CASE_IDS="None"
        echo -e "${YELLOW}⚠️ Case Count: 0 (empty or error)${NC}"
        echo "📋 Case IDs: None"
    else
        echo -e "${GREEN}📊 Case Count: $CASE_COUNT${NC}"
        echo "📋 Case IDs: $(echo $CASE_IDS | tr '\n' ',' | sed 's/,$//' | tr '\n' ' ')"
    fi

    # Write to log
    cat >> "$LOG_FILE" << EOF
## $test_id: $test_name

**URL:**
\`\`\`
$url
\`\`\`

**Expected Result:** $expected

**Actual Result:**
- Case Count: $CASE_COUNT
- Case IDs: $(echo $CASE_IDS | tr '\n' ',' | sed 's/,$//')

---

EOF
}

# Initialize log file
cat > "$LOG_FILE" << EOF
# Master Report Test Results (Curl)

**Test Date:** $(date)
**Base URL:** $BASE_URL

---

EOF

# Login first
login || exit 1

echo
echo "Starting tests..."
echo

# Test Suite 1: Basic & Date Filters
test_url "TC-01" "Default Load" \
    "${BASE_URL}/reports/master?generate_report=1" \
    "All current month cases (~14 cases: 214-224, 226-228)"

test_url "TC-02" "Specific Date Range (Old Case)" \
    "${BASE_URL}/reports/master?generate_report=1&from=2025-09-28&to=2025-09-30" \
    "Case 225"

# Test Suite 2: Doctor/Client Filters
test_url "TC-03" "Single Doctor (Client 2)" \
    "${BASE_URL}/reports/master?generate_report=1&doctor%5B%5D=2" \
    "Cases 214, 217, 221, 226 (4 cases)"

test_url "TC-04" "Multiple Doctors (2, 3)" \
    "${BASE_URL}/reports/master?generate_report=1&doctor%5B%5D=2&doctor%5B%5D=3" \
    "Cases 214, 215, 217, 218, 221, 223, 226, 228 (8 cases)"

# Test Suite 3: Workflow Stage Filters
test_url "TC-05a" "Workflow Stage - Finishing" \
    "${BASE_URL}/reports/master?generate_report=1&status%5B%5D=6" \
    "Case 227 (1 case)"

test_url "TC-05b" "Workflow Stage - Design" \
    "${BASE_URL}/reports/master?generate_report=1&status%5B%5D=1" \
    "Cases 222, 226 (2 cases)"

test_url "TC-05c" "Workflow Stage - 3D Printing" \
    "${BASE_URL}/reports/master?generate_report=1&status%5B%5D=3" \
    "Cases 215, 222, 224 (3 cases)"

# Test Suite 4: Amount Range Filters
test_url "TC-08" "Amount Range - From Only (>=100)" \
    "${BASE_URL}/reports/master?generate_report=1&amount_from=100" \
    "All except 219 (14 cases)"

test_url "TC-09" "Amount Range - To Only (<=500)" \
    "${BASE_URL}/reports/master?generate_report=1&amount_to=500" \
    "All except 220 (14 cases)"

test_url "TC-10" "Amount Range - Between (100-500)" \
    "${BASE_URL}/reports/master?generate_report=1&amount_from=100&amount_to=500" \
    "Cases 214, 215, 216, 217, 218, 221, 223, 224, 225, 227, 228 (11 cases)"

test_url "TC-10b" "Low Amount Range (1-100)" \
    "${BASE_URL}/reports/master?generate_report=1&amount_from=1&amount_to=100" \
    "Cases 217, 219 (2 cases)"

# Test Suite 5: Units Range
test_url "TC-12" "Units Range (2-4)" \
    "${BASE_URL}/reports/master?generate_report=1&units_from=2&units_to=4" \
    "Cases 215, 222, 224 (3 cases)"

test_url "TC-12b" "Many Units (6+)" \
    "${BASE_URL}/reports/master?generate_report=1&units_from=6&units_to=10" \
    "Case 220 (1 case)"

# Test Suite 6: Completion Status
test_url "TC-13" "Completion Status - Completed" \
    "${BASE_URL}/reports/master?generate_report=1&show_completed=completed" \
    "Cases 214, 216, 217, 219, 220, 221, 225 (7 cases)"

test_url "TC-14" "Completion Status - In Progress" \
    "${BASE_URL}/reports/master?generate_report=1&show_completed=in_progress" \
    "Cases 215, 218, 222, 223, 224, 226, 227, 228 (8 cases)"

# Test Suite 7: Job Type Filters
test_url "EXTRA-01" "Job Type - Crowns Only" \
    "${BASE_URL}/reports/master?generate_report=1&job_type%5B%5D=1" \
    "10 cases"

test_url "EXTRA-02" "Job Type - Bridges Only" \
    "${BASE_URL}/reports/master?generate_report=1&job_type%5B%5D=2" \
    "Cases 215, 220, 224 (3 cases)"

test_url "EXTRA-03" "Job Type - Implants Only" \
    "${BASE_URL}/reports/master?generate_report=1&job_type%5B%5D=6" \
    "Case 216 (1 case)"

# Test Suite 8: Edge Cases
test_url "TC-19" "No Results Found" \
    "${BASE_URL}/reports/master?generate_report=1&doctor%5B%5D=99999" \
    "No cases found"

test_url "TC-21" "Complex Combination" \
    "${BASE_URL}/reports/master?generate_report=1&from=2025-10-01&to=2025-10-29&doctor%5B%5D=all&material%5B%5D=all&job_type%5B%5D=all&status%5B%5D=all&amount_from=1&amount_to=200&show_completed=all" \
    "Cases 214, 216, 217, 218, 219, 221, 223, 225, 227, 228 (10 cases)"

# Summary
echo
echo "=================================================="
echo "Testing Complete!"
echo "=================================================="
echo "Results saved to: $LOG_FILE"
echo

# Cleanup
rm -f "$COOKIE_FILE"
