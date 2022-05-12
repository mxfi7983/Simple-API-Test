<?php

namespace common\models;

/**
 * This is the model class for table "photo".
 *
 * @property int $id
 * @property int $album_id
 * @property string|null $title
 * @property string $url
 *
 * @property Album $album
 */
class Photo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'photo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['album_id'], 'required'],
            [['album_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['album_id'], 'exist', 'skipOnError' => true, 'targetClass' => Album::class, 'targetAttribute' => ['album_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'album_id' => 'Album ID',
            'title' => 'Title',
        ];
    }

    /**
     * Gets query for [[Album]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlbum()
    {
        return $this->hasOne(Album::class, ['id' => 'album_id']);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $imgId = $this->getRandomImgId();
        return "/img/img{$imgId}.png";
    }

    private function getRandomImgId()
    {
        return 1 + $this->id % 10;
    }
}
