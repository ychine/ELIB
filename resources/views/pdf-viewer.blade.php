<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Viewer | ISU StudyGo</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            background-color: #0f0f0f;
            overflow-y: auto !important;
            height: auto !important;
            position: relative;
        }

        body {
            background-color: #0f0f0f;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
            overflow-y: auto !important;
            height: auto !important;
            position: relative;
            margin: 0;
            padding: 0;
            transform: none !important; 
        }

        #toolbar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            width: 100vw !important;
            background: linear-gradient(to bottom, rgba(0,0,0,0.8), rgba(0,0,0,0.6)) !important;
            color: white;
            padding: 10px 20px;
            display: flex !important;
            justify-content: space-between;
            align-items: center;
            z-index: 10000 !important;
            backdrop-filter: blur(10px);
            margin: 0 !important;
            box-sizing: border-box !important;
        }

        #toolbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        #toolbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .toolbar-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }

        .toolbar-btn:hover:not(:disabled) {
            background: rgba(255, 255, 255, 0.3);
        }

        .toolbar-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        #page-info {
            font-size: 14px;
            padding: 8px 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        #zoom-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #zoom-level {
            min-width: 60px;
            text-align: center;
        }

        #pdf-container {
            margin-top: 60px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            background-color: #0f0f0f;
            min-height: calc(100vh - 60px);
        }

        .page-container {
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            background: white;
            position: relative;
        }

        .page-container canvas {
            display: block;
            max-width: 100%;
            height: auto;
        }

        #watermark {
            position: fixed !important;
            top: 50vh !important;
            left: 50vw !important;
            transform: translate(-50%, -50%) rotate(-45deg) !important;
            opacity: 0.3;
            font-size: 60px;
            pointer-events: none !important;
            user-select: none !important;
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            color: #ffffff;
            z-index: 9998 !important;
            white-space: nowrap;
            transition: opacity 0.3s ease;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
            will-change: transform;
            margin: 0 !important;
            padding: 0 !important;
            position: fixed !important;
        }

        #watermark.enhanced {
            opacity: 0.8 !important;
            font-size: 80px;
            color: #dc2626;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.9);
        }

        .watermark-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            pointer-events: none !important;
            z-index: 9997 !important;
            background-image: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 100px,
                rgba(255,255,255,0.05) 100px,
                rgba(255,255,255,0.05) 200px
            );
            will-change: transform;
            margin: 0 !important;
            padding: 0 !important;
        }

        .watermark-text-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            pointer-events: none !important;
            z-index: 9999 !important;
            display: grid !important;
            grid-template-columns: repeat(3, 1fr) !important;
            grid-template-rows: repeat(3, 1fr) !important;
            opacity: 0.2;
            transition: opacity 0.3s ease;
            will-change: transform;
            margin: 0 !important;
            padding: 0 !important;
        }

        .watermark-text-overlay.enhanced {
            opacity: 0.5 !important;
        }

        .watermark-text-overlay div {
            display: flex;
            align-items: center;
            justify-content: center;
            transform: rotate(-45deg);
            font-size: 24px;
            color: #ffffff;
            font-weight: bold;
            user-select: none !important;
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
            pointer-events: none !important;
        }

        #loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 18px;
            z-index: 999;
        }

        #warning-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            z-index: 10000;
            text-align: center;
            max-width: 400px;
            display: none;
            animation: shake 0.5s;
        }

        #warning-popup.show {
            display: block;
        }

        #warning-popup i {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
            animation: pulse 1s infinite;
        }

        #warning-popup h3 {
            font-size: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        #warning-popup p {
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        #warning-popup button {
            background: white;
            color: #dc2626;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
            transition: transform 0.2s;
        }

        #warning-popup button:hover {
            transform: scale(1.05);
        }

        @keyframes shake {
            0%, 100% { transform: translate(-50%, -50%) translateX(0); }
            25% { transform: translate(-50%, -50%) translateX(-10px); }
            75% { transform: translate(-50%, -50%) translateX(10px); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .overlay-block {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            display: none;
        }

        .overlay-block.active {
            display: block;
        }

        @media (max-width: 768px) {
            #toolbar {
                flex-wrap: wrap;
                padding: 10px;
            }

            #toolbar-left, #toolbar-right {
                flex-wrap: wrap;
                gap: 10px;
            }

            .toolbar-btn {
                padding: 6px 12px;
                font-size: 12px;
            }

        #pdf-container {
            padding: 10px;
            padding-top: 70px; /* Space for fixed toolbar */
            margin-top: 0;
        }
        }
    </style>
