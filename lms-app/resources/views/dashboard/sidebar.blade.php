<ul class="space-y-2 px-4">
    <!-- Dashboard -->
    <li>
        <a href="{{ route('student.dashboard') }}"
            class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition duration-300 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
            <i class="fas fa-tachometer-alt mr-3 w-5"></i>
            <span>Tổng quan</span>
        </a>
    </li>

    <!-- Dynamic Menu Based on Role -->
    {{-- @if (auth()->user()->role === 'admin')
        @include('dashboard.partials.admin-menu')
    @elseif(auth()->user()->role === 'teacher')
        @include('dashboard.partials.teacher-menu')
    @else
        @include('dashboard.partials.student-menu')
    @endif --}}

    <!-- Common Menu Items -->
    <li class="pt-4 border-t border-gray-200">
        <a href="{{ route('profile.edit') }}"
            class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition duration-300 {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
            <i class="fas fa-user-edit mr-3 w-5"></i>
            <span>Hồ sơ cá nhân</span>
        </a>
    </li>
    <li>
        <a href=""
            class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition duration-300  ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
            <i class="fas fa-cog mr-3 w-5"></i>
            <span>Cài đặt</span>
        </a>
    </li>
</ul>
