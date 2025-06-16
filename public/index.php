<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../vendor/autoload.php';

use app\Config\DatabaseConnection;
use app\Config\Router;
use app\Controllers\CourseController;
use app\Controllers\RegistrationController;
use app\Controllers\StudentController;
use app\Repositories\CourseRepository;
use app\Repositories\RegistrationRepository;
use app\Repositories\StudentRepository;
use app\Services\CourseService;
use app\Services\RegistrationService;
use app\Services\StudentService;

$connection = new DatabaseConnection();
$pdo = $connection->getConnection();

$studentRepository = new StudentRepository($pdo);
$studentService = new StudentService($studentRepository);
$studentController = new StudentController($studentService);

$courseRepository = new CourseRepository($pdo);
$courseService = new CourseService($courseRepository);
$courseController = new CourseController($courseService);

$registrationRepository = new RegistrationRepository($pdo);
$registrationService = new RegistrationService($registrationRepository);
$registrationController = new RegistrationController($registrationService);

Router::get('/student', [StudentController::class, 'getStudents']);
Router::put('/student/{id}', [StudentController::class, 'editStudent']);
Router::post('/student', [StudentController::class, 'insertStudent']);
Router::delete('/student/{id}', [StudentController::class, 'removeStudent']);

Router::get('/course', [CourseController::class, 'getCourses']);
Router::put('/course/{id}', [CourseController::class, 'editCourse']);
Router::post('/course', [CourseController::class, 'insertCourse']);
Router::delete('/course/{id}', [CourseController::class, 'removeCourse']);

Router::get('/registration', [RegistrationController::class, 'getRegistration']);
Router::get('/registration-by-course', [RegistrationController::class, 'getRegistrationByCourse']);
Router::post('/registration', [RegistrationController::class, 'insertRegistration']);

Router::run([
    StudentController::class => $studentService,
    CourseController::class => $courseService,
    RegistrationController::class => $registrationService
]);
