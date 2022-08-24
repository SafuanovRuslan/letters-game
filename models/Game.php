<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * ContactForm is the model behind the contact form.
 */
class Game extends ActiveRecord
{
    /**
     * @return array the validation rules.
     */
    public function game()
    {
        return '{{game}}';
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function startGame($answer)
    {
        $words = file_get_contents(__DIR__ . '/../assets/words.json');
        $words = json_decode($words);

        if ($answer) {
            if (in_array($answer, $words)) {
                return $this->saveGame($answer);
            } else {
                return '{"result": null}';
            }
        } else {
            $count = count($words);
            $id = rand(0, $count - 1);
            $answer = $words[$id];
        
            return $this->saveGame($answer);
        }
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function saveGame($answer)
    {
        $this->answer     = $answer;
        $this->created_at = time();
        $this->url        = md5($answer . time());
        $this->save();

        $response = [
            'url' => "https://letters-game.ru/index.php?r=game/index&key=$this->url",   
        ];
        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }
}
