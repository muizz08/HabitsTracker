export default () => ({
    logs: {},
    percentage: 0, // State untuk menampung persentase dari backend

    init() {
        // Ambil data logs awal dan persentase awal dari Blade HTML dataset
        this.logs = JSON.parse(this.$el.dataset.initialLogs || '{}');
        this.percentage = parseInt(this.$el.dataset.initialPercentage || 0);
    },

    // Mengecek apakah habit pada tanggal tersebut berstatus true (checked)
    isDone(habitId, date) {
        return this.logs[habitId] && this.logs[habitId][date] === true;
    },

    // Fungsi utama saat tombol lingkaran di-klik
    async toggleHabit(habitId, date) {
        // 1. Ambil status sebelum dirubah untuk backup jika gagal
        const previousState = this.isDone(habitId, date);
        
        // 2. Update UI secara Instan (Optimistic UI)
        if (!this.logs[habitId]) this.logs[habitId] = {};
        this.logs[habitId][date] = !previousState;

        try {
            // 3. Tembak endpoint asli backend kamu
            const res = await fetch('/habit-logs/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    habit_id: habitId,
                    log_date: date // Menyesuaikan nama request backend lama: log_date
                })
            });

            if (!res.ok) throw new Error('HTTP Error dari Server');

            const data = await res.json();

            // 4. Update persentase secara realtime dari hasil hitungan controller backend
            if (data.newPercentage !== undefined) {
                this.percentage = data.newPercentage;
            }

        } catch (err) {
            // Revert UI ke status semula jika koneksi/server error
            this.logs[habitId][date] = previousState;
            alert('Gagal menyimpan perubahan.');
            console.error(err);
        }
    }
});