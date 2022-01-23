@props(['models'])
<button
    class="-order-12 hidden basis-6 flex-none md:flex items-center justify-center border-r border-r-sky-400 mr-1 hover:bg-slate-800/25 group"
    type="button" onclick="const element = document.getElementById('left_nav_bar_container');
    element.style.display == 'none' ? element.style.removeProperty('display') : element.style.display = 'none';
    document.getElementById('left_double_arrow').classList.toggle('rotate-180');">
    <span id="left_double_arrow" class="material-icons-outlined text-slate-700 group-hover:text-sky-400 rotate-180">
        double_arrow
    </span>
</button>

<div id="left_nav_bar_container" class="-order-11 hidden basis-80 flex-none md:flex flex-col items-stretch my-4">
    <x-admin.left-nav-bar :models=$adminModels />
</div>
