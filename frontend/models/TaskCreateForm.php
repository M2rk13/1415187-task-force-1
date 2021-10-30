<?php


namespace frontend\models;


use app\models\{Category, Task, TaskFile};
use Faker\Provider\Uuid;
use yii\base\Model;
use yii\db\Exception;
use yii\web\UploadedFile;

class TaskCreateForm extends Model
{

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $description;
    /**
     * @var integer
     */
    public $category;
    /**
     * @var string
     */
    public $files;
    /**
     * @var null|integer
     */
    public $price;
    /**
     * @var null|string
     */
    public $expire;
    /**
     * @var null|string
     */
    public $address;

    public const REQUIRED_MESSAGE = 'поле должно быть заполнено.';
    public const INT_MESSAGE = 'введите целое число.';
    public const MIN_LENGTH_MESSAGE = 'минимальное количество символов в боле должно быть: ';
    public const MIN_MESSAGE_LENGTH = 30;
    public const MIN_ADDRESS_LENGTH = 10;
    public const MIN_TITLE_LENGTH = 10;

    public function rules()
    {
        return [
            [['title'], 'required', 'message' => "{$this->attributeLabels()['title']}: " . self::REQUIRED_MESSAGE],
            [['description'], 'required', 'message' => "{$this->attributeLabels()['description']}: " . self::REQUIRED_MESSAGE],
            [['category'], 'required', 'message' => "{$this->attributeLabels()['category']}: " . self::REQUIRED_MESSAGE],
            [['price'], 'integer',  'message' => "{$this->attributeLabels()['price']}" . self::INT_MESSAGE],
            [['category'], 'integer',  'message' => "{$this->attributeLabels()['category']}" . self::INT_MESSAGE],
            [['description'], 'string', 'min' => self::MIN_MESSAGE_LENGTH, 'message' => "{$this->attributeLabels()['description']}" . self::MIN_LENGTH_MESSAGE . self::MIN_MESSAGE_LENGTH],
            [['title'], 'string', 'min' => self::MIN_TITLE_LENGTH, 'message' => "{$this->attributeLabels()['title']}" . self::MIN_LENGTH_MESSAGE . self::MIN_TITLE_LENGTH],
            [['address'], 'string', 'min' => self::MIN_ADDRESS_LENGTH, 'message' => "{$this->attributeLabels()['category']}" . self::MIN_LENGTH_MESSAGE . self::MIN_ADDRESS_LENGTH],
            [['title', 'description', 'price', 'category', 'expire', 'location', 'files', 'address'], 'safe'],
            [['category'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category' => 'id']],
            ['price', 'number', 'min' => '0']
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Мне нужно',
            'description' => 'Подробности задания',
            'price' => 'Бюджет',
            'location' => 'Локация',
            'expire' => 'Сроки исполнения',
            'address' => 'Адрес',
            'category' => 'Категория',
            'files' => 'Файлы',
        ];
    }

    /**
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function create()
    {
        $transaction = \Yii::$app->db->beginTransaction();

        $task = new Task();

        $task->customer_id = \Yii::$app->user->getId();
        $task->name = $this->title;
        $task->description = $this->description;
        $task->category_id = $this->category;
        $task->price = $this->price;

        if ($this->expire) {
            $task->expire = (new \DateTime($this->expire))->format('Y-m-d');
        }

        if (!$task->save()) {
            $transaction->rollBack();
            throw new Exception('Произошла ошибка при сохранении задания!');
        }

        $files = UploadedFile::getInstancesByName('files');

        foreach ($files as $file) {
            $newFileName = Uuid::uuid();
            $filePath = \Yii::getAlias('@webroot/uploads/') . $newFileName;
            $file->saveAs($filePath);

            $fileTask = new TaskFile();
            $fileTask->task_id = $task->id;
            $fileTask->filepath = $filePath;

            if (!$fileTask->save()) {
                $transaction->rollBack();
                throw new Exception('Произошла ошибка при сохранении файлов!');
            }
        }

        $transaction->commit();

        return $task;
    }

}