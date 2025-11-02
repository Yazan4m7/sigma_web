#!/bin/bash

# Master Report Testing Script
# Tests all 21 scenarios and logs results

BASE_URL="http://localhost:8000"
REPORT_URL="${BASE_URL}/reports/master"
LOG_FILE="master-report-test-results-$(date +%Y%m%d-%H%M%S).md"

echo "# Master Report Test Results" > "$LOG_FILE"
echo "**Test Date:** $(date)" >> "$LOG_FILE"
echo "**Base URL:** $BASE_URL" >> "$LOG_FILE"
echo "" >> "$LOG_FILE"
echo "---" >> "$LOG_FILE"
echo "" >> "$LOG_FILE"

# Function to test a URL and log results
test_url() {
    local test_id="$1"
    local test_name="$2"
    local url="$3"
    local expected="$4"

    echo ""
    echo "=========================================="
    echo "Testing: $test_id - $test_name"
    echo "URL: $url"
    echo "Expected: $expected"
    echo "=========================================="

    # Log to file
    echo "## $test_id: $test_name" >> "$LOG_FILE"
    echo "" >> "$LOG_FILE"
    echo "**URL:**" >> "$LOG_FILE"
    echo '```' >> "$LOG_FILE"
    echo "$url" >> "$LOG_FILE"
    echo '```' >> "$LOG_FILE"
    echo "" >> "$LOG_FILE"
    echo "**Expected Result:** $expected" >> "$LOG_FILE"
    echo "" >> "$LOG_FILE"

    # Make request and capture response
    echo "Making request..."
    RESPONSE=$(curl -s "$url")

    # Extract case IDs from table
    CASE_IDS=$(echo "$RESPONSE" | grep -oP 'data-case-id="\K[0-9]+' | sort -n | uniq | tr '\n' ',' | sed 's/,$//')

    # Count cases
    CASE_COUNT=$(echo "$CASE_IDS" | grep -o "," | wc -l)
    if [ -n "$CASE_IDS" ]; then
        CASE_COUNT=$((CASE_COUNT + 1))
    else
        CASE_COUNT=0
    fi

    # Check for "No cases found" message
    if echo "$RESPONSE" | grep -q "No cases found matching"; then
        RESULT="No cases found"
        CASE_IDS="None"
        CASE_COUNT=0
    elif [ -z "$CASE_IDS" ]; then
        RESULT="No results (empty table or error)"
        CASE_IDS="None"
    else
        RESULT="Success"
    fi

    # Log results
    echo "**Actual Result:**" >> "$LOG_FILE"
    echo "- Status: $RESULT" >> "$LOG_FILE"
    echo "- Case Count: $CASE_COUNT" >> "$LOG_FILE"
    echo "- Case IDs: $CASE_IDS" >> "$LOG_FILE"
    echo "" >> "$LOG_FILE"

    # Check for errors in response
    if echo "$RESPONSE" | grep -q "error\|Error\|Exception"; then
        ERROR_MSG=$(echo "$RESPONSE" | grep -oP '<title>\K[^<]+' | head -1)
        echo "- **ERROR DETECTED:** $ERROR_MSG" >> "$LOG_FILE"
        echo "⚠️ ERROR: $ERROR_MSG"
    fi

    echo "" >> "$LOG_FILE"
    echo "---" >> "$LOG_FILE"
    echo "" >> "$LOG_FILE"

    # Console output
    echo "Result: $RESULT"
    echo "Cases Found: $CASE_COUNT"
    echo "Case IDs: $CASE_IDS"
    echo ""

    sleep 1  # Small delay between requests
}

# Start testing
echo "Starting Master Report Tests..."
echo "Results will be saved to: $LOG_FILE"
echo ""

# Test Suite 1: Basic & Date Filters

test_url "TC-01" "Default Load" \
    "${REPORT_URL}?generate_report=1" \
    "All current month cases (14 cases: 199-209, 211-213)"

test_url "TC-02" "Specific Date Range (Old Case)" \
    "${REPORT_URL}?generate_report=1&from=2025-09-28&to=2025-09-30" \
    "Case 210 (30 days old)"

# Test Suite 2: Single & Multi-Select Filters

test_url "TC-03" "Single Specific Doctor (Client 2)" \
    "${REPORT_URL}?generate_report=1&doctor%5B%5D=2" \
    "Cases 199, 202, 206, 211 (4 cases)"

test_url "TC-04" "Multiple Specific Doctors (2, 3)" \
    "${REPORT_URL}?generate_report=1&doctor%5B%5D=2&doctor%5B%5D=3" \
    "Cases 199, 200, 202, 203, 206, 208, 211, 213 (8 cases)"

test_url "TC-05a" "Workflow Stage - Finishing (6)" \
    "${REPORT_URL}?generate_report=1&status%5B%5D=6" \
    "Case 212 (1 case)"

test_url "TC-05b" "Workflow Stage - Design (1)" \
    "${REPORT_URL}?generate_report=1&status%5B%5D=1" \
    "Cases 207, 211 (2 cases)"

test_url "TC-05c" "Workflow Stage - 3D Printing (3)" \
    "${REPORT_URL}?generate_report=1&status%5B%5D=3" \
    "Cases 200, 207, 209 (3 cases)"

