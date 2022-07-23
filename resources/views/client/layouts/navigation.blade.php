<nav id="navigation">
    <!-- container -->
    <div class="container">
        <!-- responsive-nav -->
        <div id="responsive-nav">
            @php
                $module_active = session('module_active');
            @endphp
            <!-- NAV -->
            <ul class="main-nav d-flex">
                <li class="{{ $module_active == 'home' ? 'active' : '' }}">
                    <a href="{{ url('/home') }}" title="">Trang chủ</a>
                </li>
                <li class="{{ $module_active == 'product' ? 'active' : '' }}">
                    <a href="{{ url('/san-pham') }}" title="">Sản phẩm</a>
                </li>
                <li class="{{ $module_active == 'post' ? 'active' : '' }}">
                    <a href="{{ route('blog-list') }}" title="">Blog</a>

                </li>
                @if ($pages->count() > 0)
                    @foreach ($pages as $page)
                        <li class="{{ $module_active == $page->slug ? 'active' : '' }}">
                            <a href="{{ route('page_show', $page->slug) }}" title="">{{ $page->title }}</a>
                        </li>
                    @endforeach
                @endif
                {{-- <li>
                    <a href="" title=""></a>
                    <ul class="dropdown-menu">
                        <li><a href="">Menu 1</a></li>
                        <li><a href="">Menu 2</a>
                            <ul class="sub-menu">
                                <li><a href="">Menu 2.1</a></li>
                                <li><a href="">Menu 2.2</a></li>
                                <li><a href="">Menu 2.3</a>
                                <ul class="sub-menu">
                                    <li><a href="">Menu 2.3.1</a></li>
                                    <li><a href="">Menu 2.3.2</a></li>
                                    <li><a href="">Menu 2.3.3</a></li>
                                </ul>
                            </li>
                                <li><a href="">Menu 2.4</a></li>
                                <li><a href="">Menu 2.5</a></li>
                            </ul>
                        </li>
                        <li><a href="">Menu 3</a></li>
                        <li><a href="">Menu 4</a></li>

                    </ul>
                </li> --}}

            </ul>
            <!-- /NAV -->
        </div>
        <!-- /responsive-nav -->
    </div>
    <!-- /container -->
</nav>
<!-- /NAVIGATION -->
