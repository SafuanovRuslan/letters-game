<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Game;
use app\models\Games;
use DateTime;

class GameController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionCheckAnswer()
    {
        $words = file_get_contents(__DIR__ . '/../assets/words.json');
        $words = json_decode($words);

        if (in_array($_GET['word'], $words)) {
            return '{"result": "OK"}';
        } else {
            return '{"result": null}';
        }
    }

    public function actionCheck()
    {
        $words = file_get_contents(__DIR__ . '/../assets/words.json');
        $words = json_decode($words);

        if (in_array($_GET['word'], $words)) {

            $answer = Yii::$app->request->get('word');
            $key    = Yii::$app->request->get('key');
            
            $result = Game::find()->where(['url' => $key])->one();

            var_dump($result);

            for ($i = 1; $i < 7; $i++) {
                $attempt = "attempt_$i";
                if (!$result->$attempt) {
                    $result->$attempt = $answer;
                    $result->save();
                    break;
                }
            }

            return '{"result": "OK"}';
        } else {
            return '{"result": null}';
        }
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionContinueGame()
    {
        $key = Yii::$app->request->post('key');
        $result = Game::find()->where(['url' => $key])->asArray()->one();
        // unset($result['answer']);

        if ($result) {
            return json_encode(['result' => $result], JSON_UNESCAPED_UNICODE);
        } else {
            return '{"result": null}';
        }
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionStartGame()
    {
        $answer = Yii::$app->request->post('answer');
        $game = new Game();
        return $game->startGame($answer);
    }

}
