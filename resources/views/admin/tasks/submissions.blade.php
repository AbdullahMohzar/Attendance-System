<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Review Student Submissions</h2>
            <a href="{{ route('admin.tasks') }}" class="text-sm font-bold text-purple-600 hover:underline">
                ← Back to Task Management
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-[10px] font-black uppercase text-gray-400">
                        <tr>
                            <th class="px-6 py-4 text-left">Student</th>
                            <th class="px-6 py-4 text-left">Task Title</th>
                            <th class="px-6 py-4 text-left">Response</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($submissions as $sub)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $sub->user->name }}</div>
                                <div class="text-[10px] text-gray-400">{{ $sub->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-purple-700 font-medium">{{ $sub->task->title }}</td>
                            <td class="px-6 py-4 text-xs text-gray-600 italic">"{{ Str::limit($sub->submission_text, 50) }}"</td>
                            
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded text-[10px] font-black uppercase 
                                    {{ $sub->status == 'approved' ? 'bg-green-100 text-green-700 border border-green-200' : 
                                       ($sub->status == 'rejected' ? 'bg-red-100 text-red-700 border border-red-200' : 
                                       'bg-yellow-100 text-yellow-700 border border-yellow-200') }}">
                                    {{ $sub->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <form action="{{ route('admin.tasks.review', $sub->id) }}" method="POST" class="flex items-center justify-end gap-2">
                                    @csrf
                                    <input type="text" name="feedback" placeholder="Add feedback..." value="{{ $sub->admin_feedback }}"
                                           class="text-[10px] border-gray-200 rounded-lg w-32 focus:ring-purple-500">
                                    
                                    <button type="submit" name="status" value="approved" 
                                            style="background-color: #16a34a !important; color: white !important;"
                                            class="px-3 py-1 rounded shadow-sm text-[10px] font-bold uppercase transition hover:opacity-90">
                                        Approve
                                    </button>
                                    
                                    <button type="submit" name="status" value="rejected" 
                                            style="background-color: #dc2626 !important; color: white !important;"
                                            class="px-3 py-1 rounded shadow-sm text-[10px] font-bold uppercase transition hover:opacity-90">
                                        Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center text-gray-400 italic font-medium">
                                No student submissions have been uploaded yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>