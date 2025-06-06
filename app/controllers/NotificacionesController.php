<?php
namespace app\controllers;

use app\models\NotificacionModel;

class NotificacionesController {
    private $notificacionModel;
    public function __construct() {
        $this->notificacionModel = new NotificacionModel();
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? '';
        switch ($action) {
            case 'fetch':
                $this->fetch();
                break;
            case 'fetch_all':
                $this->fetchAll();
                break;
            default:
                http_response_code(404);
                echo json_encode(['error' => 'Acción no válida']);
        }
    }

    private function fetch() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        // Permitir forzar tipo e id_destino por GET
        $tipo = $_GET['tipo'] ?? null;
        $id_destino = $_GET['id_destino'] ?? null;
        if (!$tipo || !$id_destino) {
            $id_usuario = $_SESSION['id_usuario'] ?? null;
            $role = $_SESSION['role'] ?? null;
            if ($id_usuario && $role) {
                if ($role == 1) {
                    $tipo = 'rol';
                    $id_destino = 1;
                } else if ($role == 2) {
                    $tipo = 'rol';
                    $id_destino = 2;
                } else {
                    $tipo = 'individual';
                    $id_destino = $id_usuario;
                }
            }
        }
        if ($tipo && $id_destino) {
            // Usar el nuevo método que considera el estado del reporte
            $notificaciones = $this->notificacionModel->obtenerPorDestinoConEstado($tipo, $id_destino);
            header('Content-Type: application/json');
            echo json_encode($notificaciones);
        } else {
            echo json_encode([]);
        }
    }

    private function fetchAll() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $tipo = $_GET['tipo'] ?? null;
        $id_destino = $_GET['id_destino'] ?? null;
        if (!$tipo || !$id_destino) {
            $id_usuario = $_SESSION['id_usuario'] ?? null;
            $role = $_SESSION['role'] ?? null;
            if ($id_usuario && $role) {
                if ($role == 1) {
                    $tipo = 'rol';
                    $id_destino = 1;
                } else if ($role == 2) {
                    $tipo = 'rol';
                    $id_destino = 2;
                } else {
                    $tipo = 'individual';
                    $id_destino = $id_usuario;
                }
            }
        }
        if ($tipo && $id_destino) {
            $notificaciones = $this->notificacionModel->obtenerTodasPorDestino($tipo, $id_destino);
            header('Content-Type: application/json');
            echo json_encode($notificaciones);
        } else {
            echo json_encode([]);
        }
    }
}
