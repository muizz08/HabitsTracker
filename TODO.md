## Task: Fix filter tag not active (Alpine client-side)

### Plan
- [ ] Refactor `resources/views/habits/dashboard.blade.php` bagian “Daftar Tugas” agar task dirender pakai Alpine (`x-for`) bukan `@forelse`.
- [ ] Tambahkan `tasks` ke Alpine state dari backend (include `tags` dan field yang dipakai filter: `due_date`, `is_completed`, `tags[].name`).
- [ ] Pada setiap row task, tambahkan `x-show="filterTask(task)"` atau `x-bind:class` untuk hide/show.
- [ ] Pastikan `openEditTask(task)` tetap bisa bekerja (kirim field yang dibutuhkan termasuk `task.tags`).
- [ ] Jalankan build/cek error console.

