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

        body {
            background-color: #525252;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
        }

        #toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.8), rgba(0,0,0,0.6));
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(10px);
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
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.1;
            font-size: 60px;
            pointer-events: none;
            user-select: none;
            color: #000;
            z-index: 1;
            white-space: nowrap;
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
            }
        }
    </style>
</head>
<body>
    <div id="watermark">{{ $user->email }} | {{ now()->format('Y-m-d H:i') }}</div>
    
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

        const url = "/view-book/{{ $id }}";
        let pdfDoc = null;
        let currentPage = 1;
        let scale = 1.2;
        let pages = [];

        // Load PDF
        pdfjsLib.getDocument(url).promise.then(pdf => {
            pdfDoc = pdf;
            document.getElementById('page-count').textContent = pdf.numPages;
            document.getElementById('loading').style.display = 'none';
            
            // Render all pages
            for (let i = 1; i <= pdf.numPages; i++) {
                renderPage(i);
            }
        }).catch(error => {
            console.error('Error loading PDF:', error);
            document.getElementById('loading').textContent = 'Error loading PDF';
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
                showWarning();
                return false;
            }
            
            // Windows + PrintScreen
            if (e.key === "PrintScreen" && (e.metaKey || e.ctrlKey)) {
                e.preventDefault();
                showWarning();
                return false;
            }

            // Disable certain keyboard shortcuts
            if (e.ctrlKey && ["s", "p", "u", "c"].includes(e.key.toLowerCase())) {
                e.preventDefault();
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
        });

        // Track scroll to update current page
        let scrollTimeout;
        window.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                const containers = document.querySelectorAll('.page-container');
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
        });

        // Initial page info update
        setTimeout(() => {
            if (pdfDoc) {
                updatePageInfo();
            }
        }, 500);

        // Show warning popup
        function showWarning() {
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


        // Detect right-click
        document.addEventListener("contextmenu", e => {
            e.preventDefault();
            showWarning();
            return false;
        });

        // Detect browser DevTools opening (for desktop)
        let devToolsOpen = false;
        const threshold = 160;
        setInterval(() => {
            if (window.outerHeight - window.innerHeight > threshold || 
                window.outerWidth - window.innerWidth > threshold) {
                if (!devToolsOpen) {
                    devToolsOpen = true;
                    showWarning();
                }
            } else {
                devToolsOpen = false;
            }
        }, 500);

        // Detect mobile screenshot attempts
        // iOS screenshot detection
        if (/iPhone|iPad|iPod/.test(navigator.userAgent)) {
            // Detect when app goes to background (common when taking screenshot)
            document.addEventListener("visibilitychange", () => {
                if (document.hidden) {
                    setTimeout(() => {
                        if (document.hidden) {
                            showWarning();
                        }
                    }, 100);
                }
            });

            // Detect blur event (app switching)
            window.addEventListener("blur", () => {
                setTimeout(() => {
                    if (document.hidden) {
                        showWarning();
                    }
                }, 100);
            });
        }

        // Android screenshot detection
        if (/Android/.test(navigator.userAgent)) {
            // Monitor for rapid visibility changes
            let lastVisibilityChange = Date.now();
            document.addEventListener("visibilitychange", () => {
                const now = Date.now();
                if (document.hidden && (now - lastVisibilityChange) < 500) {
                    showWarning();
                }
                lastVisibilityChange = now;
            });

            window.addEventListener("blur", () => {
                setTimeout(() => {
                    if (document.hidden) {
                        showWarning();
                    }
                }, 100);
            });
        }

        // Detect iframe embedding (prevent embedding)
        if (window.self !== window.top) {
            window.top.location = window.self.location;
        }

        // Detect copy attempts
        document.addEventListener("copy", (e) => {
            e.preventDefault();
            showWarning();
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
</body>
</html>
