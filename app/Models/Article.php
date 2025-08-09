<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category_id',
        'author_id',
        'status',
        'published_at',
    ];

    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // Category relationship
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Author relationship
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Comments relationship
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    // Automatically generate slug if empty
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;

        if (! isset($this->attributes['slug']) || empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // Only published articles
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Search by keyword in title/content
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
                ->orWhere('content', 'like', "%{$keyword}%");
        });
    }
}