</head>
<body>
    <div id="watermark">{{ $user->email }} | {{ now()->format('Y-m-d H:i') }}</div>
    <div class="watermark-overlay"></div>
    <div class="watermark-text-overlay" id="watermark-text-overlay">
        <div>{{ $user->email }}</div>
        <div>{{ $user->email }}</div>
        <div>{{ $user->email }}</div>
        <div>{{ $user->email }}</div>
        <div>{{ $user->email }}</div>
        <div>{{ $user->email }}</div>
        <div>{{ $user->email }}</div>
        <div>{{ $user->email }}</div>
        <div>{{ $user->email }}</div>
    </div>
    
    <div id="toolbar">
        <div id="toolbar-left">
            <button class="toolbar-btn" id="prev-page" onclick="changePage(-1)">
                <i class="fas fa-chevron-left"></i> Previous
            </button>
            <div id="page-info">
                Page <span id="page-num">1</span> of <span id="page-count">-</span>
            </div>
            <button class="toolbar-btn" id="next-page" onclick="changePage(1)">
                Next <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div id="toolbar-right">
            <div id="zoom-controls">
                <button class="toolbar-btn" onclick="zoomOut()">
                    <i class="fas fa-search-minus"></i>
                </button>
                <span id="zoom-level">100%</span>
                <button class="toolbar-btn" onclick="zoomIn()">
                    <i class="fas fa-search-plus"></i>
                </button>
                <button class="toolbar-btn" onclick="resetZoom()">
                    <i class="fas fa-expand"></i> Fit
                </button>
            </div>
        </div>
    </div>

    <div id="loading">Loading PDF...</div>
    <div id="pdf-container"></div>

    <!-- Warning Popup -->
    <div id="warning-popup">
        <i class="fas fa-exclamation-triangle"></i>
        <h3>⚠️ Screenshot Detected!</h3>
        <p>Screenshots and screen recording are not allowed. This action has been logged and may result in account suspension.</p>
        <button onclick="closeWarning()">I Understand</button>
    </div>

    <!-- Overlay to block content during warning -->
    <div class="overlay-block" id="overlay-block"></div>

    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 
            "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";

        const url = "{{ route('view.book', ['id' => $id]) }}";
        let pdfDoc = null;
        let currentPage = 1;
        let scale = 1.2;
        let pages = [];
        
        // Grace period to prevent false positives on page load
        let pageLoadTime = Date.now();
        let securityEnabled = false;
        let hasBeenVisible = false;
        
        // Track if page has been visible (not just loaded)
        function checkVisibility() {
            if (!document.hidden) {
                hasBeenVisible = true;
            }
        }
        
        // Check visibility immediately and on changes
        checkVisibility();
        document.addEventListener('visibilitychange', checkVisibility);
        
        // Enable security checks after 10 seconds to prevent false positives on page load
        // This gives the page plenty of time to fully load and stabilize
        setTimeout(() => {
            securityEnabled = true;
        }, 10000); // 10 second grace period

        const pdfRequestOptions = {
            url: url,
            withCredentials: true,
            httpHeaders: {
                'Accept': 'application/pdf'
            }
        };

        // Check if URL is accessible first
        fetch(url, {
            method: 'HEAD',
            credentials: 'include'
        })
            .then(response => {
                if (!response.ok) {
                    const loadingEl = document.getElementById('loading');
                    if (response.status === 403) {
                        loadingEl.textContent = 'Access denied. You may need to wait for librarian approval to view this resource.';
                    } else if (response.status === 404) {
                        loadingEl.textContent = 'PDF file not found. Please contact the librarian.';
                    } else {
                        loadingEl.textContent = 'Error loading PDF (HTTP ' + response.status + '). Please contact the librarian.';
                    }
                    loadingEl.style.color = '#ff4444';
                    throw new Error('HTTP ' + response.status);
                }
                // URL is accessible, proceed with PDF.js
                return pdfjsLib.getDocument(pdfRequestOptions).promise;
            })
            .then(pdf => {
                pdfDoc = pdf;
                document.getElementById('page-count').textContent = pdf.numPages;
                document.getElementById('loading').style.display = 'none';
                
                // Render all pages
                for (let i = 1; i <= pdf.numPages; i++) {
                    renderPage(i);
                }
            })
            .catch(error => {
                console.error('Error loading PDF:', error);
                const loadingEl = document.getElementById('loading');
                
                // Only show generic error if we haven't already set a specific message
                if (loadingEl.textContent === 'Loading PDF...') {
                    let errorMsg = 'Error loading PDF';
                    
                    if (error.message && error.message.includes('HTTP')) {
                        // Already handled by fetch check above
                        return;
                    } else if (error.name === 'InvalidPDFException') {
                        errorMsg = 'Invalid PDF file. The file may be corrupted.';
                    } else if (error.name === 'MissingPDFException') {
                        errorMsg = 'PDF file not found. Please contact the librarian.';
                    } else if (error.message) {
                        errorMsg += ': ' + error.message;
                    }
                    
                    loadingEl.textContent = errorMsg;
                    loadingEl.style.color = '#ff4444';
                }
            });

        // Render a single page
        function renderPage(pageNum) {
            pdfDoc.getPage(pageNum).then(page => {
                const viewport = page.getViewport({ scale: scale });
                
                const pageContainer = document.createElement('div');
                pageContainer.className = 'page-container';
                pageContainer.id = `page-${pageNum}`;
                
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                
                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                
                page.render(renderContext).promise.then(() => {
                    pageContainer.appendChild(canvas);
                    document.getElementById('pdf-container').appendChild(pageContainer);
                    pages[pageNum] = { page, viewport, canvas };
                });
            });
        }

        // Change page (scroll to page)
        function changePage(delta) {
            const newPage = currentPage + delta;
            if (newPage >= 1 && newPage <= pdfDoc.numPages) {
                currentPage = newPage;
                updatePageInfo();
                scrollToPage(currentPage);
            }
        }

        // Scroll to specific page
        function scrollToPage(pageNum) {
            const pageElement = document.getElementById(`page-${pageNum}`);
            if (pageElement) {
                pageElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // Update page info
        function updatePageInfo() {
            document.getElementById('page-num').textContent = currentPage;
            document.getElementById('prev-page').disabled = currentPage <= 1;
            document.getElementById('next-page').disabled = currentPage >= pdfDoc.numPages;
        }

        // Zoom functions
        function zoomIn() {
            scale += 0.2;
            updateZoom();
            rerenderAllPages();
        }

        function zoomOut() {
            if (scale > 0.5) {
                scale -= 0.2;
                updateZoom();
                rerenderAllPages();
            }
        }

        function resetZoom() {
            scale = 1.2;
            updateZoom();
            rerenderAllPages();
        }

        function updateZoom() {
            document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
        }

        // Rerender all pages with new scale
        function rerenderAllPages() {
            document.getElementById('pdf-container').innerHTML = '';
            for (let i = 1; i <= pdfDoc.numPages; i++) {
                renderPage(i);
            }
            setTimeout(() => scrollToPage(currentPage), 100);
        }

        // Keyboard navigation and security combined
        document.addEventListener('keydown', (e) => {
            // Navigation keys
            if (e.key === 'ArrowDown' || e.key === 'PageDown') {
                e.preventDefault();
                changePage(1);
                return;
            } else if (e.key === 'ArrowUp' || e.key === 'PageUp') {
                e.preventDefault();
                changePage(-1);
                return;
            } else if (e.key === 'Home') {
                e.preventDefault();
                currentPage = 1;
                updatePageInfo();
                scrollToPage(1);
                return;
            } else if (e.key === 'End') {
                e.preventDefault();
                currentPage = pdfDoc.numPages;
                updatePageInfo();
                scrollToPage(pdfDoc.numPages);
                return;
            }

            // Security: Screenshot detection
            // PrintScreen key
            if (e.key === "PrintScreen" || (e.keyCode === 44)) {
                e.preventDefault();
                enhanceWatermark();
                showWarning();
                return false;
            }
            
            // Windows + PrintScreen
            if (e.key === "PrintScreen" && (e.metaKey || e.ctrlKey)) {
                e.preventDefault();
                enhanceWatermark();
                showWarning();
                return false;
            }

            // Disable certain keyboard shortcuts
            if (e.ctrlKey && !e.shiftKey && ["s", "p", "u", "c"].includes(e.key.toLowerCase())) {
                e.preventDefault();
                enhanceWatermark();
                showWarning();
                return false;
            }

            // F12 (DevTools)
            if (e.key === "F12" || (e.keyCode === 123)) {
                e.preventDefault();
                showWarning();
                return false;
            }

            // Ctrl+Shift+I (DevTools)
            if (e.ctrlKey && e.shiftKey && e.key === "I") {
                e.preventDefault();
                showWarning();
                return false;
            }

            // Ctrl+Shift+J (Console)
            if (e.ctrlKey && e.shiftKey && e.key === "J") {
                e.preventDefault();
                showWarning();
                return false;
            }

            // Ctrl+Shift+C (Inspect Element)
            if (e.ctrlKey && e.shiftKey && e.key === "C") {
                e.preventDefault();
                showWarning();
                return false;
            }

            // Ctrl+Shift+S (Snipping Tool / Save As)
            if (e.ctrlKey && e.shiftKey && (e.key === "S" || e.key === "s")) {
                e.preventDefault();
                enhanceWatermark();
                showWarning();
                return false;
            }

            // Windows Key + Shift + S (Windows 10+ Snipping Tool)
            // Note: Windows key detection is limited in browsers, but we try to catch it
            if (e.metaKey && e.shiftKey && (e.key === "S" || e.key === "s")) {
                e.preventDefault();
                enhanceWatermark();
                showWarning();
                return false;
            }
        });

        // Track scroll to update current page
        let scrollTimeout;
        function updateCurrentPageFromScroll() {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                const containers = document.querySelectorAll('.page-container');
                if (containers.length === 0) return;
                
                const scrollPosition = window.scrollY + window.innerHeight / 2;
                
                containers.forEach((container, index) => {
                    const rect = container.getBoundingClientRect();
                    const containerTop = rect.top + window.scrollY;
                    const containerBottom = containerTop + rect.height;
                    
                    if (scrollPosition >= containerTop && scrollPosition <= containerBottom) {
                        const newPage = index + 1;
                        if (newPage !== currentPage) {
                            currentPage = newPage;
                            updatePageInfo();
                        }
                    }
                });
            }, 100);
        }
        
        window.addEventListener('scroll', updateCurrentPageFromScroll, { passive: true });
        window.addEventListener('wheel', updateCurrentPageFromScroll, { passive: true });
        document.addEventListener('scroll', updateCurrentPageFromScroll, { passive: true });

        // Initial page info update
        setTimeout(() => {
            if (pdfDoc) {
                updatePageInfo();
            }
        }, 500);
        
        // Watermarks and toolbar are fixed via CSS with !important flags
        // No JavaScript needed - CSS handles all positioning

        // Enhance watermark visibility
        function enhanceWatermark() {
            const watermark = document.getElementById('watermark');
            const watermarkOverlay = document.getElementById('watermark-text-overlay');
            
            if (watermark) {
                watermark.classList.add('enhanced');
            }
            if (watermarkOverlay) {
                watermarkOverlay.classList.add('enhanced');
            }
            
            // Keep enhanced state for 30 seconds
            setTimeout(() => {
                if (watermark) {
                    watermark.classList.remove('enhanced');
                }
                if (watermarkOverlay) {
                    watermarkOverlay.classList.remove('enhanced');
                }
            }, 30000);
        }

        // Show warning popup
        function showWarning() {
            // Don't show warnings during grace period (first 2 seconds after page load)
            if (!securityEnabled) {
                return;
            }
            
            // Enhance watermark when screenshot is detected
            enhanceWatermark();
            
            const popup = document.getElementById('warning-popup');
            const overlay = document.getElementById('overlay-block');
            popup.classList.add('show');
            overlay.classList.add('active');
            
            // Auto-close after 5 seconds
            setTimeout(() => {
                closeWarning();
            }, 5000);
        }

        // Close warning popup
        function closeWarning() {
            const popup = document.getElementById('warning-popup');
            const overlay = document.getElementById('overlay-block');
            popup.classList.remove('show');
            overlay.classList.remove('active');
        }


        // Detect right-click (only after grace period)
        document.addEventListener("contextmenu", e => {
            e.preventDefault();
            if (securityEnabled) {
                showWarning();
            }
            return false;
        });

        // Detect browser DevTools opening (for desktop)
        let devToolsOpen = false;
        const threshold = 200; // Increased threshold to reduce false positives
        setInterval(() => {
            // Only check after grace period AND at least 5 seconds after page load
            if (!securityEnabled || (Date.now() - pageLoadTime < 5000)) {
                return;
            }
            
            // Only check if window is actually focused
            if (!document.hasFocus()) {
                return;
            }
            
            if (window.outerHeight - window.innerHeight > threshold || 
                window.outerWidth - window.innerWidth > threshold) {
                if (!devToolsOpen) {
                    devToolsOpen = true;
                    showWarning();
                }
            } else {
                devToolsOpen = false;
            }
        }, 1000); // Check less frequently

        // Detect mobile screenshot attempts (only on mobile devices)
        const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
        
        // iOS screenshot detection
        if (isMobile && /iPhone|iPad|iPod/.test(navigator.userAgent)) {
            let lastBlurTime = 0;
            
            // Detect when app goes to background (common when taking screenshot)
            document.addEventListener("visibilitychange", () => {
                if (!securityEnabled) return;
                
                // Only trigger if page was visible for at least 5 seconds
                if (Date.now() - pageLoadTime < 5000) return;
                
                if (document.hidden) {
                    setTimeout(() => {
                        if (document.hidden && securityEnabled && (Date.now() - pageLoadTime > 5000)) {
                            showWarning();
                        }
                    }, 500); // Increased delay to reduce false positives
                }
            });

            // Detect blur event (app switching)
            window.addEventListener("blur", () => {
                if (!securityEnabled) return;
                
                // Only trigger if page was visible for at least 5 seconds
                if (Date.now() - pageLoadTime < 5000) return;
                
                // Don't trigger if blur happened too recently (likely page transition)
                const now = Date.now();
                if (now - lastBlurTime < 2000) return;
                lastBlurTime = now;
                
                setTimeout(() => {
                    if (document.hidden && securityEnabled && (Date.now() - pageLoadTime > 5000)) {
                        showWarning();
                    }
                }, 500);
            });
        }

        // Android screenshot detection
        if (/Android/.test(navigator.userAgent)) {
            let lastBlurTime = 0;
            // Monitor for rapid visibility changes
            let lastVisibilityChange = Date.now();
            document.addEventListener("visibilitychange", () => {
                if (!securityEnabled) return;
                
                // Only trigger if page was visible for at least 5 seconds
                if (Date.now() - pageLoadTime < 5000) return;
                
                const now = Date.now();
                if (document.hidden && (now - lastVisibilityChange) < 500) {
                    showWarning();
                }
                lastVisibilityChange = now;
            });

            window.addEventListener("blur", () => {
                if (!securityEnabled) return;
                
                // Only trigger if page was visible for at least 5 seconds
                if (Date.now() - pageLoadTime < 5000) return;
                
                // Don't trigger if blur happened too recently
                const now = Date.now();
                if (now - lastBlurTime < 2000) return;
                lastBlurTime = now;
                
                setTimeout(() => {
                    if (document.hidden && securityEnabled && (Date.now() - pageLoadTime > 5000)) {
                        showWarning();
                    }
                }, 500);
            });
        }

        // Desktop: Detect window blur (could indicate Snipping Tool or screenshot software)
        if (!isMobile) {
            let lastBlurTime = 0;
            let blurTimeout;
            
            window.addEventListener("blur", () => {
                if (!securityEnabled) return;
                if (Date.now() - pageLoadTime < 5000) return;
                
                const now = Date.now();
                // Don't trigger if blur happened too recently
                if (now - lastBlurTime < 2000) return;
                lastBlurTime = now;
                
                // Preemptively enhance watermark when window loses focus
                // This helps catch Snipping Tool usage
                enhanceWatermark();
                
                // If window stays blurred for more than 1 second, show warning
                blurTimeout = setTimeout(() => {
                    if (document.hidden && securityEnabled) {
                        showWarning();
                    }
                }, 1000);
            });
            
            window.addEventListener("focus", () => {
                // Clear timeout if window regains focus quickly
                if (blurTimeout) {
                    clearTimeout(blurTimeout);
                    blurTimeout = null;
                }
            });
        }

        // Detect iframe embedding (prevent embedding)
        if (window.self !== window.top) {
            window.top.location = window.self.location;
        }

        // Detect copy attempts (only after grace period)
        document.addEventListener("copy", (e) => {
            e.preventDefault();
            if (securityEnabled) {
                showWarning();
            }
            return false;
        });

        // Detect selection attempts (make text harder to select)
        document.addEventListener("selectstart", (e) => {
            e.preventDefault();
            return false;
        });

        // Disable drag
        document.addEventListener("dragstart", (e) => {
            e.preventDefault();
            return false;
        });

        // Additional protection: Make canvas harder to screenshot
        // Add CSS to prevent easy screenshot
        const style = document.createElement('style');
        style.textContent = `
            body {
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }
            canvas {
                -webkit-touch-callout: none;
                -webkit-user-select: none;
                -khtml-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }
        `;
        document.head.appendChild(style);
    </script>
    @include('partials.globalLoader')
</body>
</html>
