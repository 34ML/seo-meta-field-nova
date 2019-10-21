<?php

namespace Gwd\SeoMeta\Traits;

use Gwd\SeoMeta\Models\SeoMetaItem;
use Illuminate\Support\Facades\Storage;

trait SeoMetaTrait
{
    /**
     * Get the seo_metaable relationship.
     *
     * @return morphOne
     */
    public function seo_meta()
    {
        return $this->morphOne(SeoMetaItem::class, 'seo_metaable');
    }

    /**
     * Return the seo_metaable data as array
     *
     * @return array
     */
    public function getSeoMeta()
    {
        $attrs = false;

        if ($this->seo_meta) {
            $attrs = $this->seo_meta->toArray();
        } else {
            $title = $this->getSeoTitleDefault();

            if ($title) {
                $attrs = [
                    'title' => $title,
                    'description' => $this->getSeoDescriptionDefault(),
                    'keywords' => $this->getSeoKeywordsDefault(),
                    'image' => $this->getSeoImageDefault(),
                    'follow_type' => $this->getSeoFollowDefault()
                ];
            }
        }

        if($attrs && isset($attrs['image']) && $attrs['image']){
            $attrs['image_path'] = Storage::url($attrs['image']);
        }

        return $attrs;
    }

    /**
     * Get default SEO title
     */
    public function getSeoTitleDefault()
    {
        return null;
    }

    /**
     * Get default SEO description
     */
    public function getSeoDescriptionDefault()
    {
        return null;
    }

    /**
     * Get default SEO title
     */
    public function getSeoKeywordsDefault()
    {
        return null;
    }

    /**
     * Get default SEO title
     */
    public function getSeoImageDefault()
    {
        return null;
    }

    /**
     * Get default SEO title
     */
    public function getSeoFollowDefault()
    {
        return 'index, follow';
    }
}
