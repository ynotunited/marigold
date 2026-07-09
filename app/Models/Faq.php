<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Faq extends Model
{
    public static function getAllActive()
    {
        $db = static::getDB();
        $stmt = $db->query("
            SELECT * FROM faqs 
            WHERE status = 'active'
            ORDER BY sort_order ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
