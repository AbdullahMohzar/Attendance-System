<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /** * ADMIN: Show Task Creation Page 
     */
    public function index()
    {
        $tasks = Task::latest()->get();
        return view('admin.tasks.index', compact('tasks'));
    }

    /** * ADMIN: Store New Task & Notify Students
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $task = Task::create($request->all());

        // WhatsApp Trigger: Notify all students about the new task
        $students = User::where('role', 'student')->whereNotNull('phone')->get();
        
        foreach($students as $student) {
            WhatsAppService::send(
                $student->phone, 
                "📝 *New Task Assigned*\n\n*Title:* {$task->title}\n\nHello {$student->name}, a new task has been assigned. Please check your dashboard to view details and submit your work."
            );
        }

        return redirect()->back()->with('success', 'Task assigned and notifications sent!');
    }

    /** * ADMIN: Delete Task 
     */
    public function destroy($id)
    {
        Task::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Task deleted.');
    }

    /** * ADMIN: View All Student Submissions
     */
    public function viewSubmissions()
    {
        $submissions = TaskSubmission::with(['task', 'user'])->latest()->get();
        return view('admin.tasks.submissions', compact('submissions'));
    }

    /** * ADMIN: Approve or Reject a Submission & Notify Student
     */
    public function reviewSubmission(Request $request, $id)
    {
        $submission = TaskSubmission::with(['task', 'user'])->findOrFail($id);
        
        $submission->update([
            'status' => $request->status, // 'approved' or 'rejected'
            'admin_feedback' => $request->feedback
        ]);

        // WhatsApp Trigger: Notify the student of the result
        if ($submission->user && $submission->user->phone) {
            $statusEmoji = $request->status == 'approved' ? '✅' : '❌';
            $statusText = strtoupper($request->status);
            $feedback = $request->feedback ? "\n\n*Feedback:* " . $request->feedback : "\n\nNo specific feedback provided.";
            
            WhatsAppService::send(
                $submission->user->phone, 
                "{$statusEmoji} *Task Review Update*\n\nYour submission for *'{$submission->task->title}'* has been *{$statusText}*." . $feedback
            );
        }

        return redirect()->back()->with('success', 'Submission reviewed and student notified.');
    }

    /** * STUDENT: View Tasks Assigned to them 
     */
    public function studentIndex()
    {
        $tasks = Task::with(['submissions' => function($q) {
            $q->where('user_id', auth()->id());
        }])->latest()->get();

        return view('tasks.index', compact('tasks'));
    }

    /** * STUDENT: Submit Task Response 
     */
    public function submitTask(Request $request, $id)
    {
        $request->validate(['submission_text' => 'required']);

        $user = auth()->user();
        $task = Task::findOrFail($id);

        $submission = TaskSubmission::updateOrCreate(
            ['task_id' => $id, 'user_id' => $user->id],
            [
                'submission_text' => $request->submission_text,
                'status' => 'pending',
                'admin_feedback' => null
            ]
        );

        // NEW WhatsApp Trigger: Notify the Admin that a student submitted a task
        $admin = User::where('role', 'admin')->first();
        if ($admin && $admin->phone) {
            WhatsAppService::send(
                $admin->phone, 
                "📩 *New Task Submission*\n\n*Student:* {$user->name}\n*Task:* {$task->title}\n\nPlease log in to review the submission."
            );
        }

        return redirect()->back()->with('success', 'Task submitted successfully! Admin has been notified.');
    }
}