<ul class="nav nav-pills flex-column mb-auto">
    <div class="bg-dark text-white p-4 vh-100" style="width:260px;">
        <h4 class="fw-bold mb-4 text-center">Korean Diner <br> Davao</h4>

        <div class="text-center mb-4">
            <div class="bg-secondary rounded-circle mx-auto" style="width:100px; height:100px;"></div>
            <p class="mt-2">Good Morning, User!</p>
        </div>
    <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="{{route('attendance.index')}}" class="nav-link text-white">Attendance</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white">Request</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white">Payroll</a></li>
        </ul>
    <li>
        <a href="{{ url('/logout') }}" class="nav-link">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </li>
</ul>
