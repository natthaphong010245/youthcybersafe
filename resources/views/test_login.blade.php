@extends('layouts.home.index')

@section('content')
<div class="bg-white w-full flex-grow rounded-t-[50px] px-6 pt-8 flex flex-col mt-16">
    <div class="text-center mb-6">
        <h1 class="text-[#3F359E] text-[24px] font-bold">
            สำนักวิชาวิทยาศาสตร์สุขภาพ
        </h1>
        <p class="text-[#3F359E] text-[22px] font-bold">มหาวิทยาลัยแม่ฟ้าหลวง</p>
    </div>

    <div class="space-y-3 mb-8">
        <a href="#"
            class="flex items-center justify-center space-x-6 w-full border border-gray-300 rounded-2xl py-2 px-2 hover:bg-gray-50 transition duration-300 text-[20px]">
            <img src="/images/line.png" alt="Line" class="h-8 w-8">
            <span class="text-gray-700 font-bold">พื้นที่พูดคุย</span>
        </a>

        <a href="#"
            class="flex items-center justify-center space-x-5 w-full border border-gray-300 rounded-2xl py-2 px-2 hover:bg-gray-50 transition duration-300 text-[20px]">
            <img src="/images/facebook.png" alt="Facebook" class="h-8 w-8">
            <span class="text-gray-700 font-bold">ข้อมูลข่าวสาร</span>
        </a>
    </div>

    <div class="mt-auto mb-8">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center justify-center w-full border border-gray-300 bg-red-100 rounded-2xl py-2 px-2 hover:bg-red-200 transition duration-300 text-[20px]">
                <span class="text-red-600 font-bold">ออกจากระบบ</span>
            </button>
        </form>
    </div>

    <div class="text-center text-gray-500 text-sm mb-4">
        <p>ผู้ใช้งาน: {{ Auth::user()->name }} {{ Auth::user()->lastname }}</p>
    </div>
</div>
@endsection