<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Post extends Model
{
    public static function getAllPublished()
    {
        $db = static::getDB();
        $stmt = $db->query("
            SELECT p.*, u.first_name, u.last_name 
            FROM posts p
            LEFT JOIN users u ON p.author_id = u.id
            WHERE p.status = 'published'
            ORDER BY p.published_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findBySlug($slug)
    {
        $db = static::getDB();
        $stmt = $db->prepare("
            SELECT p.*, u.first_name, u.last_name 
            FROM posts p
            LEFT JOIN users u ON p.author_id = u.id
            WHERE p.slug = :slug AND p.status = 'published'
            LIMIT 1
        ");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
