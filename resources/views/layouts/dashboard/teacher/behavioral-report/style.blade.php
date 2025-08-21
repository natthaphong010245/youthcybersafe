<style>
.behavioral-table th,
.behavioral-table td {
    text-align: center !important;
    vertical-align: middle !important;
}

.behavioral-table {
    border-collapse: collapse;
    border: 2px solid #ddd;
}

.behavioral-table th {
    font-weight: 600;
    color: #2d3748;
    border: 1px solid #ddd;
    padding: 12px;
    background-color: #f8f9fa;
}

.behavioral-table td {
    border: 1px solid #ddd;
    padding: 12px;
    vertical-align: middle;
}

.behavioral-table tbody tr:nth-child(odd) {
    background-color: #f9f9f9;
}

.behavioral-table tbody tr:nth-child(even) {
    background-color: white;
}

.report-row:hover {
    background-color: #f0f0f0 !important;
}

.pagination-wrapper {
    display: inline-flex;
    align-items: center;
    font-family: 'Kanit', sans-serif;
}

.google-pagination {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 2px;
}

.google-pagination .page-item {
    margin: 0;
}

.google-pagination .page-link {
    color: #453d9c;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 400;
    background-color: transparent;
    transition: all 0.2s ease-in-out;
    min-width: 34px;
    min-height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
}

.google-pagination .page-item.active .page-link {
    background-color: #626DF7;
    color: white;
    font-weight: 500;
    border-radius: 4px;
    pointer-events: none;
}

.google-pagination .page-item.disabled .page-link {
    color: #d0d0d0 !important;
    background-color: #f8f9fa !important;
    cursor: not-allowed !important;
    opacity: 0.5 !important;
    pointer-events: none;
}

.google-pagination .page-item:not(.disabled) .page-link:hover {
    background-color: #f0f0f0 !important;
    color: #333 !important;
    transform: translateY(-1px);
}

.google-pagination .page-arrow {
    color: #70757a !important;
    font-size: 11px;
    padding: 6px 8px !important;
    min-width: 32px !important;
    min-height: 32px !important;
}

.google-pagination .dots {
    color: #70757a !important;
    cursor: default;
    padding: 6px 4px !important;
}

#filterToggle {
    background: white;
    border: 2px solid #ddd;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    color: #2d3748;
    transition: all 0.2s ease;
    min-width: 120px;
    position: relative;
}

#filterToggle:hover {
    border-color: #999;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#filterToggle:focus {
    border-color: #626DF7;
    box-shadow: 0 0 0 0.2rem rgba(98, 109, 247, 0.25);
}

.filter-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    z-index: 1050;
    min-width: 160px;
    margin-top: 8px;
}

.filter-dropdown-content {
    background: white;
    border: 2px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    animation: fadeInUp 0.2s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.filter-option {
    padding: 12px 16px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
    font-weight: 500;
    color: #2d3748;
    border-bottom: 1px solid #f0f0f0;
}

.filter-option:last-child {
    border-bottom: none;
}

.filter-option:hover {
    background-color: #f8f9ff;
    color: #626DF7;
}

.filter-option.active {
    background-color: #626DF7;
    color: white;
}

.image-container img {
    transition: transform 0.3s ease;
}

.indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.5);
    cursor: pointer;
    transition: all 0.3s ease;
}

.indicator.active {
    background-color: white;
    transform: scale(1.3);
}

.audio-control,
.location-control {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 10px;
    border-radius: 10px;
}

.audio-control:hover,
.location-control:hover {
    background-color: rgba(98, 109, 247, 0.1);
    transform: translateY(-2px);
}

.audio-icon,
.location-icon {
    width: 48px;
    height: 48px;
    background: rgba(98, 109, 247, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.audio-control:hover .audio-icon,
.location-control:hover .location-icon {
    background: rgba(98, 109, 247, 0.2);
    transform: scale(1.05);
}

.audio-icon.playing {
    background: rgba(98, 109, 247, 0.2);
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(98, 109, 247, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(98, 109, 247, 0); }
    100% { box-shadow: 0 0 0 0 rgba(98, 109, 247, 0); }
}

.message-content {
    font-size: 16px;
    line-height: 1.8;
}

.message-content h6 {
    color: #343A81;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 15px;
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 10px;
}

.modal.show .modal-backdrop,
.modal-backdrop.show,
.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.4) !important;
    backdrop-filter: blur(3px) !important;
    -webkit-backdrop-filter: blur(3px) !important;
    opacity: 1 !important;
}

body .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.4) !important;
    backdrop-filter: blur(3px) !important;
    -webkit-backdrop-filter: blur(3px) !important;
    opacity: 1 !important;
}

#confirmationModal .modal-content {
    background: white;
    border-radius: 20px;
    border: none;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
}

#confirmationModal .modal-body {
    padding: 30px;
}

#reportDetailModal .modal-backdrop,
#confirmationModal .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.4) !important;
    backdrop-filter: blur(3px) !important;
    -webkit-backdrop-filter: blur(3px) !important;
}

#confirmationModal {
    z-index: 1060 !important;
}

#confirmationModal .modal-backdrop {
    z-index: 1055;
}

.confirmation-custom-backdrop {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background-color: rgba(0, 0, 0, 0.4) !important;
    backdrop-filter: blur(3px) !important;
    -webkit-backdrop-filter: blur(3px) !important;
    z-index: 1055 !important;
    opacity: 1 !important;
}

#confirmationModal .modal-dialog {
    z-index: 1060 !important;
    position: relative !important;
}

.message-content::-webkit-scrollbar {
    width: 4px;
}

.message-content::-webkit-scrollbar-track {
    background: #e9ecef;
    border-radius: 2px;
}

.message-content::-webkit-scrollbar-thumb {
    background: #626DF7;
    border-radius: 2px;
}

.btn-close-custom {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-close-custom:hover {
    transform: scale(1.1);
}

#reviewBtn {
    transition: all 0.3s ease;
    box-shadow: 0 3px 10px rgba(98, 109, 247, 0.3);
}

#reviewBtn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(98, 109, 247, 0.4);
}

#reviewBtn:disabled {
    box-shadow: none;
    transform: none;
}
</style>