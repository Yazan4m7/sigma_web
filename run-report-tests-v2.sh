#!/bin/bash

# SIGMA Reports Testing Script V2 - Improved validation
# Checks for actual page load success, not just error keywords

BASE_URL="http://127.0.0.1:8000"
TIMESTAMP=$(date +"%Y%m%d-%H%M%S")
RESULTS_FILE="test-results-v2-${TIMESTAMP}.md"

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Initialize results
echo "# SIGMA Reports Test Results V2" > "$RESULTS_FILE"
echo "**Generated:** $(date)" >> "$RESULTS_FILE"
echo "" >> "$RESULTS_FILE"
echo "| Report | Test | Status | HTTP Code | Has Content |" >> "$RESULTS_FILE"
echo "|--------|------|--------|-----------|-------------|" >> "$RESULTS_FILE"

TOTAL_TESTS=0
PASSED_TESTS=0
FAILED_TESTS=0

# Function to test a URL - improved validation
test_url() {
    local report_name="$1"
    local test_name="$2"
    local test_url="$3"

    TOTAL_TESTS=$((TOTAL_TESTS + 1))

    echo -n "Testing $report_name - $test_name..."

    # Get HTTP code
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$test_url" 2>/dev/null)

    # Get page content and check for actual content indicators
    RESPONSE=$(curl -s "$test_url" 2>/dev/null)

    # Check for positive indicators that page loaded correctly
    HAS_HTML=$(echo "$RESPONSE" | grep -c "<!DOCTYPE html" || echo "0")
    HAS_TABLE=$(echo "$RESPONSE" | grep -c "<table" || echo "0")
    HAS_CONTENT=$(echo "$RESPONSE" | grep -c "master-report-container\|report-container\|dataTables\|card-body" || echo "0")

    # Determine status
    STATUS="FAIL"
    HAS_CONTENT_TEXT="No"

    if [ "$HTTP_CODE" = "200" ] && [ "$HAS_HTML" -gt "0" ] && ([ "$HAS_TABLE" -gt "0" ] || [ "$HAS_CONTENT" -gt "0" ]); then
        STATUS="PASS"
        HAS_CONTENT_TEXT="Yes"
        PASSED_TESTS=$((PASSED_TESTS + 1))
        echo -e " ${GREEN}PASS${NC}"
    else
        FAILED_TESTS=$((FAILED_TESTS + 1))
        echo -e " ${RED}FAIL${NC} (HTTP: $HTTP_CODE, HTML: $HAS_HTML, Content: $HAS_CONTENT)"
    fi

    echo "| $report_name | $test_name | $STATUS | $HTTP_CODE | $HAS_CONTENT_TEXT |" >> "$RESULTS_FILE"
}

echo "========================================="
echo " SIGMA Reports Test Suite V2"
echo "========================================="
echo ""

# Test all reports
echo -e "${YELLOW}[Report 1/7] Number of Units Report${NC}"
test_url "Num of Units" "Test 1.1" "${BASE_URL}/reports/num-of-units?from=2025-10-01&to=2025-10-20&material%5B%5D=2&material%5B%5D=3&material%5B%5D=4&doctor%5B%5D=all"
test_url "Num of Units" "Test 1.2" "${BASE_URL}/reports/num-of-units?from=2024-12-15&to=2025-01-15&material%5B%5D=1&doctor%5B%5D=all"
test_url "Num of Units" "Test 1.3" "${BASE_URL}/reports/num-of-units?from=2025-10-20&to=2025-10-20&material=all&doctor%5B%5D=all"
test_url "Num of Units" "Test 1.4" "${BASE_URL}/reports/num-of-units?from=2025-07-01&to=2025-10-20&material%5B%5D=2&material%5B%5D=3&doctor%5B%5D=1"

