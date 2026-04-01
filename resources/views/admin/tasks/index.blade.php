<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-gray-800 tracking-tight">
                Task <span class="text-purple-600">Assignment Center</span>
            </h2>
            <a href="{{ route('admin.tasks.submissions') }}" class="bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 text-white px-6 py-2.5 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-pink-100 transition transform hover:scale-105">
                Review Student Uploads →
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div id="success-alert" class="mb-8 p-5 bg-emerald-500 text-white rounded-[2rem] font-bold shadow-xl shadow-emerald-100 flex justify-between items-center animate-in fade-in slide-in-from-top-4 duration-500">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        <span class="uppercase tracking-widest text-xs">Mission Accomplished: {{ session('success') }}</span>
                    </div>
                    <button onclick="document.getElementById('success-alert').style.display='none'" class="opacity-50 hover:opacity-100">✕</button>
                </div>
            @endif

            <div class="mb-8">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-purple-600 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Command Center
                </a>
            </div>

            <div class="bg-white p-10 rounded-[2.5rem] shadow-2xl shadow-purple-100/50 border border-gray-100 mb-12">
                <div class="flex items-center mb-8">
                    <div class="w-2 h-8 bg-purple-600 rounded-full mr-4 shadow-lg shadow-purple-200"></div>
                    <h3 class="text-xl font-black text-gray-800 uppercase tracking-widest">Post New Assignment</h3>
                </div>
                
                <form action="{{ route('admin.tasks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="md:col-span-2">
                            <label class="block font-black text-[10px] uppercase text-gray-400 tracking-widest mb-3">Task Heading</label>
                            <input type="text" name="title" placeholder="e.g., Database Design Phase 1" required 
                                   class="w-full border-gray-100 bg-gray-50 rounded-2xl py-4 px-6 focus:ring-purple-500 focus:border-purple-500 shadow-inner transition-all font-bold text-gray-700">
                        </div>

                        <div>
                            <label class="block font-black text-[10px] uppercase text-gray-400 tracking-widest mb-3">Submission Deadline</label>
                            <input type="datetime-local" name="due_date" required 
                                   value="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}"
                                   class="w-full border-gray-100 bg-gray-50 rounded-2xl py-4 px-6 focus:ring-purple-500 focus:border-purple-500 shadow-inner transition-all font-bold text-gray-700">
                        </div>
                    </div>

                    <div>
                        <label class="block font-black text-[10px] uppercase text-gray-400 tracking-widest mb-3">Reference Material (Optional PDF/Photo)</label>
                        <label class="flex items-center justify-center px-6 py-3.5 bg-white border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:bg-purple-50 hover:border-purple-300 transition-all group relative overflow-hidden">
                            <div class="flex items-center group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                <span id="file-chosen" class="text-[10px] font-black uppercase tracking-widest text-gray-400 group-hover:text-purple-600">Attach Reference Document</span>
                            </div>
                            <input type="file" name="task_attachment" class="hidden" onchange="document.getElementById('file-chosen').textContent = 'File Ready: ' + this.files[0].name; document.getElementById('file-chosen').classList.add('text-purple-600')" />
                        </label>
                    </div>

                    <div>
                        <label class="block font-black text-[10px] uppercase text-gray-400 tracking-widest mb-4">Detailed Instructions</label>
                        <div class="rounded-[2rem] overflow-hidden border border-gray-100 shadow-inner bg-white focus-within:border-purple-300 transition-all">
                            <textarea name="description" id="editor" class="editor-instance"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="w-full md:w-auto bg-gray-900 hover:bg-purple-600 text-white px-12 py-5 rounded-[2rem] font-black uppercase tracking-[0.2em] shadow-xl shadow-gray-200 transition-all transform hover:-translate-y-1 active:scale-95">
                            🚀 Publish Task to Students
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
                <div class="flex items-center mb-8">
                    <div class="w-2 h-8 bg-pink-500 rounded-full mr-4 shadow-lg shadow-pink-200"></div>
                    <h3 class="text-xl font-black text-gray-800 uppercase tracking-widest">Currently Active Tasks</h3>
                </div>
                
                @forelse($tasks as $task)
                    <div class="p-8 bg-white rounded-[2rem] border border-gray-50 flex flex-col mb-6 hover:shadow-2xl hover:shadow-purple-100 hover:scale-[1.01] transition-all duration-300 group">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="bg-gray-900 text-white text-[10px] font-black px-3 py-1 rounded-lg">{{ $loop->iteration }}</div>
                                    <h4 class="font-black text-gray-900 text-xl tracking-tight">{{ $task->title }}</h4>
                                    @if($task->task_attachment)
                                        <span class="bg-purple-50 text-purple-600 text-[8px] font-black px-3 py-1 rounded-full border border-purple-100 uppercase tracking-widest">📎 Attachment</span>
                                    @endif
                                </div>
                                <div class="text-gray-500 prose prose-sm max-w-none mb-6 font-medium leading-relaxed italic">
                                    {!! $task->description !!}
                                </div>
                                <div class="flex flex-wrap items-center gap-6">
                                    <div class="flex items-center text-[9px] text-gray-400 uppercase font-black tracking-widest">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2"></path></svg>
                                        Deadline: {{ $task->due_date ? $task->due_date->format('M d, h:i A') : 'Manual/No Deadline' }}
                                        @if($task->due_date && $task->due_date->isPast())
                                            <span class="ml-2 text-red-500 underline decoration-double">Expired</span>
                                        @endif
                                    </div>
                                    
                                    <button onclick="toggleExtendForm({{ $task->id }})" class="text-[9px] font-black text-emerald-600 hover:text-emerald-800 uppercase tracking-[0.2em] flex items-center transition group">
                                        <svg class="w-3 h-3 mr-1 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"></path></svg>
                                        Extend Deadline
                                    </button>

                                    @if($task->task_attachment)
                                        <a href="{{ Storage::url($task->task_attachment) }}" target="_blank" class="text-[9px] font-black text-purple-600 hover:text-purple-800 uppercase tracking-[0.2em] flex items-center transition">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                            Verify Material
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this task?')" class="p-4 rounded-2xl text-gray-200 hover:text-red-600 hover:bg-red-50 transition-all">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>

                        <div id="extend-form-{{ $task->id }}" class="hidden mt-6 p-6 bg-emerald-50/50 border border-emerald-100 rounded-[1.5rem] animate-in slide-in-from-top-2 duration-300">
                            <form action="{{ route('admin.tasks.extend', $task->id) }}" method="POST" class="flex flex-col md:flex-row items-end md:items-center gap-4">
                                @csrf @method('PATCH')
                                <div class="flex-1 w-full">
                                    <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-2 ml-1">Set New Extension Time</p>
                                    <input type="datetime-local" name="due_date" 
                                           value="{{ $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i') }}" required
                                           class="w-full border-emerald-200 bg-white rounded-xl text-xs font-bold text-emerald-900 focus:ring-emerald-500">
                                </div>
                                <button type="submit" class="bg-emerald-600 text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition">
                                    Apply Extension
                                </button>
                                <button type="button" onclick="toggleExtendForm({{ $task->id }})" class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">
                                    Cancel
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-gray-50/50 rounded-[2rem] border-2 border-dashed border-gray-100">
                        <p class="text-gray-400 font-black uppercase tracking-widest text-xs italic">No active assignments found.</p>
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
        .ck.ck-editor__top .ck-sticky-panel .ck-toolbar { border-radius: 0 !important; }
    </style>

    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
    <script>
        function toggleExtendForm(taskId) {
            const form = document.getElementById('extend-form-' + taskId);
            form.classList.toggle('hidden');
        }

        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ],
                placeholder: 'Specify task details and requirements here...'
            })
            .then(editor => {
                editor.editing.view.change(writer => {
                    writer.setStyle('min-height', '250px', editor.editing.view.document.getRoot());
                });
            })
            .catch(error => { console.error(error); });
    </script>
</x-app-layout>