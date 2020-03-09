<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <div class="float-center">
            <ul class="list-inline">
                <li class="list-inline-item">
                    <a href="{{url('/')}}" class="navbar-brand font-weight-bold mt-2"><h3>Cafe Management System</h3></a>
                </li>
            </ul>
        </div>
        @if(\Illuminate\Support\Facades\Auth::check())
        <div class="float-right mt-1" >
            <ul class="list-inline">
                <li class="list-inline-item">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">{{\Illuminate\Support\Facades\Auth::user()->name}}
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="{{url('logout')}}">Logout</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        @endif
    </div>
</nav>
