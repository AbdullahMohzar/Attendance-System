<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-gray-800 tracking-tight">
                Performance <span class="text-indigo-600">Analytics</span>
            </h2>
            <button onclick="window.print()" class="bg-gray-900 hover:bg-indigo-600 text-white px-8 py-2.5 rounded-[1.5rem] text-xs font-black uppercase tracking-widest shadow-xl shadow-gray-200 no-print transition transform hover:-translate-y-1 active:scale-95 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Export Report
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6 no-print">
                <a href="{{ route('admin.reports') }}" 
                   class="inline-flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-indigo-600 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Refine Parameters
                </a>
                
                <div class="flex items-center space-x-4 bg-white p-2 pl-6 rounded-[2rem] shadow-sm border border-gray-100">
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        Analysis Window: <span class="text-gray-900 ml-1">{{ \Carbon\Carbon::parse($startDate)->format('M d') }} — {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</span>
                    </div>
                    <div class="h-8 w-px bg-gray-100"></div>
                    <div class="bg-indigo-50 text-indigo-600 px-6 py-2 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest">
                        {{ $totalDays }} Billable Workdays
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($students as $student)
                    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-indigo-100/50 border border-gray-100 overflow-hidden hover:scale-[1.02] transition-all duration-300">
                        
                        <div class="p-8 bg-gray-50/50 border-b border-gray-100">
                            <div class="flex justify-between items-start">
                                <div class="flex-1 pr-4">
                                    <h4 class="text-xl font-black text-gray-900 tracking-tight leading-tight">{{ $student->name }}</h4>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">{{ $student->email }}</p>
                                </div>
                                <div class="flex flex-col items-center justify-center w-16 h-16 rounded-2xl bg-white shadow-xl shadow-gray-100 border border-gray-50">
                                    <span class="text-3xl font-black {{ $student->grade_color }}">{{ $student->grade }}</span>
                                    <span class="text-[8px] uppercase font-black text-gray-300 tracking-tighter">Grade</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-8">
                            <div class="flex justify-between items-end mb-4">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Efficiency Rating</span>
                                <span class="text-2xl font-black text-gray-900">{{ $student->attendance_percentage }}%</span>
                            </div>
                            
                            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden shadow-inner relative">
                                <div class="h-full rounded-full transition-all duration-1000 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500" 
                                     style="width: {{ $student->attendance_percentage }}%"></div>
                            </div>

                            <div class="grid grid-cols-3 gap-4 mt-10">
                                <div class="text-center p-4 bg-blue-50/50 rounded-[1.5rem] border border-blue-100">
                                    <span class="block text-[8px] font-black text-blue-400 uppercase tracking-widest mb-1">Present</span>
                                    <span class="text-xl font-black text-blue-700">{{ (int)$student->present_count }}</span>
                                </div>
                                <div class="text-center p-4 bg-emerald-50/50 rounded-[1.5rem] border border-emerald-100">
                                    <span class="block text-[8px] font-black text-emerald-400 uppercase tracking-widest mb-1">Leave</span>
                                    <span class="text-xl font-black text-emerald-700">{{ (int)$student->leave_count }}</span>
                                </div>
                                <div class="text-center p-4 bg-rose-50/50 rounded-[1.5rem] border border-rose-100">
                                    <span class="block text-[8px] font-black text-rose-400 uppercase tracking-widest mb-1">Absent</span>
                                    <span class="text-xl font-black text-rose-700">{{ (int)$student->absent_count }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="px-8 py-5 bg-gray-50/80 flex justify-between items-center border-t border-gray-100">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Classification</span>
                            <span class="px-4 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-white shadow-sm border border-gray-200 {{ $student->grade_color }}">
                                @if($student->attendance_percentage >= 90) Elite
                                @elseif($student->attendance_percentage >= 75) Optimum
                                @elseif($student->attendance_percentage >= 60) Standard
                                @else Critical
                                @endif
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            
            </div>
        </div>
    </div>
    
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; margin: 0; padding: 0; }
            .py-12 { padding-top: 0 !important; padding-bottom: 0 !important; }
            .max-w-7xl { max-width: 100% !important; }
            .rounded-[2.5rem] { border-radius: 1rem !important; }
            .shadow-2xl, .shadow-xl, .shadow-sm { box-shadow: none !important; border: 1px solid #f1f5f9 !important; }
            .bg-gray-50\/50, .bg-gray-50\/80 { background-color: #f8fafc !important; -webkit-print-color-adjust: exact; }
            .grid { display: block !important; }
            .bg-white { page-break-inside: avoid; margin-bottom: 2rem; }
        }
    </style>
</x-app-layout>