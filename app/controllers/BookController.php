<?php

namespace app\controllers;

use app\models\Author;
use app\models\Book;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class BookController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView(string $id): string
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionList(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Book::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ],
        ]);
        return $this->render('list', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(): Response|string
    {
        $this->checkAdmin();

        $model = new Book();

        $authorsList = ArrayHelper::map(
            Author::find()->orderBy(['last_name' => SORT_ASC])->all(),
            'id',
            fn($author) => $author->getFullName()
        );

        if ($model->load(Yii::$app->request->post()))
        {
            $model->coverFile = UploadedFile::getInstance($model, 'coverFile');

            if ($model->save())
            {
                if ($model->coverFile)
                {
                    $model->uploadCover();
                    $model->save(false);
                }

                if (!empty($model->authorIds))
                {
                    $model->unlinkAll('authors', true);

                    foreach ($model->authorIds as $authorId)
                    {
                        $author = Author::findOne($authorId);

                        if ($author)
                        {
                            $model->link('authors', $author);
                        }
                    }
                }

                return $this->redirect(['view', 'id' => $model->primaryKey]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'titlePrefix' => 'Создание',
            'authorsList' => $authorsList,
        ]);
    }

    public function actionUpdate(string $id): Response|string
    {
        $this->checkAdmin();

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['list']);
        }

        $authorsList = ArrayHelper::map(
            Author::find()->orderBy(['last_name' => SORT_ASC])->all(),
            'id',
            fn($author) => $author->getFullName()
        );

        return $this->render('update', [
            'model' => $model,
            'titlePrefix' => 'Редактирование',
            'authorsList' => $authorsList,
        ]);
    }

    public function actionDelete(string $id): Response
    {
        $this->checkAdmin();

        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['list']);
    }

    protected function checkAdmin(): void
    {
        if (!Yii::$app->user->identity || Yii::$app->user->identity->role !== 'admin')
        {
            throw new ForbiddenHttpException('Доступ запрещён');
        }
    }

    protected function findModel(string $id): Book
    {
        if (($model = Book::findOne($id)) !== null)
        {
            return $model;
        }

        throw new NotFoundHttpException('Книга не найдена');
    }
}