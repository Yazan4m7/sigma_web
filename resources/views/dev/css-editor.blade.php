<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sigma CSS Editor</title>
    <style>
        :root {
            --dev-bg: #0f172a;
            --dev-panel: #111827;
            --dev-border: #263244;
            --dev-text: #e5edf7;
            --dev-muted: #91a0b6;
            --dev-accent: #38b44a;
            --dev-danger: #ef4444;
            --dev-editor: #07101f;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--dev-bg);
            color: var(--dev-text);
            font-family: Arial, sans-serif;
        }

        .dev-shell {
            display: grid;
            grid-template-columns: minmax(260px, 32vw) 1fr;
            min-height: 100vh;
        }

        .dev-sidebar,
        .dev-main {
            min-width: 0;
            padding: 16px;
        }

        .dev-sidebar {
            background: var(--dev-panel);
            border-right: 1px solid var(--dev-border);
        }

        .dev-header {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .dev-title {
            margin: 0;
            font-size: 18px;
            letter-spacing: 0;
        }

        .dev-count,
        .dev-path,
        .dev-meta {
            color: var(--dev-muted);
            font-size: 12px;
        }

        .dev-search,
        .dev-editor {
            width: 100%;
            color: var(--dev-text);
            background: var(--dev-editor);
            border: 1px solid var(--dev-border);
            border-radius: 8px;
        }

        .dev-search {
            height: 42px;
            padding: 0 12px;
            margin-bottom: 12px;
            outline: none;
        }

        .dev-search:focus,
        .dev-editor:focus {
            border-color: var(--dev-accent);
        }

        .dev-file-list {
            height: calc(100vh - 92px);
            overflow: auto;
            padding-right: 4px;
        }

        .dev-file {
            width: 100%;
            display: block;
            padding: 10px;
            margin-bottom: 6px;
            color: var(--dev-text);
            background: transparent;
            border: 1px solid transparent;
            border-radius: 8px;
            text-align: left;
            cursor: pointer;
        }

        .dev-file:hover,
        .dev-file.is-active {
            border-color: rgba(56, 180, 74, 0.45);
            background: rgba(56, 180, 74, 0.12);
        }

        .dev-file-name {
            display: block;
            font-size: 13px;
            font-weight: 700;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dev-file-path {
            display: block;
            margin-top: 4px;
            color: var(--dev-muted);
            font-size: 11px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dev-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .dev-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .dev-button {
            height: 38px;
            padding: 0 18px;
            color: #fff;
            background: var(--dev-accent);
            border: 0;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }

        .dev-button:disabled {
            cursor: not-allowed;
            opacity: 0.45;
        }

        .dev-status {
            min-height: 18px;
            color: var(--dev-muted);
            font-size: 13px;
        }

        .dev-status.is-error {
            color: var(--dev-danger);
        }

        .dev-editor {
            min-height: calc(100vh - 92px);
            padding: 14px;
            resize: none;
            outline: none;
            font-family: Consolas, "Courier New", monospace;
            font-size: 13px;
            line-height: 1.5;
            tab-size: 4;
            white-space: pre;
        }

        @media (max-width: 760px) {
            .dev-shell {
                grid-template-columns: 1fr;
            }

            .dev-sidebar {
                border-right: 0;
                border-bottom: 1px solid var(--dev-border);
            }

            .dev-file-list {
                max-height: 36vh;
            }

            .dev-editor {
                min-height: 56vh;
            }
        }
    </style>
</head>
<body>
<div class="dev-shell">
    <aside class="dev-sidebar">
        <div class="dev-header">
            <h1 class="dev-title">CSS Files</h1>
            <span class="dev-count">{{ count($files) }}</span>
        </div>
        <input class="dev-search" id="fileSearch" type="search" placeholder="Search CSS, SCSS, SASS, LESS">
        <div class="dev-file-list" id="fileList">
            @foreach ($files as $file)
                <button class="dev-file" type="button" data-path="{{ $file['path'] }}">
                    <span class="dev-file-name">{{ $file['name'] }}</span>
                    <span class="dev-file-path">{{ $file['path'] }}</span>
                </button>
            @endforeach
        </div>
    </aside>

    <main class="dev-main">
        <div class="dev-toolbar">
            <div>
                <div class="dev-path" id="currentPath">Pick a file</div>
                <div class="dev-meta" id="currentMeta"></div>
            </div>
            <div class="dev-actions">
                <span class="dev-status" id="status"></span>
                <button class="dev-button" id="saveButton" type="button" disabled>Save</button>
            </div>
        </div>
        <textarea class="dev-editor" id="editor" spellcheck="false" disabled></textarea>
    </main>
</div>

<script>
    const routes = {
        show: @json(route('dev.css-editor.show')),
        update: @json(route('dev.css-editor.update')),
    };
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const fileButtons = Array.from(document.querySelectorAll('.dev-file'));
    const searchInput = document.getElementById('fileSearch');
    const editor = document.getElementById('editor');
    const saveButton = document.getElementById('saveButton');
    const currentPath = document.getElementById('currentPath');
    const currentMeta = document.getElementById('currentMeta');
    const status = document.getElementById('status');
    let activePath = '';
    let dirty = false;

    function setStatus(message, isError = false) {
        status.textContent = message;
        status.classList.toggle('is-error', isError);
    }

    function setDirty(value) {
        dirty = value;
        saveButton.disabled = !activePath || !dirty;
    }

    function activateButton(path) {
        fileButtons.forEach((button) => {
            button.classList.toggle('is-active', button.dataset.path === path);
        });
    }

    async function loadFile(path) {
        if (dirty && !confirm('Current file has unsaved changes. Open another file?')) {
            return;
        }

        setStatus('Loading...');
        const response = await fetch(`${routes.show}?path=${encodeURIComponent(path)}`, {
            headers: { 'Accept': 'application/json' },
        });

        if (!response.ok) {
            setStatus('Could not load file.', true);
            return;
        }

        const data = await response.json();
        activePath = data.path;
        editor.value = data.content;
        editor.disabled = false;
        currentPath.textContent = data.path;
        currentMeta.textContent = `Last modified: ${data.updated_at}`;
        activateButton(data.path);
        setDirty(false);
        setStatus('Loaded');
        editor.focus();
    }

    async function saveFile() {
        if (!activePath || !dirty) {
            return;
        }

        saveButton.disabled = true;
        setStatus('Saving...');

        const response = await fetch(routes.update, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                path: activePath,
                content: editor.value,
            }),
        });

        if (!response.ok) {
            setStatus('Save failed.', true);
            saveButton.disabled = false;
            return;
        }

        const data = await response.json();
        currentMeta.textContent = `Last modified: ${data.updated_at}`;
        setDirty(false);
        setStatus('Saved');
    }

    fileButtons.forEach((button) => {
        button.addEventListener('click', () => loadFile(button.dataset.path));
    });

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.trim().toLowerCase();
        fileButtons.forEach((button) => {
            button.hidden = query !== '' && !button.dataset.path.toLowerCase().includes(query);
        });
    });

    editor.addEventListener('input', () => setDirty(true));
    saveButton.addEventListener('click', saveFile);

    document.addEventListener('keydown', (event) => {
        if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 's') {
            event.preventDefault();
            saveFile();
        }
    });

    window.addEventListener('beforeunload', (event) => {
        if (!dirty) {
            return;
        }

        event.preventDefault();
        event.returnValue = '';
    });
</script>
</body>
</html>
