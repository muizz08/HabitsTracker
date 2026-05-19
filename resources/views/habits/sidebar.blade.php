<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r flex flex-col p-6 transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0">
    
    <div class="flex items-center justify-between mb-10">
        <div class="text-blue-600 font-bold text-2xl">Habitify</div>
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-500">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <nav class="space-y-4">
        <a href="#" class="flex items-center p-3 bg-blue-50 text-blue-600 font-medium rounded-xl transition">
            <i class="fas fa-home mr-3"></i> Dashboard
        </a>
        <a href="#" class="flex items-center p-3 text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium rounded-xl transition">
            <i class="fas fa-list mr-3"></i> Todo List
        </a>
    </nav>

    <div class="flex-grow flex items-end my-8">
        <div class="bg-indigo-50/50 rounded-3xl p-5 text-center border border-indigo-50 w-full">
            <div class="flex justify-center mb-3">
                <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center text-3xl">
                    🌱
                </div>
            </div>
            <h4 class="text-slate-900 font-bold text-sm leading-snug">Konsistensi adalah kunci perubahan.</h4>
            <p class="text-slate-500 text-xs mt-2">Sedikit setiap hari, hasil luar biasa.</p>
        </div>
    </div>

    <div class="mt-auto pt-4 border-t border-slate-100">
        <div class="flex items-center justify-between cursor-pointer p-2 hover:bg-slate-50 rounded-xl transition" x-data="{ profileMenuOpen: false }" @click="profileMenuOpen = !profileMenuOpen">
            <div class="flex items-center gap-3 min-w-0">
                <img src="{{ asset('storage/images/muiss.png') }}"
                     alt="User Profile" 
                     class="w-10 h-10 rounded-full object-cover shrink-0 border border-slate-100">
                
                <div class="min-w-0">
                    <h2 class="text-sm font-bold text-slate-800 truncate">Muiss</h2>
                    <p class="text-xs text-slate-400 truncate">muiss@email.com</p>
                </div>
            </div>
            
            <span class="text-slate-400 text-xs shrink-0 ml-2">
                <i class="fas fa-chevron-down transition-transform duration-200" :class="profileMenuOpen ? 'rotate-180' : ''"></i>
            </span>
        </div>
    </div>

</aside>