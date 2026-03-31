<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Managing Attendance: {{ $student->name }}</h2>
    </x-slot>
    <div class="mb-6">
        <a href="{{ route('admin.students') }}" 
        class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-indigo-600 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Student List
        </a>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 border text-left">Date</th>
                            <th class="p-3 border text-left">Time</th>
                            <th class="p-3 border text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->attendances as $record)
                        <tr>
                            <td class="p-3 border">{{ $record->attendance_date }}</td>
                            <td class="p-3 border text-gray-500">{{ $record->check_in_time }}</td>
                            <td class="p-3 border text-center">
                                <form action="{{ route('attendance.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>