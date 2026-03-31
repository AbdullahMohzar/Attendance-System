<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Task Assignment Center') }}
            </h2>
            <a href="{{ route('admin.tasks.submissions') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow transition">
                Review Student Uploads →
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('dashboard') }}" class="text-sm font-bold text-gray-500 hover:text-purple-600 transition flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Command Center
                </a>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 mb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2 uppercase tracking-widest">Post New Assignment</h3>
                
                <form action="{{ route('admin.tasks.store') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block font-black text-xs uppercase text-gray-500 mb-2">Task Heading</label>
                        <input type="text" name="title" placeholder="e.g., Final Project Submission" required 
                               class="w-full border-gray-200 rounded-xl focus:ring-purple-500 focus:border-purple-500 shadow-sm">
                    </div>

                    <div class="mb-6">
                        <label class="block font-black text-xs uppercase text-gray-500 mb-2">Detailed Instructions</label>
                        <div class="rounded-xl overflow-hidden border border-gray-200">
                            <textarea name="description" id="editor"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg transition transform hover:-translate-y-1">
                            Publish Task to Students
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2 uppercase tracking-widest">Currently Active Tasks</h3>
                
                @if($tasks->isEmpty())
                    <p class="text-center py-10 text-gray-400 italic">No tasks assigned yet. Use the form above to start.</p>
                @else
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($tasks as $task)
                            <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100 flex justify-between items-start group">
                                <div class="flex-1">
                                    <h4 class="font-black text-gray-800 text-lg">{{ $task->title }}</h4>
                                    <div class="text-sm text-gray-600 mt-2 prose max-w-none">
                                        {!! $task->description !!}
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-4 uppercase font-bold">Created: {{ $task->created_at->diffForHumans() }}</p>
                                </div>
                                
                                <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Delete this task? All student responses will also be removed.')">
                                    @csrf @method('DELETE')
                                    <button class="bg-white p-2 rounded-lg text-red-500 shadow-sm border border-red-50 hover:bg-red-500 hover:text-white transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ]
            })
            .catch(error => { console.error(error); });
    </script>
</x-app-layout>