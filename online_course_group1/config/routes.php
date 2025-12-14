<?php
require_once __DIR__ . '/Router.php';

$router = new Router();

// Home routes
$router->get('/', 'HomeController', 'index');
$router->get('/home', 'HomeController', 'index');

// Auth routes
$router->get('/auth/select-role', 'AuthController', 'selectRole');
$router->get('/login', 'AuthController', 'showLogin');
$router->post('/login', 'AuthController', 'login');
$router->get('/auth/select-register', 'AuthController', 'showSelectRegister');
$router->get('/register', 'AuthController', 'showRegister');
$router->post('/register', 'AuthController', 'register');
$router->get('/logout', 'AuthController', 'logout');

// Course routes
$router->get('/courses', 'CourseController', 'index');
$router->get('/courses/{id}', 'CourseController', 'show');

// Student routes
$router->get('/student/dashboard', 'StudentController', 'dashboard');
$router->get('/student/courses', 'StudentController', 'myCourses');
$router->get('/student/course/{id}/progress', 'StudentController', 'courseProgress');

// Instructor routes
$router->get('/instructor/dashboard', 'InstructorController', 'dashboard');
$router->get('/instructor/courses', 'InstructorController', 'myCourses');
$router->get('/instructor/courses/create', 'InstructorController', 'createCourse');
$router->post('/instructor/courses', 'InstructorController', 'storeCourse');
$router->get('/instructor/courses/{id}/edit', 'InstructorController', 'editCourse');
$router->post('/instructor/courses/{id}/update', 'InstructorController', 'updateCourse');
$router->post('/instructor/courses/{id}/delete', 'InstructorController', 'deleteCourse');
$router->get('/instructor/courses/{id}/manage', 'InstructorController', 'manageCourse');
$router->get('/instructor/student-progress', 'InstructorController', 'studentProgress');
// Lesson routes
$router->get('/courses/{courseId}/lessons/create', 'LessonController', 'create');
$router->post('/courses/{courseId}/lessons', 'LessonController', 'store');
$router->get('/lessons/{id}', 'LessonController', 'show');
$router->get('/lessons/{id}/edit', 'LessonController', 'edit');
$router->post('/lessons/{id}/update', 'LessonController', 'update');
$router->post('/lessons/{id}/complete', 'LessonController', 'markComplete');
$router->post('/lessons/{id}/start', 'LessonController', 'markStarted');
$router->post('/lessons/{id}/delete', 'LessonController', 'delete');

// Material routes
$router->get('/courses/{courseId}/materials', 'MaterialController', 'index');
$router->post('/courses/{courseId}/materials', 'MaterialController', 'store');
$router->get('/materials/{id}/download', 'MaterialController', 'download');
$router->post('/materials/{id}/delete', 'MaterialController', 'delete');

// Admin routes
$router->get('/admin/dashboard', 'AdminController', 'dashboard');
$router->get('/admin/users', 'AdminController', 'manageUsers');
$router->post('/admin/users/{id}/activate', 'AdminController', 'activateUser');
$router->post('/admin/users/{id}/deactivate', 'AdminController', 'deactivateUser');
$router->get('/admin/courses', 'AdminController', 'manageCourses');
$router->post('/admin/courses/{id}/approve', 'AdminController', 'approveCourse');
$router->post('/admin/courses/{id}/reject', 'AdminController', 'rejectCourse');
$router->get('/admin/categories', 'AdminController', 'manageCategories');
$router->get('/admin/categories/create', 'AdminController', 'createCategory');
$router->post('/admin/categories', 'AdminController', 'storeCategory');
$router->get('/admin/categories/{id}/edit', 'AdminController', 'editCategory');
$router->post('/admin/categories/{id}/update', 'AdminController', 'updateCategory');
$router->post('/admin/categories/{id}/delete', 'AdminController', 'deleteCategory');

// Enrollment routes
$router->get('/enrollment/create/{courseId}', 'EnrollmentController', 'create');
$router->post('/enroll/{courseId}', 'EnrollmentController', 'enroll');
$router->post('/unenroll/{courseId}', 'EnrollmentController', 'unenroll');

return $router;
?>