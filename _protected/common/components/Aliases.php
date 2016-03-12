<?php
namespace common\components;

use yii\base\Component;
use Yii;

/**
 * Component where you can define your aliases.
 * 
 * This component is bootstrap-ed in your web.php configuration file.
 * It is good to make aliases here so we can use predefined aliases 
 * and other settings made by application configuration.
 *
 * @author Nenad Zivkovic <nenad@freetuts.org>
 * @since 2.3.0 <improved template version>
 */
class Aliases extends Component
{
    public function init() 
    {
        Yii::setAlias('@themes', Yii::$app->view->theme->baseUrl);
        Yii::setAlias('@uploads', Yii::getAlias('@webroot').'/uploads/');
        Yii::setAlias('@tests', Yii::getAlias('@webroot').'/_protected/tests/');
    }
}