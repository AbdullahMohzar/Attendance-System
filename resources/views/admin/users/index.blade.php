<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-gray-800 leading-tight uppercase tracking-tighter">
                👥 {{ __('Global Staff Directory') }}
            </h2>
            <a href="{{ route('admin.summary') }}" class="text-[10px] font-black text-indigo-600 hover:text-indigo-800 transition uppercase tracking-widest">
                &larr; Back to Oversight
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div id="toast" class="mb-6 p-4 bg-gray-900 text-white rounded-2xl shadow-xl flex items-center animate-in slide-in-from-top-4 duration-300">
                    <span class="mr-3 bg-emerald-500 p-1 rounded-full text-[10px]">✓</span>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
                <script>setTimeout(() => document.getElementById('toast')?.remove(), 3000);</script>
            @endif

            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-separate border-spacing-y-2 px-4">
                    <thead>
                        <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Identity</th>
                            <th class="px-6 py-4 text-center">System Role</th>
                            <th class="px-6 py-4 text-right">Management</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr class="group hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 bg-gray-50/50 group-hover:bg-white rounded-l-2xl">
                                <div class="flex items-center">
                                    {{-- Temporary UI Avatars - Will be replaced by Profile Uploads next --}}
                                    <img class="h-10 w-10 rounded-full border-2 border-white shadow-sm mr-4" 
                                         src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background={{ $user->role === 'admin' ? '333' : '6366f1' }}&color=fff&bold=true">
                                    <div>
                                        <div class="text-sm font-black text-gray-800">{{ $user->name }}</div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 bg-gray-50/50 group-hover:bg-white text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter 
                                    {{ $user->role === 'admin' ? 'bg-gray-900 text-white' : '' }}
                                    {{ $user->role === 'teacher' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $user->role === 'hr' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $user->role === 'student' ? 'bg-blue-100 text-blue-700' : '' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 bg-gray-50/50 group-hover:bg-white rounded-r-2xl text-right">
                                {{-- DO NOT allow admins to delete themselves --}}
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.students.destroy', $user->id) }}" method="POST" onsubmit="return confirm('PERMANENT ACTION: Remove all access and data for {{ $user->name }}?')">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="text-[10px] font-black text-rose-400 hover:text-rose-600 uppercase tracking-tighter transition">
                                            Revoke Access
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[9px] font-black text-gray-300 uppercase italic">Current Session</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>