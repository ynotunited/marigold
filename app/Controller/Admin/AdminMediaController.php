<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Model;
use App\Core\View;

class AdminMediaController extends Controller
{
    public function index()
    {
        $db = Model::getDB();

        $fileRows = $db->query("
            SELECT id, filename, path, type, size, alt_text, folder, created_at
            FROM media
            ORDER BY created_at DESC
        ")->fetchAll();

        $files = [];
        $storageBytes = 0;
        foreach ($fileRows as $f) {
            $storageBytes += (int)$f['size'];
            $files[] = [
                'id'         => $f['id'],
                'name'       => $f['filename'],
                'folder'     => ucfirst($f['folder'] ?: 'general'),
                'size'       => $this->formatBytes((int)$f['size']),
                'dimensions' => '—',
                'type'       => $f['type'] ?: 'image/png',
                'date'       => date('M j, Y', strtotime($f['created_at'])),
                'thumbnail'  => $f['path'] ? app_url($f['path']) : app_url('/ms-logo-icon.png'),
                'unused'     => false,
            ];
        }

        // Folder summary
        $folderRows = $db->query("
            SELECT folder, COUNT(*) AS cnt, COALESCE(SUM(size), 0) AS bytes
            FROM media
            GROUP BY folder
        ")->fetchAll();
        $folders = [];
        foreach ($folderRows as $g) {
            $folders[] = [
                'name'  => ucfirst($g['folder'] ?: 'general'),
                'files' => (int)$g['cnt'],
                'size'  => $this->formatBytes((int)$g['bytes']),
            ];
        }

        $storageTotal = 10 * 1024 * 1024 * 1024; // 10 GB plan
        $storagePercent = $storageTotal > 0 ? round(($storageBytes / $storageTotal) * 100, 1) : 0;

        return View::renderTemplate('pages/admin/media/index', 'admin', [
            'title' => 'Media Library | Admin',
            'folders' => $folders,
            'files' => $files,
            'storage_used' => $this->formatBytes($storageBytes),
            'storage_total' => $this->formatBytes($storageTotal),
            'storage_percent' => $storagePercent,
        ]);
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) return round($bytes / 1073741824, 1) . ' GB';
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
