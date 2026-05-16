export default () => ({
    percentage: 0,
    habits: {},

    init() {
        // Mengambil data awal dari atribut HTML data-initial-*
        this.habits = JSON.parse(this.$el.getAttribute('data-initial-logs') || '{}');
        this.percentage = parseInt(this.$el.getAttribute('data-initial-percentage') || '0');
    },

    async toggleHabit(habitId, date) {
        if (!this.habits[habitId]) {
            this.habits[habitId] = {};
        }

        // Simpan status lama buat rollback kalau gagal
        const previousState = this.habits[habitId][date] ?? false;
        
        // Update di UI duluan (Reaktif)
        this.habits[habitId][date] = !previousState;

        try {
            const response = await fetch('/habit-logs/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    habit_id: habitId,
                    log_date: date
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error('Gagal memperbarui data');
            }

            // Update persentase baru dari server
            if (data && data.newPercentage !== undefined) {
                this.percentage = data.newPercentage;
            }

        } catch (error) {
            // Rollback status kalau network/server error
            this.habits[habitId][date] = previousState;
            alert('Gagal memperbarui data, silahkan coba lagi.');
        }
    }
});    