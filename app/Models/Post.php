<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $table = 'blog_posts';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'meta_description',
        'meta_keywords',
        'published_at',
        'is_published',
        'views_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    /**
     * Scope para obtener solo posts publicados
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc');
    }

    /**
     * Usar slug como route key
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Incrementar contador de vistas
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Obtener excerpt o resumen del contenido
     */
    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Si no hay excerpt, tomar primeros 200 caracteres del contenido
        return Str::limit(strip_tags($this->content), 200);
    }
}
