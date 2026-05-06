<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rajesh's DS Journey</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=JetBrains+Mono:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg: #0a0a0f;
            --surface: #111118;
            --surface2: #1a1a24;
            --surface3: #222230;
            --accent: #7c6df0;
            --accent2: #4ecdc4;
            --accent3: #f7b731;
            --danger: #ff6b6b;
            --success: #51cf66;
            --text: #e8e8f0;
            --text2: #9898b0;
            --text3: #5a5a70;
            --border: rgba(255, 255, 255, 0.06);
            --border2: rgba(255, 255, 255, 0.12);
            --glow: rgba(124, 109, 240, 0.15);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Syne', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Noise texture overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
            opacity: 0.4;
        }

        .bg-glow {
            position: fixed;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(124, 109, 240, 0.08) 0%, transparent 70%);
            top: -200px;
            left: -200px;
            pointer-events: none;
            z-index: 0;
        }

        .bg-glow2 {
            position: fixed;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(78, 205, 196, 0.06) 0%, transparent 70%);
            bottom: -100px;
            right: -100px;
            pointer-events: none;
            z-index: 0;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px 80px;
            position: relative;
            z-index: 1;
        }

        /* Header */
        .header {
            padding: 40px 0 32px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            margin-bottom: 32px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .header-left {}

        .header-tag {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--accent2);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header-tag::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent2);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(0.8);
            }
        }

        h1 {
            font-size: clamp(28px, 5vw, 48px);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.02em;
            color: var(--text);
        }

        h1 span {
            color: var(--accent);
        }

        .header-sub {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: var(--text3);
            margin-top: 10px;
        }

        /* Stats bar */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 32px;
        }

        @media (max-width: 600px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--accent-color, var(--accent));
            opacity: 0.7;
        }

        .stat-num {
            font-size: 28px;
            font-weight: 800;
            color: var(--accent-color, var(--accent));
            letter-spacing: -0.03em;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 11px;
            color: var(--text3);
            font-family: 'JetBrains Mono', monospace;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        /* Overall progress */
        .overall-bar-wrap {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .overall-bar-label {
            font-size: 13px;
            color: var(--text2);
            white-space: nowrap;
        }

        .overall-bar-track {
            flex: 1;
            min-width: 150px;
            height: 8px;
            background: var(--surface3);
            border-radius: 99px;
            overflow: hidden;
        }

        .overall-bar-fill {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, var(--accent), var(--accent2));
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .overall-bar-fill::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 12px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 99px;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {

            0%,
            100% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }
        }

        .overall-pct {
            font-family: 'JetBrains Mono', monospace;
            font-size: 15px;
            font-weight: 500;
            color: var(--accent);
            white-space: nowrap;
            min-width: 40px;
            text-align: right;
        }

        /* Phase sections */
        .phase {
            margin-bottom: 28px;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .phase-header {
            padding: 18px 24px;
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            user-select: none;
            transition: background 0.2s;
            position: relative;
        }

        .phase-header:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .phase-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .phase-name {
            font-size: 15px;
            font-weight: 700;
            flex: 1;
            letter-spacing: -0.01em;
        }

        .phase-meta {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--text3);
            text-align: right;
        }

        .phase-chevron {
            color: var(--text3);
            font-size: 12px;
            transition: transform 0.3s;
        }

        .phase.open .phase-chevron {
            transform: rotate(180deg);
        }

        .phase-progress-bar {
            height: 3px;
            background: var(--surface3);
        }

        .phase-progress-fill {
            height: 100%;
            transition: width 0.5s ease;
        }

        .phase-body {
            display: none;
            background: var(--surface);
        }

        .phase.open .phase-body {
            display: block;
        }

        /* Week group */
        .week-group {
            border-bottom: 1px solid var(--border);
        }

        .week-group:last-child {
            border-bottom: none;
        }

        .week-header {
            padding: 14px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--surface2);
            cursor: pointer;
        }

        .week-num {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--text3);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            min-width: 60px;
        }

        .week-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            flex: 1;
        }

        .week-count {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--text3);
            background: var(--surface3);
            padding: 2px 8px;
            border-radius: 99px;
        }

        .week-mini-bar {
            width: 48px;
            height: 4px;
            background: var(--surface3);
            border-radius: 99px;
            overflow: hidden;
        }

        .week-mini-fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.4s ease;
        }

        /* Tasks */
        .task-list {
            padding: 8px 0;
        }

        .task-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 12px 24px;
            transition: background 0.15s;
            cursor: pointer;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }

        .task-item:last-child {
            border-bottom: none;
        }

        .task-item:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .task-check {
            width: 18px;
            height: 18px;
            border-radius: 5px;
            border: 1.5px solid var(--border2);
            flex-shrink: 0;
            margin-top: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            background: transparent;
        }

        .task-item.done .task-check {
            border-color: var(--success);
            background: var(--success);
        }

        .task-check-inner {
            opacity: 0;
            color: #000;
            font-size: 11px;
            font-weight: 700;
            transition: opacity 0.2s;
        }

        .task-item.done .task-check-inner {
            opacity: 1;
        }

        .task-body {
            flex: 1;
        }

        .task-title {
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
            line-height: 1.4;
            transition: all 0.2s;
        }

        .task-item.done .task-title {
            color: var(--text3);
            text-decoration: line-through;
            text-decoration-color: var(--text3);
        }

        .task-desc {
            font-size: 12px;
            color: var(--text3);
            margin-top: 3px;
            line-height: 1.5;
            font-family: 'JetBrains Mono', monospace;
        }

        .task-item.done .task-desc {
            color: var(--text3);
            opacity: 0.5;
        }

        .task-tags {
            display: flex;
            gap: 6px;
            margin-top: 6px;
            flex-wrap: wrap;
        }

        .task-tag {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            padding: 2px 7px;
            border-radius: 4px;
            font-weight: 400;
            letter-spacing: 0.05em;
        }

        .task-date {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            color: var(--text3);
            white-space: nowrap;
            margin-top: 2px;
            text-align: right;
            min-width: 80px;
        }

        /* Daily custom task section */
        .daily-section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            margin-bottom: 28px;
            overflow: hidden;
        }

        .daily-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .daily-header-left {}

        .daily-title-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .daily-title {
            font-size: 15px;
            font-weight: 700;
        }

        .today-badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            background: var(--accent);
            color: #fff;
            padding: 2px 8px;
            border-radius: 4px;
            letter-spacing: 0.05em;
        }

        .daily-date {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--text3);
            margin-top: 2px;
        }

        .add-task-btn {
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .add-task-btn:hover {
            background: #9284f5;
            transform: translateY(-1px);
        }

        .add-task-form {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border);
            display: none;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .add-task-form.show {
            display: flex;
        }

        .task-input {
            flex: 1;
            min-width: 200px;
            background: var(--surface3);
            border: 1px solid var(--border2);
            border-radius: 8px;
            padding: 9px 14px;
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s;
        }

        .task-input:focus {
            border-color: var(--accent);
        }

        .task-input::placeholder {
            color: var(--text3);
        }

        .form-btn {
            border: none;
            border-radius: 8px;
            padding: 9px 16px;
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .form-btn.primary {
            background: var(--accent);
            color: #fff;
        }

        .form-btn.secondary {
            background: var(--surface3);
            color: var(--text2);
        }

        .form-btn:hover {
            opacity: 0.85;
        }

        .daily-tasks {
            padding: 8px 0;
        }

        .daily-task-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            transition: background 0.15s;
            cursor: pointer;
        }

        .daily-task-item:last-child {
            border-bottom: none;
        }

        .daily-task-item:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .daily-task-item .task-check {
            border-color: rgba(247, 183, 49, 0.4);
        }

        .daily-task-item.done .task-check {
            border-color: var(--accent3);
            background: var(--accent3);
        }

        .daily-task-text {
            font-size: 13px;
            color: var(--text);
            flex: 1;
            transition: all 0.2s;
        }

        .daily-task-item.done .daily-task-text {
            color: var(--text3);
            text-decoration: line-through;
        }

        .del-btn {
            background: none;
            border: none;
            color: var(--text3);
            cursor: pointer;
            font-size: 16px;
            padding: 2px 6px;
            border-radius: 4px;
            opacity: 0;
            transition: all 0.15s;
            line-height: 1;
        }

        .daily-task-item:hover .del-btn {
            opacity: 1;
        }

        .del-btn:hover {
            color: var(--danger);
            background: rgba(255, 107, 107, 0.1);
        }

        .empty-daily {
            padding: 24px;
            text-align: center;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--text3);
        }

        /* Streak */
        .streak-section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px 24px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .streak-fire {
            font-size: 36px;
            line-height: 1;
            filter: drop-shadow(0 0 12px rgba(247, 183, 49, 0.5));
        }

        .streak-info {}

        .streak-num {
            font-size: 32px;
            font-weight: 800;
            color: var(--accent3);
            letter-spacing: -0.03em;
            line-height: 1;
        }

        .streak-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--text3);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 2px;
        }

        .streak-dots {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            flex: 1;
        }

        .streak-dot {
            width: 10px;
            height: 10px;
            border-radius: 2px;
            background: var(--surface3);
        }

        .streak-dot.active {
            background: var(--accent3);
        }

        /* Section label */
        .section-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--text3);
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: var(--surface2);
            border: 1px solid var(--border2);
            border-radius: 10px;
            padding: 12px 18px;
            font-size: 13px;
            color: var(--text);
            z-index: 999;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 10px;
            pointer-events: none;
            max-width: 300px;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast-icon {
            font-size: 16px;
        }

        /* Reset btn */
        .reset-btn {
            background: none;
            border: 1px solid var(--border2);
            color: var(--text3);
            border-radius: 8px;
            padding: 6px 12px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .reset-btn:hover {
            border-color: var(--danger);
            color: var(--danger);
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--surface3);
            border-radius: 99px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--border2);
        }

        /* Confetti particle */
        .confetti-piece {
            position: fixed;
            width: 8px;
            height: 8px;
            border-radius: 2px;
            pointer-events: none;
            z-index: 9999;
            animation: confetti-fall 1s ease-out forwards;
        }

        @keyframes confetti-fall {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translateY(200px) rotate(720deg);
                opacity: 0;
            }
        }

        .phase-complete-banner {
            background: linear-gradient(135deg, rgba(81, 207, 102, 0.1), rgba(78, 205, 196, 0.1));
            border: 1px solid rgba(81, 207, 102, 0.2);
            border-radius: 8px;
            padding: 10px 16px;
            margin: 12px 24px;
            font-size: 12px;
            color: var(--success);
            font-family: 'JetBrains Mono', monospace;
            display: none;
            align-items: center;
            gap: 8px;
        }

        .phase-complete-banner.show {
            display: flex;
        }
    </style>
