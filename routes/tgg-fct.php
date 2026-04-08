<?php

use App\Http\Controllers\ProjectCollaborationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResearcherDashboardController;
use App\Http\Controllers\TggFct\Researcher\DataController;
use App\Http\Controllers\User\KnowledgeResearchController;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\User\ResearchAssistanceController;
use App\Http\Controllers\User\UserApprovalController;
use App\Http\Controllers\VolunteerDashboardController;
// use UniSharp\LaravelFilemanager\Lfm;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->prefix('tgg-edge/tgg-fct')->name('tgg-fct.')->group(function () {

    Route::get('/login', [LoginController::class, 'show'])->name('show');
    Route::post('/login', [LoginController::class, 'login'])->name('login');


    // Public registration routes
    Route::prefix('register')->name('register.')->group(function () {
        Route::get('{user_type}', [RegisterController::class, 'show'])->name('show');
        Route::post('{user_type}', [RegisterController::class, 'store'])->name('store');
    });

    // admin routes 
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', function () {
            return view('tgg-fct.admin.dashboard');
        })->name('dashboard');

        Route::get('/profile', [\App\Http\Controllers\TggFct\Admin\ProfileController::class, 'show'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\TggFct\Admin\ProfileController::class, 'update'])->name('profile.update');


        // application start
        Route::get('/new-applications', [\App\Http\Controllers\TggFct\Admin\ApplicationController::class, 'newApplication'])->name('new-applications');
        Route::get('/processed-applications', [\App\Http\Controllers\TggFct\Admin\ApplicationController::class, 'processedApplication'])->name('processed-applications');

        Route::get('/user-profile/{id}', [\App\Http\Controllers\TggFct\Admin\ApplicationController::class, 'userProfile'])->name('user-profile');
        Route::post('/users/{id}/userProfileUpdate', [\App\Http\Controllers\TggFct\Admin\ApplicationController::class, 'userProfileUpdate'])->name('users.profile.update');
        Route::get('/users/{id}/approval', [\App\Http\Controllers\TggFct\Admin\ApplicationController::class, 'updateApproval'])->name('users.update.approval');
        Route::post('/users/{id}/project', [\App\Http\Controllers\TggFct\Admin\ApplicationController::class, 'updateProject'])->name('users.update.project');
        // end

        // application start
        Route::get('/researcher-projects', [ProjectController::class, 'researcherProject'])->name('researcher-projects');
        Route::get('/volunteer-projects', [ProjectController::class, 'volunteerProject'])->name('volunteer-projects');
        Route::get('/researcher-project/freezed/{id}', [ProjectController::class, 'researcherFreezeProject'])->name('researcher-project.freezed');
        Route::get('/volunteer-project/freezed/{id}', [ProjectController::class, 'volunteerFreezeProject'])->name('volunteer-project.freezed');
        // end

        // trainer routes 
        Route::resource('assignments', \App\Http\Controllers\TggFct\Admin\AssignmentController::class);

        Route::get('/knowledge-research', [\App\Http\Controllers\TggFct\Admin\KnowledgeResearchController::class, 'knowledgeAndResearch'])->name('knowledge-research.index');
    });

    Route::middleware('assignee')->prefix('assignee')->name('assignee.')->group(function () {
        Route::get('/dashboard', function () {
            return view('tgg-fct.assignee.dashboard');
        })->name('dashboard');
        Route::resource('assignments', \App\Http\Controllers\TggFct\Assignee\AssignmentController::class);
        Route::get('/profile', [\App\Http\Controllers\TggFct\Admin\ProfileController::class, 'show'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\TggFct\Admin\ProfileController::class, 'update'])->name('profile.update');

        Route::prefix('research-assistance')->name('research-assistance.')->group(function () {
        Route::get('/literature', [\App\Http\Controllers\TggFct\Assignee\ResearchAssistanceController::class, 'literature'])->name('literature');
        Route::get('/videos', [\App\Http\Controllers\TggFct\Assignee\ResearchAssistanceController::class, 'videos'])->name('videos');
        Route::get('/links', [\App\Http\Controllers\TggFct\Assignee\ResearchAssistanceController::class, 'links'])->name('links');
        Route::get('/linkedin', [\App\Http\Controllers\TggFct\Assignee\ResearchAssistanceController::class, 'linkedin'])->name('linkedin');
        });

        Route::get('/knowledge-research', [\App\Http\Controllers\TggFct\Assignee\KnowledgeResearchController::class, 'knowledgeAndResearch'])->name('knowledge-research.index');
            // end
        
    });


    Route::middleware('researcher')->prefix('researcher')->name('researcher.')->group(function () {

        // Route::get('/dashboard', function () {
        //     return view('tgg-fct.researcher.dashboard');
        // })->name('dashboard');
        Route::get('/dashboard', [\App\Http\Controllers\TggFct\Researcher\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [\App\Http\Controllers\TggFct\Researcher\ProfileController::class, 'show'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\TggFct\Researcher\ProfileController::class, 'update'])->name('profile.update');

        Route::post('/project', [\App\Http\Controllers\TggFct\Researcher\ProjectController::class, 'store'])->name('project.store');
        Route::post('/project-progress/update', [\App\Http\Controllers\TggFct\Researcher\ProjectController::class, 'progressUpdate'])->name('project-progress.update');
        Route::post('/project-collaboration/apply', [\App\Http\Controllers\TggFct\Researcher\ProjectCollaborationController::class, 'apply'])->name('project-collaboration.apply');
        Route::post('/project-collaboration/progress/update', [\App\Http\Controllers\TggFct\Researcher\ProjectCollaborationController::class, 'progressUpdate'])->name('project-collaboration.progress.update');
        Route::post('/project-collaboration/progress/application/accept-reject', [\App\Http\Controllers\TggFct\Researcher\ProjectCollaborationController::class, 'applicationAcceptReject'])->name('project-collaboration.application/accept-reject');
        Route::post('/project-collaboration-progress/update', [\App\Http\Controllers\TggFct\Researcher\ProjectCollaborationController::class, 'researcherProgressUpdate'])->name('project-collaboration-progress.update');

       
        Route::prefix('research-assistance')->name('research-assistance.')->group(function () {
        Route::get('/literature', [\App\Http\Controllers\TggFct\Researcher\ResearchAssistanceController::class, 'literature'])->name('literature');
        Route::get('/videos', [\App\Http\Controllers\TggFct\Researcher\ResearchAssistanceController::class, 'videos'])->name('videos');
        Route::get('/links', [\App\Http\Controllers\TggFct\Researcher\ResearchAssistanceController::class, 'links'])->name('links');
        Route::get('/linkedin', [\App\Http\Controllers\TggFct\Researcher\ResearchAssistanceController::class, 'linkedin'])->name('linkedin');
         });

        Route::get('/knowledge-research', [\App\Http\Controllers\TggFct\Researcher\KnowledgeResearchController::class, 'knowledgeAndResearch'])->name('knowledge-research.index');

        Route::prefix('ai-tools-research')->name('ai-tools-research.')->group(function () {
           Route::get('/research-material-organizer', function(){
            return view('tgg-fct.researcher.ai-tools-research.research-material-organizer');
           })->name('research-material-organizer');

           Route::get('/kanban-note-board', function(){
            return view('tgg-fct.researcher.ai-tools-research.kanban-note-board');
           })->name('kanban-note-board');

           Route::get('visualization/analytics', [DataController::class, 'showUploadForm'])->name('visualization-analytics.form');
           Route::post('visualization/analytics', [DataController::class, 'upload'])->name('visualization-analytics.upload');
        });
        // end
    });


    Route::middleware('volunteer')->prefix('volunteer')->name('volunteer.')->group(function () {

        // Route::get('/dashboard', function () {
        //     return view('tgg-fct.researcher.dashboard');
        // })->name('dashboard');
        Route::get('/dashboard', [\App\Http\Controllers\TggFct\Volunteer\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [\App\Http\Controllers\TggFct\Volunteer\ProfileController::class, 'show'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\TggFct\Volunteer\ProfileController::class, 'update'])->name('profile.update');

        Route::post('/project', [\App\Http\Controllers\TggFct\Volunteer\ProjectController::class, 'store'])->name('project.store');
        Route::post('/project-progress/update', [\App\Http\Controllers\TggFct\Volunteer\ProjectController::class, 'progressUpdate'])->name('project-progress.update');
        Route::post('/project-collaboration/apply', [\App\Http\Controllers\TggFct\Volunteer\ProjectCollaborationController::class, 'apply'])->name('project-collaboration.apply');
        Route::post('/project-collaboration/progress/update', [\App\Http\Controllers\TggFct\Volunteer\ProjectCollaborationController::class, 'progressUpdate'])->name('project-collaboration.progress.update');
        Route::post('/project-collaboration/progress/application/accept-reject', [\App\Http\Controllers\TggFct\Volunteer\ProjectCollaborationController::class, 'applicationAcceptReject'])->name('project-collaboration.application/accept-reject');
        Route::post('/project-collaboration-progress/update', [\App\Http\Controllers\TggFct\Volunteer\ProjectCollaborationController::class, 'researcherProgressUpdate'])->name('project-collaboration-progress.update');

        Route::get('/knowledge-research', [\App\Http\Controllers\TggFct\Volunteer\KnowledgeResearchController::class, 'knowledgeAndResearch'])->name('knowledge-research.index');
        // end
    });




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


    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
