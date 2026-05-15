import os
import re

files_to_modify = [
    r"c:\laragon\www\artilia\resources\views\admin\components\mobile-hamburger.blade.php",
    r"c:\laragon\www\artilia\resources\views\user\components\mobile-hamburger-user.blade.php",
    r"c:\laragon\www\artilia\resources\views\user\components\sidebar-user.blade.php"
]

logout_block = """        {{-- User Info & Sign Out --}}
        <div class="flex-shrink-0 border-t border-gray-100 p-3 flex flex-col gap-3">
            <div class="flex items-center gap-3 px-2">
                <img src="{{ Auth::user()->profil ? asset(Auth::user()->profil) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=4F76F6&background=EEF2FF&size=40' }}"
                    alt="{{ Auth::user()->name }}"
                    class="h-9 w-9 rounded-full object-cover ring-2 ring-gray-100 dark:ring-slate-800"
                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=4F76F6&background=EEF2FF&size=40'" />
                <div class="flex flex-col min-w-0">
                    <span class="text-[13px] font-bold text-gray-900 truncate">{{ Auth::user()->name }}</span>
                    <span class="text-[10px] text-gray-500 truncate">{{ Auth::user()->email }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" @click="__MENU_VAR__ = false"
                    class="flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-semibold text-red-600 bg-red-50 rounded-xl hover:bg-red-100 active:bg-red-200 transition-colors duration-150 group">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" class="group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>"""

for filepath in files_to_modify:
    if not os.path.exists(filepath):
        continue
    
    with open(filepath, "r", encoding="utf-8") as f:
        content = f.read()

    # 1. Navigation flex wrapper
    content = content.replace('class="flex-1 px-3 py-4 overflow-y-auto space-y-0.5"', 'class="flex-1 px-3 py-4 overflow-y-auto flex flex-col gap-1"')
    
    # 2. pt-4 wrappers
    content = content.replace('<div class="pt-4">', '<div class="pt-4 flex flex-col gap-1">')
    
    # 3. mb-2 text-[10px] to mb-1 text-[10px]
    content = content.replace('mb-2 text-[10px]', 'mb-1 text-[10px]')
    
    # 4. Determine menu variable for the close button
    menu_var = "mobileMenuOpen"
    if "open = false" in content and "mobileMenuOpen" not in content:
        menu_var = "open"
    elif "sidebar-user" in filepath:
        menu_var = "userMobileMenuOpen" # Not used since it's desktop, we can remove @click
        
    # Replace logout block
    pattern = re.compile(r'\{\{-- Sign Out --\}\}.*?</div>', re.DOTALL)
    
    replacement = logout_block.replace("__MENU_VAR__", menu_var)
    if "sidebar-user" in filepath:
        # remove @click
        replacement = replacement.replace('@click="userMobileMenuOpen = false"', "")
        
    content = re.sub(pattern, replacement, content)

    with open(filepath, "w", encoding="utf-8") as f:
        f.write(content)
        
print("Replacements done.")
