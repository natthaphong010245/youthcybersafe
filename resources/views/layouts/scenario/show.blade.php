    <style>
        .option-card {
            transition: all 0.3s ease;
            border: 2px solid #E5E7EB;
            background: linear-gradient(135deg, #8B7FE8 0%, #9B8BF5 100%);
            color: white;
        }

        .option-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 127, 232, 0.4);
        }

        .option-card.selected {
            background: linear-gradient(135deg, #7C6FE0 0%, #8B7FE8 100%);
            box-shadow: 0 4px 20px rgba(139, 127, 232, 0.5);
        }

        .option-card input[type="radio"] {
            accent-color: white;
        }

        .option-card label span {
            color: white;
            font-weight: 500;
        }

        .result-content {
            background: white;
            border-radius: 24px;
            padding: 32px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        }

        .completion-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            padding: 20px; /* Added side padding */
            opacity: 0;
            transform: scale(0.95);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        #completionModal {
            display: none;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            padding: 20px; /* Added side padding */
        }

        #completionModal.show {
            display: flex !important;
            opacity: 1;
            pointer-events: auto;
        }

        .completion-modal.show {
            display: flex;
            opacity: 1;
            transform: scale(1);
        }

        .completion-content {
            background: white;
            border-radius: 24px;
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalScaleIn 0.5s ease-out;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            margin: 0 20px; /* Added horizontal margins */
        }

        .bullying-type {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8edff 100%);
            border-left: 4px solid #8B7FE8;
            padding: 16px;
            margin-bottom: 16px;
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .bullying-type:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(139, 127, 232, 0.2);
        }

        .bullying-title {
            color: #5A63D7;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 8px;
        }

        .bullying-description {
            color: #4A5568;
            font-size: 14px;
            line-height: 1.6;
        }

        .celebration-icon {
            background: linear-gradient(135deg, #8B7FE8 0%, #9B8BF5 100%);
            color: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin: 0 auto 20px;
            animation: celebrationPulse 2s infinite;
        }

        @keyframes celebrationPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        button {
            transition: background 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            transform: scale(1.05);
        }

        .completion-button {
            background: linear-gradient(135deg, #8B7FE8 0%, #9B8BF5 100%);
            color: white;
            padding: 14px 32px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0 8px;
        }

        .completion-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 127, 232, 0.4);
        }

        .completion-button.secondary {
            background: linear-gradient(135deg, #6B7280 0%, #9CA3AF 100%);
        }

        .completion-button.secondary:hover {
            box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4);
        }

        .button-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 24px;
        }

        .scroll-container {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .scroll-container::-webkit-scrollbar {
            width: 6px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background: #8B7FE8;
            border-radius: 10px;
        }

        .scroll-container::-webkit-scrollbar-thumb:hover {
            background: #7C6FE0;
        }

        @media (min-width: 768px) {
            .desktop-container {
                width: 90%;
                max-width: 1000px;
                margin: 0 auto;
            }
            
            .desktop-main {
                max-width: 768px;
                margin: 0 auto;
            }
        }

        @media (max-width: 640px) {
            .completion-content {
                margin: 0 10px; /* Smaller margins on mobile */
            }
            
            .button-container {
                flex-direction: column;
                align-items: center;
            }
            
            .completion-button {
                width: 100%;
                max-width: 250px;
            }
        }

        .modal-backdrop {
            opacity: 0;
            pointer-events: none;
        }

        .modal-backdrop.show {
            opacity: 1;
            transform: scale(1);
            pointer-events: auto;
        }

        .modal-content {
            animation: modalScaleIn 0.5s ease-out;
        }

        @keyframes modalScaleIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        #resultModal {
            display: none;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        #resultModal.show {
            display: flex !important;
            opacity: 1;
            pointer-events: auto;
        }
    </style>


