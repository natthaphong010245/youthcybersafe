<style>
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

.stat-number {
    transition: all 0.3s ease;
}

.stat-card:hover .stat-number {
    transform: scale(1.05);
}
</style>