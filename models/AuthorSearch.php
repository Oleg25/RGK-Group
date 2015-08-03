<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Author;

/**
 * AuthorSearch represents the model behind the search form about `app\models\Author`.
 */
class AuthorSearch extends Author
{
    public $fullName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['firstname', 'lastname'], 'safe'],
            [['fullName'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Author::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'id',
                'fullName' => [
                    'asc' => ['firstname' => SORT_ASC, 'lastname' => SORT_ASC],
                    'desc' => ['firstname' => SORT_DESC, 'lastname' => SORT_DESC],
                    'label' => 'Автор',
                    'default' => SORT_ASC
                ],
                'author_id'
            ]
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->addCondition($query, 'id');
        $this->addCondition($query, 'firstname', true);
        $this->addCondition($query, 'lastname', true);
        $this->addCondition($query, 'author_id');

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        //$query->andFilterWhere(['like', 'firstname', $this->firstname])
          // ->andFilterWhere(['like', 'lastname', $this->lastname]);

        $query->andWhere('firstname LIKE "%' . $this->fullName . '%" ' .
            'OR lastname LIKE "%' . $this->fullName . '%"'
        );


        return $dataProvider;
    }
}
