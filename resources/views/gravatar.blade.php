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

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.03);
            padding: 28px;
            margin-bottom: 24px;
        }

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

        .search-box {
            margin-bottom: 20px;
        }

        .search-box input {
            background: #f9fafb;
        }

        .stats-bar {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            background: #f9fafb;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .stat-item {
            flex: 1;
            text-align: center;
            min-width: 80px;
        }

        .stat-num {
            display: block;
            font-size: 20px;
            font-weight: 700;
            color: #3b82f6;
        }

        .stat-label {
            display: block;
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }

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
            flex-wrap: wrap;
            gap: 10px;
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
            flex-wrap: wrap;
            justify-content: flex-end;
        }

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

        .action-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

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
            max-height: 85vh;
            overflow-y: auto;
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
            margin-top: 16px;
        }

        .row {
            display: flex;
            gap: 12px;
        }

        .row > * {
            flex: 1;
        }

        .pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-top: 20px;
        }

        .page-btn {
            padding: 6px 14px;
            border-radius: 8px;
            background: #f3f4f6;
            color: #374151;
            text-decoration: none;
            font-size: 13px;
        }

        .page-btn.disabled {
            opacity: 0.4;
            pointer-events: none;
        }

        .page-info {
            font-size: 13px;
            color: #6b7280;
        }

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
            .avatar-actions {
                justify-content: center;
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
    <div class="header">
        <h1>Gravatar Generator</h1>
    </div>

    <div class="card">
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">✗ {{ session('error') }}</div>
        @endif

        <div class="stats-bar">
            <div class="stat-item">
                <span class="stat-num" id="statTotal">-</span>
                <span class="stat-label">Total</span>
            </div>
            <div class="stat-item">
                <span class="stat-num" id="statReal">-</span>
                <span class="stat-label">Real Photos</span>
            </div>
            <div class="stat-item">
                <span class="stat-num" id="statFavorites">-</span>
                <span class="stat-label">Favorites</span>
            </div>
            <div class="stat-item">
                <span class="stat-num" id="statRecent">-</span>
                <span class="stat-label">This Week</span>
            </div>
        </div>

        <div class="search-box">
            <form method="GET" action="/">
                <div class="row">
                    <div style="flex:2;">
                        <input type="text" name="search" placeholder="Search by email..." value="{{ request('search') }}">
                    </div>
                    <div>
                        <select name="sort" onchange="this.form.submit()">
                            <option value="latest" {{ ($sort ?? 'latest') == 'latest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ ($sort ?? '') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="email_asc" {{ ($sort ?? '') == 'email_asc' ? 'selected' : '' }}>Email A-Z</option>
                            <option value="email_desc" {{ ($sort ?? '') == 'email_desc' ? 'selected' : '' }}>Email Z-A</option>
                            <option value="favorites" {{ ($sort ?? '') == 'favorites' ? 'selected' : '' }}>Favorites First</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>

        @if(request('search'))
            <p class="text-muted" style="margin-bottom: 16px;">
                Results for: <strong>{{ request('search') }}</strong>
                <a href="/" style="color: #3b82f6; text-decoration: none; margin-left: 8px;">Clear</a>
            </p>
        @endif

        <div class="section-title">Generate Avatar</div>
        <form method="POST" action="{{ route('generate.avatar') }}" id="singleForm">
            @csrf
            <div class="row">
                <div style="flex: 3;">
                    <input type="email" name="email" id="emailInput" placeholder="Enter email address" required>
                </div>
                <div>
                    <button type="submit">Generate</button>
                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div>
                    <select name="size" id="sizeSelect">
                        <option value="80">80px</option>
                        <option value="200" selected>200px</option>
                        <option value="300">300px</option>
                    </select>
                </div>
                <div>
                    <select name="rating" id="ratingSelect">
                        <option value="g" selected>G - General</option>
                        <option value="pg">PG - Parental Guidance</option>
                        <option value="r">R - Restricted</option>
                        <option value="x">X - Explicit</option>
                    </select>
                </div>
                <div>
                    <select name="default_image" id="defaultImageSelect">
                        <option value="identicon" selected>Identicon</option>
                        <option value="monsterid">MonsterID</option>
                        <option value="wavatar">Wavatar</option>
                        <option value="retro">Retro</option>
                        <option value="robohash">RoboHash</option>
                        <option value="mp">Mystery Person</option>
                        <option value="blank">Blank</option>
                    </select>
                </div>
            </div>
        </form>

        <div id="previewBox" class="preview-box" style="display: none;">
            <img id="previewImg" src="" alt="Preview">
            <div class="preview-info">
                <div id="previewEmail" class="preview-email"></div>
                <div id="previewStatus" class="preview-status"></div>
            </div>
            <button id="previewBtn" class="btn-sm" style="background: #10b981;">Generate</button>
        </div>

        <hr>

        <div class="section-title">
            Bulk Generate <span class="badge">New</span>
        </div>
        <form method="POST" action="{{ route('bulk.generate') }}">
            @csrf
            <textarea name="emails" rows="2" placeholder="Enter multiple emails separated by commas or new lines"></textarea>
            <div class="row" style="margin-top: 12px;">
                <div>
                    <select name="bulk_size">
                        <option value="80">80px</option>
                        <option value="200" selected>200px</option>
                        <option value="300">300px</option>
                    </select>
                </div>
                <div>
                    <select name="bulk_rating">
                        <option value="g" selected>G</option>
                        <option value="pg">PG</option>
                        <option value="r">R</option>
                        <option value="x">X</option>
                    </select>
                </div>
                <div>
                    <select name="bulk_default_image">
                        <option value="identicon" selected>Identicon</option>
                        <option value="monsterid">MonsterID</option>
                        <option value="wavatar">Wavatar</option>
                        <option value="retro">Retro</option>
                        <option value="robohash">RoboHash</option>
                        <option value="mp">Mystery Person</option>
                        <option value="blank">Blank</option>
                    </select>
                </div>
                <div>
                    <button type="submit" style="background: #10b981;">Generate All</button>
                </div>
            </div>
        </form>

        <hr>

        <div class="action-bar">
            <a href="{{ route('export.csv') }}" style="text-decoration: none;">
                <button type="button" class="btn-secondary">Export CSV</button>
            </a>
            <button type="button" onclick="showClearModal()" class="btn-danger">Clear All</button>
        </div>

        <div class="section-title">
            Saved Avatars ({{ $totalCount }})
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
                            <div class="avatar-email">
                                {{ $avatar->email }}
                                @if($avatar->has_real_gravatar)
                                    <span class="badge" style="background:#d1fae5;color:#059669;">Real Photo</span>
                                @else
                                    <span class="badge">Default</span>
                                @endif
                            </div>
                            <div class="avatar-date">{{ $avatar->created_at->diffForHumans() }} • {{ strtoupper($avatar->rating) }} • {{ $avatar->size }}px</div>
                        </div>
                    </div>
                    <div class="avatar-actions">
                        <form method="POST" action="{{ route('favorite.avatar', $avatar->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-sm" style="background: {{ $avatar->is_favorite ? '#fbbf24' : '#f3f4f6' }}; color: {{ $avatar->is_favorite ? 'white' : '#374151' }};">{{ $avatar->is_favorite ? '★' : '☆' }}</button>
                        </form>
                        <button class="btn-secondary btn-sm" onclick="openEditModal({{ $avatar->id }}, '{{ $avatar->email }}', {{ $avatar->size }}, '{{ $avatar->rating }}', '{{ $avatar->default_image }}')">Edit</button>
                        <button class="btn-secondary btn-sm" onclick="copyEmail('{{ $avatar->email }}')">Copy</button>
                        <button class="btn-secondary btn-sm" onclick="copyUrl('{{ $avatar->avatar }}')">Link</button>
                        <a href="{{ $avatar->avatar }}" download target="_blank" style="text-decoration:none;">
                            <button type="button" class="btn-secondary btn-sm">Download</button>
                        </a>
                        <form method="POST" action="{{ route('refresh.cache', $avatar->id) }}">
                            @csrf
                            <button type="submit" class="btn-secondary btn-sm">Refresh</button>
                        </form>
                        <form method="POST" action="{{ route('delete.avatar', $avatar->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if($avatars->hasPages())
            <div class="pagination-wrap">
                @if($avatars->onFirstPage())
                    <span class="page-btn disabled">‹ Prev</span>
                @else
                    <a href="{{ $avatars->previousPageUrl() }}" class="page-btn">‹ Prev</a>
                @endif

                <span class="page-info">Page {{ $avatars->currentPage() }} of {{ $avatars->lastPage() }}</span>

                @if($avatars->hasMorePages())
                    <a href="{{ $avatars->nextPageUrl() }}" class="page-btn">Next ›</a>
                @else
                    <span class="page-btn disabled">Next ›</span>
                @endif
            </div>
        @endif
    </div>
</div>

<div id="clearModal" class="modal">
    <div class="modal-content">
        <h3>Delete All Avatars?</h3>
        <p>This will permanently delete all {{ $totalCount }} avatar(s). This action cannot be undone.</p>
        <div class="modal-buttons">
            <button onclick="closeModal()" class="btn-secondary">Cancel</button>
            <form method="POST" action="{{ route('clear.all') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Delete All</button>
            </form>
        </div>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <h3>Edit Avatar Settings</h3>
        <form method="POST" id="editForm">
            @csrf
            @method('PATCH')
            <div class="form-group" style="text-align:left;">
                <label>Email</label>
                <input type="text" id="editEmailDisplay" disabled>
            </div>
            <div class="form-group" style="text-align:left;">
                <label>Size</label>
                <select name="size" id="editSize">
                    <option value="80">80px</option>
                    <option value="200">200px</option>
                    <option value="300">300px</option>
                </select>
            </div>
            <div class="form-group" style="text-align:left;">
                <label>Rating</label>
                <select name="rating" id="editRating">
                    <option value="g">G - General</option>
                    <option value="pg">PG - Parental Guidance</option>
                    <option value="r">R - Restricted</option>
                    <option value="x">X - Explicit</option>
                </select>
            </div>
            <div class="form-group" style="text-align:left;">
                <label>Default Image</label>
                <select name="default_image" id="editDefaultImage">
                    <option value="identicon">Identicon</option>
                    <option value="monsterid">MonsterID</option>
                    <option value="wavatar">Wavatar</option>
                    <option value="retro">Retro</option>
                    <option value="robohash">RoboHash</option>
                    <option value="mp">Mystery Person</option>
                    <option value="blank">Blank</option>
                </select>
            </div>
            <div class="modal-buttons">
                <button type="button" onclick="closeEditModal()" class="btn-secondary">Cancel</button>
                <button type="submit">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    const emailInput = document.getElementById('emailInput');
    const sizeSelect = document.getElementById('sizeSelect');
    const ratingSelect = document.getElementById('ratingSelect');
    const defaultImageSelect = document.getElementById('defaultImageSelect');
    const previewBox = document.getElementById('previewBox');
    const previewImg = document.getElementById('previewImg');
    const previewEmailSpan = document.getElementById('previewEmail');
    const previewStatusSpan = document.getElementById('previewStatus');
    const previewBtn = document.getElementById('previewBtn');

    let previewTimeout;

    function triggerPreview() {
        clearTimeout(previewTimeout);
        const email = emailInput.value.trim();

        if (email && email.includes('@')) {
            previewTimeout = setTimeout(() => fetchPreview(email), 400);
        } else {
            previewBox.style.display = 'none';
        }
    }

    emailInput.addEventListener('input', triggerPreview);
    sizeSelect.addEventListener('change', triggerPreview);
    ratingSelect.addEventListener('change', triggerPreview);
    defaultImageSelect.addEventListener('change', triggerPreview);

    function fetchPreview(email) {
        fetch('{{ route("preview.avatar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                preview_email: email,
                preview_size: sizeSelect.value,
                preview_rating: ratingSelect.value,
                preview_default_image: defaultImageSelect.value
            })
        })
        .then(response => response.json())
        .then(data => {
            previewImg.src = data.avatar_url;
            previewEmailSpan.textContent = data.email;

            let statusParts = [];

            if (data.exists) {
                statusParts.push('Already saved');
                previewBtn.style.display = 'none';
            } else {
                statusParts.push('Ready to generate');
                previewBtn.style.display = 'inline-block';
            }

            statusParts.push(data.is_real_gravatar ? 'Real photo found' : 'No real photo, using default');

            previewStatusSpan.textContent = statusParts.join(' • ');
            previewStatusSpan.style.color = data.exists ? '#ef4444' : '#10b981';

            previewBox.style.display = 'flex';

            previewBtn.onclick = function() {
                const form = document.getElementById('singleForm');
                const hiddenEmail = document.createElement('input');
                hiddenEmail.type = 'hidden';
                hiddenEmail.name = 'email';
                hiddenEmail.value = email;
                form.appendChild(hiddenEmail);
                form.submit();
            };
        })
        .catch(() => {});
    }

    function copyEmail(email) {
        navigator.clipboard.writeText(email).then(() => {
            showToast('Copied: ' + email);
        });
    }

    function copyUrl(url) {
        navigator.clipboard.writeText(url).then(() => {
            showToast('Copied avatar URL');
        });
    }

    function showToast(message) {
        const toast = document.createElement('div');
        toast.textContent = '✓ ' + message;
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
    }

    function showClearModal() {
        document.getElementById('clearModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('clearModal').classList.remove('active');
    }

    function openEditModal(id, email, size, rating, defaultImage) {
        document.getElementById('editForm').action = '/avatar/' + id;
        document.getElementById('editEmailDisplay').value = email;
        document.getElementById('editSize').value = size;
        document.getElementById('editRating').value = rating;
        document.getElementById('editDefaultImage').value = defaultImage;
        document.getElementById('editModal').classList.add('active');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
    }

    window.onclick = function(event) {
        const clearModal = document.getElementById('clearModal');
        const editModal = document.getElementById('editModal');
        if (event.target === clearModal) closeModal();
        if (event.target === editModal) closeEditModal();
    }

    fetch('{{ route("avatar.stats") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('statTotal').textContent = data.total;
            document.getElementById('statReal').textContent = data.real_gravatars;
            document.getElementById('statFavorites').textContent = data.favorites;
            document.getElementById('statRecent').textContent = data.recent_7_days;
        })
        .catch(() => {});

    const style = document.createElement('style');
    style.textContent = `@keyframes fadeOut { 0% { opacity: 1; } 70% { opacity: 1; } 100% { opacity: 0; visibility: hidden; } }`;
    document.head.appendChild(style);
</script>

</body>
</html>