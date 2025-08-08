{{-- resources/views/layouts/dashboard/style.blade.php --}}
<style>
@import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap');

body {
    font-family: 'Kanit', sans-serif;
    background: #f5f5f5;
}

.sidebar {
    background: #f5f5f5;
    box-shadow: 2px 0 10px rgba(0,0,0,0.05);
    position: fixed;
    height: 100vh;
    overflow-y: auto;
    border-right: 1px solid #e0e0e0;
}

.detial-graph{
    font-size: 12px;
}

.sidebar .logo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #667eea;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sidebar .nav-link {
    color: #878AA2;
    border-radius: 12px;
    margin: 5px 5px;
    transition: all 0.3s ease;
    font-weight: 400;
    font-size: 1.25rem;
    background: transparent;
}

.sidebar .nav-link:hover {
    color: #626DF7;
}

.sidebar .nav-link.active {
    background: #626DF7;
    color: white !important;
    box-shadow: 0 4px 10px rgba(98, 109, 247, 0.3);
}

.sidebar-profile {
    margin-top: auto;
    padding: 0;
}

.profile-card {
    background: white;
    border-radius: 12px;
    padding: 15px 5px 15px 5px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.05);
    margin: 10px 5px;
}

.profile-card strong {
    font-size: 1.25rem;
    color: #3A3A3A;
    display: block;
    font-weight: 600;
}

.profile-card small {
    color: #878AA2;
    font-size: 0.875rem;
    margin-top: 2px;
    display: block;
}

.main-content {
    margin-left: 16.66667%;
    background: #f5f5f5;
    min-height: 100vh;
    padding: 30px;
}

.main-content h2 {
    color: #313131;
    font-weight: 600;
    font-size: 3rem;
    margin: 10 0 10 0;
}

.stat-card {
    border-radius: 20px;
    border: none;
    padding: 25px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}


.title-head{
    color: #252525;
    font-weight: 600;
    font-size: 2rem;
}

.stat-card-pink { 
    background: #FFBCC5;
}

.stat-card-orange { 
    background: #FFE7B6;
}

.stat-card-green { 
    background: #B5FDCE;
}

.stat-card-purple { 
    background: #E4C6FF;
}

.stat-card-blue { 
    background: #B8E2FF;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    color: white;
    font-size: 1.5rem;
}

.stat-icon-pink { background: #FA5A7E; }
.stat-icon-orange { background: #FF957A; }
.stat-icon-green { background: #3CD956; }
.stat-icon-purple { background: #BF83FF; }
.stat-icon-blue { background: #4252B8; }

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    margin-top: 10px;
    color: #2d3748;
}

.stat-label {
    font-size: 1rem;
    font-weight: 500;
    color: #434343;
}

.chart-container {
    background: white;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.05);
    transition: box-shadow 0.3s ease;
    height: auto;
}

.chart-container:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
}

.chart-container h6 {
    color: #2d3748;
    font-weight: 600;
    font-size: 1.4rem;
}

.chart-container {
    color: #6c757d !important;
}
.text-muted {
    color: #6c757d !important;
    margin-bottom: 6px;
    font-size: 1rem;
}

.donut-chart {
    position: relative;
    width: 200px;
    height: 200px;
    margin: 20px auto;
}

.donut-chart canvas {
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
}

.donut-chart:hover canvas {
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.15));
}

.chart-legend {
    margin-top: 20px;
    padding: 0;
}

.chart-legend-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 8px;
    border-radius: 12px;
    margin: 6px 0;
    transition: all 0.3s ease;
    cursor: pointer;
    border: 2px solid transparent;
    position: relative;
}

.chart-legend-item:hover {
    background: rgba(212, 218, 255, 0.3);
    padding: 12px 16px;
    transform: scale(1.02);
    border-color: rgba(212, 218, 255, 0.5);
}

.chart-legend-item.active {
    background: linear-gradient(135deg, #D4DAFF 0%, #E8EBFF 100%);
    padding: 12px 16px;
    box-shadow: 0 4px 12px rgba(212, 218, 255, 0.4);
    border-color: #8593ED;
    transform: scale(1.03);
}

.chart-legend-item.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #8593ED;
    border-radius: 2px;
}

.chart-legend-horizontal {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
    gap: 20px;
}

.legend-item-horizontal {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 10px;
    border-radius: 8px;
    flex: 1;
}

.legend-item-horizontal:hover {
    background: rgba(212, 218, 255, 0.2);
    transform: translateY(-2px);
}

.legend-item-horizontal.active {
    background: rgba(212, 218, 255, 0.4);
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(212, 218, 255, 0.3);
}

