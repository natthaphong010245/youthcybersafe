@extends('layouts.login&register.index')

@section('content')
    <style>
        .eye-icon-container {
            position: absolute;
            top: 50%;
            right: 1.5rem;
            transform: translateY(-50%);
            width: 1.5rem;
            height: 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6B7280;
            transition: color 0.2s;
        }
        
        .eye-icon-container:hover {
            color: #374151;
        }
        
        .eye-icon-container svg {
            width: 100%;
            height: 100%;
            display: block;
        }
        
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
    </style>

    <div class="bg-white w-full flex-grow rounded-t-[50px] px-10 pt-8 flex flex-col mt-16">
        <h1 class="text-center mb-6 text-[#3F359E] text-3xl font-bold">
            ลงทะเบียน
        </h1>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="mb-4">
                <label for="role" class="block text-left text-[16px] font-medium mb-1">บทบาท</label>
                <div class="relative">
                    <select id="role" name="role"
                        class="w-full px-6 pr-14 py-3 border {{ $errors->has('role') ? 'border-red-500' : 'border-gray-400' }} rounded-xl text-gray-700 focus:outline-none focus:border-[#929AFF] appearance-none">
                        <option value="" disabled selected>--บทบาท--</option>
                        <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>คุณครู</option>
                        <option value="researcher" {{ old('role') == 'researcher' ? 'selected' : '' }}>นักวิจัย</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-6 flex items-center">
                        <svg class="h-6 w-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                @if ($errors->has('role'))
                    <div class="flex items-center mt-1 text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">{{ $errors->first('role') }}</span>
                    </div>
                @endif
            </div>

            <div class="mb-4" id="school-container">
                <label for="school" class="block text-left text-[16px] font-medium mb-1">โรงเรียน</label>
                <div class="relative">
                    <select id="school" name="school"
                        class="w-full px-6 pr-14 py-3 border {{ $errors->has('school') ? 'border-red-500' : 'border-gray-400' }} rounded-xl text-gray-700 focus:outline-none focus:border-[#929AFF] appearance-none">
                        <option value="" disabled selected>--โรงเรียน--</option>
                        <option value="school1" {{ old('school') == 'school1' ? 'selected' : '' }}>โรงเรียน 1</option>
                        <option value="school2" {{ old('school') == 'school2' ? 'selected' : '' }}>โรงเรียน 2</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-6 flex items-center">
                        <svg class="h-6 w-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                @if ($errors->has('school'))
                    <div class="flex items-center mt-1 text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">{{ $errors->first('school') }}</span>
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <label for="name" class="block text-left text-[16px] font-medium mb-1">ชื่อ</label>
                <input type="text" id="name" name="name" placeholder="กรุณากรอกชื่อ" value="{{ old('name') }}"
                    class="w-full px-6 py-3 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-400' }} rounded-xl text-gray-700 placeholder-gray-500 focus:outline-none focus:border-[#929AFF]">
                @if ($errors->has('name'))
                    <div class="flex items-center mt-1 text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">{{ $errors->first('name') }}</span>
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <label for="lastname" class="block text-left text-[16px] font-medium mb-1">นามสกุล</label>
                <input type="text" id="lastname" name="lastname" placeholder="กรุณากรอกนามสกุล"
                    value="{{ old('lastname') }}"
                    class="w-full px-6 py-3 border {{ $errors->has('lastname') ? 'border-red-500' : 'border-gray-400' }} rounded-xl text-gray-700 placeholder-gray-500 focus:outline-none focus:border-[#929AFF]">
                @if ($errors->has('lastname'))
                    <div class="flex items-center mt-1 text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">{{ $errors->first('lastname') }}</span>
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <label for="username" class="block text-left text-[16px] font-medium mb-1">ชื่อผู้ใช้</label>
                <input type="text" id="username" name="username" placeholder="กรุณากรอกชื่อผู้ใช้"
                    value="{{ old('username') }}"
                    class="w-full px-6 py-3 border {{ $errors->has('username') ? 'border-red-500' : 'border-gray-400' }} rounded-xl text-gray-700 placeholder-gray-500 focus:outline-none focus:border-[#929AFF]">
                @if ($errors->has('username'))
                    <div class="flex items-center mt-1 text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">{{ $errors->first('username') }}</span>
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <label for="password" class="block text-left text-[16px] font-medium mb-1">รหัสผ่าน</label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="กรุณากรอกรหัสผ่าน"
                        class="w-full px-6 pr-14 py-3 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-400' }} rounded-xl text-gray-700 placeholder-gray-500 focus:outline-none focus:border-[#929AFF]">
                    <div id="togglePassword" class="eye-icon-container" style="display: none;">
                        <svg id="eyeIconPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>
                @if ($errors->has('password'))
                    <div class="flex items-center mt-1 text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">{{ $errors->first('password') }}</span>
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <label for="password_confirmation"
                    class="block text-left text-[16px] font-medium mb-1">ยืนยันรหัสผ่าน</label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="กรุณากรอกยืนยันรหัสผ่าน"
                        class="w-full px-6 pr-14 py-3 border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-400' }} rounded-xl text-gray-700 placeholder-gray-500 focus:outline-none focus:border-[#929AFF]">
                    <div id="togglePasswordConfirmation" class="eye-icon-container" style="display: none;">
                        <svg id="eyeIconPasswordConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>
                @if ($errors->has('password_confirmation'))
                    <div class="flex items-center mt-1 text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">{{ $errors->first('password_confirmation') }}</span>
                    </div>
                @endif
            </div>

            <button type="submit"
                class="bg-[#929AFF] hover:bg-[#7B84EA] text-white py-2.5 px-6 rounded-xl text-center transition mt-6 duration-300 w-[100%] mx-auto text-[20px] font-median">
                ลงทะเบียน
            </button>

        </form>
        <a href="{{ route('home') }}"
            class="bg-[#ffffff] py-2.5 px-6 rounded-xl text-center transition duration-300 w-[100%] mx-auto text-[20px] mt-2 mb-8 text-gray-400 border border-gray-400 font-median active:border-white">
            ยกเลิก
        </a>

        <div class="py-3 text-center text-sm mt-auto ">
            <p class=" text-[16px]">เข้าสู่ระบบสำหรับใช้งาน <a href="{{ route('login') }}"
                    class="text-[#3F359E] text-[16px] active:text-[#827ea4]">เข้าสู่ระบบ</a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const eyeOpenPath = {
                path1: "M15 12a3 3 0 11-6 0 3 3 0 016 0z",
                path2: "M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
            };
            
            const eyeClosedPath = {
                path1: "M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"
            };

            function updateIcon(iconElement, paths) {
                const pathElements = iconElement.querySelectorAll('path');
                pathElements.forEach(path => path.remove());
                
                if (paths.path1) {
                    const path1 = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    path1.setAttribute('stroke-linecap', 'round');
                    path1.setAttribute('stroke-linejoin', 'round');
                    path1.setAttribute('stroke-width', '2');
                    path1.setAttribute('d', paths.path1);
                    iconElement.appendChild(path1);
                }
                
                if (paths.path2) {
                    const path2 = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    path2.setAttribute('stroke-linecap', 'round');
                    path2.setAttribute('stroke-linejoin', 'round');
                    path2.setAttribute('stroke-width', '2');
                    path2.setAttribute('d', paths.path2);
                    iconElement.appendChild(path2);
                }
            }

            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIconPassword = document.getElementById('eyeIconPassword');
            let isPasswordVisible = false;
            
            passwordInput.addEventListener('input', function() {
                if (this.value.length > 0) {
                    togglePassword.style.display = 'flex';
                    updateIcon(eyeIconPassword, eyeOpenPath);
                    isPasswordVisible = false;
                } else {
                    togglePassword.style.display = 'none';
                    this.setAttribute('type', 'password');
                    isPasswordVisible = false;
                }
            });

            togglePassword.addEventListener('click', function() {
                if (isPasswordVisible) {
                    passwordInput.setAttribute('type', 'password');
                    updateIcon(eyeIconPassword, eyeOpenPath);
                    isPasswordVisible = false;
                } else {
                    passwordInput.setAttribute('type', 'text');
                    updateIcon(eyeIconPassword, eyeClosedPath);
                    isPasswordVisible = true;
                }
            });

            const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const eyeIconPasswordConfirm = document.getElementById('eyeIconPasswordConfirm');
            let isPasswordConfirmVisible = false;

            passwordConfirmationInput.addEventListener('input', function() {
                if (this.value.length > 0) {
                    togglePasswordConfirmation.style.display = 'flex';
                    updateIcon(eyeIconPasswordConfirm, eyeOpenPath);
                    isPasswordConfirmVisible = false;
                } else {
                    togglePasswordConfirmation.style.display = 'none';
                    this.setAttribute('type', 'password');
                    isPasswordConfirmVisible = false;
                }
            });

            togglePasswordConfirmation.addEventListener('click', function() {
                if (isPasswordConfirmVisible) {
                    passwordConfirmationInput.setAttribute('type', 'password');
                    updateIcon(eyeIconPasswordConfirm, eyeOpenPath);
                    isPasswordConfirmVisible = false;
                } else {
                    passwordConfirmationInput.setAttribute('type', 'text');
                    updateIcon(eyeIconPasswordConfirm, eyeClosedPath);
                    isPasswordConfirmVisible = true;
                }
            });
        });
    </script>

    @extends('layouts.login&register.script')
@endsection