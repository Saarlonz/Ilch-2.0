<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Mappers;

use Modules\Events\Models\Events as EventModel;
use Modules\Events\Mappers\Events as EventMapper;

class Events extends \Ilch\Mapper
{
    /**
     * Gets the Event entries.
     *
     * @param array $where
     *
     * @return EventModel[]|array
     */
    public function getEntries($where = [])
    {
        $entryArray = $this->db()->select('*')
            ->from('events')
            ->where($where)
            ->order(['start' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $entryModel = new EventModel();
            $entryModel->setId($entries['id'])
                ->setUserId($entries['user_id'])
                ->setStart($entries['start'])
                ->setEnd($entries['end'])
                ->setTitle($entries['title'])
                ->setPlace($entries['place'])
                ->setLatLong($entries['lat_long'])
                ->setImage($entries['image'])
                ->setText($entries['text'])
                ->setCurrency($entries['currency'])
                ->setPrice($entries['price'])
                ->setPriceArt($entries['price_art'])
                ->setShow($entries['show']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Gets event.
     *
     * @param int $id
     *
     * @return EventModel|null
     */
    public function getEventById($id)
    {
        $eventRow = $this->db()->select('*')
            ->from('events')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($eventRow)) {
            return null;
        }

        $eventModel = new EventModel();
        $eventModel->setId($eventRow['id'])
            ->setUserId($eventRow['user_id'])
            ->setStart($eventRow['start'])
            ->setEnd($eventRow['end'])
            ->setTitle($eventRow['title'])
            ->setPlace($eventRow['place'])
            ->setLatLong($eventRow['lat_long'])
            ->setImage($eventRow['image'])
            ->setText($eventRow['text'])
            ->setCurrency($eventRow['currency'])
            ->setPrice($eventRow['price'])
            ->setPriceArt($eventRow['price_art'])
            ->setShow($eventRow['show']);

        return $eventModel;
    }

    /**
     * @return EventMapper[]|array
     */
    public function getEventListUpcoming($limit = null)
    {
        $eventMapper = new EventMapper();

        $sql = 'SELECT *
                FROM `[prefix]_events`
                WHERE DAY(start) >= DAY(CURDATE()) AND MONTH(start) = MONTH(CURDATE()) OR MONTH(start) = MONTH(CURDATE()+INTERVAL 1 MONTH)
                ORDER BY start ASC';

        if ($limit !== null) { $sql .= ' LIMIT '.$limit; }

        $rows = $this->db()->queryArray($sql);

        if (empty($rows)) {
            return null;
        }

        $events = [];
        foreach ($rows as $row) {
            $events[] = $eventMapper->getEventById($row['id']);
        }

        return $events;
    }

    /**
     * @return EventMapper[]|array
     */
    public function getEventListUpcomingALL()
    {
        $eventMapper = new EventMapper();

        $sql = 'SELECT *
                FROM `[prefix]_events`
                WHERE start >= CURDATE()
                ORDER BY start ASC';

        $rows = $this->db()->queryArray($sql);

        if (empty($rows)) {
            return null;
        }

        $events = [];
        foreach ($rows as $row) {
            $events[] = $eventMapper->getEventById($row['id']);
        }

        return $events;
    }

    /**
     * @return EventMapper[]|array
     */
    public function getEventListParticipation($userId)
    {
        $eventMapper = new EventMapper();

        $entryRow = $this->db()->select('*')
            ->from('events')
            ->where(['user_id' => $userId])
            ->execute()
            ->fetchRows();

        if (empty($entryRow)) {
            return null;
        }

        $events = [];
        foreach ($entryRow as $row) {
            $events[] = $eventMapper->getEventById($row['id']);
        }

        return $events;
    }

    /**
     * @return EventMapper[]|array
     */
    public function getEventListOther($limit = null)
    {
        $eventMapper = new EventMapper();

        $sql = 'SELECT *
                FROM `[prefix]_events`
                WHERE DAY(start) > DAY(CURDATE()) AND MONTH(start) > MONTH(CURDATE()) AND MONTH(start) = MONTH(CURDATE()+INTERVAL 2 MONTH)
                ORDER BY start ASC';

        if ($limit !== null) { $sql .= ' LIMIT '.$limit; }

        $rows = $this->db()->queryArray($sql);

        if (empty($rows)) {
            return null;
        }

        $events = [];
        foreach ($rows as $row) {
            $events[] = $eventMapper->getEventById($row['id']);
        }

        return $events;
    }

    /**
     * @return EventMapper[]|array
     */
    public function getEventListPast($limit = null)
    {
        $eventMapper = new EventMapper();

        $sql = 'SELECT *
                FROM `[prefix]_events`
                WHERE start < CURDATE()
                ORDER BY start DESC';

        if ($limit !== null) { $sql .= ' LIMIT '.$limit; }

        $rows = $this->db()->queryArray($sql);

        if (empty($rows)) {
            return null;
        }

        $events = [];
        foreach ($rows as $row) {
            $events[] = $eventMapper->getEventById($row['id']);
        }

        return $events;
    }

    public function existsTable($table)
    {
        $module = $this->db()->ifTableExists('[prefix]_'.$table);

        return $module;
    }

    /**
     * Gets the Events by start and end.
     *
     * @param int $start
     * @param int $end
     *
     * @return EventModel[]|array
     */
    public function getEntriesForJson($start, $end)
    {
        if ($start && $end) {
            $start = new \Ilch\Date($start);
            $end = new \Ilch\Date($end);

            $sql = sprintf("SELECT * FROM `[prefix]_events` WHERE start >= '%s' AND end <= '%s' AND `show` = 1 ORDER BY start ASC;", $start, $end);
        } else {
            return null;
        }

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $entryModel = new EventModel();
            $entryModel->setId($entries['id'])
                ->setStart($entries['start'])
                ->setEnd($entries['end'])
                ->setTitle($entries['title'])
                ->setShow($entries['show']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Get latitude and longitude for Google Maps by address
     *
     * @param string $address
     * @param string $googleMapsKey
     *
     * @return $latlongitude
     */
    public function getLatLongFromAddress($address, $googleMapsKey) 
    {
        $prepAddr = str_replace(' ', '+', $address);
        $geocode = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$prepAddr.'&key='.$googleMapsKey);
        $output = json_decode($geocode);
        $latitude = $output->results[0]->geometry->location->lat;
        $longitude = $output->results[0]->geometry->location->lng;
        $latlongitude = $latitude.','.$longitude;

        return $latlongitude;
    }

    /**
     * Inserts or updates event model.
     *
     * @param EventModel $event
     */
    public function save(EventModel $event)
    {
        $fields = [
            'user_id' => $event->getUserId(),
            'start' => $event->getStart(),
            'end' => $event->getEnd(),
            'title' => $event->getTitle(),
            'place' => $event->getPlace(),
            'lat_long' => $event->getLatLong(),
            'image' => $event->getImage(),
            'text' => $event->getText(),
            'currency' => $event->getCurrency(),
            'price' => $event->getPrice(),
            'price_art' => $event->getPriceArt(),
            'show' => $event->getShow()
        ];

        if ($event->getId()) {
            $this->db()->update('events')
                ->values($fields)
                ->where(['id' => $event->getId()])
                ->execute();
        } else {
            $this->db()->insert('events')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes event with given id.
     *
     * @param int $id
     */
    public function delete($id)
    {
        $imageRow = $this->db()->select('*')
            ->from('events')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (file_exists($imageRow['image'])) {
            unlink($imageRow['image']);
        }

        $this->db()->delete('events')
                ->where(['id' => $id])
                ->execute();

        $this->db()->delete('events_entrants')
                ->where(['event_id' => $id])
                ->execute();

        $this->db()->delete('comments')
                ->where(['key' => 'events/show/event/id/'.$id])
                ->execute();
    }

    /**
     * Delete/Unlink Image by id.
     *
     * @param int $id
     */
    public function delImageById($id) 
    {
        $imageRow = $this->db()->select('*')
            ->from('events')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (file_exists($imageRow['image'])) {
            unlink($imageRow['image']);
        }

        $this->db()->update('events')
            ->values(['image' => ''])
            ->where(['id' => $id])
            ->execute();
    }
}
