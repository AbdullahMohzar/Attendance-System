<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-gray-800 tracking-tight">
                Attendance <span class="text-blue-600">Report Engine</span>
            </h2>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-blue-600 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Command
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="mb-8 p-5 bg-rose-500 text-white rounded-[2rem] font-bold shadow-xl shadow-rose-100 flex items-center animate-in fade-in slide-in-from-top-4 duration-500">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="uppercase tracking-widest text-xs">Error: {{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white p-10 rounded-[2.5rem] shadow-2xl shadow-blue-100/50 border border-gray-100">
                <div class="flex items-center mb-10">
                    <div class="w-2 h-8 bg-blue-600 rounded-full mr-4 shadow-lg shadow-blue-200"></div>
                    <h3 class="text-xl font-black text-gray-800 uppercase tracking-widest">Configuration</h3>
                </div>

                <form action="{{ route('admin.reports.generate') }}" method="GET" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="relative group">
                            <label class="block font-black text-[10px] uppercase text-gray-400 tracking-widest mb-3 ml-1">From Date</label>
                            <input type="date" name="start_date" 
                                   value="{{ now()->startOfMonth()->format('Y-m-d') }}"
                                   required 
                                   class="w-full border-gray-100 bg-gray-50 rounded-2xl py-4 px-6 focus:ring-blue-500 focus:border-blue-500 shadow-inner transition-all font-bold text-gray-700">
                        </div>
                        <div class="relative group">
                            <label class="block font-black text-[10px] uppercase text-gray-400 tracking-widest mb-3 ml-1">To Date</label>
                            <input type="date" name="end_date" 
                                   value="{{ now()->format('Y-m-d') }}"
                                   required 
                                   class="w-full border-gray-100 bg-gray-50 rounded-2xl py-4 px-6 focus:ring-blue-500 focus:border-blue-500 shadow-inner transition-all font-bold text-gray-700">
                        </div>
                    </div>

                    <div class="relative group">
                        <label class="block font-black text-[10px] uppercase text-gray-400 tracking-widest mb-3 ml-1">Target Audience</label>
                        <select name="student_id" class="w-full border-gray-100 bg-gray-50 rounded-2xl py-4 px-6 focus:ring-blue-500 focus:border-blue-500 shadow-inner transition-all font-bold text-gray-700 appearance-none">
                            <option value="">All Students (Global Summary)</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-6 pt-6 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full bg-gray-900 hover:bg-blue-600 text-white py-6 rounded-[2rem] font-black uppercase tracking-[0.2em] shadow-xl shadow-gray-200 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-6m10 10V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2z"></path></svg>
                            Analyze & Generate Data
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-8 text-center px-10">
                <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.3em] leading-relaxed">
                    Reports include approved leaves and present status only. Weekends are automatically excluded from the analysis.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>