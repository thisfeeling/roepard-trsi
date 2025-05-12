<?php
require_once __DIR__ . '/../models/Diagnostic.php';

class DiagnosticService {
    private $diagnosticModel;

    public function __construct() {
        $this->diagnosticModel = new Diagnostic();
    }

    public function getDiagnostic() {
        $status = $this->diagnosticModel->checkConnection();
        $perms = $this->diagnosticModel->checkPermissions();
        return [
            'db_status' => $status,
            'perm_select' => $perms['select'],
            'perm_insert' => $perms['insert'],
            'perm_update' => $perms['update'],
            'perm_delete' => $perms['delete']
        ];
    }
}