test_url "TC-06" "Combination Filters (Doctor+Material+JobType)" \
    "${REPORT_URL}?generate_report=1&doctor%5B%5D=2&material%5B%5D=1&job_type%5B%5D=1" \
    "Cases 199, 202, 206, 211 (4 cases)"

test_url "TC-07" "Material Filter (Zirconia)" \
    "${REPORT_URL}?generate_report=1&material%5B%5D=1" \
    "All 15 cases (199-213)"

# Test Suite 3: Numeric Range & Toggle Filters

test_url "TC-08" "Amount Range - From Only (>=100)" \
    "${REPORT_URL}?generate_report=1&amount_from=100" \
    "All except 204 (14 cases)"

test_url "TC-09" "Amount Range - To Only (<=500)" \
    "${REPORT_URL}?generate_report=1&amount_to=500" \
    "All except 205 (14 cases)"

test_url "TC-10" "Amount Range - Between (100-500)" \
    "${REPORT_URL}?generate_report=1&amount_from=100&amount_to=500" \
    "Cases 199, 200, 201, 202, 203, 206, 208, 209, 210, 212, 213 (11 cases)"

test_url "TC-10b" "Amount Range - Very Low (1-100)" \
    "${REPORT_URL}?generate_report=1&amount_from=1&amount_to=100" \
    "Cases 202, 204 (2 cases)"

test_url "TC-11" "Invalid Amount Range (500-100)" \
    "${REPORT_URL}?generate_report=1&amount_from=500&amount_to=100" \
    "No results or error"

test_url "TC-12" "Units Range (2-4)" \
    "${REPORT_URL}?generate_report=1&units_from=2&units_to=4" \
    "Cases 200, 207, 209 (3 cases)"

test_url "TC-12b" "Units Range - Many (6+)" \
    "${REPORT_URL}?generate_report=1&units_from=6&units_to=10" \
    "Case 205 (1 case)"

test_url "TC-13" "Completion Status - Completed" \
    "${REPORT_URL}?generate_report=1&show_completed=completed" \
    "Cases 199, 201, 202, 204, 205, 206, 210 (7 cases)"

test_url "TC-14" "Completion Status - In Progress" \
    "${REPORT_URL}?generate_report=1&show_completed=in_progress" \
    "Cases 200, 203, 207, 208, 209, 211, 212, 213 (8 cases)"

# Test Suite 5: Edge Cases

test_url "TC-19" "No Results Found (Invalid Doctor)" \
    "${REPORT_URL}?generate_report=1&doctor%5B%5D=99999" \
    "No cases found message"

test_url "TC-20" "All Option Cleanup" \
    "${REPORT_URL}?generate_report=1&doctor%5B%5D=all&doctor%5B%5D=2" \
    "Cases 199, 202, 206, 211 (4 cases)"

test_url "TC-21" "Complex Real-World Example" \
    "${REPORT_URL}?generate_report=1&from=2025-10-01&to=2025-10-29&doctor%5B%5D=all&material%5B%5D=all&job_type%5B%5D=all&status%5B%5D=all&amount_from=1&amount_to=200&show_completed=all" \
    "Cases with invoice 1-200 JOD (10 cases)"

# Additional Tests

test_url "EXTRA-01" "Job Type - Crowns Only" \
    "${REPORT_URL}?generate_report=1&job_type%5B%5D=1" \
    "10 cases with Crown job type"

test_url "EXTRA-02" "Job Type - Bridges Only" \
    "${REPORT_URL}?generate_report=1&job_type%5B%5D=2" \
    "Cases 200, 205, 209 (3 cases)"

test_url "EXTRA-03" "Job Type - Implants Only" \
    "${REPORT_URL}?generate_report=1&job_type%5B%5D=6" \
    "Case 201 (1 case)"

test_url "EXTRA-04" "High-Value In-Progress" \
    "${REPORT_URL}?generate_report=1&amount_from=200&show_completed=in_progress" \
    "Cases 200, 209 (2 cases)"

test_url "EXTRA-05" "Completed Low-Value" \
    "${REPORT_URL}?generate_report=1&amount_to=200&show_completed=completed" \
    "Cases 199, 201, 202, 204, 206, 210 (6 cases)"

# Summary
echo "" >> "$LOG_FILE"
echo "---" >> "$LOG_FILE"
echo "" >> "$LOG_FILE"
echo "## Test Summary" >> "$LOG_FILE"
echo "" >> "$LOG_FILE"
echo "**Total Tests Run:** 26" >> "$LOG_FILE"
echo "**Test Completion Date:** $(date)" >> "$LOG_FILE"
echo "" >> "$LOG_FILE"
echo "### Test Coverage" >> "$LOG_FILE"
echo "- Basic & Date Filters: 2 tests" >> "$LOG_FILE"
echo "- Single & Multi-Select Filters: 7 tests" >> "$LOG_FILE"
echo "- Numeric Range & Toggle Filters: 9 tests" >> "$LOG_FILE"
echo "- Edge Cases: 3 tests" >> "$LOG_FILE"
echo "- Additional Tests: 5 tests" >> "$LOG_FILE"
echo "" >> "$LOG_FILE"

echo ""
echo "=========================================="
echo "Testing Complete!"
echo "=========================================="
echo "Results saved to: $LOG_FILE"
echo ""
echo "Summary:"
echo "- Total Tests: 26"
echo "- Log File: $LOG_FILE"
echo ""
