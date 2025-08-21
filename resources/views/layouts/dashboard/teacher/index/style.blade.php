<style>
.dashboard-equal-height {
    height: 400px !important;
    display: flex;
    flex-direction: column;
}

.dashboard-content-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.dashboard-table-container {
    flex: 1;
    overflow-y: auto;
    max-height: 300px;
}

.dashboard-table-container::-webkit-scrollbar {
    width: 4px;
}

.dashboard-table-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}

.dashboard-table-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

.dashboard-table-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
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

.dashboard-pagination {
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 2px;
    font-size: 12px;
}

.dashboard-pagination .page-item {
    margin: 0;
}

.dashboard-pagination .page-link {
    color: #626DF7;
    border: none;
    padding: 4px 8px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 400;
    background-color: transparent;
    transition: all 0.2s ease-in-out;
    min-width: 24px;
    min-height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
}

.dashboard-pagination .page-item.active .page-link {
    background-color: #626DF7;
    color: white;
    font-weight: 500;
}

.dashboard-pagination .page-item.disabled .page-link {
    color: #d0d0d0;
    cursor: not-allowed;
    opacity: 0.5;
}

.dashboard-pagination .page-item:not(.disabled) .page-link:hover {
    background-color: #f0f0f0;
    color: #333;
}

.dashboard-pagination .page-arrow {
    color: #70757a;
    font-size: 10px;
    padding: 4px 6px;
    min-width: 22px;
    min-height: 22px;
}

.student-reports-table {
    font-size: 13px;
    margin-bottom: 0;
}

.student-reports-table th,
.student-reports-table td {
    padding: 8px 12px;
    vertical-align: middle;
}

.student-reports-table th {
    background-color: #f8f9fa;
    border-top: none;
    font-size: 12px;
    font-weight: 600;
    color: #2d3748;
}

.student-reports-table td {
    font-size: 12px;
    line-height: 1.4;
}

@media (max-width: 768px) {
    .dashboard-equal-height {
        height: auto !important;
        min-height: 300px;
    }
    
    .dashboard-table-container {
        max-height: 200px;
    }
    
    .student-reports-table th,
    .student-reports-table td {
        padding: 6px 8px;
        font-size: 11px;
    }
}

.dashboard-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100px;
    color: #6c757d;
}

.dashboard-loading i {
    margin-right: 8px;
}
</style>