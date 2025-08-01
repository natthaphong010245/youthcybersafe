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

        /* CSS สำหรับ Custom Dropdown */
        .custom-select {
            position: relative;
        }

        .custom-select-options {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 0.75rem;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            margin-top: 0.25rem;
            animation: slideDown 0.2s ease-out;
        }

        .custom-select-options.hidden {
            display: none;
        }

        .custom-select-option {
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
            font-size: 1rem;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
        }

        .custom-select-option:last-child {
            border-bottom: none;
        }

        .custom-select-option:hover {
            background-color: #f9fafb;
        }

        .custom-select-option.selected {
            background-color: #929AFF;
            color: white;
        }

        .custom-select-option.selected:hover {
            background-color: #7B84EA;
        }

        /* ปรับแต่ง scrollbar */
        .custom-select-options::-webkit-scrollbar {
            width: 6px;
        }

        .custom-select-options::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .custom-select-options::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .custom-select-options::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Firefox scrollbar */
        .custom-select-options {
            scrollbar-width: thin;
            scrollbar-color: #c1c1c1 #f1f1f1;
        }

        /* Animation สำหรับ dropdown */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* เปลี่ยนสี placeholder เมื่อยังไม่ได้เลือก */
        .custom-select span {
            color: #9ca3af;
            transition: color 0.2s ease;
        }

        .custom-select span.text-gray-700 {
            color: #374151 !important;
        }

        /* ปรับ arrow transition */
        .custom-select svg {
            transition: transform 0.2s ease;
        }

        /* สำหรับ disabled state */
        .school-disabled {
            opacity: 0.5;
            pointer-events: none;
            background-color: #f9fafb !important;
        }

        .school-disabled span {
            color: #9ca3af !important;
        }

        /* Responsive สำหรับ mobile */
        @media (max-width: 640px) {
            .custom-select-options {
                max-height: 150px;
            }
            
            .custom-select-option {
                padding: 0.625rem 1rem;
                font-size: 0.875rem;
            }
        }

        /* สำหรับกรณีที่ dropdown อาจต้องแสดงทางด้านบน */
        .custom-select-options.dropdown-up {
            top: auto;
            bottom: 100%;
            margin-top: 0;
            margin-bottom: 0.25rem;
            animation: slideUp 0.2s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="bg-white w-full flex-grow rounded-t-[50px] px-10 pt-8 flex flex-col mt-16">
        <h1 class="text-center mb-6 text-[#3F359E] text-3xl font-bold">
            ลงทะเบียน
        </h1>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <!-- Custom Dropdown สำหรับบทบาท -->
            <div class="mb-4">
                <label for="role" class="block text-left text-[16px] font-medium mb-1">บทบาท</label>
                <div class="relative custom-select">
                    <div id="role-select" class="w-full px-6 pr-14 py-3 border {{ $errors->has('role') ? 'border-red-500' : 'border-gray-400' }} rounded-xl text-gray-700 focus:outline-none focus:border-[#929AFF] cursor-pointer bg-white">
                        <span id="role-selected">--บทบาท--</span>
                        <div class="pointer-events-none absolute inset-y-0 right-6 flex items-center">
                            <svg class="h-6 w-6 text-gray-500 transform transition-transform" id="role-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    
                    <div id="role-options" class="custom-select-options hidden">
                        <div class="custom-select-option" data-value="teacher">คุณครู</div>
                        <div class="custom-select-option" data-value="researcher">นักวิจัย</div>
                    </div>
                    
                    <!-- Hidden input สำหรับส่งข้อมูล -->
                    <input type="hidden" id="role" name="role" value="{{ old('role') }}">
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

            <!-- Custom Dropdown สำหรับโรงเรียน -->
            <div class="mb-4" id="school-container">
                <label for="school" class="block text-left text-[16px] font-medium mb-1">
                    โรงเรียน
                    <span class="text-sm text-red-500" id="school-required-indicator">*</span>
                    <span class="text-sm text-gray-500" id="school-optional-indicator" style="display: none;">(ไม่จำเป็นสำหรับนักวิจัย)</span>
                </label>
                <div class="relative custom-select">
                    <div id="school-select" class="w-full px-6 pr-14 py-3 border {{ $errors->has('school') ? 'border-red-500' : 'border-gray-400' }} rounded-xl text-gray-700 focus:outline-none focus:border-[#929AFF] cursor-pointer bg-white">
                        <span id="school-selected">--โรงเรียน--</span>
                        <div class="pointer-events-none absolute inset-y-0 right-6 flex items-center">
                            <svg class="h-6 w-6 text-gray-500 transform transition-transform" id="school-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    
                    <div id="school-options" class="custom-select-options hidden">
                        <div class="custom-select-option" data-value="โรงเรียนวาวีวิทยาคม">โรงเรียนวาวีวิทยาคม</div>
                        <div class="custom-select-option" data-value="โรงเรียนสหศาสตร์ศึกษา">โรงเรียนสหศาสตร์ศึกษา</div>
                        <div class="custom-select-option" data-value="โรงเรียนราชประชานุเคราะห์ 62">โรงเรียนราชประชานุเคราะห์ 62</div>
                        <div class="custom-select-option" data-value="โรงเรียนห้วยไร่สามัคคี">โรงเรียนห้วยไร่สามัคคี</div>
                    </div>
                    
                    <!-- Hidden input สำหรับส่งข้อมูล -->
                    <input type="hidden" id="school" name="school" value="{{ old('school') }}">
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
            // ฟังก์ชันสำหรับสร้าง custom dropdown
            function createCustomDropdown(selectId, optionsId, selectedId, arrowId, hiddenInputId) {
                const selectElement = document.getElementById(selectId);
                const optionsElement = document.getElementById(optionsId);
                const selectedElement = document.getElementById(selectedId);
                const arrowElement = document.getElementById(arrowId);
                const hiddenInput = document.getElementById(hiddenInputId);
                
                let isOpen = false;
                
                // เปิด/ปิด dropdown
                selectElement.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    // ตรวจสอบว่า dropdown นี้ถูก disable หรือไม่
                    if (selectElement.classList.contains('school-disabled')) {
                        return;
                    }
                    
                    // ปิด dropdown อื่นๆ ก่อน
                    closeAllDropdowns();
                    
                    if (!isOpen) {
                        optionsElement.classList.remove('hidden');
                        arrowElement.style.transform = 'rotate(180deg)';
                        selectElement.classList.add('border-[#929AFF]');
                        isOpen = true;
                        
                        // ปรับตำแหน่ง dropdown ให้ไม่ล้นออกจากหน้าจอ
                        adjustDropdownPosition();
                    } else {
                        closeDropdown();
                    }
                });
                
                // เลือกตัวเลือก
                optionsElement.addEventListener('click', function(e) {
                    if (e.target.classList.contains('custom-select-option')) {
                        const value = e.target.getAttribute('data-value');
                        const text = e.target.textContent;
                        
                        selectedElement.textContent = text;
                        selectedElement.classList.remove('text-gray-500');
                        selectedElement.classList.add('text-gray-700');
                        hiddenInput.value = value;
                        
                        // ลบการเลือกเก่า
                        optionsElement.querySelectorAll('.custom-select-option').forEach(option => {
                            option.classList.remove('selected');
                        });
                        
                        // เพิ่มการเลือกใหม่
                        e.target.classList.add('selected');
                        
                        // *** ควบคุมโรงเรียน เมื่อเลือก role ***
                        if (hiddenInputId === 'role') {
                            handleRoleChange(value);
                        }
                        
                        closeDropdown();
                    }
                });
                
                function closeDropdown() {
                    optionsElement.classList.add('hidden');
                    arrowElement.style.transform = 'rotate(0deg)';
                    selectElement.classList.remove('border-[#929AFF]');
                    isOpen = false;
                }
                
                function adjustDropdownPosition() {
                    const rect = selectElement.getBoundingClientRect();
                    const optionsRect = optionsElement.getBoundingClientRect();
                    const viewportHeight = window.innerHeight;
                    
                    // ตรวจสอบว่า dropdown จะล้นล่างหรือไม่
                    if (rect.bottom + optionsRect.height > viewportHeight - 20) {
                        // แสดง dropdown ทางด้านบน
                        optionsElement.style.top = 'auto';
                        optionsElement.style.bottom = '100%';
                        optionsElement.style.marginBottom = '0.25rem';
                        optionsElement.classList.add('dropdown-up');
                    } else {
                        // แสดง dropdown ทางด้านล่าง (ปกติ)
                        optionsElement.style.top = '100%';
                        optionsElement.style.bottom = 'auto';
                        optionsElement.style.marginTop = '0.25rem';
                        optionsElement.classList.remove('dropdown-up');
                    }
                }
                
                // ส่งกลับฟังก์ชัน close เพื่อใช้ปิดจากภายนอก
                return closeDropdown;
            }
            
            // *** ฟังก์ชันปรับปรุงควบคุมการแสดงผลโรงเรียน ***
            function handleRoleChange(role) {
                const schoolContainer = document.getElementById('school-container');
                const schoolSelect = document.getElementById('school-select');
                const schoolSelected = document.getElementById('school-selected');
                const schoolHiddenInput = document.getElementById('school');
                const schoolRequiredIndicator = document.getElementById('school-required-indicator');
                const schoolOptionalIndicator = document.getElementById('school-optional-indicator');
                
                if (role === 'researcher') {
                    // เพิ่ม disabled class และแสดงข้อความว่าไม่จำเป็น
                    schoolSelect.classList.add('school-disabled');
                    schoolRequiredIndicator.style.display = 'none';
                    schoolOptionalIndicator.style.display = 'inline';
                    
                    // รีเซ็ตค่าโรงเรียน
                    schoolSelected.textContent = '--ไม่จำเป็นสำหรับนักวิจัย--';
                    schoolSelected.classList.remove('text-gray-700');
                    schoolSelected.classList.add('text-gray-500');
                    schoolHiddenInput.value = '';
                    
                    // ลบการเลือกทั้งหมด
                    document.querySelectorAll('#school-options .custom-select-option').forEach(option => {
                        option.classList.remove('selected');
                    });
                } else if (role === 'teacher') {
                    // เอา disabled class ออกและแสดงเครื่องหมาย * 
                    schoolSelect.classList.remove('school-disabled');
                    schoolRequiredIndicator.style.display = 'inline';
                    schoolOptionalIndicator.style.display = 'none';
                    
                    // รีเซ็ตข้อความ placeholder
                    if (schoolHiddenInput.value === '') {
                        schoolSelected.textContent = '--โรงเรียน--';
                        schoolSelected.classList.remove('text-gray-700');
                        schoolSelected.classList.add('text-gray-500');
                    }
                } else {
                    // กรณีไม่มีการเลือกบทบาท
                    schoolSelect.classList.add('school-disabled');
                    schoolRequiredIndicator.style.display = 'none';
                    schoolOptionalIndicator.style.display = 'none';
                    
                    schoolSelected.textContent = '--โรงเรียน--';
                    schoolSelected.classList.remove('text-gray-700');
                    schoolSelected.classList.add('text-gray-500');
                    schoolHiddenInput.value = '';
                }
            }
            
            // สร้าง dropdown สำหรับบทบาท
            const closeRoleDropdown = createCustomDropdown(
                'role-select', 
                'role-options', 
                'role-selected', 
                'role-arrow', 
                'role'
            );
            
            // สร้าง dropdown สำหรับโรงเรียน
            const closeSchoolDropdown = createCustomDropdown(
                'school-select', 
                'school-options', 
                'school-selected', 
                'school-arrow', 
                'school'
            );
            
            // ฟังก์ชันปิด dropdown ทั้งหมด
            function closeAllDropdowns() {
                closeRoleDropdown();
                closeSchoolDropdown();
            }
            
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.custom-select')) {
                    closeAllDropdowns();
                }
            });
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAllDropdowns();
                }
            });
            
            function restoreSelectedValues() {
                const roleValue = document.getElementById('role').value;
                const schoolValue = document.getElementById('school').value;
                
                if (roleValue) {
                    const roleOption = document.querySelector(`#role-options [data-value="${roleValue}"]`);
                    if (roleOption) {
                        document.getElementById('role-selected').textContent = roleOption.textContent;
                        document.getElementById('role-selected').classList.remove('text-gray-500');
                        document.getElementById('role-selected').classList.add('text-gray-700');
                        roleOption.classList.add('selected');
                        
                        handleRoleChange(roleValue);
                    }
                } else {
                    handleRoleChange('');
                }
                
                if (schoolValue && roleValue === 'teacher') {
                    const schoolOption = document.querySelector(`#school-options [data-value="${schoolValue}"]`);
                    if (schoolOption) {
                        document.getElementById('school-selected').textContent = schoolOption.textContent;
                        document.getElementById('school-selected').classList.remove('text-gray-500');
                        document.getElementById('school-selected').classList.add('text-gray-700');
                        schoolOption.classList.add('selected');
                    }
                }
            }
            
            // เรียกใช้งานทันทีเมื่อโหลดหน้า
            restoreSelectedValues();

            // JavaScript เดิมสำหรับ password toggle
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

            document.querySelector('form').addEventListener('submit', function(e) {
                const roleValue = document.getElementById('role').value;
                const schoolValue = document.getElementById('school').value;
                
                if (roleValue === 'researcher') {
                    document.getElementById('school').value = '';
                }
                
                if (roleValue === 'teacher' && !schoolValue) {
                }
            });
        });
    </script>

    @extends('layouts.login&register.script')
@endsection