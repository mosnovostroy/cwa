<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
//use Yii;

$this->title = 'О проекте';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
      <div class="col-md-7">
          <p>Коворкинг-ревю - проект о совместной работе в широком смысле этого слова. Традиционная модель организации бизнеса (собственный или съемный офис + работа штатных сотрудников в этом офисе) в последнее время все чаще перестает рассматриваться как единственно возможная. И компании, и специалисты-профессионалы начинают стремиться к большей гибкости. Наш проект создан как площадка для анализа соответствующих возможностей.</p>

          <p>Во-первых, мы собираем информацию о существующих коворкинг-центрах или коворкингах. Коворкинг - место, где можно взять в аренду рабочее место. Срок аренды - от нескольких минут до нескольких месяцев. Коворкингами интересуются как профессионалы-"одиночки" (например, работающие в режиме фриланс), так и компании (например, запускающие новый проект и не готовые на старте вкладываться в долгосрочную аренду офиса). Коворкинг-ревю - площадка для сравнения коворкингов и выбора наиболее подходящего. Приветствуются отзывы посетителей коворкингов.</p>

          <p>Во-вторых, Коворкинг-ревю является площадкой для подбора партнеров для совместной аренды офиса. Часто бывает так, что есть хорошее помещение, но на данном этапе развития компании оно "великовато". Терять его жалко, арендовать - расточительно. Отличным решением в таком случае становится совместная аренда - нужно лишь найти партнера, с которым было бы комфортно делить пространство.</p>

          <p>Ссылки на основные разделы сайта:</p>
          <p><?= Html::a('Подбор коворкинга', ['center/index'], ['class' => 'btn btn-link']) ?></p>
          <p><?= Html::a('Объявления о совместной аренде', ['arenda/index'], ['class' => 'btn btn-link']) ?></p>
          </p></p>
      </div>
      <div class="col-md-5">
      </div>
  </div>
</div>
