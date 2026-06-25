<?php session_name('RoKenAI'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKenAI | News & Updates</title>
    <?php include 'partials/link.php'; ?>
    <style>
        .news-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 24px 48px;
            animation: fadeInUp 0.5s ease;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-header h1 {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 12px;
        }

        .page-header p {
            font-size: 15px;
            color: var(--text-secondary);
            max-width: 520px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* ===== Featured Article ===== */
        .featured-article {
            background: var(--bg-elevated);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-subtle);
            border-radius: 24px;
            overflow: hidden;
            margin-bottom: 32px;
            display: grid;
            grid-template-columns: 1fr;
            transition: all 0.35s ease;
        }

        .featured-article:hover {
            border-color: var(--border-hover);
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
        }

        @media (min-width: 768px) {
            .featured-article {
                grid-template-columns: 1fr 1fr;
            }
        }

        .featured-image {
            min-height: 240px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(250,204,21,0.05), rgba(99,102,241,0.08));
            position: relative;
            overflow: hidden;
        }

        .featured-image i {
            width: 80px;
            height: 80px;
            color: rgba(250, 204, 21, 0.15);
        }

        .featured-image .badge {
            position: absolute;
            top: 16px;
            left: 16px;
            padding: 4px 12px;
            border-radius: 100px;
            background: rgba(250, 204, 21, 0.12);
            border: 1px solid rgba(250, 204, 21, 0.15);
            font-size: 10px;
            font-weight: 600;
            color: var(--brand-yellow);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .featured-content {
            padding: 28px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .featured-content .date {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 500;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .featured-content h2 {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
            letter-spacing: -0.02em;
        }

        .featured-content p {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.7;
            margin-bottom: 16px;
        }

        .featured-content .read-more {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--brand-yellow);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .featured-content .read-more:hover { gap: 10px; }
        .featured-content .read-more i { width: 16px; height: 16px; }

        /* ===== News Grid ===== */
        .news-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        @media (min-width: 640px) { .news-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .news-grid { grid-template-columns: repeat(3, 1fr); } }

        .news-card {
            background: var(--bg-card);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-subtle);
            border-radius: 20px;
            padding: 24px;
            cursor: pointer;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .news-card:hover {
            border-color: var(--border-hover);
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .news-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(250, 204, 21, 0.12), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .news-card:hover::before { opacity: 1; }

        .news-card .news-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }

        .news-card .news-icon i { width: 20px; height: 20px; }

        .news-card .news-date {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
        }

        .news-card h3 {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
            line-height: 1.4;
        }

        .news-card p {
            font-size: 12px;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 14px;
        }

        .news-card .news-tag {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 100px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .news-tag.release { background: rgba(250, 204, 21, 0.1); color: var(--brand-yellow); }
        .news-tag.update { background: rgba(99, 102, 241, 0.1); color: var(--brand-indigo); }
        .news-tag.event { background: rgba(34, 197, 94, 0.1); color: #22C55E; }
        .news-tag.tutorial { background: rgba(239, 68, 68, 0.1); color: #EF4444; }

        /* ===== Newsletter ===== */
        .newsletter-card {
            margin-top: 32px;
            background: linear-gradient(135deg, rgba(250,204,21,0.04), rgba(99,102,241,0.04));
            border: 1px solid var(--border-subtle);
            border-radius: 24px;
            padding: 32px;
            text-align: center;
        }

        .newsletter-card h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .newsletter-card p {
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 20px;
        }

        .newsletter-form {
            display: flex;
            gap: 10px;
            max-width: 440px;
            margin: 0 auto;
        }

        .newsletter-form input {
            flex: 1;
            padding: 12px 18px;
            border-radius: 12px;
            background: var(--bg-input);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            outline: none;
            transition: all 0.25s ease;
        }

        .newsletter-form input:focus {
            border-color: rgba(250, 204, 21, 0.3);
            box-shadow: 0 0 0 4px rgba(250, 204, 21, 0.06);
        }

        .newsletter-form button {
            padding: 12px 24px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #FACC15, #EAB308);
            color: #0B0F19;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.25s ease;
            white-space: nowrap;
        }

        .newsletter-form button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(250, 204, 21, 0.25);
        }

        @media (max-width: 480px) {
            .newsletter-form { flex-direction: column; }
        }
    </style>
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div id="content-wrapper">
        <main class="news-page">

            <div class="page-header">
                <h1 class="gradient-text" data-i18n="news.title">News & Updates</h1>
                <p data-i18n="news.desc">Stay up to date with the latest RoKenAI features, research breakthroughs, and community events.</p>
            </div>

            <!-- Featured Article -->
            <div class="featured-article">
                <div class="featured-image">
                    <i data-lucide="sparkles"></i>
                    <span class="badge" data-i18n="news.featured">Featured</span>
                </div>
                <div class="featured-content">
                    <div class="date"><i data-lucide="calendar" style="width:14px;height:14px;"></i> June 20, 2026</div>
                    <h2>YOLOv8 + OpenVINO: 3x Faster Inference</h2>
                    <p>We've rolled out a major performance update. By converting YOLOv8 models to OpenVINO format, inference speed has increased by over 300% on supported hardware — enabling real-time road damage detection at 60+ FPS.</p>
                    <a href="chat.php?prompt=Tell+me+about+the+YOLOv8+OpenVINO+performance+update" class="read-more">
                        <span data-i18n="news.readMore">Read more</span> <i data-lucide="arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- News Grid -->
            <div class="news-grid">

                <div class="news-card">
                    <div class="news-icon" style="background:rgba(250,204,21,0.1);color:var(--brand-yellow);"><i data-lucide="rocket"></i></div>
                    <div class="news-date">June 15, 2026</div>
                    <h3>RoKenAI v2.0 Launch</h3>
                    <p>Introducing our biggest update yet — Bento Grid UI, AI chat workspace, real-time detection pipeline, and enhanced report generation with PDF export.</p>
                    <span class="news-tag release">Release</span>
                </div>

                <div class="news-card">
                    <div class="news-icon" style="background:rgba(99,102,241,0.1);color:var(--brand-indigo);"><i data-lucide="database"></i></div>
                    <div class="news-date">June 10, 2026</div>
                    <h3>Road Damage Dataset v2 Released</h3>
                    <p>Expanded dataset with 15,000+ annotated road images covering 8 damage categories. Includes night-time and wet-surface conditions for robust model training.</p>
                    <span class="news-tag update">Update</span>
                </div>

                <div class="news-card">
                    <div class="news-icon" style="background:rgba(34,197,94,0.1);color:#22C55E;"><i data-lucide="users"></i></div>
                    <div class="news-date">June 5, 2026</div>
                    <h3>Community Webinar: Road AI</h3>
                    <p>Join our upcoming webinar on July 12 where we'll discuss best practices for deploying YOLOv8-based road inspection systems at scale.</p>
                    <span class="news-tag event">Event</span>
                </div>

                <div class="news-card">
                    <div class="news-icon" style="background:rgba(239,68,68,0.1);color:#EF4444;"><i data-lucide="book-open"></i></div>
                    <div class="news-date">May 28, 2026</div>
                    <h3>New Tutorial: Training from Scratch</h3>
                    <p>Step-by-step guide covering dataset preparation, YOLOv8 configuration, training pipeline, and model evaluation for road damage detection.</p>
                    <span class="news-tag tutorial">Tutorial</span>
                </div>

                <div class="news-card">
                    <div class="news-icon" style="background:rgba(250,204,21,0.1);color:var(--brand-yellow);"><i data-lucide="cpu"></i></div>
                    <div class="news-date">May 20, 2026</div>
                    <h3>OpenVINO Integration Complete</h3>
                    <p>YOLOv8 models can now be exported to OpenVINO IR format for optimized inference on Intel CPUs, GPUs, and VPUs with minimal accuracy loss.</p>
                    <span class="news-tag update">Update</span>
                </div>

                <div class="news-card">
                    <div class="news-icon" style="background:rgba(99,102,241,0.1);color:var(--brand-indigo);"><i data-lucide="upload"></i></div>
                    <div class="news-date">May 12, 2026</div>
                    <h3>Batch Upload & Processing</h3>
                    <p>New batch processing feature allows uploading and analyzing multiple road images simultaneously. Supports ZIP archives and bulk folder uploads.</p>
                    <span class="news-tag release">Release</span>
                </div>

            </div>

            <!-- Newsletter -->
            <div class="newsletter-card">
                <i data-lucide="mail" style="width:32px;height:32px;color:var(--brand-yellow);margin-bottom:12px;"></i>
                <h3 data-i18n="news.stayUpdated">Stay Updated</h3>
                <p data-i18n="news.newsletterDesc">Get the latest RoKenAI news, tutorials, and updates delivered to your inbox.</p>
                <form class="newsletter-form" onsubmit="alert('Subscribed! (Demo)'); return false;">
                    <input type="email" data-i18n="news.newsletterPlaceholder" placeholder="Enter your email address" required>
                    <button type="submit" data-i18n="news.subscribe">Subscribe</button>
                </form>
            </div>

        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