.legend-item-horizontal i {
    font-size: 12px;
    margin-bottom: 8px;
    display: block;
}

.legend-item-horizontal span {
    font-size: 16px;
    font-weight: 500;
    color: #333;
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-item-horizontal span i {
    margin-bottom: 0;
    margin-right: 0;
}

.legend-stats {
    font-size: 16px;
    color: #666;
}

.legend-stats div:first-child {
    font-weight: 500;
}

.legend-stats div:last-child {
    font-weight: 600;
    color: #333;
}

.custom-tooltip {
    position: absolute;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    z-index: 1000;
    pointer-events: none;
    display: none;
    min-width: 150px;
}

.tooltip-content {
    text-align: center;
}

.tooltip-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 4px;
}

.tooltip-subtitle {
    font-size: 12px;
    margin-bottom: 4px;
    opacity: 0.9;
}

.tooltip-label {
    font-size: 11px;
    opacity: 0.8;
}

.bottom-row-container {
    height: 450px;
    display: flex;
    flex-direction: column;
}

.bottom-row-container .chart-container {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.safe-area-cards {
    display: flex;
    gap: 15px;
    flex: 1;
    align-items: stretch;
}

.safe-area-card {
    flex: 1;
    padding: 25px;
    border-radius: 15px;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 150px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.safe-area-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.1);
}

.safe-area-card-blue {
    background: #B8E2FF;
}

.safe-area-card-pink {
    background: #FFACAC;
}

.safe-area-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    color: white;
    font-size: 1.5rem;
}

.safe-area-icon-blue {
    background: #0999FF;
}

.safe-area-icon-pink {
    background: #FB5959;
}

.safe-area-card .stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: #2d3748;
}

.safe-area-card .stat-label {
    font-size: 1rem;
    color: #2d3748;
    opacity: 0.8;
}

.chart-container canvas {
    width: 100% !important;
    height: 100% !important;
    max-height: 100%;
}

.chart-container canvas#mentalHealthChart {
    border: none !important;
    background: transparent !important;
}

.chart-container .chartjs-render-monitor {
    border: none !important;
}

/* New styles for behavioral report */
.col-md-2_4 {
    flex: 0 0 20%;
    max-width: 20%;
    padding: 0 10px;
}

.report-row {
    transition: background-color 0.2s ease;
    cursor: pointer;
}

.report-row:hover {
    background-color: #f8f9fa !important;
}

.indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.5);
    cursor: pointer;
    transition: all 0.3s ease;
}

.indicator.active {
    background-color: white;
    transform: scale(1.2);
}

.image-container img {
    transition: transform 0.3s ease;
}

.image-nav-left:hover,
.image-nav-right:hover {
    background: linear-gradient(to right, rgba(0,0,0,0.1), transparent);
}

.image-nav-right:hover {
    background: linear-gradient(to left, rgba(0,0,0,0.1), transparent);
}

.btn-disabled {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}

.message-content {
    font-size: 24px;
    line-height: 1.6;
    color: #333;
}

.message-content::-webkit-scrollbar {
    width: 6px;
}

.message-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.message-content::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.message-content::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.table th {
    font-weight: 600;
    color: #2d3748;
    border-bottom: 2px solid #e2e8f0;
}

.table td {
    vertical-align: middle;
    border-bottom: 1px solid #e2e8f0;
}

.pagination .page-link {
    color: #626DF7;
    border-color: #e2e8f0;
}

.pagination .page-item.active .page-link {
    background-color: #626DF7;
    border-color: #626DF7;
}

.pagination .page-link:hover {
    color: #4c5eef;
    background-color: #f8f9ff;
    border-color: #e2e8f0;
}

.form-select:focus {
    border-color: #626DF7;
    box-shadow: 0 0 0 0.2rem rgba(98, 109, 247, 0.25);
}

.year-selector select {
    border: 2px solid #e3e6f0;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 14px;
    font-weight: 500;
    color: #5a5c69;
    background-color: white;
    transition: all 0.3s ease;
}

.year-selector select:focus {
    border-color: #4C6FFF;
    box-shadow: 0 0 0 0.2rem rgba(76, 111, 255, 0.25);
    outline: none;
}

.year-selector select:hover {
    border-color: #4C6FFF;
}

.chart-loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    border-radius: 8px;
}

.loading-spinner {
    text-align: center;
}

.loading-spinner p {
    color: #6c757d;
    margin: 0;
    font-size: 14px;
}

