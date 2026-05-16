{{-- resources/views/livewire/habit-tracker.blade.php --}}

<div> <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-4">
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-lg font-bold text-slate-800">Progress Minggu Ini</h3>
            <span class="text-2xl font-black text-blue-600">{{ $percentage }}%</span>
        </div>
        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
            <div class="bg-blue-500 h-3 rounded-full transition-all duration-700 ease-out" 
                 style="width: {{ $percentage }}%">
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4 flex-wrap gap-4">
            <h3 class="text-xl font-semibold text-slate-900">Habit Tracker</h3>
        </div>

        <div class="overflow-x-auto">
            {{-- Tabel Header --}}
            <div class="flex gap-2 md:gap-5 px-4 text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold border-b border-slate-200 pb-3 mb-4 min-w-max">
                <div class="w-48 text-center mt-2 font-bold text-lg">Habit</div>
                @foreach($weekDays as $day)
                    <div class="w-16 text-center">
                        <div>{{ $day['label'] }}</div>
                        <div class="mt-1 text-sm font-semibold text-slate-900">{{ $day['number'] }}</div>
                    </div>
                @endforeach
            </div>

            {{-- List Habit --}}
            <div class="space-y-4">
                @foreach($habits as $habit)
                    <div class="flex items-center gap-2 md:gap-5 rounded-3xl border border-slate-200 bg-white px-4 py-4 shadow-sm min-w-max">
                        <div class="flex items-center gap-2 md:gap-3 w-48 shrink-0">
                            <div class="h-12 w-12 rounded-full grid place-items-center text-white"
                                 style="background-color: {{ $habit->color }};">
                                <i class="fas {{ $habit->icon }} text-lg"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-sm leading-tight">{{ $habit->title }}</p>
                                <p class="text-xs text-slate-400">Habit harian fixed</p>
                            </div>
                        </div>

                        @foreach($weekDays as $day)
                            @php
                                $cellDate = $day['date'];
                                $isDone = isset($habitLogs[$habit->id]) && $habitLogs[$habit->id]->contains('log_date', $cellDate);
                            @endphp
                            <div class="w-16 flex justify-center">
                                <button type="button"
                                    wire:click="toggleHabit({{ $habit->id }}, '{{ $cellDate }}')"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-full border transition duration-200
                                    {{ $isDone ? 'bg-emerald-500 border-emerald-500 text-white' : 'bg-white border-slate-200 text-slate-400' }}">
                                    @if($isDone)
                                        <i class="fas fa-check"></i>
                                    @endif
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div> ```

### Hal Penting untuk Diingat:
* **Root Tag:** Seluruh kode HTML di atas dibungkus oleh satu tag `<div>` paling luar. Tanpa ini, Livewire akan terus mengeluarkan error *missing root tag*.
* **Variabel `$percentage`:** Pastikan variabel ini ada di dalam file Blade agar tidak muncul error *undefined variable* yang Anda alami sebelumnya.
* **Lokasi File:** Kode PHP yang Anda kirim harus berada di `app/Livewire/HabitTracker.php` agar Laravel bisa menemukannya.

Apakah setelah dibungkus dengan satu `<div>` besar, halamannya sudah bisa muncul tanpa error?