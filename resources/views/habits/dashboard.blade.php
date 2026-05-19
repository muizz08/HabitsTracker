<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitify Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
</head>

<body class="bg-gray-50 font-sans">
    <div class="flex h-screen">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen w-full">

            <div x-show="sidebarOpen" @click="sidebarOpen = false"
                class="fixed inset-0 z-20 bg-black opacity-50 lg:hidden">
            </div>
            @include('habits.sidebar')

            <div class="flex-1 flex flex-col overflow-hidden">
                <header class="flex items-center justify-between p-4 bg-white border-b lg:hidden">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    <div class="font-bold text-blue-600">Habitify</div>
                    <div class="w-8"></div>
                </header>

                @php
                    // Mapping data log agar dibaca instan oleh Alpine.js
                    $initialLogs = [];
                    foreach ($habits as $habit) {
                        $initialLogs[$habit->id] = [];
                        foreach ($weekDays as $day) {
                            $hasLog = false;
                            if (isset($habitLogs[$habit->id])) {
                                $hasLog = $habitLogs[$habit->id]->contains('log_date', $day['date']);
                            }
                            $initialLogs[$habit->id][$day['date']] = $hasLog;
                        }
                    }
                    $currentPercentage = $averagePercentage ?? 0;
                @endphp

                @php
                    // Guard agar include sidepanel tidak error jika $tags tidak tersedia di konteks tertentu.
                    $tags = $tags ?? collect();
                @endphp

                <main x-data="habitTracker()" data-initial-logs='@json($initialLogs)'
                    data-initial-percentage="{{ $currentPercentage }}"
                    class="flex-1 overflow-x-hidden overflow-y-auto p-4 lg:p-8 transition-all duration-300"
                    x-bind:style="openPanel ? 'max-width: calc(100% - 22rem)' : 'max-width: 100%'">
                    <header class="flex justify-between items-center mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Halo, Muiss! 👋</h1>
                            <p class="text-gray-500">
                                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                    </header>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-10">
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                                    <i class="fas fa-list-ul"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold">
                                        {{ $tasks->filter(function($task) {
                                            if (!$task->due_date) return false;
                                            
                                            // Ubah due_date dan tanggal hari ini ke format string yang sama (Y-m-d)
                                            $taskDate = \Carbon\Carbon::parse($task->due_date)->format('Y-m-d');
                                            $today = \Carbon\Carbon::today()->format('Y-m-d');
                                            
                                            return $taskDate === $today;
                                        })->count() }}
                                    </p>
                                    <p class="text-xs text-gray-500">Tugas Hari Ini</p>
                                </div>
                            </div>
                        </div>


                        <!-- FUNGSI DASHBOARD -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                                    <i class="fas fa-fire"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold">6</p>
                                    <p class="text-xs text-gray-500">Habit Aktif</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 bg-amber-50 rounded-xl text-amber-600">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold" x-text="percentage + '%'">%</p>
                                    <p class="text-xs text-gray-500">Rata-rata Minggu</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 bg-sky-50 rounded-xl text-sky-600">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold">28</p>
                                    <p class="text-xs text-gray-500">Hari Beruntun</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- DAFTAR -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
                                <div>
                                    <h3 class="text-xl font-semibold text-slate-900">Daftar Tugas</h3>
                                </div>
                                <button @click="openPanel = true; panelMode = 'create'"
                                    class="rounded-2xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 w-full md:w-auto">
                                    + Tambah Tugas
                                </button>
                            </div>

                            @php
                                $defaultTabs = ['Semua', 'Hari Ini', 'Selesai'];
                            @endphp

                            <div class="mb-6 flex flex-nowrap overflow-x-auto lg:flex-wrap lg:overflow-x-visible gap-2 pb-2 scrollbar-none"
                                style="-webkit-overflow-scrolling: touch;">

                                {{-- DEFAULT FILTER --}}
                                @foreach($defaultTabs as $tab)
                                    <button type="button" @click="activeFilter = '{{ $tab }}'"
                                        :class="activeFilter === '{{ $tab }}' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                        class="rounded-full px-4 py-2 text-sm font-semibold transition shrink-0 lg:shrink h-auto min-w-max">
                                        {{ $tab }}
                                    </button>
                                @endforeach

                                {{-- TAG FILTER --}}
                                @foreach($tags as $tag)
                                    <button type="button" @click="activeFilter = '{{ $tag->name }}'"
                                        :class="activeFilter === '{{ $tag->name }}' ? 'bg-indigo-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100'"
                                        class="rounded-full px-4 py-2 text-sm font-semibold transition shrink-0 lg:shrink h-auto min-w-max">
                                        #{{ $tag->name }}
                                    </button>
                                @endforeach

                            </div>

                            <div class="w-full overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
                                <div class="min-w-[900px]">
                                    <div class="grid grid-cols-[3fr_1.2fr_1fr_1.2fr_0.8fr] gap-1 pr-9 items-center text-sm text-slate-500 uppercase tracking-[0.2em] border-b border-slate-200 pb-4 mb-3 pr-3">
                                        <div class="pl-10 font-semibold text-center">Tugas</div>
                                        <div class="font-semibold text-center pl-4">Kategori</div>
                                        <div class="font-semibold text-center pl-3">Prioritas</div>
                                        <div class="font-semibold text-center">Tanggal</div>
                                        <div class="font-semibold text-center pr-1">Edit</div>
                                    </div>

                                    <div class="space-y-5 max-h-[477px] overflow-y-auto custom-scroll" style="scrollbar-gutter: stable;">
                                        @forelse($tasks as $task)
                                            @php
                                                $categoryName = $task->category->name ?? 'Tanpa Kategori';
                                                $isCompleted = $task->is_completed ?? false;

                                                $categoryColor = match (strtolower($categoryName)) {
                                                    'penting' => 'bg-red-100 text-red-700',
                                                    'kesehatan' => 'bg-emerald-100 text-emerald-700',
                                                    'belajar' => 'bg-sky-100 text-sky-700',
                                                    'kebiasaan' => 'bg-emerald-100 text-emerald-700',
                                                    'kerja' => 'bg-amber-100 text-amber-700',
                                                    default => 'bg-slate-100 text-slate-700',
                                                };

                                                $priorityColor = match (strtolower($task->priority)) {
                                                    'tinggi' => 'text-red-600',
                                                    'sedang' => 'text-amber-600',
                                                    'rendah' => 'text-emerald-600',
                                                    default => 'text-slate-600',
                                                };
                                            @endphp

                                            <div id="task-row-{{ $task->id }}" x-show="
                                                    activeFilter === 'Semua' || 
                                                    (activeFilter === 'Hari Ini' && {{ $task->due_date ? (\Carbon\Carbon::parse($task->due_date)->isToday() ? 'true' : 'false') : 'false' }}) || 
                                                    (activeFilter === 'Selesai' && {{ $isCompleted ? 'true' : 'false' }}) || 
                                                    '{{ $task->tags->pluck('name')->implode(',') }}'.split(',').includes(activeFilter)
                                                 "
                                                class="rounded-[1.75rem] border border-slate-200 bg-slate-50 px-5 py-4 shadow-sm transition hover:border-slate-300 hover:shadow-md">

                                                <div class="grid grid-cols-[3fr_1.2fr_1fr_1.2fr_0.8fr] gap-1 items-center text-sm text-slate-700">
                                                    <div class="grid grid-cols-[24px_1fr] gap-1 items-start">
                                                        <input type="checkbox" @if($isCompleted) checked @endif
                                                            class="mt-1 h-5 w-5 shrink-0 rounded border-slate-300 text-blue-600 focus:ring-blue-500" />

                                                        <div class="min-w-0">
                                                            <p class="font-semibold text-slate-900 truncate {{ $isCompleted ? 'line-through text-slate-400' : '' }}">
                                                                {{ $task->title }}
                                                            </p>
                                                            @if($task->description)
                                                                <p class="text-xs text-slate-500 mt-1 hidden lg:block">
                                                                    {{ Str::limit($task->description, 60) }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="flex justify-center">
                                                        <span class="inline-flex items-center rounded-full px-5 py-1 text-xs font-semibold {{ $categoryColor }}">
                                                            {{ $categoryName }}
                                                        </span>
                                                    </div>

                                                    <div class="text-center text-sm font-semibold {{ $priorityColor }}">
                                                        {{ $task->priority }}
                                                    </div>

                                                    <div class="flex items-center justify-center">
                                                        <span class="text-sm text-slate-500 whitespace-nowrap">
                                                            {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->translatedFormat('d M Y') : '-' }}
                                                        </span>
                                                    </div>

                                                    <div class="flex flex-col items-center justify-center gap-2">
                                                        <button type="button" @click='openEditTask({
                                                            id: {{ $task->id }},
                                                            title: @json($task->title),
                                                            category_id: {{ $task->category_id ?? "null" }},
                                                            priority: @json($task->priority),
                                                            due_date: @json($task->due_date),
                                                            description: @json($task->description),
                                                            tags: @json($task->tags)
                                                            })'
                                                            class="rounded-full p-1 text-slate-400 transition hover:bg-slate-200 hover:text-slate-700">
                                                            <i class="fas fa-pen text-sm"></i>
                                                        </button>

                                                        <form id="delete-form-{{ $task->id }}"
                                                            action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" onclick="confirmDelete({{ $task->id }})"
                                                                class="rounded-full p-1 text-red-400 transition hover:bg-red-100 hover:text-red-600">
                                                                <i class="fas fa-trash text-sm"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 py-14 text-center">
                                                <p class="text-slate-500">Belum ada tugas.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-4 flex-wrap gap-4">
                                <div>
                                    <h3 class="text-xl font-semibold text-slate-900">Habit Tracker</h3>
                                </div>
                                <select class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-700">
                                    <option>Minggu Ini</option>
                                </select>
                            </div>

                            <div class="overflow-x-auto">
                                <div class="flex gap-2 md:gap-5 px-4 text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold border-b border-slate-200 pb-3 mb-4 min-w-max">
                                    <div class="w-48 text-center mt-2 font-bold text-lg">Habit</div>
                                    @foreach($weekDays as $day)
                                        <div class="w-16 text-center">
                                            <div>{{ $day['label'] }}</div>
                                            <div class="mt-1 text-sm font-semibold text-slate-900">{{ $day['number'] }}</div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="space-y-4">
                                    @foreach($habits as $habit)
                                        <div class="flex items-center gap-2 md:gap-5 rounded-3xl border border-slate-200 bg-white px-4 py-4 shadow-sm min-w-max">
                                            <div class="flex items-center gap-2 md:gap-3 w-48 shrink-0">
                                                <div class="h-12 w-12 rounded-full grid place-items-center text-white shrink-0"
                                                    style="background-color: {{ $habit->color }};">
                                                    <i class="fas {{ $habit->icon }} text-lg"></i>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-900 text-sm leading-tight">
                                                        {{ $habit->title }}
                                                    </p>
                                                    <p class="text-xs text-slate-400">Habit harian fixed</p>
                                                </div>
                                            </div>

                                            @foreach($weekDays as $day)
                                                @php
                                                    $cellDate = $day['date'];
                                                    // Cek langsung lewat data log yang dikirim dari Backend Laravel
                                                    $isDone = isset($habitLogs[$habit->id]) && $habitLogs[$habit->id]->contains('log_date', $cellDate);
                                                @endphp

                                                <div class="w-16 flex justify-center">
                                                    <button type="button"
                                                        @click="toggleHabit({{ $habit->id }}, '{{ $cellDate }}')"
                                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full border transition duration-200 ease-in-out"
                                                        :class="habits[{{ $habit->id }}]['{{ $cellDate }}'] ? 'bg-emerald-500 border-emerald-500 text-white' : 'bg-white border-slate-200 text-slate-400 hover:border-slate-300 hover:text-slate-600'">
                                                        <template x-if="habits[{{ $habit->id }}]['{{ $cellDate }}']">
                                                            <i class="fas fa-check"></i>
                                                        </template>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 bg-white p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                        <div class="flex items-start gap-6 relative z-10">
                            <div class="text-indigo-200">
                                <svg class="w-12 h-12 fill-current" viewBox="0 0 24 24">
                                    <path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H16.017C14.9124 8 14.017 7.10457 14.017 6V5C14.017 3.89543 14.9124 3 16.017 3H19.017C21.2261 3 23.017 4.79086 23.017 7V15C23.017 18.866 19.883 22 16.017 22H14.017V21ZM1 21L1 18C1 16.89543 1.89543 16 3 16H6C6.55228 16 7 15.5523 7 15V9C7 8.44772 6.55228 8 6 8H3C1.89543 8 1 7.10457 1 6V5C1 3.89543 1.89543 3 3 3H6C8.20914 3 10 4.79086 10 7V15C10 18.866 6.86599 22 3 22H1V21Z" />
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-xl font-bold text-slate-800 mb-2">Disiplin adalah jembatan antara tujuan dan pencapaian.</h3>
                                <p class="text-slate-500">Terus konsisten dan jangan menyerah!</p>
                            </div>
                        </div>

                        <div class="absolute right-0 bottom-0 opacity-20 pointer-events-none">
                            <img src="{{ asset('storage/images/gunung.png') }}" alt="user upload" class="w-80">
                        </div>
                    </div>

                    @include('habits.sidepanel')
                </main>
            </div>
        </div>
    </div>
</body>

</html>