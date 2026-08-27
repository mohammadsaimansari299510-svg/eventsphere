<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\AdminController;

// 1. Public Visitor Routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');
Route::get('/events/{id}/calendar', [EventController::class, 'downloadCalendar'])->name('events.calendar');

Route::get('/gallery', [MediaController::class, 'index'])->name('gallery.index');

// 2. Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. Registered Student (Participant) Routes
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::post('/student/notifications/read', [StudentController::class, 'markNotificationsRead'])->name('student.notifications.read');
    Route::get('/student/certificates/{id}/download', [StudentController::class, 'downloadCertificate'])->name('student.certificate.download');
    Route::post('/student/profile/update', [StudentController::class, 'updateProfile'])->name('student.profile.update');

    Route::post('/events/{id}/register', [RegistrationController::class, 'store'])->name('events.register');
    Route::post('/registrations/{id}/cancel', [RegistrationController::class, 'cancel'])->name('events.register.cancel');
    Route::post('/registrations/{id}/pay-fee', [RegistrationController::class, 'payFee'])->name('events.register.payFee');

    Route::post('/events/{id}/bookmark', [EventController::class, 'toggleBookmark'])->name('events.bookmark');
    Route::post('/events/{id}/feedback', [FeedbackController::class, 'store'])->name('events.feedback');
    Route::post('/gallery/{id}/favorite', [MediaController::class, 'toggleFavorite'])->name('gallery.favorite');
});

// 4. Organizer (Faculty Staff) Routes
Route::middleware(['auth', 'role:organizer'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/dashboard', [OrganizerController::class, 'dashboard'])->name('dashboard');
    Route::get('/events/create', [OrganizerController::class, 'createEvent'])->name('events.create');
    Route::post('/events/store', [OrganizerController::class, 'storeEvent'])->name('events.store');
    Route::get('/events/{id}/edit', [OrganizerController::class, 'editEvent'])->name('events.edit');
    Route::post('/events/{id}/update', [OrganizerController::class, 'updateEvent'])->name('events.update');
    Route::get('/events/{id}/registrations', [OrganizerController::class, 'manageRegistrations'])->name('events.registrations');
    Route::get('/events/{id}/scanner', [OrganizerController::class, 'showScanner'])->name('events.scanner');
    Route::post('/events/{id}/verify', [OrganizerController::class, 'verifyAttendance'])->name('events.verify');
    Route::post('/events/{id}/certificates/issue', [OrganizerController::class, 'issueCertificates'])->name('events.certificates.issue');
    Route::post('/events/{id}/announcement', [OrganizerController::class, 'sendAnnouncement'])->name('events.announcement');
});

// Media upload accessible by both organizer and admin
Route::middleware(['auth'])->post('/gallery/upload', [MediaController::class, 'upload'])->name('gallery.upload');

// 5. Admin (System Administrator) Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/events/pending', [AdminController::class, 'pendingEvents'])->name('events.pending');
    Route::post('/events/{id}/approve', [AdminController::class, 'approveEvent'])->name('events.approve');
    Route::post('/events/{id}/reject', [AdminController::class, 'rejectEvent'])->name('events.reject');
    
    Route::get('/users', [AdminController::class, 'manageUsers'])->name('users');
    Route::post('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
    Route::post('/users/{id}/status', [AdminController::class, 'toggleUserStatus'])->name('users.status');
    Route::post('/users/{id}/password', [AdminController::class, 'resetUserPassword'])->name('users.password');

    Route::get('/content', [AdminController::class, 'contentModeration'])->name('content');
    Route::post('/content/feedback/{id}/delete', [AdminController::class, 'deleteFeedback'])->name('content.feedback.delete');
    Route::post('/content/media/{id}/delete', [AdminController::class, 'deleteMedia'])->name('content.media.delete');

    Route::post('/announcements/send', [AdminController::class, 'sendAnnouncement'])->name('announcements.send');
    Route::get('/reports/export/{type}', [AdminController::class, 'exportReport'])->name('reports.export');
});
