<nav>
    <ul class="metismenu" id="menu">
        <li class="active">
            <a href="javascript:void(0)" aria-expanded="true"><i class="ti-dashboard"></i><span>dashboard</span></a>
            <ul class="collapse">
                <li class="active"><a href="index.html">ICO dashboard</a></li>
                <li><a href="index2.html">Ecommerce dashboard</a></li>
                <li><a href="index3.html">SEO dashboard</a></li>
            </ul>
        </li>
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
                {{--<li>
                    <a href="index3-horizontalmenu.html">Horizontal Sidebar</a>
                </li>--}}
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
                {{--<li>
                    <a href="index3-horizontalmenu.html">Horizontal Sidebar</a>
                </li>--}}
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
                {{--<li>
                    <a href="index3-horizontalmenu.html">Horizontal Sidebar</a>
                </li>--}}
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
