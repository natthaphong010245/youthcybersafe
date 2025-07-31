<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;

class ScenarioController extends Controller
{   

    
    
    private function getScenarioData()
    {
        return [
            '1' => [
                'title' => 'สถานการณ์ที่ 1',
                'subtitle' => 'การส่งข้อความที่ดูหมิ่น',
                'scenarioImage' => 'scenario_1.png',
                'altText' => 'Scenario 1 - Threatening Messages',
                'options' => [
                    [
                        'id' => 'stop',
                        'text' => 'ปล่อยว่าง ไม่เก็บมาใส่ใจมากเกินไป (Stop)',
                        'isCorrect' => true,
                        'feedback' => [
                            'title' => 'คุณทำถูกแล้ว',
                            'message' => 'มันเป็นธรรมชาติของเราแหละ ไม่เห็นจะผิดตรงไหนเลย สำเนียงหรือวิธีการพูดของแต่ละคนไม่เหมือนกัน มั่นใจในคุณค่าของตนเอง (BE STRONG)',
                            'type' => 'success'
                        ]
                    ],
                    [
                        'id' => 'fight_back',
                        'text' => 'ล้อมา ล้อกลับไม่โกง',
                        'isCorrect' => false,
                        'feedback' => [
                            'title' => 'คุณแน่ใจหรอที่จะทำแบบนี้',
                            'message' => 'เราไม่ชอบแบบไหนเราก็อย่าทำแบบนั้น การล้อคนอื่นมันไม่ดีหรอกนะ',
                            'type' => 'warning'
                        ]
                    ]
                ],
                'nextRoute' => 'scenario_2'
            ],
            
            '2' => [
                'title' => 'สถานการณ์ที่ 2',
                'subtitle' => 'การส่งข้อความที่ดูหมิ่น',
                'scenarioImage' => 'scenario_2.png',
                'altText' => 'Scenario 2 - Sharing Images Without Permission',
                'options' => [
                    [
                        'id' => 'report',
                        'text' => 'อ้วนแล้วไง เคยส่องกระจกดูตัวเองบ้างรึเปล่า',
                        'isCorrect' => false,
                        'feedback' => [
                            'title' => 'คุณแน่ใจหรอที่จะทำแบบนี้',
                            'message' => 'เราไม่ชอบแบบไหนเราก็อย่าทำแบบนั้นเลยนิ่งเฉยไม่ตอบโต้ เพื่อไม่ให้เกิดความรุนแรงดีกว่า (STOP)',
                            'type' => 'warning'
                        ]
                    ],
                    [
                        'id' => 'ignore',
                        'text' => 'นิ่งเฉยไม่ตอบโต้',
                        'isCorrect' => true,
                        'feedback' => [
                            'title' => 'คุณทำถูกแล้ว',
                            'message' => 'ตั้งสติ ปล่อยวาง นิ่งเฉยไม่ตอบโต้เพื่อไม่ให้เกิดการกระทำซ้ำหรือเพิ่มความรุนแรง (STOP)\n -ไม่ให้ค่ากับคนหรือคำพูดที่ทำร้ายเรา (BE STRONG)“คำพูดของคนอื่นไม่ได้กำหนดคุณค่าของคุณ”',
                            'type' => 'success'
                        ]
                    ]
                ],
                'nextRoute' => 'scenario_3'
            ],
            
            '3' => [
                'title' => 'สถานการณ์ที่ 3',
                'subtitle' => 'การก่อกวน',
                'scenarioImage' => 'scenario_3.png',
                'altText' => 'Scenario 3 - Being Excluded from Group',
                'options' => [
                    [
                        'id' => 'talk',
                        'text' => 'สนใจ ลองกดเข้าไปเล่น',
                        'isCorrect' => false,
                        'feedback' => [
                            'title' => 'คุณแน่ใจหรอที่จะทำแบบนี้',
                            'message' => 'อาจจะทำให้เพิ่มความรุนแรงและความเสียหายได้ “อย่าตอบกลับ! อย่ากดลิงก์! และอย่าหลงเชื่อเด็ดขาด!',
                            'type' => 'warning'
                        ]
                    ],
                    [
                        'id' => 'revenge',
                        'text' => 'BLOCK ผู้ใช้งานนี้',
                        'isCorrect' => true,
                        'feedback' => [
                            'title' => 'คุณทำถูกแล้ว',
                            'message' => '-อย่ากดลิงก์เด็ดขาด! ลิงก์ที่ส่งมาอาจมี ไวรัส หรือเป็น กลโกงหลอกเอาข้อมูลส่วนตัว\n-นอกจากนั้นแล้วอาจแจ้งผู้ดูแลระบบเพื่อความปลอดภัย (REMOVE)',
                            'type' => 'success'
                        ]
                    ]
                ],
                'nextRoute' => 'scenario_4'
            ],

                        '4' => [
                'title' => 'สถานการณ์ที่ 4',
                'subtitle' => 'การใส่ร้ายป้ายสี',
                'scenarioImage' => 'scenario_4.png',
                'altText' => 'Scenario 4',
                'options' => [
                    [
                        'id' => 'talk',
                        'text' => 'เข้าไปต่อว่าพูดถึงเราทำไม',
                        'isCorrect' => false,
                        'feedback' => [
                            'title' => 'คุณแน่ใจหรอที่จะทำแบบนี้',
                            'message' => 'วิธีนี้อาจเพิ่มความรุนแรงและการกระทำซ้ำขึ้นอีกก็ได้',
                            'type' => 'warning'
                        ]
                    ],
                    [
                        'id' => 'revenge',
                        'text' => 'ทำเป็นไม่สนใจ นิ่งเฉยไม่ตอบโต้',
                        'isCorrect' => true,
                        'feedback' => [
                            'title' => 'คุณทำถูกแล้ว',
                            'message' => 'เราอาจไม่สามารถห้ามใครไม่ให้พูดได้ แต่ทางออกที่ดีที่สุดคือการพูดคุยกับเพื่อนโดยตรง แทนที่จะใช้โซเชียลมีเดีย เพื่อป้องกันไม่ให้เหตุการณ์เกิดซ้ำหรือรุนแรงขึ้น',
                            'type' => 'success'
                        ]
                    ]
                ],
                'nextRoute' => 'scenario_5'
            ],

                        '5' => [
                'title' => 'สถานการณ์ที่ 5',
                'subtitle' => 'การสวมรอยเป็นคนอื่น',
                'scenarioImage' => 'scenario_5.png',
                'altText' => 'Scenario 5',
                'options' => [
                    [
                        'id' => 'talk',
                        'text' => 'ลองโอนเงินให้ทันที',
                        'isCorrect' => false,
                        'feedback' => [
                            'title' => 'คุณแน่ใจหรอที่จะทำแบบนี้',
                            'message' => 'อาจทำให้เกิดการกระทำซ้ำหรือเพิ่มความรุนแรง',
                            'type' => 'warning'
                        ]
                    ],
                    [
                        'id' => 'revenge',
                        'text' => 'ตั้งสติ นิ่งเฉยไม่ตอบโต้',
                        'isCorrect' => true,
                        'feedback' => [
                            'title' => 'คุณทำถูกแล้ว',
                             'message' => 'ดีแล้วที่ไม่รีบร้อน! การตรวจสอบตัวตนก่อนโอนเงินเป็นสิ่งสำคัญ เพราะมิจฉาชีพมักใช้วิธีแอบอ้างเป็นคนรู้จักเพื่อหลอกลวง',
                            'type' => 'success'
                        ]
                    ]
                ],
                'nextRoute' => 'scenario_6'
            ],

                      '6' => [
                'title' => 'สถานการณ์ที่ 6',
                'subtitle' => 'การสวมรอยเป็นคนอื่น',
                'scenarioImage' => 'scenario_6.png',
                'altText' => 'Scenario 6',
                'options' => [
                    [
                        'id' => 'talk',
                        'text' => 'คุยกับเพื่อนที่ทำและเตือนให้หยุดพฤติกรรมนี้',
                        'isCorrect' => true,
                        'feedback' => [
                            'title' => 'คุณทำถูกแล้ว',
                            'message' => 'หากเป็นการกระทำที่เกิดจากความคึกคะนอง อาจพูดคุยกับเพื่อนให้เข้าใจว่าการกระทำเป็นการละเมิดความเป็นส่วนตัว และอาจก่อให้เกิดปัญหาตามมาได้',
                            'type' => 'success'
                        ]
                    ],
                    [
                        'id' => 'revenge',
                        'text' => 'ลบเพื่อนคนนั้นออกไปเลย',
                        'isCorrect' => false,
                        'feedback' => [
                            'title' => 'คุณแน่ใจหรอที่จะทำแบบนี้',
                             'message' => 'เราไม่สามารถลบเพื่อนทุกคนได้ต่อไปเราก็อาจถูกแกล้งจากคนอื่นอยู่ดี',
                            'type' => 'warning'
                        ]
                    ]
                ],
                'nextRoute' => 'scenario_7'
            ],

                   '7' => [
                'title' => 'สถานการณ์ที่ 7',
                'subtitle' => 'เผยแพร่ข้อมูลส่วนตัวของผู้อื่น',
                'scenarioImage' => 'scenario_7.png',
                'altText' => 'Scenario 7',
                'options' => [
                    [
                        'id' => 'talk',
                        'text' => 'บอกเพื่อนตรงๆ ว่ารู้สึกไม่ดีขอให้ลบภาพออก',
                        'isCorrect' => true,
                        'feedback' => [
                            'title' => 'คุณทำถูกแล้ว',
                            'message' => 'ถ้าคุณรู้ว่าใครเป็นคนแคป ให้คุยตรงๆ ว่าทำไมถึงทำ และให้ลบข้อความนั้นออก\n -การแคปข้อความโดยไม่ขออนุญาต อาจทำลายความไว้ใจของเพื่อนๆ ได้ ระวังไว้เสมอว่า \n"สิ่งที่พูดในกลุ่ม ควรอยู่แค่ในกลุ่ม"',
                            'type' => 'success'
                        ]
                    ],
                    [
                        'id' => 'revenge',
                        'text' => 'โพสต์ความลับของเพื่อนคนนั้นคืนบ้าง',
                        'isCorrect' => false,
                        'feedback' => [
                            'title' => 'คุณแน่ใจหรอที่จะทำแบบนี้',
                             'message' => 'อาจทำให้เพิ่มความรุนแรงขึ้นได้ \nปล. การ Cyberbullying ผิดกฎหมาย พ.ร.บ.คอมพิวเตอร์ทั้งผู้โพสต์และผู้ผยแพร่ส่งต่อจะมีความผิดด้วยน้า',
                            'type' => 'warning'
                        ]
                    ]
                ],
                'nextRoute' => 'scenario_8'
            ],
            
                '8' => [
                'title' => 'สถานการณ์ที่ 8',
                'subtitle' => 'การสวมรอยเป็นคนอื่น',
                'scenarioImage' => 'scenario_8.png',
                'altText' => 'Scenario 8',
                'options' => [
                    [
                        'id' => 'talk',
                        'text' => 'บอกเพื่อนตรงๆ ว่ารู้สึกไม่ดีขอให้ลบภาพออก',
                        'isCorrect' => true,
                        'feedback' => [
                            'title' => 'คุณทำถูกแล้ว',
                            'message' => 'อาจแจ้งผู้ดูแลระบบเพื่อขอความร่วมมือและเพื่อความปลอดภัยของตัวเรา',
                            'type' => 'success'
                        ]
                    ],
                    [
                        'id' => 'revenge',
                        'text' => 'โพสต์บัตรประชาชนของเพื่อนคืนบ้าง',
                        'isCorrect' => false,
                        'feedback' => [
                            'title' => 'คุณแน่ใจหรอที่จะทำแบบนี้',
                             'message' => 'แกล้งกันไปมาอาจทำให้เพิ่มความรุนแรงขึ้นได้นะ\n-ปล. การ Cyberbullying ผิดกฎหมาย พ.ร.บ.คอมพิวเตอร์ทั้งผู้โพสต์และผู้เผยแพร่ส่งต่อจะมีความผิดด้วยน้า',
                            'type' => 'warning'
                        ]
                    ]
                ],
                'nextRoute' => 'scenario_9'
            ],
            
                    '9' => [
                'title' => 'สถานการณ์ที่ 9',
                'subtitle' => 'การขับออกจากกลุ่ม',
                'scenarioImage' => 'scenario_9.png',
                'altText' => 'Scenario 9',
                'options' => [
                    [
                        'id' => 'talk',
                        'text' => 'จมอยู่กับความเสียใจที่ไม่มีคนชวน',
                        'isCorrect' => false,
                        'feedback' => [
                            'title' => 'คุณแน่ใจหรอที่จะทำแบบนี้',
                            'message' => 'เพื่อนในกลุ่มอาจจะคิดว่าเราไม่อยากเข้าก็ได้นะ ลองเข้าไปถามเพื่อนๆดูไหม',
                            'type' => 'warning'
                        ]
                    ],
                    [
                        'id' => 'revenge',
                        'text' => 'ลองพูดคุยกับเพื่อนถึงสาเหตุ',
                        'isCorrect' => true,
                        'feedback' => [
                            'title' => 'คุณทำถูกแล้ว',
                             'message' => '-เพื่อนในกลุ่มอาจจะคิดว่าเราไม่อยากเข้าก็ได้หรือเราเผลอทำอะไรให้เพื่อนไม่สบายใจลองเข้าไปถามเพื่อนๆดูไหม\n-สงบสติอารมณ์และให้เวลาผ่านไปอาจทำให้สถานการณ์คลี่คลาย',
                            'type' => 'success'
                        ]
                    ]
                ],
                'nextRoute' => 'scenario_10'
            ],

                                '10' => [
                'title' => 'สถานการณ์ที่ 10',
                'subtitle' => 'เฝ้าติดตามทางอินเตอร์เน็ต',
                'scenarioImage' => 'scenario_10.png',
                'altText' => 'Scenario 10',
                'options' => [
                    [
                        'id' => 'talk',
                        'text' => 'บล็อกและรายงานบัญชีปลอม',
                        'isCorrect' => true,
                        'feedback' => [
                            'title' => 'คุณทำถูกแล้ว',
                            'message' => 'เพื่อป้องกันการเพิ่มความรุนแรง ควรนิ่งเฉยไม่ตอบโต้ (STOP)',
                            'type' => 'success'
                        ]
                    ],
                    [
                        'id' => 'revenge',
                        'text' => 'ไปออดิชั่นตามที่อยู่XX เพื่อสืบหาความจริง',
                        'isCorrect' => false,
                        'feedback' => [
                            'title' => 'คุณแน่ใจหรอที่จะทำแบบนี้',
                             'message' => 'BLOCK ปิดกั้นผู้ที่ระราน ไม่ให้สามารถติดต่อหรือระรานเอาได้อีกทั้งนี้อาจบอกพ่อแม่ ครู หรือบุคคลที่ไว้ใจ เพื่อขอความช่วยเหลือ',
                            'type' => 'warning'
                        ]
                    ]
                ],
                'nextRoute' => 'scenario_11'
            ],

               '11' => [
                'title' => 'สถานการณ์ที่ 11',
                'subtitle' => 'ถ่ายคลิปวิดิโอและเผยแพร่บนอินเตอร์เน็ต',
                'scenarioImage' => 'scenario_11.png',
                'altText' => 'Scenario 11',
                'options' => [
                    [
                        'id' => 'talk',
                        'text' => 'บอกเพื่อนตรงๆ ว่ารู้สึกไม่ดีขอให้ลบภาพออก',
                        'isCorrect' => true,
                        'feedback' => [
                            'title' => 'คุณทำถูกแล้ว',
                            'message' => 'การพูดคุยเพื่อแสดงความรู้สึกไม่สบายใจอาจช่วยให้เขาเข้าใจและให้ความสำคัญกับความเป็นส่วนตัวของน้องๆมากขึ้น',
                            'type' => 'success'
                        ]
                    ],
                    [
                        'id' => 'revenge',
                        'text' => 'ขู่ว่าครั้งหน้าถ้าเผลอระวังตัวไว้นะ จะแก้แค้นแบบนี้บ้าง',
                        'isCorrect' => false,
                        'feedback' => [
                            'title' => 'คุณแน่ใจหรอที่จะทำแบบนี้',
                             'message' => 'แบบนี้เราจะยิ่งเป็นจุดสนใจบางเรื่องปล่อยวางได้ก็ดีนะ อย่าเก็บเอามาใส่ใจเลย',
                            'type' => 'warning'
                        ]
                    ]
                ],
                'nextRoute' => 'scenario_12'
            ],

                           '12' => [
                'title' => 'สถานการณ์ที่ 12',
                'subtitle' => 'ส่งต่อภาพหรือวีดีโอที่ล่อแหลมทางเพศ',
                'scenarioImage' => 'scenario_12.png',
                'altText' => 'Scenario 12',
                'options' => [
                    [
                        'id' => 'talk',
                        'text' => 'ด่าสวนกลับไปทันที',
                        'isCorrect' => false,
                        'feedback' => [
                            'title' => 'คุณแน่ใจหรอที่จะทำแบบนี้',
                            'message' => 'แบบนี้อาจเพิ่มจุดสนใจหรือความรุนแรงได้อาจลองบอกบุคคลที่ไว้ใจ เช่น ครู พ่อแม่ เพื่อขอความช่วยเหลือพร้อมกับเก็บหลักฐานไปแจ้งความดำเนินคดี (กรณีเป็นเรื่องที่ผิดกฎหมายหรือถูกขู่คุกคาม)(Tell)',
                            'type' => 'warning'
                        ]
                    ],
                    [
                        'id' => 'revenge',
                        'text' => 'บอกบุคคลที่ไว้ใจ เช่น ครู พ่อแม่ เพื่อขอความช่วยเหลือพร้อมกับเก็บหลักฐานไปแจ้งความดำเนินคดี',
                        'isCorrect' => true,
                        'feedback' => [
                            'title' => 'คุณทำถูกแล้ว',
                             'message' => '- (BE Strong) เข้มแข็งและอดทนไว้น้า เดี๋ยวมันจะผ่านไป\n-ปล. ข้อควรระวัง รู้หรือไม่ว่า Cyberbullying ผิดกฎหมาย พรบ คอมพิวเตอร์ มาตรา 14',
                            'type' => 'success'
                        ]
                    ]
                ],
                'nextRoute' => 'scenario_13'
            ],

                                       '13' => [
                'title' => 'สถานการณ์ที่ 13',
                'subtitle' => 'ส่งต่อภาพหรือวีดีโอที่ล่อแหลมทางเพศ',
                'scenarioImage' => 'scenario_13.png',
                'altText' => 'Scenario 13',
                'options' => [
                    [
                        'id' => 'talk',
                        'text' => 'ส่งให้อีกเพื่อเพื่อยอด Like และ หัวใจ',
                        'isCorrect' => false,
                        'feedback' => [
                            'title' => 'คุณแน่ใจหรอที่จะทำแบบนี้',
                            'message' => 'การตัดสินใจโพสต์เนื้อหาประเภทนี้เป็นเรื่องส่วนตัว ซึ่งน้องๆ ควรคำนึงถึงความพร้อมทางจิตใจและผลกระทบที่อาจเกิดขึ้นในอนาคต',
                            'type' => 'warning'
                        ]
                    ],
                    [
                        'id' => 'revenge',
                        'text' => 'ลองพิจารณาถึงข้อดีและข้อเสียที่อาจเกิดขึ้นหลังจากการโพสต์',
                        'isCorrect' => true,
                        'feedback' => [
                            'title' => 'คุณทำถูกแล้ว',
                             'message' => 'การตัดสินใจโพสต์เนื้อหาประเภทนี้เป็นเรื่องส่วนตัว ซึ่งน้องๆ ควรคำนึงถึงความพร้อมทางจิตใจและผลกระทบที่อาจเกิดขึ้นในอนาคต',
                            'type' => 'success'
                        ]
                    ]
                ],
                'nextRoute' => 'scenario_13'
            ],
           
           
           

        ];
    }

    
    public function index()
    {
        $scenarios = [];
        for ($i = 1; $i <= 13; $i++) {
            $scenarios[] = [
                'id' => $i,
                'title' => "สถานการณ์ที่ {$i}",
                'image' => "scenario_{$i}_thumb.png",
                'route' => "scenario_{$i}"
            ];
        }

        return view('scenario.index', compact('scenarios'));
    }