@media (max-width: 768px) {
    .sidebar {
        position: relative;
        height: auto;
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .stat-card {
        height: 140px;
        margin-bottom: 15px;
    }
    
    .stat-number {
        font-size: 2.2rem;
    }
    
    .safe-area-cards {
        flex-direction: column;
        gap: 10px;
    }
    
    .bottom-row-container {
        height: auto;
        min-height: 350px;
    }
    
    .col-md-2_4 {
        flex: 0 0 50%;
        max-width: 50%;
        margin-bottom: 15px;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chart-container {
    animation: fadeInUp 0.6s ease-out;
}

.stat-card {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes highlightPulse {
    0% {
        box-shadow: 0 2px 8px rgba(212, 218, 255, 0.4);
    }
    50% {
        box-shadow: 0 4px 16px rgba(212, 218, 255, 0.6);
    }
    100% {
        box-shadow: 0 2px 8px rgba(212, 218, 255, 0.4);
    }
}

.chart-legend-item.active {
    animation: highlightPulse 2s ease-in-out infinite;
}

.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.1);
    border-radius: 3px;
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 3px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.5);
}

.loading {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255,255,255,.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.chart-container > div:last-child {
    position: relative;
    flex: 1;
    min-height: 0;
}

.chart-legend-item span {
    display: flex;
    align-items: center;
    gap: 8px;
}

.chart-legend-item .text-end {
    text-align: right;
    min-width: 120px;
}

.chart-legend-item {
    user-select: none;
}

.chart-legend-item:active {
    transform: scale(0.98);
}

.sidebar .p-3 {
    height: 100vh;
    display: flex;
    flex-direction: column;
}

.behavioral-overview .row {
    margin: 0 -10px;
}

.behavioral-overview .col-md-2_4 {
    padding: 0 10px;
    margin-bottom: 20px;
}

.behavioral-overview .stat-card {
    height: 200px;
    min-height: 200px;
}

.behavioral-overview .stat-label {
    font-size: 0.9rem;
    line-height: 1.3;
    text-align: center;
}

.stat-icon-blue-custom { 
    background: #0999FF !important;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    color: white;
    font-size: 1.5rem;
}

.stat-icon-pink-custom { 
    background: #FB5959 !important;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    color: white;
    font-size: 1.5rem;
}

/* Badge styles */
.badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

/* Modal improvements */
.modal-xl {
    max-width: 1200px;
}

.modal-header {
    padding-bottom: 0;
    border-bottom: none;
}

.modal-header h5 {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 0;
}

.modal-header h6 {
    color: #6c757d;
    font-weight: 400;
    margin-bottom: 0;
}

.modal-body {
    padding: 20px 30px 30px;
}

/* Image gallery styles */
.image-gallery {
    margin-bottom: 20px;
}

.image-container {
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.controls-section {

    padding-top: 15px;
}

.audio-control {
    font-size: 14px;
    color: #6c757d;
}

.content-section h6 {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 15px;
}

/* Responsive adjustments */
@media (max-width: 992px) {
    .modal-xl {
        max-width: 95%;
        margin: 20px auto;
    }
    
    .modal-body {
        padding: 15px 20px 20px;
    }
    
    .row .col-md-6 {
        margin-bottom: 20px;
    }
}

/* Additional utility classes */
.text-truncate-custom {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

/* Focus states */
.btn:focus,
.form-select:focus {
    box-shadow: 0 0 0 0.2rem rgba(98, 109, 247, 0.25);
}

/* Custom scrollbar for modal content */
.modal-body::-webkit-scrollbar {
    width: 6px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Hover effects for interactable elements */
.table-hover tbody tr:hover {
    background-color: rgba(98, 109, 247, 0.05);
}

/* Status badge hover effects */
.badge {
    transition: all 0.2s ease;
}

/* Image indicator improvements */
.image-indicators {
    backdrop-filter: blur(4px);
    background: rgba(0, 0, 0, 0.2);
    padding: 5px 10px;
    border-radius: 15px;
}

/* Button improvements */
.btn {
    transition: all 0.2s ease;
    font-weight: 500;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn:active {
    transform: translateY(0);
}

/* Modal backdrop improvements */
.modal-backdrop {
    backdrop-filter: blur(2px);
}

/* Enhanced focus indicators */
.btn:focus-visible,
.form-select:focus-visible,
.page-link:focus-visible {
    outline: 2px solid #626DF7;
    outline-offset: 2px;
}

/* Loading states */
.btn.loading {
    pointer-events: none;
    opacity: 0.7;
}

.btn.loading::after {
    content: "";
    width: 16px;
    height: 16px;
    margin-left: 8px;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    display: inline-block;
    animation: spin 1s linear infinite;
}
</style>