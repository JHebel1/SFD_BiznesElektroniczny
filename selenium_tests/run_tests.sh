#!/bin/bash

# Run Selenium tests for PrestaShop

set -e  # Exit on error

echo "=========================================="
echo "PrestaShop Selenium Test Suite"
echo "=========================================="
echo ""

# Check if virtual environment exists
if [ ! -d ".venv" ]; then
    echo "Creating virtual environment (.venv)..."
    python3 -m venv .venv
    echo "Virtual environment created"
else
    echo "Virtual environment already exists"
fi

# Activate virtual environment
echo "Activating virtual environment..."
source .venv/bin/activate
echo "Virtual environment activated"

# Upgrade pip
echo "Upgrading pip..."
pip install --upgrade pip > /dev/null 2>&1
echo "Pip upgraded"

# Install dependencies
echo "Installing dependencies..."
pip install -r requirements.txt > /dev/null 2>&1
echo "Dependencies installed"

# Create necessary directories
mkdir -p screenshots downloads reports
echo "Directories created"

# Run tests
echo ""
echo "=========================================="
echo "Running tests..."
echo "=========================================="
echo ""

if [ "$1" == "--html" ]; then
    pytest --html=reports/report.html --self-contained-html
elif [ "$1" == "--timing" ]; then
    pytest test_shop_workflow.py::test_full_workflow_timing -v
else
    pytest -v
fi

TEST_EXIT_CODE=$?

deactivate

echo ""
echo "=========================================="
if [ $TEST_EXIT_CODE -eq 0 ]; then
    echo "Tests completed successfully!"
else
    echo "Tests failed with exit code: $TEST_EXIT_CODE"
fi
echo "=========================================="

exit $TEST_EXIT_CODE
