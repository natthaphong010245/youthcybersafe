@extends('layouts.home.index')

@section('content')
    <div class="bg-white w-full flex-grow rounded-t-[50px] px-6 pt-8 flex flex-col mt-12">
        <div class="text-center mb-6">
            <h1 class="text-[#3F359E] text-[24px] font-bold">
                สำนักวิชาวิทยาศาสตร์สุขภาพ
            </h1>
            <p class="text-[#3F359E] text-[22px] font-bold">มหาวิทยาลัยแม่ฟ้าหลวง</p>
        </div>

        <a href="{{ route('main') }}"
            class="bg-[#929AFF]  text-white py-2.5 px-6 rounded-2xl text-center transition duration-300 w-[85%] mx-auto text-[20px] active:bg-[#7B84EA]">
            เข้าสู่เว็บไซต์
        </a>

        <div class="flex items-center my-8">
            <div class="flex-grow h-px bg-gray-400"></div>
            <span class="px-4 text-gray-700 text-sm text-[20px]">หรือ</span>
            <div class="flex-grow h-px bg-gray-400"></div>
        </div>

        <div class="space-y-3 mb-8">
            <a href="https://www.facebook.com/profile.php?id=61577920375564" target="_blank"
                class="flex items-center justify-center space-x-6 w-full border border-gray-300 rounded-2xl py-2 px-2 transition duration-300 text-[20px] active:border-white">
                <img src="/images/line.png" alt="Line" class="h-8 w-8">
                <span class="text-gray-700 font-bold">พื้นที่พูดคุย</span>
            </a>

            <a href="https://lin.ee/ZLnJCpu" target="_blank"
                class="flex items-center justify-center space-x-5 w-full border border-gray-300 rounded-2xl py-2 px-2 transition duration-300 text-[20px] active:border-white">
                <img src="/images/facebook.png" alt="Facebook" class="h-8 w-8">
                <span class="text-gray-700 font-bold">ข้อมูลข่าวสาร</span>
            </a>
        </div>

        <div class="py-3 text-center text-sm mt-auto">
            <p class="text-[16px]">เข้าสู่ระบบสำหรับผู้ดูแลที่นี่ <a href="{{ route('login') }}"
                    class="text-[#3F359E] text-[16px] active:text-[#827ea4]">เข้าสู่ระบบ</a></p>
        </div>
    </div>
@endsection
