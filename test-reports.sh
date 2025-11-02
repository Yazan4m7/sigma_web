#!/bin/bash

# SIGMA Reports Comprehensive Testing Script
# Generated: 2025-10-20

BASE_URL="http://127.0.0.1:8000"
RESULTS_FILE="test-results-$(date +%Y%m%d-%H%M%S).md"

echo "# SIGMA Reports Test Results" > $RESULTS_FILE
echo "" >> $RESULTS_FILE
echo "**Test Date:** $(date)" >> $RESULTS_FILE
echo "**Base URL:** $BASE_URL" >> $RESULTS_FILE
echo "" >> $RESULTS_FILE
echo "| Report | Test | HTTP Status | Errors | Result |" >> $RESULTS_FILE
echo "|--------|------|-------------|--------|--------|" >> $RESULTS_FILE

# Function to test a URL
test_url() {
    local report_name="$1"
    local test_name="$2"
    local url="$3"

    # Get HTTP status
    http_status=$(curl -s -o /tmp/response.html -w "%{http_code}" "$url")

    # Check for PHP errors in response
    error_count=$(grep -i "error\|exception\|undefined variable\|fatal" /tmp/response.html 2>/dev/null | wc -l)

    # Determine result
    if [ "$http_status" = "200" ] && [ "$error_count" = "0" ]; then
        result="✅ PASS"
    elif [ "$http_status" = "200" ]; then
        result="⚠️ WARN (Errors in HTML)"
    else
        result="❌ FAIL (HTTP $http_status)"
    fi

    echo "| $report_name | $test_name | $http_status | $error_count | $result |" >> $RESULTS_FILE
    echo "[$report_name - $test_name] Status: $http_status, Errors: $error_count - $result"
}

echo "Starting SIGMA Reports Testing..."
echo ""

# REPORT 1: Number of Units
echo "Testing Report 1: Number of Units..."
test_url "Num of Units" "1.1 Current Month" "${BASE_URL}/reports/num-of-units?from=2025-10-01&to=2025-10-20&material%5B%5D=2&material%5B%5D=3&material%5B%5D=4&doctor%5B%5D=all"
test_url "Num of Units" "1.2 Year Boundary" "${BASE_URL}/reports/num-of-units?from=2024-12-15&to=2025-01-15&material%5B%5D=1&doctor%5B%5D=all"
test_url "Num of Units" "1.3 Single Day" "${BASE_URL}/reports/num-of-units?from=2025-10-20&to=2025-10-20&material=all&doctor%5B%5D=all"
test_url "Num of Units" "1.4 Multi-Month" "${BASE_URL}/reports/num-of-units?from=2025-07-01&to=2025-10-20&material%5B%5D=2&material%5B%5D=3&doctor%5B%5D=1"

# REPORT 2: Implants
echo "Testing Report 2: Implants..."
test_url "Implants" "2.1 Units Mode" "${BASE_URL}/reports/implants?from=2025-10-01&to=2025-10-20&perToggle=1&implantsInput=all&abutmentsInput=all&doctor%5B%5D=all"
test_url "Implants" "2.2 Cases Mode" "${BASE_URL}/reports/implants?from=2024-12-15&to=2025-01-15&perToggle=0&implantsInput=all&abutmentsInput=all&doctor%5B%5D=all"
test_url "Implants" "2.3 Specific Implants" "${BASE_URL}/reports/implants?from=2025-07-01&to=2025-10-20&perToggle=1&implantsInput%5B%5D=1&implantsInput%5B%5D=2&abutmentsInput=all&doctor%5B%5D=all"
test_url "Implants" "2.4 Specific Abutments" "${BASE_URL}/reports/implants?from=2025-10-20&to=2025-10-20&perToggle=0&implantsInput=all&abutmentsInput%5B%5D=1&abutmentsInput%5B%5D=2&doctor%5B%5D=all"

# REPORT 3: Job Types
echo "Testing Report 3: Job Types..."
test_url "Job Types" "3.1 Units Mode" "${BASE_URL}/reports/job-types?from=2025-10-01&to=2025-10-20&perToggle=1&jobTypesInput%5B%5D=1&jobTypesInput%5B%5D=2&jobTypesInput%5B%5D=3&jobTypesInput%5B%5D=4&doctor%5B%5D=all"
test_url "Job Types" "3.2 Cases Mode" "${BASE_URL}/reports/job-types?from=2024-12-15&to=2025-01-15&perToggle=0&jobTypesInput%5B%5D=1&jobTypesInput%5B%5D=2&doctor%5B%5D=all"
test_url "Job Types" "3.3 Single Day" "${BASE_URL}/reports/job-types?from=2025-10-20&to=2025-10-20&perToggle=1&jobTypesInput=all&doctor%5B%5D=all"
test_url "Job Types" "3.4 Multi-Month" "${BASE_URL}/reports/job-types?from=2025-07-01&to=2025-10-20&perToggle=0&jobTypesInput%5B%5D=1&jobTypesInput%5B%5D=3&doctor%5B%5D=1"

