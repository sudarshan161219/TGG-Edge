<?php

use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\ProjectCollaborationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResearcherDashboardController;
use App\Http\Controllers\User\KnowledgeResearchController;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\User\ResearchAssistanceController;
use App\Http\Controllers\User\UserApprovalController;
use App\Http\Controllers\VolunteerDashboardController;
use Illuminate\Support\Facades\Route;

// Public routes (login and register)
Route::middleware('web')->prefix('user')->name('user.')->group(function () {
    // Public login routes
    Route::get('/login', [LoginController::class, 'show'])->name('show');
    Route::post('/login', [LoginController::class, 'login'])->name('login');

    // Public registration routes
    Route::prefix('register')->name('register.')->group(function () {
        Route::get('{user_type}', [RegisterController::class, 'show'])->name('show');
        Route::post('{user_type}', [RegisterController::class, 'store'])->name('store');
    });
});

// Protected routes (require login)
Route::middleware(['web', 'auth'])->prefix('user')->name('user.')->group(function () {


    // Route::get('/dashboard', function () {
    //     return view('user.dashboard');
    // })->name('dashboard');

    Route::get('/dashboard', function () {
    // Force login as the first user in the DB so the 'auth' middleware passes
    auth()->loginUsingId(1); 
    
    return view('user.dashboard');
})->name('dashboard');

    Route::get('/admin-dashboard', function () {
        return view('user.admin-dashboard');
    })->name('admin-dashboard');


    // researcher start
    Route::get('/researcher-dashboard', [ResearcherDashboardController::class, 'index'])->name('researcher-dashboard');

    Route::post('/project', [ProjectController::class, 'store'])->name('project.store');
    Route::post('/project-progress/update', [ProjectController::class, 'progressUpdate'])->name('project-progress.update');

    // end

    //volunteer start
    Route::get('/volunteer-dashboard', [VolunteerDashboardController::class, 'index'])->name('volunteer-dashboard');
    
    Route::post('/project-collaboration/apply', [ProjectCollaborationController::class, 'apply'])->name('project-collaboration.apply');
    Route::post('/project-collaboration/progress/update', [ProjectCollaborationController::class, 'progressUpdate'])->name('project-collaboration.progress.update');
    Route::post('/project-collaboration/progress/application/accept-reject', [ProjectCollaborationController::class, 'applicationAcceptReject'])->name('project-collaboration.application/accept-reject');
    Route::post('/project-collaboration-progress/update', [ProjectCollaborationController::class, 'researcherProgressUpdate'])->name('project-collaboration-progress.update');
    //end


    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::prefix('research-assistance')->name('research-assistance.')->group(function () {
        Route::get('/literature', [ResearchAssistanceController::class, 'literature'])->name('literature');
        Route::get('/videos', [ResearchAssistanceController::class, 'videos'])->name('videos');
        Route::get('/links', [ResearchAssistanceController::class, 'links'])->name('links');
        Route::get('/linkedin', [ResearchAssistanceController::class, 'linkedin'])->name('linkedin');
    });

    Route::get('/knowledge-research', [KnowledgeResearchController::class, 'knowledgeAndResearch'])->name('knowledge-research.index');
    Route::post('/search-knowledge', [KnowledgeResearchController::class, 'searchKnowledge'])->name('knowledge-research.search-knowledge');

    // application start
    Route::get('/new-applications', [UserApprovalController::class, 'newApplication'])->name('new-applications');
    Route::get('/processed-applications', [UserApprovalController::class, 'processedApplication'])->name('processed-applications');

    Route::get('/user-profile/{id}', [UserApprovalController::class, 'userProfile'])->name('user-profile');
    Route::post('/users/{id}/userProfileUpdate', [UserApprovalController::class, 'userProfileUpdate'])->name('users.profile.update');
    Route::get('/users/{id}/approval', [UserApprovalController::class, 'updateApproval'])->name('users.update.approval');
    Route::post('/users/{id}/project', [UserApprovalController::class, 'updateProject'])->name('users.update.project');
    // end


    // application start
    Route::get('/researcher-projects', [ProjectController::class, 'researcherProject'])->name('researcher-projects');
    Route::get('/volunteer-projects', [ProjectController::class, 'volunteerProject'])->name('volunteer-projects');
    Route::get('/researcher-project/freezed/{id}', [ProjectController::class, 'researcherFreezeProject'])->name('researcher-project.freezed');
    Route::get('/volunteer-project/freezed/{id}', [ProjectController::class, 'volunteerFreezeProject'])->name('volunteer-project.freezed');
    
    // end

});
