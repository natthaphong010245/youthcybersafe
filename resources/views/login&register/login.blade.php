{{-- resources/views/layouts/login&register/login.blade.php --}}
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

        .modal-container {
            border: 3px solid #e5e7eb;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        @media (max-width: 640px) {
            .modal-container {
                border: none;
            }
        }
    </style>

    <div class="bg-white w-full flex-grow rounded-t-[50px] px-10 pt-8 flex flex-col mt-16">
        <h1 class="text-center mb-6 text-[#3F359E] text-3xl font-bold">
            เข้าสู่ระบบ
        </h1>

        @if($errors->has('username') && $errors->first('username') == 'คุณไม่มีสิทธิ์เข้าถึงระบบ')
        <div id="accessDeniedModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 px-4">
            <div class="bg-white rounded-3xl p-8 w-full max-w-md mx-auto text-center modal-container">
                <div class="text-red-500 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-800">ขออภัย</h3>
                <p class="mb-3 text-gray-600 text-lg">คุณไม่มีสิทธิ์เข้าถึงระบบ</p>
                <p class="mb-8 text-gray-600 text-lg">เจ้าหน้าที่กำลังตรวจสอบคำขอคุณ</p>
                <button id="closeAccessDeniedModal" class="bg-[#929AFF] hover:bg-[#7B84EA] text-white py-3 px-8 rounded-2xl text-center transition duration-300 w-full font-bold text-lg">
                    ตกลง
                </button>
            </div>
        </div>
        @endif

        @if($errors->has('username') && $errors->first('username') == 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง')
        <div id="invalidCredentialsModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 px-4">
            <div class="bg-white rounded-3xl p-8 w-full max-w-md mx-auto text-center modal-container">
                <div class="text-red-500 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-800">เข้าสู่ระบบไม่สำเร็จ</h3>
                <p class="mb-8 text-gray-600 text-lg">ชื่อผู้ใช้ หรือรหัสผ่านไม่ถูกต้อง</p>
                <button id="closeInvalidCredentialsModal" class="bg-[#929AFF] hover:bg-[#7B84EA] text-white py-3 px-8 rounded-2xl text-center transition duration-300 w-full font-bold text-lg">
                    ตกลง
                </button>
            </div>
        </div>
        @endif

        @if(session('success') && session('success') == 'ออกจากระบบเรียบร้อยแล้ว')
        <div id="logoutSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 px-4">
            <div class="bg-white rounded-3xl p-8 w-full max-w-md mx-auto text-center modal-container">
                <div class="text-green-500 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-800">ขอบคุณที่ใช้บริการ</h3>
                <p class="mb-8 text-gray-600 text-lg">ออกจากระบบเรียบร้อยแล้ว</p>
                <button id="closeLogoutSuccessModal" class="bg-[#929AFF] hover:bg-[#7B84EA] text-white py-3 px-8 rounded-2xl text-center transition duration-300 w-full font-bold text-lg">
                    ตกลง
                </button>
            </div>
        </div>
        @endif

        @if(session('success') && session('success') != 'ออกจากระบบเรียบร้อยแล้ว')
        <div id="registerSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 px-4">
            <div class="bg-white rounded-3xl p-8 w-full max-w-md mx-auto text-center modal-container">
                <div class="text-green-500 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-800">ลงทะเบียนสำเร็จ!</h3>
                <p class="mb-8 text-gray-600 text-lg">{{ session('success') }}</p>
                <button id="closeRegisterSuccessModal" class="bg-[#929AFF] hover:bg-[#7B84EA] text-white py-3 px-8 rounded-2xl text-center transition duration-300 w-full font-bold text-lg">
                    ตกลง
                </button>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('login.authenticate') }}">
            @csrf

            <div class="mb-4">
                <label for="username" class="block text-left text-[16px] font-medium mb-1">ชื่อผู้ใช้</label>
                <input type="text" id="username" name="username" placeholder="กรุณากรอกชื่อผู้ใช้" value="{{ old('username') }}"
                    class="w-full px-6 py-3 border {{ $errors->has('username') ? 'border-red-500' : 'border-gray-400' }} rounded-xl text-gray-700 placeholder-gray-500 focus:outline-none focus:border-[#929AFF]">
                @if($errors->has('username') && $errors->first('username') != 'คุณไม่มีสิทธิ์เข้าถึงระบบ' && $errors->first('username') != 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง')
                    <div class="flex items-center mt-1 text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
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
                @if($errors->has('password'))
                    <div class="flex items-center mt-1 text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">{{ $errors->first('password') }}</span>
                    </div>
                @endif
            </div>

            <button type="submit"
                class="bg-[#929AFF] hover:bg-[#7B84EA] text-white py-2.5 px-6 rounded-xl text-center transition mt-6 duration-300 w-[100%] mx-auto text-[20px] font-median">
                เข้าสู่ระบบ
            </button>
        </form>

        <a href="{{ route('home') }}"
            class="bg-[#ffffff] py-2.5 px-6 rounded-xl text-center transition duration-300 w-[100%] mx-auto text-[20px] mt-8 mb-8 text-gray-400 border border-gray-400 font-median active:border-white">
            ยกเลิก
        </a>

        <div class="py-3 text-center text-sm mt-auto ">
            <p class=" text-[16px]">ลงทะเบียนสำหรับใช้งาน <a href="{{ route('register') }}"
                    class="text-[#3F359E] text-[16px] active:text-[#827ea4]">ลงทะเบียน</a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accessDeniedModal = document.getElementById('accessDeniedModal');
            const closeAccessDeniedButton = document.getElementById('closeAccessDeniedModal');
            
            if (closeAccessDeniedButton) {
                closeAccessDeniedButton.addEventListener('click', function() {
                    accessDeniedModal.classList.add('hidden');
                });
            }
            
            const invalidCredentialsModal = document.getElementById('invalidCredentialsModal');
            const closeInvalidCredentialsButton = document.getElementById('closeInvalidCredentialsModal');
            
            if (closeInvalidCredentialsButton) {
                closeInvalidCredentialsButton.addEventListener('click', function() {
                    invalidCredentialsModal.classList.add('hidden');
                });
            }
            
            const logoutSuccessModal = document.getElementById('logoutSuccessModal');
            const closeLogoutSuccessButton = document.getElementById('closeLogoutSuccessModal');
            
            if (closeLogoutSuccessButton) {
                closeLogoutSuccessButton.addEventListener('click', function() {
                    logoutSuccessModal.classList.add('hidden');
                });
            }
            
            const registerSuccessModal = document.getElementById('registerSuccessModal');
            const closeRegisterSuccessButton = document.getElementById('closeRegisterSuccessModal');
            
            if (closeRegisterSuccessButton) {
                closeRegisterSuccessButton.addEventListener('click', function() {
                    registerSuccessModal.classList.add('hidden');
                });
            }

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
        });
    </script>
@endsection