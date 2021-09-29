<?php

namespace app\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $name Заголовок задания
 * @property string $description Текст задания
 * @property int $category_id Идентификатор категории из таблицы типов категорий
 * @property int $status_id Идентификатор статуса из таблицы статусов заданий
 * @property float $price Цена заказчика
 * @property int $customer_id Идентификатор заказчика из таблицы пользователей
 * @property string $date_add
 * @property int|null $executor_id Идентификатор исполнителя из таблицы пользователей
 * @property string|null $address
 * @property int|null $city_id Идентификатор города из таблицы городов
 * @property string|null $expire Срок исполнения задания
 * @property string|null $address_comment
 *
 * @property Category $category
 * @property City $city
 * @property User $customer
 * @property User $executor
 * @property Feedback[] $feedbacks
 * @property Message[] $messages
 * @property Responce[] $responces
 * @property Status $status
 * @property TaskFile[] $taskFiles
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'category_id', 'status_id', 'price', 'customer_id'], 'required'],
            [['description'], 'string'],
            [['category_id', 'status_id', 'customer_id', 'executor_id', 'city_id'], 'integer'],
            [['price'], 'number'],
            [['date_add', 'expire'], 'safe'],
            [['name'], 'string', 'max' => 128],
            [['address', 'address_comment'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['executor_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Заголовок задания'),
            'description' => Yii::t('app', 'Текст задания'),
            'category_id' => Yii::t('app', 'Идентификатор категории из таблицы типов категорий'),
            'status_id' => Yii::t('app', 'Идентификатор статуса из таблицы статусов заданий'),
            'price' => Yii::t('app', 'Цена заказчика'),
            'customer_id' => Yii::t('app', 'Идентификатор заказчика из таблицы пользователей'),
            'date_add' => Yii::t('app', 'Date Add'),
            'executor_id' => Yii::t('app', 'Идентификатор исполнителя из таблицы пользователей'),
            'address' => Yii::t('app', 'Адресс'),
            'city_id' => Yii::t('app', 'Идентификатор города из таблицы городов'),
            'expire' => Yii::t('app', 'Срок исполнения задания'),
            'address_comment' => Yii::t('app', 'Комментарий к адресу'),
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
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
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::className(), ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::className(), ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Feedbacks]].
     *
     * @return \yii\db\ActiveQuery|FeedbackQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery|MessageQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Responces]].
     *
     * @return \yii\db\ActiveQuery|ResponceQuery
     */
    public function getResponces()
    {
        return $this->hasMany(Responce::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery|StatusQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    /**
     * Gets query for [[TaskFiles]].
     *
     * @return \yii\db\ActiveQuery|TaskFileQuery
     */
    public function getTaskFiles()
    {
        return $this->hasMany(TaskFile::className(), ['task_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return TaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskQuery(get_called_class());
    }

    /**
     * Список новых заданий
     *
     * @return TaskQuery
     */
    public static function getNewTasks($filters, $pagination = null)
    {
        $filters->period = !empty($filters->period) ? $filters->period : '100 year';
        $dateFilter = new Expression('now() - interval ' . $filters->period);

        $tasks = self::find()
            ->innerJoin(['s' => Status::tableName()], 's.id = `task`.status_id')
            ->orderBy('date_add')
            ->where(
                [
                    'and',
                    ['s.`name`' => Status::STATUS_NEW],
                    ['>', 'task.date_add', $dateFilter]
                ]
            )
            ->andFilterWhere(['task.category_id' => $filters->categories])
            ->andFilterWhere(['like', 'task.name', $filters->search]);

        if ($filters->remoteWork) {
            $tasks
                ->andWhere(['task.city_id' => null]);
        }

        if ($filters->noExecutor) {
            $tasks
                ->andWhere(['task.executor_id' => null]);
        }

        if ($pagination) {
            $tasks
                ->offset($pagination->offset)
                ->limit($pagination->limit);
        }

        return $tasks;
    }
}
