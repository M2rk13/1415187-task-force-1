<?php

namespace frontend\models;

use yii\base\Model;

class UsersFilter extends Model
{

    public $categories;
    public $vacant;
    public $online;
    public $hasFeedback;
    public $inFavorites;
    public $search;

    public function attributeLabels(): array
    {
        return [
            'categories' => 'Категории',
            'vacant' => 'Сейчас свободен',
            'online' => 'Сейчас онлайн',
            'hasFeedback' => 'Есть отзывы',
            'inFavorites' => 'В избранном',
            'search' => 'Поиск по имени',
        ];
    }

    public function rules(): array
    {
        return [
            [['categories', 'vacant', 'online', 'hasFeedback', 'inFavorites', 'search', 'sort'], 'safe'],
        ];
    }

}
