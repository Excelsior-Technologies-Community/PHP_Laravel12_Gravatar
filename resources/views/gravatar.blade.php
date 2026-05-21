<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gravatar Generator</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f5f7fa;
            color: #1a1a2e;
            line-height: 1.5;
            padding: 30px 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        /* Card Style */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.03);
            padding: 28px;
            margin-bottom: 24px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 6px;
        }

        .header p {
            color: #6b7280;
            font-size: 14px;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.2s;
            background: white;
            font-family: inherit;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        textarea {
            resize: vertical;
            font-family: monospace;
            font-size: 13px;
        }

        button {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        button:hover {
            background: #2563eb;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .btn-danger {
            background: #ef4444;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-sm {
            padding: 5px 12px;
            font-size: 12px;
        }

        /* Messages */
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Search */
        .search-box {
            margin-bottom: 20px;
        }

        .search-box input {
            background: #f9fafb;
        }

        /* Avatar List */
        .avatar-list {
            margin-top: 16px;
        }

        .avatar-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px;
            background: #f9fafb;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: all 0.2s;
        }

        .avatar-item:hover {
            background: #f3f4f6;
        }

        .avatar-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .avatar-info img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            background: #e5e7eb;
        }

        .avatar-email {
            font-weight: 500;
            font-size: 14px;
            color: #1f2937;
        }

        .avatar-date {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .avatar-actions {
            display: flex;
            gap: 8px;
        }

        /* Preview Box */
        .preview-box {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px;
            background: #f9fafb;
            border-radius: 12px;
            margin-top: 16px;
        }

        .preview-box img {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #e5e7eb;
        }

        .preview-info {
            flex: 1;
        }

        .preview-email {
            font-size: 13px;
            font-weight: 500;
            color: #1f2937;
        }

        .preview-status {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }

        /* Action Bar */
        .action-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        /* Divider */
        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #e5e7eb;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #1f2937;
        }

        .text-muted {
            color: #9ca3af;
            font-size: 13px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #9ca3af;
        }

        .badge {
            display: inline-block;
            background: #e0e7ff;
            color: #3b82f6;
            font-size: 10px;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 20px;
            margin-left: 8px;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 24px;
            max-width: 380px;
            width: 90%;
            text-align: center;
        }

        .modal-content h3 {
            font-size: 18px;
            margin-bottom: 12px;
        }

        .modal-content p {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        /* Row */
        .row {
            display: flex;
            gap: 12px;
        }

        .row > * {
            flex: 1;
        }

        /* Responsive */
        @media (max-width: 600px) {
            body {
                padding: 20px 16px;
            }
            .card {
                padding: 20px;
            }
            .avatar-item {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            .avatar-info {
                flex-direction: column;
            }
            .row {
                flex-direction: column;
            }
            .action-bar {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>Gravatar Generator</h1>
       
    </div>

    <!-- Main Card -->
    <div class="card">
        <!-- Messages -->
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">✗ {{ session('error') }}</div>
        @endif

        <!-- Search -->
        <div class="search-box">
            <form method="GET" action="/">
                <input type="text" name="search" placeholder=" Search by email..." value="{{ request('search') }}">
            </form>
        </div>

        @if(request('search'))
            <p class="text-muted" style="margin-bottom: 16px;">
                Results for: <strong>{{ request('search') }}</strong>
                <a href="/" style="color: #3b82f6; text-decoration: none; margin-left: 8px;">Clear</a>
            </p>
        @endif

        <!-- Single Generate Form -->
        <div class="section-title">
             Generate Avatar
        </div>
        <form method="POST" action="{{ route('generate.avatar') }}" id="singleForm">
            @csrf
            <div class="row">
                <div style="flex: 2;">
                    <input type="email" name="email" id="emailInput" placeholder="Enter email address" required>
                </div>
                <div>
                    <select name="size">
                        <option value="80">80px</option>
                        <option value="200" selected>200px</option>
                        <option value="300">300px</option>
                    </select>
                </div>
                <div>
                    <button type="submit">Generate</button>
                </div>
            </div>
        </form>

        <!-- Live Preview -->
        <div id="previewBox" class="preview-box" style="display: none;">
            <img id="previewImg" src="" alt="Preview">
            <div class="preview-info">
                <div id="previewEmail" class="preview-email"></div>
                <div id="previewStatus" class="preview-status"></div>
            </div>
            <button id="previewBtn" class="btn-sm" style="background: #10b981;">Generate</button>
        </div>

        <hr>

        <!-- Bulk Generate -->
        <div class="section-title">
            Bulk Generate <span class="badge">New</span>
        </div>
        <form method="POST" action="{{ route('bulk.generate') }}">
            @csrf
            <textarea name="emails" rows="2" placeholder=""></textarea>
            <div class="row" style="margin-top: 12px;">
                <div>
                    <select name="bulk_size">
                        <option value="80">80px</option>
                        <option value="200" selected>200px</option>
                        <option value="300">300px</option>
                    </select>
                </div>
                <div>
                    <button type="submit" style="background: #10b981;">Generate All</button>
                </div>
            </div>
        </form>

        <hr>

        <!-- Actions -->
        <div class="action-bar">
            <a href="{{ route('export.csv') }}" style="text-decoration: none;">
                <button type="button" class="btn-secondary">Export CSV</button>
            </a>
            <button type="button" onclick="showClearModal()" class="btn-danger"> Clear All</button>
        </div>

        <!-- Avatar List -->
        <div class="section-title">
             Saved Avatars ({{ $avatars->count() }})
        </div>

        @if($avatars->isEmpty())
            <div class="empty-state">
                <div style="font-size: 40px; margin-bottom: 10px;"></div>
                <p>No avatars yet</p>
                <p class="text-muted" style="margin-top: 6px;">Enter an email above to get started</p>
            </div>
        @endif

        <div class="avatar-list">
            @foreach($avatars as $avatar)
                <div class="avatar-item">
                    <div class="avatar-info">
                        <img src="{{ $avatar->avatar }}" alt="avatar">
                        <div>
                            <div class="avatar-email">{{ $avatar->email }}</div>
                            <div class="avatar-date">{{ $avatar->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div class="avatar-actions">
                        <button class="btn-secondary btn-sm" onclick="copyEmail('{{ $avatar->email }}')">Copy</button>
                        <form method="POST" action="{{ route('delete.avatar', $avatar->id) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Clear All Modal -->
<div id="clearModal" class="modal">
    <div class="modal-content">
        <h3>Delete All Avatars?</h3>
        <p>This will permanently delete all {{ $avatars->count() }} avatar(s). This action cannot be undone.</p>
        <div class="modal-buttons">
            <button onclick="closeModal()" class="btn-secondary">Cancel</button>
            <form method="POST" action="{{ route('clear.all') }}" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Delete All</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Live Preview
    const emailInput = document.getElementById('emailInput');
    const previewBox = document.getElementById('previewBox');
    const previewImg = document.getElementById('previewImg');
    const previewEmailSpan = document.getElementById('previewEmail');
    const previewStatusSpan = document.getElementById('previewStatus');
    const previewBtn = document.getElementById('previewBtn');
    
    let previewTimeout;
    
    emailInput.addEventListener('input', function() {
        clearTimeout(previewTimeout);
        const email = this.value.trim();
        
        if (email && email.includes('@')) {
            previewTimeout = setTimeout(() => fetchPreview(email), 400);
        } else {
            previewBox.style.display = 'none';
        }
    });
    
    function fetchPreview(email) {
        const size = document.querySelector('select[name="size"]').value;
        
        fetch('{{ route("preview.avatar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ preview_email: email, preview_size: size })
        })
        .then(response => response.json())
        .then(data => {
            previewImg.src = data.avatar_url;
            previewEmailSpan.textContent = data.email;
            
            if (data.exists) {
                previewStatusSpan.textContent = 'Already exists';
                previewStatusSpan.style.color = '#ef4444';
                previewBtn.style.display = 'none';
            } else {
                previewStatusSpan.textContent = '✓ Ready to generate';
                previewStatusSpan.style.color = '#10b981';
                previewBtn.style.display = 'inline-block';
            }
            
            previewBox.style.display = 'flex';
            
            previewBtn.onclick = function() {
                const form = document.getElementById('singleForm');
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'email';
                hiddenInput.value = email;
                form.appendChild(hiddenInput);
                form.submit();
            };
        })
        .catch(() => {});
    }
    
    // Copy email
    function copyEmail(email) {
        navigator.clipboard.writeText(email).then(() => {
            const toast = document.createElement('div');
            toast.textContent = '✓ Copied: ' + email;
            toast.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: #1f2937;
                color: white;
                padding: 8px 16px;
                border-radius: 10px;
                font-size: 13px;
                z-index: 1000;
                animation: fadeOut 1.5s forwards;
            `;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 1500);
        });
    }
    
    // Modal
    function showClearModal() {
        document.getElementById('clearModal').classList.add('active');
    }
    
    function closeModal() {
        document.getElementById('clearModal').classList.remove('active');
    }
    
    window.onclick = function(event) {
        const modal = document.getElementById('clearModal');
        if (event.target === modal) closeModal();
    }
    
    // Animation
    const style = document.createElement('style');
    style.textContent = `@keyframes fadeOut { 0% { opacity: 1; } 70% { opacity: 1; } 100% { opacity: 0; visibility: hidden; } }`;
    document.head.appendChild(style);
</script>

</body>
</html>