</head>

<body>

    <div class="bg-glow"></div>
    <div class="bg-glow2"></div>
    <div class="toast" id="toast"><span class="toast-icon"></span><span id="toast-text"></span></div>

    <div class="container">

        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <div class="header-tag">data science journey · 12 months</div>
                <h1>Rajesh's <span>DS</span> Tracker</h1>
                <div class="header-sub">// 30-45 mins/day → Data Analyst by Month 12</div>
            </div>
            <button class="reset-btn" onclick="confirmReset()">↺ reset progress</button>
        </div>

        <!-- Stats -->
        <div class="stats-grid" id="stats-grid">
            <div class="stat-card" style="--accent-color: var(--accent)">
                <div class="stat-num" id="stat-done">0</div>
                <div class="stat-label">tasks done</div>
            </div>
            <div class="stat-card" style="--accent-color: var(--accent2)">
                <div class="stat-num" id="stat-total">0</div>
                <div class="stat-label">total tasks</div>
            </div>
            <div class="stat-card" style="--accent-color: var(--accent3)">
                <div class="stat-num" id="stat-streak">0</div>
                <div class="stat-label">day streak</div>
            </div>
            <div class="stat-card" style="--accent-color: var(--success)">
                <div class="stat-num" id="stat-phases">0/4</div>
                <div class="stat-label">phases done</div>
            </div>
        </div>

        <!-- Overall progress -->
        <div class="overall-bar-wrap">
            <span class="overall-bar-label">Overall progress</span>
            <div class="overall-bar-track">
                <div class="overall-bar-fill" id="overall-fill" style="width: 0%"></div>
            </div>
            <span class="overall-pct" id="overall-pct">0%</span>
        </div>

        <!-- Streak -->
        <div class="streak-section">
            <div class="streak-fire">🔥</div>
            <div class="streak-info">
                <div class="streak-num" id="streak-num">0</div>
                <div class="streak-label">day streak</div>
            </div>
            <div class="streak-dots" id="streak-dots"></div>
        </div>

        <!-- Daily Tasks -->
        <div class="section-label">Today's custom tasks</div>
        <div class="daily-section">
            <div class="daily-header">
                <div class="daily-header-left">
                    <div class="daily-title-row">
                        <span class="daily-title">Daily Session</span>
                        <span class="today-badge" id="today-badge">TODAY</span>
                    </div>
                    <div class="daily-date" id="daily-date"></div>
                </div>
                <button class="add-task-btn" onclick="toggleAddForm()">
                    <span>+</span> Add task
                </button>
            </div>
            <div class="add-task-form" id="add-task-form">
                <input type="text" class="task-input" id="new-task-input"
                    placeholder="e.g. Completed Week 1 Day 3 of Python for Everybody..." maxlength="120" />
                <button class="form-btn primary" onclick="addDailyTask()">Add</button>
                <button class="form-btn secondary" onclick="toggleAddForm()">Cancel</button>
            </div>
            <div class="daily-tasks" id="daily-tasks-list"></div>
        </div>

        <!-- Phases -->
        <div class="section-label">12-month roadmap</div>
        <div id="phases-container"></div>

    </div>

    <script>
        // ─── DATA ───────────────────────────────────────────────────────────────────
        const PHASES = [{
                id: 'p1',
                name: 'Phase 1 — Python Survival Kit',
                duration: 'Month 1–2 · 8 weeks',
                color: '#7c6df0',
                weeks: [{
                        id: 'w1',
                        title: 'Python absolute basics',
                        period: 'Week 1–2',
                        tasks: [{
                                id: 't1',
                                title: 'Install Anaconda + open Jupyter Notebook',
                                desc: 'anaconda.com → download → install → launch',
                                tags: ['setup']
                            },
                            {
                                id: 't2',
                                title: 'Variables, print(), and data types',
                                desc: 'int, float, str, bool — think PHP without $',
                                tags: ['python']
                            },
                            {
                                id: 't3',
                                title: 'If/else conditions',
                                desc: 'Write a script: if age > 18 print "adult" else "minor"',
                                tags: ['python']
                            },
                            {
                                id: 't4',
                                title: 'For loops and while loops',
                                desc: 'Print numbers 1-10, then sum of 1-100 using a loop',
                                tags: ['python']
                            },
                            {
                                id: 't5',
                                title: 'Lists — create, append, slice, loop',
                                desc: 'Make a list of 5 cities, print each one',
                                tags: ['python']
                            },
                            {
                                id: 't6',
                                title: 'Dictionaries — key/value pairs',
                                desc: 'Create a dict of your skills with years of experience',
                                tags: ['python']
                            },
                            {
                                id: 't7',
                                title: 'Watch Python for Everybody — Week 1 videos',
                                desc: 'youtube.com → search "Python for Everybody Dr Chuck"',
                                tags: ['resource', 'video']
                            },
                            {
                                id: 't8',
                                title: 'Mini project: build a simple calculator',
                                desc: 'Add, subtract, multiply, divide using if/else — no AI',
                                tags: ['project']
                            },
                        ]
                    },
                    {
                        id: 'w2',
                        title: 'Functions, files, and Jupyter',
                        period: 'Week 3–4',
                        tasks: [{
                                id: 't9',
                                title: 'Define and call functions',
                                desc: 'Write a function that takes a name and returns "Hello, {name}!"',
                                tags: ['python']
                            },
                            {
                                id: 't10',
                                title: 'Function arguments and return values',
                                desc: 'Build a BMI calculator function',
                                tags: ['python']
                            },
                            {
                                id: 't11',
                                title: 'Read a text file with Python',
                                desc: 'Create a .txt file with 5 names, read and print them',
                                tags: ['python']
                            },
                            {
                                id: 't12',
                                title: 'Write to a file',
                                desc: 'Take user input and save it to a file',
                                tags: ['python']
                            },
                            {
                                id: 't13',
                                title: 'Learn Jupyter markdown cells',
                                desc: 'Add text, headings, bold — make your notebook readable',
                                tags: ['jupyter']
                            },
                            {
                                id: 't14',
                                title: 'Mini project: name sorter',
                                desc: 'Read names from a file, sort alphabetically, save to new file',
                                tags: ['project']
                            },
                        ]
                    },
                    {
                        id: 'w3',
                        title: 'Pandas basics',
                        period: 'Week 5–6',
                        tasks: [{
                                id: 't15',
                                title: 'Install pandas and import it',
                                desc: 'pip install pandas → import pandas as pd',
                                tags: ['pandas']
                            },
                            {
                                id: 't16',
                                title: 'Download IPL dataset from Kaggle',
                                desc: 'kaggle.com → search IPL matches → download CSV',
                                tags: ['data']
                            },
                            {
                                id: 't17',
                                title: 'Load CSV with pd.read_csv()',
                                desc: 'Load IPL data, print first 5 rows with .head()',
                                tags: ['pandas']
                            },
                            {
                                id: 't18',
                                title: 'Filter rows and select columns',
                                desc: 'Which matches were played in Mumbai? df[df["city"]=="Mumbai"]',
                                tags: ['pandas']
                            },
                            {
                                id: 't19',
                                title: 'GroupBy and count',
                                desc: 'Which team won the most matches? df.groupby("winner").count()',
                                tags: ['pandas']
                            },
                            {
                                id: 't20',
                                title: 'Sort and find top values',
                                desc: 'Top 5 teams by wins — .sort_values().head(5)',
                                tags: ['pandas']
                            },
                            {
                                id: 't21',
                                title: 'Complete Kaggle Pandas micro-course',
                                desc: 'kaggle.com/learn/pandas — free, 6 lessons',
                                tags: ['resource', 'kaggle']
                            },
                        ]
                    },
                    {
                        id: 'w4',
                        title: 'First visualisations',
                        period: 'Week 7–8',
                        tasks: [{
                                id: 't22',
                                title: 'Install matplotlib and seaborn',
                                desc: 'pip install matplotlib seaborn',
                                tags: ['viz']
                            },
                            {
                                id: 't23',
                                title: 'Bar chart — team wins',
                                desc: 'plt.bar() showing IPL team win counts',
                                tags: ['viz']
                            },
                            {
                                id: 't24',
                                title: 'Line chart — matches per year',
                                desc: 'How many IPL matches per season?',
                                tags: ['viz']
                            },
                            {
                                id: 't25',
                                title: 'Histogram — runs scored distribution',
                                desc: 'plt.hist() on run totals',
                                tags: ['viz']
                            },
                            {
                                id: 't26',
                                title: 'Seaborn countplot and boxplot',
                                desc: 'sns.countplot() for toss decisions',
                                tags: ['viz']
                            },
                            {
                                id: 't27',
                                title: 'Add titles, labels, and legends',
                                desc: 'Every chart needs plt.title(), plt.xlabel(), plt.ylabel()',
                                tags: ['viz']
                            },
                            {
                                id: 't28',
                                title: '★ MILESTONE: IPL Analysis notebook on GitHub',
                                desc: '5 charts + written observations. Push to GitHub. This is portfolio piece #1!',
                                tags: ['milestone', 'portfolio']
                            },
                        ]
                    }
                ]
            },
            {
                id: 'p2',
                name: 'Phase 2 — Stats Without Trauma',
                duration: 'Month 3–5 · 10 weeks',
                color: '#4ecdc4',
                weeks: [{
                        id: 'w5',
                        title: 'Stats visually — no formulas yet',
                        period: 'Week 9–11',
                        tasks: [{
                                id: 't29',
                                title: 'Watch StatQuest: Mean, Median, Mode',
                                desc: 'youtube.com → "StatQuest mean median mode"',
                                tags: ['stats', 'video']
                            },
                            {
                                id: 't30',
                                title: 'Standard deviation — what does it actually mean?',
                                desc: 'StatQuest standard deviation video + implement in Python',
                                tags: ['stats']
                            },
                            {
                                id: 't31',
                                title: 'Normal distribution — the bell curve',
                                desc: 'StatQuest normal distribution + plot one in matplotlib',
                                tags: ['stats']
                            },
                            {
                                id: 't32',
                                title: 'What is a p-value? (plain English)',
                                desc: 'StatQuest p-value video — do not memorise the formula',
                                tags: ['stats', 'video']
                            },
                            {
                                id: 't33',
                                title: 'Calculate stats on real data with Pandas',
                                desc: 'df.describe(), df.mean(), df.std() on IPL data',
                                tags: ['stats', 'pandas']
                            },
                            {
                                id: 't34',
                                title: 'Understand skewness — left vs right skewed',
                                desc: 'What does a skewed distribution mean in plain English?',
                                tags: ['stats']
                            },
                        ]
                    },
                    {
                        id: 'w6',
                        title: 'Correlation, outliers, missing data',
                        period: 'Week 12–14',
                        tasks: [{
                                id: 't35',
                                title: 'What is correlation? (≠ causation)',
                                desc: 'StatQuest correlation video. Key: correlation does not mean one causes the other.',
                                tags: ['stats', 'video']
                            },
                            {
                                id: 't36',
                                title: 'Correlation heatmap with seaborn',
                                desc: 'sns.heatmap(df.corr()) — what variables move together?',
                                tags: ['stats', 'viz']
                            },
                            {
                                id: 't37',
                                title: 'Find and handle missing values',
                                desc: 'df.isnull().sum() → decide: drop or fill?',
                                tags: ['data-cleaning']
                            },
                            {
                                id: 't38',
                                title: 'Detect outliers with boxplots',
                                desc: 'sns.boxplot() — spot extreme values visually',
                                tags: ['stats', 'viz']
                            },
                            {
                                id: 't39',
                                title: 'IQR method to remove outliers',
                                desc: 'Filter rows beyond Q1-1.5*IQR and Q3+1.5*IQR',
                                tags: ['stats']
                            },
                            {
                                id: 't40',
                                title: 'Download Zomato dataset and do EDA',
                                desc: 'kaggle.com → search Zomato Bengaluru restaurants. Answer 5 business questions.',
                                tags: ['eda', 'project']
                            },
                        ]
                    },
                    {
                        id: 'w7',
                        title: 'SQL for analysts',
                        period: 'Week 15–18',
                        tasks: [{
                                id: 't41',
                                title: 'SELECT, WHERE, ORDER BY refresher',
                                desc: 'sqlzoo.net — you know MySQL so this is fast',
                                tags: ['sql']
                            },
                            {
                                id: 't42',
                                title: 'GROUP BY + aggregate functions',
                                desc: 'COUNT, SUM, AVG, MIN, MAX with GROUP BY',
                                tags: ['sql']
                            },
                            {
                                id: 't43',
                                title: 'JOINs — INNER, LEFT, RIGHT',
                                desc: 'sqlzoo.net JOIN exercises — visualise which rows match',
                                tags: ['sql']
                            },
                            {
                                id: 't44',
                                title: 'Subqueries',
                                desc: 'Query inside a query — "give me teams that won more than the average"',
                                tags: ['sql']
                            },
                            {
                                id: 't45',
                                title: 'Window functions — ROW_NUMBER, RANK, LAG',
                                desc: 'These appear in every Data Analyst interview',
                                tags: ['sql', 'interview']
                            },
                            {
                                id: 't46',
                                title: 'CTEs (Common Table Expressions)',
                                desc: 'WITH cte AS (...) SELECT ... — cleaner than subqueries',
                                tags: ['sql']
                            },
                            {
                                id: 't47',
                                title: 'Complete Mode Analytics SQL tutorial',
                                desc: 'mode.com/sql-tutorial — free, interactive',
                                tags: ['resource', 'sql']
                            },
                            {
                                id: 't48',
                                title: '★ MILESTONE: SQL portfolio — 10 business questions answered',
                                desc: 'On any dataset, write 10 SQL queries that answer real questions. Push to GitHub.',
                                tags: ['milestone', 'portfolio']
                            },
                        ]
                    }
                ]
            },
            {
                id: 'p3',
                name: 'Phase 3 — First ML Models',
                duration: 'Month 6–8 · 10 weeks',
                color: '#f7b731',
                weeks: [{
                        id: 'w8',
                        title: 'Linear regression',
                        period: 'Week 19–22',
                        tasks: [{
                                id: 't49',
                                title: 'Understand what regression is trying to do',
                                desc: 'StatQuest linear regression — watch 2x if needed',
                                tags: ['ml', 'video']
                            },
                            {
                                id: 't50',
                                title: 'Implement linear regression from scratch',
                                desc: 'y = mx + b. Calculate m and b manually in Python — no sklearn yet.',
                                tags: ['ml']
                            },
                            {
                                id: 't51',
                                title: 'Train/test split concept',
                                desc: 'Why do we split data? Overfitting vs underfitting.',
                                tags: ['ml']
                            },
                            {
                                id: 't52',
                                title: 'Linear regression with sklearn',
                                desc: 'from sklearn.linear_model import LinearRegression — now use the library',
                                tags: ['ml', 'sklearn']
                            },
                            {
                                id: 't53',
                                title: 'Evaluate with MAE, RMSE, R²',
                                desc: 'What do these scores actually mean?',
                                tags: ['ml']
                            },
                            {
                                id: 't54',
                                title: 'Project: predict house prices',
                                desc: 'Kaggle House Prices dataset — full pipeline: clean → train → evaluate',
                                tags: ['project', 'ml']
                            },
                        ]
                    },
                    {
                        id: 'w9',
                        title: 'Logistic regression + decision trees',
                        period: 'Week 23–24',
                        tasks: [{
                                id: 't55',
                                title: 'Logistic regression — classification, not regression',
                                desc: 'StatQuest logistic regression — yes/no predictions',
                                tags: ['ml', 'video']
                            },
                            {
                                id: 't56',
                                title: 'Confusion matrix, precision, recall',
                                desc: 'How do we measure classification accuracy properly?',
                                tags: ['ml']
                            },
                            {
                                id: 't57',
                                title: 'Decision tree — how it splits data',
                                desc: 'StatQuest decision tree video — very intuitive',
                                tags: ['ml', 'video']
                            },
                            {
                                id: 't58',
                                title: 'sklearn DecisionTreeClassifier',
                                desc: 'Train a tree to predict loan approval',
                                tags: ['ml', 'sklearn']
                            },
                            {
                                id: 't59',
                                title: 'Visualise your decision tree',
                                desc: 'plot_tree() — see the actual decisions it makes',
                                tags: ['ml', 'viz']
                            },
                        ]
                    },
                    {
                        id: 'w10',
                        title: 'Kaggle competition',
                        period: 'Week 25–26',
                        tasks: [{
                                id: 't60',
                                title: 'Join Kaggle Titanic competition',
                                desc: 'kaggle.com/c/titanic — the DS rite of passage',
                                tags: ['kaggle', 'milestone']
                            },
                            {
                                id: 't61',
                                title: 'EDA on Titanic data',
                                desc: 'Who survived? What features matter? Write your findings.',
                                tags: ['eda']
                            },
                            {
                                id: 't62',
                                title: 'Feature engineering',
                                desc: 'Create new columns from existing ones — title from name, family size, etc.',
                                tags: ['ml']
                            },
                            {
                                id: 't63',
                                title: 'Build your own model — no copying notebooks',
                                desc: 'Try logistic regression first, then decision tree',
                                tags: ['ml']
                            },
                            {
                                id: 't64',
                                title: 'Submit predictions to Kaggle',
                                desc: 'Get your score. Aim for > 75%',
                                tags: ['kaggle']
                            },
                            {
                                id: 't65',
                                title: '★ MILESTONE: Titanic solution on GitHub with writeup',
                                desc: 'Document your approach, what worked, what did not. This is portfolio piece #2.',
                                tags: ['milestone', 'portfolio']
                            },
                        ]
                    }
                ]
            },
            {
                id: 'p4',
                name: 'Phase 4 — Portfolio + Job Switch',
                duration: 'Month 9–12 · 16 weeks',
                color: '#51cf66',
                weeks: [{
                        id: 'w11',
                        title: '3 portfolio projects',
                        period: 'Month 9–10',
                        tasks: [{
                                id: 't66',
                                title: 'Project 1: Indian stock market analysis',
                                desc: 'Use yfinance library — analyse 5 stocks over 2 years. Charts + insights.',
                                tags: ['project', 'portfolio']
                            },
                            {
                                id: 't67',
                                title: 'Project 2: IPL deep dive (upgrade of Phase 1)',
                                desc: 'Add prediction: who will win given team lineups? ML model.',
                                tags: ['project', 'portfolio']
                            },
                            {
                                id: 't68',
                                title: 'Project 3: Your choice — something you care about',
                                desc: 'Cricket, movies, job market, food — passion projects are memorable',
                                tags: ['project', 'portfolio']
                            },
                            {
                                id: 't69',
                                title: 'Write a great README for each project',
                                desc: 'What problem, what data, what method, what findings. Include charts.',
                                tags: ['portfolio']
                            },
                            {
                                id: 't70',
                                title: 'GitHub profile — pin your 3 best projects',
                                desc: 'Add a GitHub bio that mentions Laravel background + DS skills',
                                tags: ['portfolio']
                            },
                        ]
                    },
                    {
                        id: 'w12',
                        title: 'Streamlit dashboard',
                        period: 'Month 11',
                        tasks: [{
                                id: 't71',
                                title: 'Learn Streamlit basics',
                                desc: 'pip install streamlit → streamlit.io/docs → hello world app',
                                tags: ['streamlit']
                            },
                            {
                                id: 't72',
                                title: 'Build an interactive data dashboard',
                                desc: 'Load a dataset, add filters, show charts dynamically',
                                tags: ['streamlit', 'project']
                            },
                            {
                                id: 't73',
                                title: 'Add your Laravel API as a data source',
                                desc: 'Fetch live data from one of your Laravel apps — unique combo!',
                                tags: ['streamlit', 'laravel']
                            },
                            {
                                id: 't74',
                                title: 'Deploy on Streamlit Cloud (free)',
                                desc: 'share.streamlit.io — live URL you can share with recruiters',
                                tags: ['deploy', 'portfolio']
                            },
                            {
                                id: 't75',
                                title: '★ MILESTONE: Live dashboard URL in your Naukri profile',
                                desc: 'This is your showstopper. Most DS freshers have no deployments.',
                                tags: ['milestone', 'portfolio']
                            },
                        ]
                    },
                    {
                        id: 'w13',
                        title: 'Job search + interviews',
                        period: 'Month 12',
                        tasks: [{
                                id: 't76',
                                title: 'Update Naukri profile — target Data Analyst',
                                desc: 'New resume, new headline, salary ₹8–10 LPA',
                                tags: ['job']
                            },
                            {
                                id: 't77',
                                title: 'Update LinkedIn — add DS skills and projects',
                                desc: 'Post about your Streamlit dashboard — tag it with data science',
                                tags: ['job']
                            },
                            {
                                id: 't78',
                                title: 'Apply to 5 companies per week',
                                desc: 'Target: fintech, healthtech, SaaS startups — they value builders',
                                tags: ['job']
                            },
                            {
                                id: 't79',
                                title: 'Prep: 30 SQL interview questions',
                                desc: 'leetcode.com → SQL Easy + Medium. These appear in every DA interview.',
                                tags: ['interview']
                            },
                            {
                                id: 't80',
                                title: 'Prep: explain your 3 projects confidently',
                                desc: 'Practice out loud: problem → approach → finding → impact. 5 mins per project.',
                                tags: ['interview']
                            },
                            {
                                id: 't81',
                                title: '★ FINAL MILESTONE: First Data Analyst interview',
                                desc: 'You made it. Target ₹6–9 LPA to start. Your Laravel background adds ₹1-2L premium.',
                                tags: ['milestone']
                            },
                        ]
                    }
                ]
            }
        ];

        // ─── STATE ──────────────────────────────────────────────────────────────────
        const LS_KEY = 'rajesh_ds_tracker_v1';

        function loadState() {
            try {
                return JSON.parse(localStorage.getItem(LS_KEY)) || {};
            } catch {
                return {};
            }
        }

        function saveState(state) {
            localStorage.setItem(LS_KEY, JSON.stringify(state));
        }

        let state = loadState();
        if (!state.done) state.done = {};
        if (!state.dailyTasks) state.dailyTasks = {};
        if (!state.activeDays) state.activeDays = [];
        if (!state.lastActive) state.lastActive = null;

        // ─── UTILS ──────────────────────────────────────────────────────────────────
        function todayKey() {
            const d = new Date();
            return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
        }

        function formatDate(key) {
            const [y, m, d] = key.split('-');
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return `${months[parseInt(m)-1]} ${parseInt(d)}, ${y}`;
        }

        function showToast(icon, msg, duration = 2500) {
            const t = document.getElementById('toast');
            document.getElementById('toast-text').textContent = msg;
            t.querySelector('.toast-icon').textContent = icon;
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), duration);
        }

        function confetti(x, y) {
            const colors = ['#7c6df0', '#4ecdc4', '#f7b731', '#51cf66', '#ff6b6b'];
            for (let i = 0; i < 8; i++) {
                const el = document.createElement('div');
                el.className = 'confetti-piece';
                el.style.cssText =
                    `left:${x + (Math.random()-0.5)*60}px;top:${y}px;background:${colors[Math.floor(Math.random()*colors.length)]};transform:rotate(${Math.random()*360}deg)`;
                document.body.appendChild(el);
                setTimeout(() => el.remove(), 1000);
            }
        }

        // ─── STREAK ─────────────────────────────────────────────────────────────────
        function updateStreak() {
            const today = todayKey();

            // Mark today as active if any task was completed today
            const totalDone = Object.values(state.done).filter(Boolean).length;
            const dailyDoneToday = (state.dailyTasks[today] || []).some(t => t.done);

            if (totalDone > 0 || dailyDoneToday) {
                if (!state.activeDays.includes(today)) {
                    state.activeDays.push(today);
                    state.activeDays.sort();
                }
            }

            // Calculate current streak
            let streak = 0;
            const sortedDays = [...state.activeDays].sort().reverse();
            const todayDate = new Date(today);
            for (let i = 0; i < sortedDays.length; i++) {
                const d = new Date(sortedDays[i]);
                const diff = Math.round((todayDate - d) / (1000 * 60 * 60 * 24));
                if (diff === i) streak++;
                else break;
            }

            document.getElementById('stat-streak').textContent = streak;
            document.getElementById('streak-num').textContent = streak;

            // Dots — last 30 days
            const dotsEl = document.getElementById('streak-dots');
            dotsEl.innerHTML = '';
            for (let i = 29; i >= 0; i--) {
                const d = new Date();
                d.setDate(d.getDate() - i);
                const key =
                    `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
                const dot = document.createElement('div');
                dot.className = 'streak-dot' + (state.activeDays.includes(key) ? ' active' : '');
                dot.title = formatDate(key);
                dotsEl.appendChild(dot);
            }
        }

        // ─── STATS ──────────────────────────────────────────────────────────────────
        function updateStats() {
            const allTaskIds = PHASES.flatMap(p => p.weeks.flatMap(w => w.tasks.map(t => t.id)));
            const totalTasks = allTaskIds.length;
            const doneTasks = allTaskIds.filter(id => state.done[id]).length;
            const phaseDone = PHASES.filter(p => p.weeks.every(w => w.tasks.every(t => state.done[t.id]))).length;
            const pct = totalTasks ? Math.round(doneTasks / totalTasks * 100) : 0;

            document.getElementById('stat-done').textContent = doneTasks;
            document.getElementById('stat-total').textContent = totalTasks;
            document.getElementById('stat-phases').textContent = `${phaseDone}/4`;
            document.getElementById('overall-fill').style.width = pct + '%';
            document.getElementById('overall-pct').textContent = pct + '%';

            updateStreak();
        }

        // ─── DAILY TASKS ────────────────────────────────────────────────────────────
        function renderDailyTasks() {
            const today = todayKey();
            document.getElementById('daily-date').textContent = formatDate(today);

            const tasks = state.dailyTasks[today] || [];
            const list = document.getElementById('daily-tasks-list');

            if (tasks.length === 0) {
                list.innerHTML =
                    '<div class="empty-daily">// No tasks yet today. Add what you plan to study in this session.</div>';
                return;
            }

            list.innerHTML = tasks.map((task, i) => `
    <div class="daily-task-item ${task.done ? 'done' : ''}" onclick="toggleDailyTask(${i})">
      <div class="task-check"><span class="task-check-inner">✓</span></div>
      <div class="daily-task-text">${escapeHtml(task.text)}</div>
      <button class="del-btn" onclick="deleteDailyTask(event, ${i})">×</button>
    </div>
  `).join('');
        }

        function toggleAddForm() {
            const form = document.getElementById('add-task-form');
            form.classList.toggle('show');
            if (form.classList.contains('show')) {
                document.getElementById('new-task-input').focus();
            }
        }

        function addDailyTask() {
            const input = document.getElementById('new-task-input');
            const text = input.value.trim();
            if (!text) return;

            const today = todayKey();
            if (!state.dailyTasks[today]) state.dailyTasks[today] = [];
            state.dailyTasks[today].push({
                text,
                done: false
            });

            input.value = '';
            saveState(state);
            renderDailyTasks();

            // Mark today active
            if (!state.activeDays.includes(today)) {
                state.activeDays.push(today);
                saveState(state);
            }
            updateStats();
            showToast('📝', 'Task added for today');
        }

        document.getElementById('new-task-input').addEventListener('keydown', e => {
            if (e.key === 'Enter') addDailyTask();
            if (e.key === 'Escape') toggleAddForm();
        });

        function toggleDailyTask(index) {
            const today = todayKey();
            state.dailyTasks[today][index].done = !state.dailyTasks[today][index].done;
            saveState(state);
            renderDailyTasks();

            const allDone = state.dailyTasks[today].every(t => t.done);
            if (state.dailyTasks[today][index].done) {
                if (allDone) showToast('🎉', 'All tasks done! Great session!');
                else showToast('✅', 'Task completed!');
            }
            updateStats();
        }

        function deleteDailyTask(e, index) {
            e.stopPropagation();
            const today = todayKey();
            state.dailyTasks[today].splice(index, 1);
            saveState(state);
            renderDailyTasks();
            updateStats();
        }

        // ─── PHASES ──────────────────────────────────────────────────────────────────
        function escapeHtml(s) {
            return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        function renderPhases() {
            const container = document.getElementById('phases-container');
            container.innerHTML = PHASES.map((phase, pi) => {
                const allTasks = phase.weeks.flatMap(w => w.tasks);
                const doneTasks = allTasks.filter(t => state.done[t.id]);
                const pct = allTasks.length ? Math.round(doneTasks.length / allTasks.length * 100) : 0;
                const phaseComplete = pct === 100;

                const weeksHtml = phase.weeks.map(week => {
                    const wDone = week.tasks.filter(t => state.done[t.id]).length;
                    const wPct = Math.round(wDone / week.tasks.length * 100);

                    const tasksHtml = week.tasks.map(task => {
                        const isDone = !!state.done[task.id];
                        const tagColors = {
                            'setup': '#7c6df0',
                            'python': '#4ecdc4',
                            'pandas': '#f7b731',
                            'viz': '#ff6b6b',
                            'stats': '#51cf66',
                            'sql': '#7c6df0',
                            'ml': '#f7b731',
                            'sklearn': '#4ecdc4',
                            'eda': '#51cf66',
                            'kaggle': '#ff6b6b',
                            'resource': '#888',
                            'video': '#888',
                            'data': '#4ecdc4',
                            'data-cleaning': '#f7b731',
                            'project': '#7c6df0',
                            'milestone': '#f7b731',
                            'portfolio': '#4ecdc4',
                            'streamlit': '#51cf66',
                            'laravel': '#ff6b6b',
                            'deploy': '#51cf66',
                            'job': '#7c6df0',
                            'interview': '#f7b731'
                        };

                        const tagsHtml = (task.tags || []).map(tag => {
                            const isMilestone = tag === 'milestone';
                            const bg = tagColors[tag] || '#888';
                            return `<span class="task-tag" style="background:${bg}22;color:${bg};border:1px solid ${bg}44">${isMilestone ? '★ ' : ''}${tag}</span>`;
                        }).join('');

                        const completedDate = state.done[task.id] ?
                            `<div class="task-date">✓ ${formatDate(state.done[task.id])}</div>` :
                            '';

                        return `
          <div class="task-item ${isDone ? 'done' : ''}" onclick="toggleTask('${task.id}', this)" data-task-id="${task.id}">
            <div class="task-check"><span class="task-check-inner">✓</span></div>
            <div class="task-body">
              <div class="task-title">${escapeHtml(task.title)}</div>
              <div class="task-desc">${escapeHtml(task.desc)}</div>
              ${tagsHtml ? `<div class="task-tags">${tagsHtml}</div>` : ''}
            </div>
            ${completedDate}
          </div>
        `;
                    }).join('');

                    return `
        <div class="week-group">
          <div class="week-header">
            <span class="week-num">${week.period}</span>
            <span class="week-title">${week.title}</span>
            <div class="week-mini-bar">
              <div class="week-mini-fill" style="width:${wPct}%;background:${phase.color}"></div>
            </div>
            <span class="week-count">${wDone}/${week.tasks.length}</span>
          </div>
          <div class="task-list">${tasksHtml}</div>
        </div>
      `;
                }).join('');

                return `
      <div class="phase ${pi === 0 ? 'open' : ''}" id="phase-${phase.id}">
        <div class="phase-header" onclick="togglePhase('${phase.id}')">
          <div class="phase-dot" style="background:${phase.color}"></div>
          <span class="phase-name">${phase.name}</span>
          <div class="phase-meta">
            ${doneTasks.length}/${allTasks.length} tasks<br>${pct}%
          </div>
          <span class="phase-chevron">▼</span>
        </div>
        <div class="phase-progress-bar">
          <div class="phase-progress-fill" style="width:${pct}%;background:${phase.color}"></div>
        </div>
        <div class="phase-complete-banner ${phaseComplete ? 'show' : ''}">
          🎉 Phase complete! You crushed it.
        </div>
        <div class="phase-body">${weeksHtml}</div>
      </div>
    `;
            }).join('');
        }

        function togglePhase(phaseId) {
            const el = document.getElementById(`phase-${phaseId}`);
            el.classList.toggle('open');
        }

        function toggleTask(taskId, el) {
            const wasDone = !!state.done[taskId];

            if (wasDone) {
                delete state.done[taskId];
                el.classList.remove('done');
                showToast('↩️', 'Task marked incomplete');
            } else {
                state.done[taskId] = todayKey();
                el.classList.add('done');

                // Update check appearance immediately
                const check = el.querySelector('.task-check-inner');
                if (check) check.style.opacity = '1';

                // Confetti for milestones
                const rect = el.getBoundingClientRect();
                const isMilestone = el.querySelector('.task-tag') && el.querySelector('.task-tag').textContent.includes(
                '★');
                if (isMilestone) {
                    confetti(rect.left + rect.width / 2, rect.top);
                    showToast('🎊', 'MILESTONE COMPLETED! Amazing!', 4000);
                } else {
                    showToast('✅', 'Task done! Keep going!');
                }

                // Mark today active
                const today = todayKey();
                if (!state.activeDays.includes(today)) state.activeDays.push(today);
            }

            saveState(state);

            // Re-render to update counts and dates
            renderPhases();
            updateStats();
        }

        // ─── RESET ──────────────────────────────────────────────────────────────────
        function confirmReset() {
            if (confirm('Reset ALL progress? This cannot be undone.')) {
                state = {
                    done: {},
                    dailyTasks: {},
                    activeDays: [],
                    lastActive: null
                };
                saveState(state);
                renderPhases();
                renderDailyTasks();
                updateStats();
                showToast('🔄', 'Progress reset. Fresh start!');
            }
        }

        // ─── INIT ───────────────────────────────────────────────────────────────────
        renderPhases();
        renderDailyTasks();
        updateStats();
    </script>
</body>

</html>
