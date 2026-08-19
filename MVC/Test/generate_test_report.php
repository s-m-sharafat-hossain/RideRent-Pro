<?php
/**
 * Customer Module Test Report Generator
 * Generates an HTML report of all customer module tests
 */

// Suppress error reporting for cleaner output
error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', 0);

// Define paths
define('BASE_PATH', __DIR__ . '/');
define('APP_PATH', BASE_PATH . 'app/');

// Autoloader
spl_autoload_register(function($class) {
    $classPath = str_replace('\\', '/', $class);
    $filePath = APP_PATH . $classPath . '.php';
    if (file_exists($filePath)) {
        require_once $filePath;
        return true;
    }
    return false;
});

// Load all required classes
$coreFiles = [
    'core/Database.php',
    'core/Model.php',
    'core/Controller.php',
    'core/View.php',
    'models/User.php',
    'models/Booking.php',
    'models/Vehicle.php',
    'models/Review.php',
    'models/Driver.php',
    'controllers/CustomerController.php',
    'controllers/AuthController.php'
];

foreach ($coreFiles as $file) {
    $path = APP_PATH . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}

require_once BASE_PATH . 'tests/TestCase.php';
require_once BASE_PATH . 'tests/Feature/CustomerModuleTest.php';

// Test Runner Class
class TestRunner {
    private $passed = 0;
    private $failed = 0;
    private $tests = [];
    private $startTime;
    
    public function run() {
        $this->startTime = microtime(true);
        $this->executeTests();
        return $this->generateReport();
    }
    
    private function executeTests() {
        try {
            $testClass = new CustomerModuleTest();
            $reflection = new ReflectionClass($testClass);
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
            
            foreach ($methods as $method) {
                if (strpos($method->getName(), 'test') === 0) {
                    $this->runTest($testClass, $method);
                }
            }
        } catch (Exception $e) {
            // Silent fail
        }
    }
    
    private function runTest($testInstance, ReflectionMethod $method) {
        $testName = $method->getName();
        try {
            // Call setUp if it exists
            if (method_exists($testInstance, 'setUp')) {
                $testInstance->setUp();
            }
            
            // Run the test
            $method->invoke($testInstance);
            
            // Call tearDown if it exists
            if (method_exists($testInstance, 'tearDown')) {
                $testInstance->tearDown();
            }
            
            $this->tests[] = [
                'name' => $testName,
                'status' => 'passed',
                'message' => ''
            ];
            $this->passed++;
        } catch (AssertionError $e) {
            $this->tests[] = [
                'name' => $testName,
                'status' => 'failed',
                'message' => $e->getMessage()
            ];
            $this->failed++;
        } catch (Exception $e) {
            $this->tests[] = [
                'name' => $testName,
                'status' => 'error',
                'message' => $e->getMessage()
            ];
            $this->failed++;
        }
    }
    
    private function generateReport() {
        $duration = microtime(true) - $this->startTime;
        $total = $this->passed + $this->failed;
        $passPercentage = $total > 0 ? round(($this->passed / $total) * 100, 2) : 0;
        
        $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RideRentPro - Customer Module Test Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding: 30px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        
        .stat {
            text-align: center;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .stat-total .stat-value { color: #667eea; }
        .stat-passed .stat-value { color: #28a745; }
        .stat-failed .stat-value { color: #dc3545; }
        .stat-duration .stat-value { color: #ffc107; }
        
        .progress-bar {
            width: 100%;
            height: 10px;
            background: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 15px;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #20c997);
            width: {$passPercentage}%;
            transition: width 0.3s ease;
        }
        
        .results {
            padding: 30px;
        }
        
        .result-item {
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #e9ecef;
            border-radius: 4px;
            background: #f8f9fa;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }
        
        .result-item.passed {
            border-left-color: #28a745;
            background: #f0fdf4;
        }
        
        .result-item.failed {
            border-left-color: #dc3545;
            background: #fef2f2;
        }
        
        .result-item.error {
            border-left-color: #ffc107;
            background: #fffbf0;
        }
        
        .result-icon {
            font-size: 20px;
            min-width: 30px;
            text-align: center;
        }
        
        .result-content {
            flex: 1;
        }
        
        .result-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
            font-size: 14px;
        }
        
        .result-message {
            font-size: 12px;
            color: #666;
            font-family: monospace;
        }
        
        .footer {
            padding: 20px 30px;
            background: #f8f9fa;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e9ecef;
        }
        
        .category {
            margin: 30px 0;
        }
        
        .category-title {
            font-weight: 600;
            color: #667eea;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
            font-size: 16px;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 10px;
        }
        
        .badge-passed {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-failed {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧪 RideRentPro Test Report</h1>
            <p>Customer Module Unit Tests</p>
        </div>
        
        <div class="summary">
            <div class="stat stat-total">
                <div class="stat-value">{$total}</div>
                <div class="stat-label">Total Tests</div>
            </div>
            <div class="stat stat-passed">
                <div class="stat-value">{$this->passed}</div>
                <div class="stat-label">Passed</div>
            </div>
            <div class="stat stat-failed">
                <div class="stat-value">{$this->failed}</div>
                <div class="stat-label">Failed</div>
            </div>
            <div class="stat stat-duration">
                <div class="stat-value">{$duration}s</div>
                <div class="stat-label">Duration</div>
            </div>
            <div class="progress-bar" style="grid-column: 1 / -1;">
                <div class="progress-fill"></div>
            </div>
            <div style="grid-column: 1 / -1; text-align: center; font-size: 12px; color: #666;">
                Pass Rate: <strong>{$passPercentage}%</strong>
            </div>
        </div>
        
        <div class="results">
HTML;

        // Group tests by category
        $categories = $this->groupTestsByCategory();
        
        foreach ($categories as $category => $tests) {
            $html .= "<div class='category'>";
            $html .= "<div class='category-title'>{$category}</div>";
            
            foreach ($tests as $test) {
                $icon = $test['status'] === 'passed' ? '✓' : '✗';
                $html .= <<<TEST
            <div class="result-item {$test['status']}">
                <div class="result-icon">{$icon}</div>
                <div class="result-content">
                    <div class="result-name">{$test['name']}</div>
TEST;
                if (!empty($test['message'])) {
                    $html .= "<div class='result-message'>{$test['message']}</div>";
                }
                $html .= "</div></div>";
            }
            
            $html .= "</div>";
        }
        
        $html .= <<<HTML
        </div>
        
        <div class="footer">
            <p>Report generated on <strong>HTML</strong> format | PHP {$_SERVER['PHP_VERSION']}</p>
        </div>
    </div>
</body>
</html>
HTML;
        
        return $html;
    }
    
    private function groupTestsByCategory() {
        $groups = [];
        
        foreach ($this->tests as $test) {
            // Extract category from test name
            if (preg_match('/^test(\w+?)([A-Z])/', $test['name'], $matches)) {
                $category = ucfirst($matches[1]);
            } else {
                $category = 'Other';
            }
            
            if (!isset($groups[$category])) {
                $groups[$category] = [];
            }
            
            $groups[$category][] = $test;
        }
        
        return $groups;
    }
}

// Run tests and output report
$runner = new TestRunner();
$report = $runner->run();

// Save report to file
$reportFile = BASE_PATH . 'tests/test_report.html';
file_put_contents($reportFile, $report);

// Output success message
echo "Test report generated successfully!\n";
echo "Report saved to: " . $reportFile . "\n";

// Also output to stdout for verification
echo $report;