echo -e "${YELLOW}[Report 2/7] Implants Report${NC}"
test_url "Implants" "Test 2.1" "${BASE_URL}/reports/implants?from=2025-10-01&to=2025-10-20&perToggle=1&implantsInput=all&abutmentsInput=all&doctor%5B%5D=all"
test_url "Implants" "Test 2.2" "${BASE_URL}/reports/implants?from=2024-12-15&to=2025-01-15&perToggle=0&implantsInput=all&abutmentsInput=all&doctor%5B%5D=all"
test_url "Implants" "Test 2.3" "${BASE_URL}/reports/implants?from=2025-07-01&to=2025-10-20&perToggle=1&implantsInput%5B%5D=1&implantsInput%5B%5D=2&abutmentsInput=all&doctor%5B%5D=all"
test_url "Implants" "Test 2.4" "${BASE_URL}/reports/implants?from=2025-10-20&to=2025-10-20&perToggle=0&implantsInput=all&abutmentsInput%5B%5D=1&abutmentsInput%5B%5D=2&doctor%5B%5D=all"

echo -e "${YELLOW}[Report 3/7] Job Types Report${NC}"
test_url "Job Types" "Test 3.1" "${BASE_URL}/reports/job-types?from=2025-10-01&to=2025-10-20&perToggle=1&jobTypesInput%5B%5D=1&jobTypesInput%5B%5D=2&jobTypesInput%5B%5D=3&jobTypesInput%5B%5D=4&doctor%5B%5D=all"
test_url "Job Types" "Test 3.2" "${BASE_URL}/reports/job-types?from=2024-12-15&to=2025-01-15&perToggle=0&jobTypesInput%5B%5D=1&jobTypesInput%5B%5D=2&doctor%5B%5D=all"
test_url "Job Types" "Test 3.3" "${BASE_URL}/reports/job-types?from=2025-10-20&to=2025-10-20&perToggle=1&jobTypesInput=all&doctor%5B%5D=all"
test_url "Job Types" "Test 3.4" "${BASE_URL}/reports/job-types?from=2025-07-01&to=2025-10-20&perToggle=0&jobTypesInput%5B%5D=1&jobTypesInput%5B%5D=3&doctor%5B%5D=1"

echo -e "${YELLOW}[Report 4/7] Repeats Report${NC}"
test_url "Repeats" "Test 4.1" "${BASE_URL}/reports/repeats?from=2025-10-01&to=2025-10-20&perToggle=0&countOrPercentageToggle=1&failureTypeInput=all&doctor%5B%5D=all"
test_url "Repeats" "Test 4.2" "${BASE_URL}/reports/repeats?from=2024-12-15&to=2025-01-15&perToggle=1&countOrPercentageToggle=0&failureTypeInput%5B%5D=0&failureTypeInput%5B%5D=1&doctor%5B%5D=all"
test_url "Repeats" "Test 4.3" "${BASE_URL}/reports/repeats?from=2025-07-01&to=2025-10-20&perToggle=1&countOrPercentageToggle=1&failureTypeInput%5B%5D=2&failureTypeInput%5B%5D=3&doctor%5B%5D=all"
test_url "Repeats" "Test 4.4" "${BASE_URL}/reports/repeats?from=2025-10-20&to=2025-10-20&perToggle=0&countOrPercentageToggle=0&failureTypeInput=all&doctor%5B%5D=all"

echo -e "${YELLOW}[Report 5/7] QC Report${NC}"
test_url "QC" "Test 5.1" "${BASE_URL}/reports/QC?from=2025-10-01&to=2025-10-20&causesInput=all&failureTypeInput=all&doctor%5B%5D=all"
test_url "QC" "Test 5.2" "${BASE_URL}/reports/QC?from=2024-12-15&to=2025-01-15&causesInput%5B%5D=1&causesInput%5B%5D=2&failureTypeInput=all&doctor%5B%5D=all"
test_url "QC" "Test 5.3" "${BASE_URL}/reports/QC?from=2025-07-01&to=2025-10-20&causesInput=all&failureTypeInput%5B%5D=0&failureTypeInput%5B%5D=1&doctor%5B%5D=all"
test_url "QC" "Test 5.4" "${BASE_URL}/reports/QC?from=2025-10-20&to=2025-10-20&causesInput%5B%5D=1&failureTypeInput%5B%5D=0&doctor%5B%5D=1"

