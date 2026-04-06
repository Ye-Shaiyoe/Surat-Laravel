<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index()
    {
        /** @var FilesystemAdapter $publicDisk */
        $publicDisk = Storage::disk('public');
        $templates = collect($publicDisk->files('templates'))
            ->map(fn(string $path) => [
                'nama' => basename($path),
                'url'  => $publicDisk->url($path),
            ])
            ->values();

        return view('user.template.index', [
            'title'     => 'Template Surat',
            'templates' => $templates,
        ]);
    }
}
