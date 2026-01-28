<?php

namespace app\controllers;

use app\models\Author;
use app\models\Book;
use app\models\UserSubscriptions;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AuthorController extends Controller
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

    public function actionTop(int $year = null): string
    {
        $years = 
            Yii::$app->db->createCommand("
        SELECT DISTINCT year 
        FROM book 
        ORDER BY year DESC
            ")->queryColumn();

        if ($year === null && !empty($years))
        {
            $year = max($years);
        }

        $topAuthors = Yii::$app->db->createCommand("
            SELECT 
                COUNT(*) AS books_count, 
                ab.author_id, 
                CONCAT(a.last_name, ' ', a.first_name, ' ', IFNULL(a.middle_name, '')) AS full_name
            FROM author a
            INNER JOIN author_book ab ON a.id = ab.author_id
            INNER JOIN book b ON b.id = ab.book_id
            WHERE year = :year
            GROUP BY ab.author_id
            ORDER BY books_count DESC, full_name ASC
            LIMIT 10
        ")->bindValue(':year', $year)
            ->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $topAuthors,
            'pagination' => false,
            'sort' => [
                'attributes' => ['books_count', 'full_name'],
            ],
        ]);

        return $this->render('topAuthor', [
            'dataProvider' => $dataProvider,
            'years' => $years,
            'selectedYear' => $year,
        ]);
    }

    public function actionSubscription(): Response|string
    {
        $model = new UserSubscriptions();

        if ($model->load(Yii::$app->request->post()))
        {
            $model->user_id = Yii::$app->user->id;

            if ($model->save())
            {
                Yii::$app->session->setFlash('success', 'Подписка успешно создана.');

                $this->sendSmsNotification($model);
                
                return $this->refresh();
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => UserSubscriptions::find()->with(['user', 'author']),
            'pagination' => ['pageSize' => 20],
        ]);

        return $this->render('userSubscriptions', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionList(): string
    {
        $this->checkAdmin();

        $dataProvider = new ActiveDataProvider([
            'query' => Author::find(),
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

    public function actionUpdate(string $id)
    {
        $this->checkAdmin();

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) )
        {
            $model->save();

            return $this->redirect(['list']);
        }

        return $this->render('update', [
            'titlePrefix' => 'Редактирование',
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $this->checkAdmin();

        $model = new Author();

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['list']);
        }

        return $this->render('update', [
            'titlePrefix' => 'Создание',
            'model' => $model,
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

    protected function findModel(string $id): Author
    {
        if (($model = Author::findOne($id)) !== null)
        {
            return $model;
        }

        throw new NotFoundHttpException('Автор не найден');
    }

    protected function sendSmsNotification(UserSubscriptions $subscription)
    {
        // Используем эмулятор SmsPilot для теста
        $apiKey = 'XXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZXXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZ';
        $phone = $subscription->user->phone;

        $authorName = $subscription->author->fullName ?? 'автора';

        $message = "Вы подписались на уведомления о поступлении книг от {$authorName}.";

        // Формируем URL для запроса к SmsPilot
        $url = 'https://smspilot.ru/api.php'
            .'?send='.urlencode( $message )
            .'&to='.urlencode( $phone )
            .'&from='.'INFORM'
            .'&apikey='.$apiKey
            .'&format=json';

        // Отправляем запрос
        $response = file_get_contents($url);

        $j = json_decode( $response );

        if ( !isset($j->error))
        {
            Yii::info('SMS response: ' . $response, 'sms');
        }
        else
        {
            Yii::error('Ошибка отправки SMS: ' . $response, 'sms');
        }
    }
}