    public function show($id)
    {
        $scenarioData = $this->getScenarioData();
        
        if (!isset($scenarioData[$id])) {
            abort(404);
        }

        return view('scenario.show', [
            'scenario' => $scenarioData[$id],
            'scenarioId' => $id
        ]);
    }

    public function submitAnswer($id)
    {
        $scenarioData = $this->getScenarioData();
        
        if (!isset($scenarioData[$id])) {
            abort(404);
        }

        $selectedOption = request('option');
        $scenario = $scenarioData[$id];
        
        // หา option ที่ถูกเลือก
        $feedback = null;
        foreach ($scenario['options'] as $option) {
            if ($option['id'] === $selectedOption) {
                $feedback = $option['feedback'];
                break;
            }
        }

        return view('scenario.result', [
            'scenario' => $scenario,
            'scenarioId' => $id,
            'feedback' => $feedback,
            'selectedOption' => $selectedOption
        ]);

        
    }

    

    // Methods สำหรับแต่ละสถานการณ์
    public function scenario1() { return $this->show('1'); }
    public function scenario2() { return $this->show('2'); }
    public function scenario3() { return $this->show('3'); }
    public function scenario4() { return $this->show('4'); }
    public function scenario5() { return $this->show('5'); }
    public function scenario6() { return $this->show('6'); }
    public function scenario7() { return $this->show('7'); }
    public function scenario8() { return $this->show('8'); }
    public function scenario9() { return $this->show('9'); }
    public function scenario10() { return $this->show('10'); }
    public function scenario11() { return $this->show('11'); }
    public function scenario12() { return $this->show('12'); }
    public function scenario13() { return $this->show('13'); }

    // Method สำหรับ submit คำตอบ
    public function submitScenario1() { return $this->submitAnswer('1'); }
    public function submitScenario2() { return $this->submitAnswer('2'); }
    public function submitScenario3() { return $this->submitAnswer('3'); }
    public function submitScenario4() { return $this->submitAnswer('4'); }
    public function submitScenario5() { return $this->submitAnswer('5'); }
    public function submitScenario6() { return $this->submitAnswer('6'); }
    public function submitScenario7() { return $this->submitAnswer('7'); }
    public function submitScenario8() { return $this->submitAnswer('8'); }
    public function submitScenario9() { return $this->submitAnswer('9'); }
    public function submitScenario10() { return $this->submitAnswer('10'); }
    public function submitScenario11() { return $this->submitAnswer('11'); }
    public function submitScenario12() { return $this->submitAnswer('12'); }
    public function submitScenario13() { return $this->submitAnswer('13'); }



    
}




