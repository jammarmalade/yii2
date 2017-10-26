<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    //自增
    public function increase($id,$field,$step=1){
        return Yii::$app->db->createCommand('UPDATE '.$this->tableName()." SET `$field` = `$field` + $step WHERE id = :id")->bindValue(':id',$id)->execute();
    }
    //自减
    public function decrease($id,$field,$step=1){
        return Yii::$app->db->createCommand('UPDATE '.$this->tableName()." SET `$field` = `$field` - $step WHERE id = :id")->bindValue(':id',$id)->execute();
    }
}
