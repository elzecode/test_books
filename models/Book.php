<?php

namespace app\models;

use app\common\behaviors\SmsNotification;
use app\common\interfaces\SmsNotification as SmsNotificationInterface;
use Yii;
use yii\base\Event;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $date_release
 * @property string|null|UploadedFile $cover_path
 * @property string|null $isbn
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AuthorBook[] $authorBooks
 * @property Author[] $authors
 */
class Book extends \yii\db\ActiveRecord implements SmsNotificationInterface
{
    const BOOK_COVER_STORAGE = '/book_covers';

    public function behaviors()
    {
        return [
            SmsNotification::class,
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('now()'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cover_path'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['title', 'date_release'], 'required'],
            [['description', 'isbn'], 'string'],
            [['date_release', 'created_at', 'updated_at', 'authors'], 'safe'],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'date_release' => 'Date Release',
            'cover_path' => 'Cover Path',
            'isbn' => 'Isbn',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[AuthorBooks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorBooks()
    {
        return $this->hasMany(AuthorBook::class, ['book_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->via('authorBooks');
    }

    /**
     * @param $authorIds[]
     * @return void
     */
    public function setAuthors(array $authorIds = []) {
        if ($authorIds) {
            $this->unlinkAll('authorBooks', true);
            foreach ($authorIds as $id) {
                $this->link('authors', Author::findOne($id));
            }
            $this->trigger(SmsNotification::EVENT_SEND_SMS_NOTIFICATION);
        }
    }

    public function beforeSave($insert)
    {
        if ($this->cover_path) {
            $this->cover_path->saveAs('@app/web/' . self::BOOK_COVER_STORAGE . '/' . $this->cover_path->name);
        } else {
            unset($this->cover_path);
        }
        return parent::beforeSave($insert);
    }

    /**
     * @return false|string
     */
    public function getCover() {
        if ($this->cover_path) {
            return Yii::getAlias(self::BOOK_COVER_STORAGE . '/' . $this->cover_path);
        }
        return false;
    }

    public function getPhoneNumbersForSms(): array
    {
        $phoneNumbers = [];

        foreach ($this->authors as $author) {
            foreach ($author->authorFollowers as $authorFollower) {
                $phoneNumbers[] = $authorFollower->follower_phone;
            }
        }

        return $phoneNumbers;
    }

    public function getTextForSms(): string
    {
        return 'Вышла новая книга ' . $this->title;
    }
}
