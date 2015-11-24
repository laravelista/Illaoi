<?php

namespace Laravelista\Illaoi;

use Illuminate\Database\Eloquent\Model;

class Illaoi {

    /**
     * Turns "Čista velika" to "cista-velika".
     *
     * @param  [type] $text
     * @return [type]
     */
    public function generate($text)
    {
        // Replace croatian characters with normal
        $patterns     = ['/č/', '/ć/', '/š/', '/đ/', '/ž/', '/Č/', '/Ć/', '/Š/', '/Đ/', '/Ž/'];
        $replacements = ['c', 'c', 's', 'd', 'z', 'c', 'c', 's', 'd', 'z'];
        $slug = preg_replace($patterns, $replacements, $text);

        // Replace everything except letters, numbers, white space or a dash with empty space
        $slug = trim(preg_replace("/[^a-zA-Z0-9\s-]/", "", $slug));

        // Replace whitespace and a dash with white space and trim the result
        $slug = trim(preg_replace("/[\s-]+/", " ", $slug));

        // Replace whitespace with a dash
        $slug = preg_replace("/\s/", "-", $slug);

        // Convert text to lowercase
        $slug = strtolower($slug);

        return $slug;
    }

    /**
     * Turns 'Old Dota' to 'old-dota' or 'old-dota-2.
     * Generates unique slug by verifying in database.
     *
     * @param  [type] $text
     * @param  Model $model
     * @param  string $field
     * @param  integer $idToIgnore
     * @return [type]
     */
    public function generateUnique($text, Model $model, $field = 'slug', $idToIgnore = 0)
    {
        $iteration = 2;

        // old-dota
        $slug = $this->generate($text);

        while($model->where($field, '=', $slug)->where('id', '!=', $idToIgnore)->exists())
        {
            // old-dota-2
            $slug = $this->generate($slug . '-' . $iteration++);
        }

        return $slug;
    }

}