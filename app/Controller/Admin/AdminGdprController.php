<?php

namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\CSRF;
use App\Service\DeletionService;
use App\Service\GdprExportService;
use App\Service\AuditService;

/**
 * Admin GDPR panel — view pending deletions, trigger exports, force delete,
 * view compliance receipts.
 */
class AdminGdprController extends Controller
{
    /**
     * Dashboard: pending deletions + recent compliance receipts.
     */
    public function index()
    {
        $db = \App\Core\Model::getDB();

        // Users pending deletion
        $pending = $db->query("
            SELECT id, first_name, last_name, email, deleted_at,
                   DATEDIFF(NOW(), deleted_at) AS days_since_delete,
                   DATE_ADD(deleted_at, INTERVAL " . (int)DeletionService::retentionDays() . " DAY) AS expires_at
            FROM users
            WHERE deleted_at IS NOT NULL AND status = 'pending_deletion'
            ORDER BY deleted_at ASC
        ")->fetchAll();

        $pendingRows = [];
        foreach ($pending as $p) {
            $daysLeft = max(0, DeletionService::retentionDays() - (int)$p['days_since_delete']);
            $pendingRows[] = [
                'id' => $p['id'],
                'name' => trim(($p['first_name'] ?? '') . ' ' . ($p['last_name'] ?? '')) ?: $p['email'],
                'email' => $p['email'],
                'deleted_at' => $p['deleted_at'],
                'expires_at' => $p['expires_at'],
                'days_left' => $daysLeft,
            ];
        }

        // Recent compliance receipts
        $receiptResult = DeletionService::queryReceipts([], 1, 15);

        $receipts = [];
        foreach ($receiptResult['rows'] as $r) {
            $userName = trim(($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? ''));
            $receipts[] = [
                'id' => $r['id'],
                'user_id' => $r['user_id'],
                'email_hash' => substr($r['email_hash'], 0, 12) . '…',
                'action' => $r['action'],
                'tables' => json_decode($r['tables_affected'], true) ?: [],
                'row_counts' => json_decode($r['row_counts'], true) ?: [],
                'anonymized' => json_decode($r['anonymized_tables'], true) ?: [],
                'initiated_by' => $r['initiated_by'],
                'initiator' => $userName ?: $r['initiated_by'],
                'date' => date('M j, Y g:i A', strtotime($r['created_at'])),
            ];
        }

        return \App\Core\View::renderTemplate('pages/admin/gdpr/index', 'admin', [
            'title' => 'GDPR & Data Retention | Admin',
            'pending' => $pendingRows,
            'receipts' => $receipts,
            'retention_days' => DeletionService::retentionDays(),
        ]);
    }

    /**
     * Generate and download a GDPR data export for any user (admin tool).
     */
    public function export($userId)
    {
        $userId = (int)$userId;

        $data = GdprExportService::export($userId);
        if (!$data) {
            Session::error('No data found for this user.');
            $this->redirect('/admin/gdpr');
        }

        $html = GdprExportService::exportHtml($userId);

        AuditService::act('user.gdpr_export_admin', 'users', $userId, [], ['admin_id' => Session::get('user_id')]);

        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        exit;
    }

    /**
     * Download a GDPR data export as JSON file (admin tool).
     */
    public function exportJson($userId)
    {
        $userId = (int)$userId;
        $data = GdprExportService::export($userId);

        if (!$data) {
            Session::error('No data found for this user.');
            $this->redirect('/admin/gdpr');
        }

        AuditService::act('user.gdpr_export_admin', 'users', $userId, [], ['admin_id' => Session::get('user_id')]);

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="gdpr-export-user-' . $userId . '-' . date('Y-m-d') . '.json"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Force hard-delete a user immediately (bypasses retention window).
     */
    public function forceDelete($userId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/gdpr');
        }

        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            throw new \Exception('Invalid CSRF token', 403);
        }

        $userId = (int)$userId;
        $adminId = Session::get('user_id');

        $receipt = DeletionService::hardDelete($userId, 'admin', $adminId);

        if (empty($receipt)) {
            Session::error('User not found or already deleted.');
        } else {
            $totalRows = array_sum($receipt['row_counts'] ?? []);
            Session::success("User #{$userId} permanently deleted. {$totalRows} rows affected across " . count($receipt['tables_affected'] ?? []) . " tables.");
        }

        $this->redirect('/admin/gdpr');
    }

    /**
     * Restore a pending-deletion user.
     */
    public function restore($userId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/gdpr');
        }

        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            throw new \Exception('Invalid CSRF token', 403);
        }

        $userId = (int)$userId;
        DeletionService::cancelDeletion($userId);
        AuditService::act('user.deletion_restored_by_admin', 'users', $userId, [], ['admin_id' => Session::get('user_id')]);

        Session::success("User #{$userId} has been restored. Their account is active again.");
        $this->redirect('/admin/gdpr');
    }
}
