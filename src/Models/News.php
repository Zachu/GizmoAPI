<?php namespace Pisa\GizmoAPI\Models;

use Exception;

class News extends BaseModel implements NewsInterface
{
    protected $fillable = [
        'Title',
        'Data',
        'StartDate',
        'EndDate',
        'Url',
    ];

    protected $guraded = [
        'Id',
        'Date',
    ];

    /**
     * @throws Exception on error
     * @api
     */
    public function delete()
    {
        try {
            if (!$this->exists()) {
                throw new Exception("News doesn't even exist");
            }

            $response = $this->client->delete('News/Delete', [
                'feedId' => $this->getPrimaryKeyValue(),
            ]);
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertEmpty();
            $response->assertStatusCodes(204);

            unset($this->Id);
            return $this;
        } catch (Exception $e) {
            throw new Exception("Unable to delete news: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception on error
     */
    protected function create()
    {
        try {
            if ($this->exists()) {
                throw new Exception("News already exists. Did you mean update?");
            }

            $response = $this->client->put('News/Add', $this->getAttributes());
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertEmpty();
            $response->assertStatusCodes(204);

            return $this;
        } catch (Exception $e) {
            throw new Exception("Unable to create news: " . $e->getMessage());
        }
    }

    /**
     * Sets EndDate attributes to ISO-8601 format
     * @param int|string $date Unix timestamp or strtotime parseable string
     * @return void
     * @internal
     */
    protected function setEndDateAttribute($date)
    {
        if (is_int($date) && $date >= 0) {
            $value = date('c', $date);
        } elseif (is_string($date) && strtotime($date) !== false) {
            $value = date('c', strtotime($date));
        } else {
            throw new Exception("Unable to parse attribute EndDate");
        }

        $this->attributes['EndDate'] = $value;
    }

    /**
     * Sets StartDate attributes to ISO-8601 format
     * @param int|string $date Unix timestamp or strtotime parseable string
     * @return void
     * @internal
     */
    protected function setStartDateAttribute($date)
    {
        if (is_int($date) && $date >= 0) {
            $value = date('c', $date);
        } elseif (is_string($date) && strtotime($date) !== false) {
            $value = date('c', strtotime($date));
        } else {
            throw new Exception("Unable to parse attribute StartDate");
        }

        $this->attributes['StartDate'] = $value;
    }

    /**
     * @throws Exception on error
     */
    protected function update()
    {
        try {
            if (!$this->exists()) {
                throw new Exception("News does not exist. Did you mean create?");
            }

            $response = $this->client->post('News/Update', $this->getAttributes());
            if ($response === null) {
                throw new Exception("Response failed");
            }

            $response->assertEmpty();
            $response->assertStatusCodes(204);

            return $this;
        } catch (Exception $e) {
            throw new Exception("Unable to update news: " . $e->getMessage());
        }
    }
}
