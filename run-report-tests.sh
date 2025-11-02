#!/bin/bash

# SIGMA Reports Automated Testing Script
# Based on REPORTS_TESTING_PLAN.md

BASE_URL="http://127.0.0.1:8000"
TIMESTAMP=$(date +"%Y%m%d-%H%M%S")
RESULTS_FILE="test-results-${TIMESTAMP}.md"

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Initialize results file
echo "# SIGMA Reports Test Results" > "$RESULTS_FILE"
echo "**Generated:** $(date)" >> "$RESULTS_FILE"
echo "" >> "$RESULTS_FILE"
echo "| Report | Test | Status | HTTP Code | Notes |" >> "$RESULTS_FILE"
echo "|--------|------|--------|-----------|-------|" >> "$RESULTS_FILE"

# Test counter
TOTAL_TESTS=0
PASSED_TESTS=0
FAILED_TESTS=0

# Function to test a URL
test_url() {
    local report_name="$1"
    local test_name="$2"
    local test_url="$3"

    TOTAL_TESTS=$((TOTAL_TESTS + 1))

    echo -n "Testing $report_name - $test_name..."

    # Make the request and capture HTTP code and response
    HTTP_CODE=$(php -r "
        \$ch = curl_init('$test_url');
        curl_setopt(\$ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(\$ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt(\$ch, CURLOPT_TIMEOUT, 30);
        \$response = curl_exec(\$ch);
        \$http_code = curl_getinfo(\$ch, CURLINFO_HTTP_CODE);
        curl_close(\$ch);
        echo \$http_code;
    " 2>&1)

    # Check for errors in response
    ERRORS=$(php -r "
        \$ch = curl_init('$test_url');
        curl_setopt(\$ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(\$ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt(\$ch, CURLOPT_TIMEOUT, 30);
        \$response = curl_exec(\$ch);
        curl_close(\$ch);

        if (stripos(\$response, 'error') !== false ||
            stripos(\$response, 'exception') !== false ||
            stripos(\$response, 'undefined') !== false) {
            echo 'HAS_ERRORS';
        } else {
            echo 'NO_ERRORS';
        }
    " 2>&1)

    # Determine status
    STATUS="FAIL"
    NOTES=""

    if [ "$HTTP_CODE" = "200" ] && [ "$ERRORS" = "NO_ERRORS" ]; then
        STATUS="PASS"
        PASSED_TESTS=$((PASSED_TESTS + 1))
        echo -e " ${GREEN}PASS${NC}"
    else
        FAILED_TESTS=$((FAILED_TESTS + 1))
        echo -e " ${RED}FAIL${NC}"
        if [ "$HTTP_CODE" != "200" ]; then
            NOTES="HTTP $HTTP_CODE"
        fi
        if [ "$ERRORS" = "HAS_ERRORS" ]; then
            NOTES="$NOTES PHP Errors"
        fi
    fi

    # Write to results file
    echo "| $report_name | $test_name | $STATUS | $HTTP_CODE | $NOTES |" >> "$RESULTS_FILE"
}

echo "========================================="
echo " SIGMA Reports Comprehensive Test Suite"
echo "========================================="
echo ""

# REPORT 1: Number of Units Report
echo -e "${YELLOW}[Report 1/7] Number of Units Report${NC}"
test_url "Num of Units" "Test 1.1" "${BASE_URL}/reports/num-of-units?from=2025-10-01&to=2025-10-20&material[]=2&material[]=3&material[]=4&doctor[]=all"
test_url "Num of Units" "Test 1.2" "${BASE_URL}/reports/num-of-units?from=2024-12-15&to=2025-01-15&material[]=1&doctor[]=all"
test_url "Num of Units" "Test 1.3" "${BASE_URL}/reports/num-of-units?from=2025-10-20&to=2025-10-20&material=all&doctor[]=all"
test_url "Num of Units" "Test 1.4" "${BASE_URL}/reports/num-of-units?from=2025-07-01&to=2025-10-20&material[]=2&material[]=3&doctor[]=1"

# REPORT 2: Implants Report
echo -e "${YELLOW}[Report 2/7] Implants Report${NC}"
test_url "Implants" "Test 2.1" "${BASE_URL}/reports/implants?from=2025-10-01&to=2025-10-20&perToggle=1&implantsInput=all&abutmentsInput=all&doctor[]=all"
test_url "Implants" "Test 2.2" "${BASE_URL}/reports/implants?from=2024-12-15&to=2025-01-15&perToggle=0&implantsInput=all&abutmentsInput=all&doctor[]=all"
test_url "Implants" "Test 2.3" "${BASE_URL}/reports/implants?from=2025-07-01&to=2025-10-20&perToggle=1&implantsInput[]=1&implantsInput[]=2&abutmentsInput=all&doctor[]=all"
test_url "Implants" "Test 2.4" "${BASE_URL}/reports/implants?from=2025-10-20&to=2025-10-20&perToggle=0&implantsInput=all&abutmentsInput[]=1&abutmentsInput[]=2&doctor[]=all"

# REPORT 3: Job Types Report
echo -e "${YELLOW}[Report 3/7] Job Types Report${NC}"
test_url "Job Types" "Test 3.1" "${BASE_URL}/reports/job-types?from=2025-10-01&to=2025-10-20&perToggle=1&jobTypesInput[]=1&jobTypesInput[]=2&jobTypesInput[]=3&jobTypesInput[]=4&doctor[]=all"
test_url "Job Types" "Test 3.2" "${BASE_URL}/reports/job-types?from=2024-12-15&to=2025-01-15&perToggle=0&jobTypesInput[]=1&jobTypesInput[]=2&doctor[]=all"
test_url "Job Types" "Test 3.3" "${BASE_URL}/reports/job-types?from=2025-10-20&to=2025-10-20&perToggle=1&jobTypesInput=all&doctor[]=all"
test_url "Job Types" "Test 3.4" "${BASE_URL}/reports/job-types?from=2025-07-01&to=2025-10-20&perToggle=0&jobTypesInput[]=1&jobTypesInput[]=3&doctor[]=1"

# REPORT 4: Repeats Report
echo -e "${YELLOW}[Report 4/7] Repeats Report${NC}"
test_url "Repeats" "Test 4.1" "${BASE_URL}/reports/repeats?from=2025-10-01&to=2025-10-20&perToggle=0&countOrPercentageToggle=1&failureTypeInput=all&doctor[]=all"
test_url "Repeats" "Test 4.2" "${BASE_URL}/reports/repeats?from=2024-12-15&to=2025-01-15&perToggle=1&countOrPercentageToggle=0&failureTypeInput[]=0&failureTypeInput[]=1&doctor[]=all"
test_url "Repeats" "Test 4.3" "${BASE_URL}/reports/repeats?from=2025-07-01&to=2025-10-20&perToggle=1&countOrPercentageToggle=1&failureTypeInput[]=2&failureTypeInput[]=3&doctor[]=all"
test_url "Repeats" "Test 4.4" "${BASE_URL}/reports/repeats?from=2025-10-20&to=2025-10-20&perToggle=0&countOrPercentageToggle=0&failureTypeInput=all&doctor[]=all"

# REPORT 5: QC Report
echo -e "${YELLOW}[Report 5/7] QC Report${NC}"
test_url "QC" "Test 5.1" "${BASE_URL}/reports/QC?from=2025-10-01&to=2025-10-20&causesInput=all&failureTypeInput=all&doctor[]=all"
test_url "QC" "Test 5.2" "${BASE_URL}/reports/QC?from=2024-12-15&to=2025-01-15&causesInput[]=1&causesInput[]=2&failureTypeInput=all&doctor[]=all"
test_url "QC" "Test 5.3" "${BASE_URL}/reports/QC?from=2025-07-01&to=2025-10-20&causesInput=all&failureTypeInput[]=0&failureTypeInput[]=1&doctor[]=all"
test_url "QC" "Test 5.4" "${BASE_URL}/reports/QC?from=2025-10-20&to=2025-10-20&causesInput[]=1&failureTypeInput[]=0&doctor[]=1"

# REPORT 6: Material Report
echo -e "${YELLOW}[Report 6/7] Material Report${NC}"
test_url "Material" "Test 6.1" "${BASE_URL}/reports/material?from=2025-10-01&to=2025-10-20&doctor=all"
test_url "Material" "Test 6.2" "${BASE_URL}/reports/material?from=2024-12-15&to=2025-01-15&doctor[]=1"
test_url "Material" "Test 6.3" "${BASE_URL}/reports/material?from=2025-07-01&to=2025-10-20&doctor[]=1&doctor[]=2&doctor[]=3"
test_url "Material" "Test 6.4" "${BASE_URL}/reports/material?from=2025-10-20&to=2025-10-20&doctor=all&patient_name=ahmad"

# REPORT 7: Master Report
echo -e "${YELLOW}[Report 7/7] Master Report${NC}"
test_url "Master" "Test 7.1" "${BASE_URL}/reports/master?from=2025-10-01&to=2025-10-20&doctor=all&material=all&job_type=all&show_completed=all"
test_url "Master" "Test 7.2" "${BASE_URL}/reports/master?from=2024-12-15&to=2025-01-15&doctor=all&material[]=1&material[]=2&job_type[]=1&show_completed=all"
test_url "Master" "Test 7.3" "${BASE_URL}/reports/master?from=2025-07-01&to=2025-10-20&doctor=all&material=all&job_type=all&show_completed=completed"
test_url "Master" "Test 7.4" "${BASE_URL}/reports/master?from=2025-10-20&to=2025-10-20&doctor=all&material=all&job_type=all&show_completed=in_progress"
test_url "Master" "Test 7.5" "${BASE_URL}/reports/master?from=2025-10-01&to=2025-10-20&doctor=all&material=all&job_type=all&status[]=2&status[]=3&show_completed=all"
test_url "Master" "Test 7.6" "${BASE_URL}/reports/master?from=2025-07-01&to=2025-10-20&doctor=all&material=all&job_type=all&amount_from=100&amount_to=500&show_completed=all"
test_url "Master" "Test 7.7" "${BASE_URL}/reports/master?from=2025-10-01&to=2025-10-20&doctor=all&material=all&job_type=all&units_from=1&units_to=5&show_completed=all"
test_url "Master" "Test 7.8" "${BASE_URL}/reports/master?from=2024-12-15&to=2025-01-15&doctor[]=1&material[]=2&job_type[]=1&status[]=7&amount_from=50&show_completed=all"

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
