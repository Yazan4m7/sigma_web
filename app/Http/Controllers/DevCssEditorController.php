<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DevCssEditorController extends Controller
{
    private const EDITABLE_EXTENSIONS = ['css', 'scss', 'sass', 'less'];

    public function index()
    {
        $this->authorizeLocalDev();

        return view('dev.css-editor', [
            'files' => $this->editableFiles(),
        ]);
    }

    public function show(Request $request)
    {
        $this->authorizeLocalDev();

        $path = $this->resolveEditablePath($request->query('path'));

        return response()->json([
            'path' => $this->relativePath($path),
            'content' => File::get($path),
            'updated_at' => date('Y-m-d H:i:s', File::lastModified($path)),
        ]);
    }

    public function update(Request $request)
    {
        $this->authorizeLocalDev();

        $data = $request->validate([
            'path' => ['required', 'string'],
            'content' => ['present', 'string'],
        ]);

        $path = $this->resolveEditablePath($data['path']);
        File::put($path, $data['content']);

        return response()->json([
            'ok' => true,
            'path' => $this->relativePath($path),
            'updated_at' => date('Y-m-d H:i:s', File::lastModified($path)),
        ]);
    }

    private function authorizeLocalDev(): void
    {
        abort_unless(
            config('app.debug') && in_array(app()->environment(), ['local', 'development', 'debug'], true),
            403,
            'CSS editor is available only when APP_DEBUG is true in a local/dev environment.'
        );
    }

    private function editableFiles(): array
    {
        $files = [];

        foreach ($this->editableRoots() as $root) {
            if (! File::isDirectory($root)) {
                continue;
            }

            foreach (File::allFiles($root) as $file) {
                if (! in_array(strtolower($file->getExtension()), self::EDITABLE_EXTENSIONS, true)) {
                    continue;
                }

                $files[] = [
                    'path' => $this->relativePath($file->getPathname()),
                    'name' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'updated_at' => date('Y-m-d H:i:s', $file->getMTime()),
                ];
            }
        }

        usort($files, fn ($a, $b) => strcmp($a['path'], $b['path']));

        return $files;
    }

    private function resolveEditablePath(?string $relativePath): string
    {
        abort_if($relativePath === null || trim($relativePath) === '', 404);

        $normalized = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, ltrim($relativePath, '\\/'));
        $path = realpath(base_path($normalized));

        abort_unless($path && File::isFile($path), 404);
        abort_unless(in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), self::EDITABLE_EXTENSIONS, true), 403);
        abort_unless($this->isInsideEditableRoot($path), 403);

        return $path;
    }

    private function isInsideEditableRoot(string $path): bool
    {
        foreach (array_filter(array_map('realpath', $this->editableRoots())) as $root) {
            if (str_starts_with($path, $root . DIRECTORY_SEPARATOR) || $path === $root) {
                return true;
            }
        }

        return false;
    }

    private function editableRoots(): array
    {
        return [
            public_path('assets/css'),
            public_path('assets/icons'),
            public_path('assets/plugins'),
            public_path('css'),
            public_path('custom-CSS-JS'),
            public_path('vendor'),
            public_path('white/css'),
            public_path('white/demo'),
            resource_path('sass'),
        ];
    }

    private function relativePath(string $path): string
    {
        return str_replace('\\', '/', ltrim(str_replace(base_path(), '', $path), '\\/'));
    }
}
