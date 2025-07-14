<form action="{{ route('contact-us.store') }}" method="POST">
    @csrf
    <div>
        <input type="text" class="form-control" name="name" value="{{ old('name') }}"
            placeholder="نام و نام خانوادگی" />
        <div class="form-text text-danger">
            @error('name')
                {{ $message }}
            @enderror
        </div>
    </div>
    <div>
        <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="ایمیل" />
        <div class="form-text text-danger">
            @error('email')
                {{ $message }}
            @enderror
        </div>
    </div>
    <div>
        <input type="text" class="form-control" name="subject" value="{{ old('subject') }}"
            placeholder="موضوع پیام" />
        <div class="form-text text-danger">
            @error('subject')
                {{ $message }}
            @enderror
        </div>
    </div>
    <div>
        <textarea rows="10" style="height: 100px" class="form-control" name="body"
            placeholder="متن پیام">{{ old('body') }}</textarea>
        <div class="form-text text-danger">
            @error('body')
                {{ $message }}
            @enderror
        </div>
    </div>
    <div class="btn_box">
        <button>
            ارسال پیام
        </button>
    </div>
</form>
