<?php namespace Pisa\GizmoAPI\Models;

use Pisa\GizmoAPI\Exceptions\InternalException;
use Pisa\GizmoAPI\Exceptions\RequirementException;

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
        if (!$this->exists()) {
            throw new RequirementException("News doesn't even exist");
        }
        $this->logger->notice("[News $this] Deleting news");

        $response = $this->client->delete('News/Delete', [
            'feedId' => $this->getPrimaryKeyValue(),
        ]);
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertEmpty();
        $response->assertStatusCodes(204);

        unset($this->Id);
        return $this;
    }

    /**
     * @throws Exception on error
     */
    protected function create()
    {
        if ($this->exists()) {
            throw new RequirementException("News already exists. Did you mean update?");
        }

        $this->logger->notice("[News $this] Creating news");
        $response = $this->client->put('News/Add', $this->getAttributes());
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertEmpty();
        $response->assertStatusCodes(204);

        return $this;
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
            throw new InvalidArgumentException("Unable to parse attribute EndDate");
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
            throw new InvalidArgumentException("Unable to parse attribute StartDate");
        }

        $this->attributes['StartDate'] = $value;
    }

    /**
     * @throws Exception on error
     */
    protected function update()
    {
        if (!$this->exists()) {
            throw new RequirementException("News does not exist. Did you mean create?");
        }

        $this->logger->notice("[News $this] Updating news");
        $response = $this->client->post('News/Update', $this->getAttributes());
        if ($response === null) {
            throw new InternalException("Response failed");
        }

        $response->assertEmpty();
        $response->assertStatusCodes(204);

        return $this;
    }
}
