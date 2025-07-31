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

/* Profile section at bottom of sidebar */
.sidebar-profile {
    margin-top: auto;
    padding: 0;
}

.profile-card {
    background: white;
    border-radius: 12px;
    padding: 15px;
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

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
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

/* Donut Chart Hover Effects */
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

/* Chart Legend Styling - Enhanced for better interactivity */
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

/* Horizontal Legend Styling (like Image 2) */
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

/* Custom Tooltip Styling */
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

/* Bottom Row Equal Height - Fixed container heights */
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

/* Safe Area Cards */
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

/* Chart Container Improvements for Better Fitting */
.chart-container canvas {
    width: 100% !important;
    height: 100% !important;
    max-height: 100%;
}

/* Mental Health Chart Specific Styling */
.chart-container canvas#mentalHealthChart {
    border: none !important;
    background: transparent !important;
}

/* Hide any chart.js default borders or lines */
.chart-container .chartjs-render-monitor {
    border: none !important;
}

/* Specific fixes for chart overflow issues */
.bottom-row-container canvas {
    max-height: 300px !important;
}

/* Responsive Design */
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
}

/* Chart Animations */
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

/* Enhanced Chart Legend Animations */
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

/* Custom Scrollbar for Sidebar */
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

/* Loading Animation */
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

/* Additional improvements for chart containers */
.chart-container > div:last-child {
    position: relative;
    flex: 1;
    min-height: 0;
}

/* Ensure proper spacing and alignment */
.chart-legend-item span {
    display: flex;
    align-items: center;
    gap: 8px;
}

.chart-legend-item .text-end {
    text-align: right;
    min-width: 120px;
}

/* Tooltip styling improvements */
.chart-legend-item {
    user-select: none;
}

.chart-legend-item:active {
    transform: scale(0.98);
}

/* Sidebar height fix */
.sidebar .p-3 {
    height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Behavioral Report Specific Styling */
.behavioral-overview .row {
    margin: 0 -10px;
}

.behavioral-overview .col-md-2 {
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

/* Ensure equal width for behavioral report cards */
@media (min-width: 768px) {
    .behavioral-overview .col-md-2 {
        flex: 0 0 20%;
        max-width: 20%;
    }
}
</style>