<div class="content-section">
      <main class="bg-white rounded-top-section pb-10 desktop-main flex-grow h-full">
        
        <div id="intro-modal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="modal-content bg-white rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 text-center">
                <h3 class="text-xl font-bold text-indigo-800">{{ $scenario['title'] }}</h3>
                <p class="text-indigo-800 text-lg mb-4 font-medium">{{ $scenario['subtitle'] }}</p>
                <img src="{{ asset('images/material/school_man.png') }}" alt="School Girl Character"
                     class="w-32 h-auto mx-auto mb-6 object-contain">
                
                <p class="text-indigo-800 text-lg mb-2 font-medium">เริ่มความท้าทายกันเลย</p>
                
                <button onclick="startGame()" class="bg-[#929AFF] text-white text-lg py-1 px-6 rounded-xl transition-colors hover:bg-[#7C6FE0] font-medium">
                    เริ่ม
                </button>
            </div>
        </div>

        <div id="game-content" class="game-content">
          <div class="px-6">
              <div class="text-center">
                <h2 class="text-xl font-bold mb-1" style="color: #3E36AE;">{{ $scenario['title'] }}</h2>
                <p class="text-lg" style="color: #3E36AE;">{{ $scenario['subtitle'] }}</p>
              </div>

              <div class="text-center">
                <div class="bg-white rounded-2xl p-4 mx-auto max-w-lg">
                  <div class="border-2 border-gray-300 rounded-2xl p-4 bg-white">
                    <img src="{{ asset('images/scenarios/' . $scenario['scenarioImage']) }}" 
                        alt="{{ $scenario['altText'] }}" 
                        class="w-full h-200 object-contain rounded-xl"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display: none;" class="w-full h-64 bg-purple-100 rounded-xl flex items-center justify-center">
                      <div class="text-center">
                        <div class="w-20 h-20 bg-purple-200 rounded-full flex items-center justify-center mx-auto mb-3">
                          <span class="text-3xl font-bold text-purple-600">{{ $scenarioId }}</span>
                        </div>
                        <p class="text-purple-600 font-medium text-lg">{{ $scenario['title'] }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <form action="{{ route('scenario_' . $scenarioId . '.submit') }}" method="POST" id="scenarioForm">
                @csrf
                <div class="space-y-4 max-w-lg mb-4 mx-auto border-2 border-gray-300 rounded-2xl p-4 bg-white">
                  <h3 class="text-base font-semibold text-[#3E36AE] text-left mb-2">จากบทสนทนาน้องๆ คิดเห็นอย่างไร</h3>
                  
                  @foreach($scenario['options'] as $option)
                  <div 
                    class="option-card w-full max-w-[450px] min-h-[40px] mx-auto border border-[#838383] bg-[#929AFF] text-white 
                              rounded-xl flex items-center justify-center text-base font-medium shadow-sm 
                              hover:shadow-md transition-all duration-200 cursor-pointer py-2"
                    onclick="selectOption('{{ $option['id'] }}')"
                  >
                    <label class="w-full h-full flex items-center justify-center cursor-pointer px-4">
                      <input type="radio" name="option" value="{{ $option['id'] }}" class="sr-only" required>
                      <span class="text-center leading-relaxed break-words">{{ $option['text'] }}</span>
                    </label>
                  </div>
                  @endforeach
                </div>

                <div class="text-right">
                  <button type="button" 
                          onclick="skipScenario()"
                          class="bg-gradient-to-r from-[#929AFF] to-[#929AFF] text-white px-6 py-1 rounded-lg font-medium text-base hover:from-[#7C6FE0] hover:to-[#8B7FE8] transition-all duration-300 shadow-lg">
                    ข้าม
                  </button>
                </div>
              </form>

            <div id="resultModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex items-center justify-center z-50">
              <div class="result-content modal-content">
                <div id="resultImage" class="w-32 h-32 mx-auto mb-6">
                </div>
                
                <h2 id="resultTitle" class="text-2xl font-bold text-[#3E36AE] mb-4">
                </h2>
                
                <p id="resultMessage" class="text-[#3E36AE] leading-relaxed mb-8 text-lg">
                </p>
                
                <button onclick="goToNextScenario()" 
                        class="bg-gradient-to-r from-[#929AFF] to-[#929AFF] text-white px-6 py-1 rounded-lg font-medium hover:from-[#7C6FE0] hover:to-[#8B7FE8] transition-all duration-300">
                  ถัดไป
                </button>
              </div>
            </div>

            @include('layouts.scenario.bullying-types')
          </div>
        </div>
      </main>
    </div>