<?php

namespace console\controllers;

use Yii;
use common\models\Photo;
use common\models\User;
use common\models\Album;
use yii\base\Exception;
use yii\console\Controller;
use yii\db\Transaction;

class SeedUsersController extends Controller
{
    const NEW_USERS_COUNT = 10;
    const ALBUMS_PER_USER = 10;
    const PHOTOS_PER_ALBUM = 10;

    public function actionIndex()
    {
        // get pass
        $masterPassword = $this->getPasswordFromFile();

        $transaction = new Transaction(['db' => Yii::$app->db]);
        $transaction->begin();

        try {
            // seed users
            $users = $this->createUsers($masterPassword);
            // seed albums
            $albums = $this->createAlbums($users);
            // seed photos
            $photos = $this->createPhotos($albums);
        } catch (\Throwable $e) {
            $transaction->rollBack();
            echo $e->getMessage();
            exit;
        }

        $transaction->commit();

        echo 'Successfully created '
            . count($users)  . ' users, '
            . count($albums) . ' albums, '
            . count($photos) . ' photos' . "\n\n";
    }

    protected function createUsers(string $password): array
    {
        $ret = [];

        for ($i = 0; $i < self::NEW_USERS_COUNT; $i++) {
            $id = $this->getNextId(User::class);
            $user = new User;
            $user->id = $id;
            $user->username = "user_{$id}";
            $user->email = "user_{$id}@tt.tt";
            $user->password = $user->setPassword($password);
            $user->auth_key = "";
            $user->firstname = "User {$id}";
            $user->lastname = "";
            $user->status = User::STATUS_ACTIVE;
            if ($user->save()) {
                $ret[] = $user;
            } else {
                throw new Exception('Can\'t save user');
            }
        }

        return $ret;
    }

    /**
     * @param array $users
     * @return array
     * @throws Exception
     */
    protected function createAlbums(array $users): array
    {
        $ret = [];

        for ($j = 0; $j < count($users); $j++) {
            for ($i = 0; $i < self::ALBUMS_PER_USER; $i++) {
                $id = $this->getNextId(Album::class);
                $album = new Album;
                $album->id = $id;
                $album->user_id = $users[$j]->id;
                $album->title = "Album {$id}";
                if ($album->save()) {
                    $ret[] = $album;
                } else {
                    throw new Exception('Can\'t save album');
                }
            }
        }

        return $ret;
    }

    /**
     * @param array $albums
     * @return array
     * @throws Exception
     */
    protected function createPhotos(array $albums): array
    {
        $ret = [];

        for ($j = 0; $j < count($albums); $j++) {
            for ($i = 0; $i < self::PHOTOS_PER_ALBUM; $i++) {
                $id = $this->getNextId(Photo::class);
                $photo = new Photo;
                $photo->id = $id;
                $photo->album_id = $albums[$j]->id;
                $photo->title = "Photo {$id}";
                if ($photo->save()) {
                    $ret[] = $photo;
                } else {
                    throw new Exception('Can\'t save photo');
                }
            }
        }

        return $ret;
    }

    /**
     * @throws Exception
     */
    protected function getPasswordFromFile()
    {
        try {
            $masterPass = file(__DIR__ . '/password.txt')[0];
        } catch (\Throwable $e) {
            $masterPass = null;
        }

        if (strlen($masterPass) === 0)
            throw new Exception("Password file not found.\n Please, create file password.txt with password inside.\n\n");

        return $masterPass;
    }

    /**
     * @param string $className
     * @return int
     */
    protected function getNextId(string $className): int
    {
        return 1 + $className::find()
                ->select('id')
                ->orderBy(['id' => SORT_DESC])
                ->scalar();
    }
}
