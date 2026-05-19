<div x-show="openPanel"
    class="relative z-50"
    aria-labelledby="slide-over-title"
    role="dialog"
    aria-modal="true"
    style="display: none;">

    <div x-show="openPanel"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="openPanel = false"
        class="fixed inset-0 bg-slate-900/40">
    </div>

    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full">

                <div x-show="panelMode !== 'create_habit'"
                    x-transition:enter="transform transition ease-in-out duration-300"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-300"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="pointer-events-auto w-screen max-w-lg transform-gpu">

                    <div class="flex h-full flex-col bg-[#F8F8FA] shadow-2xl">

                        <div class="flex-1 overflow-y-auto p-5">

                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-[18px] font-semibold text-slate-900"
                                    x-text="panelMode === 'edit' ? 'Edit Tugas' : 'Tambah Tugas'">
                                </h2>

                                <button @click="openPanel = false"
                                    class="h-9 w-9 rounded-full flex items-center justify-center text-slate-400 hover:bg-white transition">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <form x-ref="taskForm"
                                action="{{ route('tasks.store') }}"
                                method="POST"
                                class="space-y-5"
                                @submit.prevent="
                                    {{-- Pastikan form.tags adalah array sebelum mengecek length --}}
                                    showTagError = !Array.isArray(form.tags) || form.tags.length === 0;

                                    if(showTagError){
                                        return;
                                    }

                                    if(panelMode === 'edit'){
                                        $refs.taskForm.action = '/tasks/' + panelTaskId;
                                    }

                                    $refs.taskForm.submit();
                                ">

                                @csrf

                                <template x-if="panelMode === 'edit'">
                                    <input type="hidden" name="_method" value="PUT">
                                </template>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-2">
                                        Judul Tugas
                                    </label>
                                    <input type="text"
                                        name="title"
                                        x-model="form.title"
                                        placeholder="Menyelesaikan desain landing page"
                                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                                        required>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-2">
                                        Kategori
                                    </label>
                                    <select name="category_id"
                                        x-model="form.category_id"
                                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                                        required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-3">
                                        Prioritas
                                    </label>
                                    <div class="flex items-center gap-6">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="priority" x-model="form.priority" value="Rendah" class="text-indigo-600 focus:ring-indigo-500" required>
                                            <span class="text-sm text-slate-600">Rendah</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="priority" x-model="form.priority" value="Sedang" class="text-indigo-600 focus:ring-indigo-500" required>
                                            <span class="text-sm text-slate-600">Sedang</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="priority" x-model="form.priority" value="Tinggi" class="text-indigo-600 focus:ring-indigo-500" required>
                                            <span class="text-sm text-slate-600">Tinggi</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-3">
                                        Tag <span class="text-slate-400">(Pilih lebih dari satu)</span>
                                    </label>

                                    <div class="grid grid-cols-2 gap-3 rounded-2xl transition-all duration-200 p-2"
                                        :class="showTagError ? 'border border-red-400 bg-red-50/40' : 'border border-transparent'">

                                        @foreach($tags as $tag)
                                            <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-3 cursor-pointer hover:border-indigo-300 transition">
                                                {{-- PERBAIKAN UTAMA: Memastikan x-model membaca struktur data array dengan benar dan tetap mempertahankan attribute name --}}
                                                <input type="checkbox"
                                                    name="tags[]"
                                                    value="{{ $tag->id }}"
                                                    x-model="form.tags"
                                                    @change="showTagError = !Array.isArray(form.tags) || form.tags.length === 0"
                                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                                <span class="text-sm text-slate-700">{{ $tag->name }}</span>
                                            </label>
                                        @endforeach

                                    </div>

                                    <p x-show="showTagError" x-transition class="mt-2 text-xs text-red-500">
                                        Minimal pilih 1 tag
                                    </p>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-700">Pengingat</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="reminder" x-model="form.reminder" class="sr-only peer">
                                        <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                                    </label>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-2">Tanggal</label>
                                    <input type="date" name="due_date" x-model="form.due_date" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-2">Deskripsi</label>
                                    <textarea name="description" x-model="form.description" rows="5" placeholder="Menyelesaikan desain landing page sebelum deadline." class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none resize-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"></textarea>
                                </div>

                                <div class="border-t border-slate-200 bg-white pt-5">
                                    <div class="grid grid-cols-3 gap-3">
                                        <button @click="openPanel = false" type="button" class="rounded-xl border border-slate-200 bg-white py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                                            Batal
                                        </button>
                                        <button type="button" @click="deleteTask()" class="rounded-xl bg-red-500 py-3 text-sm font-semibold text-white hover:bg-red-600 transition">
                                            Hapus
                                        </button>
                                        <button type="submit" class="rounded-xl bg-indigo-600 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                                            Simpan
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <div x-show="panelMode === 'create_habit'"
                    x-transition:enter="transform transition ease-in-out duration-300"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-300"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="pointer-events-auto w-screen max-w-md bg-white shadow-2xl border-l border-slate-100 z-50 p-6 flex flex-col justify-between">

                    <div>
                        <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="fas fa-seedling text-emerald-500"></i>
                                Tambah Kebiasaan Baru
                            </h3>
                            <button @click="openPanel = false" class="text-slate-400 hover:text-slate-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <form id="formTambahHabit" action="/habits" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nama Kebiasaan</label>
                                <input type="text" name="name" required placeholder="Contoh: Membaca Buku 15 Menit" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                            </div>
                        </form>
                    </div>

                    <div class="border-t border-slate-100 pt-4 flex space-x-3">
                        <button @click="openPanel = false" type="button" class="w-1/2 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 transition">
                            Batal
                        </button>
                        <button type="submit" form="formTambahHabit" class="w-1/2 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium shadow-sm shadow-emerald-200 transition">
                            Simpan Habit
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>