echo -e "${YELLOW}[Report 6/7] Material Report${NC}"
test_url "Material" "Test 6.1" "${BASE_URL}/reports/material?from=2025-10-01&to=2025-10-20&doctor=all"
test_url "Material" "Test 6.2" "${BASE_URL}/reports/material?from=2024-12-15&to=2025-01-15&doctor%5B%5D=1"
test_url "Material" "Test 6.3" "${BASE_URL}/reports/material?from=2025-07-01&to=2025-10-20&doctor%5B%5D=1&doctor%5B%5D=2&doctor%5B%5D=3"
test_url "Material" "Test 6.4" "${BASE_URL}/reports/material?from=2025-10-20&to=2025-10-20&doctor=all&patient_name=ahmad"

echo -e "${YELLOW}[Report 7/7] Master Report${NC}"
test_url "Master" "Test 7.1" "${BASE_URL}/reports/master?from=2025-10-01&to=2025-10-20&doctor=all&material=all&job_type=all&show_completed=all"
test_url "Master" "Test 7.2" "${BASE_URL}/reports/master?from=2024-12-15&to=2025-01-15&doctor=all&material%5B%5D=1&material%5B%5D=2&job_type%5B%5D=1&show_completed=all"
test_url "Master" "Test 7.3" "${BASE_URL}/reports/master?from=2025-07-01&to=2025-10-20&doctor=all&material=all&job_type=all&show_completed=completed"
test_url "Master" "Test 7.4" "${BASE_URL}/reports/master?from=2025-10-20&to=2025-10-20&doctor=all&material=all&job_type=all&show_completed=in_progress"
test_url "Master" "Test 7.5" "${BASE_URL}/reports/master?from=2025-10-01&to=2025-10-20&doctor=all&material=all&job_type=all&status%5B%5D=2&status%5B%5D=3&show_completed=all"
test_url "Master" "Test 7.6" "${BASE_URL}/reports/master?from=2025-07-01&to=2025-10-20&doctor=all&material=all&job_type=all&amount_from=100&amount_to=500&show_completed=all"
test_url "Master" "Test 7.7" "${BASE_URL}/reports/master?from=2025-10-01&to=2025-10-20&doctor=all&material=all&job_type=all&units_from=1&units_to=5&show_completed=all"
test_url "Master" "Test 7.8" "${BASE_URL}/reports/master?from=2024-12-15&to=2025-01-15&doctor%5B%5D=1&material%5B%5D=2&job_type%5B%5D=1&status%5B%5D=7&amount_from=50&show_completed=all"

# Summary
echo "" >> "$RESULTS_FILE"
echo "## Summary" >> "$RESULTS_FILE"
echo "" >> "$RESULTS_FILE"
echo "- **Total Tests:** $TOTAL_TESTS" >> "$RESULTS_FILE"
echo "- **Passed:** $PASSED_TESTS" >> "$RESULTS_FILE"
echo "- **Failed:** $FAILED_TESTS" >> "$RESULTS_FILE"
echo "- **Pass Rate:** $(awk "BEGIN {printf \"%.1f\", ($PASSED_TESTS/$TOTAL_TESTS)*100}")%" >> "$RESULTS_FILE"

echo ""
echo "========================================="
echo " Test Results Summary"
echo "========================================="
echo -e "Total Tests: $TOTAL_TESTS"
echo -e "${GREEN}Passed: $PASSED_TESTS${NC}"
echo -e "${RED}Failed: $FAILED_TESTS${NC}"
echo -e "Pass Rate: $(awk "BEGIN {printf \"%.1f\", ($PASSED_TESTS/$TOTAL_TESTS)*100}")%"
echo ""
echo "Full results saved to: $RESULTS_FILE"
echo "========================================="
