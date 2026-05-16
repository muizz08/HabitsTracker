<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r flex flex-col p-6 transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0">
    
    <div class="flex items-center justify-between mb-10">
        <div class="text-blue-600 font-bold text-2xl">Habitify</div>
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-500">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <nav class="space-y-4">
        <a href="#" class="flex items-center p-2 bg-blue-50 text-blue-600 rounded-lg">
            <i class="fas fa-home mr-3"></i> Dashboard
        </a>
        <a href="#" class="flex items-center p-2 text-gray-500 hover:bg-gray-50 rounded-lg">
            <i class="fas fa-list mr-3"></i> Todo List
        </a>
    </nav>
</aside>