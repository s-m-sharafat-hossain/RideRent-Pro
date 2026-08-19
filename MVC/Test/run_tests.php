<?php
/**
 * Simple PHP Test Runner for Customer Module Tests
 * This is a standalone test runner that doesn't require PHPUnit binary
 */

// Define paths
define('BASE_PATH', __DIR__ . '/../');
define('APP_PATH', BASE_PATH . 'app/');

// Colors for output
const GREEN = "\033[92m";
const RED = "\033[91m";
const YELLOW = "\033[93m";
const RESET = "\033[0m";
const BLUE = "\033[94m";

// Autoloader for app classes
spl_autoload_register(function($class) {
    $classPath = str_replace('\\', '/', $class);
    $filePath = APP_PATH . $classPath . '.php';
    
    if (file_exists($filePath)) {
        require_once $filePath;
        return true;
    }
    return false;
});

// Load core classes
require_once APP_PATH . 'core/Database.php';
require_once APP_PATH . 'core/Model.php';
require_once APP_PATH . 'core/Controller.php';
require_once APP_PATH . 'core/View.php';

// Load models
require_once APP_PATH . 'models/User.php';
require_once APP_PATH . 'models/Booking.php';
require_once APP_PATH . 'models/Vehicle.php';
require_once APP_PATH . 'models/Review.php';
require_once APP_PATH . 'models/Driver.php';

// Load controllers
require_once APP_PATH . 'controllers/CustomerController.php';
require_once APP_PATH . 'controllers/AuthController.php';

// Load test base class
require_once __DIR__ . '/../tests/TestCase.php';

// Simple Test Framework
class SimpleTestRunner {
    private $tests = [];
    private $passed = 0;
    private $failed = 0;
    private $assertions = 0;
    private $failures = [];
    
    public function addTestClass($testClassName) {
        $this->tests[] = $testClassName;
    }
    
    public function run() {
        echo BLUE . "╔════════════════════════════════════════════════════════════╗\n";
        echo BLUE . "║  RideRentPro - Customer Module Unit Tests                  ║\n";
        echo BLUE . "╚════════════════════════════════════════════════════════════╝\n\n";
        
        $startTime = microtime(true);
        
        foreach ($this->tests as $testClassName) {
            $this->runTestClass($testClassName);
        }
        
        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 3);
        
        $this->printSummary($duration);
    }
    
    private function runTestClass($className) {
        try {
            $testClass = new $className();
            $reflection = new ReflectionClass($testClass);
            
            echo BLUE . "📋 Test Class: " . RESET . $className . "\n";
            
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
            
            foreach ($methods as $method) {
                if (strpos($method->getName(), 'test') === 0) {
                    $this->runTest($testClass, $method);
                }
            }
            
            echo "\n";
        } catch (Exception $e) {
            echo RED . "✗ Error in $className: " . $e->getMessage() . RESET . "\n";
            $this->failed++;
        }
    }
    
    private function runTest($testInstance, ReflectionMethod $method) {
        try {
            // Setup
            $testInstance->setUp();
            
            // Run test
            $method->invoke($testInstance);
            
            // Teardown
            $testInstance->tearDown();
            
            echo GREEN . "  ✓ " . RESET . $method->getName() . "\n";
            $this->passed++;
            $this->assertions++;
        } catch (AssertionError $e) {
            echo RED . "  ✗ " . RESET . $method->getName() . "\n";
            echo RED . "    └─ " . $e->getMessage() . RESET . "\n";
            $this->failed++;
            $this->failures[] = [
                'test' => $method->getName(),
                'error' => $e->getMessage()
            ];
        } catch (Exception $e) {
            echo RED . "  ✗ " . RESET . $method->getName() . "\n";
            echo RED . "    └─ Exception: " . $e->getMessage() . RESET . "\n";
            $this->failed++;
            $this->failures[] = [
                'test' => $method->getName(),
                'error' => "Exception: " . $e->getMessage()
            ];
        }
    }
    
    private function printSummary($duration) {
        $total = $this->passed + $this->failed;
        
        echo BLUE . "╔════════════════════════════════════════════════════════════╗\n";
        echo BLUE . "║  TEST SUMMARY                                              ║\n";
        echo BLUE . "╚════════════════════════════════════════════════════════════╝\n\n";
        
        if ($this->failed === 0) {
            echo GREEN . "✓ All tests passed!\n\n";
        } else {
            echo RED . "✗ Some tests failed!\n\n";
        }
        
        echo "Tests run: $total\n";
        echo GREEN . "Passed: " . RESET . $this->passed . "\n";
        echo RED . "Failed: " . RESET . $this->failed . "\n";
        echo YELLOW . "Duration: " . RESET . "{$duration}s\n";
        
        if (!empty($this->failures)) {
            echo YELLOW . "\nFailures:\n" . RESET;
            foreach ($this->failures as $failure) {
                echo "  - " . $failure['test'] . ": " . $failure['error'] . "\n";
            }
        }
        
        echo "\n";
    }
}

// Create and run tests
$runner = new SimpleTestRunner();
$runner->addTestClass('CustomerModuleTest');
$runner->run();
