<?php

namespace Laravelista\Illaoi;

use Illuminate\Database\Eloquent\Model;
use Laravelista\Illaoi\InvalidIdException;
use Laravelista\Illaoi\InvalidIterationException;

class Illaoi {

    /**
     * old-dota turns to old-dota-2 if old-dota is
     * found already in database.
     *
     * @var integer
     */
    protected $iteration = 2;

    /**
     * Ignore model with specific id when generating
     * unique slug.
     *
     * @var integer
     */
    protected $idToIgnore = 0;

    /**
     * By default search by this field in db.
     *
     * @var string
     */
    protected $field = 'slug';


    /**
     * Sets the value of iteration. Default is `2`.
     *
     * Modify iteration before using generateUnique method to modify
     * the starting number when a record exists in the db with the
     * same slug.
     *
     * eg. `$this->iteration = 3`
     * In DB exists: `this-post`
     * Input: `This post`
     * Result: `this-post-3`
     *
     * @return mixed
     */
    public function setIteration($iteration)
    {
        if(!is_int($iteration)) throw new InvalidIterationException("Iteration must be an integer.");

        $this->iteration = $iteration;

        return $this;
    }


    /**
     * Use this to set a model id to ignore prior to
     * using `generateUnique` method. Default is `0`.
     *
     * @param [type] $id
     */
    public function ignoreId($id)
    {
        if(!is_int($id) || $id < 1) throw new InvalidIdException("Id must be an integer and more than zero.");

        $this->idToIgnore = $id;

        return $this;
    }


    /**
     * Sets field by which to search in db
     * for duplicates. Default is `slug`.
     *
     * @param string $field
     */
    public function searchBy($field)
    {
        $this->field = $field;

        return $this;
    }


    /**
     * Turns "Čista velika" to "cista-velika".
     *
     * @param  string $text
     * @return string
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
     * @param  string $text
     * @param  Model $model
     * @return string
     */
    public function generateUnique($text, Model $model)
    {
        // old-dota
        $slug = $backupSlug = $this->generate($text);

        while($this->generateSearchQuery($slug, $model)->exists())
        {
            // old-dota-2, old-dota-3...
            $slug = $backupSlug . '-' . $this->iteration++;
        }

        return $slug;
    }


    /**
     * Generates search query for database.
     *
     * @param  string $slug
     * @param  Model $model
     * @return [type]
     */
    protected function generateSearchQuery($slug, Model $model)
    {
        // Default search query by field `slug`
        $query = $model->where($this->field, '=', $slug);

        // If idToIgnore is set.
        if($this->idToIgnore !== 0)
            $query = $query->where('id', '!=', $this->idToIgnore);

        return $query;
    }

}