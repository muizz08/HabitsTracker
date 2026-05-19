export default () => ({
    percentage: 0,
    habits: {},

    activeFilter: 'Semua',
    // PANEL
    openPanel: false,
    panelMode: 'create',
    panelTaskId: null,


    // FORM TASK
    form: {
        title: '',
        category_id: '',
        priority: 'Tinggi',
        due_date: '',
        description: '',
        reminder: true,
        tags: [],
    },

    showTagError: false,

    init() {
        this.habits = JSON.parse(
            this.$el.getAttribute('data-initial-logs') || '{}'
        )

        this.percentage = parseInt(
            this.$el.getAttribute('data-initial-percentage') || '0'
        )
    },

    // RESET FORM
    resetForm() {
        this.form = {
            title: '',
            category_id: '',
            priority: 'Tinggi',
            due_date: '',
            description: '',
            reminder: true,
            tags: [],
        }
        this.showTagError = false

        this.panelTaskId = null
        this.panelMode = 'create'
    },

    // OPEN CREATE
    openCreateTask() {
        this.resetForm()
        this.openPanel = true
    },

    // OPEN EDIT
    openEditTask(task) {

        this.panelMode = 'edit'
        this.panelTaskId = task.id

        this.form = {
            title: task.title ?? '',
            category_id: task.category_id ?? '',
            priority: task.priority ?? 'Tinggi',
            due_date: task.due_date ?? '',
            description: task.description ?? '',
            reminder: task.reminder ?? false,
            tags: task.tags ?? [],
        }

        this.openPanel = true
    },

    filterTask(task) {

        if (this.activeFilter === 'Semua') {
            return true
        }

        if (this.activeFilter === 'Hari Ini') {

            const today = new Date().toISOString().split('T')[0]

            return task.due_date === today
        }

        if (this.activeFilter === 'Selesai') {
            return task.is_completed
        }

        return task.tags?.some(tag =>
            tag.name === this.activeFilter
        )
    },

    async toggleHabit(habitId, date) {

        if (!this.habits[habitId]) {
            this.habits[habitId] = {}
        }

        const previousState = this.habits[habitId][date] ?? false

        this.habits[habitId][date] = !previousState

        try {

            const response = await fetch('/habit-logs/toggle', {

                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                },

                body: JSON.stringify({
                    habit_id: habitId,
                    log_date: date
                })

            })

            const data = await response.json()

            if (!response.ok) {
                throw new Error('Gagal memperbarui data')
            }

            if (data && data.newPercentage !== undefined) {
                this.percentage = data.newPercentage
            }

        } catch (error) {

            this.habits[habitId][date] = previousState

            alert('Gagal memperbarui data')
        }
    }
})