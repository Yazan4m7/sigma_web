#!/bin/bash

# ====================================================================
# SIGMA Dashboard Load Time Measurement Script
# ====================================================================
# This script measures the actual page load time for the operations dashboard
# Run this BEFORE and AFTER adding indexes to compare performance
# ====================================================================

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "======================================================================"
echo "SIGMA Operations Dashboard - Performance Measurement"
echo "======================================================================"
echo ""

# Configuration
BASE_URL="http://127.0.0.1:8000"
DASHBOARD_URL="${BASE_URL}/operations-dashboard"
ITERATIONS=5

# Check if curl is available
if ! command -v curl &> /dev/null; then
    echo -e "${RED}Error: curl is not installed${NC}"
    exit 1
fi

# Function to measure load time
measure_load_time() {
    local url=$1
    local cookie=$2

    # Use curl with timing info
    time_total=$(curl -o /dev/null -s -w '%{time_total}\n' \
        -H "Cookie: ${cookie}" \
        "${url}")

    echo "${time_total}"
}

# Prompt for session cookie
echo -e "${YELLOW}Please provide your Laravel session cookie:${NC}"
echo "1. Open browser and login to SIGMA"
echo "2. Open Developer Tools (F12)"
echo "3. Go to Application > Cookies > http://127.0.0.1:8000"
echo "4. Copy the value of 'laravel_session' cookie"
echo ""
read -p "Paste cookie value: " SESSION_COOKIE

if [ -z "$SESSION_COOKIE" ]; then
    echo -e "${RED}Error: Session cookie is required${NC}"
    exit 1
fi

COOKIE="laravel_session=${SESSION_COOKIE}"

echo ""
echo -e "${GREEN}Starting performance measurement...${NC}"
echo "URL: ${DASHBOARD_URL}"
echo "Iterations: ${ITERATIONS}"
echo ""

# Array to store times
declare -a times

# Run measurements
for i in $(seq 1 $ITERATIONS); do
    echo -n "Iteration $i/$ITERATIONS... "
    load_time=$(measure_load_time "${DASHBOARD_URL}" "${COOKIE}")
    times+=("$load_time")

    # Convert to milliseconds for display
    load_time_ms=$(echo "$load_time * 1000" | bc)
    echo -e "${GREEN}${load_time_ms} ms${NC}"

    # Small delay between requests
    sleep 1
done

echo ""
echo "======================================================================"
echo "RESULTS"
echo "======================================================================"

# Calculate statistics
total=0
min=${times[0]}
max=${times[0]}

for time in "${times[@]}"; do
    total=$(echo "$total + $time" | bc)

    # Update min
    if (( $(echo "$time < $min" | bc -l) )); then
        min=$time
    fi

    # Update max
    if (( $(echo "$time > $max" | bc -l) )); then
        max=$time
    fi
done

# Calculate average
avg=$(echo "scale=4; $total / ${#times[@]}" | bc)

# Convert to milliseconds
min_ms=$(echo "$min * 1000" | bc)
max_ms=$(echo "$max * 1000" | bc)
avg_ms=$(echo "$avg * 1000" | bc)

echo "Minimum load time: ${min_ms} ms"
echo "Maximum load time: ${max_ms} ms"
echo -e "${YELLOW}Average load time: ${avg_ms} ms${NC}"
echo ""
echo "Individual measurements:"
for i in "${!times[@]}"; do
    time_ms=$(echo "${times[$i]} * 1000" | bc)
    echo "  Iteration $(($i + 1)): ${time_ms} ms"
done

echo ""
echo "======================================================================"
echo "SAVE THESE RESULTS TO COMPARE AFTER OPTIMIZATION"
echo "======================================================================"
echo ""

# Save results to file
RESULTS_FILE="dashboard_performance_$(date +%Y%m%d_%H%M%S).txt"
{
    echo "SIGMA Dashboard Performance Test"
    echo "Date: $(date)"
    echo "URL: ${DASHBOARD_URL}"
    echo "Iterations: ${ITERATIONS}"
    echo ""
    echo "RESULTS:"
    echo "--------"
    echo "Minimum: ${min_ms} ms"
    echo "Maximum: ${max_ms} ms"
    echo "Average: ${avg_ms} ms"
    echo ""
    echo "Individual measurements:"
    for i in "${!times[@]}"; do
        time_ms=$(echo "${times[$i]} * 1000" | bc)
        echo "  Iteration $(($i + 1)): ${time_ms} ms"
    done
} > "$RESULTS_FILE"

echo -e "${GREEN}Results saved to: ${RESULTS_FILE}${NC}"
