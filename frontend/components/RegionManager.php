<?php
namespace frontend\components;

use Yii;
use yii\helpers\Url;
use yii\base\Component;
//use common\models\Region;

class RegionManager extends Component
{
    private $_regionData;

    public function getId()
    {
        if (!$this->_regionData) {
            $this->loadFromCockie();
        }

        return isset($this->_regionData['id']) ? $this->_regionData['id'] : '';
    }

    public function getName()
    {
        if (!$this->_regionData) {
            $this->loadFromCockie();
        }

        return isset($this->_regionData['name']) ? $this->_regionData['name'] : '';
    }

    public function getNameTp()
    {
        if (!$this->_regionData) {
            $this->loadFromCockie();
        }

        return isset($this->_regionData['name_tp']) ? $this->_regionData['name_tp'] : '';
    }

    public function getAlias()
    {
        if (!$this->_regionData) {
            $this->loadFromCockie();
        }

        return isset($this->_regionData['alias']) ? $this->_regionData['alias'] : '';
    }


    public function loadFromCockie()
    {
        $regionId = Yii::$app->getRequest()->getCookies()->getValue('cwrRegion1');

        if ($regionId) {
            $this->_regionData = Yii::$app->db->createCommand('SELECT id, name, alias FROM region
                                                        WHERE id=:rid' , [':rid' => $regionId])
                                        ->queryOne();

        } else {
            $this->_regionData = [];
        }

    }

    public function setRegion($id)
    {
        if (!$id) return;

        //if (!isset(Yii::$app->request->cookies['cwrRegionId']))
        {
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'cwrRegion1',
                'value' => $id,
                'expire' => time() + 86400 * 365,
            ]));
        }

        $this->_regionData = Yii::$app->db->createCommand('SELECT * FROM region
                                                        WHERE id=:rid' , [':rid' => $id])
                                        ->queryOne();

    }


}

?>
