<?php

namespace app\models;

use yii\db\Expression;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $password
 * @property string $date_add
 * @property string $date_activity Время последней активности на сайте
 * @property int $is_visible Показывает/скрывает профиль пользователя. Если пользователь заказчик - скрыть контакты со страницы пользователя. Если пользователь исполнитель - скрыть показ карточки со страницы исполнителей.
 * @property int|null $city_id Идентификатор города из таблицы городов
 * @property string|null $address Адрес пользователя
 * @property string|null $birthday
 * @property string|null $phone
 * @property string|null $skype
 * @property string|null $telegram
 * @property string|null $avatar
 * @property string|null $about
 * @property int $is_deleted
 *
 * @property City $city
 * @property Favorite[] $favoritesCustomer
 * @property Favorite[] $favoritesExecutor
 * @property Feedback[] $feedbacks
 * @property Message[] $messagesReceiver
 * @property Message[] $messagesSender
 * @property Opinion[] $opinionsCustomer
 * @property Opinion[] $opinionsExecutor
 * @property Opinion[] $opinionsExecutorRate
 * @property Portfolio[] $portfolios
 * @property Responce[] $responces
 * @property Specialisation[] $specialisations
 * @property Task[] $tasksUser
 * @property Task[] $tasksExecutor
 * @property UserNotification[] $userNotifications
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'name', 'password'], 'required'],
            [['date_add', 'date_activity', 'birthday'], 'safe'],
            [['is_visible', 'city_id', 'is_deleted'], 'integer'],
            [['about'], 'string'],
            [['email', 'name', 'avatar'], 'string', 'max' => 128],
            [['password', 'skype', 'telegram'], 'string', 'max' => 64],
            [['address'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'name' => Yii::t('app', 'Name'),
            'password' => Yii::t('app', 'Password'),
            'date_add' => Yii::t('app', 'Время последней активности на сайте'),
            'date_activity' => Yii::t('app', 'Date Activity'),
            'is_visible' => Yii::t('app', 'Показывает/скрывает профиль пользователя.
Если пользователь заказчик - скрыть контакты со страницы пользователя.
Если пользователь исполнитель - скрыть показ карточки со страницы исполнителей.'),
            'city_id' => Yii::t('app', 'Идентификатор города из таблицы городов'),
            'address' => Yii::t('app', 'Адрес пользователя'),
            'birthday' => Yii::t('app', 'Birthday'),
            'phone' => Yii::t('app', 'Phone'),
            'skype' => Yii::t('app', 'Skype'),
            'telegram' => Yii::t('app', 'Telegram'),
            'avatar' => Yii::t('app', 'Avatar'),
            'about' => Yii::t('app', 'About'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery|CityQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[FavoritesCustomer]].
     *
     * @return \yii\db\ActiveQuery|FavoriteQuery
     */
    public function getFavoritesCustomer()
    {
        return $this->hasMany(Favorite::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[FavoritesExecutor]].
     *
     * @return \yii\db\ActiveQuery|FavoriteQuery
     */
    public function getFavoritesExecutor()
    {
        return $this->hasMany(Favorite::className(), ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Feedbacks]].
     *
     * @return \yii\db\ActiveQuery|FeedbackQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::className(), ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[MessagesReceiver]].
     *
     * @return \yii\db\ActiveQuery|MessageQuery
     */
    public function getMessagesReceiver()
    {
        return $this->hasMany(Message::className(), ['receiver_id' => 'id']);
    }

    /**
     * Gets query for [[MessagesSender]].
     *
     * @return \yii\db\ActiveQuery|MessageQuery
     */
    public function getMessagesSender()
    {
        return $this->hasMany(Message::className(), ['sender_id' => 'id']);
    }

    /**
     * Gets query for [[OpinionsCustomer]].
     *
     * @return \yii\db\ActiveQuery|OpinionQuery
     */
    public function getOpinionsCustomer()
    {
        return $this->hasMany(Opinion::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[OpinionsExecutor]].
     *
     * @return \yii\db\ActiveQuery|OpinionQuery
     */
    public function getOpinionsExecutor()
    {
        return $this->hasMany(Opinion::className(), ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[OpinionsExecutorRate]].
     *
     * @return array|\yii\db\ActiveRecord
     * @throws \yii\db\Exception
     */
    public function getOpinionsExecutorRate()
    {
        $calcRateQuery = new \yii\db\Expression('sum(rate) / count(rate)');

        return $this->hasMany(Opinion::className(), ['executor_id' => 'id'])
            ->select(['rating' => $calcRateQuery])
            ->createCommand()
            ->queryOne();
    }

    /**
     * Gets query for [[ExecutorOpinionsAndFeedbacks]].
     *
     * @return Query
     * @throws \yii\db\Exception
     */
    public static function getExecutorOpinionsAndFeedbacks($id, $pagination = null): Query
    {
        $opinionsQuery = (new Query())
            ->select(
                [
                    'o.customer_id',
                    'task_id' => new Expression('null'),
                    'o.executor_id',
                    'o.rate',
                    'o.created_at',
                    'o.description'
                ]
            )
            ->from(['o' => Opinion::tableName()])
            ->where(['o.executor_id' => $id]);

        $feedbacksQuery = (new Query())
            ->select(
                [
                    'customer_id' => 'u.id',
                    'f.task_id',
                    'f.executor_id',
                    'f.rate',
                    'f.created_at',
                    'f.description'
                ]
            )
            ->from(['f' => Feedback::tableName()])
            ->innerJoin(['t' => Task::tableName()], 't.id = f.task_id')
            ->innerJoin(['u' => User::tableName()], 'u.id = t.customer_id')
            ->where(['f.executor_id' => $id]);

        $comments = (new Query())
            ->from($feedbacksQuery->union($opinionsQuery))
            ->orderBy(['created_at' => SORT_DESC]);

        if ($pagination) {
            $comments
                ->offset($pagination->offset)
                ->limit($pagination->limit);
        }

        return $comments;
    }

    /**
     * Gets query for [[OpinionsExecutorRate]].
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getAllExecutorRate($id): array
    {
        $allFeedbackQuery = self::getExecutorOpinionsAndFeedbacks($id);
        $rateQuery = new Expression('coalesce(sum(rate), 0) / coalesce(nullif(count(rate), 0), 1)');
        $countQuery = new Expression('count(rate)');

        return (new Query())
            ->select(
                [
                    'rate'  => $rateQuery,
                    'count' => $countQuery
                ]
            )
            ->from(['temp_table' => $allFeedbackQuery])
            ->createCommand()
            ->queryOne();
    }

    /**
     * Gets query for [[Portfolios]].
     *
     * @return \yii\db\ActiveQuery|PortfolioQuery
     */
    public function getPortfolios($pagination = null)
    {
        $portfolios =  $this->hasMany(Portfolio::className(), ['user_id' => 'id']);

        if ($pagination) {
            $portfolios
                ->offset($pagination->offset)
                ->limit($pagination->limit);
        }

        return $portfolios;
    }

    /**
     * Gets query for [[Responces]].
     *
     * @return \yii\db\ActiveQuery|ResponceQuery
     */
    public function getResponces()
    {
        return $this->hasMany(Responce::className(), ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Specialisations]].
     *
     * @return \yii\db\ActiveQuery|SpecialisationQuery
     */
    public function getSpecialisations()
    {
        return $this->hasMany(Specialisation::className(), ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[TasksUser]].
     *
     * @return \yii\db\ActiveQuery|TaskQuery
     */
    public function getTasksCustomer()
    {
        return $this->hasMany(Task::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[TasksExecutor]].
     *
     * @return \yii\db\ActiveQuery|TaskQuery
     */
    public function getTasksExecutor()
    {
        return $this->hasMany(Task::className(), ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[UserNotifications]].
     *
     * @return \yii\db\ActiveQuery|UserNotificationQuery
     */
    public function getUserNotifications()
    {
        return $this->hasMany(UserNotification::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Specialisation]].
     *
     * @return \yii\db\ActiveQuery|SpecialisationQuery
     */
    public function getSpecialisation()
    {
        return $this->hasMany(Specialisation::className(), ['executor_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * Список завершенных заданий исполнителя
     *
     * @return array
     */
    public function getCompletedTasksExecutor()
    {
        return $this->getTasksExecutor()
            ->innerJoin(['s' => Status::tableName()], 's.`id` = `task`.status_id')
            ->where(['s.`name`' => Status::STATUS_DONE])
            ->all();
    }

    /**
     * Список исполнителей
     *
     * @return UserQuery
     */
    public static function getExecutors($filters = null): UserQuery
    {
        $users = self::find()
            ->innerJoin(['s' => Specialisation::tableName()], 's.executor_id = `user`.id')
            ->joinWith('opinionsExecutor')
            ->andFilterWhere(['s.category_id' => $filters->categories ?? null])
            ->andFilterWhere(['like', 'name', $filters->search ?? null])
            ->groupBy('`user`.id')
            ->orderBy(['date_add' => SORT_DESC]);

        if ($filters->vacant ?? null) {
            $users->JoinWith('tasksExecutor')
                ->andWhere(['or', ['task.id' => null], ['task.status_id' => Status::STATUS_DONE]]);
        }

        if ($filters->online ?? null) {
            $onlineExpression = new Expression('now() - interval 5 minute');
            $users->where(['>', '`user`.date_activity', $onlineExpression]);
        }

        if ($filters->hasFeedback ?? null) {
            $users->joinWith('opinionsExecutor');
            $users->andWhere(['is not', 'opinion.customer_id', null]);
        }

        if ($filters->inFavorites ?? null) {
            $users->joinWith('favoritesExecutor');
            $users->andWhere(['is not', 'favorite.customer_id', null]);
        }

        return $users;
    }

    /**
     * Исполнитель по id
     *
     * @return UserQuery
     */
    public static function getExecutor($filters = null): UserQuery
    {
        $users = self::find()
            ->innerJoin(['s' => Specialisation::tableName()], 's.executor_id = `user`.id')
            ->joinWith('opinionsExecutor')
            ->andFilterWhere(['s.category_id' => $filters->categories ?? null])
            ->andFilterWhere(['like', 'name', $filters->search ?? null])
            ->groupBy('`user`.id')
            ->orderBy(['date_add' => SORT_DESC]);

        if ($filters->vacant ?? null) {
            $users->JoinWith('tasksExecutor')
                ->andWhere(['or', ['task.id' => null], ['task.status_id' => Status::STATUS_DONE]]);
        }

        if ($filters->online ?? null) {
            $onlineExpression = new Expression('now() - interval 5 minute');
            $users->where(['>', '`user`.date_activity', $onlineExpression]);
        }

        if ($filters->hasFeedback ?? null) {
            $users->joinWith('opinionsExecutor');
            $users->andWhere(['is not', 'opinion.customer_id', null]);
        }

        if ($filters->inFavorites ?? null) {
            $users->joinWith('favoritesExecutor');
            $users->andWhere(['is not', 'favorite.customer_id', null]);
        }

        return $users;
    }
}
