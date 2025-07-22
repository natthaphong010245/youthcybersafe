@extends('layouts.main_category')

@php
    $mainUrl = '/main';
    
    $consultations = [
        [
            'name' => 'โรงพยาบาลเชียงรายประชานุเคราะห์',
            'phone' => '0-5391-0600',
            'image' => 'โรงพยาบาลเชียงรายประชานุเคราะห์.png',
            'link' => 'https://maps.app.goo.gl/DynUSEJk6xggFRw78',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลศูนย์การแพทย์มหาวิทยาลัยแม่ฟ้าหลวง',
            'phone' => '053-914000',
            'image' => 'โรงพยาบาลศูนย์การแพทย์มหาวิทยาลัยแม่ฟ้าหลวง.png',
            'link' => 'https://maps.app.goo.gl/krKaH9ir3JkedVZ99',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลแม่จัน',
            'phone' => '053-771300',
            'image' => 'โรงพยาบาลแม่จัน.png',
            'link' => 'https://maps.app.goo.gl/Tia6CpMaPSoeoJWR8',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลแม่สรวย',
            'phone' => '053-786017',
            'image' => 'โรงพยาบาลแม่สรวย.png',
            'link' => 'https://maps.app.goo.gl/WacYS9WTZaYTRitp6',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลแม่ฟ้าหลวง',
            'phone' => '053-730357-8',
            'image' => 'โรงพยาบาลแม่ฟ้าหลวง.png',
            'link' => 'https://maps.app.goo.gl/FowAWFw9D64q6K147',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลขุนตาล',
            'phone' => '053-606221',
            'image' => 'โรงพยาบาลขุนตาล.png',
            'link' => 'https://maps.app.goo.gl/Tgv3FKmGEaga3wL58',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลเชียงแสน',
            'phone' => '053-777035',
            'image' => 'โรงพยาบาลเชียงแสน.png',
            'link' => 'https://maps.app.goo.gl/uzTJz28bNtjvcSGK7',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลดอยหลวง',
            'phone' => '053-790056',
            'image' => 'โรงพยาบาลดอยหลวง.png',
            'link' => 'https://maps.app.goo.gl/RXuR6eM4cu69S5kk9',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลเทิง',
            'phone' => '053-795259',
            'image' => 'โรงพยาบาลเทิง.png',
            'link' => 'https://maps.app.goo.gl/RpqWpPdmzTFjV8sh6',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลป่าแดด',
            'phone' => '053-761019',
            'image' => 'โรงพยาบาลป่าแดด.png',
            'link' => 'https://maps.app.goo.gl/kA2fTcH4rtC73aEL7',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลพญาเม็งราย',
            'phone' => '053-799033',
            'image' => 'โรงพยาบาลพญาเม็งราย.png',
            'link' => 'https://maps.app.goo.gl/qGQQiYggcsYXnmWw8',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลพาน',
            'phone' => '053-721345',
            'image' => 'โรงพยาบาลพาน.png',
            'link' => 'https://maps.app.goo.gl/qfJdLgtbTskpfUyF7',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลแม่ลาว',
            'phone' => '053-603103',
            'image' => 'โรงพยาบาลแม่ลาว.png',
            'link' => 'https://maps.app.goo.gl/YxqZunjcehydgdqG9',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลแม่สาย',
            'phone' => '053-731300',
            'image' => 'โรงพยาบาลแม่สาย.png',
            'link' => 'https://maps.app.goo.gl/rLA3m2u8Ci4Pttfr7',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลเวียงแก่น',
            'phone' => '053-603140',
            'image' => 'โรงพยาบาลเวียงแก่น.png',
            'link' => 'https://maps.app.goo.gl/DmAMiznLJEajhx4y8',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลเวียงเชียงรุ้ง',
            'phone' => '053-953137',
            'image' => 'โรงพยาบาลเวียงเชียงรุ้ง.png',
            'link' => 'https://maps.app.goo.gl/gY3NcXf6w2VM7Djx9',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลเวียงป่าเป้า',
            'phone' => '053-781342',
            'image' => 'โรงพยาบาลเวียงป่าเป้า.png',
            'link' => 'https://maps.app.goo.gl/4wAbf6YhehwL7LpN9',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลสมเด็จพระญาณสังวร',
            'phone' => '053-603123',
            'image' => 'โรงพยาบาลสมเด็จพระญาณสังวร.png',
            'link' => 'https://maps.app.goo.gl/Pd4JExHDw6XmZpK48',
            'image_size' => 'w-20 h-20',
        ],
        [
            'name' => 'โรงพยาบาลสมเด็จพระยุพราชเชียงของ',
            'phone' => '053-791206',
            'image' => 'โรงพยาบาลสมเด็จพระยุพราชเชียงของ.png',
            'link' => 'https://maps.app.goo.gl/7YCnJMVSSvdSMedX7',
            'image_size' => 'w-20 h-20',
        ]
    ];
@endphp

@section('content')
    <div class="card-container px-5 pb-6">
        <div class="text-center mb-10 relative">
            <div class="flex items-center justify-center">
                <div class="relative">
                    <h1 class="text-2xl font-bold text-[#3E36AE] inline-block">ขอคำปรึกษาจากหน่วยงาน</h1>
                    <p class="text-sm text-[#3E36AE] absolute -bottom-5 right-0">ประเทศไทย</p>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            @foreach($consultations as $consultation)
                @include('report&consultation.request_consultation.province.card', $consultation)
            @endforeach
        </div>
    </div>
@endsection