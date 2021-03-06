<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Checkoutbasic\Mappers;

use Modules\Checkoutbasic\Models\Entry as CheckoutModel;

class Checkout extends \Ilch\Mapper
{
    /**
     * Gets the Checkout entries.
     *
     * @param array $where
     * @return CheckoutModel[]|array
     */
    public function getEntries($where = [])
    {
        $entryArray = $this->db()->select('*')
            ->from('checkoutbasic')
            ->where($where)
            ->order(['id' => 'DESC'])
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return [];
        }

        $entry = [];

        foreach ($entryArray as $entries) {
            $entryModel = new CheckoutModel();
            $entryModel->setId($entries['id']);
            $entryModel->setDatetime($entries['date_created']);
            $entryModel->setName($entries['name']);
            $entryModel->setUsage($entries['usage']);
            $entryModel->setAmount($entries['amount']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    public function getEntryById($id)
    {
        $entry = $this->getEntries(['id' => $id]);
        return $entry;
    }

    public function getAmount()
    {
        return $this->db()->select('ROUND(SUM(amount),2)', 'checkoutbasic')
            ->execute()
            ->fetchCell();
    }

    public function getAmountPlus()
    {
        return $this->db()->select('ROUND(SUM(amount),2)', 'checkoutbasic', ['amount >' => 0])
            ->execute()
            ->fetchCell();
    }

    public function getAmountMinus()
    {
        return $this->db()->select('ROUND(SUM(amount),2)', 'checkoutbasic', ['amount <' => 0])
            ->execute()
            ->fetchCell();
    }

    /**
     * Inserts or updates Checkout entry.
     *
     * @param CheckoutModel $model
     */
    public function save(CheckoutModel $model)
    {
        if ($model->getId()) {
            $this->db()->update('checkoutbasic')
                ->values(['name' => $model->getName(),'date_created' => $model->getDatetime(),'usage' => $model->getUsage(),'amount' => $model->getAmount()])
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('checkoutbasic')
                ->values(['name' => $model->getName(),'date_created' => $model->getDatetime(),'usage' => $model->getUsage(),'amount' => $model->getAmount()])
                ->execute();
        }
    }

    /**
     * Deletes the Checkout entry.
     *
     * @param integer $id
     */
    public function deleteById($id)
    {
        return $this->db()->delete('checkoutbasic')
            ->where(['id' => $id])
            ->execute();
    }
}
