<?php

namespace frontend\models;

use yii\base\Model;

class TasksFilter extends Model
{

    public $categories;
    public $noExecutor;
    public $remoteWork;
    public $period;
    public $search;

    public function attributeLabels(): array
    {
        return [
            'categories' => 'Категории',
            'noExecutor' => 'Без исполнителя',
            'remoteWork' => 'Удаленная работа',
            'period'     => 'Период',
            'search'     => 'Поиск',
        ];
    }

    public function rules(): array
    {
        return [
            [['categories', 'noExecutor', 'remoteWork', 'period', 'search'], 'safe'],
        ];
    }
}
