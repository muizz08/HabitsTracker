<div x-show="openPanel" class="relative z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true"
    style="display: none;">

    <!-- BACKDROP -->
    <div x-show="openPanel" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="openPanel = false"
        class="fixed inset-0 bg-slate-900/40">
    </div>

    <!-- PANEL -->
    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">

            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full">

                <div x-show="openPanel" x-transition:enter="transform transition ease-in-out duration-300"
                    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-300"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                    class="pointer-events-auto w-screen max-w-lg transform-gpu">

                    <!-- CARD -->
                    <div class="flex h-full flex-col bg-[#F8F8FA] shadow-2xl">

                        <!-- CONTENT -->
                        <div class="flex-1 overflow-y-auto p-5">

                            <!-- HEADER -->
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-[18px] font-semibold text-slate-900">
                                    Tambah / Edit Tugas
                                </h2>

                                <button @click="openPanel = false" class="h-9 w-9 rounded-full flex items-center justify-center
                                    text-slate-400 hover:bg-white transition">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <!-- FORM -->
                            <form action="{{ route('tasks.store') }}" method="POST" class="space-y-5">
                                @if (session('success'))
                                    <div
                                        class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if ($errors->any())
                                    <div
                                        class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                                        <ul class="list-disc pl-5">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @csrf
                                <!-- JUDUL -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-2">
                                        Judul Tugas
                                    </label>

                                    <input type="text" placeholder="Menyelesaikan desain landing page" class="w-full rounded-2xl border border-slate-200 bg-white
                                        px-4 py-3 text-sm outline-none
                                        focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" name="title">
                                </div>

                                <!-- KATEGORI -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-2">
                                        Kategori
                                    </label>

                                    <select name="category_id" class="w-full rounded-2xl border border-slate-200 bg-white
                                        px-4 py-3 text-sm outline-none
                                        focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">

                                        <option value="">
                                            Pilih Kategori
                                        </option>

                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- PRIORITAS -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-3">
                                        Prioritas
                                    </label>

                                    <div class="flex items-center gap-6">

                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="priority" value="Rendah"
                                                class="text-indigo-600 focus:ring-indigo-500">
                                            <span class="text-sm text-slate-600">Rendah</span>
                                        </label>

                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="priority" value="Sedang"
                                                class="text-indigo-600 focus:ring-indigo-500">
                                            <span class="text-sm text-slate-600">Sedang</span>
                                        </label>

                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="priority" value="Tinggi" checked
                                                class="text-indigo-600 focus:ring-indigo-500">
                                            <span class="text-sm text-slate-600">Tinggi</span>
                                        </label>

                                    </div>
                                </div>

                                <!-- TAG -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-3">
                                        Tag
                                        <span class="text-slate-400">
                                            (Pilih lebih dari satu)
                                        </span>
                                    </label>

                                    <div class="grid grid-cols-2 gap-3">

                                        @foreach($categories as $category)

                                            <label
                                                class="flex items-center gap-3 rounded-xl border border-slate-200
                                                                                                        bg-white px-3 py-3 cursor-pointer hover:border-indigo-300 transition">

                                                <input type="checkbox"
                                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">

                                                <span class="text-sm text-slate-700">
                                                    {{ $category->name }}
                                                </span>

                                            </label>

                                        @endforeach

                                    </div>
                                </div>

                                <!-- PENGINGAT -->
                                <div class="flex items-center justify-between">

                                    <div>
                                        <p class="text-sm font-semibold text-slate-700">
                                            Pengingat
                                        </p>
                                    </div>

                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="reminder" checked class="sr-only peer">

                                        <div class="w-11 h-6 bg-slate-200 rounded-full
                                            peer peer-checked:bg-indigo-600
                                            after:content-['']
                                            after:absolute after:top-[2px] after:left-[2px]
                                            after:bg-white after:rounded-full
                                            after:h-5 after:w-5 after:transition-all
                                            peer-checked:after:translate-x-full">
                                        </div>
                                    </label>
                                </div>

                                <!-- TANGGAL -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-2">
                                        Tanggal
                                    </label>

                                    <input type="date" name="due_date" class="w-full rounded-2xl border border-slate-200 bg-white
                                        px-4 py-3 text-sm outline-none
                                        focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                </div>

                                <!-- DESKRIPSI -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-2">
                                        Deskripsi
                                        <span class="text-slate-400">(Opsional)</span>
                                    </label>

                                    <textarea name="description" rows="5"
                                        placeholder="Menyelesaikan desain landing page sebelum deadline." class="w-full rounded-2xl border border-slate-200 bg-white
                                        px-4 py-3 text-sm outline-none resize-none
                                        focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"></textarea>

                                    <div class="mt-2 text-right text-xs text-slate-400">
                                        61/200
                                    </div>
                                </div>

                                <!-- FOOTER -->
                                <div class="border-t border-slate-200 bg-white p-5">

                                    <div class="grid grid-cols-3 gap-3">

                                        <button @click="openPanel = false" type="button" class="rounded-xl border border-slate-200 bg-white
                                        py-3 text-sm font-semibold text-slate-600
                                        hover:bg-slate-50 transition">
                                            Batal
                                        </button>

                                        <button type="button" class="rounded-xl bg-red-500
                                        py-3 text-sm font-semibold text-white
                                        hover:bg-red-600 transition">
                                            Hapus
                                        </button>

                                        <button type="submit" class="rounded-xl bg-indigo-600
                                        py-3 text-sm font-semibold text-white w-full h-full
                                        hover:bg-indigo-700 transition">
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>