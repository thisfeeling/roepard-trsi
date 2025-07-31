<?php
// Requiere el modelo
require_once __DIR__ . '/../models/Diagnostic.php';

// Clase DiagnosticService
class DiagnosticService {
    private $diagnosticModel;

    // Crea una nueva instancia
    public function __construct() {
        $this->diagnosticModel = new Diagnostic();
    }

    // Obtiene el estado de la base de datos y los permisos
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
?>