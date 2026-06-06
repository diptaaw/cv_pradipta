@extends('admin.layout')

@section('title', 'Analytics Dashboard — Coming Soon')
@section('page-title', 'Analytics')

@section('breadcrumb')
    <span>&rarr;</span> <span class="active">Analytics</span>
@endsection

@section('content')
    <!-- Coming Soon Banner -->
    <div style="background: rgba(123, 97, 255, 0.08); border: 1px solid rgba(123, 97, 255, 0.20); border-radius: 18px; padding: 24px; margin-bottom: 28px; display: flex; align-items: center; gap: 20px; backdrop-filter: blur(10px);">
        <div style="font-size: 2.5rem; animation: floatAnim 3s ease-in-out infinite;">📈</div>
        <div>
            <h2 style="font-size: 1.25rem; color: #c4b5fd; margin-bottom: 6px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                Coming Soon
                <span class="admin-badge" style="background: rgba(123,97,255,0.15); border: 1px solid rgba(123,97,255,0.25); color: #c4b5fd; font-size: 0.68rem; padding: 2px 6px;">Dormant Pipeline</span>
            </h2>
            <p style="color: rgba(255,255,255,0.65); font-size: 0.86rem; margin: 0; line-height: 1.5;">
                We are building a robust tracking and telemetry database to record real-time site engagement. Once active, visitor metrics, click-through rates, and document telemetry will be compiled in high-fidelity reports right here.
            </p>
        </div>
    </div>

    <!-- Analytics Placeholder Grid -->
    <div class="cms-form-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 28px;">
        <!-- Card 1: Page Views -->
        <div class="admin-card" style="display: flex; flex-direction: column; justify-content: space-between; min-height: 200px;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <span style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255, 255, 255, 0.45);">Page Views</span>
                    <div class="stat-card-icon purple">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#a996ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </div>
                </div>
                <div style="font-size: 2.2rem; font-weight: 800; background: linear-gradient(135deg, #fff 40%, #c4b5fd 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1.1;">
                    0,000
                </div>
            </div>
            <div style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 12px; font-size: 0.76rem; color: rgba(255, 255, 255, 0.35); display: flex; justify-content: space-between; align-items: center;">
                <span>Tracking dormant</span>
                <span>0.0% &uarr;</span>
            </div>
        </div>

        <!-- Card 2: Unique Visitors -->
        <div class="admin-card" style="display: flex; flex-direction: column; justify-content: space-between; min-height: 200px;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <span style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255, 255, 255, 0.45);">Unique Visitors</span>
                    <div class="stat-card-icon blue">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                </div>
                <div style="font-size: 2.2rem; font-weight: 800; background: linear-gradient(135deg, #fff 40%, #93c5fd 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1.1;">
                    0,000
                </div>
            </div>
            <div style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 12px; font-size: 0.76rem; color: rgba(255, 255, 255, 0.35); display: flex; justify-content: space-between; align-items: center;">
                <span>Awaiting connection</span>
                <span>0.0% &uarr;</span>
            </div>
        </div>

        <!-- Card 3: Project Clicks -->
        <div class="admin-card" style="display: flex; flex-direction: column; justify-content: space-between; min-height: 200px;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <span style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255, 255, 255, 0.45);">Project Clicks</span>
                    <div class="stat-card-icon green">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2ecc71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 11 7-7 7 7"/><path d="M12 4v16"/></svg>
                    </div>
                </div>
                <div style="font-size: 2.2rem; font-weight: 800; background: linear-gradient(135deg, #fff 40%, #6ee7b7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1.1;">
                    0,000
                </div>
            </div>
            <div style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 12px; font-size: 0.76rem; color: rgba(255, 255, 255, 0.35); display: flex; justify-content: space-between; align-items: center;">
                <span>Telemetry offline</span>
                <span>0.0% &uarr;</span>
            </div>
        </div>
    </div>

    <style>
        @keyframes floatAnim {
            0% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
            100% { transform: translateY(0); }
        }
    </style>
@endsection
