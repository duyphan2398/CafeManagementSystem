<nav>
    <ul class="metismenu" id="menu">
        <li>
            <a href="" aria-expanded="true">
                <i class="ti-face-smile"></i>
                <span>
                    Manage Users
                </span>
            </a>
            <ul class="collapse">
                <li>
                    <a href="{{url('users')}}">List Of Users</a>
                </li>
                <li>
                    <a href="{{url('schedules')}}">Schedules</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="" aria-expanded="true">
                <i class="ti-cup"></i>
                <span>
                    Foods And Drinks
                </span>
            </a>
            <ul class="collapse">
                <li>
                    <a href="{{url('products')}}">List Of Product</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="" aria-expanded="true">
                <i class="ti-pencil-alt"></i>
                <span>
                    Manage Receipts
                </span>
            </a>
            <ul class="collapse">
                <li>
                    <a href="{{url('receipts')}}">Receipts</a>
                </li>
                <li>
                    <a href="{{url('tables')}}">List Of Tables</a>
                </li>
                <li>
                    <a href="{{url('promotions')}}">List Of Promotions</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="" aria-expanded="true">
                <i class="ti-home"></i>
                <span>
                    Warehouse
                </span>
            </a>
            <ul class="collapse">
                <li>
                    <a href="{{url('materials')}}">List Of Materials</a>
                </li>
                <li>
                    <a href="{{url('statistics')}}">Statistics</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{url('logout')}}" aria-expanded="true">
                <i class="ti-shift-left"></i>
                <span>
                    Logout
                </span>
            </a>
        </li>
    </ul>
</nav>
