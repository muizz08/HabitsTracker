window.confirmDelete = async function(taskId) {

    const result = await Swal.fire({
        title: 'Hapus tugas?',
        text: 'Task yang dihapus tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    });

    if (!result.isConfirmed) return;

    try {

        const response = await fetch(`/tasks/${taskId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        console.log(response);
        if (response.ok) {

            const row = document.getElementById(`task-row-${taskId}`);

            row.style.transition = 'all .3s ease';
            row.style.opacity = '0';
            row.style.transform = 'translateX(30px)';

            setTimeout(() => {
                row.remove();
            }, 300);

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Task berhasil dihapus',
                timer: 1500,
                showConfirmButton: false
            });

        } else {  console.log(await response.text());

            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Task gagal dihapus'
            });

        }

    } catch (error) {

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan'
        });

    }
}