# REPORT 4: Repeats
echo "Testing Report 4: Repeats..."
test_url "Repeats" "4.1 Count Mode" "${BASE_URL}/reports/repeats?from=2025-10-01&to=2025-10-20&perToggle=0&countOrPercentageToggle=1&failureTypeInput=all&doctor%5B%5D=all"
test_url "Repeats" "4.2 Percentage Mode" "${BASE_URL}/reports/repeats?from=2024-12-15&to=2025-01-15&perToggle=1&countOrPercentageToggle=0&failureTypeInput%5B%5D=0&failureTypeInput%5B%5D=1&doctor%5B%5D=all"
test_url "Repeats" "4.3 Units + Count" "${BASE_URL}/reports/repeats?from=2025-07-01&to=2025-10-20&perToggle=1&countOrPercentageToggle=1&failureTypeInput%5B%5D=2&failureTypeInput%5B%5D=3&doctor%5B%5D=all"
test_url "Repeats" "4.4 Cases + Percentage" "${BASE_URL}/reports/repeats?from=2025-10-20&to=2025-10-20&perToggle=0&countOrPercentageToggle=0&failureTypeInput=all&doctor%5B%5D=all"

# REPORT 5: QC
echo "Testing Report 5: QC..."
test_url "QC" "5.1 All Causes" "${BASE_URL}/reports/QC?from=2025-10-01&to=2025-10-20&causesInput=all&failureTypeInput=all&doctor%5B%5D=all"
test_url "QC" "5.2 Specific Causes" "${BASE_URL}/reports/QC?from=2024-12-15&to=2025-01-15&causesInput%5B%5D=1&causesInput%5B%5D=2&failureTypeInput=all&doctor%5B%5D=all"
test_url "QC" "5.3 Failure Types" "${BASE_URL}/reports/QC?from=2025-07-01&to=2025-10-20&causesInput=all&failureTypeInput%5B%5D=0&failureTypeInput%5B%5D=1&doctor%5B%5D=all"
test_url "QC" "5.4 Combined Filters" "${BASE_URL}/reports/QC?from=2025-10-20&to=2025-10-20&causesInput%5B%5D=1&failureTypeInput%5B%5D=0&doctor%5B%5D=1"

# REPORT 6: Material
echo "Testing Report 6: Material..."
test_url "Material" "6.1 All Clients" "${BASE_URL}/reports/material?from=2025-10-01&to=2025-10-20&doctor=all"
test_url "Material" "6.2 Specific Client" "${BASE_URL}/reports/material?from=2024-12-15&to=2025-01-15&doctor%5B%5D=1"
test_url "Material" "6.3 Multiple Clients" "${BASE_URL}/reports/material?from=2025-07-01&to=2025-10-20&doctor%5B%5D=1&doctor%5B%5D=2&doctor%5B%5D=3"
test_url "Material" "6.4 Patient Search" "${BASE_URL}/reports/material?from=2025-10-20&to=2025-10-20&doctor=all&patient_name=ahmad"

# REPORT 7: Master Report
echo "Testing Report 7: Master Report..."
test_url "Master Report" "7.1 Basic Filters" "${BASE_URL}/reports/master?from=2025-10-01&to=2025-10-20&doctor=all&material=all&job_type=all&show_completed=all"
test_url "Master Report" "7.2 Material + Job Type" "${BASE_URL}/reports/master?from=2024-12-15&to=2025-01-15&doctor=all&material%5B%5D=1&material%5B%5D=2&job_type%5B%5D=1&show_completed=all"
test_url "Master Report" "7.3 Completed Only" "${BASE_URL}/reports/master?from=2025-07-01&to=2025-10-20&doctor=all&material=all&job_type=all&show_completed=completed"
test_url "Master Report" "7.4 In Progress Only" "${BASE_URL}/reports/master?from=2025-10-20&to=2025-10-20&doctor=all&material=all&job_type=all&show_completed=in_progress"
test_url "Master Report" "7.5 Workflow Stage" "${BASE_URL}/reports/master?from=2025-10-01&to=2025-10-20&doctor=all&material=all&job_type=all&status%5B%5D=2&status%5B%5D=3&show_completed=all"
test_url "Master Report" "7.6 Amount Range" "${BASE_URL}/reports/master?from=2025-07-01&to=2025-10-20&doctor=all&material=all&job_type=all&amount_from=100&amount_to=500&show_completed=all"
test_url "Master Report" "7.7 Units Range" "${BASE_URL}/reports/master?from=2025-10-01&to=2025-10-20&doctor=all&material=all&job_type=all&units_from=1&units_to=5&show_completed=all"
test_url "Master Report" "7.8 Complex Multi-Filter" "${BASE_URL}/reports/master?from=2024-12-15&to=2025-01-15&doctor%5B%5D=1&material%5B%5D=2&job_type%5B%5D=1&status%5B%5D=7&amount_from=50&show_completed=all"

echo ""
echo "==============================================="
echo "Testing Complete!"
echo "Results saved to: $RESULTS_FILE"
echo "==============================================="
echo ""
cat $RESULTS_FILE
