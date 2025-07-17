<div class="col-sm-12 col-lg-3">
    <ul class="list-group">
        <li class="list-group-item">
            <a href="{{ route('profile.index') }}">اطلاعات کاربر</a>
        </li>
        <li class="list-group-item">
            <a href="{{ route('profile.address') }}">آدرس ها</a>
        </li>
        <li class="list-group-item">
            <a href="">سفارشات</a>
        </li>
        <li class="list-group-item">
            <a href="">تراکنش ها</a>
        </li>
        <li class="list-group-item">
            <a href="{{ route('profile.wishlist') }}">لیست علاقه مندی ها</a>
        </li>
        <li class="list-group-item">
            <a href="{{ route('profile.edit.password') }}">تغییر رمزعبور</a>
        </li>
        <li class="list-group-item">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="border: none; background-color: #fff; margin: 0; padding: 0;">خروج از حساب کاربری</button>
            </form>
        </li>
    </ul>
</div>
