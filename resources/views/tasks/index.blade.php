<x-app-layout>
    <div class="py-12 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @php
                $totalTasks = $tasks->count();
                $completed = 0;
                $awaitingReview = 0;
                $rejected = 0;
                $pendingAction = 0;
                $missed = 0;

                foreach($tasks as $t) {
                    $sub = $t->submissions->where('user_id', auth()->id())->first();
                    $isExp = $t->due_date && $t->due_date->isPast();

                    if ($sub) {
                        if ($sub->status == 'approved') $completed++;
                        elseif ($sub->status == 'rejected') $rejected++;
                        else $awaitingReview++;
                    } else {
                        if ($isExp) $missed++;
                        else $pendingAction++;
                    }
                }
            @endphp

            <div class="max-w-4xl mx-auto mb-10">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="bg-white p-4 rounded-[2rem] border border-gray-100 shadow-sm text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total</p>
                        <p class="text-2xl font-black text-gray-900">{{ $totalTasks }}</p>
                    </div>
                    <div class="bg-emerald-50 p-4 rounded-[2rem] border border-emerald-100 shadow-sm text-center">
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">Done</p>
                        <p class="text-2xl font-black text-emerald-700">{{ $completed }}</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-[2rem] border border-blue-100 shadow-sm text-center">
                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">Review</p>
                        <p class="text-2xl font-black text-blue-700">{{ $awaitingReview }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-[2rem] border border-purple-100 shadow-sm text-center">
                        <p class="text-[10px] font-black text-purple-600 uppercase tracking-widest mb-1">Pending</p>
                        <p class="text-2xl font-black text-purple-700">{{ $pendingAction }}</p>
                    </div>
                    <div class="bg-rose-50 p-4 rounded-[2rem] border border-rose-100 shadow-sm text-center col-span-2 md:col-span-1">
                        <p class="text-[10px] font-black text-rose-600 uppercase tracking-widest mb-1">Alerts</p>
                        <p class="text-2xl font-black text-rose-700">{{ $rejected + $missed }}</p>
                    </div>
                </div>
            </div>

            <div class="max-w-4xl mx-auto">
                @forelse($tasks as $task)
                    @php 
                        $submission = $task->submissions->where('user_id', auth()->id())->first(); 
                        $isExpired = $task->due_date && $task->due_date->isPast();
                    @endphp
                    
                    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-purple-200/40 border border-gray-100 overflow-hidden mb-12 transition-all duration-300 hover:shadow-purple-300/30">
                        <div class="p-8 bg-gradient-to-r from-white via-purple-50/30 to-white border-b border-gray-100 flex justify-between items-center">
                            <div class="flex items-center space-x-5">
                                <div class="w-14 h-14 bg-purple-600 rounded-2xl flex items-center justify-center text-white text-xl font-black shadow-lg shadow-purple-200">
                                    {{ $loop->iteration }}
                                </div>
                                <div>
                                    <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ $task->title }}</h3>
                                    <div class="flex items-center mt-1 space-x-3">
                                        <p class="text-[10px] uppercase font-black tracking-widest {{ $isExpired ? 'text-red-500' : 'text-purple-400' }}">
                                            @if($task->due_date)
                                                Deadline • {{ $task->due_date->format('M d, h:i A') }}
                                                <span class="ml-1 opacity-70">({{ $isExpired ? 'Ended ' . $task->due_date->diffForHumans() : 'Ends ' . $task->due_date->diffForHumans() }})</span>
                                            @else
                                                <span class="text-gray-400 italic">No Deadline Set</span>
                                            @endif
                                        </p>
                                        @if($task->due_date)
                                            <span class="{{ $isExpired ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }} text-[8px] font-black px-2 py-0.5 rounded-full uppercase tracking-tighter">
                                                {{ $isExpired ? 'Expired' : 'Active' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            @if($submission)
                                <div class="text-right">
                                    <span class="px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-[0.15em] shadow-sm border
                                        {{ $submission->status == 'approved' ? 'bg-green-500 text-white border-green-400' : ($submission->status == 'rejected' ? 'bg-red-500 text-white border-red-400' : 'bg-amber-400 text-white border-amber-300') }}">
                                        {{ $submission->status }}
                                    </span>
                                </div>
                            @else
                                <span class="px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-[0.15em] bg-indigo-600 text-white {{ $isExpired ? 'bg-gray-400' : 'animate-pulse' }}">
                                    {{ $isExpired ? 'Submission Closed' : 'Pending Action' }}
                                </span>
                            @endif
                        </div>

                        <div class="p-10 border-b border-gray-50 bg-white">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-6">Assignment Brief</h4>
                            <div class="prose prose-purple prose-lg max-w-none text-gray-700 leading-relaxed mb-8">
                                {!! $task->description !!}
                            </div>

                            @if($task->task_attachment)
                                <div class="p-6 bg-indigo-50/50 rounded-[2rem] border border-indigo-100 flex items-center justify-between group hover:bg-indigo-50 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm mr-4 group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-width="2"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Reference Material</p>
                                            <p class="text-xs font-bold text-indigo-900 leading-tight">Instructions from Teacher</p>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($task->task_attachment) }}" target="_blank" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">View File</a>
                                </div>
                            @endif
                        </div>

                        <div class="p-10 bg-gray-50/80">
                            @if($isExpired && (!$submission || $submission->status == 'rejected'))
                                <div class="bg-white p-12 rounded-[2.5rem] border border-red-100 text-center shadow-inner">
                                    <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m0 0v2m0-2h2m-2 0H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"></path></svg>
                                    </div>
                                    <p class="text-red-900 font-black uppercase tracking-[0.2em] text-xs">Submission Window Closed</p>
                                    <p class="text-gray-400 text-[10px] mt-2 font-bold italic uppercase tracking-widest">The deadline has passed. Late submissions are not accepted.</p>
                                </div>
                            @elseif(!$submission || $submission->status == 'rejected')
                                <form action="{{ route('tasks.submit', $task->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                                    @csrf
                                    <div class="relative">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Your Submission Details</label>
                                        <div class="rounded-[2rem] overflow-hidden border border-gray-100 shadow-inner bg-white">
                                            <textarea name="submission_text" id="editor-{{ $task->id }}" class="editor-instance">{{ $submission ? $submission->submission_text : '' }}</textarea>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">File Attachment</label>
                                        @if($submission && $submission->attachment)
                                            <div class="flex items-center p-4 bg-white rounded-2xl border border-purple-100 mb-4 shadow-sm">
                                                <svg class="w-8 h-8 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2"></path></svg>
                                                <div class="flex-1">
                                                    <p class="text-xs font-bold text-gray-800 uppercase tracking-tighter">Current File</p>
                                                    <a href="{{ Storage::url($submission->attachment) }}" target="_blank" class="text-[10px] text-purple-600 hover:underline font-black uppercase">Click to preview</a>
                                                </div>
                                            </div>
                                        @endif
                                        <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-[2rem] cursor-pointer bg-white hover:bg-purple-50 transition-all group">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <div class="p-4 bg-purple-100 rounded-full mb-3 group-hover:scale-110 transition-transform">
                                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" stroke-width="2"></path></svg>
                                                </div>
                                                <p class="text-xs font-black text-gray-600 uppercase">Drop files or click to browse</p>
                                            </div>
                                            <input type="file" name="attachment" class="hidden" accept=".pdf,image/*" onchange="updateFileName(this, 'file-name-{{ $task->id }}')" />
                                        </label>
                                        <div id="file-name-{{ $task->id }}" class="mt-2 text-center text-[10px] font-black text-purple-600 uppercase tracking-widest"></div>
                                    </div>
                                    <button type="submit" class="w-full bg-gray-900 hover:bg-purple-600 text-white py-6 rounded-[2rem] font-black uppercase tracking-[0.2em] shadow-xl duration-300 hover:-translate-y-1 active:scale-95">
                                        🚀 Finalize & Submit Work
                                    </button>
                                </form>
                            @else
                                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-inner">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Archive Submission History</p>
                                    <div class="prose prose-purple prose-sm max-w-none text-gray-600 italic mb-8 p-8 bg-gray-50/50 rounded-[2rem] border border-gray-100 shadow-sm leading-relaxed">
                                        {!! $submission->submission_text !!}
                                    </div>
                                    @if($submission->attachment)
                                        <div class="flex items-center justify-between p-6 bg-purple-50 rounded-[2rem] border border-purple-100 transition hover:bg-purple-100/50">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-purple-600 shadow-sm mr-4">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2"></path></svg>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] font-black text-purple-400 uppercase tracking-widest">My Uploaded Asset</p>
                                                    <p class="text-xs font-bold text-purple-900 tracking-tight">Cloud Archive Verified</p>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($submission->attachment) }}" target="_blank" class="px-8 py-3 bg-white text-purple-600 rounded-2xl text-[10px] font-black uppercase tracking-widest border border-purple-200 hover:bg-purple-600 hover:text-white transition shadow-sm">Open My File</a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-white rounded-[3rem] shadow-sm border border-gray-50">
                        <p class="text-gray-400 font-black uppercase tracking-[0.3em]">No Active Assignments</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        :root {
            --ck-border-radius: 0px !important;
            --ck-color-base-border: transparent !important;
            --ck-color-toolbar-border: transparent !important;
        }
        .ck-editor__main > .ck-editor__editable { border: none !important; box-shadow: none !important; min-height: 250px; padding: 2rem !important; }
        .ck-toolbar { border: none !important; background: #ffffff !important; border-bottom: 1px solid #f1f5f9 !important; padding: 0.5rem 1rem !important; }
    </style>

    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
    <script>
        function updateFileName(input, targetId) {
            const fileName = input.files[0] ? input.files[0].name : '';
            document.getElementById(targetId).textContent = fileName ? 'Ready: ' + fileName : '';
        }

        document.querySelectorAll('.editor-instance').forEach(editorEl => {
            ClassicEditor
                .create(editorEl, {
                    toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ],
                    placeholder: 'Type your response here...'
                })
                .then(editor => {
                    editor.editing.view.change(writer => {
                        writer.setStyle('min-height', '250px', editor.editing.view.document.getRoot());
                    });
                })
                .catch(error => { console.error(error); });
        });
    </script>
</x-app-layout>