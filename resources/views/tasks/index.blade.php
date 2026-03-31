<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Assignments & Tasks') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-xs font-bold text-gray-500 hover:text-purple-600 transition flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Tasks</p>
                    <p class="text-2xl font-black text-gray-800">{{ $tasks->count() }}</p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-[10px] font-black text-green-400 uppercase tracking-widest">Approved</p>
                    <p class="text-2xl font-black text-green-600">{{ $tasks->filter(fn($t) => $t->submissions->where('user_id', auth()->id())->where('status', 'approved')->count())->count() }}</p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-[10px] font-black text-orange-400 uppercase tracking-widest">Pending</p>
                    <p class="text-2xl font-black text-orange-500">{{ $tasks->filter(fn($t) => $t->submissions->where('user_id', auth()->id())->where('status', 'pending')->count())->count() }}</p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-[10px] font-black text-red-400 uppercase tracking-widest">To Do/Rejected</p>
                    <p class="text-2xl font-black text-red-500">{{ $tasks->count() - $tasks->filter(fn($t) => $t->submissions->where('user_id', auth()->id())->where('status', 'approved')->count())->count() }}</p>
                </div>
            </div>

            <div class="max-w-4xl mx-auto">
                @if(session('success'))
                    <div id="success-alert" class="mb-6 p-4 bg-green-600 text-white rounded-2xl font-bold shadow-lg flex justify-between items-center">
                        <span>✓ {{ session('success') }}</span>
                        <button onclick="document.getElementById('success-alert').remove()" class="text-white opacity-50 hover:opacity-100">×</button>
                    </div>
                @endif

                @forelse($tasks as $task)
                    @php 
                        $submission = $task->submissions->where('user_id', auth()->id())->first(); 
                    @endphp
                    
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8 transition hover:shadow-md">
                        <div class="p-6 bg-gray-50 border-b flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-black text-gray-800">{{ $task->title }}</h3>
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">
                                    Assigned: {{ $task->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            
                            @if($submission)
                                <span class="px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border
                                    {{ $submission->status == 'approved' ? 'bg-green-50 text-green-700 border-green-200' : ($submission->status == 'rejected' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-orange-50 text-orange-700 border-orange-200') }}">
                                    {{ $submission->status }}
                                </span>
                            @else
                                <span class="px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100 animate-pulse">New Task</span>
                            @endif
                        </div>

                        <div class="p-8 border-b border-gray-50 bg-white">
                            <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-4">Task Details:</p>
                            <div class="prose max-w-none text-gray-700 leading-relaxed">
                                {!! $task->description !!}
                            </div>
                        </div>

                        <div class="p-8 bg-gray-50/30">
                            @if(!$submission || $submission->status == 'rejected')
                                <form action="{{ route('tasks.submit', $task->id) }}" method="POST">
                                    @csrf
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Submit Your Work</label>
                                    <textarea name="submission_text" id="student-editor-{{ $task->id }}" required rows="5" 
                                              class="w-full border-gray-200 rounded-2xl focus:ring-purple-500 focus:border-purple-500 shadow-sm"
                                              placeholder="Describe your work or provide GitHub/Project links...">{{ $submission ? $submission->submission_text : '' }}</textarea>
                                    
                                    <button type="submit" class="mt-6 w-full bg-purple-600 hover:bg-purple-700 text-white py-4 rounded-2xl font-bold shadow-lg transition transform hover:-translate-y-1">
                                        {{ $submission ? 'Update & Re-submit Assignment' : 'Confirm & Finalize Submission' }}
                                    </button>
                                </form>
                            @else
                                <div class="bg-indigo-50 p-6 rounded-2xl border border-indigo-100">
                                    <div class="flex items-center text-indigo-400 mb-3">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>
                                        <p class="text-xs font-black uppercase tracking-widest">Submission Locked</p>
                                    </div>
                                    <p class="text-indigo-900 italic mb-4 leading-relaxed">"{{ $submission->submission_text }}"</p>
                                    
                                    @if($submission->admin_feedback)
                                        <div class="mt-4 pt-4 border-t border-indigo-200">
                                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">Instructor Feedback:</p>
                                            <div class="p-3 bg-white/50 rounded-xl text-sm font-bold text-gray-800 border border-indigo-100">
                                                {{ $submission->admin_feedback }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-20 rounded-3xl shadow-sm text-center border border-gray-100">
                        <div class="flex justify-center mb-4">
                            <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <p class="text-gray-400 font-bold uppercase tracking-widest">Everything is up to date!</p>
                        <p class="text-xs text-gray-300 mt-1">No tasks have been assigned to your account yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>