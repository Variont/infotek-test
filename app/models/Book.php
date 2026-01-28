<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property int|null $year
 * @property string|null $description
 * @property string|null $isbn
 * @property string|null $cover
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property AuthorBook[] $authorBooks
 * @property Author[] $authors
 */
class Book extends ActiveRecord
{
    public $authorIds = null;
    public $coverFile;

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
            [['year', 'description', 'isbn', 'cover', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['title', 'isbn', 'year'], 'required'],
            [['year', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['title', 'cover'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 20],
            [['coverFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg, png, gif, webp'],
            [['isbn'], 'unique'],
            ['authorIds', 'safe'],
        ];
    }

    public function afterFind(): void
    {
        parent::afterFind();

        $this->authorIds = ArrayHelper::getColumn($this->authors, 'id');
    }

    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        AuthorBook::deleteAll(['book_id' => $this->id]);

        if (!empty($this->authorIds))
        {
            foreach ($this->authorIds as $authorId)
            {
                $ab = new AuthorBook();
                $ab->book_id = $this->id;
                $ab->author_id = $authorId;

                $ab->save(false);
            }
        }
    }

    public function beforeValidate(): bool
    {
        if ($this->authorIds === '')
        {
            $this->authorIds = null;
        }
        return parent::beforeValidate();
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => time(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'year' => 'Год выхода',
            'description' => 'Описание',
            'isbn' => 'Номер',
            'cover' => 'Обложка',
            'created_at' => 'Добавлена',
            'updated_at' => 'Обновлена',
        ];
    }

    /**
     * Gets query for [[AuthorBooks]].
     *
     * @return ActiveQuery
     */
    public function getAuthorBooks(): ActiveQuery
    {
        return $this->hasMany(AuthorBook::class, ['book_id' => 'id']);
    }

    /**
     * Gets query for [[Authors]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])->viaTable('author_book', ['book_id' => 'id']);
    }

    public function getAuthorsAsString(): string
    {
        $authors = $this->authors;
        $names = [];

        foreach ($authors as $author)
        {
            $names[] = Html::encode($author->getFullName());
        }

        return implode(', ', $names);
    }

    public function uploadCover(): bool
    {
        if ($this->coverFile) {
            $fileName = 'cover_' . $this->id . '.' . $this->coverFile->extension;
            $filePath = \Yii::getAlias('@webroot/uploads/covers/') . $fileName;

            if (!is_dir(dirname($filePath))) {
                mkdir(dirname($filePath), 0777, true);
            }

            if ($this->coverFile->saveAs($filePath)) {
                $this->cover = '/uploads/covers/' . $fileName;
                return true;
            }
        }
        return false;
    }
}
