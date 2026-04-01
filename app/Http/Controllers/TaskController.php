<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    /** * ADMIN/TEACHER: Show Task Creation Page 
     */
    public function index()
    {
        $tasks = Task::latest()->get();
        return view('admin.tasks.index', compact('tasks'));
    }

    /** * ADMIN/TEACHER: Store New Task & Notify Students
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'due_date' => 'required|date|after:' . now()->subMinutes(2)->toDateTimeString(), 
            'task_attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $dueDate = Carbon::parse($request->due_date);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $dueDate,
            'task_attachment' => $request->hasFile('task_attachment') 
                ? $request->file('task_attachment')->store('task_instructions', 'public') 
                : null,
        ]);

        $students = User::where('role', 'student')->whereNotNull('phone')->get();
        $deadlineStr = $task->due_date->format('M d, Y h:i A');
        
        foreach($students as $student) {
            WhatsAppService::send(
                $student->phone, 
                "📝 *New Task Assigned*\n\n*Title:* {$task->title}\n*Deadline:* {$deadlineStr}\n\nHello {$student->name}, a new task has been assigned. Please complete it before the deadline."
            );
        }

        return redirect()->back()->with('success', 'Task assigned and notifications sent!');
    }

    /** * ADMIN/TEACHER: Extend Deadline & Notify Students
     */
    public function extendDeadline(Request $request, $id)
    {
        $request->validate([
            'due_date' => 'required|date|after:' . now()->subMinutes(2)->toDateTimeString(),
        ]);

        $task = Task::findOrFail($id);
        
        $task->update([
            'due_date' => Carbon::parse($request->due_date)
        ]);

        $newDeadline = $task->due_date->format('M d, Y h:i A');

        // WHATSAPP TRIGGER: Notify students of the extension
        $students = User::where('role', 'student')->whereNotNull('phone')->get();
        foreach($students as $student) {
            WhatsAppService::send(
                $student->phone, 
                "⏳ *Deadline Extended!*\n\n*Task:* {$task->title}\n*New Deadline:* {$newDeadline}\n\nGood news {$student->name}, you have more time to complete your assignment. Please ensure you submit before the new deadline."
            );
        }

        return redirect()->back()->with('success', 'Deadline extended until ' . $task->due_date->format('M d, h:i A') . ' and students notified!');
    }

    /** * ADMIN/TEACHER: Delete Task
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        if ($task->task_attachment && Storage::disk('public')->exists($task->task_attachment)) {
            Storage::disk('public')->delete($task->task_attachment);
        }

        foreach ($task->submissions as $submission) {
            if ($submission->attachment && Storage::disk('public')->exists($submission->attachment)) {
                Storage::disk('public')->delete($submission->attachment);
            }
        }

        $task->delete();
        return redirect()->back()->with('success', 'Task and all related submissions deleted.');
    }

    /** * STUDENT: View Tasks 
     */
    public function studentIndex()
    {
        $tasks = Task::with(['submissions' => function($q) {
            $q->where('user_id', Auth::id());
        }])->latest()->get();

        return view('tasks.index', compact('tasks'));
    }

    /** * STUDENT: Submit Task (Security Checked for Due Date)
     */
    public function submitTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        if ($task->due_date && $task->due_date->isPast()) {
            return redirect()->back()->with('error', 'The deadline for this task has passed.');
        }

        $request->validate([
            'submission_text' => 'required',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $user = Auth::user();
        $submission = TaskSubmission::where('task_id', $id)->where('user_id', $user->id)->first();
        $attachmentPath = $submission ? $submission->attachment : null;

        if ($request->hasFile('attachment')) {
            if ($attachmentPath && Storage::disk('public')->exists($attachmentPath)) {
                Storage::disk('public')->delete($attachmentPath);
            }
            $attachmentPath = $request->file('attachment')->store('submissions', 'public');
        }

        TaskSubmission::updateOrCreate(
            ['task_id' => $id, 'user_id' => $user->id],
            [
                'submission_text' => $request->submission_text,
                'attachment' => $attachmentPath,
                'status' => 'pending',
                'admin_feedback' => null
            ]
        );

        return redirect()->back()->with('success', 'Task submitted successfully!');
    }

    /** * ADMIN/TEACHER: View Student Submissions
     */
    public function viewSubmissions()
    {
        $submissions = TaskSubmission::with(['task', 'user'])->latest()->get();
        return view('admin.tasks.submissions', compact('submissions'));
    }

    /** * ADMIN/TEACHER: Review Submission
     */
    public function reviewSubmission(Request $request, $id)
    {
        $submission = TaskSubmission::with(['task', 'user'])->findOrFail($id);
        $submission->update(['status' => $request->status, 'admin_feedback' => $request->feedback]);

        if ($submission->user && $submission->user->phone) {
            $statusEmoji = $request->status == 'approved' ? '✅' : '❌';
            WhatsAppService::send(
                $submission->user->phone, 
                "{$statusEmoji} *Task Review Update*\n\nYour submission for *'{$submission->task->title}'* has been *".strtoupper($request->status)."*."
            );
        }
        return redirect()->back()->with('success', 'Submission reviewed.');
    }

    /** * SHARED: Download Attachment
     */
    public function downloadAttachment($id)
    {
        $submission = TaskSubmission::findOrFail($id);
        if (!$submission->attachment || !Storage::disk('public')->exists($submission->attachment)) {
            return back()->with('error', 'File not found.');
        }
        return Storage::disk('public')->download($submission->attachment);
    }
}