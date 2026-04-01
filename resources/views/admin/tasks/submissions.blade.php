<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-gray-800 tracking-tight">
                Review <span class="text-purple-600">Student Submissions</span>
            </h2>
            <a href="{{ route('admin.tasks') }}" class="flex items-center text-xs font-black uppercase tracking-widest text-gray-400 hover:text-purple-600 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                Task Management
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 p-4 bg-purple-600 text-white rounded-2xl font-bold shadow-lg shadow-purple-200 flex justify-between items-center animate-bounce">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-[2rem] shadow-xl shadow-purple-100/50 border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">
                        <tr>
                            <th class="px-8 py-5 text-left">Student Profile</th>
                            <th class="px-8 py-5 text-left">Assignment</th>
                            <th class="px-8 py-5 text-left">Work Detail</th>
                            <th class="px-8 py-5 text-center">Attachment</th>
                            <th class="px-8 py-5 text-center">Status</th>
                            <th class="px-8 py-5 text-right">Evaluation</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white">
                        @forelse($submissions as $sub)
                        <tr class="hover:bg-purple-50/30 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-black mr-3 shadow-md">
                                        {{ substr($sub->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-black text-gray-900">{{ $sub->user->name }}</div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $sub->user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-8 py-6 text-sm text-purple-700 font-black">
                                {{ $sub->task->title }}
                            </td>

                            <td class="px-8 py-6">
                                <div class="text-xs text-gray-600 italic leading-relaxed max-w-xs truncate">
                                    "{!! strip_tags($sub->submission_text) !!}"
                                </div>
                                <button onclick="alert('{!! addslashes(strip_tags($sub->submission_text)) !!}')" class="text-[9px] font-black text-purple-400 uppercase hover:text-purple-600">View Full Text</button>
                            </td>

                            <td class="px-8 py-6 text-center">
                                @if($sub->attachment)
                                    <a href="{{ route('tasks.download', $sub->id) }}" class="inline-flex items-center p-2 bg-white border border-purple-100 rounded-xl text-purple-600 hover:bg-purple-600 hover:text-white transition shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2"></path></svg>
                                    </a>
                                @else
                                    <span class="text-[10px] font-bold text-gray-300 uppercase">None</span>
                                @endif
                            </td>
                            
                            <td class="px-8 py-6 text-center">
                                <span class="px-4 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border
                                    {{ $sub->status == 'approved' ? 'bg-green-50 text-green-600 border-green-200' : 
                                       ($sub->status == 'rejected' ? 'bg-red-50 text-red-600 border-red-200' : 
                                       'bg-amber-50 text-amber-600 border-amber-200') }}">
                                    {{ $sub->status }}
                                </span>
                            </td>

                            <td class="px-8 py-6">
                                <form action="{{ route('admin.tasks.review', $sub->id) }}" method="POST" class="flex items-center justify-end gap-3">
                                    @csrf
                                    <input type="text" name="feedback" placeholder="Write feedback..." value="{{ $sub->admin_feedback }}"
                                           class="text-[11px] font-bold border-gray-100 bg-gray-50 rounded-xl w-40 focus:ring-purple-500 focus:bg-white transition-all">
                                    
                                    <div class="flex shadow-sm rounded-xl overflow-hidden">
                                        <button type="submit" name="status" value="approved" 
                                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-[10px] font-black uppercase tracking-widest transition">
                                            ✓
                                        </button>
                                        <button type="submit" name="status" value="rejected" 
                                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-[10px] font-black uppercase tracking-widest transition border-l border-red-500">
                                            ✕
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-20 text-center text-gray-400 italic font-medium">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" stroke-width="2"></path></svg>
                                    No submissions pending review.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>