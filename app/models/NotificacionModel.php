<?php

namespace app\models;

use app\config\DataBase;
use PDO;

class NotificacionModel {
    private $db;

    public function __construct() {
        $this->db = DataBase::getInstance();
    }

    // Crear notificación
    public function crear($mensaje, $tipo, $id_destino, $id_reporte_asociado = null, $fecha_expiracion = null) {
        $sql = "INSERT INTO notificaciones (mensaje, tipo, id_destino, id_reporte_asociado, fecha_expiracion) VALUES (:mensaje, :tipo, :id_destino, :id_reporte_asociado, :fecha_expiracion)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':mensaje', $mensaje, PDO::PARAM_STR);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':id_destino', $id_destino, PDO::PARAM_INT);
        $stmt->bindParam(':id_reporte_asociado', $id_reporte_asociado, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_expiracion', $fecha_expiracion, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Obtener notificaciones por usuario o rol
    public function obtenerPorDestino($tipo, $id_destino) {
        $sql = "SELECT * FROM notificaciones WHERE tipo = :tipo AND id_destino = :id_destino ORDER BY fecha_creacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':id_destino', $id_destino, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Marcar notificación como leída
    public function marcarLeida($id) {
        $sql = "UPDATE notificaciones SET leida = 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Obtener notificaciones priorizando no leídas (o forzadas como no leídas por estado pendiente), máximo 10
    public function obtenerPorDestinoConEstado($tipo, $id_destino) {
        $sql = "SELECT n.*, fr.id_estado_reporte_fallas
            , fr.codigo_reporte_fallas
            FROM notificaciones n
            LEFT JOIN reporte_fallas fr ON n.id_reporte_asociado = fr.id_reporte_fallas
            WHERE n.tipo = :tipo AND n.id_destino = :id_destino
            ORDER BY n.fecha_creacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':id_destino', $id_destino, PDO::PARAM_INT);
        $stmt->execute();
        $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($notificaciones as &$n) {
            if (
                isset($n['id_reporte_asociado']) && $n['id_reporte_asociado'] &&
                isset($n['id_estado_reporte_fallas'])
            ) {
                // Si está Pendiente (1) o Rechazado (1), forzar como no leída
                if ((int)$n['id_estado_reporte_fallas'] === 1) {
                    $n['leida'] = 0;
                } else if ((int)$n['id_estado_reporte_fallas'] === 2 || (int)$n['id_estado_reporte_fallas'] === 3) {
                    $n['leida'] = 1;
                }
            }
        }
        usort($notificaciones, function($a, $b) {
            if ($a['leida'] == $b['leida']) {
                return strtotime($b['fecha_creacion']) <=> strtotime($a['fecha_creacion']);
            }
            return $a['leida'] <=> $b['leida'];
        });
        return array_slice($notificaciones, 0, 10);
    }

    // Obtener todas las notificaciones por usuario o rol (sin límite)
    public function obtenerTodasPorDestino($tipo, $id_destino) {
        $sql = "SELECT n.*, fr.id_estado_reporte_fallas, fr.codigo_reporte_fallas
            FROM notificaciones n
            LEFT JOIN reporte_fallas fr ON n.id_reporte_asociado = fr.id_reporte_fallas
            WHERE n.tipo = :tipo AND n.id_destino = :id_destino
            ORDER BY n.fecha_creacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':id_destino', $id_destino, PDO::PARAM_INT);
        $stmt->execute();
        $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($notificaciones as &$n) {
            if (
                isset($n['id_reporte_asociado']) && $n['id_reporte_asociado'] &&
                isset($n['id_estado_reporte_fallas'])
            ) {
                if ((int)$n['id_estado_reporte_fallas'] === 1) {
                    $n['leida'] = 0;
                } else if ((int)$n['id_estado_reporte_fallas'] === 2 || (int)$n['id_estado_reporte_fallas'] === 3) {
                    $n['leida'] = 1;
                }
            }
        }
        usort($notificaciones, function($a, $b) {
            if ($a['leida'] == $b['leida']) {
                return strtotime($b['fecha_creacion']) <=> strtotime($a['fecha_creacion']);
            }
            return $a['leida'] <=> $b['leida'];
        });
        return $notificaciones;
    }
}
