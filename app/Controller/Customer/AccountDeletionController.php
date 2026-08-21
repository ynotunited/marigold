<?php

namespace App\Controller\Customer;

use App\Core\Controller;
use App\Core\Session;
use App\Core\CSRF;
use App\Service\DeletionService;
use App\Service\GdprExportService;

/**
 * Customer-facing account deletion and GDPR data export.
 */
class AccountDeletionController extends Controller
{
    /**
     * Show the account deletion page with confirmation form.
     */
    public function index()
    {
        $userId = Session::get('user_id');
        $isPending = DeletionService::isPendingDeletion($userId);

        $daysRemaining = null;
        if ($isPending) {
            $db = \App\Core\Model::getDB();
            $stmt = $db->prepare("SELECT deleted_at FROM users WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $userId]);
            $row = $stmt->fetch();
            if ($row && $row['deleted_at']) {
                $deletedAt = strtotime($row['deleted_at']);
                $expiresAt = $deletedAt + (DeletionService::retentionDays() * 86400);
                $daysRemaining = max(0, (int)ceil(($expiresAt - time()) / 86400));
            }
        }

        return \App\Core\View::renderTemplate('pages/customer/delete_account', 'customer', [
            'title' => 'Delete Account | Marigold Signature',
            'is_pending' => $isPending,
            'days_remaining' => $daysRemaining,
            'retention_days' => DeletionService::retentionDays(),
        ]);
    }

    /**
     * Handle the deletion request (POST).
     */
    public function request()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/account/delete');
        }

        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            throw new \Exception('Invalid CSRF token', 403);
        }

        $userId = Session::get('user_id');

        // Confirm checkbox
        if (empty($_POST['confirm_deletion'])) {
            Session::error('Please confirm that you understand this action is irreversible after the retention period.');
            $this->redirect('/account/delete');
        }

        $result = DeletionService::softDelete($userId);

        if ($result['blocked']) {
            Session::error($result['reason']);
            $this->redirect('/account/delete');
        }

        // Log the user out
        \App\Service\AuthService::logout();

        Session::success('Your account has been scheduled for deletion. You have ' . DeletionService::retentionDays() . ' days to change your mind before data is permanently removed.');
        $this->redirect('/login');
    }

    /**
     * Cancel a pending deletion (POST).
     */
    public function cancel()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/account/delete');
        }

        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            throw new \Exception('Invalid CSRF token', 403);
        }

        $userId = Session::get('user_id');
        DeletionService::cancelDeletion($userId);

        Session::success('Your account deletion has been cancelled. Your account is active again.');
        $this->redirect('/account/settings');
    }

    /**
     * Download GDPR data export as JSON (customer self-serve).
     */
    public function exportData()
    {
        $userId = Session::get('user_id');
        $data = GdprExportService::export($userId);

        if (!$data) {
            Session::error('No data found for your account.');
            $this->redirect('/account/settings');
        }

        // Log the export
        $db = \App\Core\Model::getDB();
        try {
            \App\Service\AuditService::act('user.gdpr_export', 'users', $userId);
        } catch (\Throwable $e) {
            // Don't crash on audit failure
        }

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="marigold-data-export-' . $userId . '-' . date('Y-m-d') . '.json"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * View GDPR data export as HTML (customer self-serve).
     */
    public function viewData()
    {
        $userId = Session::get('user_id');
        $html = GdprExportService::exportHtml($userId);

        header('Content-Type: text/html; charset=utf-8');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        echo $html;
        exit;
    }
}
