<?php
/**
 * @copyright Ilch 2.0
 */

namespace Ilch\Validation\Validators;

use Ilch\Registry;

/**
 * Email validation class.
 */
class Unique extends Base
{
    /**
     * Default error key for this validator.
     *
     * @var string
     */
    protected $errorKey = 'validation.errors.unique.valueExists';

    /**
     * Minimum parameter count needed.
     *
     * @var int
     */
    protected $minParams = 1;

    /**
     * Runs the validation.
     *
     * @return self
     */
    public function run()
    {
        $db = Registry::get('db');

        $table = $this->getParameter(0);
        $column = is_null($this->getParameter(1)) ? $this->getField() : $this->getParameter(1);

        $ignoreId = $this->getParameter(2);
        $ignoreIdColumn = is_null($this->getParameter(3)) ? 'id' : $this->getParameter(3);

        $whereLeft = 'LOWER(`'.$column.'`)';
        $whereMiddle = '=';
        $whereRight = $db->escape(strtolower($this->getValue()), true);

        $where = new \Ilch\Database\Mysql\Expression\Comparison($whereLeft, $whereMiddle, $whereRight);

        $result = $db->select()
            ->from($table)
            ->where([$where]);

        if (!is_null($ignoreId)) {
            $result = $result->andWhere([$ignoreIdColumn.' !=' => $ignoreId]);
        }

        $result = $result->execute();

        $this->setIsValid($result->getNumRows() === 0);
        $this->setErrorParameters([$this->getValue()]);

        return $this;
    }
}
